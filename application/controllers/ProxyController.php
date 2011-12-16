<?php

class ProxyController extends Zend_Controller_Action {

    public function init() { 
        /* Initialize action controller here */
    }

    public function indexAction() {
      echo "WORX!: " . get_include_path();
      $this->_helper->viewRenderer->setNoRender(true);
      die();
    }

    public function showAction() {
    }

    public function addAction() {
        
    }

    public function editAction() {
        
    }

    public function deleteAction() {
        
    }
}









