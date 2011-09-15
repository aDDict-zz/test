<?php
#error_reporting(E_ALL);
#ini_set("display_errors", true);

    header("Content-type:text/xml; charset=UTF-8");
    include_once('inc/_variables.php');
    $_HI_var = new HirekVar();
    $type = isset($_REQUEST["type"]) ? $_REQUEST["type"] : "";
    if (!empty($type) && $type != "ct") $type = "";
    $kw = isset($_REQUEST["kw"]) ? $_REQUEST["kw"] : "";
    if (empty($kw)) {
        $page = isset($_REQUEST["page"]) ? strtolower($_REQUEST["page"]) : "";
        if (empty($page)) $page = "fooldal";
        $file = "{$_HI_var->basedir}/rss{$type}/{$page}.xml";
        if (is_file($file)) {
            readfile($file);
        }
    } else {
        include_once('inc/feed_functions.php');
#error_reporting(E_ALL);
#ini_set("display_errors", true);
	include_once('libs/Smarty.class.php');
	include_once('inc/smarty.config.php');
	$smarty = new Smarty;
	$title = "Kulcsszó: $kw";

	include_once('inc/db_prop.inc.php');
	$connection = mysql_connect($host, $user, $psw) or die(mysql_error());
	$db = mysql_select_db($data_base, $connection) or die(mysql_error());
	mysql_query("Set names 'UTF8'") or die(mysql_error());
//lucene - nem jo mert nem ido szerint rendez
/*
	include_once 'inc/search.config.php';		  											
	$f = "~0.7";
        $period = 1;//7 nap
        $query = "(news_title:$kw$f OR news_lead:$kw$f)";   
        if(!empty($_REQUEST["category"])) {
		$smarty->assign("category", $_REQUEST["category"]);
		if ($cat= getFeedCategoryByName($_REQUEST["category"])) {
        	    $query .= " AND cat_id:$cat[cat_id]";
			$smarty->assign("cat_id", $cat[cat_id]);
		    $title .= ", kategória: $cat[cat_name]";
		}
        }
	$results = search_lucene($total_results, $lib_path, $xml_path, $index_base_path, $query, 0, 19, $period);
*/
	$smarty->assign("title", $title);
	$news = outKeywordsFeed($kw, 20);
	$smarty->assign("results", $news["entries"]);
	$smarty->assign("kw", $kw);
	$smarty->assign("type", $type);
	$smarty->assign("pubDate", date('D, d M Y H:i:s O'));
	$smarty->assign("lastBuildDate", date('D, d M Y H:i:s O'));
	$smarty->display("rss.html");

#print "$query:<pre>";        print_r($results);
    }


?>
