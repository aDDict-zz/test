<?php
class form{

	public $main;
	public $form = array();
	public $formElements = array();
	public $formID;
	public $content = "";

	function __construct( &$obj, $form ){
		$this->init( &$obj, $form );
	}

	public function init( &$obj, $form ){
		$this->main = &$obj;
		$this->form = $form;
		$this->render();
	}

	public function render(){
		$this->formID = (string)array_pop( array_keys( $this->form ) );
		$this->formElements = $this->form[ $this->formID ][ "elements" ];
		if( isset( $_REQUEST[ "formId" ] ) && $_REQUEST[ "formId" ] != "" ){
			$submit;
			$submit = $_REQUEST[ "formId" ] . "_submit";
			if( method_exists( $this->main, $submit ) ){
				$this->main->$submit();
			}
		}

		foreach ( $this->formElements as $key => $value ){
			$thisMethod = array_pop( array_keys( $this->formElements[ $key ] ) );
			if( method_exists( $this, $thisMethod ) ){
				$this->$thisMethod( $value[ $thisMethod ] );
			}
		}

		$this->getForm();
	}

	public function getForm(){
		$thisContent = "";
		switch( $this->form[ $this->formID ][ "isDom" ] ){
			case "true":
				$thisContent = "
				<form action='/{$this->form[ $this->formID ][ "action" ]}' accept-charset='{$this->form[ $this->formID ][ "charset" ]}' method='{$this->form[ $this->formID ][ "method" ]}' id='{$this->formID}'>
				";
				$thisContent .= "
				<input type='hidden' id='formId' name='formId' value='{$this->formID}' />
				";
				$thisContent .= $this->content . "</form>";
				$this->content = $thisContent;
			break;
			case "false":
				$thisContent = "
				<input type='hidden' id='formId' name='formId' value='{$this->formID}' />
				";
				$thisContent .= $this->content;
				$this->content = $thisContent;
			break;
		}
	}
	public function checkBox( $thisArray ){
		$this->content .= "
		<div id='edit-{$thisArray[ "id" ]}' class='form-item'>
			<label>{$thisArray[ "label" ]}:</label>
			<input id='{$thisArray[ "id" ]}' class='{$thisArray[ "class" ]}' type='checkbox' name='{$thisArray[ "name" ]}' rel='{$thisArray[ "rel" ]}' value='{$thisArray[ "value" ]}' checked='{$thisArray[ "checked" ]}'/>
		</div>
		";
	}
	public function text( $thisArray ){
		$this->content .= "
		<div id='edit-{$thisArray[ "id" ]}' class='form-item'>
			<label>{$thisArray[ "label" ]}:</label>
			<input type='text' name='{$thisArray[ "name" ]}' id='{$thisArray[ "id" ]}' value='{$thisArray[ "value" ]}' rel='{$thisArray[ "rel" ]}' class='{$thisArray[ "class" ]}' />
		</div>
		";
	}
	public function password( $thisArray ){
		$this->content .= "
		<div id='edit-{$thisArray[ "id" ]}' class='form-item'>
			<label>{$thisArray[ "label" ]}:</label>
			<input type='password' name='{$thisArray[ "name" ]}' id='{$thisArray[ "id" ]}' value='{$thisArray[ "value" ]}' rel='{$thisArray[ "rel" ]}' class='{$thisArray[ "class" ]}' />
		</div>
		";
	}
	public function button( $thisArray ){
		$this->content .= "
		<div id='edit-{$thisArray[ "id" ]}' class='form-item'>
			<input type='{$thisArray[ "type" ]}' name='{$thisArray[ "name" ]}' id='{$thisArray[ "id" ]}' value='{$thisArray[ "value" ]}' rel='{$thisArray[ "rel" ]}' class='{$thisArray[ "class" ]}' />
		</div>
		";
	}
	public function textarea( $thisArray ){
		$this->content .= "
		<div id='edit-{$thisArray[ "id" ]}' class='form-item'>
			<label>{$thisArray[ "label" ]}:</label>
			<textarea class='{$thisArray[ "class" ]}' name='{$thisArray[ "name" ]}' rel='{$thisArray[ "rel" ]}' id='{$thisArray[ "id" ]}'>{$thisArray[ "value" ]}</textarea>
		</div>
		";
	}
	public function select( $thisArray ){
		$this->content .= "
		<div id='edit-{$thisArray[ "id" ]}' class='form-item'>
			<label>{$thisArray[ "label" ]}:</label>
			<select class='{$thisArray[ "class" ]}' name='{$thisArray[ "name" ]}' rel='{$thisArray[ "rel" ]}' id='{$thisArray[ "id" ]}'>";

		foreach( $thisArray[ "options" ] as $key => $option ){
			if( $option == $thisArray[ "value" ] ){
				$this->content .= "<option value='{$key}' selected='selected'>{$option}</option>";
			} else {
				$this->content .= "<option value='{$key}'>{$option}</option>";
			}
		}

		$this->content .= "</select></div>";
	}
}
?>

