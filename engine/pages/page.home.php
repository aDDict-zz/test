<?php
class home extends page{
	
	function __construct( $class, &$obj ){
		parent::__construct( $class, &$obj );
	}
	public function home(){ 
		$this->applications = array(
			/*0 => "DOCTYPE",*/
			/*1 => "META",*/
			2 => "dependencies",
			3 => "title",
			4 => "script",
			4 => "mimox",
			/*6 => "content",
			7 => "footer"
			8 => "menu"*/
		);
	
		$this->templateSource = "index.tpl";
	}
	
	
}
?>