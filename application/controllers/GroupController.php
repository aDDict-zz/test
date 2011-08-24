<?php

class GroupController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        $groups = new Application_Model_Groups();
        echo  Zend_Json::encode($groups->fetchAll()); 
        
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


}







