<?php

class GroupController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        
    }

    public function addAction() {
        // action body
    }

    public function editAction() {
        // action body
    }

    public function deleteAction() {
        // action body
    }

    public function showAction() {
      $params = $this->getRequest()->getParams();
      $id     = $params["stub"];
      
      $groups = new Application_Model_Groups();
      echo  Zend_Json::encode($groups->getGroup($id)); 
        
        // disable the rendering of the view
      $this->_helper->viewRenderer->setNoRender(true);
    }


}









