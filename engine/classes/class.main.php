<?php

class main{

	public $urlParts = array();
	public $error = array();
	public $page;

	function __construct(){
		$this->init();
	}

	public function init(){
		require_once( CONFIG . "functions.php" );
		session_start();
		$this->getUrlParts();
		$this->render();
	}

	public function getUrlParts(){
		$exp = explode( "/", $_SERVER[ "REQUEST_URI" ] );
		$this->urlParts = array_slice( $exp, 1 );
	}

	public function render(){
		$thisUrlPart = $this->urlParts[ 0 ];
		switch( $thisUrlPart ){
			case "":
				$this->page = new home( home, &$this );
			break;
			default:
				if( file_exists( PAGES . "page." . $thisUrlPart . ".php" ) ){
					$this->page = new $thisUrlPart( $thisUrlPart, &$this );
				} else {
					$this->page = new error404( error404, &$this );
				}
			break;
		}
	}
}

function __autoload( $class ){
	switch( $class ){
		case "page":
			require_once( CLASSES . "class.page.php" );
		break;
		case "db":
			require_once( CLASSES . "class.db.php" );
		break;
		case "getDb":
			require_once( CLASSES . "class.db.php" );
		break;
		case "model":
			require_once( CLASSES . "class.model.php" );
		break;
		case "application":
			require_once( CLASSES . "class.application.php" );
		break;
		case "view":
			require_once( CLASSES . "class.view.php" );
		break;
		case "getView":
			require_once( CLASSES . "class.view.php" );
		break;
		case "form":
			require_once( CLASSES . "class.form.php" );
		break;
		default:
			if( file_exists( APPS . $class . "/app." . $class . ".php" ) ){
				require_once( APPS . $class . "/app." . $class . ".php" );
			} else if( file_exists( MODELS . $class . ".php" ) ){
				require_once( MODELS . $class . ".php" );
			} else if( file_exists( PAGES . "page." . $class . ".php" ) ){
				require_once( PAGES . "page." . $class . ".php" );
			}
		break;
	}

}

