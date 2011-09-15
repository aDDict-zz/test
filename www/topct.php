<?php

	include_once('libs/Smarty.class.php');
	include_once('inc/smarty.config.php');
    include_once('inc/_variables.php');
    $_HI_var = new HirekVar();
	include_once('inc/db_prop.inc.php');
	$smarty = new Smarty;

    if ($_HI_var->test_site) {
        error_reporting(2047);
        ini_set("display_errors", true);
    }
	
	$smarty->template_dir = $template_dir;
	$smarty->compile_dir = $compile_dir;
    $smarty->assign("var", $_HI_var);
	global $connection, $db;
	
	$connection = mysql_connect($host, $user, $psw) or die(mysql_error());
	$db = mysql_select_db($data_base, $connection) or die(mysql_error());
	mysql_query("Set names 'UTF8'") or die(mysql_error());
    $poss_intervals = array(24=>"az elmúlt 24 órában...", 72=>"az elmúlt 3 napban...");//, 168=>"1 het");
    
    $out = !empty($_REQUEST["out"]) ? $_REQUEST["out"] : 0;
    $interval_datum = !empty($_REQUEST["interval_datum"]) ? $_REQUEST["interval_datum"] : 0;
    if (!empty($_REQUEST["interval"]) && isset($poss_intervals[$_REQUEST["interval"]])) {
        $intervals = array($_REQUEST["interval"]=>isset($poss_intervals[$_REQUEST["interval"]]) ? $poss_intervals[$_REQUEST["interval"]] : "");
    } elseif ($out && $out == "rss") {
        $intervals = array(168=>"az elmúlt héten...");
    } elseif ($out) {
        $intervals = array(24=> $poss_intervals[24]);
    } elseif ($interval_datum) {
        $ts = time() - strtotime($interval_datum);
        $intervals = array(intval($ts/3600) => "$interval_datum óta");
    
    } else {
        $intervals = $poss_intervals;
    }
    $default_page_id = 1;
    $page_id = isset($_REQUEST["page_id"]) ? mysql_real_escape_string($_REQUEST["page_id"]) : $default_page_id;
    $default_count = $out ? 5 : 5;
    $count = isset($_REQUEST["count"]) ? mysql_real_escape_string($_REQUEST["count"]) : $default_count;    
    $news_id = isset($_REQUEST["news_id"]) ? $_REQUEST["news_id"] : 0;    
    $encoding = isset($_REQUEST["encoding"]) ? $_REQUEST["encoding"] : "utf-8";
    if ($out && $out != "rss") {
        $inframe = isset($_REQUEST["inframe"]) ? $_REQUEST["inframe"] : 0;
        $width = isset($_REQUEST["width"]) ? $_REQUEST["width"] : 178;
        $height = isset($_REQUEST["height"]) ? $_REQUEST["height"] : 140;
        $border_color = isset($_REQUEST["border_color"]) ? $_REQUEST["border_color"] : "000";
        $border_width = isset($_REQUEST["border_width"]) ? $_REQUEST["border_width"] : 1;
        $border_style = isset($_REQUEST["border_style"]) ? $_REQUEST["border_style"] : "solid";
        $bg_color = isset($_REQUEST["bg_color"]) ? $_REQUEST["bg_color"] : 0;
        $text_color = isset($_REQUEST["text_color"]) ? $_REQUEST["text_color"] : "000";
        $text_size = isset($_REQUEST["text_size"]) ? $_REQUEST["text_size"] : 11;
        $title_color = isset($_REQUEST["title_color"]) ? $_REQUEST["title_color"] : "c00";
        $title_size = isset($_REQUEST["title_size"]) ? $_REQUEST["title_size"] : 11;

        $rows = $width < 200 ? 3 : 2;
        $space = $height - 23 - $title_size;
        $item_height = intval($space / $count);
        while (($item_height < ($rows*($text_size+3))) && $count > 3) {
            $count--;
            $item_height = intval($space / $count);
        }
        $smarty->assign("item_height", $item_height);
        $smarty->assign("list_height", $item_height*$count);

        if ($border_width) {
            $border = array("width"=>$border_width, "color"=>$border_color, "style"=>$border_style);    
            $smarty->assign("border", $border);
        }
        $smarty->assign("width", intval($width)-2);
        $smarty->assign("height", intval($height)-2);
        $smarty->assign("bg_color", $bg_color);
        $smarty->assign("text_color", $text_color);
        $smarty->assign("text_size", $text_size);
        $smarty->assign("title_color", $title_color);
        $smarty->assign("title_size", $title_size);
        $incdir = "templates/includes";
        $file_name = "topct_out_" . (!empty($_REQUEST["interval"]) ? $_REQUEST["interval"] : "all") . "_$encoding.html";
        if ($inframe) {
            readfile("$incdir/$file_name");
            exit;
        }
    }
    

    $smarty->assign("page_id", $page_id);
    
    
    $smarty->assign("intervals", $intervals);
    $where_spec = " and rss_name not like '%Jobinfo%'";
    $sqltpl = "select count(*) c, ct.url, ct.title, rss_name, ct.rss_id from ct_news ct inner join rss_categories rc on ct.rss_id = rc.rss_id inner join page_categories pc on rc.cat_id = pc.cat_id inner join rss_feeds f on rc.rss_id = f.id where pc.page_id = '$page_id' and date_add(ct.date_add, interval %x hour) > now() $where_spec group by ct.url, ct.title order by c desc limit $count";
    $top = array();
    foreach ($intervals as $i=>$caption) {
        if ($encoding != "utf-8") $caption = iconv("utf-8", $encoding . "//IGNORE", $caption);
        $sql = str_replace("%x", $i, $sqltpl);
        $r = mysql_query($sql) or die(mysql_error());
        $top[$i] = array();
        while ($k = mysql_fetch_assoc($r)) {
            $k["title"] = htmlspecialchars(stripslashes(($encoding != "utf-8" ? iconv("utf-8", $encoding."//IGNORE", $k["title"]) : $k["title"])));
            if ($encoding != "utf-8") $k["rss_name"] = iconv("utf-8", $encoding . "//IGNORE", $k["rss_name"]);
#            $k["rss_name"] = htmlspecialchars(stripslashes($k["rss_name"]));
            $ct_url = mysql_real_escape_string(preg_replace("/\/$/", "", $k["url"]));
            $ct_title = mysql_real_escape_string($k["title"]);
            $sql = "select *, unix_timestamp(dadd) ts_dadd from news2 where  (news_url = '$ct_url' or news_url = '$ct_url/') and news_title = '$ct_title'";
#            print "$sql<br><br>";
            $rr= mysql_query($sql) or die(mysql_error());
            if ($n = mysql_fetch_assoc($rr)) {
                $lead = $encoding != "utf-8" ? iconv("utf-8", $encoding."//IGNORE", $n["news_lead"]) : $n["news_lead"];
#                print "$k[title], lead: $k[lead]<br><br>";
		if ($out && $out == "rss") {
			$k["lead"] = trim($lead);
		} else {
            $k["lead"] = str_replace(array("'", "\n", "\""), array("\'", "", "&quot;"), str_replace("&#039;", "'", trim($lead)));
		}
        $k["news_id"] = $n["id"];
		$k["pubdate"] = date("r", $n["ts_dadd"]);
                if ($k["news_id"] == $news_id) {
                    $k["highlighted"] = 1;
                }
            }
            $top[$i][] = $k;
        }
    }
    $smarty->assign("top", $top);
    if ($out && $out == "rss") {
	header("Content-Type: application/xml; charset=utf-8"); 
        $smarty->display("topct_rss.html");
        //print "<pre>";print_r($top);print "</pre>";
    } elseif ($out) {
        $title = "Legolvasottabb hírek az elmúlt 24 órában";
        $more = "további érdekes hírek";
        $smarty->assign("title", $encoding!= "utf-8" ? iconv("utf-8", $encoding."//IGNORE",$title) : $title);
        $smarty->assign("more", $encoding!= "utf-8" ? iconv("utf-8", $encoding."//IGNORE",$more) : $more);
        if ($fp = fopen("$incdir/$file_name", "w")) {
            if (!fwrite($fp, $smarty->fetch("topct_out.html") )) {
            }
        }
        print "document.write('<iframe name=\"hirek_top_frame\" scrolling=\"no\"  frameborder=\"0\" allowtransparency=\"true\" hspace=\"0\" vspace=\"0\" marginheight=\"0\" marginwidth=\"0\" width=\"$width\" height=\"$height\" src=\"".$_HI_var->baseurl."topct.php?inframe=1&out=1&encoding=$encoding&width=$width&height=$height&border_color=$border_color&border_width=$border_width&border_style=$border_style&bg_color=$bg_color&text_color=$text_color&text_size:$text_size&title_color=$title_color&title_size=$title_size\">');";
        print "document.write('</iframe>');";
    } else {
        $smarty->display("topct.html");
    }

;

?>
