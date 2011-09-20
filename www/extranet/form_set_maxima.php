<?
$linkID = mysql_connect("localhost", "root", "v");
$succDB = mysql_select_db("maxima", $linkID);
mysql_query("set names utf8");

// called through xmlreq, directly when previewing or indirectly included in production environment with absolute path
// xmlreq('form_set_maxima.php?mn='+mx_maxima+'&psv='+savestr,mx_noresp);
// http://office.manufacture.co.yu/maxima/form_set_maxima.php?mn=mxform35&psv=lifestyle_new%7C4006_4002%2C4011_4003%7Cszuletesnap%7C-0-0

//print_r($_POST);

$form_id=0;
$preview=0;
if (isset($_POST["mn"])) {
    $maximaname=mysql_escape_string($_POST["mn"]);
}
elseif (isset($_POST["__mn__"])) {
    $maximaname=mysql_escape_string($_POST["__mn__"]);
}
else {
    $maximaname="";
}
if (isset($_POST["__psv__"])) {
    $formdata=mysql_escape_string($_POST["__psv__"]);
    $form_id=mysql_escape_string($_POST["data__"]);
    $res=mysql_query("select * from form where id='$form_id'");
    if ($res && mysql_num_rows($res)) {
        $fd=mysql_fetch_array($res);
        $form_id=$fd["id"];
    }
    else {
        $form_id=0;
    }
}
elseif (isset($_POST["psv"])) {
    $formdata=mysql_escape_string($_POST["psv"]);
}
else {
    $formdata="";
}
$cid="";
$cluster=0;
$vals=explode("|",rawurldecode($formdata));
for ($j=0;$j<count($vals);$j+=2) {
    if (isset($vals[$j+1]) && $vals[$j]=="cid") {
        $cid=mysql_escape_string($vals[$j+1]);
    }
    if (isset($vals[$j+1]) && $vals[$j]=="__preview__") {
        $preview=mysql_escape_string($vals[$j+1]);
    }
    if (isset($vals[$j+1]) && $vals[$j]=="__cluster__") {
        $cluster=mysql_escape_string($vals[$j+1]);
    }
}

if (strlen($cid)>5) {
    $res=mysql_query("select formdata from form_save_temporary where cid='$cid' and maximaname='$maximaname'");
    if ($res && mysql_num_rows($res)) {
        mysql_query("update form_save_temporary set formdata='$formdata' where cid='$cid' and maximaname='$maximaname'");
    }
    else {
        mysql_query("insert into form_save_temporary set formdata='$formdata',cid='$cid',maximaname='$maximaname',dateadd=now()");
    }
}
/*

Clusters will be removed

if ($form_id) {
    if ($preview) {
        $next_cluster="form_generate.php?group_id=$fd[group_id]&form_id=$form_id&preview=1&cid=$cid&fwt=$cluster";
    }
    else {
        $next_cluster="tesztkerdoiv.php?cid=$cid";
    }
    header("location: $next_cluster");
}
*/
?>
