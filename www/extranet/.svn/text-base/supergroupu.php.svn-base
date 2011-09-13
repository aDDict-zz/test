<?
include "auth.php";
$_MX_superadmin=0;
include "cookie_auth.php";

$multiid=intval(get_http("multiid",0));
$addgroup=intval(get_http("addgroup",0));
$delgroup=intval(get_http("delgroup",0));

$mres = mysql_query("select m.id from multi m inner join multi_members mm where m.id='$multiid' and m.id=mm.group_id and mm.user_id='$active_userid' and mm.membership='moderator'");
if ($mres && mysql_num_rows($mres)) {
    $row=mysql_fetch_array($mres);
}
else {
    header("Location: index.php");
    exit;
}

if ($delgroup) {
    mysql_query("delete from multigroup where multiid='$multiid' and groupid='$delgroup'");
}
if ($addgroup) {
    if ($_MX_superadmin) {
        $q="select id from groups where id='$addgroup'";
    }
    else {
        $q="select m.id from groups m inner join members mm where m.id='$addgroup' and m.id=mm.group_id and mm.user_id='$active_userid' and mm.membership='moderator'";
    }
    $res=mysql_query($q);
    if ($res && mysql_num_rows($res)) {
        $res2=mysql_query("select id from multigroup where multiid='$multiid' and groupid='$addgroup'");
        if ($res2 && !mysql_num_rows($res2)) {
            mysql_query("insert into multigroup (multiid,groupid,tstamp) values ('$multiid','$addgroup',now())");
        }
    }
}

header("Location: supergroup.php?multiid=$multiid");
?>
