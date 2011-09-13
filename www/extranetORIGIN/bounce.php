<?
include "auth.php";
include "decode.php";
$weare=51;
include "cookie_auth.php";
include "common.php";

$pagenum = get_http('pagenum','');
$b_signed = get_http('b_signed','');
$group_id = get_http('group_id','');
$filt_error = get_http('filt_error','');
$filt_email = get_http('filt_email','');
$filt_go = get_http('filt_go','');

if (empty($sortm) && isset($Bosortm)) 
    $sortm=$Bosortm;
    
if (empty($maxPerPage) && isset($Boperpage)) 
    $maxPerPage=$Boperpage;
    
if (empty($b_signed) && isset($Bosigned)) 
    $b_signed=$Bosigned;

if ($b_signed!="yes" && $b_signed!="no")
    $b_signed="no";

setcookie("Bosortm",$sortm,time()+30*24*3600);
setcookie("Bosigned",$b_signed,time()+30*24*3600);
setcookie("Boperpage",$maxPerPage,time()+30*24*3600);

if (empty($filt_email) && isset($Bofilt_email) && !isset($filt_email_clear)) 
    $filt_email=$Bofilt_email;

if (isset($filt_email_clear)) {
    setcookie("Bofilt_email");
    $filt_email='';
}
else 
    setcookie("Bofilt_email",$filt_email,time()+30*24*3600);

if (!strlen($filt_error) && isset($Bofilt_error) && !isset($filt_error_clear)) 
    $filt_error=$Bofilt_error;

if (isset($filt_error_clear)) {
    setcookie("Bofilt_error");
    $filt_error='';
}
else 
    setcookie("Bofilt_error",$filt_error,time()+30*24*3600);

# create list of group_ids user has access to.
# if there is none, user does not have access to this script.
# here is a little problem with authentication, because on the site auth is based on the 
# single group_id, user_id and the kind of membership in that group, if all is ok, he can
# operate on that group. In this script, however, user operates on all his groups 
# (depending, again, on kind of the membership!). To stick to some conventions, access  
# will not be granted if user is not member of the given group_id.

$access=0;
$group_id_list="";
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
    }
}
if (!$access) {
    header("Location: $_MX_var->baseUrl/index.php?no_group=1"); exit; }
  
include "menugen.php";
include "./lang/$language/bounce.lang";

$hfilt_email=slasher($filt_email,-1);
$filt_email=slasher($filt_email);
if (!empty($filt_email))
    $email_filt_part=" and users_$title.ui_email like '%$filt_email%' ";
else
    $email_filt_part="";

if ($b_signed=="yes")
    $signedpart="";
else
    $signedpart="and users_$title.bounced='no'";

$error_codes=array();
$error_dd="";
$found_errtype=0;
$res=mysql_query("select * from error_code order by description");
if ($res && mysql_num_rows($res)) {
    while ($k=mysql_fetch_array($res)) {
        $error_codes["$k[code]"]=$k["description"];
        if ($k["code"]==$filt_error) {
            $found_errtype=1;
            $sel="selected";
        }
        else
            $sel="";
        $error_dd.="<option $sel value='$k[code]'>$k[code] $k[description]</option>";
    }
}

if (strlen($filt_error) && $found_errtype=1)
    $error_filt_part=" and bounced_back.error_code='$filt_error' ";
else
    $error_filt_part="";

if (!$sortm)
    $sortm=1;
if (!empty($limitord))
    $order="order by $limitord";
else {    
    switch ($sortm) {
        case 1: $order = "order by email asc"; break;
        case 2: $order = "order by email desc"; break;
        case 3: $order = "order by last_date asc"; break;
        case 4: $order = "order by last_date desc"; break;
        case 5: $order = "order by first_date asc"; break;
        case 6: $order = "order by first_date desc"; break;
    }
}

$maxrecords=0;
$cqr="select count(distinct email) from bounced_back,users_$title 
      where bounced_back.email=users_$title.ui_email and bounced_back.project='maxima'
      $signedpart
      and bounced_back.group_id='$group_id' $error_filt_part $email_filt_part $flagged_part";
//print $cqr;
$res=mysql_query($cqr);
if ($res && mysql_num_rows($res)) 
    $maxrecords=mysql_result($res,0,0);
$stat_text="$word[total_of] $maxrecords $word[b_emails]";

$maxPerPage=intval($maxPerPage);
if($maxPerPage<1) $maxPerPage = 50;
$pagenum=intval($pagenum);
if($pagenum<1) $pagenum=1;
if($first<0) $first=0;
if(!strlen($first) && $pagenum) $first=($pagenum-1)*$maxPerPage;
if(!$first) $first = 0;
if($first>=$maxrecords) $first = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;
$pagenum=(int)($first / $maxPerPage) + 1;
$maxpages = ceil($maxrecords / $maxPerPage);
$LastPage = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;
$OnePageLeft = $first - $maxPerPage; if($OnePageLeft<1) $OnePageLeft = -1;
$OnePageRight = $maxPerPage + $first; if($OnePageRight>$LastPage) $OnePageRight = $LastPage;

$query = "select email,count(*) as cnt,max(bounced_back.date) as last_date,min(bounced_back.date) as first_date
          from bounced_back,users_$title where 
          bounced_back.email=users_$title.ui_email and bounced_back.project='maxima' 
          and bounced_back.group_id='$group_id' $error_filt_part $email_filt_part $flagged_part $signedpart
          group by email $order limit $first,$maxPerPage";

printhead();

//echo nl2br(htmlspecialchars($query));
$rst=mysql_query($query);
$index = $first;
if ($rst && mysql_num_rows($rst)) {
    printnavigation();
    echo "<form action='bounceu.php' method='post' name='myinputs'>
          <input type='hidden' name='group_id' value='$group_id'>
          <input type='hidden' name='pagenum' value='$pagenum'><tr>
          <td class='bgkiemelt2' align=left width=6%><span class=szovegvastag>&nbsp;</span></td>
          <td class='bgkiemelt2' align=left width=31%><span class=szovegvastag>$word[t_email]</span></td>
          <td class='bgkiemelt2' align=left width=17%><span class=szovegvastag>$word[t_bounced]</span></td>
          <td class='bgkiemelt2' align=left width=17%><span class=szovegvastag>$word[t_first]</span></td>
          <td class='bgkiemelt2' align=left width=17%><span class=szovegvastag>$word[t_last]</span></td>
          <td class='bgkiemelt2' align=center width=12%><span class=szovegvastag>
          <a href=\"javascript:select_all()\"><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/selectall.gif' border='0' alt='$word[select_all]'></a>
          <a href=\"javascript:deselect_all()\"><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/selectnone.gif' border='0' alt='$word[select_none]'></a>&nbsp;
          <input type='submit' name='deluser' value='$word[delete]' onClick=\"return(confirm('$word[delete_sure]'))\"><br>
          <input type='submit' name='signuser' value='$word[sign]' onClick=\"return(confirm('$word[sign_sure]'))\">
          </span></td>
          </tr>\n";
    while($row=mysql_fetch_array($rst)) {
        $index++;
        if ($index%2)
           $bgrnd="class=bgvilagos2";
        else
           $bgrnd="bgcolor=white";
        $hemail=htmlspecialchars($row["email"]);
        $row["email"]=addslashes($row["email"]);
        echo "<tr>
              <td $bgrnd align=left width=6%><span class=szoveg>$index.</span></td>
              <td $bgrnd align=left width=31%><span class=szoveg>$hemail</span></td>
              <td $bgrnd align=center width=17%>
              <a href='#' onClick='window.open(\"$_MX_var->baseUrl/bounce_detail.php?group_id=$group_id&email=$row[email]\", \"bbm$index\", \"width=500,height=500,scrollbars=yes,resizable=yes\"); return false;'>$row[cnt]</a>
              </td>
              <td $bgrnd align=left width=17%><span class=szoveg>$row[first_date]</span></td>
              <td $bgrnd align=left width=17%><span class=szoveg>$row[last_date]</span></td>
              <td $bgrnd align=center width=12%><span class=szoveg>
              <input type='checkbox' name='emaillist[$row[email]]'>
              </span></td>
              </tr>\n";
    }
    echo "</form>";
    printnavigation();
}

printfoot();

include "footer.php";

######################### functions ####################################

function printhead() {

    global $_MX_var,$stat_text,$error_dd,$group_id,$pagenum,$b_signed;
    global $_MX_var,$hfilt_email,$user_status,$maxrecords,$date_type_string,$word;

    echo "<script language=\"JavaScript\">
        function select_all()
        {
          len = document.myinputs.elements.length;
          var i=0;
          for(i=0; i < len; i++) {
            if (document.myinputs.elements[i].type == 'checkbox')
            { document.myinputs.elements[i].checked = true }
          }

        }
        function deselect_all()
        {
          len = document.myinputs.elements.length;
          var i=0;
          for(i=0; i < len; i++) {
            if (document.myinputs.elements[i].type == 'checkbox')
            { document.myinputs.elements[i].checked = false }
          }

        }
        </script>
        <TABLE cellSpacing=0 cellPadding=1 width=\"100%\" bgColor=$_MX_var->main_table_border_color border=0>
        <TR>
        <TD>
        <TABLE cellSpacing=1 cellPadding=0 width=\"100%\" bgColor=$_MX_var->main_table_border_color border=0>
        <tr>
		<td class=bgkiemelt2 align=left colspan=6><span class=szovegvastag>$stat_text ($word[in_this])</span></td>
		</tr>\n";
    if (!empty($error_dd))
        echo "<form>
            <input type='hidden' name='group_id' value='$group_id'>
            <input type='hidden' name='pagenum' value='$pagenum'>
            <tr>
		    <td class=bgkiemelt2 align=left colspan=6><span class=szovegvastag>
            $word[filterr]:</span>&nbsp;<select onChange='JavaScript: this.form.submit();' name='filt_error'>
	        <option value='0'>$word[select]</option>
            $error_dd
            </select>&nbsp;
            <input type='submit' name='filt_error_clear' value='$word[del_filterr]'>
            </td>
		    </tr></form>\n";
    echo "<form>
        <input type='hidden' name='group_id' value='$group_id'>
        <input type='hidden' name='pagenum' value='$pagenum'>
        <tr>
        <td class=bgkiemelt2 align=left colspan=6><span class=szovegvastag>
        &nbsp;$word[filt]:</span>&nbsp;<input type='text' name='filt_email' value=\"$hfilt_email\">
        <input type='submit' name='filt_go' value='$word[go]'>
        <input type='submit' name='filt_email_clear' value='$word[del_filt]'>
        </td>
        </tr></form>\n";
    $b_signed=="yes"?$yessel="selected":$yessel="";    
    echo "<form>
        <input type='hidden' name='group_id' value='$group_id'>
        <input type='hidden' name='pagenum' value='$pagenum'>
        <tr>
        <td class=bgkiemelt2 align=left colspan=6>
        <select onChange='JavaScript: this.form.submit();' name='b_signed'>
        <option value='no'>$word[sel_notsigned]</option>
        <option $yessel value='yes'>$word[sel_all]</option>
        </select>&nbsp;
        </td>
        </tr></form>\n";
}

function printfoot() {

    echo "</table>
        </td>
        </tr>
        </table>";
}

//****[ PrintNavigation ]*****************************************************
function PrintNavigation() {
  global $_MX_var,$group_id,$pagenum,$LastPage,$OnePageLeft,$OnePageRight,$sortm,$filt_demog;
  global $_MX_var,$pagenum,$maxpages,$first,$word,$maxPerPage,$limitord,$date_type_string;

  $sel_sort[$sortm] = "selected";
  $params="group_id=$group_id";
  
  if (!empty($limitord))
    $sort_order="&nbsp;";
  else
    $sort_order="$word[sort_order]:";
  
  echo "
<tr><td colspan=6><table border=0 cellspacing=0 cellpadding=0 width='100%'>  
<tr>
    <td>
    <table border=0 cellspacing=0 cellpadding=0 width='100%'>
	<tr>
    	<td class='formmezo' align='left' width='33%'>
	<table border='0' cellspacing='0' cellpadding='0'>
          <form name=inputs>
          <input type='hidden' name='group_id' value='$group_id'>
	  <tr>";
 if ($first>0)
 echo " 
            <td nowrap align='right'><a href='$_MX_var->baseUrl/bounce.php?$params&first=0'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down2.gif' width='20' height='14' border='0'></a>
              <a href='$_MX_var->baseUrl/bounce.php?$params&first=$OnePageLeft'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down1.gif' width='20' height='14' border='0'></a>
            </td>";
 else
 echo " 
            <td nowrap align='right'>&nbsp;&nbsp;
              <img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down2_dead.gif' width='20' height='14' border='0'>
              <img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down1_dead.gif' width='20' height='14' border='0'>
            </td>";
 echo "
            <td nowrap align='right'> 
              <input type='text' name='pagenum' size='3' maxlength='3' value='$pagenum'>
            </td>
            <td nowrap class='formmezo'>&nbsp;/ $maxpages</td>";
if ($first<$LastPage)
echo "
            <td nowrap align='right'> &nbsp;
              <a href='$_MX_var->baseUrl/bounce.php?$params&first=$OnePageRight'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up1.gif' width='20' height='14' border='0'></a>
              <a href='$_MX_var->baseUrl/bounce.php?$params&first=$LastPage'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up2.gif' width='20' height='14' border='0'></a>&nbsp; </td>";
 else
 echo " 
            <td nowrap align='right'>&nbsp;&nbsp;
              <img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up1_dead.gif' width='20' height='14' border='0'>
              <img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up2_dead.gif' width='20' height='14' border='0'>
            </td>";
echo "
           </form>
          </tr>
        </table>
	      </td>
    </tr>
  </table>
      </td>
    	<td class='formmezo' align='center' width='33%'>
        <table border='0' cellspacing='0' cellpadding='0'>
          <tr> 
  <form>
  <input type='hidden' name='group_id' value='$group_id'>
            <td nowrap class='formmezo' align='center'>$word[view]:</td>
            <td nowrap align='center'> 
              <input type='text' name='maxPerPage' value='$maxPerPage' size='3' maxlength='3'>
            </td>
            <td nowrap class='formmezo' align='center'> $word[email_page]</td>
</form>
          </tr>
        </table>
      </td>
    	<td class='formmezo' align='right' width='33%'>
        <table border='0' cellspacing='0' cellpadding='0'>
          <tr> 
  <form>
  <input type='hidden' name='group_id' value='$group_id'>
            <td nowrap class='formmezo'>$sort_order </td>
            <td nowrap>\n";
            if (!empty($limitord))
              echo "&nbsp;";
            else
              echo "
              <select onChange='JavaScript: this.form.submit();' name=sortm>
                <option value=1 $sel_sort[1]>$word[email_asc]</option>
                <option value=2 $sel_sort[2]>$word[email_desc]</option>
                <option value=3 $sel_sort[3]>$word[last_asc]</option>
                <option value=4 $sel_sort[4]>$word[last_desc]</option>
                <option value=5 $sel_sort[5]>$word[first_asc]</option>
                <option value=6 $sel_sort[6]>$word[first_desc]</option>
              </select>\n";
            echo "
            </td>
</form>
          </tr>
        </table>

      </td>
    </tr>
    </table></td></tr>\n";
}



  
?>
