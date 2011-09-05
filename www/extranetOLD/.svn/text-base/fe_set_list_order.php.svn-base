<?
include "auth.php";

$items_id=$_REQUEST["itemsid"];
$form_id=$_REQUEST["form_id"];
$group_id=$_REQUEST["group_id"];
$items_tmp=explode("|",$items_id);
$ind=1;
$ids=array();
foreach ($items_tmp as $id) {
    $tmp=explode("_",$id);
    if ($tmp[1]) {
	$res=mysql_query("select id from form_element where page='$tmp[0]' and form_id='$form_id'");
        while ($w=mysql_fetch_array($res)) {
            $ids[$ind].=$w["id"].",";
	}    
    }
    ++$ind;
}
$ind=1;
foreach ($items_tmp as $id) {
    $tmp=explode("_",$id);
    if ($tmp[1]) {
        $ids[$ind]=ereg_replace(",$","",$ids[$ind]);
	$query="update form_element set page=$ind where id in ($ids[$ind]) and form_id=$form_id";	
	mysql_query($query);
	echo $id."-".$ind."_".$tmp[1].",";
    }
    ++$ind;
}
?>
