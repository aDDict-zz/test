<?php
session_start();
if(isset($_SESSION["admin_id"]))
{

header('Content-Type: text/html; charset=iso-8859-2');
$GLOBALS[benchmark][begin] = microtime(true);

if(isset($_REQUEST["v_id"]))
{

include "/var/www/www.depo.hu.confs/_config.php";
include "/var/www/www.depo.hu.confs/_functions.php";

mysql_connect("$_db_change_host","$_dbuser","$_dbpass");
mysql_select_db("$_dbname");


/*
depo_categs", categ_ids);
<------><------><------>    ds.setBaseParam("vcateg_ids", vcateg_ids);
<------><------><------>    ds.setBaseParam("p_ids"
*/
switch($_REQUEST["mode"])
{
    case "szotar_insert":
    //41-kword-1676,41-kword-1678,41-kword-929   49262,49322   683830,684078,684094
	if(isset($_REQUEST["depo_categs"]) && isset($_REQUEST["vcateg_ids"]))
	{
	    if(strstr($_REQUEST["depo_categs"],","))
		$dc_ids=explode(",",$_REQUEST["depo_categs"]);
	    else
		$dc_ids[]=$_REQUEST["depo_categs"];

	    if(strstr($_REQUEST["vcateg_ids"],","))
		$vc_ids=explode(",",$_REQUEST["vcateg_ids"]);
	    else
		$vc_ids[]=$_REQUEST["vcateg_ids"];
	    
	    if(isset($_REQUEST["vc_eids"]) && $_REQUEST["vc_eids"]!=""){
			if(strstr($_REQUEST["vc_eids"],","))
			    $vc_event_ids=explode(",",$_REQUEST["vc_eids"]);
			else
			    $vc_event_ids[]=$_REQUEST["vc_eids"];
	    }

	    foreach($dc_ids as $dc_value){
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

	    foreach($vc_ids as $key => $vc_value){
			mysql_query("DELETE FROM csv_szotar_vendor_keywords WHERE csz_id='".$vc_value."'");
			foreach($allkwids as $kw){
			    mysql_query("INSERT INTO csv_szotar_vendor_keywords SET csz_id='".$vc_value."',kw_id='".$kw."',admin_id='".$_SESSION["admin_id"]."',date=now()");
			}
			
			$sql = "INSERT INTO events_log(el_admin_id,el_date,el_event_type) values('{$_SESSION[admin_id]}',NOW(),3)";
			mysql_query($sql);
			$change_id = mysql_insert_id();			
			
			$eids = $vc_event_ids[$key];
			if (trim($eids)){
				$sql = "UPDATE events_log 
						SET el_change_el_id = '{$change_id}',
							el_change_date = NOW()
						WHERE el_id in ('{$eids}') ";
				mysql_query($sql);
			} 
			
			mysql_query("UPDATE csv_szotar_vendor 
						 SET incomplete='0',
						 	 changed='1',
						 	 error='',
							 event_id = '{$change_id}'
						 WHERE csz_id='".$vc_value."'");
	    }	    
		
	    $where = array();
	   	if ($_REQUEST["v_id"]){
			$where[] = " pl_vid='".$_REQUEST["v_id"]."' ";
		}		
		$where[] = " csz_id in ('".implode("', '",$vc_ids)."') ";
		$where[] = " p_kwid<1 ";
		$where = implode(" AND ", $where);
		$sql = "SELECT $nocache p_id, csz_id, pl_category, p_kwid 
				FROM products a,pricelists b
				LEFT JOIN csv_szotar_vendor ON pl_category = csv_szotar_vendor.csv_name
				WHERE a.p_id=b.pl_pid AND $where";
	    $GLOBALS[debug_sql][] =  $sql;
		$res = mysql_query($sql);
		$GLOBALS[benchmark]["autokulcsszavaznivalo_termekek"] = microtime(true);		
		$pids = array();
		while ($line = mysql_fetch_assoc($res)){
			$pids[] = $line[p_id]; 
		}	
		
		
	    
	    if(count($pids)>0)
	    {
    		$all_kwid=",".implode(",",$allkwids).",";
			foreach($pids as $key => $pid){
				$sql = "INSERT INTO events_log(el_admin_id,el_date,el_event_type) values('{$_SESSION[admin_id]}',NOW(),16)";
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
	
			    foreach($allkwids as $akw){
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
    case "szotar_error":
	if(isset($_REQUEST["error_categs"]) && $_REQUEST["error_categs"]!="")
	{
		if(isset($_REQUEST["vc_eids"]) && $_REQUEST["vc_eids"]!=""){
			if(strstr($_REQUEST["vc_eids"],","))
			    $vc_event_ids=explode(",",$_REQUEST["vc_eids"]);
			else
			    $vc_event_ids[]=$_REQUEST["vc_eids"];
	    }	    
	    if(strstr($_REQUEST["error_categs"],",")){
			$err_ids=explode(",",$_REQUEST["error_categs"]);
	    }else{
			$err_ids[]=$_REQUEST["error_categs"];
		}

	    foreach($err_ids as $key => $v){
	    	$sql = "INSERT INTO events_log(el_admin_id,el_date,el_event_type) values('{$_SESSION[admin_id]}',NOW(),12)";
			mysql_query($sql);
			$change_id = mysql_insert_id();			
			
			$eids = $vc_event_ids[$key];
			if (trim($eids)){
				$sql = "UPDATE events_log 
						SET el_change_el_id = '{$change_id}',
							el_change_date = NOW()
						WHERE el_id in ('{$eids}') ";
				mysql_query($sql);
			} 
			
			mysql_query("UPDATE csv_szotar_vendor 
						 SET error='".iconv("UTF-8","ISO-8859-2",$_REQUEST["error_msg"])."',
						 	 event_id = '{$change_id}'
						 WHERE csz_id='".$v."'");
		}
	}
    break;
}


$gres=mysql_query("select a.kg_id as kg_id,kg_name,kw_id from key_groups a,groups_keywords b where kg_type=1 and a.kg_id=b.kg_id order by kg_order");
while($g=mysql_fetch_array($gres))
{
    $groups[]=$g["kw_id"];
}

if (!$_REQUEST[start]){
	$_REQUEST[start] = 0;
}
if (!$_REQUEST[limit]){
	$_REQUEST[limit] = 30;
}
if ($_GET[debug]){
	$nocache = " SQL_NO_CACHE ";
}

$s=$_REQUEST["start"]+$_REQUEST["limit"]-$_REQUEST["limit"];
$limit = "LIMIT ".($s).",".$_REQUEST["limit"];

$categ_where = array();
$categ_where[] = " active='1' ";

// Filterek
if(isset($_REQUEST["have_black"]) && $_REQUEST["have_black"]=="1")
    $filters[]="(incomplete=1 AND (error='' OR error IS NULL))";

if(isset($_REQUEST["have_green"]) && $_REQUEST["have_green"]=="1")
    $filters[]="(incomplete=0 AND (error='' OR error IS NULL))";

if(isset($_REQUEST["have_red"]) && $_REQUEST["have_red"]=="1")
    $filters[]="(error!='' OR error IS NOT NULL)";

if (is_array($filters) && count($filters)){
	$categ_where[] = "(".implode(" OR ",$filters).")";
}

//Csak kulcsszónélküli termékes kategóriák listázása
if (isset($_REQUEST["have_kulcsszoNelkuliTermek"]) && $_REQUEST["have_kulcsszoNelkuliTermek"]=="1"){
	$where = array();
	if ($_REQUEST["v_id"]){
		$where[] = " pl_vid='".$_REQUEST["v_id"]."' ";
	}
	$where[] = " kw_id is null ";
	$where = implode(" AND ",$where);
	$sql = "SELECT $nocache pl_category
			FROM products a,pricelists b 
			WHERE a.p_id=b.pl_pid AND $where
			GROUP BY pl_category";
	$sql = "SELECT $nocache pl_pid, pl_category 
	FROM pricelists b
	INNER JOIN products a ON a.p_id = b.pl_pid 
	LEFT JOIN product_keyword ON a.p_id = product_keyword.p_id
	WHERE $where
	GROUP BY pl_category";
	
	$GLOBALS[debug_sql][] = $sql;
	$res = mysql_query($sql);
	while ($line = mysql_fetch_assoc($res)){
		$kulcsszonelkuli_categs[] = $line[pl_category];	
	}	
	$GLOBALS[benchmark]["categ_product_count_full"] = microtime(true);
	$categ_where[] = " csv_name IN ('".implode("', '",$kulcsszonelkuli_categs)."')";
}


if(!count($filters)) $filters[]="1";

if(isset($_REQUEST["query"]) && $_REQUEST["query"]!=""){
	$categ_where[] = " csv_name LIKE '%".str_replace(" ","%",iconv("UTF-8","ISO-8859-2",$_REQUEST["query"]))."%' ";
}

if($_REQUEST["v_id"]!=="all_vendor"){
	$categ_where[] = " v_id='".$_REQUEST["v_id"]."' ";
}

$categ_where = implode(" AND ",$categ_where);

/*
if($_REQUEST["v_id"]!=="all_vendor")
{
    $total=mysql_num_rows(mysql_query("select * from csv_szotar_vendor where active='1' AND v_id='".$_REQUEST["v_id"]."' AND (".implode(" OR ",$filters).") $search GROUP BY csz_id"));
    $res=mysql_query("select * from csv_szotar_vendor where active='1' AND v_id='".$_REQUEST["v_id"]."' AND (".implode(" OR ",$filters).") $search GROUP BY csz_id ORDER BY incomplete DESC,csv_name $limit");    
//    $total=mysql_num_rows(mysql_query("select * from csv_szotar_vendor,pricelists where csv_name=pl_category AND v_id='".$_REQUEST["v_id"]."' AND (".implode(" OR ",$filters).") $search GROUP BY csz_id"));
//    $res=mysql_query("select * from csv_szotar_vendor,pricelists where csv_name=pl_category AND v_id='".$_REQUEST["v_id"]."' AND (".implode(" OR ",$filters).") $search GROUP BY csz_id ORDER BY incomplete DESC,csv_name $limit");
}else{
    $total=mysql_num_rows(mysql_query("select * from csv_szotar_vendor where active='1' AND (".implode(" OR ",$filters).") $search GROUP BY csz_id"));
    $res=mysql_query("select * from csv_szotar_vendor where active='1' AND (".implode(" OR ",$filters).") $search GROUP BY csz_id ORDER BY incomplete DESC,csv_name $limit");
//    $total=mysql_num_rows(mysql_query("select * from csv_szotar_vendor,pricelists where csv_name=pl_category AND (".implode(" OR ",$filters).") $search GROUP BY csz_id"));
//    $res=mysql_query("select * from csv_szotar_vendor,pricelists where csv_name=pl_category AND (".implode(" OR ",$filters).") $search GROUP BY csz_id ORDER BY incomplete DESC,csv_name $limit");
}
*/
$sql_count = "SELECT $nocache * 
			  from csv_szotar_vendor 
			  where $categ_where GROUP BY csz_id";
$GLOBALS[debug_sql][] = $sql_count;
$total=mysql_num_rows(mysql_query($sql_count));
$GLOBALS[benchmark]["sql_count"] = microtime(true);

$sql = "SELECT $nocache * 
		from csv_szotar_vendor 
		where $categ_where
		GROUP BY csz_id 
		ORDER BY incomplete DESC,csv_name $limit";
$GLOBALS[debug_sql][] = $sql;
$res=mysql_query($sql);
$GLOBALS[benchmark]["main_query"] = microtime(true);


while($r=mysql_fetch_array($res)){
	$forg_kategoriak[$r["csz_id"]] = $r;
	$forg_kats_to_query[$r["csz_id"]] = $r[csv_name];
	$event_ids_to_query[$r["csz_id"]] = $r[event_id];
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

// Aktuálisan listázott elemekhez az event bejegyzések lekérdezése
// ****************************************************************
$event_ids_to_query = implode("', '", array_unique($event_ids_to_query));
$events_query = "SELECT $nocache el_id, el_admin_id, el_date
				 FROM events_log
			 	 WHERE el_id in ('{$event_ids_to_query}') ";
$GLOBALS[debug_sql][] =  $events_query;
$events_res = mysql_query($events_query);
while ($line = mysql_fetch_assoc($events_res)){
	$events[$line[el_id]] = $line;
}
$GLOBALS[benchmark][eventsQuery] = microtime(true);

// Kategóriába tartozó termékek számának lekérdezése    
// ************************************************
$where = array();
if ($_REQUEST["v_id"]){
	$where[] = " pl_vid='".$_REQUEST["v_id"]."' ";
}
$where[] = " pl_category in ('".implode("', '",$forg_kats_to_query)."')";

$where_count = implode(" AND ",$where);
$sql = "SELECT $nocache pl_category, count(distinct pl_pid) cnt 
		FROM pricelists b 
		WHERE $where_count 
		GROUP BY pl_category";
$GLOBALS[debug_sql][] = $sql;
$res = mysql_query($sql);
while ($line = mysql_fetch_assoc($res)){
	$categ_product_count_full[$line[pl_category]] = $line;	
}
$GLOBALS[benchmark]["categ_product_count_full"] = microtime(true);

//print_r($categ_product_count_full);
//echo "<br />";
//echo "<br />";

$where[] = " kw_id is null ";
$where_count = implode(" AND ",$where);
$sql = "SELECT $nocache pl_category, count(distinct pl_pid) cnt
		FROM pricelists b 
		INNER JOIN products a ON a.p_id = b.pl_pid 
		LEFT JOIN product_keyword ON a.p_id = product_keyword.p_id
		WHERE a.p_id=b.pl_pid AND $where_count 
		GROUP BY pl_category";
$GLOBALS[debug_sql][] = $sql;
mysql_query($sql);
$res = mysql_query($sql);
while ($line = mysql_fetch_assoc($res)){
	$categ_product_count_kulcsszonelkuli[$line[pl_category]] = $line;	
}
$GLOBALS[benchmark]["categ_product_count_kulcsszonelkuli"] = microtime(true);

//print_r($categ_product_count_kulcsszonelkuli);
//echo "<br />";
//echo "<br />";


$list["totalCount"]=$total;
foreach($forg_kategoriak as $r){
	//Kulcsszavak lekérdezése a kategóriához
    $kwres=mysql_query("SELECT * FROM csv_szotar_vendor_keywords a,keywords b WHERE a.kw_id=b.kw_id AND csz_id='".$r["csz_id"]."'");
    if(mysql_num_rows($kwres)){
		while($kw=mysql_fetch_array($kwres)){
	    	if(in_array($kw["kw_id"],$groups))
				$tmpkwids[]="<span style='color: red;' ext:qtip='Felvitel: {$kw["admin_id"]} ({$kw["date"]})'>".iconv("iso-8859-2","utf-8",$kw["kw_word"])."</span>";
	    	elseif($kw["kw_2nd_level"]=="1")
				$tmpkwids[]="<span style='color: green;' ext:qtip='Felvitel: {$kw["admin_id"]} ({$kw["date"]})'>".iconv("iso-8859-2","utf-8",$kw["kw_word"])."</span>";
	    	else
			$tmpkwids[]="<span ext:qtip='Felvitel: {$kw["admin_id"]} ({$kw["date"]})'>".iconv("iso-8859-2","utf-8",$kw["kw_word"])."</span>";
		}
		if(count($tmpkwids)){
			 $tmp["keywords"]=implode(" | ",$tmpkwids);
		}
		unset($tmpkwids);
    }
	
   
    
    $tmp["v_id"]=$_REQUEST["v_id"];
    $tmp["id"]=$r["csz_id"];
    $tmp["csz_id"]=$r["csz_id"];
	$tmp["event_id"] = $r["event_id"];
	$tmp[categ_product_count] = "";	
	if ($kesz = ($categ_product_count_full[$r["csv_name"]][cnt] - $categ_product_count_kulcsszonelkuli[$r["csv_name"]][cnt])){
		$tmp[categ_product_count].= "<span style='color: green;'>".$kesz."</span>";
	}else{
		$tmp[categ_product_count].= (int)$kesz;
	}
	$tmp[categ_product_count].=" / ";
	if ($nemkesz = $categ_product_count_kulcsszonelkuli[$r["csv_name"]][cnt]){
		$tmp[categ_product_count].= "<span style='color: red;'>".$nemkesz."</span>";
	}else{
		$tmp[categ_product_count].= (int)$nemkesz;
	}
	
	if ($r["event_id"]){
		$tmp["eventMetaData"] = iconv("iso-8859-2","utf-8",$admin_users[$events[$r["event_id"]][el_admin_id]][au_name])." (".$events[$r["event_id"]][el_date].")";
    }else{
    	$tmp["eventMetaData"] = "<span style=\"font-size: 9px\">Nincs hozzárendelt esemény. A felvitel dátumát az egyes kulcsszavakon lehet látni</span>";
    }
	
    if($r["incomplete"]!="1" && $r["error"]=="")
	$tmp["category"]="<span title='forgId:".$r["v_id"]." - ".iconv("iso-8859-2","utf-8",$r["csv_name"])."' style='color: green;'>".iconv("iso-8859-2","utf-8",$r["csv_name"])."</span>";
    elseif($r["error"]!="")
	$tmp["category"]="<span title='forgId:".$r["v_id"]." - ".iconv("iso-8859-2","utf-8",$r["error"]." - ".$r["csv_name"])."' style='color: red;'>".iconv("iso-8859-2","utf-8",$r["csv_name"])."</span>";
    else
	$tmp["category"]="<span title='forgId:".$r["v_id"]." - ".iconv("iso-8859-2","utf-8",$r["csv_name"])."'>".iconv("iso-8859-2","utf-8",$r["csv_name"])."</span>";

    $tmp["csv_name"]=iconv("iso-8859-2","utf-8",$r["csv_name"]);

    $rlist[]=$tmp;
    unset($tmp);
}

if(isset($rlist) && count($rlist)>0)
    $list["categs"]=$rlist;
else
    $list["categs"]=array();


	$GLOBALS[benchmark][end] = microtime(true);
	if ($_GET[debug]){	
		echo $b;
		foreach ($GLOBALS[benchmark] as $event => $value){
			if ($prev_event){
				echo "$prev_event -> $event: ";
				echo $value-$prev_value;
				echo "<br />";
			}
			$prev_event = $event;
			$prev_value = $value;
		}
		foreach ($GLOBALS[debug_sql] as $sql){
			echo $sql;
			echo "<br />";
			echo "<br />";
		}
	}
	mysql_close(); 
	if ($_GET[debug]){	
		print_r($list);
	}else{
		echo json_encode($list);
	}
}

}else echo "session_timeout";
?>