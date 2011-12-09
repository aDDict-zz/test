<?php

class GroupController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
      
      $sessionUser = new Zend_Session_Namespace('sessionUser');
      $groups = new Application_Model_Groups();
      echo Zend_Json::encode($groups->getAll($sessionUser));
      $this->_helper->viewRenderer->setNoRender(true);
      die();
    }

    public function showAction() {
      $params = $this->getRequest()->getParams();
      $id     = $params["stub"];
      $groups = new Application_Model_Groups();
      echo  Zend_Json::encode($groups->getGroup($id));
      $this->_helper->viewRenderer->setNoRender(true);
    }

    public function addAction() {
        
    }

    public function editAction() {
        
    }

    public function deleteAction() {
        
    }
}









