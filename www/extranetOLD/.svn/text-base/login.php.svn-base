<?
include_once "auth.php";
include_once "common.php";
$language=select_lang();
include $_MX_var->baseDir . "/lang/$language/login.lang";
$password=slasher($_POST["password"]);
$susername=slasher($_POST["username"]);
$abstract_login=0;
$ugyfelkapu_message="";

if (!empty($_MX_ugyfelkapu_request)) {
    $qpart="md5(concat(email,password))='$_MX_ugyfelkapu_request'";
}
else {
    $qpart="email='$susername' and binary password='$password'";
}
if (isset($_POST["public_login_source"]) && in_array($_POST["public_login_source"],array("/login.php","/"))) {
    $_MX_ugyfelkapu_request = "/public";
}

$res = mysql_query("select *,to_days(now())-to_days(password_modify) pw_mod_days from user where $qpart");
if ($res && mysql_num_rows($res)) {
    $row = mysql_fetch_array($res);
    $abstract_login=$row["id"];
    $pw_mod_days=$row["pw_mod_days"];
}
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
if ($pw_mod_ask && $pw_mod_days>30) {   // nem valtoztatta meg a jelszavat egy honapja, nem lephet be.
    if (!empty($_MX_ugyfelkapu_request)) {
        $ugyfelkapu_message=$word["login_expired"];
    }
    else {
        $loginerr="$word[login_expired]";
    }
    $abstract_login=0;
    logger('',0,'login',"le|:|".$susername."|:|"."******");
}
elseif ($memberships && $abstract_login) {
    mt_srand ((double) microtime() * 1000000);
    $randval = mt_rand();
    $hash=time().$REMOTE_ADDR.$randval.$abstract_login;
    //echo $hash;
    $unique_id = md5($hash);
    $params = $request_uri;
    setcookie("cunique_id",$unique_id,0,"/");
    setcookie("cuser_id",$abstract_login,0,"/");
    mysql_query("update user set unique_id = '$unique_id' where id='$abstract_login'");
    $login_exp_warn="";
    if ($pw_mod_ask && $pw_mod_days>23) {   // figyelmeztetni kell hogy lejar a jelszava
        $expire_in=31-$pw_mod_days;
        $login_exp_warn="$word[login_exp_warn1] $expire_in $word[login_exp_warn2]";
    }
    if (!empty($_MX_ugyfelkapu_request)) {
        logger('',0,'login',"lsu|:|".$_MX_ugyfelkapu_request."|:|"."******");
        if (!empty($login_exp_warn)) { 
            $ugyfelkapu_message=str_replace("\\","",str_replace("\\n","<br>",$login_exp_warn));
        }
    }
    else {
        logger('',0,'login',"ls|:|".$susername."|:|"."******");
        if (!$fromurl) {
            $fromurl = "index.php";
        }
        if (!empty($login_exp_warn)) { 
			$alert="alert('$login_exp_warn');";
        }
        else {
			$alert="";
        }
        $login_successful=1;
		if ($_MX_var->application_instance!="kc") {
		    print "
<html>        
<head>        
</head>
<body>
<script>
$alert
var locto='$_MX_var->baseUrl/$fromurl';
if (parent) {
	parent.location = locto;
}
else {
	location=locto;
}
</script>
</body>
</html>  ";
		exit;
        }
    }
}
elseif (strlen($susername)) {
    $loginerr="$word[login_incorrect]";
    logger('',0,'login',"lf|:|".$susername."|:|"."******");
}
elseif (!empty($_MX_ugyfelkapu_request)) {
    logger('',0,'login',"lfu|:|".$_MX_ugyfelkapu_request."|:|"."$_MX_ugyfelkapu_request");
}
else {
}
$loginerr_row="<TR><TD colspan='3'><FONT CLASS='szoveg' STYLE='margin-right: 10px'><b>$loginerr</b></TD></TR>";

//print "*$loginerr*";exit;

if (isset($_POST["public_login_source"]) && in_array($_POST["public_login_source"],array("/login.php","/"))) {
    $loginstatus=0;
    if ($abstract_login) {
        $loginstatus=1;
    }
    elseif (empty($ugyfelkapu_message)) {
        $ugyfelkapu_message="Rossz jelszó";
    }
    $location = "$_MX_var->publicBaseUrl$_POST[public_login_source]?loginstatus=$loginstatus&loginerror=" . rawurlencode($ugyfelkapu_message);
    if (!empty($login_exp_warn)) { 
        print "
<html>        
<head>        
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' /> 
</head>
<body>
<script>
alert('$login_exp_warn');
location='$location';
</script>
</body>
</html>        
            ";
    }
    else {
        header("Location: $location");
    }
    exit;
}
?>
