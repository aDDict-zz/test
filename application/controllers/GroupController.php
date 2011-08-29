<?php

class GroupController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        //$params = $this->getRequest()->getParams();
        $params = $this->getRequest()->getPost();
        //echo  Zend_Json::encode($params);
        //print_r($this->getRequest()->getPost());
        echo $this->getRequest()->getPost("data");
        
        // disable the rendering of the view
        $this->_helper->viewRenderer->setNoRender(true);
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
      //echo  Zend_Json::encode($groups->fetchAll($id)); 
      echo  Zend_Json::encode($groups->getGroup($id)); 
      
      
      // disable the rendering of the view
      $this->_helper->viewRenderer->setNoRender(true);
    }
}









