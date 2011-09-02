<?php

class FormBuilder {
 
 public $cfg = array();
 protected $form;
 
  public function __construct($form) {
    $this->form = $form;
    $form .= "Form";
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
  
  protected function loginForm(){
    return array(
      "name"      => "loginForm",
      "elements"  => array(
          array(
            "tag" => "input",
            "type" => "text",
            "name" => "username",
            "value" => "",
            "label" => "user",
           ),
           array(
            "tag" => "input",
            "type" => "hidden",
            "name" => "{$this->form}token",
            "value" => $this->getHash(),
            "label" => "",
           ),
           array(
            "tag" => "input",
            "type" => "password",
            "name" => "password",
            "value" => "",
            "label" => "pass",
           ),
           array(
            "tag" => "input",
            "type" => "submit",
            "name" => "submit",
            "value" => "Go",
            "label" => "submit",
           )
      )
    );
  }
  
  protected function setHash(){
    $_SESSION["{$this->form}token"] = md5 ($this->form . rand(time(),true));
  }
  
  protected function getHash(){
    if(!isset($_SESSION["{$this->form}token"]))
      $this->setHash();
    
    return $_SESSION["{$this->form}token"];
  }
  
}