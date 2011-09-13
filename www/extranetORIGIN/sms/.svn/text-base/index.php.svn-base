<?
/*

[11:58:23] <Tóth Tamás> az a borsodis sms kuldo...
[11:58:31] <Tóth Tamás> 2 honapja kezdodott talan
[12:00:40] <Tóth Tamás> le kellene allitani kompletten...
 
 * */

exit;


include("../auth.php");

$log_filename = "/var/www/maxima_engine/www/var/log/smsverify/custom/" . date("Y-m-d-H-i-s-") . mt_rand(0,999) . ".notok";

$message_id = 1208; // A constant "message_id" for a custom sending for tracko
$group_id = 0;

$message = $_GET["message"];
$phone = $_GET["phone"];
$ip = $_SERVER["REMOTE_ADDR"];

$allowed_ips=array("192.168.250.254","93.92.248.121","86.101.232.13","91.82.187.2","212.24.178.215","91.83.231.4");

mx_log("Message: $message\nphone: $phone\nip: $ip");

if (!in_array($ip,$allowed_ips)) {
	print "-1";
    mx_log("Invalid ip");
    exit;
}
$phone = preg_replace("/[^0-9]/","",$phone);
if (preg_match("/^(?:36|0036|06)?([237]0)([0-9]{7})$/",$phone,$regs)) {
    $phone = "+36$regs[1]$regs[2]";
}
else {
	print "-2";
    mx_log("Invalid phone number");
    exit;
}
if (empty($message) || strlen($message)>160) {
	print "-3";
    mx_log("Message empty or too long");
    exit;
}

mysql_query("insert into sms_tracko (group_id,user_id,date,ui_mobil,sms_send_id,response_id,user_response_id) 
                             values ('$group_id','',now(),'$phone','$message_id','-1','-1')");
$tracko_id = mysql_insert_id();
if (empty($tracko_id)) {
	print "-4";
    mx_log("Could not add the message to the database");
    exit;
}

mx_log("tracko_id: $tracko_id");

$sms_message = "UserId: $tracko_id\nSMSId: $message_id\nDestinationAddress: $phone\nSenderId: 36303444119\nUserData:\n$message\n";
$spool_filename = "/var/www/maxima_engine/www/var/spool/dqueue/$group_id-$tracko_id-$message_id-" . time() . ".temp";

if (!$fp = fopen($spool_filename,"w")) {
    mx_log("Could not open $spool_filename");
	print "-4";
    exit;
}
if (!fwrite($fp,$sms_message)) {
    mx_log("Could not write to $spool_filename");
	print "-4";
    exit;
}
fclose($fp);

rename($spool_filename,preg_replace("/temp$/","sms",$spool_filename));

print "1";
mx_log("","end");

function mx_log($log="",$type="log") {

    global $log_filename;

    if (!empty($log)) {
        if ($fp = fopen($log_filename,"a")) {
            fwrite($fp,"$log\n");
        }
    }
    if ($type == "end") {
        rename($log_filename,preg_replace("/notok$/","ok",$log_filename));
    }
}
?>
