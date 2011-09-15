<?php
	switch($_POST['action']){
		case "login":
			$_SESSION['message'] = array();
			if($_POST['login_name']=="") array_push($_SESSION['message'], "Nem adta meg felhaszn&aacute;l&oacute;nev&eacute;t!");
			if($_POST['password']=="") array_push($_SESSION['message'], "Nem adta meg jelszav&aacute;t!");
			if(count($_SESSION['message'])!=0){
				header("Location: index.php?id=4");exit();
			}else{				
				$query = "Select user_id From  users2 Where user_email='".$_POST['login_name']."' And user_password='".$_POST['password']."' And user_type=1";
				$result = mysql_query($query) or die(mysql_error());
				$row = mysql_fetch_assoc($result);
				if($row['user_id']!=''){
					$_SESSION['hirek_admin_logged'] = $row['user_id'].":".$_POST['login_name'];
					$_SESSION['message'] = "";
					header("Location: index.php?id=1&sub_id=1");exit();
				}else{
					array_push($_SESSION['message'], "Hib&aacute;s felhaszn&aacute;l&oacute;n&eacute;v vagy jelsz&oacute;!");
				}
				if(count($_SESSION['message'])!=0){
					$_SESSION['login_name'] = isset($_POST['login_name']) ? $_POST['login_name'] : '';
					$_SESSION['password'] = isset($_POST['password']) ? $_POST['password'] : '';
					header("Location: index.php?id=4");exit();
				}								
				
			}
			break;
	}
	$smarty->assign('message', $_SESSION['message']);
	$_SESSION['message'] = "";
	$smarty->assign('login_name', $_SESSION['login_name']);
	$_SESSION['login_name'] = "";
	$smarty->assign('password', $_SESSION['password']);
	$_SESSION['password'] = "";
?>
