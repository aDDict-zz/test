<?php
session_start();

header('Content-Type: text/html; charset=utf-8');

include "/var/www/maxima/www/www/include/_config.php"; 
include('/var/www/maxima/www/www/include/_functions.php');

mysql_connect("$_db_change_host","$_dbuser","$_dbpass");
mysql_select_db("$_dbname");
mysql_query("SET NAMES utf8");

switch($_POST['mode'])
{
	case "delete":
			$tmp=mysql_fetch_array(mysql_query("SELECT * FROM refs WHERE r_id='".$_POST['ref_id']."'"));
			
			@unlink("/var/www/maxima/www/www/images/references/little/".$tmp['r_lpicture']);
			@unlink("/var/www/maxima/www/www/images/references/little/".$tmp['r_lpicture_over']);
			@unlink("/var/www/maxima/www/www/images/references/".$tmp['r_bpicture']);
			mysql_query("DELETE FROM refs WHERE r_id='".$_POST['ref_id']."'");
	break;
	case "insert":
		if(isset($_POST['ref_active'])) $active='1';
		else $active='0';

		if($_POST['ref_id'] && $_POST['ref_id']>0){
			mysql_query("UPDATE refs SET r_date=now(),r_group='".$_POST['ref_group']."',r_title='".addslashes($_POST['ref_name'])."',r_text='".addslashes($_POST['ref_desc'])."',r_active='".$active."',r_url='".deekezet(addslashes($_POST['ref_name']))."' WHERE r_id='".$_POST['ref_id']."'");
			$id=$_POST['ref_id'];
		}else{
			mysql_query("INSERT INTO refs SET r_date=now(),r_group='".$_POST['ref_group']."',r_title='".addslashes($_POST['ref_name'])."',r_text='".addslashes($_POST['ref_desc'])."',r_active='".$active."',r_url='".deekezet(addslashes($_POST['ref_name']))."'");
			$id=mysql_insert_id();
		}


    if ($_FILES['ref_lpicture']['tmp_name']){
           if(stristr($_FILES['ref_lpicture']['name'],".jpg")) $kit="jpg";
           elseif(stristr($_FILES['ref_lpicture']['name'],".gif")) $kit="gif";
           elseif(stristr($_FILES['ref_lpicture']['name'],".png")) $kit="png";

		   $picture=$id."_reference.".$kit;

           $f=$_FILES['ref_lpicture']['tmp_name'];
           $fn="/var/www/maxima/www/www/images/references/little/".$picture;

			$size = getimagesize($f);
		    if($size[0]>68 || $size[1]>68)
           		exec("convert -resize 68x68 -quality 100 '".$_FILES['ref_lpicture']['tmp_name']."' '".$fn."'");
           	else
	           	copy($_FILES['ref_lpicture']['tmp_name'],$fn);

			mysql_query("UPDATE refs SET r_lpicture='".$picture."' WHERE r_id='".$id."'");
    }

    if ($_FILES['ref_lpicture_over']['tmp_name']){
           if(stristr($_FILES['ref_lpicture_over']['name'],".jpg")) $kit="jpg";
           elseif(stristr($_FILES['ref_lpicture_over']['name'],".gif")) $kit="gif";
           elseif(stristr($_FILES['ref_lpicture_over']['name'],".png")) $kit="png";

		   $picture=$id."_reference_over.".$kit;

           $f=$_FILES['ref_lpicture_over']['tmp_name'];
           $fn="/var/www/maxima/www/www/images/references/little/".$picture;

			$size = getimagesize($f);
		    if($size[0]>68 || $size[1]>68)
           		exec("convert -resize 68x68 -quality 100 '".$_FILES['ref_lpicture_over']['tmp_name']."' '".$fn."'");
			else
	           	copy($_FILES['ref_lpicture_over']['tmp_name'],$fn);

			mysql_query("UPDATE refs SET r_lpicture_over='".$picture."' WHERE r_id='".$id."'");
    }

    if ($_FILES['ref_bpicture']['tmp_name']){
           if(stristr($_FILES['ref_bpicture']['name'],".jpg")) $kit="jpg";
           elseif(stristr($_FILES['ref_bpicture']['name'],".gif")) $kit="gif";
           elseif(stristr($_FILES['ref_bpicture']['name'],".png")) $kit="png";

		   $picture=$id."_reference.".$kit;

           $f=$_FILES['ref_bpicture']['tmp_name'];
           $fn="/var/www/maxima/www/www/images/references/".$picture;

 			$size = getimagesize($f);
		    if($size[0]>660 || $size[1]>551)
		      	exec("convert -resize 660x511 -quality 100 '".$_FILES['ref_bpicture']['tmp_name']."' '".$fn."'");
			else
	           	copy($_FILES['ref_bpicture']['tmp_name'],$fn);
			
			mysql_query("UPDATE refs SET r_bpicture='".$picture."' WHERE r_id='".$id."'");
    }




	break;		
}


$list=array();
  
$vres=mysql_query("SELECT * FROM refs WHERE  r_group='".$_GET['id']."' ORDER BY r_order");
$total=mysql_num_rows($vres);
while($v=mysql_fetch_array($vres))
{

    $tmp["ref_id"]=$v["r_id"];
//    $tmp["ref_name"]=iconv("iso-8859-2","utf-8",$v["r_title"]);
    $tmp["ref_name"]=$v["r_title"];
    $tmp["ref_group"]=iconv("iso-8859-2","utf-8",$v["r_group"]);
//    $tmp["ref_desc"]=iconv("iso-8859-2","utf-8",$v["r_text"]);
    $tmp["ref_desc"]=$v["r_text"];
    $tmp["ref_date"]=iconv("iso-8859-2","utf-8",$v["r_date"]);
    $tmp["ref_lpicture"]=iconv("iso-8859-2","utf-8",$v["r_lpicture"]);
    $tmp["ref_lpicture_over"]=iconv("iso-8859-2","utf-8",$v["r_lpicture_over"]);
    $tmp["ref_bpicture"]=iconv("iso-8859-2","utf-8",$v["r_bpicture"]);
    $tmp["ref_active"]=iconv("iso-8859-2","utf-8",$v["r_active"]);

	if($tmp["ref_active"]=="1")
    	$tmp["ref_active_text"]="igen";
    else
    	$tmp["ref_active_text"]="nem";
  
    $vlist[]=$tmp;
}


$list["totalCount"]=$total;
$list["success"]=true;

if(isset($vlist) && count($vlist)>0)
    $list["refs"]=$vlist;
else
    $list["refs"]=array();

mysql_close();

echo json_encode($list);

?>
