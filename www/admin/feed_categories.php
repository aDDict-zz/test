<?php
	include_once '../inc/feed_functions.php';
	switch($_POST['action']){
		case "del_cat":
			if($_POST['cat_id']!=''){
				$query = "Delete From feed_categories Where cat_id=".$_POST['cat_id']." Limit 1;";
				mysql_query($query) or die(mysql_error());
			}
			header("Location: ".$_SERVER['HTTP_REFERER']);
			exit;
			break;
		case "add_new_cat":
			$category = $_POST['category'];
			if($category['cat_name']!='' && $category['cat_title']!=''){
				$query = "Insert Into feed_categories Set cat_name='".$category['cat_name']."', cat_title='".$category['cat_title']."'";
				mysql_query($query);
				$cat_id = mysql_insert_id();
				$_SESSION['error'] = 'Sikeres felvitel';
				header("Location: ".$_SERVER['HTTP_REFERER']."&cat_id=".$cat_id);
				exit;
			}
			break;
		case "update_cat":
			$category = $_POST['category'];
			if($category['cat_id']!=''){
				$query = "Update feed_categories Set cat_name='".$category['cat_name']."', cat_title='".$category['cat_title']."' Where cat_id=".$category['cat_id'];
				mysql_query($query) or die(mysql_error());
				$_SESSION['error'] = 'Sikeres m&oacute;dos&iacute;t&aacute;s';
				header("Location: ".$_SERVER['HTTP_REFERER']);
				exit;
			}
			break;
		case "resort_feeds":
			if($_POST['rss_categories']!=''){
				$cat_id = $_POST['rss_categories'];
				$query = "Delete From feed_cats Where cat_id=".$cat_id;
				mysql_query($query) or die(mysql_error());
				
				$feeds = $_POST['rss_cagorized'];				
				for($i=0;$i<count($feeds);$i++){
					$query = "Insert Into feed_cats Set cat_id=".$cat_id.", feed_id=".$feeds[$i];
					mysql_query($query) or die(mysql_error());
				}
				$_SESSION['error'] = 'Sikeres m&oacute;dos&iacute;t&aacute;s';
				header("Location: ".$_SERVER['HTTP_REFERER']);
				exit;
			}
			break;
		case "highlighted_feeds":
			$query = "Update rss_feeds Set featured=0 Where featured=1";
			mysql_query($query) or die(mysql_error());
			
			$feeds = $_POST['highlighted'];
			for($i=0;$i<count($feeds);$i++){
				$query = "Update rss_feeds Set featured=1 Where id=".$feeds[$i];
				mysql_query($query) or die(mysql_error());
			}
			$_SESSION['error'] = 'Sikeres m&oacute;dos&iacute;t&aacute;s';
			header("Location: ".$_SERVER['HTTP_REFERER']);
			exit;
			break;
	}
	$smarty->assign('error', $_SESSION['error']);
	$_SESSION['error'] = '';
	switch($__sub_id){
		case 1:
			$categories = getFeedCategories();
			$smarty->assign('categories', $categories);
			break;
		case 2:
			if($_GET['cat_id']){
				$category = getFeedCategoryById($_GET['cat_id']);
				$smarty->assign('category', $category);
			}
			break;		
	}
?>
