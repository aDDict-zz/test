<?php
require_once("WS_SB_Hirek.php");

ini_set('soap.wsdl_cache_enabled', 0);

global $HTTP_RAW_POST_DATA;

//create the server instantiation
$Server = new SoapServer('http://www.maxima.hu/soap/hirek.wsdl');

//In order to avoid using PHP-SOAP's default no HTTP_RAW_POST_DATA fault,
//we will expressly return this fault for no request (eg from a browser)

if(!$HTTP_RAW_POST_DATA){
	$Server->fault('Client', 'Invalid Request');
	return;
}

$Server->setClass('WS_SB_Hirek');
$Server->handle();
?>
