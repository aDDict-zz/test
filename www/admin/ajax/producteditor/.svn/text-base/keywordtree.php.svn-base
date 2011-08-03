<?php
include "/var/www/www.depo.hu.confs/_config.php";
include "/var/www/www.depo.hu.confs/_functions.php";

mysql_connect("$_db_change_host","$_dbuser","$_dbpass");
mysql_select_db("$_dbname");

$f=fopen('/var/www/depo.hu/www/public/admin_extjs/ajax/generated_json_arrays/adminszotar.json','r');
$contents = fread($f, filesize('/var/www/depo.hu/www/public/admin_extjs/ajax/generated_json_arrays/adminszotar.json'));
fclose($f);

$gres=mysql_query("select a.kg_id as kg_id,kg_name,kw_id from key_groups a,groups_keywords b where kg_type=1 and a.kg_id=b.kg_id order by kg_order");
while($g=mysql_fetch_array($gres))
{
	$groups[$g["kw_id"]]=$g["kg_id"];
}

$contents=str_ireplace('"expanded":true','"expanded":false',$contents);

$res=mysql_query("SELECT kw_id FROM product_keyword WHERE p_id='".$_REQUEST["p_id"]."' GROUP BY kw_id");
if(mysql_num_rows($res))
{
    while($kw=mysql_fetch_array($res))
    {
	// "id":"8-kword-34","checked":false
	if($groups[$kw["kw_id"]])
	    $contents=str_ireplace('"ful-'.$groups[$kw["kw_id"]].'","expanded":false','"ful-'.$groups[$kw["kw_id"]].'","expanded":true',$contents);

	$contents=str_ireplace('kword-'.$kw["kw_id"].'","checked":false','kword-'.$kw["kw_id"].'","checked":true',$contents);
    }
}

mysql_close();

echo $contents;

?>