<?php

class LangController extends Zend_Controller_Action
{

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
      echo Zend_Json::encode(array("data" => array("barosag" => "yes")));
      die();
    }


}

