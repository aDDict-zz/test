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
    $smarty->assign("PAGE_TITLE", $_HI_var->page_title_register);
	
    if (!isset($_POST['action'])) $_POST['action'] = '';	
	switch($_POST['action']){
		
		case "register":
			$data = $_POST['data'];
			$error = '';
			$_SESSION['error'] = '';
			if(trim($data['email'])=="" || !is_valid_email(trim($data['email']))) $error[] = $__error['register']['invalid_email'];
			if(strlen(trim($data['password']))<5) $error[] = $__error['register']['wrong_password'];
			if(trim($data['password'])!=trim($data['password_again'])) $error[] = $__error['register']['password_not_match'];
			
			if($error==''){
				$query = "Select count(user_id) as nr From users2 Where user_email='". mysql_real_escape_string(trim($data['email']))."';";
				$result = mysql_query($query) or die(mysql_error());
				$row = mysql_fetch_assoc($result);
				if($row['nr']!=0){
					$error[] = $__error['register']['ocupied_email'];
					$_SESSION['error']['register'] = $error;
					$_SESSION['data']['register'] = $data;				
					header("Location: register.php");
					exit();
				}
				$user_pages[0] = array(
					'title'=>$__error['register']['main_page'],
					'pos' => 0,
					'id' => '0'
				);
				
				$query = "Insert Into users2 Set user_email='". mysql_real_escape_string($data['email'])."', user_password='" . mysql_real_escape_string($data['password'])."', user_pages='".serialize($user_pages)."', date_add = now()";				
				mysql_query($query) or die(mysql_error());
				$__user_id = mysql_insert_id($connection);
				
				$query = "Insert Into user_pages2 Set user_id=".$__user_id.", page_id=0, page_structure=''";
				mysql_query($query) or die(mysql_error());
				
				include_once('inc/page_functions.php');
				
				$pages = getDefaultPages();				
				for($i=0;$i<count($pages);$i++){
					if($pages[$i]['page_id']!=-1){
						$ps = getDefaultPageStructure($pages[$i]['page_id']);
						$max_box_id = $ps[1];
						$ps = $ps[0];
						$query = "Insert Into user_default_pages2 Set user_id=".$__user_id.", page_id=".$pages[$i]['page_id'].", page_structure='".serialize($ps)."', max_box_id=".$max_box_id;
						mysql_query($query) or die(mysql_error());
					}
				}
                if (!send_mail(trim($data['email']), '' ,$__error['emails']['register'], 'register.html' ,$data)) {
                }
				$_SESSION['logged'] = $__user_id.":".$data['email'];
				header("Location: index.php?regalert=1");
				exit();
			}else{
				$_SESSION['error']['register'] = $error;
				$_SESSION['data']['register'] = $data;				
				header("Location: register.php");
				exit();
			}
			break;
		
	}
	if (!isset($_SESSION['error']['register'])) $_SESSION['error']['register'] = '';	
	$smarty->assign('error_register', $_SESSION['error']['register']);	
	$_SESSION['error'] = '';

	if (!isset($_SESSION['data']['register'])) $_SESSION['data']['register'] = '';	
	$smarty->assign('datas_register', $_SESSION['data']['register']);
	$_SESSION['data'] = '';
	
	$smarty->display('register.html');	

	
?>
