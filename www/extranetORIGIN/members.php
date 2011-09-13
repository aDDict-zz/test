<?
include "auth.php";
$weare=4;
include "cookie_auth.php";
include "decode.php";
include "common.php";
include "_filter.php";

$mres = mysql_query("select groups.id,title,num_of_mess,unique_col from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and user_id='$active_userid' 
                     and (membership='owner' or membership='moderator' or membership='support' $admin_addq)");
if ($mres && mysql_num_rows($mres)) {
    $rowg=mysql_fetch_array($mres);
}
else {
    header("Location: $_MX_var->baseUrl/index.php?no_group=1"); 
    exit; 
}
$title=$rowg["title"];
$unique_col=$rowg["unique_col"];

$_MX_filter = new MxFilter($rowg);

$_MX_filter->GetParams();

include "menugen.php";
include "./lang/$language/members.lang";

$_MX_filter->GetSql();

foreach ($_MX_filter->params as $parm=>$val) {
    $$parm = $val;
}
$hfilt_email = htmlspecialchars($filt_email);

if ($show_user_list=='yes') {
    $userlist_yn="<a href='members.php?group_id=$group_id&show_user_list=no&filt_demog=$filt_demog'>$word[hide_list]</a>";
}
else {
    $userlist_yn="<a href='members.php?group_id=$group_id&show_user_list=yes&filt_demog=$filt_demog'>$word[show_list]</a>";
}

printhead();

// echo nl2br(htmlspecialchars($_MX_filter->query));

if ($show_user_list=='yes' && ($_MX_filter->total_users || $_MX_filter->maxrecords)) {
    $rst=mysql_query($_MX_filter->query);
    $index = $_MX_filter->first;
    if ($rst && mysql_num_rows($rst)) {
        printnavigation();
        echo "<form action='membersu.php' method='post' name='myinputs'>
              <input type='hidden' name='group_id' value='$group_id'>
              <input type='hidden' name='filt_demog' value='$filt_demog'>
              <input type='hidden' name='pagenum' value='$_MX_filter->pagenum'><tr>
              <td class='bgkiemelt2' align=left width=7%><span class=szovegvastag>&nbsp;</span></td>
              <td class='bgkiemelt2' align=left width=24%><span class=szovegvastag>$_MX_filter->unique_title</span></td>
              <td class='bgkiemelt2' align=left width=17%><span class=szovegvastag>$_MX_filter->date_type_string</span></td>
              <td class='bgkiemelt2' align=left width=17%><span class=szovegvastag>$word[t_lastclick]</span></td>
              <td class='bgkiemelt2' align=left width=17%><span class=szovegvastag>$word[t_lastmess]</span></td>
              <td class='bgkiemelt2' align=left width=9%><span class=szovegvastag>&nbsp;</span></td>
              <td class='bgkiemelt2 tac' width=9%><span class=szovegvastag>
              <input type='submit' name='deluser' onClick=\"return(confirm('$word[sure_delete]'))\" value='$word[delete]'>
              <input type='submit' name='unsubuser' onClick=\"return(confirm('$word[sure_unsubscribe]'))\" value='$word[unsubscribe]'>
              <input type='submit' name='activateuser' onClick=\"return(confirm('$word[sure_activate]'))\" value='$word[activate]'><br>
              <a href=\"javascript:select_all()\"><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/selectall.gif' border='0'></a>
              <a href=\"javascript:deselect_all()\"><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/selectnone.gif' border='0'></a>
              </span></td>
              </tr>\n";
        while($row=mysql_fetch_array($rst)) {
            $index++;
            if ($index%2)
               $bgrnd="class=bgvilagos2";
            else
               $bgrnd="bgcolor=white";
            if (empty($row["last_clicked"]) || $row["last_clicked"]=="0000-00-00 00:00:00" || $row["last_clicked"]=="1970-01-01 00:00:01")
                $last_clicked="---";
            else
                $last_clicked=$row["last_clicked"];
            if (empty($row["last_sent"]) || $row["last_sent"]=="0000-00-00 00:00:00"  || $row["last_sent"]=="1970-01-01 00:00:01")
                $last_sent="---";
            else
                $last_sent=$row["last_sent"];
            $mess_total=intval($row["mess_total"]);
            $user_date=$row["$_MX_filter->date_type"];
            $unique_data=$row["ui_$unique_col"];
            if ($_MX_filter->unique_addid)
                $unique_data.=" ($row[id])";
            echo "<tr>
		          <td $bgrnd align=left width=7%><span class=szoveg>$index.</span></td>
		          <td $bgrnd align=left width=24%><span class=szoveg>$unique_data</span></td>
		          <td $bgrnd align=left width=17%><span class=szoveg>$user_date</span></td>
		          <td $bgrnd align=left width=17%><span class=szoveg>$last_clicked</span></td>
		          <td $bgrnd align=left width=17%><span class=szoveg>
                  <a href='#' onClick='window.open(\"$_MX_var->baseUrl/message_received.php?group_id=$group_id&rec_userid=$row[id]\", \"m_d_i\", \"width=710,height=500,scrollbars=yes,resizable=yes\"); return false;'>$last_sent($mess_total)</a>
                  </span></td>
		          <td $bgrnd align=left width=9%><span class=szoveg>
                  <a href='#' onClick='window.open(\"$_MX_var->baseUrl/members_demog_info.php?group_id=$group_id&user_id=$row[id]\", \"m_d_i\", \"width=710,height=500,scrollbars=yes,resizable=yes\"); return false;'>$word[demog_info]</a>
                  </span></td>
		          <td $bgrnd align=center width=9%><span class=szoveg>
                  <input type='checkbox' name='deluser_id[$row[id]]'>
                  </span></td>
		          </tr>\n";
        }
        echo "</form>";
        printnavigation();
    }
}

printfoot();

include "footer.php";

######################### functions ####################################

function printhead() {

    global $_MX_filter,$userlist_yn,$group_id,$filtaff,$_MX_var,$filt_demog,$hfilt_email,$user_status,$word,$unique_col;

    echo "<script language=\"JavaScript\">
            function select_all() {
              len = document.myinputs.elements.length;
              var i=0;
              for(i=0; i < len; i++) {
                if (document.myinputs.elements[i].type == 'checkbox')
                { document.myinputs.elements[i].checked = true }
              }
            }
            function deselect_all() {
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
		<td class=bgkiemelt2 align=left colspan=7><span class=szovegvastag>$_MX_filter->stat_text</span></td>
		</tr>\n";
    if ($_MX_filter->total_users || $_MX_filter->maxrecords)
        echo "<tr>
		    <td class=bgkiemelt2 align=left colspan=7>
            $userlist_yn
            </td>
		    </tr>\n";
    if (!empty($_MX_filter->filt_demog_options)) {
        echo "<form>
            <input type='hidden' name='group_id' value='$group_id'>
            <input type='hidden' name='pagenum' value='$_MX_filter->pagenum'>
            <tr>
		    <td class=bgkiemelt2 align=left colspan=5><span class=szovegvastag>
            $word[filter]</span>&nbsp;<select onChange='JavaScript: this.form.submit();' name='filt_demog'>
	        <option value='0'>$word[select]</option>
            $_MX_filter->filt_demog_options
            </select>&nbsp;
            <input type='submit' name='filt_clear' value='$word[clear_filter]'>
            </td>
            <td class=bgkiemelt2 align=left width=9%><span class=szoveg>";
        if ($_MX_filter->mass_demog_change) {
            echo "<a href='#' onClick='window.open(\"$_MX_var->baseUrl/members_demog_info.php?group_id=$group_id&filt_demog=$filt_demog\", \"m_d_i\", \"width=710,height=500,scrollbars=yes,resizable=yes\"); return false;'>$word[demog_info]</a>";
        }
        echo "</span></td>
            <td class=bgkiemelt2 align=center width=9%><span class=szoveg>&nbsp;
            </span></td>
		    </tr></form>\n";
    }
    if (!empty($_MX_filter->ug_options))
        echo "<form>
            <input type='hidden' name='group_id' value='$group_id'>
            <input type='hidden' name='pagenum' value='$_MX_filter->pagenum'>
            <tr>
		    <td class=bgkiemelt2 align=left colspan=7><span class=szovegvastag>
            $word[filter_ug]</span>&nbsp;<select onChange='JavaScript: this.form.submit();' name='filt_ug'>
	        <option value='0'>$word[select]</option>
            $_MX_filter->ug_options
            </select>&nbsp;
            <input type='submit' name='ug_clear' value='$word[clear_filter]'>
            </td>
		    </tr></form>\n";
    $normal_sel=$robinson_sel=$all_sel=$bounced_sel="";
    ${$user_status."_sel"}="selected";
    $r2=mysql_query("select user.email,user.id from user,members
                     where user.id=members.user_id and members.group_id='$group_id'");
    $affdd="";
    if ($r2 &&mysql_num_rows($r2)) {
        while ($k=mysql_fetch_array($r2)) {
            if ($k["id"]==$filtaff)
                $sel="selected";
            else
                $sel="";
            $affdd.="<option value='$k[id]' $sel>$k[email]</option>";
        }
    }
    echo "<form>
        <input type='hidden' name='group_id' value='$group_id'>
        <input type='hidden' name='pagenum' value='$_MX_filter->pagenum'>
        <input type='hidden' name='filt_demog' value='$filt_demog'>
        <tr>
        <td class=bgkiemelt2 align=left colspan=7><span class=szovegvastag>
        &nbsp;$word[email_filter]:</span>&nbsp;<input type='text' name='filt_email' value=\"$hfilt_email\">
        <input type='submit' name='filt_go' value='$word[filter_go]'>
        <input type='submit' name='filt_email_clear' value='$word[clear_filter]'>
        </td>
        </tr></form>
        <form>
        <input type='hidden' name='group_id' value='$group_id'>
        <input type='hidden' name='pagenum' value='$_MX_filter->pagenum'>
        <input type='hidden' name='filt_demog' value='$filt_demog'>
        <tr>
        <td class=bgkiemelt2 align=left colspan=7>
        <select name='user_status' onchange=this.form.submit();>
        <option $normal_sel value='normal'>$word[st_normal]</option>
        <option $robinson_sel value='robinson'>$word[st_unsub]</option>
        <option $all_sel value='all'>$word[st_all]</option>
        <option $bounced_sel value='bounced'>$word[st_bounced]</option>
        </select>
        </td>
        </tr></form>\n";
    if (!empty($affdd)) 
        echo "<form>
            <input type='hidden' name='group_id' value='$group_id'>
            <input type='hidden' name='pagenum' value='$_MX_filter->pagenum'>
            <input type='hidden' name='filt_demog' value='$filt_demog'>
            <tr>
            <td class=bgkiemelt2 align=left colspan=7>
            <select name='filtaff' onchange=this.form.submit();>
            <option value='0'>$word[filtaff]</option>
            $affdd
            </select>
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
  global $_MX_var,$group_id,$lang,$sortm,$filt_demog,$_MX_filter,$word,$maxPerPage,$limitord,$unique_col;

    for ($i=0;$i<10;$i++) {
        $sel_sort[$i] = "";
    }
  $sel_sort[$sortm] = "selected";
  $params="group_id=$group_id&filt_demog=$filt_demog";
  
  if (!empty($limitord))
    $sort_order="&nbsp;";
  else
    $sort_order="$word[sort_order]:";
  
  echo "
<tr><td colspan=7><table border=0 cellspacing=0 cellpadding=0 width='100%'>  
<tr>
    <td>
    <table border=0 cellspacing=0 cellpadding=0 width='100%'>
	<tr>
    	<td class='formmezo' align='left' width='33%'>
	<table border='0' cellspacing='0' cellpadding='0'>
          <form name=inputs>
          <input type='hidden' name='group_id' value='$group_id'>
          <input type='hidden' name='filt_demog' value='$filt_demog'>
	  <tr>";
 if ($_MX_filter->first>0)
 echo " 
            <td nowrap align='right'>              <a href='$_MX_var->baseUrl/members.php?$params&first=0'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down2.gif' width='20' height='14' border='0'></a>
              <a href='$_MX_var->baseUrl/members.php?$params&first=$_MX_filter->OnePageLeft'><img
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
              <input type='text' name='pagenum' size='3' maxlength='3' value='$_MX_filter->pagenum'>
            </td>
            <td nowrap class='formmezo'>&nbsp;/ $_MX_filter->maxpages</td>";
if ($_MX_filter->first<$_MX_filter->LastPage)
echo "
            <td nowrap align='right'> &nbsp;
              <a href='$_MX_var->baseUrl/members.php?$params&first=$_MX_filter->OnePageRight'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up1.gif' width='20' height='14' border='0'></a>
              <a href='$_MX_var->baseUrl/members.php?$params&first=$_MX_filter->LastPage'><img
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
            <td nowrap class='formmezo' align='center'> $word[members_page]</td>
</form>
          </tr>
        </table>
      </td>
    	<td class='formmezo' align='right' width='33%'>
        <table border='0' cellspacing='0' cellpadding='0'>
          <tr> 
  <form>
  <input type='hidden' name='group_id' value='$group_id'>
  <input type='hidden' name='filt_demog' value='$filt_demog'>
            <td nowrap class='formmezo'>$sort_order </td>
            <td nowrap>\n";
            if (!empty($limitord))
              echo "&nbsp;";
            else
              echo "
              <select onChange='JavaScript: this.form.submit();' name=sortm>
                <option value=1 $sel_sort[1]>$unique_col $word[by_asc]</option>
                <option value=2 $sel_sort[2]>$unique_col $word[by_desc]</option>
                <option value=3 $sel_sort[3]>$_MX_filter->date_type_string $word[by_asc]</option>
                <option value=4 $sel_sort[4]>$_MX_filter->date_type_string $word[by_desc]</option>
              </select>\n";
            echo "
            </td>
</form>
          </tr>
        </table>

      </td>
    </tr>
    </table></td></tr>
  ";
}
  
?>
