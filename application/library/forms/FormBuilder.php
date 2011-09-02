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
  
}