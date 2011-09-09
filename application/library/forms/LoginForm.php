<?php

class LoginForm extends FormBuilder{
  
  protected function login(){
    return array(
      "title"   => "Login",
      "action"  => WEB_ROOT."/login/process",
      "method"  => "post",
      "items"   => array(
        array(
          "xtype"       => 'textfield',
          "fieldLabel"  => 'username',
          "name"        => 'username'
        ),
        array(
          "xtype"       => 'textfield',
          "fieldLabel"  => 'password',
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
}



/*
    return array(
      "title"      => "loginForm",
      "action"    => "/login/process",
      "method"    => "post",
      "submit"    => "loginSubmit",
      "elements"  => array(
        array(
          "tag"   => "div",
          "cls"   => "formElementWrapper",
          "style" => "",
          "html"  => "",
          "cn"    => array(
            array(
              "tag"   => "label",
              "html"  => "user"
            ),
            array(
              "tag"   => "input",
              "id"    => "user",
              "type"  => "text",
              "cls"   => "formElement",
              "name"  => "username",
              "value" => ""
            )
          )
        ),
        array(
          "tag"   => "div",
          "cls"   => "formElementWrapper",
          "style" => "",
          "html"  => "",
          "cn"    => array(
            array(
              "tag"   => "label",
              "html"  => "password"
            ),
            array(
              "tag"   => "input",
              "id"    => "password",
              "type"  => "password",
              "cls"   => "formElement",
              "name"  => "password",
              "value" => ""
            )
          )
        ),
        array(
          "tag"   => "input",
          "type"  => "hidden",
          "name"  => "{$this->form}token",
          "value" => $this->getHash()
        ),
        array(
          "tag"   => "input",
          "type"  => "submit",
          "id"    => "loginSubmit",
          "name"  => "submit",
          "value" => "login"
        )
      )
    ); 
 */