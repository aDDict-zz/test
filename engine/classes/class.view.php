<?php

require_once( SMARTY . "Smarty.class.php" );

class getView{
    public function &view(){
		static $obj;
        if ( !is_object( $obj ) ){
            $obj = new render();
        }
        return $obj;
    }
}

class render{

	public $smarty;
	public $content;

	function __construct(){
		$this->smarty = new Smarty();
		$this->smarty->template_dir = TEMPLATES;
		$this->smarty->compile_dir = SMARTY . 'templates_c';
		$this->smarty->cache_dir = SMARTY . 'cache';
		$this->smarty->config_dir = SMARTY . 'configs';
	}

	public function parse( $template, $vars = null ){
		if( $vars != null ) $this->smarty->assign( "var", $vars );
		$this->content = $this->smarty->fetch( $template );
		return $this->content;
	}
}

