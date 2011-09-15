<?php
	header("Content-type:text/html; charset=UTF-8");
	session_start();
	include_once('libs/Smarty.class.php');		
	include_once('inc/db_prop.inc.php');
	include_once('inc/common.php');
	
	$__lang = 'hu';
	include_once 'lang/'.$__lang.'/error.php';
	
	
	$connection = mysql_connect($host, $user, $psw) or die(mysql_error());
	$db = mysql_select_db($data_base, $connection) or die(mysql_error());
	mysql_query("Set names 'UTF8'") or die(mysql_error());
	
	$smarty = new Smarty;
    $smarty->assign("var", $_HI_var);
    $smarty->assign("PAGE_TITLE", $_HI_var->page_title_login);
	
	if (!isset($_POST["action"])) $_POST["action"] = "";
	switch($_POST['action']){
		case "forgott":
			$data = $_POST['data'];
			$error = '';
			$_SESSION['error'] = '';
			$_SESSION['data'] = $data;
			if(trim($data['email'])=="" || !is_valid_email(trim($data['email']))) $error[] = $__error['register']['invalid_email'];
			if($error == ''){
				$query = "Select user_password From users2 Where user_email='". mysql_real_escape_string(trim($data['email']))."' Limit 1";
				$result = mysql_query($query) or die(mysql_error());
				$row = mysql_fetch_assoc($result);
				
				if($row['user_password']!=''){
					$data['password'] = $row['user_password'];
					send_mail(trim($data['email']), '' ,$__error['emails']['forgott'], 'forgott_password.html' ,$data);
					$error[] = $__error['login']['forgott_succes'];
					$_SESSION['data'] = '';
				}else $error[] = $__error['login']['invalid_user'];
			}
			
			$_SESSION['error'] = $error;
			header("Location: ".$_SERVER['HTTP_REFERER']);
			exit;
			break;		
		case "login":
			$data = $_POST['data'];
			$error = '';
			if(trim($data['email'])=='' || trim($data['password'])=='') $error[] = $__error['login']['empty_data'];
			if($error==''){
				$query = "Select user_id From users2 Where user_email='". mysql_real_escape_string($data['email'])."' and user_password='". mysql_real_escape_string($data['password'])."'" ;
				$result = mysql_query($query) or die(mysql_error());
				$row = mysql_fetch_assoc($result);
				if($row['user_id']!=''){
					$_SESSION['logged'] = $row['user_id'].":".$data['email'];
					if(isset($_POST["remind_me"]) && $_POST['remind_me']=='1'){
						setcookie('Hirek', $row['user_id'].":".$data['email'], time()+(60*60*24*30));
					}
					header("Location: index.php");
					exit;
				}else{
					$error[] = $__error['login']['invalid_user'];
					$_SESSION['error']['login'] = $error;
					$_SESSION['data']['login'] = $data;				
					header("Location: login.php");
					exit();
				}
			}else{
				$_SESSION['error']['login'] = $error;
				$_SESSION['data']['login'] = $data;				
				header("Location: login.php");
				exit();
			}
			break;
	}
 
    if (!isset($_GET["id"])) $_GET["id"] = 0;
	switch($_GET['id']){
		case  1:
			$smarty->assign('error', $_SESSION['error']);
			
			$_SESSION['error'] = '';
			
			$smarty->assign('datas', $_SESSION['data']);
			$_SESSION['data'] = '';
			
			$smarty->display('forgott_pass.html');
			break;
		default:
			$smarty->assign('error_register', isset($_SESSION['error']['register']) ? $_SESSION['error']['register'] : '');
			$smarty->assign('error_login', isset($_SESSION['error']['login']) ? $_SESSION['error']['login'] : '');	
			
			$_SESSION['error'] = '';
			
			$smarty->assign('datas_register', isset($_SESSION['data']['register']) ? $_SESSION['data']['register'] : '');
			$smarty->assign('datas_login', isset($_SESSION['data']['login']) ? $_SESSION['data']['login'] : '');
			$_SESSION['data'] = '';
			
			$smarty->display('login.html');	
			break;	
	}
	
?>
