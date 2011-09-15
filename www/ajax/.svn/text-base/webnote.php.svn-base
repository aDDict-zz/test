<?php
	session_start();
	header('Content-type:text/html; charset=UTF-8');
	include_once('../inc/db_prop.inc.php');
	include_once('../inc/feed_functions.php');
	include_once('../libs/Smarty.class.php');
	include_once('../inc/smarty.config.php');
	
	
	$smarty = new Smarty;
	
	$smarty->template_dir = $template_dir;
	$smarty->compile_dir = $compile_dir;
	
	global $connection, $db;
	
	$connection = mysql_connect($host, $user, $psw) or die(mysql_error());
	$db = mysql_select_db($data_base, $connection) or die(mysql_error());
	mysql_query("Set names 'UTF8'") or die(mysql_error());
	
	list($__user_id, $__user_email) = explode(":", $_SESSION['logged']);
	#$__user_id = 1;
	
	switch($_REQUEST['action']){
		case "update_webnote":
			if($_REQUEST['id'] && $__user_id!=-1)	updateWebNote($__user_id, $_REQUEST['id'], $_REQUEST['content']);
			break;
		case "get_content":
            include_once '../lang/hu/webnote.php';
			if($_REQUEST['id'] && $__user_id!=-1)	{
                $content = getWebNote($__user_id, $_REQUEST['id']);
                if (trim($content) == "") $content = $__default_text. "_::_1";
                echo $content;
            }
			if($__user_id==-1){
			 include_once '../lang/hu/webnote.php';
			 echo $__default_text;
      }
			break;	
	}
?>
