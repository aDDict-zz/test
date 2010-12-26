<?php
/**
* site config
*/     
$exp = explode( "www", $_SERVER[ "DOCUMENT_ROOT" ] ); 

define( APPLICATION_ROOT, $exp[ 0 ] );  
define( DOCUMENT_ROOT, $_SERVER[ "DOCUMENT_ROOT" ] . "/" );  
define( CLASSES, APPLICATION_ROOT . "classes/" ); 
define( APPS, APPLICATION_ROOT . "apps/" );
define( CONFIG, APPLICATION_ROOT . "config/" );
define( MODELS, APPLICATION_ROOT . "models/" );
define( TEMPLATES, APPLICATION_ROOT . "templates/" ); 
define( PAGES, APPLICATION_ROOT . "pages/" );
define( CACHE, APPLICATION_ROOT . "cache/" );
/** smarty */
define( SMARTY, APPLICATION_ROOT . "3rdParty/Smarty/" );
/** adodb */
define( ADODB, APPLICATION_ROOT . "3rdParty/adodb5/" );
/** css */
define( CSS, DOCUMENT_ROOT . "css/" );  
/** js */
define( JS, DOCUMENT_ROOT . "js/" );
/** img */
define( IMG, DOCUMENT_ROOT . "img/" ); 

/**
* db params
*/
define( MYSQL_HOST, "localhost" );
define( MYSQL_DATABASE, "mimox" );
define( MYSQL_USER, "root" );
define( MYSQL_PSW, "vvvvvv" );

$dbArray = array(
    "MYSQL_HOST" => "localhost",
    "MYSQL_DATABASE" => "mimox",
    "MYSQL_USER" => "root",
    "MYSQL_PSW" => "vvvvvv"
);