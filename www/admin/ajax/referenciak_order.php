<?php
session_start();

header('Content-Type: text/html; charset=utf-8');

include "/var/www/maxima/www/www/include/_config.php"; 
include('/var/www/maxima/www/www/include/_functions.php');

mysql_connect("$_db_change_host","$_dbuser","$_dbpass");
mysql_select_db("$_dbname");
mysql_query("SET NAMES 'UTF8'");

if(isset($_GET['order']))
{
	$i=0;	
	$tid=explode(",",$_GET['order']);
	if(count($tid)){
		mysql_query("UPDATE refs SET r_order='0' WHERE r_group='".$_GET['id']."'");
		foreach($tid as $v)
		{
			$v=str_replace("ref-","",$v);
			mysql_query("UPDATE refs SET r_order='".$i."' WHERE r_id='".$v."'");
			$i++;							
			echo "UPDATE refs SET r_order='".$i."' WHERE r_id='".$v."'";
		}
	}
}


        $fires=mysql_query("SELECT * FROM refs WHERE  r_group='".$_GET['id']."' AND r_active=1 ORDER BY r_order");
        while($f=mysql_fetch_array($fires))
        {
            $tmp1["id"]="ref-".$f["r_id"];
    //        $tmp1["text"]=iconv("iso-8859-2","utf-8",$f["r_title"]);
            $tmp1["text"]=$f["r_title"];
            $tmp1["leaf"]=true;
            $tmp1["cls"]="file";

            $tmp[]=$tmp1;
            unset($tmp1);
        }

mysql_close();

echo json_encode($tmp);
/*
echo '[{"text":"docs","id":"\/docs","cls":"folder"},{"text":"ext-all-debug-w-comments.js","id":"\/ext-all-debug-w-comments.js","leaf":true,"cls":"file"},{"text":"ext-all-debug.js","id":"\/ext-all-debug.js","leaf":true,"cls":"file"},{"text":"resources","id":"\/resources","cls":"folder"},{"text":"gpl-3.0.txt","id":"\/gpl-3.0.txt","leaf":true,"cls":"file"},{"text":"INCLUDE_ORDER.txt","id":"\/INCLUDE_ORDER.txt","leaf":true,"cls":"file"},{"text":"ext.jsb2","id":"\/ext.jsb2","leaf":true,"cls":"file"},{"text":"ext-all.js","id":"\/ext-all.js","leaf":true,"cls":"file"},{"text":"src","id":"\/src","cls":"folder"},{"text":"test","id":"\/test","cls":"folder"},{"text":"license.txt","id":"\/license.txt","leaf":true,"cls":"file"},{"text":"welcome","id":"\/welcome","cls":"folder"},{"text":"examples","id":"\/examples","cls":"folder"},{"text":"pkgs","id":"\/pkgs","cls":"folder"},{"text":"index.html","id":"\/index.html","leaf":true,"cls":"file"},{"text":"adapter","id":"\/adapter","cls":"folder"}]';
*/
?>