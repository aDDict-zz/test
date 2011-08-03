<?

session_start();
$_SESSION["admin_id"]="";
$_SESSION["admin_level"];
session_destroy();
header("Location: /admin_extjs/admin.php");
?>