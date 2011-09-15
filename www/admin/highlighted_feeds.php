<?php
	session_start();	
	include_once('../../inc/db_prop.inc.php');
	include_once('../../inc/feed_functions.php');
	include_once('../../libs/Smarty.class.php');
	include_once('../../inc/smarty.config.php');
	
	global	$db, $connection;
	$connection = mysql_connect($host, $user, $psw) or die(mysql_error());
	$db = mysql_select_db($data_base, $connection) or die(mysql_error());
	mysql_query("SET NAMES 'utf8'") or die(mysql_error());
	
	$smarty = new Smarty;
	
	$smarty->template_dir = $admin_template_dir;
	$smarty->compile_dir = $admin_compile_dir;
	
	if($_SESSION['hirek_admin_logged']==""){				
		exit;
	}else{
		list($__user_id, $__user_name) = explode(":", $_SESSION['hirek_admin_logged']);
		$smarty->assign("__user_id", $__user_id);
		$smarty->assign("__user_name", $__user_name);
	}
	

	switch($_POST['action']){
		case  "get_feeds":
			header('Content-type:text/xml; charset=UTF-8');
			$feeds = getFeaturedFeeds();
			
			$smarty->assign('feeds', $feeds);
			$smarty->display('resort_feed_list.html');
			break;		
		case "search_feeds":
			header('Content-type:text/xml; charset=UTF-8');
			$q = explode(" ", trim($_POST['q']));
			$where = '';
			for($i=0;$i<count($q);$i++){
				if($i==0) $where = " rss_name like '%".$q[$i]."%'";// And rss_url like '%".$q[$i]."%' ";
					else 	$where .= " And (rss_name like '%".$q[$i]."%')";// And rss_url like '%".$q[$i]."%') ";
			}
			
			$query = "Select rss_name, id From rss_feeds Where ".$where;
			$result = mysql_query($query) or die(mysql_error());
			$i=0;
			while($row = mysql_fetch_assoc($result)){
				$feeds[$i] = array(
					'rss_name'=>$row['rss_name'],
					'id'=>$row['id'],
				);
				$i++;
			}
			$smarty->assign('feeds', $feeds);
			$smarty->display('resort_feed_list.html');
			break;
	}
?>