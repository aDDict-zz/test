<?php
session_start();
if(isset($_SESSION["admin_id"]))
{
header('Content-Type: text/html; charset=iso-8859-2');

include "/var/www/www.depo.hu.confs/_config.php";
include "/var/www/www.depo.hu.confs/_functions.php";
include "/var/www/depo.hu/common/generated_arrays/_subwords.php";

mysql_connect("$_db_change_host","$_dbuser","$_dbpass");
mysql_select_db("$_dbname");

$i=0;
$gres=mysql_query("select a.kg_id as kg_id,kg_name,kw_id from key_groups a,groups_keywords b where kg_type=1 and a.kg_id=b.kg_id order by kg_order");
while($g=mysql_fetch_array($gres))
{
	$groups[$i]=$g["kw_id"];
	$gnames[$i]=$g["kg_name"];
	$groupids[$i]=$g["kg_id"];
	$i++;
}

foreach($groups as $k=>$g){
    if($old_group_name!=$gnames[$k] && $gnames[$k]!="")
    {

	    $tmp["id"]="ful-".$groupids[$k];
    	    $tmp["expanded"]=true;
    	    $tmp["text"]=iconv("iso-8859-2","utf-8",$gnames[$k]);
	    $tmp["cls"]="folder";
	    $tmp["leaf"]=false;  // ha nincs elem akkor true

	    $old_group_name=$gnames[$k];
    }

    if($groupids[$k]==8){
        $kwres=mysql_query("select kw_id,kw_word from keywords where kw_id in (".implode(",",$_subwords[8]).") order by kw_word");
    }elseif($groupids[$k]==9){
        $kwres=mysql_query("select kw_id,kw_word from keywords where kw_id in (".implode(",",$_subwords[9]).") order by kw_word");
    }else
	$kwres=mysql_query("select a.kw_id,kw_word from keywords a,product_keyword b where a.kw_id=b.kw_id and all_kwid like '%,".$g.",%' and kw_2nd_level='1' group by a.kw_id order by kw_word");

// ha nincs eredmeny akkor egyszintes ful
    if(!mysql_num_rows($kwres))
	$kwres=mysql_query("select a.kw_id as kw_id,kw_word from keywords a,product_keyword b where a.kw_id=b.kw_id and all_kwid like '%,".$g.",%' group by a.kw_id order by kw_word");

    if(mysql_num_rows($kwres))
    {
	while($kw=mysql_fetch_array($kwres))
	{
	
	    if(!in_array($kw["kw_id"],$groups))
	    {

    		$tmp1["id"]=$groupids[$k]."-kword-".$kw["kw_id"];
    		$tmp1["checked"]=false;
    		$tmp1["text"]="<span title='".iconv("iso-8859-2","utf-8",$kw["kw_word"])."'>".iconv("iso-8859-2","utf-8",$kw["kw_word"])."</span>";
    		$tmp1["leaf"]=true;
    		$tmp1["cls"]="file";

    		$tmp2[]=$tmp1;
    		unset($tmp1);
	    }
	    $tmp["children"]=$tmp2;
	}
    
	$nodes[]=$tmp;
	unset($tmp);
	unset($tmp2);

     }
}

mysql_close();

$f=fopen("/var/www/depo.hu/www/public/admin_extjs/ajax/generated_json_arrays/adminszotar.json","w+");
fwrite($f,json_encode($nodes));
fclose($f);

echo json_encode($nodes);

}else echo "session_timeout";
?>