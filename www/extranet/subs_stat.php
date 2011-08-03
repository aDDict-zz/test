<? 

include "main.php";

include "auth.php";
$type=intval(get_http("type",1));
if ($type<1 || $type>4) {
    exit;
}
$mcount=1;
$weare=40+$type;
$sgweare=240+$type;

include "cookie_auth.php";  
include "common.php";
$language=select_lang();
include "./lang/$language/statistics.lang";

$group_id=get_http("group_id",0);
$multiid=get_http("multiid",0);


if ($group_id) {
    $multiid=0;
    $mres = mysql_query("select title,num_of_mess,membership from groups,members where groups.id=members.group_id
                         and groups.id='$group_id' and user_id='$active_userid'
                         and (membership='owner' or membership='moderator' or membership='affiliate' $admin_addq)");
    if ($mres && mysql_num_rows($mres))
        $row=mysql_fetch_array($mres);  
    else {
        header("Location: $_MX_var->baseUrl/index.php?no_group=1"); exit; }
}
else {
    $mres = mysql_query("select multi.*,membership from multi,multi_members where multi.id=multi_members.group_id
                         and multi.id='$multiid' and user_id='$active_userid' 
                         and membership in ('moderator','affiliate')");
    if ($mres && mysql_num_rows($mres))
        $row=mysql_fetch_array($mres);  
    else {
        header("Location: index.php?no_group=1"); exit; }
    $group_id="";
    $title=$row["title"];
    $multititles=array();
    $res=mysql_query("select g.title from multigroup mg,groups g where mg.groupid=g.id and mg.multiid='$multiid'");
    if ($res && mysql_num_rows($res)) {
        while ($k=mysql_fetch_array($res)) {
            $multititles[]=$k["title"];
        }
    }
    if (count($multititles)==0) {
        exit;
    }
}
if ($multiid && ($type<1 || $type>2)) { // only these stats will work for multis for now
    exit;
}

$group_title=$row["title"];

$title=$row["title"];
$active_membership=$row["membership"];

if (!$tpl) {
    include "class.FastTemplate.php";
    $tpl=new FastTemplate("./templates");
    $tpl->no_strict();
}
$tplist=array("html_report","aff_filt","aff_all","t_date","t_members","group","group_lower");
$tpl->assign_same($tplist);
$tpl->assign("language",$language);
$tpl->assign("GTITLE",$title);

$tpl->assign("GIDPOST",$group_id);
$tpl->assign("MIDPOST",$multiid);

if ($active_membership=="affiliate" && $type>=4) { //affiliates should not see info about not validated members
    exit;
}

$multi_unique_members="";
if ($active_membership=="moderator" && $multiid) { 
    $res=mysql_query("select date,members from multi_unique_members where multiid='$multiid' order by date desc limit 1");
    if ($res && mysql_num_rows($res)) {
        $mum_date=mysql_result($res,0,0);
        $mum_members=mysql_result($res,0,1);
        $multi_unique_members="<br>Összes egyedi tag (minden affiliate): $mum_members ($mum_date)";
    }
}

if ($type==3 && $active_membership=="moderator") {
    $tpl->assign("CSVLINK","<a class='felso' href='subs_stat.php?group_id=$group_id&aff=$aff&type=3&report=3'>CSV report (leiratkozott tagok listája)</a>&nbsp;");
}
else {
    $tpl->assign("CSVLINK","");
}

$tpl->assign("TYPE",$type);

$aff=get_http("aff",0);
$report=get_http("report",0);
$afflist="";
$affpart="";
$reportaff="";
if ($active_membership=="owner" || $active_membership=="moderator") {
    if ($multiid) {
        // for the multigroups, scanning all member groups for possible affiliates is costly;
        // therefore check only for possible affiliates in the multi_members table
        // this means that they should be added as multi members to appear here
	    $q="select u.id aff,u.email from multi_members m,user u where m.group_id='$multiid' and m.user_id=u.id";
    }
    else {
	    $q="select distinct users_$title.aff,user.email from users_$title,user where users_$title.aff=user.id";
    }
    $r2=mysql_query($q);    
	logger($q,$group_id,"","statisztika","users_");
    if ($r2 && mysql_num_rows($r2)) {
        while ($k2=mysql_fetch_array($r2)) {
            if ($multiid && !$aff) { // for multis, only show stats per aff, the union creates a huge table otherwise
                $aff=$k2["aff"];
            }
            if ($aff==$k2["aff"]) {
                $sel="selected";
            }
            else {
                $sel="";
            }
            $afflist.="<option value='$k2[aff]' $sel>$k2[email] $word[aff_members]</option>";
        }
    }
    if ($aff) {
        $affpart="and aff='$aff'";
        $affpart_id=$aff;
        if ($report) {
            $res=mysql_query("select email from user where id='$aff'");
            if ($res && mysql_num_rows($res))
                $reportaff="[".mysql_result($res,0,0)." $word[aff]]";
        }
    }
}
if ($active_membership=="affiliate") {
    $affpart="and aff='$active_userid'";
    $affpart_id=$active_userid;
}

$tpl->assign("AFF",$aff);

$tres2 = mysql_query("select to_days(now())");
$abs_end_daynum=mysql_result($tres2,0,0);
$abs_start_daynum=mysql_result($tres2,0,0)-30;
if ($group_id) {
    $tres2 = mysql_query("select to_days(now()),to_days(min(date)) from users_$title");
    if ($tres2 && mysql_num_rows($tres2)) {
        $abs_end_daynum=mysql_result($tres2,0,0);
        $abs_start_daynum=mysql_result($tres2,0,1);
    }
}

$tpl->define( array( "dategen" => "dategen.tpl"));

$min_start_daynum=730851; // 2001-01-01
$max_end_daynum=744364; // 2037-12-31

$nowyear=date('Y');
$dropdown_year=2001;
$nav_id="subs_stat";

$res = mysql_query("select name from groups where id = $group_id");
if ($res && mysql_num_rows($res)) {
    $active_title=mysql_result($res,0,0);
}

$tpl->assign("BASEURL",$_MX_var->baseUrl);
$tpl->assign("ACTIVETITLE",$active_title);
$tpl->assign("FORM_ACTION","subs_stat.php");
$tpl->assign("HIDDEN_PARTS","<input type='hidden' name='type' value='$type'><input type='hidden' name='group_id' value='$group_id'><input type='hidden' name='aff' value='$aff'><input type='hidden' name='multiid' value='$multiid'>");
$tpl->assign("AFF_HIDDEN_PARTS","<input type='hidden' name='type' value='$type'><input type='hidden' name='group_id' value='$group_id'><input type='hidden' name='multiid' value='$multiid'>");
$tpl->assign("GROUP_ID",$group_id);
$tpl->assign("MULTIID",$multiid);
include "dategen.php";
$tpl->parse("DATEGEN","dategen");

$tpl->assign("DESC",$weare);
if ($report==1) {
    include "report_head.php";
    $tpl->define(array( "statistics" => "report_subs_stat.tpl"));
    $bgstyle1="bgwhite";
    $bgstyle2="bggray";    
}
elseif ($report==3 && $type==3 && $active_membership=="moderator") {
    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment;filename=leiratkozottak_$group_title.csv");
    print csv_no_comma("Maxima Report");
    print "\n";
    print csv_no_comma(date("Y-m-d H:i:s"));
    print "\n";
}
else {
    include "menugen.php";
    $tpl->define(array( "statistics" => "subs_stat.tpl"));
    $tpl->define_dynamic("allaffs","statistics");
    if ($multiid) {
        $tpl->clear_dynamic("allaffs");
    }
    $tpl->define_dynamic("affs","statistics");
    $bgstyle1="bgwhite";
    $bgstyle2="bggray";    
    $report=0;
}
if ($report!=3) {
    $tpl->define_dynamic("list_row","statistics");
    $tpl->define_dynamic("statmain","statistics");
}

if (!empty($afflist)) {
    $tpl->assign("AFFLIST",$afflist);
}
elseif (!$report) {
    $tpl->clear_dynamic("affs");
}
        
if ($_REQUEST["dostat"] || $report) {
    $t1c_addon="";
    if ($type==1) {
        $date_type="validated_date";
        $status_str=" and validated='yes' and bounced='no' $affpart";
        $t1c_addon=" and robinson='no' ";
        $doctitle="$word[title1]";
        $doctitle2="$word[title12]";
    }
    elseif ($type==2) {
        $date_type="validated_date";
        #again, we should show here number of subscribers; how many of them unsubscribed, tells the next stat.
        $status_str=" and validated='yes' and bounced='no' $affpart";
        $doctitle="$word[title2]";
        $doctitle2="$word[title22]";
        $t1c_addon=" and robinson='no' ";
    }
    elseif ($type==3) {
        $date_type="unsub_date";
        $status_str=" and validated='yes' and robinson!='no' and bounced='no' $affpart";
        $doctitle="$word[title3]";
        $doctitle2="$word[title32]";
    }

    if ($multiid) {
        // note that this union for multis carries the same problem we already had for singles - 
        // if I sub 12 days ago then unsub 10 days ago in group A
        // then I sub again 7 days ago and then unsub 5 days ago
        // I was not member of this multi 8 days ago, however this sql will say I was.
        // Note that for singles we have the same problem through other mechanisms.
        $union=array();
        foreach ($multititles as $mt) {
            $union[]="select ui_email,validated_date,unsub_date,robinson from users_$mt where 1 $status_str";
        }
        $status_str="";
        $union_all=implode(" union all ",$union);
        mysql_query("create temporary table users_$title select ui_email,min(validated_date) validated_date,max(unsub_date) unsub_date,min(robinson) robinson
                     from ($union_all) uall group by ui_email");
        mysql_query("alter table users_$title add key ui_email(ui_email)");
        //print mysql_error();
        $stat_group="users_$title";
        if (!empty($affpart)) {
            foreach ($multititles as $mt) {
                // remove those who have registrated earlier, under some other affiliate.
                $fix_only_from="";
                if ($affpart_id==80503) {
                    $fix_only_from=" and ug.validated_date>'2008-05-26 11:20:00'";
                }
                $q="delete ug from users_$title ug,users_$mt g where ug.ui_email=g.ui_email and g.aff!=$affpart_id and g.validated_date<ug.validated_date $fix_only_from"; 
                mysql_query($q);
                //print htmlspecialchars($q) . "<br>";
            }
        }
        //$stat_group="(select ui_email,min(validated_date) validated_date,max(unsub_date) unsub_date,min(robinson) robinson
        //              from ($union_all) uall group by ui_email) users_$title";
    }
    else {
        $stat_group="users_$title";
    }

    $addnum=0;
    if ($type==1) {
        #calculate how many users were the day before (subscribed-robinsons THEN),
        #than, the first query will calculate subscribers, the second robinsons.
        $query="select count(*) from $stat_group where 
                          (to_days(validated_date)<'$start_daynum' or isnull(validated_date)) $status_str";
                          #print "<br>$query<br>";
        $res=mysql_query($query);
        if ($res && mysql_num_rows($res))
            $addnum=mysql_result($res,0,0);
        $query="select count(*) from $stat_group where robinson!='no'
                          and (to_days(unsub_date)<'$start_daynum' or isnull(unsub_date)) $status_str";
                          #print "<br>$query<br>";
        $res=mysql_query($query);
        if ($res && mysql_num_rows($res))
            $addnum-=mysql_result($res,0,0);
    }

    $total=0;
    if ($type==4) {
        $date_type="date";
        $total=0;
        $res=mysql_query("select count(*) from validation where action='sub' and validated='no'
                        and group_id='$group_id'");
        if ($res && mysql_num_rows($res))
            $total+=mysql_result($res,0,0);
        # the following query is really disgusting, but multivalidation.groups coloumn was
        # obviously not designed for such query. If there will be problems with speed the
        # subscribe scripts should be slightly changed.
        $res=mysql_query("select count(*) from multivalidation where action='sub' and validated='no'
                          and (concat(' ',groups,' ') regexp '[[:space:]]$title"."[[:space:]|\\|]' 
                          or concat(',',groups,',') like '%,$group_id,%')");
        if ($res && mysql_num_rows($res))
            $total+=mysql_result($res,0,0);
        $doctitle="$word[title4]";
        $doctitle2="$word[title42]";
    }
    else {
        $res=mysql_query("select count(*) from $stat_group where 1 $status_str $t1c_addon");
        if ($res && mysql_num_rows($res)) {
            $total=mysql_result($res,0,0);
        }
    }

    if ($report!=3) {
        $tpl->assign("DOCTITLE","$doctitle $total $doctitle2 $reportaff $multi_unique_members");
    }
    else {
        print csv_no_comma("$doctitle2");
        print "\n";
        $rb=mysql_query("select from_days($start_daynum),from_days($end_daynum)");
        if ($rb && mysql_num_rows($rb)) 
            print csv_no_comma(substr(mysql_result($rb,0,0),0,10)." - ".substr(mysql_result($rb,0,1),0,10));
        print "\n";
    }

    $subsnum=array();

    if ($end_daynum-$start_daynum<=1) {
       $view_mode="hours";
       $what="to_days($date_type),substring($date_type,9,5)";
       $what2="to_days(unsub_date),substring(unsub_date,9,5)";
       $loop_end=($end_daynum-$start_daynum+1)*24;
       $step=3600;
    }
    else {
       $view_mode="days";
       $what="to_days($date_type)";
       $what2="to_days(unsub_date)";
       $loop_end=$end_daynum-$start_daynum+1;
       $step=86400;
    }

    if ($type==4) {
        $total=0;
        $res=mysql_query("select count(*),$what as d from validation where action='sub' and validated='no'
                          and to_days($date_type)<='$end_daynum' and to_days($date_type)>='$start_daynum'
                          and group_id='$group_id' group by d order by d");
        if ($res && mysql_num_rows($res)) {
            while ($l=mysql_fetch_row($res)) {
              if ($view_mode=="days")
                 $in = $l[1] - $start_daynum;
              else
                 $in = ($l[1] - $start_daynum)*24+intval(substr($l[2],3,2));
              $subsnum[$in] += $l[0];      
            }
        }
        $res=mysql_query("select count(*),$what as d from multivalidation where action='sub' and validated='no'
                          and (concat(' ',groups,' ') regexp '[[:space:]]$title"."[[:space:]|\\|]' 
                          or concat(',',groups,',') like '%,$group_id,%')
                          and to_days($date_type)<='$end_daynum' and to_days($date_type)>='$start_daynum'
                          group by d order by d");
        if ($res && mysql_num_rows($res)) {
            while ($l=mysql_fetch_row($res)) {
              if ($view_mode=="days")
                 $in = $l[1] - $start_daynum;
              else
                 $in = ($l[1] - $start_daynum)*24+intval(substr($l[2],3,2));
              $subsnum[$in] += $l[0];      
            }
        }
    }
    else {
        if ($type==3 && $report==3 && $active_membership=="moderator") {
            $q="select ui_email,unsub_date from $stat_group where to_days($date_type)<='$end_daynum' and 
                to_days($date_type)>='$start_daynum' $status_str order by unsub_date";
            print csv_no_comma("Dátum");
            print ";";
            print csv_no_comma("Tag");
            print "\n";
            $res=mysql_query($q);
            while ($k=mysql_fetch_array($res)) {
                print csv_no_comma($k["unsub_date"]);
                print ";";
                print csv_no_comma($k["ui_email"]);
                print "\n";
            }
            exit;
        }
        else {
            $q="select count(*),$what as d from $stat_group where to_days($date_type)<='$end_daynum' and 
                to_days($date_type)>='$start_daynum' $status_str group by d order by d";
        }
        $query = mysql_query($q);
    //print "<br>$q<br>";
      if ($query && mysql_num_rows($query)) {
        while ($l=mysql_fetch_row($query)) {
          if ($view_mode=="days")
             $in = $l[1] - $start_daynum;
          else
             $in = ($l[1] - $start_daynum)*24+intval(substr($l[2],3,2));
          $subsnum[$in] += $l[0];      
        }
      }
    }
    $data1=array();
    if ($type==1) {
        for ($j=0;$j<$loop_end;$j++) 
            for ($jj=$j;$jj<$loop_end;$jj++)  
                $data1[$jj]+=$subsnum[$j];
        $q="select count(*),$what2 as d from $stat_group 
                              where to_days(unsub_date)<='$end_daynum' and to_days(unsub_date)>='$start_daynum'
                              and validated='yes' and robinson!='no' and bounced='no' $affpart
                              group by d order by d";
        $query = mysql_query($q);
                          #print "<br>$q<br>";
          if ($query && mysql_num_rows($query)) {
            while ($l=mysql_fetch_row($query)) {
              if ($view_mode=="days")
                 $in = $l[1] - $start_daynum;
              else
                 $in = ($l[1] - $start_daynum)*24+intval(substr($l[2],3,2));
              $robnum[$in] = $l[0];      
            }
          }
        for ($j=0;$j<$loop_end;$j++) 
            for ($jj=$j;$jj<$loop_end;$jj++)  
                $data1[$jj]-=$robnum[$j];
        for ($i=0;$i<$loop_end;$i++) 
            $subsnum[$i]=$data1[$i]+$addnum;
    }

    $bars1="";
    $bars2="";
    for ($i=0;$i<$loop_end;$i++) {
       if ($i) {
          $bars1.=",";
          $bars2.=",";
       }
       $bars1.=intval($subsnum[$i]);
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
                          <td colspan='5' bgcolor='#cccc33'>
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
             //if ($mcount%2 == 0) $break = '<tr><td colspan="5"><h1 class="page_break"></h1></td></tr>';
             //else $break = '';
             $tpl->assign("MONTHSEP","<tr><td class='$voidbg_year'>
                          <img src='$_MX_var->application_instance/gfx/spacer.gif' width='1' height='1'></td>
                          <td colspan='4' bgcolor='#33cc33'>
                          <img src='$_MX_var->application_instance/gfx/spacer.gif' width='1' height='1'></td></tr>");
             $monthsep=4;
             $mcount++;
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
                          <td colspan='3' bgcolor='#3333cc'>
                          <img src='$_MX_var->application_instance/gfx/spacer.gif' width='1' height='1'></td></tr>");
             $daysep=3;
          }
          if ($prevday!=-1 && $day==1) {
             $tpl->assign("WEEKSEP","<tr><td class='$voidbg_year'>
                          <img src='$_MX_var->application_instance/gfx/spacer.gif' width='1' height='1'></td>
                          <td class='$voidbg_month'>
                          <img src='$_MX_var->application_instance/gfx/spacer.gif' width='1' height='1'></td>
                          <td colspan='3' bgcolor='#cc3333'>
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

       if ($subsnum[$i]<0)
           $subsnum[$i]=0;
       if ($stamp<=time())
           $tpl->assign("SUBSNUM",number_format($subsnum[$i]));
       else
           $tpl->assign("SUBSNUM","--");
       $subsnum_sum+=$subsnum[$i];
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
       if ($report!=3) 
        $tpl->parse("LIST_ROW",".list_row");
       $stamp+=$step;
    }
    if ($type!=1) {
        if ($type==5)
            $tpl->assign("SUBSNUM_SUM",number_format($distinct_endnum));
        else
            $tpl->assign("SUBSNUM_SUM",number_format($subsnum_sum));
        $tpl->assign("SUMTEXT","$word[total]");
    }
    else {
        $tpl->assign("SUBSNUM_SUM"," ");
        $tpl->assign("SUMTEXT"," ");
    }

    $rep_filename="user";
    
    /*$cDataXml = '<chart><series><value xid="100">1950</value></series><graphs><graph gid="1"><value xid="100" color="#318DBD">-0.307</value></graph><graph gid="2"><value xid="100">-0.171</value></graph></graphs></chart>';

    $tpl->assign("CHARTDATAXML",$cDataXml);*/

}
elseif ($report!=3) {
    $tpl->clear_dynamic("statmain");
}
include "report_foot.php";

function csv_no_comma($string) {
    //if (ereg("(\"|,)",$string)) {
        $string=str_replace("\"","\"\"",$string);
        $string="\"$string\"";
    //}
    return $string;
}



?>
