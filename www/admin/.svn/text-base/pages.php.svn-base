<?php	
	include("../inc/page_functions.php");
	switch($_POST['action']){
		case "update_pages":
			$query = "Select page_id From pages";
			$result = mysql_query($query) or die(mysql_error());
			while($row = mysql_fetch_assoc($result)){
				$query = "Update pages Set status=".$_POST['status_'.$row['page_id']].", order_by=".$_POST['position_'.$row['page_id']].", visible=".$_POST['visible_'.$row['page_id']]." Where page_id=".$row['page_id'];
				mysql_query($query) or die(mysql_error());
			}
			header("Location: ".$_SERVER['HTTP_REFERER']);
			exit();
			break;
		case "del_page":
			if($_POST['page_id']!=""){
				$query = "Delete From page_categories Where page_id=".$_POST['page_id'];
				mysql_query($query) or die(mysql_error());
				$query = "Delete From pages Where page_id=".$_POST['page_id'];
				mysql_query($query) or die(mysql_error());
			}			
			header("Location: ".$_SERVER['HTTP_REFERER']);
			exit();
			break;
		case "add_page":
			$query = "Select page_id From pages Where page_url='".$_POST['page_url']."'";
			$result = mysql_query($query) or die(mysql_error());
			$row = mysql_fetch_assoc($result);
			if($row['page_id']==""){
				$dadd = time();
				$query = "Insert into pages (page_name, page_title, page_url, page_keywords, page_template, page_html, page_description, create_uid, create_date, modify_uid, modify_date, page_xml) 
						Values ('".$_POST['page_name']."', '".$_POST['page_title']."', '".$_POST['page_url']."', 
								'".$_POST['page_keywords']."', '".$_POST['page_template']."', '".$_POST['page_html']."', '".$_POST['page_description']."', ".$__user_id.", ".$dadd.", ".$__user_id.", ".$dadd.", '".$_POST['page_xml']."')";
				mysql_query($query) or die(mysql_error());
				$page_id = mysql_insert_id($connection);
				$_SESSION['message'] = "Sikeres felvitel!";
				header("Location: ".$_SERVER['HTTP_REFERER']."&page_id=".$page_id);
				exit();
			}else{
				$_SESSION['message'] = "Ilyen oldal m&aacute;r szerepel az adatb&aacute;zisban!<br />Ha kiv&aacute;nja m&oacute;dos&iacute;tani most megeteheti.";			
				header("Location: ".$_SERVER['HTTP_REFERER']."&page_id=".$row['page_id']);
				exit();
			}
			break;
		case "update_page":			
			if($_POST['page_id']!=""){
				$dadd = time();
				$query = "Update pages Set 
							page_name='".$_POST['page_name']."',
							page_title='".$_POST['page_title']."',
							page_url='".$_POST['page_url']."',
							page_keywords='".$_POST['page_keywords']."',
							page_template='".$_POST['page_template']."',
							page_html='".$_POST['page_html']."',
							page_description='".$_POST['page_description']."',
							modify_uid=".$__user_id.",
							modify_date=".$dadd.",
							page_xml='".$_POST['page_xml']."'
							Where page_id=".$_POST['page_id']." Limit 1";							
				mysql_query($query) or die(mysql_error());			
				$_SESSION['message'] = "Sikeres m&oacute;dos&iacute;t&aacute;s!";
				header("Location: ".$_SERVER['HTTP_REFERER']."&page_id=".$_POST['page_id']);
			}
			break;		
	}
	
	switch($__sub_id){
		case 1:
			$smarty->assign('pages', get_pages());
			break;
		case 2:
			$smarty->assign('message', $_SESSION['message']);
			$_SESSION['message'] = "";
			$smarty->assign('templates', get_templates());
			if($_GET['page_id']) $smarty->assign('page', get_page_by_id($_GET['page_id']));
			break;	
		case 3:
			if($_GET['page_id']) $smarty->assign('page', get_page_by_id($_GET['page_id']));
			$smarty->assign('page_id', $_GET['page_id']);
			$smarty->assign('cat_css', get_all_cat_css());
			break;	
		case 4:	
			if($_GET['page_id']){
				$page = get_page_by_id($_GET['page_id']);
				$smarty->assign('fcats', unserialize($page['fixed_categories']));				
				$smarty->assign('page', $page);				
			};
			break;	
	}
?>
