<?php

class FormBuilder {
 
 public $hash = array();
 
  public function __construct($form) {
    $form .= "Form";
    if(method_exists($this, $form)) {
      $this->hash = $this->$form();
    } else {
      //error
    }
  }
  
  public function getHash(){
    return $this->hash;
  }
  
  protected function loginForm(){
    return array(
      "asdasd" => 'FOKK',
      "dsfdsf" => "ddd"
    );
  }
  
}