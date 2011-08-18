<?php

$from_offline_cgi=1;

$mpath = "/var/www/maxima_engine/www/www";
require "$mpath/decode.php";
require "$mpath/auth.php";
require "$mpath/lang/$language/login.lang";
require "email_message.php";

$alert = array();

$users = array();
$res = mysql_query("select *,to_days(now())-to_days(password_modify) pw_mod_days from user where to_days(now())-to_days(password_modify)>23");
if ($res && mysql_num_rows($res)) {
    while ($row = mysql_fetch_array($res)) {
        $users[] = $row;
    }
}
foreach ($users as $row) {
    $abstract_login=$row["id"];
    $pw_mod_days=$row["pw_mod_days"];
    $pw_mod_ask=0;
    $pw_mod_admin=0;
    $memberships=0;
    $r2 = mysql_query("select * from members where user_id='$abstract_login' and membership!='sender'");
    if ($r2 && mysql_num_rows($r2)) {
        while ($k2=mysql_fetch_array($r2)) {
            $memberships++;          // (nem sender) tag valamelyik csoportban
            if ($k2["membership"]=="admin") {    // lehet hogy kell kerni a jelszovaltast
                $pw_mod_admin=1;
            }
            if (in_array($k2["membership"],array('owner','moderator','support'))) {     // biztosan kerni kell a jelszovaltast
                $pw_mod_ask=1;
            }
        }
    }
    $r2 = mysql_query("select * from multi_members where user_id='$abstract_login' and membership='affiliate'");
    if ($r2 && mysql_num_rows($r2)) {
        while ($k2=mysql_fetch_array($r2)) {
            $memberships++;          // a multi affiliate is belephet hogy megnezze a statisztikakat
        }
    }

    // ha maximum admin a felhasznalo, akkor meg kell nezni hogy van-e joga nem readonly oldalhoz
    if ($pw_mod_ask==0 && $pw_mod_admin==1) {
        $pw_mod_ask=1;
        $r2=mysql_query("select count(*) from page_user u,page p where u.user_id='$abstract_login' and u.page_id=p.id and p.readonly!='yes'");
        if ($r2 && mysql_num_rows($r2)) {
            $pw_mod_ask=mysql_result($r2,0,0);  // tehat ha csak readonly oldalakhoz ferhet hozza akkor nem kell kerni a jelszovaltast.
        }
    }
    $loginerr="";
    if ($pw_mod_ask && $pw_mod_days>30) {   // nem valtoztatta meg a jelszavat egy honapja, nem lephet be.
//        $loginerr="$word[login_expired]";
    }
    elseif ($memberships && $abstract_login) {
        if ($pw_mod_ask && $pw_mod_days>23) {   // figyelmeztetni kell hogy lejar a jelszava
            $expire_in=31-$pw_mod_days;
            $loginerr="$word[password_change_warn1] $expire_in $word[password_change_warn2]";
        }
    }

    if (!empty($loginerr)) {
        $alert[] = array($row["id"], $row["email"], $row["name"], $loginerr, $pw_mod_days);
    }
}

foreach ($alert as $a) {
    $email_message=new email_message_class;
    $email_message->default_charset = "utf-8";
    $subject = $word["password_change_reminder"]; 
    $email_message->SetMultipleEncodedEmailHeader("To",array($a[1]=>$a[2]));
    $email_message->SetEncodedEmailHeader("From","passwordchange@maxima.hu","Maxima.hu - $subject");
    $email_message->SetEncodedEmailHeader("Reply-To","marci@manufaktura.rs",$subject);
    $email_message->SetHeader("Sender","passwordchange@maxima.hu");
    $mail_bodytext = "$word[dear_user]<br><br>$a[3]<br><br>Maxima.hu";
    $email_message->AddHTMLPart($email_message->WrapText($mail_bodytext));
    $email_message->SetEncodedHeader("Subject",$subject);
    $error=$email_message->Send();

    print $error;
	//    echo "$a[1] ($a[4])\n";
}
?>
