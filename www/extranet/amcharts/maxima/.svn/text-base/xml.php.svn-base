<?php
    header ("Content-Type:text/xml");

if ($report==2) {
    include "/maxima2.0/auth.php";
}
else {
    include "../../auth.php";
    $plusdot="../.";
    $language = get_http("language","");
    if (empty($language)) $language="hu";
    include "../../lang/$language/statistics.lang";    
}

$cache_id=get_http("cache_id",0);
$type_id=get_http("type",0);

$bars1=array();
$bars2=array();

$start_daynum=get_http("start_daynum",0);
$end_daynum=get_http("end_daynum", 0);
$res=mysql_query("SELECT FROM_DAYS(".$start_daynum."), FROM_DAYS(".$end_daynum.");");
if ($res && mysql_num_rows($res)) {
   $startdate=mysql_result($res,0,0);
   $enddate=mysql_result($res,0,1);
}

if ($end_daynum-$start_daynum<=1) {
   $view_mode="hours";
   $step=3600;
}
else {
   $view_mode="days";
   $step=86400;
}

$res=mysql_query("select ordnum from stat_cache where id='$cache_id'");
if ($res && mysql_num_rows($res)) {
   $bars1=explode(",",mysql_result($res,0,0));
}

$datanum=count($bars1);
$compress_step=1;
$compress_step_label=ceil($datanum/50);
$barnum=ceil($datanum/$compress_step);

$width=580-$shrink;
if ($barnum<10)
  $width=380-$shrink;

if ($desc==1) 
    $what="$word[ct_all]";
elseif ($desc==11) 
    $what="$word[ct_gdist]";
elseif ($desc==12) 
    $what=$word["1x1_gdist"];
elseif ($desc==41) 
    $what="$word[members]";
elseif ($desc==42) 
    $what="$word[new_members]";
elseif ($desc==43) 
    $what="$word[unsub_members]";
elseif ($desc==44) 
    $what="$word[unval_members]";
elseif ($desc==46) 
    $what="$word[title52]";
elseif ($desc==47) 
    $what="$word[title62]";
elseif ($desc==144) 
    $what="$word[sg_subs_members]";
else 
    $what="$word[copies]";

$x_data = array();

$stamp=mktime(0,0,0,substr($startdate,5,2),substr($startdate,8,2),substr($startdate,0,4))+1800;
for ($i=0;$i<$barnum;$i++) {
   for ($k=$i*$compress_step;$k<($i+1)*$compress_step;$k++) {
      $bars1_comp[$i]+=$bars1[$k]; 
   }
   if (($i+1)*$compress_step>count($bars1)) {
        $cct = count($bars1) - $i*$compress_step;
   } else {
        $cct = $compress_step;
   }
   if ($cct == 0) $cct = $compress_step;
    
   $bars1_comp[$i]=floor($bars1_comp[$i]/$cct); 
   if ($view_mode=="hours") 
      $x_data[$i]=$i%24;
   else {
      //if ($i%$compress_step_label==0) {
        $x_data[$i]=date("M j",$stamp);
      //} else {
        
        //$x_data[$i]='';//$datanum.'-'.$i . '-' . $compress_step_label;
      //}
   }
   $stamp+=$step*$compress_step;
}
if ($view_mode=="hours") 
   $s_per="$word[b_hour]";
else
   $s_per="$word[b_day]";

if ($compress_step>1)
   $s_avg="$word[b_avg]";
else
   $s_avg="";

$explain="$what $startdate -- $enddate, 1 $word[b_col] = $compress_step $s_per $s_avg";
$y_data['d1'] = $bars1_comp;

?><chart><series>
<?php foreach($x_data as $x_key=>$x_value) { ?>
<value xid="<?=(100+$x_key);?>"><?=$x_value;?></value>
<?php } ?>
</series>
<graphs>
<graph gid="1">
<?php foreach($y_data['d1'] as $y_key=>$y_value) { ?>
<value xid="<?=(100+$y_key);?>" color="#318DBD"><?=$y_value;?></value>
<?php } ?>
</graph>

<?php if ($type_id!="mess" && $type_id!="ct" ) { ?>
<graph gid="2">
<?php

$avgArray = array();
$counter = 0;
$avgCounter = ceil(count($y_data['d1'])/8);
foreach($y_data['d1'] as $y_key=>$y_value) {
$avgArray[] = $y_value;
if (count($avgArray)>$avgCounter) {
    array_shift($avgArray);
}
$avgArray2 = $avgArray;

for ($i=0; $i<$avgCounter; $i++) {
    if (isset($y_data['d1'][$y_key+$i])) {
        $avgArray2[] = $y_data['d1'][$y_key+$i];
    }
}

$avg = ceil(array_sum($avgArray2) / count($avgArray2));
?><value xid="<?=(100+$y_key);?>"><?=$avg;?></value>
<?php } ?>
</graph>
<?php } ?>
</graphs></chart>

