<?php

include_once('libs/Smarty.class.php');
include_once('inc/db_prop.inc.php');
include_once('inc/feed_functions.php');

global $connection, $db;

if (false && $_HI_var->test_site) error_reporting(2047);

$connection = mysql_connect($host, $user, $psw) or die(mysql_error());
$db = mysql_select_db($data_base, $connection) or die(mysql_error());
mysql_query("Set names 'UTF8'") or die(mysql_error());
    

include_once('inc/smarty.config.php');

$smarty = new Smarty;
$smarty->assign("var", $_HI_var);

$out = isset($_REQUEST["out"]) ? $_REQUEST["out"] : "xml";
$encoding = isset($_REQUEST["encoding"]) ? $_REQUEST["encoding"] : 0;
$count = isset($_REQUEST["count"]) ? $_REQUEST["count"] : 10;
$reverse = isset($_REQUEST["reverse"]) ? $_REQUEST["reverse"] : 0;
$kw = isset($_REQUEST["kw"]) ? mysql_real_escape_string(rawurldecode($_REQUEST["kw"])) : "";


$width = isset($_REQUEST["width"]) ? $_REQUEST["width"] : 200;
$height = isset($_REQUEST["height"]) ? $_REQUEST["height"] : 250;
$border_color = isset($_REQUEST["border_color"]) ? $_REQUEST["border_color"] : "000";
$border_width = isset($_REQUEST["border_width"]) ? $_REQUEST["border_width"] : 1;
$border_style = isset($_REQUEST["border_style"]) ? $_REQUEST["border_style"] : "solid";
$bg_color = isset($_REQUEST["bg_color"]) ? $_REQUEST["bg_color"] : 0;
$text_color = isset($_REQUEST["text_color"]) ? $_REQUEST["text_color"] : "000";
$text_size = isset($_REQUEST["text_size"]) ? $_REQUEST["text_size"] : 11;
$title_color = isset($_REQUEST["title_color"]) ? $_REQUEST["title_color"] : "c00";
$title_size = isset($_REQUEST["title_size"]) ? $_REQUEST["title_size"] : 13;
if ($out == "html") {
    $rows = $width < 200 ? 3 : 2;
    $space = $height - 45 - $title_size;
    $item_height = intval($space / $count);
    while (($item_height < ($rows*($text_size+3))) && $count) {
        $count--;
        $item_height = intval($space / $count);
    }
    $smarty->assign("item_height", $item_height);
    $smarty->assign("list_height", $item_height*$count);
}

$inframe = isset($_REQUEST["inframe"]) ? $_REQUEST["inframe"] : 0;
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

$from = isset($_REQUEST["from"]) ? $_REQUEST["from"] : 0;
$smarty->assign("fromparam", empty($from) ? "" : "&from=$from&count=$count");
$sql = "select template from out_site where site='".mysql_real_escape_string($from) . "'";
$r = mysql_query($sql)  or die(mysql_error());
$tpl="html";
if ($k = mysql_fetch_assoc($r)) {
	if (!empty($k)) {
		$tpl = $k["template"];
	}
}
        
$filename = "rss_out_$from.html";
$incdir = "templates/includes";
        
$news = array();

if ($out == "html" && $inframe) {
    readfile("$incdir/$filename");
    
} else {
    if (!empty($kw)) {
        $news = outKeywordsFeed($kw, $count);
        if ($reverse) $news = array_reverse($news);
        foreach($news["entries"]  as $key=>$r) {
            $news["entries"][$key]["title"] = $encoding && !preg_match("/^utf-8$/i", $encoding) ? iconv("UTF-8", "$encoding//IGNORE", $r["title"]) : $r["title"];
            $news["entries"][$key]["enctitle"] = str_replace("\"", "'", $news["entries"][$key]["title"]);
            $news["entries"][$key]["encdescription"] = str_replace("\"", "'", $news["entries"][$key]["description"]);

        }
        $news["kw"] = $kw;
        $smarty->assign("news", $news);
        if ($out == "xml") {
            header("Content-type:text/xml; charset=UTF-8");
            $smarty->display("rss_out_xml.html");
        } elseif ($out == "html") {
            if ($fp = fopen("$incdir/$filename", "w")) {
                if (!fwrite($fp, $smarty->fetch("rss_out_$tpl.html") )) {
                }
            }
            if (count($news["entries"]) > 2) {
                print "document.write('<iframe name=\"hirek_frame\" scrolling=\"no\"  frameborder=\"0\" allowtransparency=\"true\" hspace=\"0\" vspace=\"0\" marginheight=\"0\" marginwidth=\"0\" width=\"$width\" height=\"$height\" src=\"".$_HI_var->baseurl."rss_out.php?inframe=1&out=html&from=$from&kw=$kw&encoding=$encoding&count=$count&reverse=$reverse&width=$width&height=$height&border_color=$border_color&border_width=$border_width&border_style=$border_style&bg_color=$bg_color&text_color=$text_color&text_size:$text_size&title_color=$title_color&title_size=$title_size\">');";
                print "document.write('</iframe>');";
                exit;
            }

        } elseif ($out=="hirek") {
		$smarty->display("rss_out_$tpl.html");
		exit;
	}
    }
}


?>
