<?php

$subscribe_service = new SoapClient("http://office.manufaktura.rs/maxima2.0/wsdl/subscribe2.wsdl",array( 'wsdl_cache' => 0, 'trace' => 1));

//include "WsSubscribe.php";
//$subscribe_service = new WsSubscribe();

$group = "gamer";
$idname = "email";
$hidden = "yes";
$affiliate = 299;
$grouplist = array();
$values = array(
    array("email","tbjanos@manufaktura.rs"),
    array("keresztnev","Janos"),
    array("vezeteknev","Toth Bagi")
);

$result = $subscribe_service->UnSubscribe("gamer","tbjanos@manufaktura.rs");
//$result = $subscribe_service->Subscribe($group,$idname,$hidden,$affiliate,$grouplist,$values);


print "result: <br>";
print_r($result);
?>
