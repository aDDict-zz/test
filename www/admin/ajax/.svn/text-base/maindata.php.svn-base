<?php
session_start();
include "/var/www/maxima/www/www/include/_config.php"; 
mysql_connect("$_db_change_host","$_dbuser","$_dbpass");
mysql_select_db("$_dbname");
mysql_query("SET NAMES 'UTF8'");

	if($_GET["mode"]=="update"){
		mysql_query("UPDATE maindata SET panel_size='".addslashes($_POST["panel_size"])."',
										 last_update='".addslashes($_POST["last_update"])."',
										 next_update='".addslashes($_POST["next_update"])."'");
	}

	$f=mysql_fetch_array(mysql_query("SELECT * FROM maindata"));

	mysql_close();

	$list["datas"]=array();
	$list["totalCount"]="1";
	$tmp['panel_size']=stripslashes($f["panel_size"]);
	$tmp['last_update']=stripslashes($f["last_update"]);
	$tmp['next_update']=stripslashes($f["next_update"]);
	$r[]=$tmp;
	$list["datas"]=$r;
	$list["success"]=true;	

echo json_encode($list);

?>