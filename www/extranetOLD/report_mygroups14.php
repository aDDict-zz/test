<?
include "auth.php";
$weare=18;
include "cookie_auth.php";  
include "common.php";
$language=select_lang();
include "./lang/$language/statistics.lang";

if (empty($maxonpage) && isset($DDonpage)) 
    $maxonpage=$DDonpage;
    
$maxonpage=intval($maxonpage);
if ($maxonpage<1 || $maxonpage>500)
    $maxonpage=50;

$group_id=intval($group_id);
$mres = mysql_query("select title,num_of_mess from groups,members where groups.id=members.group_id
                   and groups.id='$group_id' and user_id='$active_userid'
                   and (membership='owner' or membership='moderator' $admin_addq)");
if ($mres && mysql_num_rows($mres))
    $row=mysql_fetch_array($mres);  
else {
    header("Location: $_MX_var->baseUrl/index.php"); exit; }
$title=$row["title"];
$group_title=$row["title"];

$filt_demog=intval($filt_demog);
$r3=mysql_query("select * from filter where group_id='$group_id' and id='$filt_demog'");
if ($r3 && mysql_num_rows($r3)) {
    $k3=mysql_fetch_array($r3);
    $filt_demog_text="$word[filter]: $k3[name]";
}
else {
    $filt_demog_text="";
    $filt_demog=0;
}

$res=mysql_query("select count(*) from users_$title where validated='yes' and robinson='no' and bounced='no'");
if ($res && mysql_num_rows($res))
    $rmem=mysql_result($res,0,0);

$maxrecords=0;
if (!$filt_demog) {
    $filtadd=" and bounced='no' ";
    $stat_text="$word[total_of] $rmem $word[m_members]";
    $maxrecords=$rmem;
}
else {
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
    $qq="select count(*) from users_$title where validated='yes' and robinson='no' 
         and ($filter_qpart)";
    //echo nl2br(htmlspecialchars($qq))."--$limitord--$limitnum";
    $res=mysql_query($qq);
    if ($res && mysql_num_rows($res)) 
        $maxrecords=mysql_result($res,0,0);
    else
        $maxrecords=0;
    $filtadd="and ($filter_qpart)";
    if ($rmem) 
        $f_percent="(".number_format($maxrecords/$rmem*100,2)."%)";
    else
        $f_percent=" ";
    $stat_text="$word[total_of] $rmem $word[of_those] $maxrecords $f_percent $word[satisfies].";
    if ($filter_error!="filter_ok")
        $stat_text.=" $filter_error";    
}

empty($filt_demog_text)?$tfilt=$stat_text:$tfilt="$filt_demog_text - $stat_text";

$res3=mysql_query("select * from demog where id='$demog_id'");
if ($res3 && mysql_num_rows($res3)) {
   $m=mysql_fetch_array($res3);
   $question=$m["question"];
}
else
   exit;

if (!$tpl) {
    include "class.FastTemplate.php";
    $tpl=new FastTemplate("./templates");
    $tpl->no_strict();
}
$tpl->assign("freqnum",$maxonpage);
$tpl->assign("GTITLE",$title);
$tpl->assign("GROUP_ID",$group_id);
$tpl->assign("DEMOG_ID",$demog_id);
$tplist=array("html_report","group","group_lower","demog_stat","ds_period","ds_values","most_freq1","most_freq2");
$tpl->assign_same($tplist);
include "report_head.php";
$tpl->define(array( "statistics" => "report_mygroups14.tpl","statistics_head" => "report_mygroups14_head.tpl"));
$bgstyle1="bgwhite";
$bgstyle2="bggray";
$tpl->assign("QUESTION",$m["question"]);
$tpl->assign("TFILT",$tfilt);
$tpl->define_dynamic("d_subhead","statistics");
$tpl->define_dynamic("subqhead","statistics");
$tpl->define_dynamic("list","statistics");
$tpl->define_dynamic("rgraph","statistics");

if ($m["variable_type"]=="number" || $m["variable_type"]=="enum" || $m["variable_type"]=="date" || $m["variable_type"]=="matrix")
    $tpl->clear_dynamic("d_subhead");
if ($m["variable_type"]!="matrix")
    $tpl->clear_dynamic("subqhead");

$tablevar="ui_$m[variable_name]";
     
$tpl->parse("HMAIN","statistics_head");
$tpl->FastPrint("HMAIN");
if ($tablevar=="ui_szuletesnap") {
    $selfield="$tablevar";
    $month=date('m');
    $day=date('d');        
    $year=date('Y');
    $min=$year-8;
    $max=$year;
    $res3=mysql_query("select count(*) from users_$title where validated='yes' and robinson='no' 
                       and ui_szuletesnap>='$min-$month-$day' and ui_szuletesnap<'$max-$month-$day' $filtadd");  
    if ($res3 && mysql_num_rows($res3)) {
        $l=mysql_fetch_row($res3);
	    $graph_ordnum="1";
	    $graph_val="$l[0]";
        if ($maxrecords)
            $percent=" (".number_format($l[0]*100/$maxrecords,2)."%)";
        else
            $percent="";
        $tpl->assign("LNO","1");
        $tpl->assign("LVAL","0-8 $word[years_old]");
        $tpl->assign("LNUM","$l[0]$percent");
        $tpl->assign("TD_STYLE",$bgstyle2);            
        $tpl->parse("LIST",".list");
    }
    for ($bs=8;$bs<70;$bs+=3) {
        $up=$bs+3;
        $min=$year-$up;
        $max=$year-$bs;
        $res3=mysql_query("select count(*) from users_$title where validated='yes' and robinson='no' 
                           and ui_szuletesnap>='$min-$month-$day' and  ui_szuletesnap<'$max-$month-$day' $filtadd");  
        if ($res3 && mysql_num_rows($res3)) {
            $l=mysql_fetch_row($res3);
            $n_o=($bs-8)/3+2;
            $graph_ordnum.=",$n_o";
            $graph_val.=",$l[0]";
            if ($maxrecords)
                $percent=" (".number_format($l[0]*100/$maxrecords,2)."%)";
            else
                $percent="";
            $tpl->assign("LNO","$n_o");
            $tpl->assign("LVAL","$bs-$up $word[years_old]");
            $tpl->assign("LNUM","$l[0]$percent");
            if ($n_o%2)
                $tpl->assign("TD_STYLE",$bgstyle2);   
            else
                $tpl->assign("TD_STYLE",$bgstyle1);            
            $tpl->parse("LIST",".list");
        }
    }
    $max=$year-71;
    $min=$year-100;
    $res3=mysql_query("select count(*) from users_$title where validated='yes' and robinson='no' 
                       and ui_szuletesnap>='$min-$month-$day' and ui_szuletesnap<'$max-$month-$day' $filtadd");  
    if ($res3 && mysql_num_rows($res3)) {
        $l=mysql_fetch_row($res3);
	    $graph_ordnum.=",23";
	    $graph_val.=",$l[0]";
        if ($maxrecords)
            $percent=" (".number_format($l[0]*100/$maxrecords,2)."%)";
        else
            $percent="";
        $tpl->assign("LNO","23");
        $tpl->assign("LVAL","71-100 $word[years_old]");
        $tpl->assign("LNUM","$l[0]$percent");
        $tpl->assign("TD_STYLE",$bgstyle2);            
        $tpl->parse("LIST",".list");
    }
    mysql_query("insert into stat_cache (dateadd,ordnum,val) 
                 values (now(),'$graph_ordnum','$graph_val')");
    $cache_id=mysql_insert_id();
}
elseif ($m["variable_type"]=="date") {
     $selfield="$tablevar";
     $res=mysql_query("select count(*),std(to_days($tablevar)),avg(to_days($tablevar)) 
                       from users_$title where to_days($tablevar)>0 and validated='yes' 
                       and robinson='no'  $filtadd");
     $count=mysql_result($res,0,0);
     $std=mysql_result($res,0,1);
     $avg=mysql_result($res,0,2);
     if ($count) {
        $graph_ordnum="";
        $graph_val="";     
        $lower_std=intval($avg-$std*1.3);
        $upper_std=intval($avg+$std*1.3);
        $res=mysql_query("select min(to_days($tablevar)),max(to_days($tablevar)) from users_$title
	                      where to_days($tablevar)>='$lower_std' and to_days($tablevar)<='$upper_std'
                          and validated='yes' and robinson='no'  $filtadd");
        if ($res && mysql_num_rows($res)) {
           $lower_limit=mysql_result($res,0,0);
           $upper_limit=mysql_result($res,0,1);
        }
        $range=$upper_limit-$lower_limit+1;
        $bars=min(18,$range);
        $period=$range/$bars;
        $res3=mysql_query("select count(*),from_days('$lower_limit') from users_$title where 
                           to_days($tablevar)<'$lower_limit' and to_days($tablevar)>0 and validated='yes' 
                           and robinson='no'  $filtadd");  
        if ($res3 && mysql_num_rows($res3)) {
           $l=mysql_fetch_row($res3);
	    $graph_ordnum.="1";
	    $graph_val.="$l[0]";
        if ($maxrecords)
            $percent=" (".number_format($l[0]*100/$maxrecords,2)."%)";
        else
            $percent="";
            $tpl->assign("LNO","1");
            $tpl->assign("LVAL","< $l[1]");
            $tpl->assign("LNUM","$l[0]$percent");
            $tpl->assign("TD_STYLE",$bgstyle2);            
            $tpl->parse("LIST",".list");
        }
        $res3=mysql_query("select count(*),floor((to_days($tablevar)-$lower_limit)/$period) as period 
	                       from users_$title where to_days($tablevar)>='$lower_limit' 
                           and to_days($tablevar)<='$upper_limit' and validated='yes' 
                           and robinson='no'  $filtadd group by period");  
        if ($res3 && mysql_num_rows($res3)) {
           while ($l=mysql_fetch_row($res3)) {
	      $n_o=$l[1]+2;
	      $graph_ordnum.=",$n_o";
	      $graph_val.=",$l[0]";
            if ($maxrecords)
                $percent=" (".number_format($l[0]*100/$maxrecords,2)."%)";
            else
                $percent="";
          $per_l=intval($lower_limit+$l[1]*$period);
          $per_u=intval($lower_limit+($l[1]+1)*$period)-1;
          $r5=mysql_query("select from_days('$per_l'),from_days('$per_u')");
	      if ($r5 && mysql_num_rows($r5)) {
	         $per_l=mysql_result($r5,0,0);
	         $per_u=mysql_result($r5,0,1);
	      }
            if ($n_o%2)
                $tpl->assign("TD_STYLE",$bgstyle2);   
            else
                $tpl->assign("TD_STYLE",$bgstyle1);            
          $tpl->assign("LNO","$n_o");
          $tpl->assign("LVAL","$per_l - $per_u");
          $tpl->assign("LNUM","$l[0]$percent");
          $tpl->parse("LIST",".list");
           }
        }
        $res3=mysql_query("select count(*),from_days('$upper_limit') from users_$title where 
                           to_days($tablevar)>'$upper_limit' and validated='yes' and robinson='no'  $filtadd");  
        if ($res3 && mysql_num_rows($res3)) {
           $l=mysql_fetch_row($res3);
	   $n_o=$bars+2;
	   $graph_ordnum.=",$n_o";
	   $graph_val.=",$l[0]";
       if ($maxrecords)
           $percent=" (".number_format($l[0]*100/$maxrecords,2)."%)";
       else
           $percent="";
       $tpl->assign("LNO","$n_o");
       $tpl->assign("LVAL","> $l[1]");
       $tpl->assign("LNUM","$l[0]$percent");
        $tpl->assign("TD_STYLE",$bgstyle2);            
       $tpl->parse("LIST",".list");
        }
        mysql_query("insert into stat_cache (dateadd,ordnum,val) 
                     values (now(),'$graph_ordnum','$graph_val')");
        $cache_id=mysql_insert_id();	
     }
}
elseif ($m["variable_type"]=="number") {
     $selfield="$tablevar";
     $res=mysql_query("select count(*),std($tablevar),avg($tablevar) from users_$title
	                   where not isnull($tablevar) and validated='yes' 
                       and robinson='no'  $filtadd");
     $count=mysql_result($res,0,0);
     $std=mysql_result($res,0,1);
     $avg=mysql_result($res,0,2);
     if ($count) {
        $graph_ordnum="";
        $graph_val="";     
        $lower_std=$avg-$std*1.3;
        $upper_std=$avg+$std*1.3;
        $res=mysql_query("select min($tablevar),max($tablevar) from users_$title where 
                          $tablevar>='$lower_std' and $tablevar<='$upper_std' and validated='yes' 
                          and robinson='no'  $filtadd");
        if ($res && mysql_num_rows($res)) {
           $lower_limit=mysql_result($res,0,0);
           $upper_limit=mysql_result($res,0,1);
        }
        $range=$upper_limit-$lower_limit+1;
        $bars=min(18,intval($range));
        $period=$range/$bars;
        $res3=mysql_query("select count(*) from users_$title where $tablevar<'$lower_limit'
                           and not isnull($tablevar) and validated='yes' and robinson='no'  $filtadd");  
        if ($res3 && mysql_num_rows($res3)) {
            $l=mysql_fetch_row($res3);
            $graph_ordnum.="1";
            $graph_val.="$l[0]";
            if ($maxrecords)
                $percent=" (".number_format($l[0]*100/$maxrecords,2)."%)";
            else
                $percent="";
            $tpl->assign("LNO","1");
            $tpl->assign("LVAL","< $lower_limit");
            $tpl->assign("LNUM","$l[0]$percent");
            $tpl->assign("TD_STYLE",$bgstyle2);            
            $tpl->parse("LIST",".list");
        }
        $res3=mysql_query("select count(*),floor(($tablevar-$lower_limit)/$period) as period 
	                       from users_$title where $tablevar>='$lower_limit' 
                           and $tablevar<='$upper_limit' and validated='yes' and robinson='no'  
                           $filtadd group by period");  
        if ($res3 && mysql_num_rows($res3)) {
           while ($l=mysql_fetch_row($res3)) {
              $per_l=number_format($lower_limit+$l[1]*$period,2);
              $per_u=number_format($lower_limit+($l[1]+1)*$period,2);
              if ($l[1]==$bars-1)
                 $per_u-=1;
              $n_o=$l[1]+2;
              $graph_ordnum.=",$n_o";
              $graph_val.=",$l[0]";
              if ($maxrecords)
                  $percent=" (".number_format($l[0]*100/$maxrecords,2)."%)";
              else
                  $percent="";
              $tpl->assign("LNO","$n_o");
              $tpl->assign("LVAL","$per_l - $per_u");
              $tpl->assign("LNUM","$l[0]$percent");
            if ($n_o%2)
                $tpl->assign("TD_STYLE",$bgstyle2);   
            else
                $tpl->assign("TD_STYLE",$bgstyle1);            
              $tpl->parse("LIST",".list");
           }
        }
        $res3=mysql_query("select count(*) from users_$title where $tablevar>'$upper_limit'
                           and validated='yes' and robinson='no'  $filtadd");  
        if ($res3 && mysql_num_rows($res3)) {
           $l=mysql_fetch_row($res3);
           $n_o=$bars+2;
           $graph_ordnum.=",$n_o";
           $graph_val.=",$l[0]";
           if ($maxrecords)
               $percent=" (".number_format($l[0]*100/$maxrecords,2)."%)";
           else
               $percent="";
            $tpl->assign("TD_STYLE",$bgstyle2);            
           $tpl->assign("LNO","$n_o");
           $tpl->assign("LVAL","> $upper_limit");
           $tpl->assign("LNUM","$l[0]$percent");
           $tpl->parse("LIST",".list");
        }
        mysql_query("insert into stat_cache (dateadd,ordnum,val) 
                     values (now(),'$graph_ordnum','$graph_val')");
        $cache_id=mysql_insert_id();	
     }
}
elseif ($m["variable_type"]=="enum") {
     $selfield="enum_value";
     $enumvals=array();
     $res=mysql_query("select count(*) from users_$title where validated='yes' and 
                       length($tablevar)>2 and not isnull($tablevar) and validated='yes' and robinson='no'  $filtadd");
     $count=mysql_result($res,0,0);
     if ($count) {
        $graph_ordnum="";
        $graph_val="";     
        $ii=1;
        $res3=mysql_query("select id,enum_option from demog_enumvals 
                           where deleted='no' and demog_id='$demog_id'");
        if ($res3 && mysql_num_rows($res3)) {
           while ($l=mysql_fetch_row($res3)) {
              if ($m["multiselect"]=="yes")
                  $qpart="$tablevar like '%,$l[0],%'";
              else
                  $qpart="$tablevar = ',$l[0],'";
              //echo "select count(*) from users_$title where $qpart and validated='yes' and robinson='no'";
              $r31=mysql_query("select count(*) from users_$title where $qpart and validated='yes' 
                                and robinson='no'  $filtadd");
              if ($r31 && mysql_num_rows($r31))
                 $ecount=mysql_result($r31,0,0);
              else
                 $ecount=0;
            if ($maxrecords)
                $percent=" (".number_format($ecount*100/$maxrecords,2)."%)";
            else
                $percent="";
              $per=report_specialchars($l[1]);
              //$enumvals["$l[2]"]=$l[1];
              if ($ii>1) {
                 $graph_ordnum.=",";
                 $graph_val.=",";
              }
              $graph_ordnum.="$ii";
              $graph_val.="$ecount";
            if ($ii%2)
                $tpl->assign("TD_STYLE",$bgstyle2);   
            else
                $tpl->assign("TD_STYLE",$bgstyle1);            
              $tpl->assign("LNO","$ii");
              $tpl->assign("LVAL","$per");
              $tpl->assign("LNUM","$ecount$percent");
              $tpl->parse("LIST",".list");
	          $ii++;
           }
        }
        else
            $tpl->clear_dynamic("list");
        mysql_query("insert into stat_cache (dateadd,ordnum,val) 
                     values (now(),'$graph_ordnum','$graph_val')");
        $cache_id=mysql_insert_id();	
     }
}
elseif ($m["variable_type"]=="matrix") {
    $subq=array();
    $opts=array();
    $optvals=array();
    $res3=mysql_query("select id,enum_option,vertical from demog_enumvals where deleted='no' and demog_id='$demog_id' order by enum_option");
    if ($res3 && mysql_num_rows($res3)) {
       while ($l=mysql_fetch_array($res3)) {
           if ($l["vertical"]=="yes") {
               $subq["$l[id]"]=array();
           }
           else {
               $opts[]=$l["id"];
           }
           $optvals["$l[id]"]=$l["enum_option"];
       }
    }
    foreach ($subq as $sq=>$sqa) {
        foreach ($opts as $op) {
            $subq["$sq"]["$op"]=0;
        }
    }
    $res3=mysql_query("select $tablevar from users_$title where validated='yes' and robinson='no' and length($tablevar)>1 
                       and not isnull($tablevar) $filtadd");
    if ($res3 && $count=mysql_num_rows($res3)) {
       while ($l=mysql_fetch_row($res3)) {
           $mop=explode(",",$l[0]);
           foreach ($mop as $mopp) {
               if (ereg("^([0-9]+)m([0-9]+)$",$mopp,$mrg)) {
                    $sq=$mrg[1];
                    $op=$mrg[2];
                    if (isset($subq["$sq"]) && in_array($op,$opts)) {
                        $subq["$sq"]["$op"]++;
                    }
               }
           }
       }
    }
    foreach ($subq as $subq_id=>$subq_vals) {
       $graph_ordnum="";
       $graph_val="";     
       $ii=1;
       foreach ($subq_vals as $opt_id=>$ecount) {
          if ($maxrecords)
              $percent="&nbsp;(".number_format($ecount*100/$maxrecords,2)."%)";
          else
              $percent="";
          if ($notnullcount)
              $nnpercent="&nbsp;(".number_format($ecount*100/$notnullcount,2)."%)";
          else
              $nnpercent="";
          $per=htmlspecialchars($optvals["$opt_id"]);
          //$enumvals["$l[2]"]=$l[1];
          if ($ii>1) {
             $graph_ordnum.=",";
             $graph_val.=",";
          }
          $graph_ordnum.="$ii";
          $graph_val.="$ecount";
          if ($ii%2)
              $tpl->assign("TD_STYLE",$bgstyle2);   
          else
              $tpl->assign("TD_STYLE",$bgstyle1);            
          $tpl->assign("SUBQ",htmlspecialchars($optvals["$subq_id"]));
          $tpl->assign("LNO","$ii");
          $tpl->assign("LVAL","$per");
          $tpl->assign("LNUM","$ecount$percent");
          $tpl->parse("LIST",".list");
          $ii++;
       }   
       mysql_query("insert into stat_cache (dateadd,ordnum,val) values (now(),'$graph_ordnum','$graph_val')");
       $cache_id=mysql_insert_id();	
       $tpl->assign("CACHE_ID",$cache_id);
       $tpl->parse("MAIN","statistics");
       $tpl->FastPrint("MAIN");
       $tpl->clear_parse2("LIST");
    }  
}
else {
     $selfield="other_value";
     $res=mysql_query("select count(*) from users_$title where 
                       validated='yes' and not isnull($tablevar) and length($tablevar)>0 $filtadd");
     $count=mysql_result($res,0,0);
     $tpl->parse("D_SUBHEAD","d_subhead");
     if ($count) {
        $ii=1;
        $res3=mysql_query("select count(*) as cnt,$tablevar from users_$title where 
	                       validated='yes' and not isnull($tablevar) and length($tablevar)>0 $filtadd
	                       group by $tablevar having cnt>1 order by cnt desc limit $maxonpage");  
        if ($res3 && mysql_num_rows($res3)) {
           while ($l=mysql_fetch_row($res3)) {
	      $per=report_specialchars($l[1]);
            if ($maxrecords)
                $percent=" (".number_format($l[0]*100/$maxrecords,2)."%)";
            else
                $percent="";
            if ($ii%2)
                $tpl->assign("TD_STYLE",$bgstyle2);   
            else
                $tpl->assign("TD_STYLE",$bgstyle1);            
            $tpl->assign("LNO","$ii");
            $tpl->assign("LVAL","$per");
            $tpl->assign("LNUM","$l[0]$percent");
            $tpl->parse("LIST",".list");
	        $ii++;
           }
        }
        else
            $tpl->clear_dynamic("list");
     }
}

if ($m["variable_type"]=="number" || $m["variable_type"]=="enum" || $m["variable_type"]=="date") {
    $tpl->assign("CACHE_ID",$cache_id);
    $tpl->parse("RGRAPH","rgraph");
}
else {
    $tpl->clear_dynamic("rgraph");
}

$rep_filename="demog";

if ($m["variable_type"]!="matrix") {
    $tpl->parse("MAIN",".statistics");
    $tpl->FastPrint("MAIN");
}
include "footer.php";

##################################################################

function report_specialchars($str) {

    global $_MX_var,$report;
    /*if ($report==2) {
        $str=ereg_replace("{","(",$str);
        $str=ereg_replace("}",")",$str);
        return addslashes($str);
    }
    else*/
        return htmlspecialchars($str);
}

?>
