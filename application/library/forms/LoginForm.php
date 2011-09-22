<?php

class LoginForm extends FormBuilder{
  
  protected function login(){
    // bla
  }
  
  protected function createForm(){
    return array(
      "title"   => "Login",
      //"action"  => WEB_ROOT."/login/process",
      "action"  => "/login/process",
      "method"  => "post",
      "items"   => array(
        array(
          "xtype"       => 'textfield',
          "fieldLabel"  => 'username',
          "style"       => "padding:15px;padding-bottom:0px;padding-top:7px;",
          "name"        => 'username'
        ),
        array(
          "xtype"       => 'textfield',
          "inputType"   => 'password',
          "fieldLabel"  => 'password',
          "style"       => "padding:15px;padding-bottom:0px;padding-top:7px;",
          "name"        => 'password'
        ),
        array(
          "xtype"       => 'hidden',
          "fieldLabel"  => '',
          "name"        => "{$this->form}token",
          "value"       => $this->getHash()
        )
      )
    );
  }
  
  public function isValid($params){
    if(!isset($params["username"]) || !$params["username"] != "")
      return false;
    
    if(!isset($params["password"]) || !$params["password"] != "")
      return false;
    
    if(!isset($params["logintoken"]) || $params["logintoken"] != $_SESSION["logintoken"])
      return false;
    
    return true;
  }
  
}
