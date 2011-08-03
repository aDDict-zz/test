<?
include "auth.php";
$weare=21;
include "cookie_auth.php";  
include "common.php";
$language=select_lang();
include "./lang/$language/statistics.lang";
include "decode.php";

$mres = mysql_query("select title,membership from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and user_id='$active_userid'
                     and (membership='owner' or membership='moderator' or membership='client' $admin_addq)");
if ($mres && mysql_num_rows($mres))
    $row=mysql_fetch_array($mres);  
else {
    header("Location: $_MX_var->baseUrl/index.php"); exit; 
}
$message_id=get_http("message_id",0);
$tres2 = mysql_query("select subject,create_date,to_days(create_date),to_days(now()) from messages where id='$message_id'");
if ($tres2 && mysql_num_rows($tres2)) {
    $k2=mysql_fetch_row($tres2);
    $subject=nl2br(htmlspecialchars(quoted_printable_decode(decode_mime_string($k2[0]))));
    $create_date=$k2[1];
    $abs_start_daynum=$k2[2];
    $abs_end_daynum=$k2[3];
    if ($abs_end_daynum-$abs_start_daynum>30)
        $abs_end_daynum=$abs_start_daynum+30;
}
else {
    // the message does not exist.
    header("location: threadlist.php?group_id=$group_id");
    exit;
}

logger("",$group_id,"","","feedback");	
  
$group_title=$row["title"];
!empty($admin_addq)?$act_memb="moderator":$act_memb=$row["membership"];

$stattypes=array();
if ($act_memb!="owner" && $act_memb!="moderator") {
    $rcl=mysql_query("select * from message_client where message_id='$message_id' and user_id='$active_userid'");
    if (!($rcl && mysql_num_rows($rcl))) {
        // this message is not assigned to this user.
        header("location: threadlist.php?group_id=$group_id");
        exit;
    }
    $r2=mysql_query("select st.id,st.parent_id from stattype st,stattype_user su where 
                     su.user_id='$active_userid'
                     and su.group_id='$group_id' and st.id=su.stattype_id");
    if ($r2 && mysql_num_rows($r2)) {
        while ($z=mysql_fetch_array($r2)) {
            $stattypes[]=$z["id"];
        }
    }
    if ( (!in_array(2,$stattypes) || !in_array(1,$stattypes)) && (!in_array(5,$stattypes) || !in_array(4,$stattypes)) ) {
        // user has no right to see detailed stats for CT [2] or 1x1 open [5].
        header("location: threadlist.php?group_id=$group_id");
        exit;
    }
}

// Change as of 2009-09-26: 1x1 open is no longer needed in stats (unreliable)

/* the user is authenticated, it can be client or one of (admin (=moderator rights),moderator,support,owner)
   detailed stats may be seen by moderor,owner or by clients that are permitted to do so (their stats on their messages). */
if ($active_membership == "owner" || $active_membership=="moderator")    
    $permtoall=1;
else
    $permtoall=0;
  
if (!$tpl) {
    include "class.FastTemplate.php";
    $tpl=new FastTemplate("./templates");
    $tpl->no_strict();
}
$tplist=array("ct_dist","t_date","total");
$tpl->assign_same($tplist);  
$tpl->assign("LANGUAGE",$language);
$tpl->assign("BASEURL",$_MX_var->baseUrl);

if ($permtoall==1 || (in_array(2,$stattypes) && in_array(1,$stattypes))) {
    $tpl->assign("t_click",$word["t_click"]);
    $tpl->assign("t_ratio",$word["t_ratio"]);    
    $tpl->assign("t_dclick",$word["t_dclick"]);
    $tres2 = mysql_query("select to_days(max(date)),to_days(min(date)) from feedback,trackf where 
                          feedback.id=trackf.feed_id and 
                          feedback.message_id='$message_id' and feedback.group_id='$group_id'");
    if ($tres2 && mysql_num_rows($tres2)) {
          $t1ld=mysql_result($tres2,0,0);
          $t1fd=mysql_result($tres2,0,1);
          if ($t1fd) {
              if ($t1ld>$abs_end_daynum) 
                  $abs_end_daynum=$t1ld;
              if ($t1fd<$abs_start_daynum) 
                  $abs_start_daynum=$t1fd;
          }
    }
}
else {
    $tpl->assign("t_click","&nbsp;");
    $tpl->assign("t_dclick","&nbsp;");
}
if ($permtoall==1 || (in_array(5,$stattypes) && in_array(4,$stattypes))) {
      $tpl->assign("t_1x1",$word["t_1x1"]);
      $tpl->assign("t_d1x1",$word["t_d1x1"]);
      $tres2 = mysql_query("select to_days(max(date)),to_days(min(date)) from track1x1 where 
                            message_id='$message_id' and group_id='$group_id'");
      if ($tres2 && mysql_num_rows($tres2)) {
          $t1ld=mysql_result($tres2,0,0);
          $t1fd=mysql_result($tres2,0,1);
          if ($t1fd) {
              if ($t1ld>$abs_end_daynum) 
                  $abs_end_daynum=$t1ld;
              if ($t1fd<$abs_start_daynum) 
                  $abs_start_daynum=$t1fd;
          }
      }
}
else {
    $tpl->assign("t_1x1","&nbsp;");
    $tpl->assign("t_d1x1","&nbsp;");
}
  
$tpl->define( array( "dategen" => "dategen.tpl"));

$min_start_daynum=730485; // 2000-01-01
$max_end_daynum=744364; // 2037-12-31

$nowyear=date('Y');
$dropdown_year=2001;
$nav_id="ct";

$tpl->assign("FORM_ACTION","clickthrough.php");
$tpl->assign("HIDDEN_PARTS","<input type='hidden' name='group_id' value='$group_id'><input type='hidden' name='message_id' value='$message_id'>");
$tpl->assign("GROUP_ID",$group_id);
$tpl->assign("MESSAGE_ID",$message_id);
include "dategen.php";
$tpl->parse("DATEGEN","dategen");
  
if ($permtoall==1 || (in_array(2,$stattypes) && in_array(1,$stattypes))) {
    $sortd=intval($sortd);
    $maxPerPage=intval($maxPerPage);
    if (empty($sortd) && isset($m451sortd)) 
        $sortd=$m451sortd;
    if (empty($maxPerPage) && isset($m451perpage)) 
        $maxPerPage=$m451perpage;
    if (empty($showgroup) && isset($m451showgroup)) 
        $showgroup=$m451showgroup;
    setcookie("m451sortd",$sortd,time()+30*24*3600);
    setcookie("m451perpage",$maxPerPage,time()+30*24*3600);
    setcookie("m451showgroup",$showgroup,time()+30*24*3600);  
}
  
include "menugen.php";

$tres2 = mysql_query("select sum(mails) from track where message_id='$message_id' and group_id='$group_id'");
if ($tres2 && mysql_num_rows($tres2))
    $mails=mysql_result($tres2,0,0);
$year=substr($create_date,0,4);
$month=substr($create_date,5,2);
$day=substr($create_date,8,2);

$explain="&nbsp; $word[group]: $group_title, $word[thread]: $subject, $word[time]: $year. $month. $day.";

if ($permtoall==1 || in_array(11,$stattypes)) {
    $replinks.="&nbsp;<a href='javascript:void(0);' id='print_page'>$word[html_report]</a>";
}

print "<br>
       <table width=100% border=0 cellspacing=0 cellpadding=0>
         <tr>
           <td width='100%'>
             <table width=100% border=0 cellspacing=0 cellpadding=0>
               <tr>
                 <td align='left'><span class='szovegvastag'>$word[ct_] - $explain</span></td><td align='right'>$replinks</td>
               </tr>
             </table>
           </td>
         </tr>
       </table>
       <br>\n";

if ($permtoall==1 || (in_array(2,$stattypes) && in_array(1,$stattypes))) {
    $query="from feedback where message_id='$message_id' and group_id='$group_id'";
    $maxrecords=0;
    $sql = "SELECT count(*) as maxrecords $query";
    $r = mysql_query($sql);
    if ($r && mysql_num_rows($r))
        $maxrecords = mysql_result($r,0,0);
    $maxPerPage=intval($maxPerPage);
    if($maxPerPage<1) $maxPerPage = 50;
    $pagenum=intval($pagenum);
    if($pagenum<1) $pagenum=1;
    if(!strlen($first) && $pagenum) $first=($pagenum-1)*$maxPerPage;
    if(!$first) $first = 0;
    if($first>$maxrecords) $first = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;
    $pagenum=(int)($first / $maxPerPage) + 1;
    $maxpages = ceil($maxrecords / $maxPerPage);
    if (!$sortd)
        $sortd=1;
    if ($sortd==1)
        $order="order by url asc";
    else
        $order="order by url desc";
    if ($maxrecords) {
        $stdout="";
        PrintNavigation();
        $stdout .= "<TABLE cellSpacing=0 cellPadding=0 width='100%' border=0>
              <TR>
              <TD class=MENUBORDER width='100%'>
              <TABLE cellSpacing=1 cellPadding=1 width='100%' border=0>
              <tr>
              <td class=bgkiemelt2 align='center'><span class=szovegvastag>$word[ct_url]</span></td>
              <td class=bgkiemelt2 align='center'><span class=szovegvastag>$word[ct_mailnum]</span></td>
              <td class=bgkiemelt2 align='center'><span class=szovegvastag>$word[ct_clicknum]</span></td>
              <td class=bgkiemelt2 align='center'><span class=szovegvastag>$word[t_ratio]</span></td>              
              <td class=bgkiemelt2 align='center'><span class=szovegvastag>$word[ct_clicknum_dist]</span></td>
              <td class=bgkiemelt2 align='center'><span class=szovegvastag>$word[ct_click_first]</span></td>
              <td class=bgkiemelt2 align='center'><span class=szovegvastag>$word[ct_click_last]</span></td>
              </tr>\n";
        $tres = mysql_query("select id,url,name $query $order limit $first,$maxPerPage");
        $chartData = array();
        if ($tres && mysql_num_rows($tres)) {
            while ($k=mysql_fetch_array($tres)) {
                $tres2 = mysql_query("select max(date),min(date),count(*) from trackf where feed_id=$k[id]");
                if ($tres2 && mysql_num_rows($tres2)) {
                    $k2=mysql_fetch_row($tres2);
                    $click_last=$k2[0];
                    $click_first=$k2[1];
                    $clicknum=$k2[2];
                }
                $fm = mysql_query("select count(*) from trackf where feed_id=$k[id] and from_html_version='no'");
                if ($fm && mysql_num_rows($fm)) {                
                    $k3=mysql_fetch_row($fm);                    
                    $fmail=$k3[0];
                }
                if ($clicknum==0) {
                    $click_last="-";
                    $click_first="-";
                }
                $clicknum_dist=0;
                $tres2 = mysql_query("select distinct user_id from trackf where feed_id=$k[id]");
                if ($tres2 && $clicknum_dist=mysql_num_rows($tres2));
                $percent="";
                if ($mails) {
                    $percent=$clicknum_dist==0?"":"&nbsp;(".number_format($clicknum_dist/$mails*100,2)."%)";
                }
                if (ereg("(http|https|ftp)://",$k["url"]))
                    $link_url=$k["url"];
                else
                $link_url="http://".$k["url"];
                $link_name=htmlspecialchars(strlen($k["name"])?$k["name"]:$k["url"]);
				//$link_name=$k["name"];
				if (mb_strlen($link_name,"UTF-8")>50) {
					$link_name=preg_replace("'https?://(www)?.'",'',$link_name);
					$link_name=mb_substr($link_name,0,30,"UTF-8");
				}
                $stdout .= "<tr>
                      <td valign='top' align='left' class=BACKCOLOR>
                      <span class='szoveg'><a title='$link_url' href='$link_url' target='_blank'>$link_name</a></span></td>
                      <td valign='top' align='left' class=BACKCOLOR>
                      <span class='szoveg'>$mails</span></td>
                      <td valign='top' align='left' class=BACKCOLOR>
                      <span class='szoveg'>$clicknum</span></td>
                      <td valign='top' align='left' class=BACKCOLOR>
                      <span class='szoveg'>".$fmail." / ".($clicknum-$fmail)."</span></td>                      
                      <td valign='top' align='left' class=BACKCOLOR>
                      <span class='szoveg'>";
                if ($permtoall==1 || (in_array(3,$stattypes) && in_array(1,$stattypes))) 
                    $stdout .= "<a href='#' class='vastag' onClick='window.open(\"$_MX_var->baseUrl/clickthrough_url.php?group_id=$group_id&feed_id=$k[id]&message_id=$message_id\", \"ctu\", \"width=650,height=400,scrollbars=yes,resizable=yes\"); return false;'>";
                $stdout .= "$clicknum_dist$percent";
                if ($permtoall==1 || (in_array(3,$stattypes) && in_array(1,$stattypes))) 
                    $stdout .= "</a>";
                $stdout .= "</span></td>
                       <td valign='top' align='left' class=BACKCOLOR>
                       <span class='szoveg'>$click_first</span></td>
                       <td valign='top' align='left' class=BACKCOLOR>
                       <span class='szoveg'>$click_last</span></td>
                       </tr>\n";
                                         $chartData[] = array($clicknum, $link_name, $fmail);

            }
            $clicknum_dist=0;
            $tres2 = mysql_query("select distinct user_id from feedback,trackf where feedback.id=trackf.feed_id and 
                                  feedback.message_id='$message_id' and feedback.group_id='$group_id'");
            if ($tres2 && $clicknum_dist=mysql_num_rows($tres2));
            $clicknum=$clicknumfb=0;

            $tres2 = mysql_query("select count(*),max(date),min(date) from feedback,trackf where feedback.id=trackf.feed_id and 
                                  feedback.message_id='$message_id' and feedback.group_id='$group_id'");
            if ($tres2 && mysql_num_rows($tres2)) {
                $clicknum=mysql_result($tres2,0,0);
                $click_last=mysql_result($tres2,0,1);
                $click_first=mysql_result($tres2,0,2);
            }
            $tres3 = mysql_query("select count(*) from feedback,trackf where feedback.id=trackf.feed_id and 
                feedback.message_id='$message_id' and feedback.group_id='$group_id' and trackf.from_html_version='yes'");            
            $clicknumfbs=mysql_result($tres3,0,0);
            $urlnum=0;
            $tres2 = mysql_query("select count(*) from feedback where message_id='$message_id' and group_id='$group_id'");
            if ($tres2 && mysql_num_rows($tres2))
                $urlnum=mysql_result($tres2,0,0);
            $percent=$clicknum_dist==0?"":"&nbsp;(".number_format($clicknum_dist/$mails*100,2)."%)";
            $stdout .= "<tr><td colspan='7'><img src='$_MX_var->application_instance/gfx/shim.gif' height='1' width='1'></td></tr><tr>
                  <td valign='top' align='left' class=BACKCOLOR>
                  <span class='szovegvastag'>$word[total] $urlnum URL</span></td>
                  <td valign='top' align='left' class=BACKCOLOR>
                  <span class='szoveg'>$mails</span></td>
                  <td valign='top' align='left' class=BACKCOLOR>
                  <span class='szoveg'>$clicknum</span></td>
                  <td valign='top' align='left' class=BACKCOLOR>
                  <span class='szoveg'>".($clicknum-$clicknumfbs)." / $clicknumfbs</span></td>
                  <td valign='top' align='left' class=BACKCOLOR>
                  <span class='szoveg'>";
            if ($permtoall==1 || (in_array(3,$stattypes) && in_array(1,$stattypes))) 
                $stdout .= "<a href='#' class='vastag' onClick='window.open(\"$_MX_var->baseUrl/clickthrough_url.php?group_id=$group_id&message_id=$message_id\", \"ctu\", \"width=650,height=400,scrollbars=yes,resizable=yes\"); return false;'>";
            $stdout .= "$clicknum_dist$percent";
            $tpl->assign("DCLICKNUM_SUM",$clicknum_dist);
            if ($permtoall==1 || (in_array(3,$stattypes) && in_array(1,$stattypes))) 
                $stdout .= " </a>";
            $stdout .= "</span></td>
                   <td valign='top' align='left' class=BACKCOLOR>
                   <span class='szoveg'>$click_first</span></td>
                   <td valign='top' align='left' class=BACKCOLOR>
                   <span class='szoveg'>$click_last</span></td>
                   </tr>
                   </table>
                   </td></tr></table>\n";      
        }
                        
                        echo '<tr><td colspan="3" class=BACKCOLOR>';

                        echo '<script type="text/javascript" src="'.$_MX_var->baseUrl.'/amcharts/ampie/swfobject.js"></script>
                        <div id="flashcontent" align="center">
            		        <strong>You need to upgrade your Flash Player</strong>
                	    </div>
                        <script type="text/javascript">
            		    // <![CDATA[		
            		        var so = new SWFObject("'.$_MX_var->baseUrl.'/amcharts/ampie/ampie.swf", "ampie", "920", "400", "8", "#FFFFFF");
                            so.addParam("wmode", "transparent");
                            so.addVariable("path", "");
                            so.addVariable("settings_file", encodeURIComponent("'.$_MX_var->baseUrl.'/amcharts/maxima/settings-pie.php?language='.$language.'&demog=true"));
                            so.addVariable("chart_data", "<pie>';
                            
                                foreach ($chartData as $item) {
                                    echo '<slice title=\''.$item[1].'\' pull_out=\'false\'>'.$item[0].'</slice>';
                                }
                            
                            echo '</pie>");
                            
                            so.write("flashcontent");
            		    // ]]>
                        </script>';
                        /*
                        $count=1;
                        foreach ($chartData as $item) {
                        echo '<script type="text/javascript" src="'.$_MX_var->baseUrl.'/amcharts/amcolumn/swfobject.js"></script>
                        <div id="flashcontent_'.$count.'" align="center">
            		        <strong>You need to upgrade your Flash Player</strong>
                	    </div>
                	    <script type="text/javascript">
                            // <![CDATA[		
                            var so'.$count.' = new SWFObject("'.$_MX_var->baseUrl.'/amcharts/amcolumn/amcolumn.swf", "amcolumn", "920", "400", "8", "#FFFFFF");
                            so'.$count.'.addVariable("path", "'.$_MX_var->baseUrl.'/amcharts/amcolumn/");
                            so'.$count.'.addVariable("settings_file",  encodeURIComponent("'.$_MX_var->baseUrl.'/amcharts/maxima/settings.php?language='.$language.'&type=demogdate"));
                            so'.$count.'.addVariable("chart_data", "<chart><series>';
                            
                            foreach ($chartData as $key=>$item) {
                                echo '<value xid=\''.$key.'\'>'.$item[1].'</value>';
                            }

                            echo '</series><graphs><graph gid=\'1\'>';
                            
                            foreach ($chartData as $key=>$item) {
                                echo '<value xid=\''.$key.'\'>'.$item[0].'</value>';
                            }
                                                        
                            echo '</graph></graphs></chart>");
                            
                            so'.$count.'.write("flashcontent_'.$count.'");
                            // ]]>
                	    </script>';
                        $count++;
                        }*/

                        echo '</td></tr>';


        echo $stdout;
        PrintNavigation();
    }
}

$tpl->define(array( "statistics" => "ct_daily.tpl"));
$tpl->define_dynamic("list_row","statistics");
$tpl->define_dynamic("is_ct","statistics");

if ($end_daynum-$start_daynum<=1) {
   $view_mode="hours";
   $what="to_days(date),substring(date,9,5)";
   $loop_end=($end_daynum-$start_daynum+1)*24;
   $step=3600;
}
else {
   $view_mode="days";
   $what="to_days(date)";
   $loop_end=$end_daynum-$start_daynum+1;
   $step=86400;
}

if ($permtoall==1 || (in_array(2,$stattypes) && in_array(1,$stattypes))) {
  $clicknum=array();
  $dclicknum=array();
  $clicknumfb=array();  
   $qtext="select count(*),count(distinct user_id),$what as d,sum(if (from_html_version='yes',1,0))
                      from feedback,trackf 
                      where feedback.id=trackf.feed_id and 
			          feedback.message_id='$message_id' and feedback.group_id='$group_id'
                      and to_days(date)<='$end_daynum' and to_days(date)>='$start_daynum'
                      group by d order by d";
$query = mysql_query($qtext);

  if ($query && mysql_num_rows($query)) {
    while ($l=mysql_fetch_row($query)) {
	  if ($view_mode=="days") {
            $in = $l[2] - $start_daynum;
      $clicknumfb[$in] = $l[3];      
	 }
	  else {
          	$in = ($l[2] - $start_daynum)*24+intval(substr($l[3],3,2));
      $clicknumfb[$in] = $l[4];      
	  }

      $clicknum[$in] = $l[0];      
      $dclicknum[$in] = $l[1];      
    }
  }
}
else {
    $tpl->clear_dynamic("is_ct");
}

$bars1="";
for ($i=0;$i<$loop_end;$i++) {
   if ($i) {
      $bars1.=",";
   }
   $bars1.=intval($clicknum[$i]);
}

mysql_query("insert into stat_cache (dateadd,ordnum) values (now(),'$bars1')");
$cache_id=mysql_insert_id();
$tpl->assign("CACHE_ID",$cache_id);

$datanum=$end_daynum-$start_daynum+1;
$compress_step=ceil($datanum/50);

$prevyear=-1;
$prevmonth=-1;
$prevday=-1;
$m_i=1;
$y_i=0;
$stamp=mktime(0,0,0,$startmonth,$startday,$startyear,1)+1800;
$clicknum_sum=$clicknumfb_sum=0;
for ($i=0;$i<$loop_end;$i++) {
   if ($i%2)
      $tpl->assign("TD_STYLE","bggray");   
   else
      $tpl->assign("TD_STYLE","bgwhite");
   $tpl->assign("GRNUM",floor($i/$compress_step));
   $daylight=(1-date("I",$stamp))*$step;
   if ($daylight && date("I",$stamp+$daylight))
       $daylight=$daylight*0.75;   
   $c_year=date("Y",$stamp+$daylight);
   $c_month=date("n",$stamp+$daylight);
   $c_day=date("j",$stamp+$daylight);
   $c_hour=date("G",$stamp+$daylight);
   $tpl->assign("YEAR",$c_year);
   $tpl->assign("YEARSEP","");
   if ($c_year!=$prevyear) {
      $tpl->assign("YEARNAME",$c_year);
      if ($y_i%2) {
         $tpl->assign("YEARSTYLE","bggray");   
		 $voidbg_year="bggray";
	  }
      else {
         $tpl->assign("YEARSTYLE","bgwhite");
		 $voidbg_year="bgwhite";
	  }
	  $y_i++;
	  if ($prevyear!=-1)
	     $tpl->assign("YEARSEP","<tr>
		              <td colspan='7' bgcolor='#cccc33'>
		              <img src='$_MX_var->application_instance/gfx/spacer.gif' width='1' height='1'></td></tr>");
   }
   else
      $tpl->assign("YEARNAME","");
   $tpl->assign("MONTH",$c_month);
   $tpl->assign("MONTHSEP","");
   if ($c_month!=$prevmonth) {
      $tpl->assign("MONTHNAME",$word["month$c_month"]);
      if ($m_i%2) {
         $tpl->assign("MONTHSTYLE","bggray");   
		 $voidbg_month="bggray";
	  }
      else {
         $tpl->assign("MONTHSTYLE","bgwhite");
		 $voidbg_month="bgwhite";
	  }
	  $m_i++;
	  if ($prevmonth!=-1)
	     $tpl->assign("MONTHSEP","<tr><td class='$voidbg_year'>
		              <img src='$_MX_var->application_instance/gfx/spacer.gif' width='1' height='1'></td>
		              <td colspan='8' bgcolor='#33cc33'>
		              <img src='$_MX_var->application_instance/gfx/spacer.gif' width='1' height='1'></td></tr>");
   }
   else
      $tpl->assign("MONTHNAME","");
   $day=date("w",$stamp+$daylight);
   if ($day==0 || $day==6)
      $tpl->assign("HETVEGE","hetvege");
   else
      $tpl->assign("HETVEGE","font1");
   $tpl->assign("DAYSEP","");
   $tpl->assign("WEEKSEP","");
   if ($c_day!=$prevday) {
      $tpl->assign("SUBPERIOD_B",$word["day$day"]);
      $tpl->assign("DAYNAME",$c_day);
      if ($i%2 || ($view_mode=="hours" && $i>23))
         $tpl->assign("DAYSTYLE","bggray");   
      else
         $tpl->assign("DAYSTYLE","bgwhite");
	  if ($prevday!=-1 && $view_mode=="hours")
	     $tpl->assign("DAYSEP","<tr><td class='$voidbg_year'>
		              <img src='$_MX_var->application_instance/gfx/spacer.gif' width='1' height='1'></td>
					  <td class='$voidbg_month'>
		              <img src='$_MX_var->application_instance/gfx/spacer.gif' width='1' height='1'></td>
		              <td colspan='7' bgcolor='#3333cc'>
		              <img src='$_MX_var->application_instance/gfx/spacer.gif' width='1' height='1'></td></tr>");		 
	  if ($prevday!=-1 && $day==1)
	     $tpl->assign("WEEKSEP","<tr><td class='$voidbg_year'>
		              <img src='$_MX_var->application_instance/gfx/spacer.gif' width='1' height='1'></td>
					  <td class='$voidbg_month'>
		              <img src='$_MX_var->application_instance/gfx/spacer.gif' width='1' height='1'></td>
		              <td colspan='7' bgcolor='#cc3333'>
		              <img src='$_MX_var->application_instance/gfx/spacer.gif' width='1' height='1'></td></tr>");		 
   }
   else {
      $tpl->assign("DAYNAME","");
      $tpl->assign("SUBPERIOD_B","");
   }
   $prevyear=$c_year;
   $prevmonth=$c_month;
   $prevday=$c_day;
   $tpl->assign("DAY",$c_day);
   if ($view_mode=="hours")
      $tpl->assign("HOURS","$c_hour h");
   else
      $tpl->assign("HOURS","");
   if ($permtoall==1 || (in_array(2,$stattypes) && in_array(1,$stattypes))) {
       $tpl->assign("DCLICKNUM",number_format($dclicknum[$i]));
       $tpl->assign("CLICKNUM",number_format($clicknum[$i]));
       $tpl->assign("CLICKNUMFB",(number_format($clicknum[$i]-$clicknumfb[$i]))."/".number_format($clicknumfb[$i]));       
       $clicknum_sum+=$clicknum[$i];
       $clicknumfb_sum+=$clicknumfb[$i];       
   }
   else {
       $tpl->assign("CLICKNUM","&nbsp;");
       $tpl->assign("DCLICKNUM","&nbsp;");
   }
   if ($permtoall==1 || (in_array(5,$stattypes) && in_array(4,$stattypes))) {
       $tpl->assign("DT1X1",number_format($dviewnum[$i]));
       $tpl->assign("T1X1",number_format($viewnum[$i]));
       $viewnum_sum+=$viewnum[$i];
   }
   else {
       $tpl->assign("T1X1","&nbsp;");
       $tpl->assign("DT1X1","&nbsp;");
   }
   $tpl->parse("LIST_ROW",".list_row");      
   $stamp+=$step;
}
if ($permtoall==1 || (in_array(2,$stattypes) && in_array(1,$stattypes))) {
    $tpl->assign("CLICKNUM_SUM",number_format($clicknum_sum));
    $tpl->assign("CLICKNUMFB_SUM",number_format($clicknum_sum - $clicknumfb_sum)."/".number_format($clicknumfb_sum));
} else {
    $tpl->assign("DCLICKNUM_SUM","&nbsp;");
    $tpl->assign("CLICKNUM_SUM","&nbsp;");
}
if ($permtoall==1 || (in_array(5,$stattypes) && in_array(4,$stattypes))) 
    $tpl->assign("T1X1SUM",number_format($viewnum_sum));
else {
    $tpl->assign("DT1X1SUM","&nbsp;");
    $tpl->assign("T1X1SUM","&nbsp;");
}

$tpl->parse("MAIN",".statistics");

$tpl->FastPrint("MAIN");

include "footer.php";

//****[ PrintNavigation ]*****************************************************
function PrintNavigation() {
global $_MX_var,$word, $root, $sortd, $maxPerPage, $maxrecords, $first, $maxpages, $pagenum, $group_id; 
global $_MX_var,$message_id;
  
  $LastPage = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;
  $OnePageLeft = $first - $maxPerPage; if($OnePageLeft<1) $OnePageLeft = 0;
  $OnePageRight = $maxPerPage + $first; if($OnePageRight>$LastPage) $OnePageRight = $LastPage;
  $sel_sort[$sortd] = "SELECTED"; 
  
  echo "
    <table border=0 cellspacing=0 cellpadding=0 width='100%' class='noprint'>
	<tr>
    	<td class='formmezo' align='left' width='33%'>
	  <table border='0' cellspacing='0' cellpadding='0'>
          <form name=inputs>
          <input type='hidden' name='group_id' value='$group_id'>
          <input type='hidden' name='message_id' value='$message_id'>
          <tr>";
 if ($first>0)
 echo " 
            <td nowrap align='right'>&nbsp;&nbsp;
              <a href='$_MX_var->baseUrl/clickthrough.php?message_id=$message_id&group_id=$group_id&first=0'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down2.gif' width='20' height='14' border='0'></a>
              <a href='$_MX_var->baseUrl/clickthrough.php?message_id=$message_id&group_id=$group_id&first=$OnePageLeft'><img
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
              <input type='text' name='pagenum' size='2' maxlength='3' value='$pagenum'>
            </td>
            <td nowrap class='formmezo'>&nbsp; / $maxpages</td>";
if ($first<$LastPage)
echo "
            <td nowrap align='right'> &nbsp;
              <a href='$_MX_var->baseUrl/clickthrough.php?message_id=$message_id&group_id=$group_id&first=$OnePageRight'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up1.gif' width='20' height='14' border='0'></a>
              <a href='$_MX_var->baseUrl/clickthrough.php?message_id=$message_id&group_id=$group_id&first=$LastPage'><img
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
    	<td class='formmezo' align='center' width='33%'>
        <table border='0' cellspacing='0' cellpadding='0'>
          <tr> 
  <form>
          <input type='hidden' name='group_id' value='$group_id'>
          <input type='hidden' name='message_id' value='$message_id'>
	  <td nowrap align='center' class='formmezo'>&nbsp;$word[view]: </td>
            <td nowrap align='center'> 
              <input type='text' name='maxPerPage' value='$maxPerPage' size='2' maxlength='3'>
            </td>
            <td nowrap align='center' class='formmezo'>&nbsp;$word[url_page]</td>
</form>
          </tr>
        </table>
      </td>
    	<td class='formmezo' align='center' width='33%'>
        <table border='0' cellspacing='0' cellpadding='0'>
          <tr> 
  <form>
          <input type='hidden' name='group_id' value='$group_id'>
          <input type='hidden' name='message_id' value='$message_id'>
	  <td nowrap class='formmezo'>&nbsp;$word[sort_order]:</td>
            <td nowrap><span class='szoveg'> 
              <select onChange='JavaScript: this.form.submit();' name=sortd>
                <option value=1 $sel_sort[1]>$word[by_ct_url_asc]</option>
                <option value=2 $sel_sort[2]>$word[by_ct_url_desc]</option>
              </select>
              </span>
            </td>
</form>
          </tr>
        </table>
      </td>
    </tr>
  </table>
 </td>
</tr>
  ";
}

?>
