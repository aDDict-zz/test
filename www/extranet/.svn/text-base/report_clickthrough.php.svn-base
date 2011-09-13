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
  
$group_title=$row["title"];
!empty($admin_addq)?$act_memb="moderator":$act_memb=$row["membership"];

$report=1;

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
    if ( (!in_array(11,$stattypes) && $report==1) ) {
        // user has no right to see HTML report.
        header("location: threadlist.php?group_id=$group_id");
        exit;
    }
}

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

$tplist=array("ct_dist","t_date","t_click","total","ct_url","ct_mailnum","ct_clicknum",
              "ct_clicknum_dist","ct_click_first","ct_click_last","group","all1x1","all1x1dist");
$tpl->assign_same($tplist);  
$tpl->assign("LANGUAGE",$language);

if ($permtoall==1 || (in_array(2,$stattypes) && in_array(1,$stattypes))) {
    $tpl->assign("t_click",$word["t_click"]);
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
      
$nowyear=date('Y');
$dropdown_year=2001;
$nav_id="ct";

$min_start_daynum=730485; // 2000-01-01
$max_end_daynum=744364; // 2037-12-31

include "dategen.php";

$tres2 = mysql_query("select sum(mails) from track where message_id='$message_id' and group_id='$group_id'");
if ($tres2 && mysql_num_rows($tres2))
    $mails=mysql_result($tres2,0,0);
$year=substr($create_date,0,4);
$month=substr($create_date,5,2);
$day=substr($create_date,8,2);

$tpl->assign("TPART1",$word["ct_"]);
$tpl->assign("TPART2","$word[group]: $group_title, $word[thread]: $subject");
$tpl->assign("TPART3","$word[time]: $year. $month. $date.");

include "report_head.php";
$tpl->define(array( "statistics" => "report_ct_daily.tpl"));
$bgstyle1="bgwhite";
$bgstyle2="bggray";    

$tpl->define_dynamic("list_row","statistics");
$tpl->define_dynamic("url_list","statistics");
$tpl->define_dynamic("is_ct","statistics");
$tpl->define_dynamic("is_1x1","statistics");

$query="from feedback where message_id='$message_id' and group_id='$group_id'";

if ($permtoall==1 || (in_array(2,$stattypes) && in_array(1,$stattypes))) {
    $tres = mysql_query("select id,url $query order by url asc");
    if ($tres && mysql_num_rows($tres)) {
    $rs=0;
      while ($k=mysql_fetch_array($tres)) {
        $tres2 = mysql_query("select max(date),min(date),count(*) from trackf where feed_id=$k[id]");
	if ($tres2 && mysql_num_rows($tres2)) {
	  $k2=mysql_fetch_row($tres2);
	  $click_last=$k2[0];
	  $click_first=$k2[1];
	  $clicknum=$k2[2];
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
	    $percent=$clicknum_dist==0?"":" (".number_format($clicknum_dist/$mails*100,2)."%)";
    }
    $tpl->assign("URL",$k["url"]);
    $tpl->assign("MAILS",$mails);
    $tpl->assign("CLICKNUM",$clicknum);
    $tpl->assign("CLICKNUM_DIST","$clicknum_dist$percent");
    $tpl->assign("CLICK_FIRST",$click_first);
    $tpl->assign("CLICK_LAST",$click_last);
    if ($rs % 2) 
        $tpl->assign("RSTYLE",$bgstyle2);
    else
        $tpl->assign("RSTYLE",$bgstyle1);
    $rs++;
    $tpl->parse("URL_LIST",".url_list");
   }
   $clicknum_dist=0;
   $tres2 = mysql_query("select distinct user_id from feedback,trackf where 
                         feedback.id=trackf.feed_id and 
			 feedback.message_id='$message_id' and feedback.group_id='$group_id'");
   if ($tres2 && $clicknum_dist=mysql_num_rows($tres2));
   $clicknum=0;
   $tres2 = mysql_query("select count(*),max(date),min(date) from feedback,trackf where 
                         feedback.id=trackf.feed_id and 
			 feedback.message_id='$message_id' and feedback.group_id='$group_id'");
   if ($tres2 && mysql_num_rows($tres2)) {
      $clicknum=mysql_result($tres2,0,0);
      $click_last=mysql_result($tres2,0,1);
      $click_first=mysql_result($tres2,0,2);
   }
   $urlnum=0;
   $tres2 = mysql_query("select count(*) from feedback where
                         message_id='$message_id' and group_id='$group_id'");
   if ($tres2 && mysql_num_rows($tres2))
      $urlnum=mysql_result($tres2,0,0);
   $percent=$clicknum_dist==0?"":" (".number_format($clicknum_dist/$mails*100,2)."%)";
    $tpl->assign("SURL","$word[total] $urlnum URL");
    $tpl->assign("SMAILS",$mails);
    $tpl->assign("SCLICKNUM",$clicknum);
    $tpl->assign("SCLICKNUM_DIST","$clicknum_dist$percent");
    $tpl->assign("DCLICKNUM_SUM",$clicknum_dist);
    $tpl->assign("SCLICK_FIRST",$click_first);
    $tpl->assign("SCLICK_LAST",$click_last);

}
else {
    $tpl->clear_dynamic("url_list");
    $tpl->assign("SURL","-");
    $tpl->assign("SMAILS","-");
    $tpl->assign("SCLICKNUM","-");
    $tpl->assign("SCLICKNUM_DIST","-");
    $tpl->assign("SCLICK_FIRST","-");
    $tpl->assign("SCLICK_LAST","-");
}
}

if ($permtoall==1 || (in_array(5,$stattypes) && in_array(4,$stattypes))) {
    $all1x1=0;
    $all1x1dist=0;
    $query = mysql_query("select count(*),count(distinct user_id) from track1x1 
                          where message_id='$message_id' and group_id='$group_id'
                          and to_days(date)<='$end_daynum' and to_days(date)>='$start_daynum'");
    if ($query && mysql_num_rows($query)) {
        $all1x1=mysql_result($query,0,0);
        $all1x1dist=mysql_result($query,0,1);
        $tpl->assign("T1X1SUM",$all1x1);
        $tpl->assign("DT1X1SUM",$all1x1dist);
    }
}

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
  $query = mysql_query("select count(*),count(distinct user_id),$what as d from feedback,trackf 
                      where feedback.id=trackf.feed_id and 
			          feedback.message_id='$message_id' and feedback.group_id='$group_id'
                      and to_days(date)<='$end_daynum' and to_days(date)>='$start_daynum'
                      group by d order by d");
  if ($query && mysql_num_rows($query)) {
    while ($l=mysql_fetch_row($query)) {
	  if ($view_mode=="days")
         $in = $l[2] - $start_daynum;
	  else
	     $in = ($l[2] - $start_daynum)*24+intval(substr($l[3],3,2));
      $clicknum[$in] = $l[0];      
      $dclicknum[$in] = $l[1];      
    }
  }
}
else {
    $tpl->clear_dynamic("is_ct");
}

if ($permtoall==1 || (in_array(5,$stattypes) && in_array(4,$stattypes))) {
    $viewnum=array();
    $dviewnum=array();
    $query = mysql_query("select count(*),count(distinct user_id),$what as d from track1x1 
                      where message_id='$message_id' and group_id='$group_id'
                      and to_days(date)<='$end_daynum' and to_days(date)>='$start_daynum'
                      group by d order by d");
    if ($query && mysql_num_rows($query)) {
      while ($l=mysql_fetch_row($query)) {
	    if ($view_mode=="days")
         $in = $l[2] - $start_daynum;
	    else
	     $in = ($l[2] - $start_daynum)*24+intval(substr($l[3],3,2));
        $viewnum[$in] = $l[0];      
        $dviewnum[$in] = $l[1];      
     }
  }
}
else {
    $tpl->clear_dynamic("is_1x1");
}

$bars1="";
$bars2="";
for ($i=0;$i<$loop_end;$i++) {
   if ($i) {
      $bars1.=",";
      $bars2.=",";
   }
   $bars1.=intval($clicknum[$i]);
   $bars2.=intval($viewnum[$i]);
}

mysql_query("insert into stat_cache (dateadd,ordnum) values (now(),'$bars1')");
$cache_id=mysql_insert_id();
mysql_query("insert into stat_cache (dateadd,ordnum) values (now(),'$bars2')");
$cache_id2=mysql_insert_id();

$tpl->assign("CACHE_ID",$cache_id);
$tpl->assign("CACHE_ID2",$cache_id2);

$datanum=$end_daynum-$start_daynum+1;
$compress_step=ceil($datanum/50);

$prevyear=-1;
$prevmonth=-1;
$prevday=-1;
$m_i=1;
$y_i=0;
$stamp=mktime(0,0,0,$startmonth,$startday,$startyear,1)+1800;
$clicknum_sum=0;
for ($i=0;$i<$loop_end;$i++) {
   if ($i%2)
      $tpl->assign("TD_STYLE",$bgstyle2);   
   else
      $tpl->assign("TD_STYLE",$bgstyle1);
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
         $tpl->assign("YEARSTYLE",$bgstyle2);   
		 $voidbg_year=$bgstyle2;
	  }
      else {
         $tpl->assign("YEARSTYLE",$bgstyle1);
		 $voidbg_year=$bgstyle1;
	  }
	  $y_i++;
	  if ($prevyear!=-1)
	     $tpl->assign("YEARSEP","<tr>
		              <td colspan='8' bgcolor='#cccc33'>
		              <img src='$_MX_var->application_instance/gfx/spacer.gif' width='1' height='1'></td></tr>");
   }
   else
      $tpl->assign("YEARNAME","");
   $tpl->assign("MONTH",$c_month);
   $tpl->assign("MONTHSEP","");
   if ($c_month!=$prevmonth) {
      $tpl->assign("MONTHNAME",$word["month$c_month"]);
      if ($m_i%2) {
         $tpl->assign("MONTHSTYLE",$bgstyle2);   
		 $voidbg_month=$bgstyle2;
	  }
      else {
         $tpl->assign("MONTHSTYLE",$bgstyle1);
		 $voidbg_month=$bgstyle1;
	  }
	  $m_i++;
	  if ($prevmonth!=-1)
	     $tpl->assign("MONTHSEP","<tr><td class='$voidbg_year'>
		              <img src='$_MX_var->application_instance/gfx/spacer.gif' width='1' height='1'></td>
		              <td colspan='7' bgcolor='#33cc33'>
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
         $tpl->assign("DAYSTYLE",$bgstyle2);   
      else
         $tpl->assign("DAYSTYLE",$bgstyle1);
	  if ($prevday!=-1 && $view_mode=="hours")
	     $tpl->assign("DAYSEP","<tr><td class='$voidbg_year'>
		              <img src='$_MX_var->application_instance/gfx/spacer.gif' width='1' height='1'></td>
					  <td class='$voidbg_month'>
		              <img src='$_MX_var->application_instance/gfx/spacer.gif' width='1' height='1'></td>
		              <td colspan='6' bgcolor='#3333cc'>
		              <img src='$_MX_var->application_instance/gfx/spacer.gif' width='1' height='1'></td></tr>");		 
	  if ($prevday!=-1 && $day==1)
	     $tpl->assign("WEEKSEP","<tr><td class='$voidbg_year'>
		              <img src='$_MX_var->application_instance/gfx/spacer.gif' width='1' height='1'></td>
					  <td class='$voidbg_month'>
		              <img src='$_MX_var->application_instance/gfx/spacer.gif' width='1' height='1'></td>
		              <td colspan='6' bgcolor='#cc3333'>
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
       $clicknum_sum+=$clicknum[$i];
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
if ($permtoall==1 || (in_array(2,$stattypes) && in_array(1,$stattypes))) 
    $tpl->assign("CLICKNUM_SUM",number_format($clicknum_sum));
else {
    $tpl->assign("DCLICKNUM_SUM","&nbsp;");
    $tpl->assign("CLICKNUM_SUM","&nbsp;");
}
if ($permtoall==1 || (in_array(5,$stattypes) && in_array(4,$stattypes))) 
    $tpl->assign("T1X1SUM",number_format($viewnum_sum));
else {
    $tpl->assign("DT1X1SUM","&nbsp;");
    $tpl->assign("T1X1SUM","&nbsp;");
}

$rep_filename="ct";
include "report_foot.php";
  
?>
