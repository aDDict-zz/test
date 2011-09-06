<?php

class FormBuilder {
 
 public $cfg = array();
 protected $form;
 
  public function __construct($form) {
    $this->form = $form;
    if(method_exists($this, $form)) {
      $this->cfg = $this->$form();
    } else {
      //error
    }
  }
  
  public function getCfg(){
    return $this->cfg;
  }
  
  public function getJSONCfg(){
    return Zend_Json::encode($this->cfg); 
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