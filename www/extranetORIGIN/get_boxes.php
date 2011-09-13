<?
include "auth.php";

$demog_id=$_REQUEST["demog_id"];
$group_id=$_REQUEST["group_id"];
$form_id=$_REQUEST["form_id"];
$cpagebox=$_REQUEST["cpagebox"];
$comboseltext=$_REQUEST["comboseltext"];

$optionlist=array();
$query=mysql_query("SELECT boxes FROM form_page WHERE group_id='$group_id' AND form_id='$form_id' AND page_id='$comboseltext'");
if ($query && mysql_num_rows($query)) 
{
	$row = mysql_fetch_array($query);
    for ($j = 0; $j < $row['boxes']; ++$j) 
	{
    	$box=chr(ord('A')+$j);
        $cpagebox=="$comboseltext$box"?$sel="checked":$sel="";
        //		$optionlist.="&nbsp;<input type='radio' value='$comboseltext$box' $sel name='demogr_id[$demog_id]'>'$comboseltext$box'";
    	$optionlist[]="$comboseltext$box"."||".$sel;
	}
}
echo $demog_id."**".implode("|:|",$optionlist);


?>
