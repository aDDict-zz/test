<?
session_start();
$GLOBALS[benchmark][begin] = microtime(true);
if (!$_SESSION[admin_id]){
	exit("Nem vagy bejelentkezve");
}

header('Content-Type: text/html; charset=utf-8');
include "/var/www/www.depo.hu.confs/_config.php";
mysql_connect("$_db_change_host","$_dbuser","$_dbpass");
mysql_select_db("$_dbname");

if ($_GET[debug]){
	$nocache = " SQL_NO_CACHE ";
}


$GLOBALS[benchmark][init] = microtime(true);
$query = "SELECT $nocache pl_id, vendor.v_name, pl_vendor_p_name vendor_name, pl_vendor_picture url
		  FROM pricelists 
		  LEFT JOIN vendor ON pricelists.pl_vid = vendor.v_id
		  WHERE pl_pid = '{$_REQUEST[p_id]}'
		    AND pl_vendor_picture != '' AND pl_vendor_picture is not NULL";

$GLOBALS[querys][] = $query;
$result = mysql_query($query);
$GLOBALS[benchmark][pricelists] = microtime(true);	
	    
while ($line = mysql_fetch_assoc($result)){
	unset($tmp);
	$size = getimagesize($line[url]);
	if ($size[0] && $size[1]){
		$tmp[picsize] = $size[0]."x".$size[1];
		$tmp[pl_id] = $line[pl_id];
		$tmp[v_name] = iconv("iso-8859-2","utf-8",$line["v_name"]);
		$tmp[vendor_name] = iconv("iso-8859-2","utf-8",$line["vendor_name"]);
		$tmp[url] = $line[url];
		$pl_list[] = $tmp;
		$totalCount++;
	}
}
$GLOBALS[benchmark][getimagesize] = microtime(true);		    




$list["totalCount"]=(int)$totalCount;
if(isset($pl_list) && count($pl_list)>0){
    $list["pics"]=$pl_list;
}else{
    $list["pics"]=array();
}


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
	
	foreach ($GLOBALS[querys] as $item){
		echo $item;
		echo "<br /><br />\n\n";
	}
	
}

mysql_close(); 


if ($_GET[debug]){	
	print_r($list);
}else{
	echo json_encode($list);
}

?>