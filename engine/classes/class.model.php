<?php

class model{

    public $connection;
	public $result;

    function __construct( $model ){
        $this->init(  $model  );
    }

    public function init(  $model  ){
        require_once( ADODB . "adodb.inc.php" );
        $this->connection = &ADONewConnection( "mysql" );
		$this->connection->PConnect( MYSQL_HOST, MYSQL_USER, MYSQL_PSW, MYSQL_DATABASE );
		$this->connection->charSet = "utf8";
		$this->connection->Execute( "set names 'utf8'" );
        $this->$model();
    }
	public function query( $query, $queryType, $vars = array() ){
        switch( $queryType ){
			case "select":
				$res = array();
				$thisRes = array();
				$thisRes = $this->connection->Execute( $query, $vars );
				if( $thisRes ){
					while( !$thisRes->EOF ){
						$res[] = $thisRes->fields;
						$thisRes->MoveNext();
					}
					return $res;
				}
			break;
			case "query":
				 $this->connection->Execute( $query, $vars );
			break;
		}

	}
}

