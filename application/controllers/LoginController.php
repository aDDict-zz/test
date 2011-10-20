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
    /*$sessionUser = new Zend_Session_Namespace('sessionUser');
    print_r( $sessionUser->profile );
    $auth = Zend_Auth::getInstance();
    print_r($auth->getIdentity());
    die( print_r( $sessionUser->username ) );*/
    
    $request  = $this->getRequest(); 
    
    if($request->getActionName() == "logout") {
      $this->logoutAction();
    }
    
    $auth = Zend_Auth::getInstance();
    if($auth->getIdentity() && $auth->getIdentity() != "") {
      echo Zend_Json::encode(array("username" => $auth->getIdentity()));
      die();
    }
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
    
    echo Zend_Json::encode(array("username" => $auth->getIdentity()));
  
    $this->_helper->viewRenderer->setNoRender(true);
  }
  
  public function logoutAction() {
    Zend_Session::namespaceUnset('sessionUser');
    Zend_Auth::getInstance()->clearIdentity();
    echo Zend_Json::encode(array("out" => 1));
    $this->_helper->viewRenderer->setNoRender(true);
    die();
  }
}