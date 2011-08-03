<?
include "/var/www/maxima/www/www/include/_config.php";
mysql_connect("$_db_change_host","$_dbuser","$_dbpass");
mysql_select_db("$_dbname");

if(isset($_REQUEST["username"]) && isset($_REQUEST["password"]))
{
    $userres=mysql_query("SELECT * FROM admin_users WHERE au_login='".$_REQUEST["username"]."' AND au_password='".$_REQUEST["password"]."'");
    
    if(mysql_num_rows($userres))
    {
	$user=mysql_fetch_array($userres);
	session_start();
	$_SESSION["admin_id"]=$user["au_id"];
	$_SESSION["admin_name"]=$user["au_login"]; 
	$_SESSION["admin_username"]=$user["au_name"];
	$_SESSION["admin_level"]=$user["au_access_level"];
	$tmp["username"]=iconv("ISO-8859-2","UTF-8",$user["au_name"]);
	$tmp["lastlogin"]=$user["au_last_login"];
	mysql_query("UPDATE admin_users SET au_last_login=now() WHERE au_id='".$user["au_id"]."'");
	$tmp["success"]=true;
    }else {
	$tmp["success"]=false;
    }
}else{
 $tmp["success"]=false;
}

mysql_close();

echo json_encode($tmp);

?>