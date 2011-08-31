<?php

class GroupController extends Zend_Controller_Action {

    /*$params = $this->getRequest()->getParams();
    $params = $this->getRequest()->getPost();
    echo $this->getRequest()->getPost("data");*/

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        $groups = new Application_Model_Groups();
        $ddd = $groups->getAll(); print_r($ddd);
        // disable the rendering of the view
        $this->_helper->viewRenderer->setNoRender(true);
    }

    public function addAction() {
        
    }

    public function editAction() {
        
    }

    public function deleteAction() {
        
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









