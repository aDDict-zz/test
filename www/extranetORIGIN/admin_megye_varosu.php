<?
$_MX_superadmin=0;
include "auth.php";
include "cookie_auth.php";  
if (!$_MX_superadmin) {
    exit;
}

$group_id=addslashes($group_id);
$res=mysql_query("select id,title from groups where id='$group_id'");
if ($res && mysql_num_rows($res)) {
    $k=mysql_fetch_array($res);
    $title=$k["title"];
    $group_id=$k["id"];
}
else
    exit;

$minid=0;
while (list($user_id,$action)=each($mv_radio)) {
    if (!get_magic_quotes_gpc()) {
        $user_id=addslashes($user_id);
        $group_id=addslashes($group_id);
        $mv_ir[$user_id]=addslashes($mv_ir[$user_id]);
        $mv_megye[$user_id]=addslashes($mv_megye[$user_id]);
        $mv_varos[$user_id]=addslashes($mv_varos[$user_id]);
        $mv_comment[$user_id]=addslashes($mv_comment[$user_id]);
    }
    $mv_ir[$user_id]=trim($mv_ir[$user_id]);
    $mv_megye[$user_id]=trim($mv_megye[$user_id]);
    $mv_varos[$user_id]=trim($mv_varos[$user_id]);

    if ($mv_varos[$user_id]) 
        $vpart=",ui_varos='$mv_varos[$user_id]'";
    else
        $vpart="";
    if ($mv_megye[$user_id]) 
        $mpart=",ui_megye=',$mv_megye[$user_id],'";
    else
        $mpart="";
    
    if ($action=="change") {
        $q="update users_$title set ui_ir='$mv_ir[$user_id]'$mpart$vpart where id='$user_id'";
        //print "$q<br>";
        mysql_query($q);
    }
    elseif ($action=="ignore") {
        $r=mysql_query("select id from admin_mv_ignored where user_id='$user_id' and group_id='$group_id'");
        if ($r && mysql_num_rows($r))
            $q="update admin_mv_ignored set comment='$mv_comment[$user_id]' where user_id='$user_id' and group_id='$group_id'";
        else
            $q="insert into admin_mv_ignored (comment,user_id,group_id) values ('$mv_comment[$user_id]','$user_id','$group_id')";
        //print "$q<br>";
        mysql_query($q);
    }
    if (!$minid)
        $minid=$user_id;
    if ($user_id<$minid)
        $minid=$user_id;
}

header("Location: admin_megye_varos.php?group_id=$group_id&mv_suid=$minid");
?>
