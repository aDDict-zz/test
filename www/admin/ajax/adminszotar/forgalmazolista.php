<?php
session_start();
if(isset($_SESSION["admin_id"]))
{
header('Content-Type: text/html; charset=utf-8');
//header('Content-Type: text/html; charset=ISO-8859-2');
include "/var/www/www.depo.hu.confs/_config.php";
mysql_connect("$_db_change_host","$_dbuser","$_dbpass");
mysql_select_db("$_dbname");

$list=array();

$tmp["v_id"]="all_vendor";
$tmp["v_name"]=iconv("iso-8859-2","utf-8","összes forgalmazó");
  
$vlist[]=$tmp;

$vres=mysql_query("SELECT a.v_id as v_id,v_name FROM vendor a,csv_szotar_vendor b WHERE a.v_id=b.v_id GROUP BY a.v_id ORDER BY v_name");
$total=mysql_num_rows($vres);
while($v=mysql_fetch_array($vres))
{

    $incomplete=mysql_num_rows(mysql_query("SELECT csz_id FROM csv_szotar_vendor WHERE v_id='".$v["v_id"]."' AND incomplete='1' AND (error='' OR error IS NULL)"));
    $complete=mysql_num_rows(mysql_query("SELECT csz_id FROM csv_szotar_vendor WHERE v_id='".$v["v_id"]."' AND incomplete='0' AND (error='' OR error IS NULL)"));
    $error=mysql_num_rows(mysql_query("SELECT csz_id FROM csv_szotar_vendor WHERE v_id='".$v["v_id"]."' AND error!='' AND error IS NOT NULL"));

    $tmp["v_id"]=$v["v_id"];
    $tmp["complete"]=$complete;
    $tmp["incomplete"]=$incomplete;
    $tmp["error"]=$error;
    $tmp["v_name"]=iconv("iso-8859-2","utf-8",$v["v_name"]);
//    $tmp["v_name"]=iconv("iso-8859-2","utf-8",$v["v_name"])."&nbsp;<span style='color: black;'>$incomplete</span>/<span style='color: green;'>$complete</span>/<span style='color: red;'>$error</span>";
  
    $vlist[]=$tmp;
}


$list["totalCount"]=$total;

if(isset($vlist) && count($vlist)>0)
    $list["forgs"]=$vlist;
else
    $list["forgs"]=array();

mysql_close();

echo json_encode($list);
}else echo "session_timeout";

?>
