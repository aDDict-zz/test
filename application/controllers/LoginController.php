<?php

class LoginController extends Zend_Controller_Action {
  
  public function indexAction() {  
    $form = new LoginForm("login");
    echo  $form->getJSONCfg();
    $this->_helper->viewRenderer->setNoRender(true);
  }

  public function getAuthAdapter(array $params) {
    return new Adapter($params);
  }
  
  public function preDispatch() {
    /*$auth = Zend_Auth::getInstance();
    print_r($auth->getIdentity());  
    die();*/
  }
  
  public function processAction() {
    
    $request  = $this->getRequest();
    $form     = new LoginForm("login");
    
    if (!$request->isPost()) {
        return $this->_helper->redirector('index');
    }

    if (!$form->isValid($request->getPost())) {
        $this->view->form = $form;
        return $this->render('index'); // re-render the login form
    }

    $adapter = $this->getAuthAdapter($request->getPost());
    $auth    = Zend_Auth::getInstance();
    $result  = $auth->authenticate($adapter);
    
    echo Zend_Json::encode($result->isValid());
  
    $this->_helper->viewRenderer->setNoRender(true);
  }
  
  public function logoutAction() {
    Zend_Auth::getInstance()->clearIdentity();
  }
}