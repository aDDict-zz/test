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
	$smarty->cache_dir = $cache_dir;
	
	global $connection, $db;
	
	$connection = mysql_connect($host, $user, $psw) or die(mysql_error());
	$db = mysql_select_db($data_base, $connection) or die(mysql_error());
	mysql_query("Set names 'UTF8'") or die(mysql_error());
	if(!isset($_SESSION["logged"]) || $_SESSION['logged']=='') $_SESSION['logged'] = '-1:';

	list($__user_id, $__user_email) = explode(":", $_SESSION['logged']);
	#$__user_id = 1;
	
	switch($_REQUEST['action']){
		case "get_featured_feeds":
			$smarty->caching = true; 
			$smarty->cache_lifetime = 600; 
			$smarty->compile_check = true;
			if(!$smarty->is_cached('feed_list.html', md5('featured'))){
				$feeds = getFeaturedFeeds();
				$smarty->assign('feeds', $feeds);
				$smarty->assign('general', 1);
			}
			echo trim($smarty->fetch('feed_list.html', md5('featured')));
			break;
		case "get_my_feeds":
            $show = 0;
			if($__user_id!=-1){
				$feeds = getUserFeeds($__user_id);
                if (count($feeds)) {
                    $show = 1;
                    $smarty->assign('feeds', $feeds);
                    $smarty->assign('general', 0);
                    echo trim($smarty->fetch('feed_list.html'));
                }
			}
            if (!$show) {
                $smarty->assign("user_id", $__user_id);
                echo trim($smarty->fetch('feed_list_empty.html'));
            }
			break;
		case "get_feed_categories":	
			$feed_categories = getFeedCategories();
			
			$smarty->assign('feed_categories', $feed_categories);
			$smarty->display('feed_categories.html');

			break;
		case "get_feeds_by_cat"	:
			$feed_cat_id = $_REQUEST['feed_cat_id'];
			if($feed_cat_id!=""){
				$smarty->caching = true; 
				$smarty->cache_lifetime = 600; 
				$smarty->compile_check = true;
				
				if(!$smarty->is_cached('feed_list.html', md5($feed_cat_id))){
					$feeds = getFeedByCat($feed_cat_id);
					
					$smarty->assign('feeds', $feeds);
					$smarty->assign('general', 1);
				}
				echo trim($smarty->fetch('feed_list.html', md5($feed_cat_id)));
			}
			break;
		case "show_add_new_feed_box":
            $keywords = isset($_REQUEST["keywords"]) ? $_REQUEST["keywords"] : 0;
            $smarty->assign("keywords", $keywords);
            $tpl = $__user_id == -1 ? "loginhint.html" : "new_feed_box.html";
            $smarty->display($tpl);
			break;			
        case "rename_feed" :
            $feed_id = isset($_REQUEST["feed_id"]) ? $_REQUEST["feed_id"] : 0;
            $feed_id = intval($feed_id);
            $name = isset($_REQUEST["name"]) ? $_REQUEST["name"] : 0;
            $name = trim($name);
            if (empty($name)) {
                print $feed_id . "__" . "üresen hagyta a mezőt";
            } else {
                $resp = updateUserFeed($__user_id, $feed_id, $name);
                print $feed_id . "__" . (empty($resp) ? "ok" . "__" . $name : $resp);
               
            }
            break;
        case "delete_feed" :
            $feed_id = isset($_REQUEST["feed_id"]) ? $_REQUEST["feed_id"] : 0;
            $feed_id = intval($feed_id);
            if ($feed_id)  {
                $resp = removeUserFeed($__user_id, $feed_id);
                print empty($resp) ? $feed_id : "error: $resp";
            }
            break;
	}
?>
