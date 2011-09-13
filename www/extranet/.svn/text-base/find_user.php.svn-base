<?
include "auth.php";
$weare=50;
include "cookie_auth.php";  

$mres = mysql_query("select title,num_of_mess from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and user_id='$active_userid' 
                     and (membership='owner' or membership='moderator' or membership='support' $admin_addq)");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
/*else {
    header("Location: $_MX_var->baseUrl/index.php"); exit; }*/
$title=$rowg["title"];

include "menugen.php";
include "./lang/$language/find_user.lang";
include "./lang/$language/members.lang";

// admin: meg kell nezni mely csoportokban van admin jog rendelve ehhez az oldalhoz es azokban is keresni/leiratni/torolni kell
// az $admin_addq nyilvan nem jo mert akkor barmelyik csoportban keresne ahol admin fuggetlenul attol hogy megkapta-e ezt az oldalat vagy nem
// masik problema a demog info, ha az admin megkapja ezt az oldalat de a tagok oldalat nem (igaz ez nem tul logikus beallitas)

if ($_REQUEST['grid']!=NULL) {
	$grid=$_REQUEST['grid'];
	$query="select groups.id,groups.title from groups,members where groups.id=members.group_id
                 and (membership='owner' or membership='moderator' or membership='support') 
                 and members.user_id='$active_userid' and groups.id=$grid";	
	$rr=mysql_query($query);
	$k=mysql_fetch_array($rr);
	$title=$k["title"];
	logger($query,$grid,"","group_title=".$title,"groups,members");
}
$femail=get_http("femail","");
$deluser_id=get_http("deluser_id","");
$unuser_id=get_http("unuser_id","");
$delete=get_http("delete","");
$saveall=get_http("saveall","");
$hfemail=slasher($femail,-1);
$sfemail=slasher($femail);
$checkboxes="";
$unsubbed;
$deluser_id=slasher($deluser_id);
$unuser_id=slasher($unuser_id);
if ($deluser_id != NULL) 
{
	$query="delete from users_$title where id=$deluser_id";
	$r5=mysql_query($query);
	$unsubbed.="<br>$hfemail $word[success3] $title $word[success2].";
	logger($query,0,"","delete uid=".$deluser_id,"users_$ftitle");	
}
if ($unuser_id != NULL) {
    $unique_col="";
    $unsub_email="";
    $r9=mysql_query("select unique_col from groups where title='$title'");
    if ($r9 && mysql_num_rows($r9)) {
        $unique_col=mysql_result($r9,0,0);
    }
    $r9=mysql_query("select ui_email from users_$title where id='$unuser_id'");
    if ($r9 && mysql_num_rows($r9)) {
        $unsub_email=mysql_result($r9,0,0);
    }
    if ($unique_col=="email" && !empty($unsub_email)) {
        $succ=mx_ppos_unsub($unsub_email,$title,"php-find-user");
        $query="$succ=mx_ppos_unsub($unsub_email,$title,php-find-user)";
    }
    else {
        $query="update users_$title set robinson='yes' where id=$unuser_id";
        $r5=mysql_query($query);
    }
	$unsubbed.="<br>$hfemail $word[success1] $title $word[success2].";
	logger($query,0,"","unsubscribe uid=".$unuser_id,"users_$ftitle");		
}

if (!empty($_MX_superadmin)) {
	$rr=mysql_query("select groups.id,groups.title from groups");
}
else {
$rr=mysql_query("select groups.id,groups.title from groups,members where groups.id=members.group_id
                 and (membership='owner' or membership='moderator' or membership='support') 
				 and members.user_id='$active_userid'");
}

$funsub=$_POST["funsub"];

if ($rr && mysql_num_rows($rr)) {	
	logger("",0,"","search for email=".$sfemail,"users_");                         
    while ($k=mysql_fetch_array($rr)) {
        $fid=$k["id"];
        $ftitle=$k["title"];
        $query="select id from users_$ftitle where ui_email='$sfemail' and length(ui_email)>0
                         and validated='yes' and robinson='no' and bounced='no'";
//                         ";
        $r2=mysql_query($query);
        if ($r2 && mysql_num_rows($r2)) {
			$ro=mysql_fetch_array($r2);
			$usid=$ro["id"];
            if ($funsub[$fid]) {
				if (slasher($delete)!=NULL)
				{
					$query="delete from users_$ftitle where ui_email='$sfemail'";
					$r5=mysql_query($query);
	            	$unsubbed.="<br>$hfemail $word[success3] $ftitle $word[success2].";
				}
                else
				{
                    $succ=mx_ppos_unsub($sfemail,$ftitle,"php-find-user-cb");
                    $query="$succ=mx_ppos_unsub($sfemail,$ftitle,php-find-user-cb)";
                	$unsubbed.="<br>$hfemail $word[success1] $ftitle $word[success2].";
				}
				logger($query,$grid,"","ui_email=".$sfemail,"users_$ftitle");
            }
            else {
                $checkboxes.="<br><input type='checkbox' name='funsub[$fid]'> $ftitle [<a href='#' onClick='window.open(\"$_MX_var->baseUrl/members_demog_info.php?group_id=$fid&user_id=$usid\", \"m_d_i\", \"width=510,height=400,scrollbars=yes,resizable=yes\"); return false;'>$word[demog_info]</a>] 
				[<a onClick=\"return(confirm('$word[sure_delete]'))\" href='$_MX_var->baseUrl/find_user.php?group_id=$group_id&grid=$fid&deluser_id=$usid&femail=$femail''>$word[delete]</a>] 
				[<a onClick=\"return(confirm('$word[sure_unsubscribe]'))\" href='$_MX_var->baseUrl/find_user.php?group_id=$group_id&grid=$fid&unuser_id=$usid&femail=$femail''>$word[unsubscribe]</a>] ";
            }
                
        }
    }
}

if (!empty($checkboxes))
    $checkboxes.="<br>$word[select1] $hfemail $word[select2]. [<a href='javascript:ToggleAll(true)'>$word[all]</a>] [<a href='javascript:ToggleAll(false)'>$word[none]</a>]";

echo "<br>
<script>
function ToggleAll(checked) {
  len = document.mainform.elements.length;
  var i=0;
  for(i=0; i < len; i++) {
    document.mainform.elements[i].checked=checked;
  }
}
</script>
<table border=0 cellspacing=0 cellpadding=1 bgcolor=$_MX_var->main_table_border_color width=100%>
<tr><td class=formmezo>
&nbsp;$word[find_user]
</td></tr>
<tr><td>
<table border=0 cellspacing=1 cellpadding=1 bgcolor=#eeeeee width=100%>
  <form name='mainform' method='post'>
  <input type='hidden' name='group_id' value='$group_id'>  
  <input type='hidden' name='grid' value=''>  
  <input type='hidden' name='unuser_id' value=''>
  <input type='hidden' name='deluser_id' value=''>    
  <tr>
    <td class=bgvilagos2>
      <span class='szoveg'>$word[email]:&nbsp;<input type='text' name='femail' value=\"$hfemail\" size='30'> <input type='submit' class='tovabbgomb' name='members_search' value='$word[search]'</span>
    </td>
  </tr>\n";
if (!empty($femail)) {
  echo "
  <tr>
    <td class=bgvilagos2 valign='top'><span class='szoveg'>
    $unsubbed<br>
    $word[member_of]:
    $checkboxes
    </td>
  </tr>
  <tr>
    <td align='center' class=bgvilagos2>
		<input class='tovabbgomb' type='submit' name='saveall' value='$word[unsubscribe]'>
		<input class='tovabbgomb' type='submit' name='delete' value='$word[delete]'>
    </td>
  </tr>\n";
}
  echo "
  </form>
</table>
</td>
</tr></table>\n";

?>
