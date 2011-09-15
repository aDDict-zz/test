<?php
	session_start();	
	include_once('../../inc/db_prop.inc.php');
	include_once('../../inc/page_functions.php');
	
    global	$db, $connection;
	$connection = mysql_connect($host, $user, $psw) or die(mysql_error());
	$db = mysql_select_db($data_base, $connection) or die(mysql_error());
	mysql_query("SET NAMES 'utf8'") or die(mysql_error());

    $pagesort = isset($_REQUEST["pagesort"]) ? $_REQUEST["pagesort"] : "";
    $pages = explode("|", $pagesort);
    $idx = 10;
    foreach($pages as $i=>$page) {
        if ($page) {
            $sql = "update pages set order_by = " . $i * $idx . " where page_id = " . intval($page);
            mysql_query($sql);
        }
    }


?>
