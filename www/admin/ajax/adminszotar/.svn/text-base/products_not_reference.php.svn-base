<?php
session_start();
if(isset($_SESSION["admin_id"]))
{
if((isset($_REQUEST["v_id"]) && $_REQUEST["v_id"]!="") || (isset($_REQUEST["query"]) && $_REQUEST["query"]!=""))
{

header('Content-Type: text/html; charset=utf-8');
//header('Content-Type: text/html; charset=ISO-8859-2');
include "/var/www/www.depo.hu.confs/_config.php";
mysql_connect("$_db_change_host","$_dbuser","$_dbpass");
mysql_select_db("$_dbname");

switch($_REQUEST["mode"])
{
    case "only_product":
    //41-kword-1676,41-kword-1678,41-kword-929   49262,49322   683830,684078,684094
	if(isset($_REQUEST["depo_categs"]) && isset($_REQUEST["p_ids"]))
	{
	    if(strstr($_REQUEST["depo_categs"],","))
		$dc_ids=explode(",",$_REQUEST["depo_categs"]);
	    else
		$dc_ids[]=$_REQUEST["depo_categs"];

	    if(isset($_REQUEST["p_ids"]) && $_REQUEST["p_ids"]!="")
	    {
		if(strstr($_REQUEST["p_ids"],","))
		    $pids=explode(",",$_REQUEST["p_ids"]);
		else
		    $pids[]=$_REQUEST["p_ids"];
	    }

		if(isset($_REQUEST["eids"]) && $_REQUEST["eids"]!=""){
			if(strstr($_REQUEST["eids"],","))
			    $kw_event_ids=explode(",",$_REQUEST["eids"]);
			else
			    $kw_event_ids[]=$_REQUEST["eids"];
	    }
		

	    foreach($dc_ids as $dc_value)
	    {
		$tmp=explode("-",$dc_value);
		$kgids[$tmp[0]]=$tmp[0];
		$kwids["kw_".$tmp[2]]=$tmp[2];
		$p_kwid=$tmp[2];
	    }

	    $kgres=mysql_query("select kw_id from key_groups a,groups_keywords b where kg_type=1 and a.kg_id=b.kg_id and a.kg_id in (".implode(",",$kgids).") order by kg_order");
	    while($g=mysql_fetch_array($kgres))
	    {
	        $kgkwids["kw_".$g["kw_id"]]=$g["kw_id"];
	    }

	    $allkwids=array_merge($kgkwids,$kwids);

	    if(count($pids)>0)
	    {
    		$all_kwid=",".implode(",",$allkwids).",";
		
		foreach($pids as $key => $pid)
		{
			$sql = "INSERT INTO events_log(el_admin_id,el_date,el_event_type) values('{$_SESSION[admin_id]}',NOW(),4)";
			mysql_query($sql);
			$change_id = mysql_insert_id();
				
			$eids = $kw_event_ids[$key];
			if (trim($eids)){
				$sql = "UPDATE events_log 
						SET el_change_el_id = '{$change_id}',
							el_change_date = NOW()
						WHERE el_id in ('{$eids}') ";
				mysql_query($sql);
			} 
			
		    mysql_query("DELETE FROM product_keyword WHERE p_id='".$pid."'");

		    foreach($allkwids as $akw)
		    {
		        mysql_query("INSERT INTO product_keyword 
		        			 SET 
		        			 	p_id='".$pid."',
		        			 	kw_id='".$akw."',
		        			 	all_kwid='".$all_kwid."',
		        			 	event_id = '{$change_id}'");
		    }

		    mysql_query("UPDATE products SET p_kwid='".$p_kwid."' WHERE p_id='".$pid."'");
		}
	    }
	}
    break;

}


// Admin felhasználók lekérdezése
// ********************************
$sql = "SELECT $nocache au_id, au_name FROM admin_users";
$GLOBALS[debug_sql][] = $sql;
$result = mysql_query($sql);
while ($line = mysql_fetch_assoc($result)){
	$admin_users[$line[au_id]] = $line;
}
$GLOBALS[benchmark][sql_admin_users] = microtime(true);


$list=array();

$s=$_REQUEST["start"]+$_REQUEST["limit"]-$_REQUEST["limit"];

// if(isset($_REQUEST["start"])) $limit = "LIMIT ".($_REQUEST["start"]+$_REQUEST["limit"]).",".$_REQUEST["limit"];
$limit = "LIMIT ".($s).",".$_REQUEST["limit"];


if(isset($_REQUEST["query"]) && $_REQUEST["query"]!="") $search = "AND p_fullname LIKE '%".str_replace(" ","%",iconv("UTF-8","ISO-8859-2",$_REQUEST["query"]))."%'";

if(isset($_REQUEST["categ"]) && $_REQUEST["categ"]!=""){
 if(!strstr($_REQUEST["categ"],"-|-"))
    $categs = "AND pl_category='".iconv("UTF-8","ISO-8859-2",$_REQUEST["categ"])."'";
 else{
    $tmpc=explode("-|-",$_REQUEST["categ"]);
    foreach($tmpc as $v)
	$tc[]="pl_category='".iconv("UTF-8","ISO-8859-2",$v)."'";

    $categs="AND (".implode(" OR ",$tc).")";
 }
}

if($_REQUEST["v_id"]!="all_vendor")
    $vid="AND pl_vid='".$_REQUEST["v_id"]."'";
else $vid="";

if(isset($_REQUEST["all_product"]) && $_REQUEST["all_product"]=="1")
    $allp="";
else
    $allp=" AND p_kwid<1 ";
    
if(isset($_REQUEST["reference"]) && is_array($_REQUEST["reference"]) && count($_REQUEST["reference"]))
    $reference=" AND a.p_reference IN ('".implode("', '", $_REQUEST["reference"])."') ";
else
    $reference=""; 

if(isset($categs) && $categs!="")
{
    $total=mysql_num_rows(mysql_query("SELECT p_id,p_name,p_fullname,p_attributes,p_description,p_picture,p_reference,p_kwid,p_date FROM products a,pricelists b WHERE a.p_id=b.pl_pid $reference $search $vid $categs $allp GROUP BY a.p_id"));
    $pres=mysql_query("SELECT p_id,p_name,p_fullname,p_attributes,p_description,p_picture,p_reference,p_kwid,p_date FROM products a,pricelists b WHERE a.p_id=b.pl_pid $reference $search $vid $categs $allp GROUP BY a.p_id ORDER BY p_reference $limit");
}else{
    $total=mysql_num_rows(mysql_query("SELECT p_id,p_name,p_fullname,p_attributes,p_description,p_picture,p_reference,p_kwid,p_date FROM products a,pricelists b WHERE a.p_id=b.pl_pid $vid $reference $search $allp GROUP BY a.p_id"));
    $pres=mysql_query("SELECT p_id,p_name,p_fullname,p_attributes,p_description,p_picture,p_reference,p_kwid,p_date FROM products a,pricelists b WHERE a.p_id=b.pl_pid $vid $reference $search $allp GROUP BY a.p_id ORDER BY p_reference $limit");
}






while($p=mysql_fetch_array($pres))
{
    unset($fs);
    unset($eids);
    unset($keywords);
    unset($olderThan);
    $fres=mysql_query("SELECT a.kw_id as kw_id,kw_word,kw_2nd_level,event_id
					   FROM product_keyword a,keywords b 
					   WHERE a.kw_id=b.kw_id 
					     AND a.p_id='".$p["p_id"]."'");
	if(mysql_num_rows($fres)){
		while($f=mysql_fetch_array($fres)){
			$fs[]=$f;
			$eids[] = $f[event_id];
		}
		$eids = array_unique($eids);
		$eids = implode(",",$eids);
		$eres = mysql_query("SELECT * 
						      FROM events_log
				 	 		  WHERE el_id in ({$eids})");
		while($e = mysql_fetch_assoc($eres)){
			$events[$e[el_id]] = $e;
		}
		foreach ($fs as $f){
			if ($events[$f[event_id]]){
				if ($_REQUEST[colorEventsOlderThan]){
					if ($events[$f[event_id]][el_date] < $_REQUEST[colorEventsOlderThan]){
						$olderThan = true;
						$kw_color = " color: #FF00FF; ";
					}else{
						$kw_color = "";
					}
				}
				$keywords[] = "<span style=\"{$kw_color}\" ext:qtip=\"Kulcsszavazta: <br />".iconv("ISO-8859-2","UTF-8",$admin_users[$events[$f[event_id]][el_admin_id]][au_name])." (".$events[$f[event_id]][el_date].")\">".iconv("ISO-8859-2","UTF-8",$f[kw_word])."</span>";
			}else{
				if ($_REQUEST[colorEventsOlderThan]){
					$olderThan = true;
					$kw_color = " color: #FF00FF; ";
				}else{
					$kw_color = "";
				}
				$keywords[] = "<span style=\"{$kw_color}\">".iconv("ISO-8859-2","UTF-8",$f[kw_word])."</span>";
			}
		}
		$keywords=implode(", ",$keywords);
	}else{
    	$keywords=iconv("iso-8859-2","utf-8","nincs");
    }
	
	
    
		
		
		
		
    

	

//	if($keywords=="nincs" || $_REQUEST["all_product"]=="1")
//	{
		$tmp["keywords"]=$keywords;
		$tmp["kw_event_ids"] = $eids;
		if($p["p_picture"]!="" && is_file("/var/www/depo.hu/www/public/images/89x89/".$p["p_picture"]))
		    $picture="<img src='http://depo.hu/images/89x89/".$p["p_picture"]."' />";
		else
		    $picture="";

		if($p["p_fullname"]=="") $p["p_fullname"]=$p["p_name"];
		$tmp["p_reference"]=$p["p_reference"];
		$tmp["p_date"]=$p["p_date"];
		$tmp["p_id"]=$p["p_id"];
		$tmp["p_attributes"]=iconv("iso-8859-2","utf-8",$p["p_attributes"]);
		$tmp["p_description"]=iconv("iso-8859-2","utf-8",stripslashes(str_replace("\r","",str_replace("\n","",$p["p_description"]))));
		$tmp["p_picture"]=$picture;
    	if($tmp["keywords"]=="nincs"){
    		$rowColor = " inherit ";
    	}elseif ($olderThan){
    		$rowColor = " #FF00FF ";
    	}else{
    		$rowColor = " green ";
    	}
    	$tmp["id"]="<span style=\"color:{$rowColor};\">".$p["p_id"].'</span>';
		$tmp["p_fullname"]="<span style=\"color:{$rowColor};\">".iconv("iso-8859-2","utf-8",$p["p_fullname"]).'</span>';		    
  
    	    $plist[]=$tmp;
	    unset($tmp);
//	}else $total=$total-1;
}


$list["totalCount"]=$total;

if(isset($plist) && count($plist)>0)
    $list["prods"]=$plist;
else
    $list["prods"]=array();

mysql_close();

echo json_encode($list);

//      $prods[]='{"p_id":"'.$p["p_id"].'","p_fullname":"'.$p["p_fullname"].'","p_attributes":"'.$p["p_attributes"].'","p_description":"'.$p["p_description"].'"}';

}

}else echo "session_timeout";
?>
