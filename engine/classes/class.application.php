<?php
class application{

	public $main;
    public $content = "";
    public $model;

    function __construct( $app, &$obj ){
	    $this->init( $app, &$obj );
    }

    public function init( $app, $obj ){
        $this->main = &$obj;
		$thisModel = "model" . $app;
        if( file_exists( MODELS . $thisModel . ".php" ) ){
            $this->model = new $thisModel( $app );
        }
        $this->$app( &$obj );
    }

}

