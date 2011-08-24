<?php

class TestController extends Zend_Controller_Action {

    public function init() {
       
    }

    public function indexAction() {
        $data = array(
          "elso" => array(
            "a1" => 1,
            "a2" => 2
          ),
          "masodik" => "2"
        );
        $this->view->data = Zend_Json::encode($data); 
        
        
        //echo  "sdfsdfsdf"; //Zend_Json::encode($data);  
 
        //$this->_helper->viewRenderer->setNoRender(true);
        
    }


}