<?
include "auth.php";
$weare=51;
include "cookie_auth.php";  
include "common.php";

$email=slasher($email);
$group_id=intval($group_id);

# create list of group_ids user has access to.
# if there is none, user does not have access to this script.
# here is a little problem with authentication, because on the site auth is based on the 
# single group_id, user_id and the kind of membership in that group, if all is ok, he can
# operate on that group. In this script, however, user operates on all his groups 
# (depending, again, on kind of the membership!). To stick to some conventions, access  
# will not be granted if user is not member of the given group_id.

$access=0;
$group_id_list="";
$titles=array();
$mres = mysql_query("select groups.id,title,num_of_mess from groups,members where groups.id=members.group_id
                     and user_id='$active_userid' 
                     and (membership='owner' or membership='moderator' or membership='support' $admin_addq)");
if ($mres && mysql_num_rows($mres)) {
    while ($k=mysql_fetch_array($mres)) {
        if ($k["id"]==$group_id) {
            $access=1; # putting this out of the 'if' statement would be enough, see the above comment.
            $title=$k["title"]; # this also comes from the old convention.
        }
        empty($group_id_list)?$group_id_list="$k[id]":$group_id_list.=",$k[id]";
        $titles["$k[id]"]=$k["title"];
    }
}
if (!$access) 
    exit;

$i=0;
$csv="";
if (($deluser || $signuser) && is_array($emaillist)) {
    while (list($key, $val) = each($emaillist)) {
        $key=slasher($key);
        $i++;
        empty($csv)?$csv="'$key'":$csv.=",'$key'";
        if ($i==13) {
            $i=0;
            reset($titles);
            while (list(,$gtitle)=each($titles)) {
                if ($deluser)
                    $bq=("delete from users_$gtitle where ui_email in ($csv)");
                else
                    $bq=("update users_$gtitle set bounced='yes',tstamp=now() where ui_email in ($csv)");
                mysql_query($bq);
                //print "<br>$bq";
            }
            $csv="";
        }
    }
    if (!empty($csv)) {
        reset($titles);
        while (list(,$gtitle)=each($titles)) {
            if ($deluser)
                $bq=("delete from users_$gtitle where ui_email in ($csv)");
            else
                $bq=("update users_$gtitle set bounced='yes',tstamp=now() where ui_email in ($csv)");
            mysql_query($bq);
            //print "<br>$bq";
        }
    }
}
header("Location: bounce.php?group_id=$group_id");
?>
