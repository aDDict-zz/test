<?
include_once "auth.php";
include_once "common.php";

if (empty($_REQUEST["fromhirek"])) {
    header("Location: http://test.hirekmedia.hu");	
    exit();
}

$_MX_ugyfelkapu_request = mysql_escape_string($_REQUEST["code"]);

include("login.php");

$landing_page = $_MX_var->baseUrl . "/index.php";

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body bgcolor="#2E2B28">
<div style="width:355px; height: 255px; padding: 4px 5px 0px 7px;text-align: center; background: #2E2B28;">
<div style="text-align: center; width: 350px; height: 250px; background-color: #ECF85A;font-size: 11px;font-family: Arial;">
<?

if ($abstract_login) {
     if (empty($ugyfelkapu_message)) {
         $ugyfelkapu_message="Sikeres bejelentkezés";
     }
     echo "<br /><br /><br /><br /><br /><br />$ugyfelkapu_message<br /><br />";
     echo "<div style='padding: 2px 5px 0px; float: left; width: 175px; text-align: right;'><a href=\"".$landing_page."\" target=\"_parent\" style='color: black;'>tovább</a></div><div style='width: 20px; float: left;'><a href=\"".$landing_page."\" target=\"_parent\"><img src='http://test.hirekmedia.hu/$_MX_var->application_instance/gfx/more.jpg' width='20' height='20' alt='' style='border: 0px;'/></a></div>";
}
else{
    if (empty($ugyfelkapu_message)) {
        $ugyfelkapu_message="Rossz jelszó";
    }
	echo "<br /><br /><br /><br /><br />$ugyfelkapu_message<br />";	
	echo "<div style='width: 165px; float: left; text-align: right;'><a href=\"javascript:;\" onclick='history.back(-1);'><img src='http://test.hirekmedia.hu/$_MX_var->application_instance/gfx/nyil_vissza.jpg' width='20' height='20' alt='' style='border: 0px;'/></a></div><div style='padding: 2px 5px 0px; float: left; width: 50px; text-align: left;'><a href=\"javascript:;\" onclick='history.back(-1);' style='color: black;'>vissza</a></div>";
}
?>
</div>
</div>
</body>
</html>
