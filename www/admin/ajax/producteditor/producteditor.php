<?php
session_start();

if(isset($_SESSION["admin_id"]))
{

header('Content-Type: text/html; charset=utf-8');
//header('Content-Type: text/html; charset=ISO-8859-2');
include "/var/www/www.depo.hu.confs/_config.php";
include "/var/www/www.depo.hu.confs/_functions.php";

mysql_connect("$_db_change_host","$_dbuser","$_dbpass");
mysql_select_db("$_dbname");




if(isset($_REQUEST["mode"]))
{
    switch($_REQUEST["mode"])
    {
	case "update":				

	    $m=mysql_fetch_array(mysql_query("SELECT m_name FROM manufacturers WHERE m_id='".$_REQUEST["p_mid"]."'"));

	    if($_REQUEST["p_mid"]!="8")
		$p_fullname=$m["m_name"]." ".$_REQUEST["p_name"];
	    else $p_fullname=$_REQUEST["p_name"];

		$sql = "INSERT INTO events_log(el_admin_id,el_date,el_event_type) values('{$_SESSION[admin_id]}',NOW(),2)";
		mysql_query($sql);
		$change_id = mysql_insert_id();
		
		if ($_REQUEST[event_id]){
			$sql = "UPDATE events_log 
					SET el_change_el_id = '{$change_id}',
						el_change_date = NOW()
					WHERE el_id = '{$_REQUEST[event_id]}' ";
			mysql_query($sql);
		}

	    mysql_query("UPDATE products SET p_fullname='".$p_fullname."',
					     p_name='".addslashes($_REQUEST["p_name"])."',
					     p_attributes='".addslashes($_REQUEST["p_attributes"])."',
					     p_mid='".$_REQUEST["p_mid"]."',
					     p_url='".$_REQUEST["p_url"]."',
					     p_reference='".$_REQUEST["p_reference"]."',
					     p_description='".addslashes($_REQUEST["p_description"])."',
					     p_url_name='".deekezet($_REQUEST["p_name"])."',
					     p_full_url='".deekezet($p_fullname)."',
					     event_id='{$change_id}'
					WHERE p_id='".$_REQUEST["p_id"]."'
			");

	if(isset($_REQUEST["del_picture"]))
	{
	    $r=mysql_fetch_array(mysql_query("SELECT p_picture FROM products WHERE p_id='".$_REQUEST["p_id"]."'"));
	    
	    @unlink($_imagedir_62x50.$r["p_picture"]);
	    @unlink($_imagedir_89x89.$r["p_picture"]);
	    @unlink($_imagedir_160x120.$r["p_picture"]);
	    @unlink($_imagedir.$r["p_picture"]);

	    mysql_query("UPDATE products SET p_picture='' WHERE p_id='".$_REQUEST["p_id"]."'");
	}

    if ($_FILES['picture']['tmp_name']){
           $kit=substr($_FILES['picture']['name'],strlen($t)-3,3);

           if(stristr($_FILES['picture']['name'],".jpg")) $kit="jpg";
           elseif(stristr($_FILES['picture']['name'],".gif")) $kit="gif";
           elseif(stristr($_FILES['picture']['name'],".png")) $kit="png";
    
           $id=$_REQUEST["p_id"];    

	   	   $p_picture=str_replace(" ","_",$id."_".deekezet($_REQUEST["p_name"]).".".$kit); 

           $f=$_FILES['picture']['tmp_name'];
           $fn=$_imagedir.$id."_".deekezet($_REQUEST["p_name"]).".".$kit;
	   
           copy($f,$fn);

           exec("/usr/bin/convert -resize 62x50 -quality 95 -dither -sharpen 2x1 '".$fn."' '".$_imagedir_62x50.$p_picture."'");
           exec("/usr/bin/convert -resize 89x89 -quality 95 -dither -sharpen 2x1 '".$fn."' '".$_imagedir_89x89.$p_picture."'");
           exec("/usr/bin/convert -resize 160x120 -quality 95 -dither -sharpen 2x1 '".$fn."' '".$_imagedir_160x120.$p_picture."'");


	 	mysql_query("UPDATE products SET p_picture='".$p_picture."' WHERE p_id='".$id."'");
    }
    
    if ($_REQUEST["picture_url"]){
    	$kit=substr($_REQUEST["picture_url"],strlen($t)-3,3);

        if(stristr($_REQUEST["picture_url"],".jpg")) $kit="jpg";
        elseif(stristr($_REQUEST["picture_url"],".gif")) $kit="gif";
        elseif(stristr($_REQUEST["picture_url"],".png")) $kit="png";
    	
        $p_picture=str_replace(" ","_",$id."_".deekezet($_REQUEST["p_name"]).".".$kit); 
        $fn=$_imagedir.$id."_".deekezet($_REQUEST["p_name"]).".".$kit;
    	$id=$_REQUEST["p_id"];    
        
    	exec("wget -O{$fn} '{$_REQUEST["picture_url"]}' ");
    	//echo "wget -O{$fn} '{$_REQUEST["picture_url"]}' ";
    	
    	exec("/usr/bin/convert -resize 62x50 -quality 95 -dither -sharpen 2x1 '".$fn."' '".$_imagedir_62x50.$p_picture."'");
        exec("/usr/bin/convert -resize 89x89 -quality 95 -dither -sharpen 2x1 '".$fn."' '".$_imagedir_89x89.$p_picture."'");
        exec("/usr/bin/convert -resize 160x120 -quality 95 -dither -sharpen 2x1 '".$fn."' '".$_imagedir_160x120.$p_picture."'");

	 	mysql_query("UPDATE products SET p_picture='".$p_picture."' WHERE p_id='".$id."'");
    }

	if($_REQUEST[kwchange] && isset($_REQUEST["keywords"]) && isset($_REQUEST["p_id"]))
	{
	    if(strstr($_REQUEST["keywords"],","))
			$dc_ids=explode(",",$_REQUEST["keywords"]);
	    else
			$dc_ids[]=$_REQUEST["keywords"];

	    $pids[]=$_REQUEST["p_id"];

	    foreach($dc_ids as $dc_value){
			$tmp=explode("-",$dc_value);
			$kgids[$tmp[0]]=$tmp[0];
			$kwids["kw_".$tmp[2]]=$tmp[2];
			$p_kwid=$tmp[2];
	    }
		
	    //Nem látok más gyors és szívásmentes megoldást. Ezt még csak le se kérdezzük itt.
	    $eids = array();
	    $sql = "SELECT event_id 
	    		FROM product_keyword 
    			WHERE p_id IN ('".implode("','",$pids)."')";
	    $res = mysql_query($sql);
	    while ($line = mysql_fetch_assoc($res)){
	    	$eids[] = $line[event_id];
	    }
	    $eids = implode("','",$eids);
	    
	    $sql = "INSERT INTO events_log(el_admin_id,el_date,el_event_type) values('{$_SESSION[admin_id]}',NOW(),4)";
		mysql_query($sql);
		$change_id = mysql_insert_id();

		if (trim($eids)){
			$sql = "UPDATE events_log 
					SET el_change_el_id = '{$change_id}',
						el_change_date = NOW()
					WHERE el_id in ('{$eids}') ";
			mysql_query($sql);
		} 
	    
	    
	    
	    
	    
    				
	    $kgres=mysql_query("select kw_id from key_groups a,groups_keywords b where kg_type=1 and a.kg_id=b.kg_id and a.kg_id in (".implode(",",$kgids).") order by kg_order");
	    while($g=mysql_fetch_array($kgres)){
	        $kgkwids["kw_".$g["kw_id"]]=$g["kw_id"];
	    }

	    $allkwids=array_merge($kgkwids,$kwids);

	    if(count($pids)>0)
	    {
    		$all_kwid=",".implode(",",$allkwids).",";
		
			foreach($pids as $pid)
			{
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



		$success = true;
	break;

    }
}






$pres=mysql_query("SELECT * FROM products WHERE p_id='".$_REQUEST["p_id"]."'");
$total=mysql_num_rows($pres);
while($p=mysql_fetch_array($pres))
{
    unset($kws);
    $kwres=mysql_query("SELECT a.kw_id as kw_id,kw_word,kw_2nd_level FROM product_keyword a,keywords b WHERE a.kw_id=b.kw_id AND a.p_id='".$p["p_id"]."'");
    if(mysql_num_rows($kwres))
    {
	while($f=mysql_fetch_array($kwres))
	{
	    $kws[]=iconv("iso-8859-2","utf-8",$f["kw_word"]);
	}
	$tmp["keywords"]=implode(", ",$kws);
	unset($kws);
    }else $tmp["keywords"]=iconv("iso-8859-2","utf-8","nincs kulcsszó");


    $fres=mysql_query("select a.fi_name as fi_name from filter_items a,product_filter b where a.fi_id=b.fi_id and p_id='".$p["p_id"]."'");
    if(mysql_num_rows($fres))
    {
	while($f=mysql_fetch_array($fres))
	{
	    $fs[]=iconv("iso-8859-2","utf-8",$f["fi_name"]);
	}
	$tmp["filters"]=implode(", ",$fs);
	unset($fs);
    }else $tmp["filters"]=iconv("iso-8859-2","utf-8","nincs");


	if($p["p_picture"]!="" && is_file("/var/www/depo.hu/www/public/images/89x89/".$p["p_picture"])){
	    $picture="<img src='http://depo.hu/images/89x89/".$p["p_picture"]."' />";
	    $size = getimagesize("/var/www/depo.hu/www/public/images/".$p["p_picture"]);	    
	    $picsize = $size[0]."x".$size[1];
	}else {
	    $picture="";
	}
    $tmp["id"]=$p["p_id"];
    $tmp["p_id"]=$p["p_id"];
    $tmp["p_mid"]=$p["p_mid"];
    $tmp["p_url"]=iconv("iso-8859-2","utf-8",$p["p_url"]);
    $tmp["p_name"]=stripslashes(iconv("iso-8859-2","utf-8",$p["p_name"]));
    $tmp["p_fullname"]=stripslashes(iconv("iso-8859-2","utf-8",$p["p_fullname"]));
    $tmp["p_attributes"]=stripslashes(iconv("iso-8859-2","utf-8",$p["p_attributes"]));
    $tmp["p_description"]=iconv("iso-8859-2","utf-8",stripslashes(str_replace("\r","",str_replace("\n","",$p["p_description"]))));
    $tmp["p_picture"]=$p["p_picture"];
    $tmp["event_id"]=$p["event_id"];
    $tmp["picsize"]=$picsize;
    $tmp["kwchange"]=0;
    $tmp["p_reference"]=$p["p_reference"];
//    $tmp["p_picture_image"]=$picture;
  
    $plist[]=$tmp;
    $response[] = array(
    	"kwchange" => 0,
    	"event_id" => $p["event_id"]
    );    
}

$list=array();
$list["totalCount"]=$total;	
if (isset($_REQUEST["mode"])){
	if ($success){
		$list["success"] = $success; 
	}
	$list["prods"] = $response;
}else{	
	if(isset($plist) && count($plist)>0)
	    $list["prods"]=$plist;
	else
	    $list["prods"]=array();
}


mysql_close();

echo json_encode($list);

}else echo "session_timeout";

?>
