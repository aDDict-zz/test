<?php
$from_offline_cgi=1;

$mpath = "../www";

require_once "$mpath/auth.php";  // this one connects to Maxima db, ie. we can refer to it without connection id as last connected

$sql="select * from users_superbox_update_error where unix_timestamp(now()) - unix_timestamp(date) < 360 and not fixed order by id";
$res=mysql_query($sql);
$errors = array();
while ($k=mysql_fetch_array($res)) {
    $errors[] = "date: $k[date], errcode: $k[errcode], step: $k[step], user_id:$k[user_id]";
}
if (count($errors)) {
    $from = "reports@maxima.hu";
    $to = array("toth.tamas@hirekmedia.hu","tbjanos@manufaktura.rs","osvath.peter@hirekmedia.hu");
    // $to = array("tbjanos@manufaktura.rs");
    $reply = "toth.tamas@hirekmedia.hu";
    $body = "Az elmult 6 percben gondok voltak az adatok frissitesevel es szinkronizalasaval:\n " . implode("\n",$errors) . "\n\n---------------------------------\nMaxima reports\n";
    foreach ($to as $report_email) {
        mail("$report_email","Superbox update hiba",$body,"From: $from\nReply-To: $reply\nContent-Type: text/plain;\n\tcharset=\"utf-8\"");
    }
}
?>
