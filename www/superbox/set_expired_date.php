<?php
$from_offline_cgi=1;

$mpath = "/var/www/maxima_engine/www/www";

ini_set("soap.wsdl_cache_enabled", "0");
$hirek = new SoapClient("http://www.maxima.hu/soap/hirek.wsdl");

$maint_filename = "/var/www/maxima_engine/www/var/log/superbox/" . date("Y-m-d-H-i-s") . ".notok";

mx_log("");

require_once "$mpath/auth.php";  // this one connects to Maxima db, ie. we can refer to it without connection id as last connected

mx_log("setting expired superbox accounts to 0000-00-00 date");

$sql="select * from users_superbox where ui_lejarat<now() and ui_lejarat!='0000-00-00'";
$res=mysql_query($sql);
$updated = 0;
while ($k=mysql_fetch_array($res)) {
    $banner = "http://ad.hirekmedia.hu/ad.php?adslot=superbox_2";
    if (preg_match("/15/",$k["ui_nem"])) {
        $banner = "http://ad.hirekmedia.hu/ad.php?adslot=superbox_1";
    }
    $userdata = array(
         "mobilkorzet"=>$k["ui_mobilkorzet"],
         "mobilszam"=>$k["ui_mobilszam"],
         "felhasznalo"=>$k["ui_felhasznalo"],
         "lejarat"=>"0000-00-00",
         "banner_csoport"=>$banner
    );
    mx_log("$k[id] expired, calling soap::Synchronize " . $hirek->Synchronize("2e0919d6f12e9ed7221e4a36cd6a0e37",$userdata));
    $updated++;
}
mx_log("$updated users updated.","end");

function mx_log($log="",$type="log") {

    global $maint_filename;

    if (!empty($log)) {
        if ($fp = fopen($maint_filename,"a")) {
            fwrite($fp,"$log\n");
        }
    }
    if ($type == "end") {
        rename($maint_filename,preg_replace("/notok$/","ok",$maint_filename));
    }
}
?>
