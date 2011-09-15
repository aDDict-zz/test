<?php
	session_start();
	header("Content-type: text/html; charset=UTF-8");	
	include("../inc/db_prop.inc.php");
	include("../inc/smarty.config.php");
	include("../inc/common.php");
	include("../libs/Smarty.class.php");

//    error_reporting(7);

	global	$db, $connection;
	$connection = mysql_connect($host, $user, $psw) or die(mysql_error());
	$db = mysql_select_db($data_base, $connection) or die(mysql_error());
	mysql_query("SET NAMES 'utf8'") or die(mysql_error());
	
	$smarty = new Smarty;
	
	$smarty->template_dir = $admin_template_dir;
	$smarty->compile_dir = $admin_compile_dir;
    if (!isset($_SESSION['hirek_admin_logged']) || empty($_SESSION['hirek_admin_logged'])) $_SESSION['hirek_admin_logged'] = '-1:';	
    if (!isset($_GET['id'])) $_GET['id'] = -1;
    if (!isset($_POST['action'])) $_POST['action'] = '';
	if($_SESSION['hirek_admin_logged']=="" && $_GET['id']!=4 && $_POST['action']!="login"){
		header("Location: index.php?id=4");
		exit;
	}else{
		list($__user_id, $__user_name) = explode(":", $_SESSION['hirek_admin_logged']);
		$smarty->assign("__user_id", $__user_id);
		$smarty->assign("__user_name", $__user_name);
	}
	
	if($_GET['id']) $__id = $_GET['id'];
		else $__id = 1;
	if(isset($_GET['sub_id']) && $_GET['sub_id'])	 $__sub_id = $_GET['sub_id'];
		else $__sub_id = 1;
	
	switch($__id){
		case 1:
			include("pages.php");
			break;
		case 2:
			include('design.php');
			break;
		case 3:
			include("agencies.php");
			break;
		case 4:
			include("login.php");
			break;
		case 5:
			include("users.php");
			break;	
		case 6:
			include("feed_categories.php");
			break;		
		case 7:
			include("stat.php");
			break;		
		case -1:
			$_SESSION['hirek_admin_logged'] = "";
			session_destroy();
			header("Location: index.php?id=4");
			exit;
			break;	
	}
	
	$smarty->assign('id', $__id);
	$smarty->assign('sub_id', $__sub_id);
	$smarty->display("index.html");
?>
