<?php

class LoginController extends Zend_Controller_Action {
  
  public function indexAction() {
    $form = new LoginForm("login");
    echo  $form->getJSONCfg();
    
    $this->_helper->viewRenderer->setNoRender(true);
  }

  public function getForm() {
    return new LoginForm(array(
      'action' => WEB_ROOT . "/login/process",
      'method' => 'post',
    ));
  }

  public function getAuthAdapter(array $params) {
    return new Adapter($params);
  }
  
  public function preDispatch() {
    if (Zend_Auth::getInstance()->hasIdentity()) {
      // If the user is logged in, we don't want to show the login form;
      // however, the logout action should still be available
      if ('logout' != $this->getRequest()->getActionName()) {
        $this->_helper->redirector('index', 'index');
      }
    } else {
      // If they aren't, they can't logout, so that action should
      // redirect to the login form
      if ('logout' == $this->getRequest()->getActionName()) {
        $this->_helper->redirector('index');
      }
    }
  }
  
  public function processAction() {
    $request = $this->getRequest();

    // Check if we have a POST request
    if (!$request->isPost()) {
        return $this->_helper->redirector('index');
    }

    // Get our form and validate it
    $form = $this->getForm();
    if (!$form->isValid($request->getPost())) {
        // Invalid entries
        $this->view->form = $form;
        return $this->render('index'); // re-render the login form
    }

    // Get our authentication adapter and check credentials
    $adapter = $this->getAuthAdapter($form->getValues());
    $auth    = Zend_Auth::getInstance();
    $result  = $auth->authenticate($adapter);
    if (!$result->isValid()) {
        // Invalid credentials
        $form->setDescription('Invalid credentials provided');
        
        //return $this->_helper->redirector('actionName', 'controllerName');
        //return $this->_helper->redirector('index', 'login');
        
        $this->view->form = $form;
        return $this->render('index');
    }

    // We're authenticated! Redirect to the home page
    $this->_helper->redirector('index', 'index');
  }
  
  public function logoutAction() {
    Zend_Auth::getInstance()->clearIdentity();
    $this->_helper->redirector('index'); // back to login page
  }
}