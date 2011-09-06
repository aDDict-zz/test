<?php

class LoginForm extends FormBuilder{
  
  protected function login(){
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
  
}

/*class LoginForm extends Zend_Form {
  
  public function init() {
    $username = $this->addElement('text', 'username', array(
      'filters'    => array('StringTrim', 'StringToLower'),
      'validators' => array(
        'Alpha',
        array('StringLength', false, array(3, 20)),
      ),
      'required'   => true,
      'label'      => 'Your username:',
    ));

    $password = $this->addElement('password', 'password', array(
      'filters'    => array('StringTrim'),
      'validators' => array(
        'Alnum',
        array('StringLength', false, array(6, 20)),
      ),
      'required'   => true,
      'label'      => 'Password:',
    ));

    $login = $this->addElement('submit', 'login', array(
      'required' => false,
      'ignore'   => true,
      'label'    => 'Login',
    ));

    // We want to display a 'failed authentication' message if necessary;
    // we'll do that with the form 'description', so we need to add that
    // decorator.
    $this->setDecorators(array(
      'FormElements',
      array('HtmlTag', array('tag' => 'dl', 'class' => 'zend_form')),
      array('Description', array('placement' => 'prepend')),
      'Form'
    ));
  }
}*/