<?
include_once "common.php";
$language=select_lang();
include "./lang/$language/dategen.lang";

$res=mysql_query("select to_days(now())");
if ($res && mysql_num_rows($res)) 
   $now=mysql_result($res,0,0);

$max_daynum_diff=366;
if ($nav_id=="messages" || $nav_id=="form_stat") {
    $max_daynum_diff=2500;
}
$max_graph_diff=50;
$start_daynum=0;
$end_daynum=$now;

$messdebug.= ${"sessperiod$nav_id"};
$messdebug.= "*";
$messdebug.= ${"sessstart_daynum$nav_id"};
$messdebug.= "*";
$messdebug.= ${"sessend_daynum$nav_id"};
$messdebug.= "|0*$end_daynum";

$vartypes=array("lday","lmonth","lyear","startyear","endyear","startmonth","endmonth","startday","endday","period","period_left","peiod_right","sessperiod$nav_id","sessstart_daynum$nav_id","sessend_daynum$nav_id");
foreach ($vartypes as $vvvt) {
    $$vvvt=get_http($vvvt,"");
    if (!$$vvvt) $$vvvt=slasher($_COOKIE[$vvvt]);
}

if (!($lday || $lmonth || $lyear || $startyear || !empty($period))) {
    if (!empty(${"sessperiod$nav_id"}))
        $period=${"sessperiod$nav_id"};
    elseif (${"sessstart_daynum$nav_id"} && ${"sessend_daynum$nav_id"}) {
        $start_daynum=${"sessstart_daynum$nav_id"};
        $end_daynum=${"sessend_daynum$nav_id"};
    }
    else
        $period="lastmonth";
$messdebug.= "|1*$end_daynum";
}

if ($lday) {
   $res=mysql_query("select to_days('$lyear-$lmonth-$lday')");
   if ($res && mysql_num_rows($res)) {
      $start_daynum=mysql_result($res,0,0);
      $end_daynum=$start_daynum;
   }
$messdebug.= "|2*$end_daynum";
}
elseif ($lmonth) {
   $month_end=date("t",mktime(0,0,0,$lmonth,15,$lyear));
   $res=mysql_query("select to_days('$lyear-$lmonth-1'),to_days('$lyear-$lmonth-$month_end')");
   if ($res && mysql_num_rows($res)) {
      $start_daynum=mysql_result($res,0,0);
      $end_daynum=mysql_result($res,0,1);
   }
$messdebug.= "|3*$end_daynum";
}
elseif ($lyear) {
   $res=mysql_query("select to_days('$lyear-1-1'),to_days('$lyear-12-31')");
   if ($res && mysql_num_rows($res)) {
      $start_daynum=mysql_result($res,0,0);
      $end_daynum=mysql_result($res,0,1);
   }
$messdebug.= "|4*$end_daynum";
}

if ($startyear) {
   $res=mysql_query("select to_days('$startyear-$startmonth-$startday'),
                     to_days('$endyear-$endmonth-$endday')");
   if ($res && mysql_num_rows($res)) {
      $start_daynum=mysql_result($res,0,0);
      $end_daynum=mysql_result($res,0,1);
   }
$messdebug.= "|5*$end_daynum";
}
if ($period=="today") {
   $start_daynum=$now;
   $end_daynum=$now;
}
if ($period=="yesterday") {
   $start_daynum=$now-1;
   $end_daynum=$now-1;
}
if ($period=="lastweek") {
   $start_daynum=$now-6;
   $end_daynum=$now;
}
if ($period=="lastmonth") {
   $start_daynum=$now-29;
   $end_daynum=$now;
}
if ($period=="allcamp") {
   $start_daynum=$abs_start_daynum;
   $end_daynum=$abs_end_daynum;
   if ($nav_id=="messages" || $nav_id=="form_stat") {
	   $end_daynum=$now;
   }
}
$messdebug.= "|6*$end_daynum";

if (strlen($period_right)) {
   $daynum_diff=$end_daynum-$start_daynum;
   $start_daynum=$start_daynum+$daynum_diff+1;
   $end_daynum=$end_daynum+$daynum_diff+1;
}
if (strlen($period_left)) {
   $daynum_diff=$end_daynum-$start_daynum;
   $start_daynum=$start_daynum-$daynum_diff-1;
   $end_daynum=$end_daynum-$daynum_diff-1;
}

if ($end_daynum>$max_end_daynum)
   $end_daynum=$abs_end_daynum;
if ($end_daynum<$min_start_daynum)
   $end_daynum=$min_start_daynum;
if ($start_daynum<$min_start_daynum)
   $start_daynum=$min_start_daynum;
if ($start_daynum>$end_daynum)
   $start_daynum=$end_daynum;
if ($end_daynum-$start_daynum>$max_daynum_diff)
   $start_daynum=$end_daynum-$max_daynum_diff;
$messdebug.= "|7*$end_daynum";

if (!empty($period) && !strlen($period_left) && !strlen($period_right)) {
    setcookie("sessperiod$nav_id",$period,time()+3600);
    setcookie("sessend_daynum$nav_id");
    setcookie("sessstart_daynum$nav_id");
    $tpl->assign("PER_$period","selected");
}
else {
    setcookie("sessend_daynum$nav_id",$end_daynum,time()+3600);
    setcookie("sessstart_daynum$nav_id",$start_daynum,time()+3600);
    setcookie("sessperiod$nav_id");
}

$res=mysql_query("select from_days($start_daynum),from_days($end_daynum)");
if ($res && mysql_num_rows($res)) {
   $startdate=mysql_result($res,0,0);
   $enddate=mysql_result($res,0,1);
   $startyear=intval(substr($startdate,0,4));
   $startmonth=intval(substr($startdate,5,2));
   $startday=intval(substr($startdate,8,2));
   $endyear=intval(substr($enddate,0,4));
   $endmonth=intval(substr($enddate,5,2));
   $endday=intval(substr($enddate,8,2));
}

$startyear_options="";
$startmonth_options="";
$startday_options="";
$endyear_options="";
$endmonth_options="";
$endday_options="";
for ($i=2001;$i<=$nowyear+2;$i++) {
  $i==$startyear?$sel="selected":$sel="";
  $startyear_options.="<option value=$i $sel>$i</option>";
  $i==$endyear?$sel="selected":$sel="";
  $endyear_options.="<option value=$i $sel>$i</option>";
}
for ($i=1;$i<=12;$i++) {
  $monthname=$word["month$i"];
  $i==$startmonth?$sel="selected":$sel="";
  $startmonth_options.="<option value=$i $sel>$monthname</option>";
  $i==$endmonth?$sel="selected":$sel="";
  $endmonth_options.="<option value=$i $sel>$monthname</option>";
}
for ($i=1;$i<=31;$i++) {
  $i==$startday?$sel="selected":$sel="";
  $startday_options.="<option value=$i $sel>$i</option>";
  $i==$endday?$sel="selected":$sel="";
  $endday_options.="<option value=$i $sel>$i</option>";
}

$tpl->assign("STARTYEAR_OPTIONS",$startyear_options);
$tpl->assign("STARTMONTH_OPTIONS",$startmonth_options);
$tpl->assign("STARTDAY_OPTIONS",$startday_options);
$tpl->assign("ENDYEAR_OPTIONS",$endyear_options);
$tpl->assign("ENDMONTH_OPTIONS",$endmonth_options);
$tpl->assign("ENDDAY_OPTIONS",$endday_options);
$tpl->assign("START_DAYNUM",$start_daynum);
$tpl->assign("END_DAYNUM",$end_daynum);

$tplist=array("dg_period","dg_all","dg_lastmonth","dg_lastweek","dg_yesterday","dg_today","dg_go");
$tpl->assign_same($tplist);
?>
