<?php
class ajax extends page{
	
	function __construct( $class, &$obj ){
		parent::__construct( $class, &$obj );
	}
	public function ajax(){ 
		$this->applications = array(
			0 => "ajax"
		);
	
		//$this->templateSource = "";
	}
	
	
}
?>