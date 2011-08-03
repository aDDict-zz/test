<?
include "auth.php";
include "cookie_auth.php";
include_once "common.php";

setcookie('cuser_id',"",0,"/");
setcookie('cunique_id',"",0,"/");

mt_srand ((double) microtime() * 1000000);
$randval = mt_rand();
$hash=time().$REMOTE_ADDR.$randval;
//echo $hash;
$unique_id = md5($hash);
mysql_query("update user set unique_id = '$unique_id' where user_id='$active_userid'");
logger("logout");   
header("Location: $_MX_var->publicBaseUrl");
?>
