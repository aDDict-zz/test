<?
include "auth.php";
$weare=14;
$sgweare=114;
include "cookie_auth.php";  
  
$group_id=get_http("group_id",0);
$multiid=get_http("multiid",0);

if ($group_id) {
    $group_id=addslashes($group_id);
    $mres = mysql_query("select groups.* from groups,members where groups.id=members.group_id
                         and groups.id='$group_id' and user_id='$active_userid' 
                         and (membership='owner' or membership='moderator' $admin_addq)");
    if ($mres && mysql_num_rows($mres))
        $rowg=mysql_fetch_array($mres);  
    else {
        header("Location: index.php?no_group=1"); exit; }
    $title=$rowg["title"];
    $multi_id=0;
}
else {
    $multi_id=intval($multi_id);
    $mres = mysql_query("select * from multi,multi_members where multi.id=multi_members.group_id
                         and multi.id='$multiid' and user_id='$active_userid' 
                         and membership='moderator'");
    if ($mres && mysql_num_rows($mres))
        $rowg=mysql_fetch_array($mres);  
    else {
        header("Location: index.php?no_group=1"); exit; }
    $group_id="";
    $title=$rowg["title"];
}

include "menugen.php";
include "./lang/$language/settings.lang";

$ntypes=array("validate");
$gfilt="multi_id='$multi_id'";
if ($group_id) {
    $ntypes[]="delete";
    $gfilt="group_id='$group_id'";
}

$ismsg=0;
if (isset($_POST["enter"])) {
    $errors=array();
    $sets=array();
    foreach ($ntypes as $ntype) {
        $active="no";
        if (isset($_POST["active_$ntype"])) {
            $active="yes";
        }
        $base_id=0;
        if (isset($_POST["base_$ntype"])) {
            $base_id=intval($_POST["base_$ntype"]);
        }
        $rs=mysql_query("select * from sender_base where id='$base_id' and $gfilt");
        if (!($rs && mysql_num_rows($rs))) {
            $base_id=0;
        }
        if ($active=="yes" && $base_id==0) {
            $errors[]="$word[re_nobase1] &quot;".$word["re_$ntype"]."&quot; $word[re_nobase2]";
        }
        $sender_id=0;
        if (isset($_POST["sender_$ntype"])) {
            $sender_id=intval($_POST["sender_$ntype"]);
        }
        $rs=mysql_query("select * from members where user_id='$sender_id' and $gfilt and membership in ('moderator','owner','support')");
        if (!($rs && mysql_num_rows($rs))) {
            $sender_id=0;
        }
        if ($active=="yes" && $sender_id==0) {
            $errors[]="$word[re_nosender1] &quot;".$word["re_$ntype"]."&quot; $word[re_nosender2]";
        }
        $sets["$ntype"]="active='$active',base_id='$base_id',sender_id='$sender_id'";
    }
    if (count($errors)==0) {
        foreach ($sets as $ntype=>$set) {
            $res=mysql_query("select id from maint_notify where $gfilt and ntype='$ntype'");
            if ($res && mysql_num_rows($res)) {
            	$q="update maint_notify set $set where $gfilt and ntype='$ntype'";
                mysql_query($q);
                logger($q,$group_id,"","","main_notify");
            }
            else {
				$q="insert into maint_notify set $set,$gfilt,ntype='$ntype'";
                mysql_query($q);
                logger($q,$group_id,"","","main_notify");                
            }
            print mysql_error();
        }
    }
    if (count($errors)) {
        $ismsg=1;
        $msg=implode("<br>",$errors);
    }
    else {
        $ismsg=2;
        $msg=$word["data_changed"];
    }
}

$hiddens=array(array("multiid",$multiid),array("group_id",$group_id),array("enter","1"));
$hidden="";
$getvars="";
foreach ($hiddens as $hide) {
    $hidden.="<input type='hidden' name='$hide[0]' value=\"$hide[1]\"/>";
    if ($hide[0]!="enter") {
        $getvars.="&$hide[0]=$hide[1]";
    }
}

$formw=650;

print "<form action='resend.php' method='post' name='funky'>$hidden
       <table width=100% border=0 cellspacing=1 cellpadding=0 class='bordercolor'>";

if ($ismsg) {
    print "<tr><td colspan='4' width='100%'><table width=100% border=0 cellspacing=0 cellpadding=2><tr>
               <td class='formmezo' width='100%'>$msg</td>
           </tr></table></td></tr>";
}
print "<tr><td class='bgvilagos2' width='20%'><span class='szovegvastag'>$word[re_notify]</span></td><td class='bgvilagos2' width='10%'><span class='szovegvastag'>$word[re_active]</span></td><td class='bgvilagos2' width='25%'><span class='szovegvastag'>$word[re_base]</span></td><td class='bgvilagos2' width='45%'><span class='szovegvastag'>$word[re_sender]</span></td></tr>";
$db=array();
$res=mysql_query("select * from maint_notify where $gfilt");
logger($q,$group_id,"","","main_notify");
if ($res && mysql_num_rows($res)) {
    while ($k=mysql_fetch_array($res)) {
        $db["$k[ntype]"]=$k;
    }
}

foreach ($ntypes as $ntype) {
    $active="no";
    $base_id=0;
    $sender_id=0;
    if (isset($_POST["enter"])) {
        $active="no";
        if (isset($_POST["active_$ntype"])) {
            $active="yes";
        }
        if (isset($_POST["base_$ntype"])) {
            $base_id=$_POST["base_$ntype"];
        }
        if (isset($_POST["sender_$ntype"])) {
            $sender_id=$_POST["sender_$ntype"];
        }
    }
    elseif (isset($db["$ntype"])) {
        $active=$db["$ntype"]["active"];
        $base_id=$db["$ntype"]["base_id"];
        $sender_id=$db["$ntype"]["sender_id"];
    }
    $base="<select name='base_$ntype'><option value='0'> --- </option>";
    $res=mysql_query("select * from sender_base where $gfilt order by name");
    if ($res && mysql_num_rows($res)) {
        while ($k=mysql_fetch_array($res)) {
            $base_id==$k["id"]?$sel=" selected":$sel="";
            $base.="<option value='$k[id]'$sel>". htmlspecialchars($k["name"]) ."</option>";
        }
    }
    $base.="</select>";
    $sender="<select name='sender_$ntype'><option value='0'> --- </option>";
    $res=mysql_query("select u.id,concat(u.email,' <',u.name,'>') as name from members m, user u 
                      where $gfilt and m.membership in ('moderator','owner','support') 
                      and m.user_id=u.id order by u.email");
    if ($res && mysql_num_rows($res)) {
        while ($k=mysql_fetch_array($res)) {
            $sender_id==$k["id"]?$sel=" selected":$sel="";
            $sender.="<option value='$k[id]'$sel>". htmlspecialchars($k["name"]) ."</option>";
        }
    }
    $sender.="</select>";
    $active=="yes"?$checked="checked":$checked="";
    print "<tr><td class='bgvilagos2'>".$word["re_$ntype"]."</td><td class='bgvilagos2'><input type='checkbox' name='active_$ntype' $checked></td><td class='bgvilagos2'>$base</td><td class='bgvilagos2'>$sender</td></tr>";
}
print "<TR><TD align='center' colspan='4'><INPUT class='tovabbgomb' type=submit name='saveall' value=\"$word[submit3]\"></TD></TR>
       </TABLE>
       </form>\n";	  
  
include "footer.php";
?>
