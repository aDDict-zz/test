<?



include "auth.php";
$weare=4;
include "cookie_auth.php";
include "decode.php";
include "common.php";

$Amsortm=get_cookie('Amsortm');
$Am_show_user_list=get_cookie('Am_show_user_list');
$Am_user_status=get_cookie('Am_user_status');
$Amperpage=get_cookie("Amperpage");
$Amfiltaff=get_cookie("Amfiltaff");

$sortm = (isset($_GET['sortm']) || empty($Amsortm)) ? get_http('sortm',4) : $Amsortm;
$show_user_list = (isset($_GET['show_user_list']) || empty($Am_show_user_list)) ? get_http('show_user_list',1) : $Am_show_user_list;
$user_status = (isset($_GET['user_status']) || empty($Am_user_status)) ? get_http('user_status','') : $Am_user_status;
$maxPerPage = (isset($_GET['maxPerPage']) || empty($Amperpage)) ? get_http('maxPerPage',25) : $Amperpage;
if ($show_user_list!='no' && $show_user_list!='yes')
    $show_user_list='no';

if ($user_status!='robinson' /*&& $user_status!='unval'*/ && $user_status!='all' && $user_status!='bounced')
    $user_status='normal';

$user_status="yes";

$filtaff=get_http('filtaff','');
if (!strlen($filtaff) && !empty($Amfiltaff)) 
    $filtaff=$Amfiltaff;

$query="select id from members where group_id='$group_id' and user_id='$filtaff'";
$res=mysql_query($query);
logger($query,$group_id,"","user_id=$filtaff","members");
if (!($res && mysql_num_rows($res)))
    $filtaff=0;
    
setcookie("Amfiltaff",$filtaff,time()+30*24*3600);
setcookie("Amsortm",$sortm,time()+30*24*3600);
setcookie("Amperpage",$maxPerPage,time()+30*24*3600);
setcookie("Am_show_user_list",$show_user_list,time()+30*24*3600);
setcookie("Am_user_status",$user_status,time()+30*24*3600);

$filt_demog=get_http('filt_demog','');
$Amfilt_demog=get_cookie('Amfilt_demog');
$filt_clear=get_http('filt_clear','');
if (empty($filt_demog) && !empty($Amfilt_demog) && empty($filt_clear)) 
    $filt_demog=$Amfilt_demog;

$filt_email=get_http('filt_email','');
$Amfilt_email=get_cookie('Amfilt_email');
$filt_email_clear=get_http('filt_email_clear','');
if (empty($filt_email) && !empty($Amfilt_email) && empty($filt_email_clear)) 
    $filt_email=$Amfilt_email;

$filt_ug=get_http('filt_ug','');
$Amfilt_ug=get_cookie('Amfilt_ug');
$ug_clear=get_http('ug_clear','');
if (empty($filt_ug) && !empty($Amfilt_ug) && empty($ug_clear)) 
    $filt_ug=$Amfilt_ug;

if (!empty($filt_clear)) {
    setcookie("Amfilt_demog");
    $filt_demog=0;
}
else 
    setcookie("Amfilt_demog",$filt_demog,time()+30*24*3600);

if (!empty($ug_clear)) {
    setcookie("Amfilt_ug");
    $filt_ug=0;
}
else 
    setcookie("Amfilt_ug",$filt_ug,time()+30*24*3600);

if (!empty($filt_email_clear)) {
    setcookie("Amfilt_email");
    $filt_email='';
}
else 
    setcookie("Amfilt_email",$filt_email,time()+30*24*3600);

//$active_userid=get_http('active_userid','');
$mres = mysql_query("select title,num_of_mess,unique_col from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and user_id='$active_userid' 
                     and (membership='owner' or membership='moderator' or membership='support' $admin_addq)");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);
else {
    header("Location: $_MX_var->baseUrl/index.php?no_group=1"); exit; }
$title=$rowg["title"];
$unique_col=$rowg["unique_col"];
  
include "menugen.php";
include "./lang/$language/members.lang";

if ($filtaff)
    $filtaffpart=" and users_$title.aff='$filtaff' ";
else
    $filtaffpart="";

$filt_demog_options="";
$r3=mysql_query("select id,name,cache_num,to_days(cache_date) as cddn,to_days(now()) as ndn
                 from filter where group_id='$group_id' and archived='no' order by name");
if ($r3 && mysql_num_rows($r3))
    while ($k3=mysql_fetch_array($r3)) {
        if ($k3["id"]==$filt_demog) {
            $selected="selected";
            $found_filter=1;
            $filter_cache_num=$k3["cache_num"];
            $filter_cache_diff=($k3["cddn"]+$_MX_var->filter_cache_expire)-$k3["ndn"]; 
            #so if this is>=0, cached data is not yet expired. See common.php for more.
            $filter_cache_age=$k3["ndn"]-$k3["cddn"];
        }
        else
            $selected="";
        $filt_demog_options.="<option $selected value='$k3[id]'>$k3[name]</option>";
    }
if (!$found_filter)
    $filt_demog=0;

$hfilt_email=slasher($filt_email,-1);
$sfilt_email=slasher($filt_email,1);
if (!empty($filt_email))
    $email_filt_part=" and ui_email like '%$sfilt_email%' ";
else
    $email_filt_part="";

$found_ug=0;
$ug_options="";
$r3=mysql_query("select id,name from user_group where group_id='$group_id' order by name");
if ($r3 && mysql_num_rows($r3))
    while ($k3=mysql_fetch_array($r3)) {
        if ($k3["id"]==$filt_ug) {
            $selected="selected";
            $found_ug=1;
        }
        else
            $selected="";
        $ug_options.="<option $selected value='$k3[id]'>$k3[name]</option>";
    }
if (!$found_ug)
    $filt_ug=0;

$hfilt_email=slasher($filt_email,-1);
$sfilt_email=slasher($filt_email,1);
if (!empty($filt_email))
    $email_filt_part=" and ui_email like '%$sfilt_email%' ";
else
    $email_filt_part="";

# in the redefinition of 'normal member - teljes jogu tag', normal members are those who are 
# validated, not robinson AND not bounced. The validation system has been changed in 2002-03,
# since then there will be no new users in users_* tables with flag validated='no', but it is
# needed here for backward compatibility, for older subscribes. Now not valiadated users will
# not be listed by this script, they are scattered among users_$title (old subs system),
# validation and multivalidation tables, number of not validated subs will be in stats only.
# carefully with bounced flag, because it is built into filter engine, whether to use or not. 

$status_bounced="";
if ($user_status=='all') {
    $status_str="";
    $date_type="date";
    $date_type_string=$word["subs_date"];
}
elseif ($user_status=='robinson') {
    $status_str=" and robinson='yes' and validated='yes'";
    $date_type="unsub_date";
    $date_type_string=$word["unsub_date"];
}
/*elseif ($user_status=='unval') {
    $status_str=" and validated='no'";
    $date_type="date";
    $date_type_string=$word["subs_date"];
}*/
elseif ($user_status=='bounced') {
    $status_str=" and bounced='yes' and validated='yes'";
    $date_type="date";
    $date_type_string=$word["subs_date"];
}
else {
    $status_str=" and validated='yes' and robinson='no' ";
    $date_type="validated_date";
    $date_type_string=$word["validated_date"];
    $status_bounced=" and bounced='no' ";
}

logger("",$group_id,"","$status_str","users_$title");	

$r2 = mysql_query("select count(*) from users_$title where validated='yes' and robinson='no' and bounced='no'");
if ($r2 && mysql_num_rows($r2))
    $rmem=mysql_result($r2,0,0);

$maxrecords=0;
# you won't beleive, but this expression is often true, usually they will look for number of normal members
# whose data satisfies some filter.
$distinct_qpart="distinct ";
if ($filt_demog && $user_status=="normal" && empty($filt_email) && empty($filtaffpart) && $filter_cache_diff>=0 
                && $show_user_list!="yes" && $clear_filt_cache!="yes" && !$filt_ug) {
    if ($rmem && $rmem>=$filter_cache_num) # what if filter is cached, and they have thrown away some users in the meantime?
                                           # yes, they should clear the filter cache, but who knows.
        $f_percent="(".number_format($filter_cache_num/$rmem*100,2)."%)";
    else
        $f_percent="&nbsp;";
    $stat_text="$word[total_of] $rmem $word[of_those] $filter_cache_num $f_percent $word[satisfies]<br>
                [$word[from_cache] ($filter_cache_age $word[days_old])</span> 
                <a href=members.php?group_id=$group_id&filt_demog=$filt_demog&clear_filt_cache=yes>
                $word[cache_refresh]</a><span class='szovegvastag'>]";
}
elseif (!$filt_demog) {
    if ($filt_ug)
        $joinpart=",user_group_members where users_$title.id=user_group_members.user_id 
                   and user_group_members.user_group_id='$filt_ug'";
    else
        $joinpart=" where 1";
    $query="from users_$title$joinpart $status_str $status_bounced $email_filt_part $filtaffpart";
    if (empty($filt_email) && empty($filtaffpart) && !$filt_ug) {
        $res=mysql_query("select count(*) $query");
        if ($res && mysql_num_rows($res))
            $maxrecords=mysql_result($res,0,0);
        $stat_text="$word[total_of] $maxrecords $word[m_members]";
        $not_normal_filt="";
    }
    else {
        $res=mysql_query("select count(distinct users_$title.id) $query");
        if ($res && mysql_num_rows($res))
            $maxrecords=mysql_result($res,0,0);
        if ($rmem) 
            $f_percent="(".number_format($maxrecords/$rmem*100,2)."%)";
        else
            $f_percent="&nbsp;";
        $stat_text="$word[total_of] $rmem $word[of_those] $maxrecords $f_percent $word[satisfies]";
        $not_normal_filt=" +filter";
    }
}
else {
    if ($filt_ug)
        $joinpart=",user_group_members where users_$title.id=user_group_members.user_id 
                   and user_group_members.user_group_id='$filt_ug' and";
    else
        $joinpart=" where";
    unset($filtres);
    $filter_error="filter_ok";         
    if ($pp=popen("$_MX_var->filter_engine $filt_demog","r")) {
        while ($buff=fgets($pp,25000)) {
            $filtres.=$buff;
        }
        pclose($pp);
    }
    $filtarr = explode("\n",$filtres);
    if (trim($filtarr[0]) == "filter_ok") {
        $filter_qpart=trim($filtarr[1]);
        $limitord=trim($filtarr[2]);
        $limitnum=trim($filtarr[3]);
        $syntax_error=trim($filtarr[4]);
        $syntax_error_text=trim($filtarr[5]);
    }
    else {
        $filter_error="$word[filt_engine_error]: $filtarr[0]";
    }
    if ($syntax_error==1) {
        $filter_error="$word[filt_syntax_error]: $syntax_error_text";
    }     
    # bounced='no' is built into filter engine, however, in these special cases it is not needed.
    if ($user_status=='bounced' || $user_status=='all' || $user_status=='robinson')
        $filter_qpart=str_replace(" users_$title.bounced='no' "," 1 ",$filter_qpart);
    /*$qq="select count(*) from users_$title$joinpart validated='yes' and robinson='no'
         and ($filter_qpart)";
    //echo nl2br(htmlspecialchars($qq))."--$limitord--$limitnum";
    $res=mysql_query($qq);
    if ($res && mysql_num_rows($res)) 
        $maxrecords=mysql_result($res,0,0);
    else
        $maxrecords=0;*/
    if (!empty($limitord)) {
        $distinct_qpart="";
    }
    else {
        $distinct_qpart="distinct ";
    }
    $query="from users_$title$joinpart ($filter_qpart) $status_str $email_filt_part $filtaffpart";
    //if (!empty($filt_email) || !empty($filtaffpart) || $user_status!='normal') {
        $maxrecords=0;
        $res=mysql_query("select count($distinct_qpart users_$title.id) $query");
        if ($res && mysql_num_rows($res))
            $maxrecords=mysql_result($res,0,0);
    //}
    if (!empty($limitord) && $maxrecords>$limitnum)
        $maxrecords=$limitnum;
    if (empty($filt_email) && empty($filtaffpart) && $user_status=='normal' && !$filt_ug)
        mysql_query("update filter set cache_num='$maxrecords',cache_date=now() where id='$filt_demog'");
    if ($rmem) 
        $f_percent="(".number_format($maxrecords/$rmem*100,2)."%)";
    else
        $f_percent="&nbsp;";
    $stat_text="$word[total_of] $rmem $word[of_those] $maxrecords $f_percent $word[satisfies].";
    $not_normal_filt=" +filter";
    if ($filter_error!="filter_ok")
        $stat_text.="<br>$filter_error";
}

if ($user_status=='all')
    $stat_text="$word[total_of] $maxrecords $word[user] [$word[st_all]$not_normal_filt]";
elseif ($user_status=='robinson')
    $stat_text="$word[total_of] $maxrecords $word[user] [$word[st_unsub]$not_normal_filt]";
/*elseif ($user_status=='unval')
    $stat_text="$word[total_of] $maxrecords $word[user] [$word[st_notval]$not_normal_filt]";*/
elseif ($user_status=='bounced')
    $stat_text="$word[total_of] $maxrecords $word[user] [$word[st_bounced]$not_normal_filt]";

$first=get_http('first',0);
$pagenum=get_http('pagenum',0);
$maxPerPage=intval($maxPerPage);
if($maxPerPage<1) $maxPerPage = 25;
$pagenum=intval($pagenum);
if($pagenum<1) $pagenum=1;
if($first<0) $first=0;
if(empty($first) && $pagenum) $first=($pagenum-1)*$maxPerPage;
if(!$first) $first = 0;
if($first>=$maxrecords) $first = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;
$pagenum=(int)($first / $maxPerPage) + 1;
$maxpages = ceil($maxrecords / $maxPerPage);
$LastPage = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;
$OnePageLeft = $first - $maxPerPage; if($OnePageLeft<1) $OnePageLeft = -1;
$OnePageRight = $maxPerPage + $first; if($OnePageRight>$LastPage) $OnePageRight = $LastPage;

if ($show_user_list=='yes') 
    $userlist_yn="<a href='members.php?group_id=$group_id&show_user_list=no&filt_demog=$filt_demog'>$word[hide_list]</a>";
else
    $userlist_yn="<a href='members.php?group_id=$group_id&show_user_list=yes&filt_demog=$filt_demog'>$word[show_list]</a>";

$unique_addid=0;
if (empty($unique_col)) {
    $unique_col="email";
    $unique_addid=1;
}
if ($unique_col=="email")
    $unique_title=$word["t_email"];
else
    $unique_title=$unique_col;
if ($unique_addid)
    $unique_title.=" (id)";

if (!$sortm)
    $sortm=1;
if (!empty($limitord))
    $order="order by $limitord";
else {    
    switch ($sortm) {
        case 1: $order = "order by ui_$unique_col asc"; if ($unique_addid) $order.=",id asc"; break;
        case 2: $order = "order by ui_$unique_col desc"; if ($unique_addid) $order.=",id desc"; break;
        case 3: $order = "order by $date_type asc"; break;
        case 4: $order = "order by $date_type desc"; break;
    }
}

if ($first+$maxPerPage>$maxrecords)
    $limitend=$maxrecords-$first;
else
    $limitend=$maxPerPage;

$query = "select $distinct_qpart ui_$unique_col,users_$title.id,$date_type,last_clicked,last_sent,mess_total 
          $query $order limit $first,$limitend";

printhead();

//echo nl2br(htmlspecialchars($query));

if ($show_user_list=='yes' && ($rmem || $maxrecords)) {
    $rst=mysql_query($query);
    $index = $first;
    if ($rst && mysql_num_rows($rst)) {
        printnavigation();
        echo "<form action='membersu.php' method='post' name='myinputs'>
              <input type='hidden' name='group_id' value='$group_id'>
              <input type='hidden' name='filt_demog' value='$filt_demog'>
              <input type='hidden' name='pagenum' value='$pagenum'><tr>
              <td class='bgkiemelt2' align=left width=7%><span class=szovegvastag>&nbsp;</span></td>
              <td class='bgkiemelt2' align=left width=24%><span class=szovegvastag>$unique_title</span></td>
              <td class='bgkiemelt2' align=left width=17%><span class=szovegvastag>$date_type_string</span></td>
              <td class='bgkiemelt2' align=left width=17%><span class=szovegvastag>$word[t_lastclick]</span></td>
              <td class='bgkiemelt2' align=left width=17%><span class=szovegvastag>$word[t_lastmess]</span></td>
              <td class='bgkiemelt2' align=left width=9%><span class=szovegvastag>&nbsp;</span></td>
              <td class='bgkiemelt2 tac' width=9%><span class=szovegvastag>
              <input type='submit' name='deluser' onClick=\"return(confirm('$word[sure_delete]'))\" value='$word[delete]'>
              <input type='submit' name='unsubuser' onClick=\"return(confirm('$word[sure_unsubscribe]'))\" value='$word[unsubscribe]'>
              <input type='submit' name='activateuser' onClick=\"return(confirm('$word[sure_activate]'))\" value='$word[activate]'><br>
              <a href=\"javascript:select_all()\"><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/selectall.gif' border='0' alt='$word[select_all]'></a>
              <a href=\"javascript:deselect_all()\"><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/selectnone.gif' border='0' alt='$word[select_none]'></a>
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
            $user_date=$row["$date_type"];
            $unique_data=$row["ui_$unique_col"];
            if ($unique_addid)
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

    global $_MX_var,$stat_text,$rmem,$userlist_yn,$filt_demog_options,$group_id,$pagenum,$filtaff,$ug_options;
    global $_MX_var,$filt_demog,$hfilt_email,$user_status,$maxrecords,$date_type_string,$word,$unique_col,$ug_options;

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
		<td class=bgkiemelt2 align=left colspan=7><span class=szovegvastag>$stat_text</span></td>
		</tr>\n";
    if ($rmem || $maxrecords)
        echo "<tr>
		    <td class=bgkiemelt2 align=left colspan=7>
            $userlist_yn
            </td>
		    </tr>\n";
    if (!empty($filt_demog_options))
        echo "<form>
            <input type='hidden' name='group_id' value='$group_id'>
            <input type='hidden' name='pagenum' value='$pagenum'>
            <tr>
		    <td class=bgkiemelt2 align=left colspan=7><span class=szovegvastag>
            $word[filter]</span>&nbsp;<select onChange='JavaScript: this.form.submit();' name='filt_demog'>
	        <option value='0'>$word[select]</option>
            $filt_demog_options
            </select>&nbsp;
            <input type='submit' name='filt_clear' value='$word[clear_filter]'>
            </td>
		    </tr></form>\n";
    if (!empty($ug_options))
        echo "<form>
            <input type='hidden' name='group_id' value='$group_id'>
            <input type='hidden' name='pagenum' value='$pagenum'>
            <tr>
		    <td class=bgkiemelt2 align=left colspan=7><span class=szovegvastag>
            $word[filter_ug]</span>&nbsp;<select onChange='JavaScript: this.form.submit();' name='filt_ug'>
	        <option value='0'>$word[select]</option>
            $ug_options
            </select>&nbsp;
            <input type='submit' name='ug_clear' value='$word[clear_filter]'>
            </td>
		    </tr></form>\n";
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
        <input type='hidden' name='pagenum' value='$pagenum'>
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
        <input type='hidden' name='pagenum' value='$pagenum'>
        <input type='hidden' name='filt_demog' value='$filt_demog'>
        <tr>
        <td class=bgkiemelt2 align=left colspan=7>
        <select name='user_status' onchange=this.form.submit();>
        <option $normal_sel value='normal'>$word[st_normal]</option>
        <option $robinson_sel value='robinson'>$word[st_unsub]</option>
        <!--<option $unval_sel value='unval'>$word[st_notval]</option>-->
        <option $all_sel value='all'>$word[st_all]</option>
        <option $bounced_sel value='bounced'>$word[st_bounced]</option>
        </select>
        </td>
        </tr></form>\n";
    if (!empty($affdd)) 
        echo "<form>
            <input type='hidden' name='group_id' value='$group_id'>
            <input type='hidden' name='pagenum' value='$pagenum'>
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
  global $_MX_var,$group_id,$lang,$pagenum,$LastPage,$OnePageLeft,$OnePageRight,$sortm,$filt_demog;
  global $_MX_var,$pagenum,$maxpages,$first,$word,$maxPerPage,$limitord,$date_type_string,$unique_col;

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
 if ($first>0)
 echo " 
            <td nowrap align='right'>              <a href='$_MX_var->baseUrl/members.php?$params&first=0'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down2.gif' width='20' height='14' border='0'></a>
              <a href='$_MX_var->baseUrl/members.php?$params&first=$OnePageLeft'><img
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
              <a href='$_MX_var->baseUrl/members.php?$params&first=$OnePageRight'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up1.gif' width='20' height='14' border='0'></a>
              <a href='$_MX_var->baseUrl/members.php?$params&first=$LastPage'><img
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
                <option value=3 $sel_sort[3]>$date_type_string $word[by_asc]</option>
                <option value=4 $sel_sort[4]>$date_type_string $word[by_desc]</option>
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
