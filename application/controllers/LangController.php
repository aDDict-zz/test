<?php

class LangController extends Zend_Controller_Action
{

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
      $lang   = new Application_Model_Lang();
      echo Zend_Json::encode($lang->getData($this->getRequest()->getParams()));
      die();
    }
    
    public function updateAction() {
      $lang   = new Application_Model_Lang();
      die( print_r( $this->getRequest()->getParams() ) );
    }

    public function groupsAction() {
      $lang   = new Application_Model_Lang();
      echo Zend_Json::encode($lang->getGroups());
      die();
    }
    
    public function catsAction() {
      $lang   = new Application_Model_Lang();
      echo Zend_Json::encode($lang->getCats());
      die();
    }
    
    public function addcategoryAction() {
      $params = $this->getRequest()->getParams();
      $lang   = new Application_Model_Lang();
      $lang->addCategory($params['cat']);
      die();
    }
    
    public function deletecategoryAction() {
      $params = $this->getRequest()->getParams();
      $lang   = new Application_Model_Lang();
      $lang->deleteCategory($params['id']);
      die();
    }
    
    public function addlanguageAction() {
      $params = $this->getRequest()->getParams();
      $lang   = new Application_Model_Lang();
      $lang->addLanguage($params['lang']);
      die();
    }
    
    public function deletelanguageAction() {
      $params = $this->getRequest()->getParams();
      $lang   = new Application_Model_Lang();
      $lang->deleteLanguage($params['id']);
      die();
    }
}

