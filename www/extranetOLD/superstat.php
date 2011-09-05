<?
include "auth.php";
include "cookie_auth.php";  
include "common.php";
$language=select_lang();
include "./lang/$language/statistics.lang";

$multiid=intval(get_http("multiid",0));

$mres = mysql_query("select multi.* from multi,multi_members where multi.id=multi_members.group_id
                     and multi.id='$multiid' and user_id='$active_userid' 
                     and membership='moderator'");

if ($mres && mysql_num_rows($mres))
    $row=mysql_fetch_array($mres);  
else {
    header("Location: index.php"); 
    exit; 
}

$group_title=$row["title"];
$title=$row["title"];

if (!$tpl) {
    include "class.FastTemplate.php";
    $tpl=new FastTemplate("./templates");
    $tpl->no_strict();
}
$tplist=array("html_report","aff_filt","aff_all","t_date","sgs_all","sgs_yes","sgs_no","sgs_avg","group","group_lower");
$tpl->assign_same($tplist);
$tpl->assign("language",$language);
$tpl->assign("GTITLE",$title);
$tpl->assign("MULTIID",$multiid);

$type=intval($type);
$type=4; //for now this is the only type for multigroups.

$tpl->assign("TYPE",$type);

$aff=intval($aff);
$afflist="";
$affpart="";
$reportaff="";
//if ($active_membership=="owner" || $active_membership=="moderator") {
    $r2=mysql_query("select distinct multivalidation.aff,user.email from
                     multivalidation,user where multivalidation.aff=user.id 
                     and multivalidation.group_id='$multiid'");
    if ($r2 && mysql_num_rows($r2)) {
        while ($k2=mysql_fetch_array($r2)) {
            if ($aff==$k2["aff"])
                $sel="selected";
            else
                $sel="";
            $afflist.="<option value='$k2[aff]' $sel>$k2[email] $word[aff_members]</option>";
        }
    }
    if ($aff) {
        $affpart="and aff='$aff'";
        if ($report) {
            $res=mysql_query("select email from user where id='$aff'");
            if ($res && mysql_num_rows($res))
                $reportaff="[".mysql_result($res,0,0)." $word[aff]]";
        }
    }
//}
if ($active_membership=="affiliate")
    $affpart="and aff='$active_userid'";

$tpl->assign("AFF",$aff);

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

$tpl->define( array( "dategen" => "dategen.tpl"));

$min_start_daynum=730851; // 2001-01-01
$max_end_daynum=744364; // 2037-12-31

$nowyear=date('Y');
$dropdown_year=2001;
$nav_id="superstat";

$tpl->assign("FORM_ACTION","superstat.php");
$tpl->assign("HIDDEN_PARTS","<input type='hidden' name='type' value='$type'><input type='hidden' name='multiid' value='$multiid'><input type='hidden' name='aff' value='$aff'>");
$tpl->assign("AFF_HIDDEN_PARTS","<input type='hidden' name='type' value='$type'><input type='hidden' name='multiid' value='$multiid'>");
$tpl->assign("GROUP_ID",$group_id);
include "dategen.php";
$tpl->parse("DATEGEN","dategen");
  
$sgweare=140+$type;
$tpl->assign("DESC",100+$sgweare);
if ($report==1) {
    include "report_head.php";
    $tpl->define(array( "statistics" => "report_superstat.tpl"));
    $bgstyle1="bgwhite";
    $bgstyle2="bggray";    
}
else {
    include "menugen.php";
    $tpl->define(array( "statistics" => "superstat.tpl"));
    $tpl->define_dynamic("affs","statistics");
    $bgstyle1="bgwhite";
    $bgstyle2="bggray";    
}
$tpl->define_dynamic("list_row","statistics");

if (!empty($afflist))
    $tpl->assign("AFFLIST",$afflist);
elseif (!$report)
    $tpl->clear_dynamic("affs");
    
$addnum=0;
if ($type==4) {
    $date_type="date";
    $total=0;
    $doctitle="$word[titlesg4]";
}

$tpl->assign("DOCTITLE","$doctitle $reportaff");

$subsnum=array();

if ($end_daynum-$start_daynum<=1) {
   $view_mode="hours";
   $what="to_days($date_type),substring($date_type,9,5)";
   $loop_end=($end_daynum-$start_daynum+1)*24;
   $step=3600;
}
else {
   $view_mode="days";
   $what="to_days($date_type)";
   $loop_end=$end_daynum-$start_daynum+1;
   $step=86400;
}

$val_no=array();
$val_yes=array();
$val_all=array();
$val_diff=array();

if ($type==4) {
    $total=0;
    $q="select count(*),validated,sum(unix_timestamp(tstamp)-unix_timestamp(date)),
          $what as d from multivalidation where action='sub'
          and to_days($date_type)<='$end_daynum' and to_days($date_type)>='$start_daynum'
          and group_id='$multiid' $affpart group by d,validated order by d";
    //print $q;
    $res=mysql_query($q);
    if ($res && mysql_num_rows($res)) {
        while ($l=mysql_fetch_row($res)) {
          if ($view_mode=="days")
             $in = $l[3] - $start_daynum;
          else
             $in = ($l[3] - $start_daynum)*24+intval(substr($l[4],3,2));
          if ($l[1]=="no")
             $val_no[$in]+=$l[0];
          else {
             $val_yes[$in]+=$l[0];
             $val_diff[$in]+=$l[2];
          }
          $val_all[$in]+=$l[0];
        }
    }
}

$bars1="";
$bars2="";
for ($i=0;$i<$loop_end;$i++) {
   if ($i) {
      $bars1.=",";
      $bars2.=",";
   }
   $bars1.=intval($val_yes[$i]);
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
   $yearsep=0;
   $monthsep=0;
   $weeksep=0;
   $daysep=0;
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
	  if ($prevyear!=-1) {
	     $tpl->assign("YEARSEP","<tr>
		              <td colspan='8' bgcolor='#cccc33'>
		              <img src='$_MX_var->application_instance/gfx/spacer.gif' width='1' height='1'></td></tr>");
         $yearsep=5;
      }
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
	  if ($prevmonth!=-1) {
	     $tpl->assign("MONTHSEP","<tr><td class='$voidbg_year'>
		              <img src='$_MX_var->application_instance/gfx/spacer.gif' width='1' height='1'></td>
		              <td colspan='7' bgcolor='#33cc33'>
		              <img src='$_MX_var->application_instance/gfx/spacer.gif' width='1' height='1'></td></tr>");
         $monthsep=4;
      }
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
	  if ($prevday!=-1 && $view_mode=="hours") {
	     $tpl->assign("DAYSEP","<tr><td class='$voidbg_year'>
		              <img src='$_MX_var->application_instance/gfx/spacer.gif' width='1' height='1'></td>
					  <td class='$voidbg_month'>
		              <img src='$_MX_var->application_instance/gfx/spacer.gif' width='1' height='1'></td>
		              <td colspan='6' bgcolor='#3333cc'>
		              <img src='$_MX_var->application_instance/gfx/spacer.gif' width='1' height='1'></td></tr>");
         $daysep=3;
      }
	  if ($prevday!=-1 && $day==1) {
	     $tpl->assign("WEEKSEP","<tr><td class='$voidbg_year'>
		              <img src='$_MX_var->application_instance/gfx/spacer.gif' width='1' height='1'></td>
					  <td class='$voidbg_month'>
		              <img src='$_MX_var->application_instance/gfx/spacer.gif' width='1' height='1'></td>
		              <td colspan='6' bgcolor='#cc3333'>
		              <img src='$_MX_var->application_instance/gfx/spacer.gif' width='1' height='1'></td></tr>");
         $weeksep=3;
      }
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

   $tpl->assign("SALL","&nbsp;");
   $tpl->assign("SNO","&nbsp;");
   $tpl->assign("SYES","&nbsp;");
   $tpl->assign("SAVG","&nbsp;");
   if ($val_all[$i]) {
       $tpl->assign("SALL",$val_all[$i]);
       if ($val_no[$i]) {
           $perc=number_format($val_no[$i]/$val_all[$i]*100,2);
           $tpl->assign("SNO","$val_no[$i] ($perc%)");
       }
       if ($val_yes[$i]) {
           $perc=number_format($val_yes[$i]/$val_all[$i]*100,2);
           $tpl->assign("SYES","$val_yes[$i] ($perc%)");
           $avg=($val_diff[$i]/$val_yes[$i])/3600;
           if ($avg>0 && $avg<720) // reasonable values, older data may not have all the dates (tstamp especially)
               $tpl->assign("SAVG",number_format($avg,2));
       }
   }
   $val_no_sum+=$val_no[$i];
   $val_yes_sum+=$val_yes[$i];
   $val_all_sum+=$val_all[$i];
   $val_diff_sum+=$val_diff[$i];
   if ($yearsep && $report==2) {
       $y_old=$tpl->get_assigned("YEARNAME");
       $tpl->assign("YEARNAME","!SEPARATOR-0-$yearsep");
       $tpl->parse("LIST_ROW",".list_row");
       $tpl->assign("YEARNAME",$y_old);
   }
   if ($monthsep && $report==2) {
       $y_old=$tpl->get_assigned("YEARNAME");
       $tpl->assign("YEARNAME","!SEPARATOR-1-$monthsep");
       $tpl->parse("LIST_ROW",".list_row");
       $tpl->assign("YEARNAME",$y_old);
   }
   if ($weeksep && $report==2) {
       $y_old=$tpl->get_assigned("YEARNAME");
       $tpl->assign("YEARNAME","!SEPARATOR-3-$weeksep");
       $tpl->parse("LIST_ROW",".list_row");
       $tpl->assign("YEARNAME",$y_old);
   }
   if ($daysep && $report==2) {
       $y_old=$tpl->get_assigned("YEARNAME");
       $tpl->assign("YEARNAME","!SEPARATOR-2-$daysep");
       $tpl->parse("LIST_ROW",".list_row");
       $tpl->assign("YEARNAME",$y_old);
   }
   $tpl->parse("LIST_ROW",".list_row");
   $stamp+=$step;
}
$tpl->assign("SUMTEXT","$word[total]");
   $tpl->assign("SALLSUM","&nbsp;");
   $tpl->assign("SNOSUM","&nbsp;");
   $tpl->assign("SYESSUM","&nbsp;");
   $tpl->assign("SAVGSUM","&nbsp;");
   if ($val_all_sum) {
       $tpl->assign("SALLSUM",$val_all_sum);
       if ($val_no_sum) {
           $perc=number_format($val_no_sum/$val_all_sum*100,2);
           $tpl->assign("SNOSUM","$val_no_sum ($perc%)");
       }
       if ($val_yes_sum) {
           $perc=number_format($val_yes_sum/$val_all_sum*100,2);
           $tpl->assign("SYESSUM","$val_yes_sum ($perc%)");
           $avg=($val_diff_sum/$val_yes_sum)/3600;
           if ($avg>0 && $avg<720) // reasonable values, older data may not have all the dates (tstamp especially)
               $tpl->assign("SAVGSUM",number_format($avg,2));
       }
   }

$rep_filename="user";
include "report_foot.php";
?>
