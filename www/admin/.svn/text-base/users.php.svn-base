<?php
	include_once('../inc/users.php');
	
	switch($_POST['action']){
		case "delete_user":
			$user_id = $_POST['user_id'];
			if($user_id!=''){
				deleteUserById($user_id);
				$_SESSION['error'] = 'Sikeres t&ouml;rl&eacute;s!';
			}else $_SESSION['error'] = 'Sikertelen t&ouml;rl&eacute;s!';
			
			header("Location: ".$_SERVER['HTTP_REFERER']);
			exit;
			break;
			
		case "update_user":
				$user = $_POST['user'];
				updateUser($user);
				$_SESSION['error'] = 'Sikeres m&oacute;dos&iacute;t&aacute;s!';
				header("Location: ".$_SERVER['HTTP_REFERER']);
				exit;
			break;
	}
	
	switch($__sub_id){
		case 1:
			$smarty->assign('error', $_SESSION['error']);
			$_SESSION['error'] = '';
			
			$email = $_GET['email'];
			$smarty->assign('email', $email);

			if($_GET['total'])	$total_pages = $_GET['total'];						
				else $total_pages = getUsersNr($email);			
			$max_page_nr=10;

			if($_GET['page'])	$ppage= $_GET['page'];
				else $ppage= 1;		
			if ($ppage > $max_page_nr){
				$p_start=floor(($ppage-1) / $max_page_nr);
				$p_start=$p_start*$max_page_nr+1;
			}else
				$p_start=1; 			
			if($_GET['plimit'])		 $plimit = $_GET['plimit'];
				else $plimit = 10;  
			$url = "id=5&sub_id=1&email=".$email;				
			
			$smarty->assign('url', $url);
			$smarty->assign('total_pages', $total_pages);
			
			__goto($p_start, $plimit, $max_page_nr, $ppage, $total_pages);				
			$smarty->assign('users', getUsers($email, $ppage-1, $plimit));
			break;
		case 2:
			$smarty->assign('error', $_SESSION['error']);
			$_SESSION['error'] = '';
			
			$uid = $_GET['uid'];
			if($uid!=''){
				$user = getUserById($uid);
				$smarty->assign('user', $user);
			}
			break;	
	}
?>