<?php
require_once("WsSubscribe.php");

ini_set('soap.wsdl_cache_enabled', 0);

// the login service will most likely be somewhere outside the applicatiopn framework, so do the connect manually

global $HTTP_RAW_POST_DATA;

//create the server instantiation
$Server = new SoapServer('http://www.maxima.hu/wsdl/subscribe.wsdl');

//In order to avoid using PHP-SOAP's default no HTTP_RAW_POST_DATA fault,
//we will expressly return this fault for no request (eg from a browser)

if(!$HTTP_RAW_POST_DATA){
	$Server->fault('Client', 'Invalid Request');
	return;
}

$Server->setClass('WsSubscribe');
$Server->handle();
?>
