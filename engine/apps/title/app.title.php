<?php
class title extends application {
	
	public $title;
	
	function __construct( $app, &$obj ){ 
		parent::__construct( $app, &$obj );
	}
	
	public function title(){ 
		$this->title = "<title> Mimox Test </title>";
		$this->content = $this->title; 
		return $this->content;
	}
}
?>