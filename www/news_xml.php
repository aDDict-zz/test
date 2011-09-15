<?php
	include_once('libs/Smarty.class.php');
    include_once('inc/_variables.php');
    include_once('inc/db_prop.inc.php');
	
    global $connection, $db;

	
	$connection = mysql_connect($host, $user, $psw) or die(mysql_error());
	$db = mysql_select_db($data_base, $connection) or die(mysql_error());
	mysql_query("Set names 'UTF8'") or die(mysql_error());

	include_once('inc/smarty.config.php');
	
	$smarty = new Smarty;
    $smarty->assign("var", $_HI_var);

    $out = isset($_REQUEST["out"]) ? $_REQUEST["out"] : "xml";
    $encoding = isset($_REQUEST["encoding"]) ? $_REQUEST["encoding"] : 0;
    $news_perpage = isset($_REQUEST["count"]) ? $_REQUEST["count"] : ($out == "rss" ? 20 : 10);
    $reverse = isset($_REQUEST["reverse"]) ? $_REQUEST["reverse"] : 0;
    if ($encoding) {
        $smarty->assign("encoding", $encoding);
    }

    $pages = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11);
    $page_name = "";
    if (isset($_REQUEST["page_id"])) {
        $pages = array("'" . mysql_real_escape_string($_REQUEST["page_id"]) . "'");   
        $sql = "select page_id, page_name from pages where page_id = '$_REQUEST[page_id]'";
        $r = mysql_query($sql);
        if ($p = mysql_fetch_assoc($r)) {
            $page_name = $p["page_name"];
        }
    } elseif (isset($_REQUEST["page"])) {
        $page = mysql_real_escape_string($_REQUEST["page"]);
        $sql = "select page_id, page_name from pages where page_url = '$page'";
        $r = mysql_query($sql);
        if ($p = mysql_fetch_assoc($r)) {
            $page_name = $p["page_name"];
            $pages = array($p["page_id"]);
        }
    }
    if (!empty($page_name)) {
        $page_name = $encoding ? iconv("UTF-8", $encoding."//IGNORE", $page_name) : $page_name;
        $smarty->assign("page_name", $page_name);
    }
    $sql = "select pc.page_id, rc.rss_id from page_categories pc inner join rss_categories rc on pc.cat_id = rc.cat_id where pc.page_id in (" . implode(",",$pages) . ") order by pc.page_id";
    $r = mysql_query($sql) or die(mysql_error());				
    $page_rss = array();
    while ($n = mysql_fetch_assoc($r)) {
        $p_id = $out == "html" && count($pages) > 1 ? 0 : $n["page_id"];//html out: news should not be separated by pages
        $page_rss[$p_id]["rsslist"][$n["rss_id"]] = $n["rss_id"]; 
    }

    $sql = "select *, unix_timestamp(dadd) ts_dadd from news2 order by dadd desc limit 1000";
    $r = mysql_query($sql) or die(mysql_error());				
    $news = array();
    while (count($page_rss) && $n = mysql_fetch_assoc($r)) {
        foreach($page_rss as $page_id=>$val) {
            if (isset($val["rsslist"][$n["rss_id"]])) {
                if ($out == "html" && !empty($n["agency_name"])) {
                    $n["news_title"] = "$n[news_title]&nbsp<i>($n[agency_name])</i>";
                }
                $page_rss[$page_id]["newslist"][$n["id"]] = array("title"=>$encoding ? iconv("UTF-8", $encoding."//IGNORE", $n["news_title"]) : $n["news_title"], "agency_name"=>$n["agency_name"], "datum"=>date("Y-m-d", $n["ts_dadd"]));
                if ($out == "rss") {
                    $page_rss[$page_id]["newslist"][$n["id"]]["description"] = $encoding ? iconv("UTF-8", $encoding."//IGNORE", $n["news_lead"]) : $n["news_lead"];
                }
                if (count($page_rss[$page_id]["newslist"]) == $news_perpage) {
                    $news[$page_id] = $reverse ? array_reverse($page_rss[$page_id]["newslist"], true) : $page_rss[$page_id]["newslist"];
                    unset($page_rss[$page_id]);
                }
            }
        }
    }
    foreach($page_rss as $page_id=>$val) {
        $news[$page_id] = $page_rss[$page_id]["newslist"];
    }
    $smarty->assign("news", $news);

    $from = isset($_REQUEST["from"]) ? "&from=$_REQUEST[from]" : ""; 
    $smarty->assign("fromparam", $from);


    # error_reporting(2048);

#    $smarty->debugging = true;


    $smarty->force_compile = 1;
    $smarty->caching = 0;
    if ($out == "xml") {
        header("Content-type:text/xml; charset=UTF-8");
        $smarty->display("news_xml.html");
    } elseif ($out == "html") {
        $smarty->display("news_html.html");
    } elseif ($out == "rss") {
        header("Content-type:text/xml; charset=UTF-8");
        $smarty->display("news_rss.html");
    
    }


?>
