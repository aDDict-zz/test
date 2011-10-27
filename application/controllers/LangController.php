<?php

class LangController extends Zend_Controller_Action
{

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
      $params = $this->getRequest()->getParams();
      $lang   = new Application_Model_Lang();
      echo Zend_Json::encode($lang->getData($params));
      die();
    }


}

