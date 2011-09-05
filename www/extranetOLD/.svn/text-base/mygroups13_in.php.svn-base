<?
include "auth.php";
include "decode.php";
$_MX_superadmin=0;
include "cookie_auth.php";
include "common.php";
$language=select_lang();
include "./lang/$language/mygroups13.lang";      
include "_demog.php";

if (!$_MX_superadmin) {
    exit;
}

$demog_id=$_GET["demog_id"];
if (!ereg("^[0-9]+$",$demog_id)) {
    exit;
}

$res=mysql_query("select * from demog where id='$demog_id'");
if (!($res && mysql_num_rows($res))) {
    exit;
}
else {
    $gr=mysql_fetch_array($res);
}

$_MX_demog = new MxDemog();
$allparm=$_MX_demog->GetParams();

$ingroups=array();
$res=mysql_query("select g.title from groups g,vip_demog vd where vd.demog_id='$demog_id' and vd.group_id=g.id");
if ($res && mysql_num_rows($res)) {
    while ($k=mysql_fetch_array($res)) {
        $ingroups[]=$k["title"];
    }
}

if (count($ingroups)) {
    header ("location: mygroups13.php?$allparm&delnosucc=$gr[variable_name]|".implode(",",$ingroups));
    exit;
}
    
$res=mysql_query("select id from demog_enumvals where demog_id='$demog_id'");
if ($res && mysql_num_rows($res)) {
    while ($k=mysql_fetch_array($res)) {
        mysql_query("delete from form_element_enumvals where demog_enumvals_id='$k[id]'");
    }
}
mysql_query("delete from demog_enumvals where demog_id='$demog_id'");
mysql_query("delete from vip_demog where demog_id='$demog_id'");
mysql_query("delete from form_element where demog_id='$demog_id'");
mysql_query("delete from demog where id='$demog_id'");

header ("location: mygroups13.php?delsucc=$gr[variable_name]&$allparm");
?>
