<?
include "auth.php";
$weare=31;
include "cookie_auth.php";  
include "common.php";
$language=select_lang();
include "./lang/$language/statistics.lang";

$mres = mysql_query("select title,num_of_mess from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and user_id='$active_userid'
                     and (membership='owner' or membership='moderator' $admin_addq)");
if ($mres && mysql_num_rows($mres))
    $row=mysql_fetch_array($mres);  
else {
    header("Location: $_MX_var->baseUrl/index.php?no_group=1"); exit; }
  
$group_title=$row["title"];
$title=$row["title"];
  
$tres2 = mysql_query("select to_days(now()),to_days(min(date)) from users_$title");
if ($tres2 && mysql_num_rows($tres2)) {
      $abs_end_daynum=mysql_result($tres2,0,0);
      $abs_start_daynum=mysql_result($tres2,0,1);
}
else {
      $tres2 = mysql_query("select to_days(now())");
      $abs_end_daynum=mysql_result($tres2,0,0);
      $abs_start_daynum=mysql_result($tres2,0,0)-30;
} 
  
if (!$tpl) {
    include "class.FastTemplate.php";
    $tpl=new FastTemplate("./templates");
    $tpl->no_strict();
}

$res = mysql_query("select name from groups where id = $group_id");
if ($res && mysql_num_rows($res)) {
    $active_title=mysql_result($res,0,0);
}

$tpl->assign("ACTIVETITLE",$active_title);
$tplist=array("html_report","t_date","group","group_lower","total","t_messages","ct_ct_ct","ct_all");
$tpl->assign_same($tplist);
$tpl->assign("language",$language);
$tpl->assign("GTITLE",$title);
$tpl->assign("BASEURL",$_MX_var->baseUrl);
$tpl->define( array( "dategen" => "dategen.tpl"));

$min_start_daynum=730851; // 2001-01-01
$max_end_daynum=744364; // 2037-12-31

$nowyear=date('Y');
$dropdown_year=2001;
$nav_id="clickthrough_group";

$tpl->assign("FORM_ACTION","clickthrough_group.php");
$tpl->assign("HIDDEN_PARTS","<input type='hidden' name='group_id' value='$group_id'>");
$tpl->assign("GROUP_ID",$group_id);
include "dategen.php";
$tpl->parse("DATEGEN","dategen");
  
if ($report==1) {
    include "report_head.php";
    $tpl->define(array( "statistics" => "report_clickthrough_group.tpl"));
    $bgstyle1="bgwhite";
    $bgstyle2="bggray";
}
else {
    include "menugen.php";
    $tpl->define(array( "statistics" => "clickthrough_group.tpl"));
    $bgstyle1="bgwhite";
    $bgstyle2="bggray";
}

$tpl->define_dynamic("list_row","statistics");
$tpl->define_dynamic("statmain","statistics");

if ($_REQUEST["dostat"] || $report) {
    $subsnum=array();

    if ($end_daynum-$start_daynum<=1) {
       $view_mode="hours";
       $what="to_days(date),substring(date,9,5)";
       $whatc="to_days(create_date),substring(create_date,9,5)";
       $loop_end=($end_daynum-$start_daynum+1)*24;
       $step=3600;
    }
    else {
       $view_mode="days";
       $what="to_days(date)";
       $whatc="to_days(create_date)";
       $loop_end=$end_daynum-$start_daynum+1;
       $step=86400;
    }

    $query = mysql_query("select sum(mails),$what as d from track 
                          where group_id='$group_id' and 
                          to_days(date)<='$end_daynum' and to_days(date)>='$start_daynum'
                          group by d order by d");
      if ($query && mysql_num_rows($query)) {
        while ($l=mysql_fetch_row($query)) {
          if ($view_mode=="days")
             $in = $l[1] - $start_daynum;
          else
             $in = ($l[1] - $start_daynum)*24+intval(substr($l[2],3,2));
          $subsnum[$in] = $l[0];      
        }
      }

    logger($q,$group_id,"","clickthrough_statisztika","trackf");
    $query = mysql_query("select count(distinct user_id,message_id),$what as d from feedback,trackf 
                          where feedback.id=trackf.feed_id and group_id='$group_id' and 
                          to_days(date)<='$end_daynum' and to_days(date)>='$start_daynum'
                          group by d order by d");
      if ($query && mysql_num_rows($query)) {
        while ($l=mysql_fetch_row($query)) {
          if ($view_mode=="days")
             $in = $l[1] - $start_daynum;
          else
             $in = ($l[1] - $start_daynum)*24+intval(substr($l[2],3,2));
          $allnum[$in] += $l[0];      
        }
      }


    $bars1="";
    $bars2="";
    for ($i=0;$i<$loop_end;$i++) {
       if ($i) {
          $bars1.=",";
          $bars2.=",";
       }
       $bars1.=intval($allnum[$i]);
    }

    mysql_query("insert into stat_cache (dateadd,ordnum) 
                 values (now(),'$bars1')");
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
    $subsnum_sum=0;
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
                          <td colspan='6' bgcolor='#cccc33'>
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
                          <td colspan='5' bgcolor='#33cc33'>
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
                          <td colspan='4' bgcolor='#3333cc'>
                          <img src='$_MX_var->application_instance/gfx/spacer.gif' width='1' height='1'></td></tr>");		 
          if ($prevday!=-1 && $day==1)
             $tpl->assign("WEEKSEP","<tr><td class='$voidbg_year'>
                          <img src='$_MX_var->application_instance/gfx/spacer.gif' width='1' height='1'></td>
                          <td class='$voidbg_month'>
                          <img src='$_MX_var->application_instance/gfx/spacer.gif' width='1' height='1'></td>
                          <td colspan='4' bgcolor='#cc3333'>
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

       $tpl->assign("SUBSNUM",number_format($subsnum[$i]));
       $tpl->assign("ALLNUM",number_format($allnum[$i]));
       $subsnum_sum+=$subsnum[$i];
       $allnum_sum+=$allnum[$i];
       $tpl->parse("LIST_ROW",".list_row");      
       $stamp+=$step;
    }
    $tpl->assign("SUBSNUM_SUM",number_format($subsnum_sum));
    $tpl->assign("ALLNUM_SUM",number_format($allnum_sum));

    $allpercent=($allnum_sum==0 || $subsnum_sum==0)?"&nbsp;":"&nbsp;(".number_format($allnum_sum/$subsnum_sum*100,2)."%)";
    $tpl->assign("PERCENT",$allpercent);

}
else {
    $tpl->clear_dynamic("statmain");
}

$rep_filename="mess";
include "report_foot.php";

?>
