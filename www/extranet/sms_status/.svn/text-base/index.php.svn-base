<?
include("../auth.php");

$file = time().'.'.rand(1,1000);

$f=fopen($_MX_var->sms_status_log_dir . "/$file",'w');

foreach ($_GET as $key => $value) {
    fwrite($f, "$key => $value\n");
    if ($key == 'reference') {
	if (ereg("([0-9]*)0000([0-9]*)",$value, $ref)) {
		$mid    = $ref[1];
		$userid = $ref[2];
	}
    } elseif ($key == 'number') {
    	$phone = '+'.$value;
    } elseif ($key == 'code') {
        $code  = $value;
    } elseif ($key == 'message') {
        $message = $value;
    } elseif ($key == 'timestamp') {
        $date = $value;
    }
}
 
fclose($f);

mysql_query("set names cp1250"); // they send status in this encoding
mysql_query("insert into sms_status (sms_id,user_id,phone,status,code,date) values (
    '".mysql_escape_string($mid)."',
    '".mysql_escape_string($userid)."',
    '".mysql_escape_string($phone)."',
    '".mysql_escape_string($message)."',
    '".mysql_escape_string($code)."',
    '".mysql_escape_string($date)."')");

/*if ($code == 7) {
mysql_query("update users permission set ui_mobil='' where ui_mobil='".mysql_escape_string($phone)."'");
}*/



?>
