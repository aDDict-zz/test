<?php
class dependencies extends application {

	public $depArray;

	function __construct( $app, &$obj ){
		parent::__construct( $app, &$obj );
	}

	public function dependencies(){
		switch(  $this->main->urlParts[ 0 ] ){
			default:
				$this->depArray = array(
					"style" => "css",
				    "jquery" => "js",
					"mimox" => "js"
				);
			break;
		}
		$this->view = &getView::view();
		$this->content = $this->view->parse( "dependencies.tpl", $this->depArray );
	}
}

