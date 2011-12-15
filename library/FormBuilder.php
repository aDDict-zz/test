<?php

class FormBuilder {
 
 public $cfg = array();
 protected $form;
 
  public function __construct($form) {
    $this->form = $form;
    if(method_exists($this, $form)) {
      // bla $this->$form(); or sThing like this
    } else {
      //error
    }
  }
  
  public function getCfg(){
    return $this->cfg;
  }
  
  public function getJSONCfg(){
    $this->cfg = $this->createForm();
    return Zend_Json::encode($this->cfg,false,array('enableJsonExprFinder' => true)); 
  }
  
  protected function setHash(){
    $_SESSION["{$this->form}token"] = md5($this->form.rand(time(),true));
  }
  
  protected function getHash(){
    if(!isset($_SESSION["{$this->form}token"]))
      $this->setHash();
    
    return $_SESSION["{$this->form}token"];
  }
  
}