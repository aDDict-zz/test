<?php
class page{

	public $content = "";
	public $applications = array();
	public $templateVar = array();
	public $templateSource;
	public $view;

	function __construct( $class, &$obj ){
		$this->init( $class, &$obj );
	}

	public function init( $class, &$obj ){
		$obj->page = $this;
		$this->$class();
		$this->setApplications( &$obj );
		if( $this->templateSource != null ) $this->out();
	}

	public function setApplications( &$obj ){
		foreach( $this->applications as $thisArray => $item ){
			$application = new $item( $item, &$obj );
			$this->templateVar[ $item ] = $application->content;
		}
	}

	public function out(){
		$this->view = &getView::view();
		$this->content = $this->view->parse( $this->templateSource, $this->templateVar );
	}

}

