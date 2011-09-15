<?php
error_reporting(E_ALL);
ini_set("display_errors", true);
	session_start();
	include_once('inc/db_prop.inc.php');
	include_once('inc/common.php');
	$connection = mysql_connect($host, $user, $psw) or die(mysql_error());
	$db = mysql_select_db($data_base, $connection) or die(mysql_error());
	mysql_query("Set names 'UTF8'") or die(mysql_error());

	if(!isset($_SESSION["logged"]) || $_SESSION['logged']==''){
		$_SESSION['logged'] = '-1:';
    }

	list($__user_id, $__user_email) = explode(":", $_SESSION['logged']);

    $__user_id = intval($__user_id);
    $__user_email = mysql_real_escape_string($__user_email);

    $from_rss= isset($_REQUEST["from"]) ? mysql_real_escape_string(trim(rawurldecode($_REQUEST["from"]))) : "";

    $link = mysql_real_escape_string(trim(isset($_REQUEST["link"]) ? $_REQUEST["link"] : ""));
    if (isset($_REQUEST["news_id"])) {
        $news_id = mysql_real_escape_string($_REQUEST["news_id"]);
        $sql = "select * from news2 where id = '$news_id'";
        if (($r = mysql_query($sql, $connection)) && ($k = mysql_fetch_assoc($r))) {
            $link = mysql_real_escape_string($k["news_url"]);
            $title = mysql_real_escape_string(trim($k["news_title"]));
            $rss_id = mysql_real_escape_string(trim($k["rss_id"]));

        }
    } elseif (!empty($link)) {
        $title = isset($_REQUEST["title"]) ? mysql_real_escape_string(trim(rawurldecode($_REQUEST["title"]))) : "";
        $rss_id = isset($_REQUEST["rss"]) ? mysql_real_escape_string(trim(rawurldecode($_REQUEST["rss"]))) : 0;
    }
    if (!empty($link)) {
        $ip=mysql_real_escape_string($_SERVER["REMOTE_ADDR"]);
        $sql = "insert into ct_news (user_id, url, title, ip, session_id, date_add, rss_id, from_rss) values ('$__user_id', '" . mysql_real_escape_string(rawurldecode($link)) . "', '$title', '$ip', '". session_id() . "', now(), '$rss_id', '$from_rss')";
        mysql_query($sql, $connection);
        header("location:". trim(rawurldecode($link)));
        exit;

    }
?>
