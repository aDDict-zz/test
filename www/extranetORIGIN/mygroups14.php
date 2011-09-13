<?
include "auth.php";
$weare=18;
include "cookie_auth.php"; 
include "common.php";
  
if (empty($maxonpage) && isset($DDonpage)) 
    $maxonpage=$DDonpage;
    
$maxonpage=intval($maxonpage);
if ($maxonpage<1 || $maxonpage>500)
    $maxonpage=50;

setcookie("DDonpage",$maxonpage,time()+30*24*3600);

$group_id=intval(get_http("group_id",0));
$mres = mysql_query("select title,num_of_mess from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and user_id='$active_userid'
                     and (membership='owner' or membership='moderator' $admin_addq)");
logger($q,$group_id,"","demographic_statisztika","groups,members");
if ($mres && mysql_num_rows($mres))
    $row=mysql_fetch_array($mres);  
else {
    header("Location: $_MX_var->baseUrl/index.php?no_group=1"); exit; }
$title=$row["title"];
  
include "menugen.php";
include "./lang/$language/statistics.lang";

$filt_demog=get_http("filt_demog",0);
$demog_id=get_http("demog_id",0);
if (get_http("filt_clear",""))
    $filt_demog=0;

$filt_demog_options="";
$r3=mysql_query("select * from filter where group_id='$group_id' and archived='no' order by name");
if ($r3 && mysql_num_rows($r3))
    while ($k3=mysql_fetch_array($r3)) {
        if ($k3["id"]==$filt_demog) {
            $selected="selected";
            $found_filter=1;
        }
        else
            $selected="";
        $filt_demog_options.="<option $selected value='$k3[id]'>$k3[name]</option>";
    }
if (!$found_filter)
    $filt_demog=0;

$r2 = mysql_query("select count(*) from users_$title where validated='yes' and robinson='no' and bounced='no'");
if ($r2 && mysql_num_rows($r2))
    $rmem=mysql_result($r2,0,0);

$limiterr="";
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
    if (!empty($limitord))
        $limiterr="<br>[$word[demog_max_alert]]";
    if ($rmem) 
        $f_percent="(".number_format($maxrecords/$rmem*100,2)."%)";
    else
        $f_percent="&nbsp;";
    $stat_text="$word[total_of] $rmem $word[of_those] $maxrecords $f_percent $word[satisfies].";
    if ($filter_error!="filter_ok")
        $stat_text.="<br>$filter_error";    
}

$query="select distinct demog.* from vip_demog,demog where vip_demog.demog_id=demog.id
        and vip_demog.group_id='$group_id' order by question";
$mres=mysql_query($query);

$res = mysql_query("select name from groups where id = $group_id");
if ($res && mysql_num_rows($res)) {
    $active_title=mysql_result($res,0,0);
}
echo "<div class='activetitle'>$active_title</div>";

echo "<TABLE cellSpacing=0 cellPadding=0 width='100%' border=0 class='bgcolor'>
  <TR>
    <TD width='100%'>
      <TABLE cellSpacing=1 cellPadding=1 width='100%' border=0 class='noprint'>
";
        echo "<form>
            <input type='hidden' name='group_id' value='$group_id'>
            <input type='hidden' name='demog_id' value='$demog_id'>";

if ($mres && mysql_num_rows($mres)) {
    if (!empty($filt_demog_options))
    print "
            <tr>
		    <td align=left colspan='2' class='formmezo'>
            $word[filter]:&nbsp;<select name='filt_demog' onChange='JavaScript: this.form.submit();'>
	        <option value='0'>$word[select]</option>
            $filt_demog_options
            </select>&nbsp;
            <input type='submit' name='filt_clear' value='$word[clear_filter]'>
            </td>
		    </tr>";

$demog_id_options='';
while ($k=mysql_fetch_array($mres))
{
    $question=htmlspecialchars($k["question"]);
    if ($demog_id == $k['id'])
    {
        $demog_id_options .= "<option selected value='$k[id]'>$question</option>";
        $variable_type= $k['variable_type'];
        $kquestion=$question;
        $variable_name = $k['variable_name'];

    }
    else
    {
        $demog_id_options .= "<option value='$k[id]'>$question</option>";
    }
}


   echo "<tr>
		 <td align=left colspan='2' class='formmezo'>$stat_text$limiterr</td>
		 </tr>   
         <tr>
         <td valign='top' colspan='2' class='formmezo'>$word[vd_question]   
            <select name='demog_id' style='max-width:500px;' onChange='JavaScript: this.form.submit();'>
	            <option value='0'>$word[select]</option>
                $demog_id_options
            </select>&nbsp;
";

print "
         </td>
         </tr>
         </form>\n";

if (!$demog_id)
{
print "
     </table>
     </td>
     </tr>
     </table>
";

    include "footer.php";
    exit;
}


       if ($variable_type=="enum" || $variable_type=="matrix")
           $nnenumadd="and length(ui_$variable_name)>1";
       else
           $nnenumadd="";
       $res=mysql_query("select count(*) from users_$title where length(ui_$variable_name)
                         and validated='yes' and robinson='no'  $nnenumadd $filtadd");
       if ($res && mysql_num_rows($res)) {
        $notnullcount=mysql_result($res,0,0);
       }
       $titleq=$kquestion;
       if ($maxrecords)
           $nnpercent="&nbsp;(".number_format($notnullcount*100/$maxrecords,2)."%)";
       else
           $nnpercent="";


   echo "
     </table>
     </td>
     </tr>
     </table>";
     
     //echo $variable_type;
if ($demog_id)
{
    print "<a href='javascript:void(0);' id='print_page' style='margin:3px;' class='fr'>$word[html_report]</a>";
}     

   echo "
     <br>
     <TABLE cellSpacing=0 cellPadding=0 width='100%' border=0 class='bgcolor'>
        <TR>
            <TD width='100%'>
                <TABLE cellSpacing=1 cellPadding=1 width='100%' border=0>
                    <tr>
                        <td valign='top' colspan='4' class='formmezo tal'><center>$titleq<br/>$notnullcount/$maxrecords$nnpercent $word[notnulluser]</center></td>
                    </tr>\n";   
   printstats($demog_id);
}
else 
  echo "
<tr>
<td class=BACKCOLOR valign='top'><span class='szoveg'>$word[no_demog_question]</span></td>
</tr>
</table>
</td>
</tr>
</table>
";
  
include "footer.php";
######################### functions #############################

function printstats($demog_id) {
  global $_MX_var,$group_id,$title, $filtadd, $maxonpage, $filt_demog, $notnullcount;
  global $_MX_var,$word, $root, $sortd, $maxPerPage, $maxrecords, $first, $maxpages, $pagenum;
  
  $res3=mysql_query("select * from demog where id='$demog_id'");
  if ($res3 && mysql_num_rows($res3)) 
     $m=mysql_fetch_array($res3);
  else
     return;

  $tablevar="ui_$m[variable_name]";
    //echo $m['variable_type']."-".$m['multiselect'];
  if ($tablevar=="ui_szuletesnap") {
    $selfield="$tablevar";
    $month=date('m');
    $day=date('d');        
    $year=date('Y');
    $min=$year-8;
    $max=$year;
    $res3=mysql_query("select count(*) from users_$title where validated='yes' and robinson='no' 
                       and ui_szuletesnap>='$min-$month-$day' and ui_szuletesnap<'$max-$month-$day' $filtadd");  
    $stdout="";
    $chartData = array();
    if ($res3 && mysql_num_rows($res3)) {
        $l=mysql_fetch_row($res3);
	    $graph_ordnum="1";
	    $graph_val="$l[0]";
        if ($maxrecords)
            $percent="&nbsp;(".number_format($l[0]*100/$maxrecords,2)."%)";
        else
            $percent="";
          if ($notnullcount)
              $nnpercent="&nbsp;(".number_format($l[0]*100/$notnullcount,2)."%)";
          else
              $nnpercent="";
        $stdout .= "<tr class='oddbgcolor'>
             <td valign='top' width=25%><span class='szoveg'>1.</span></td>	   
             <td valign='top' width=25%><span class='szoveg'>0-8 $word[years_old]</span></td>  
             <td valign='top' width=25%><span class='szoveg'>$l[0] / $maxrecords <b>$percent</b></span></td>
             <td valign='top' width=25%><span class='szoveg'>[ $l[0] / $notnullcount <b>$nnpercent</b> ]</span></td>	   
             </tr>\n";
        $chartData[] = array($l[0], "0-8 ". $word['years_old']);

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
                $percent="&nbsp;(".number_format($l[0]*100/$maxrecords,2)."%)";
            else
                $percent="";
              if ($notnullcount)
                  $nnpercent="&nbsp;(".number_format($l[0]*100/$notnullcount,2)."%)";
              else
                  $nnpercent="";
              if ($n_o%2 == 1) $bgcolor = 'oddbgcolor';
              else $bgcolor = 'evenbgcolor';
            $stdout .= "<tr class='$bgcolor'>
                 <td valign='top' width=25%><span class='szoveg'>$n_o.</span></td>	   
                 <td valign='top' width=25%><span class='szoveg'>$bs-$up $word[years_old]</span></td>  
                 <td valign='top' width=25%><span class='szoveg'>$l[0] / $maxrecords <b>$percent</b></span></td>
                 <td valign='top' width=25%><span class='szoveg'>[ $l[0] / $notnullcount <b>$nnpercent</b> ]</span></td>	   
                 </tr>\n";
                 $chartData[] = array($l[0], $bs."-".$up ." ". $word['years_old']);
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
            $percent="&nbsp;(".number_format($l[0]*100/$maxrecords,2)."%)";
        else
            $percent="";
          if ($notnullcount)
              $nnpercent="&nbsp;(".number_format($l[0]*100/$notnullcount,2)."%)";
          else
              $nnpercent="";
        $stdout .= "<tr class='oddbgcolor'>
             <td valign='top' width=25%><span class='szoveg'>23.</span></td>	   
             <td valign='top' width=25%><span class='szoveg'>71-100 $word[years_old]</span></td>  
             <td valign='top' width=25%><span class='szoveg'>$l[0] / $maxrecords <b>$percent</b></span></td>
             <td valign='top' width=25%><span class='szoveg'>[ $l[0] / $notnullcount <b>$nnpercent</b> ]</span></td>	   
             </tr>\n";
             $chartData[] = array($l[0], "71-100 ". $word['years_old']);
    }


                        echo '<tr><td colspan="4" class=BACKCOLOR>';
                        //var_dump($chartData);

                        echo '
                        <script type="text/javascript" src="'.$_MX_var->baseUrl.'/amcharts/ampie/swfobject.js"></script>
                        <div id="flashcontent" align="center">
            		        <strong>You need to upgrade your Flash Player</strong>
                	    </div>
                        <script type="text/javascript">
            		    // <![CDATA[		
            		        var so = new SWFObject("'.$_MX_var->baseUrl.'/amcharts/ampie/ampie.swf", "ampie", "920", "400", "8", "#FFFFFF");
                            so.addVariable("path", "");
                            so.addParam("wmode", "transparent");
                            so.addVariable("settings_file", encodeURIComponent("'.$_MX_var->baseUrl.'/amcharts/maxima/settings-pie.php?language='.$language.'&demog=true"));
                            so.addVariable("chart_data", "<pie>';
                            
                                foreach ($chartData as $item) {
                                    echo '<slice title=\''.$item[1].'\' pull_out=\'false\'>'.$item[0].'</slice>';
                                }
                            
                            echo '</pie>");
                            
                            so.write("flashcontent");
            		    // ]]>
                        </script>
                        <script type="text/javascript" src="'.$_MX_var->baseUrl.'/amcharts/amcolumn/swfobject.js"></script>
                        <div id="flashcontent2" align="center">
            		        <strong>You need to upgrade your Flash Player</strong>
                	    </div>
                	    <script type="text/javascript">
                            // <![CDATA[		
                            var so2 = new SWFObject("'.$_MX_var->baseUrl.'/amcharts/amcolumn/amcolumn.swf", "amcolumn", "920", "400", "8", "#FFFFFF");
                            so.addParam("wmode", "transparent");
                            so2.addVariable("path", "'.$_MX_var->baseUrl.'/amcharts/amcolumn/");
                            so2.addVariable("settings_file",  encodeURIComponent("'.$_MX_var->baseUrl.'/amcharts/maxima/settings.php?language='.$language.'&type=demogdate"));
                            so2.addVariable("chart_data", "<chart><series>';
                            
                            foreach ($chartData as $key=>$item) {
                                echo '<value xid=\''.$key.'\'>'.$item[1].'</value>';
                            }

                            echo '</series><graphs><graph gid=\'1\'>';
                            
                            foreach ($chartData as $key=>$item) {
                                echo '<value xid=\''.$key.'\'>'.$item[0].'</value>';
                            }
                                                        
                            echo '</graph></graphs></chart>");
                            
                            so2.write("flashcontent2");
                            // ]]>
                	    </script>';

                        echo "</td></tr>

                    <tr>
                        <td valign='top' width='25%' class='formmezo tac'>&nbsp;</td>
                        <td valign='top' width='25%' class='formmezo'>$word[ds_period]</td>
                        <td valign='top' width='50%' colspan='2' class='formmezo tal'>$word[ds_values]</td>
                    </tr>";                      

        echo $stdout;
/*

    mysql_query("insert into stat_cache (dateadd,ordnum,val) 
                 values (now(),'$graph_ordnum','$graph_val')");
    $cache_id=mysql_insert_id();	
    echo "<tr>
          <td class=BACKCOLOR colspan='3' align='center' valign='center'>
          <img src='grafnew/stat_demog.php?cache_id=$cache_id&demog_id=$m[id]&group_id=$group_id' align='center' valign='center'>
      </td>
      </tr>
      ";*/
   }
  elseif ($m["variable_type"]=="date") {
     $selfield="$tablevar";
     $res=mysql_query("select count(*),std(to_days($tablevar)),avg(to_days($tablevar)) 
                       from users_$title where to_days($tablevar)>0 and validated='yes' and robinson='no'  $filtadd");
     $count=mysql_result($res,0,0);
     $std=mysql_result($res,0,1);
     $avg=mysql_result($res,0,2);
     $stdout="";
     $chartData = array();

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
                           to_days($tablevar)<'$lower_limit' and to_days($tablevar)>0 
                           and validated='yes' and robinson='no'  $filtadd");  
        if ($res3 && mysql_num_rows($res3)) {
           $l=mysql_fetch_row($res3);
	    $graph_ordnum.="1";
	    $graph_val.="$l[0]";
        if ($maxrecords)
            $percent="&nbsp;(".number_format($l[0]*100/$maxrecords,2)."%)";
        else
            $percent="";
           $stdout .= "<tr>
                 <td valign='top' width=25%><span class='szoveg'>1.</span></td>	   
                 <td valign='top' width=25%><span class='szoveg'>&lt;&nbsp;$l[1]</span></td>  
                 <td valign='top' width=25%><span class='szoveg'>$l[0] / $maxrecords <b>$percent</b></span></td>
                 <td valign='top' width=25%><span class='szoveg'>[ $l[0] / $notnullcount <b>$nnpercent</b> ]</span></td>	   
		 </tr>
		";
        $chartData[] = array($l[0], $l[1]);

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
                $percent="&nbsp;(".number_format($l[0]*100/$maxrecords,2)."%)";
            else
                $percent="";
              if ($notnullcount)
                  $nnpercent="&nbsp;(".number_format($l[0]*100/$notnullcount,2)."%)";
              else
                  $nnpercent="";
          $per_l=intval($lower_limit+$l[1]*$period);
          $per_u=intval($lower_limit+($l[1]+1)*$period)-1;
          $r5=mysql_query("select from_days('$per_l'),from_days('$per_u')");
	      if ($r5 && mysql_num_rows($r5)) {
	         $per_l=mysql_result($r5,0,0);
	         $per_u=mysql_result($r5,0,1);
	      }
              if ($n_o%2 == 1) $bgcolor = 'oddbgcolor';
              else $bgcolor = 'evenbgcolor';          
              $stdout .= "<tr class='$bgcolor'>
                 <td valign='top' width=25%><span class='szoveg'>$n_o.</span></td>	   
                 <td valign='top' width=25%><span class='szoveg'>$per_l - $per_u</span></td>
                 <td valign='top' width=25%><span class='szoveg'>$l[0] / $maxrecords <b>$percent</b></span></td>
                 <td valign='top' width=25%><span class='szoveg'>[ $l[0] / $notnullcount <b>$nnpercent</b> ]</span></td>	   
		 </tr>
	      ";
          $chartData[] = array($l[0], $per_l." - ".$per_u);

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
           $percent="&nbsp;(".number_format($l[0]*100/$maxrecords,2)."%)";
       else
           $percent="";
          if ($notnullcount)
              $nnpercent="&nbsp;(".number_format($l[0]*100/$notnullcount,2)."%)";
          else
              $nnpercent="";
              if ($n_o%2 == 1) $bgcolor = 'oddbgcolor';
              else $bgcolor = 'evenbgcolor';               
           $stdout .= "<tr class='$bgcolor'>
                 <td valign='top' width=25%><span class='szoveg'>$n_o.</span></td>	   
                 <td valign='top' width=25%><span class='szoveg'>&gt;&nbsp;$l[1]</span></td>
                 <td valign='top' width=25%><span class='szoveg'>$l[0] / $maxrecords <b>$percent</b></span></td>
                 <td valign='top' width=25%><span class='szoveg'>[ $l[0] / $notnullcount <b>$nnpercent</b> ]</span></td>	   
		 </tr>
		";
        $chartData[] = array($l[0], $l[1]);
        }


                        echo '<tr><td colspan="4" class=BACKCOLOR>';
                        //var_dump($chartData);

                        echo '
                        <script type="text/javascript" src="'.$_MX_var->baseUrl.'/amcharts/ampie/swfobject.js"></script>
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
                        </script>
                        <script type="text/javascript" src="'.$_MX_var->baseUrl.'/amcharts/amcolumn/swfobject.js"></script>
                        <div id="flashcontent2" align="center">
            		        <strong>You need to upgrade your Flash Player</strong>
                	    </div>
                	    <script type="text/javascript">
                            // <![CDATA[		
                            var so2 = new SWFObject("'.$_MX_var->baseUrl.'/amcharts/amcolumn/amcolumn.swf", "amcolumn", "920", "400", "8", "#FFFFFF");
                            so.addParam("wmode", "transparent");
                            so2.addVariable("path", "'.$_MX_var->baseUrl.'/amcharts/amcolumn/");
                            so2.addVariable("settings_file",  encodeURIComponent("'.$_MX_var->baseUrl.'/amcharts/maxima/settings.php?language='.$language.'&type=demogdate"));
                            so2.addVariable("chart_data", "<chart><series>';
                            
                            foreach ($chartData as $key=>$item) {
                                echo '<value xid=\''.$key.'\'>'.$item[1].'</value>';
                            }

                            echo '</series><graphs><graph gid=\'1\'>';
                            
                            foreach ($chartData as $key=>$item) {
                                echo '<value xid=\''.$key.'\'>'.$item[0].'</value>';
                            }
                                                        
                            echo '</graph></graphs></chart>");
                            
                            so2.write("flashcontent2");
                            // ]]>
                	    </script>';

                        echo "</td></tr>
                            <tr>
                                <td valign='top' width='25%' class='formmezo tac'>&nbsp;</td>
                                <td valign='top' width='25%' class='formmezo'>$word[ds_period]</td>
                                <td valign='top' width='50%' colspan='2' class='formmezo tal'>$word[ds_values]</td>
                            </tr>";

        echo $stdout;



        /*mysql_query("insert into stat_cache (dateadd,ordnum,val) 
                     values (now(),'$graph_ordnum','$graph_val')");
        $cache_id=mysql_insert_id();	
        echo "<tr>
              <td class=BACKCOLOR colspan='3' align='center' valign='center'>
              <img src='grafnew/stat_demog.php?cache_id=$cache_id&demog_id=$m[id]&group_id=$group_id' align='center' valign='center'>
	      </td>
	      </tr>
	      ";*/
     }
   }
  elseif ($m["variable_type"]=="number") {
     $selfield="$tablevar";
     $res=mysql_query("select count(*),std($tablevar),avg($tablevar) from users_$title
	                   where not isnull($tablevar) and validated='yes' and robinson='no'  $filtadd");
     $count=mysql_result($res,0,0);
     $std=mysql_result($res,0,1);
     $avg=mysql_result($res,0,2);
     $stdout="";
     $chartData = array();

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
            $percent="&nbsp;(".number_format($l[0]*100/$maxrecords,2)."%)";
        else
            $percent="";
           $stdout .= "<tr>
                 <td valign='top' width=25%><span class='szoveg'>1.</span></td>	   
                 <td valign='top' width=25%><span class='szoveg'>&lt;&nbsp;$lower_limit</span></td>  
                 <td valign='top' width=25%><span class='szoveg'>$l[0] / $maxrecords <b>$percent</b></span></td>
                 <td valign='top' width=25%><span class='szoveg'>[ $l[0] / $notnullcount <b>$nnpercent</b> ]</span></td>	   
		 </tr> 
		";
        $chartData[] = array($l[0], $lower_limit);

        }
        $res3=mysql_query("select count(*),floor(($tablevar-$lower_limit)/$period) as period 
	                       from users_$title where $tablevar>='$lower_limit' 
                           and $tablevar<='$upper_limit' and validated='yes' and robinson='no'  $filtadd group by period");  
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
                $percent="&nbsp;(".number_format($l[0]*100/$maxrecords,2)."%)";
            else
                $percent="";
              if ($notnullcount)
                  $nnpercent="&nbsp;(".number_format($l[0]*100/$notnullcount,2)."%)";
              else
                  $nnpercent="";
              if ($n_o%2 == 1) $bgcolor = 'oddbgcolor';
              else $bgcolor = 'evenbgcolor';                  
              $stdout .= "<tr class='$bgcolor'>
                 <td valign='top' width=25%><span class='szoveg'>$n_o.</span></td>	   
                 <td valign='top' width=25%><span class='szoveg'>$per_l - $per_u</span></td>
                 <td valign='top' width=25%><span class='szoveg'>$l[0] / $maxrecords <b>$percent</b></span></td>
                 <td valign='top' width=25%><span class='szoveg'>[ $l[0] / $notnullcount <b>$nnpercent</b> ]</span></td>	   
		 </tr>
	      ";
          $chartData[] = array($l[0], $per_l." - ".$per_u);

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
            $percent="&nbsp;(".number_format($l[0]*100/$maxrecords,2)."%)";
        else
            $percent="";
          if ($notnullcount)
              $nnpercent="&nbsp;(".number_format($l[0]*100/$notnullcount,2)."%)";
          else
              $nnpercent="";
              if ($n_o%2 == 1) $bgcolor = 'oddbgcolor';
              else $bgcolor = 'evenbgcolor';              
           $stdout .= "<tr>
                 <td valign='top' width=25%><span class='szoveg'>$n_o.</span></td>	   
                 <td valign='top' width=25%><span class='szoveg'>&gt;&nbsp;$upper_limit</span></td>
                 <td valign='top' width=25%><span class='szoveg'>$l[0] / $maxrecords <b>$percent</b></span></td>
                 <td valign='top' width=25%><span class='szoveg'>[ $l[0] / $notnullcount <b>$nnpercent</b> ]</span></td>	   
		 </tr>
		";
        $chartData[] = array($l[0], $upper_limit);
        }
        
        
                        echo '<tr><td colspan="4" class=BACKCOLOR>';
                        //var_dump($chartData);

                        echo '
                        <script type="text/javascript" src="'.$_MX_var->baseUrl.'/amcharts/ampie/swfobject.js"></script>
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
                        </script>
                        <script type="text/javascript" src="'.$_MX_var->baseUrl.'/amcharts/amcolumn/swfobject.js"></script>
                        <div id="flashcontent2" align="center">
            		        <strong>You need to upgrade your Flash Player</strong>
                	    </div>
                	    <script type="text/javascript">
                            // <![CDATA[		
                            var so2 = new SWFObject("'.$_MX_var->baseUrl.'/amcharts/amcolumn/amcolumn.swf", "amcolumn", "920", "400", "8", "#FFFFFF");
                            so.addParam("wmode", "transparent");
                            so2.addVariable("path", "'.$_MX_var->baseUrl.'/amcharts/amcolumn/");
                            so2.addVariable("settings_file",  encodeURIComponent("'.$_MX_var->baseUrl.'/amcharts/maxima/settings.php?language='.$language.'&type=demogdate"));
                            so2.addVariable("chart_data", "<chart><series>';
                            
                            foreach ($chartData as $key=>$item) {
                                echo '<value xid=\''.$key.'\'>'.$item[1].'</value>';
                            }

                            echo '</series><graphs><graph gid=\'1\'>';
                            
                            foreach ($chartData as $key=>$item) {
                                echo '<value xid=\''.$key.'\'>'.$item[0].'</value>';
                            }
                                                        
                            echo '</graph></graphs></chart>");
                            
                            so2.write("flashcontent2");
                            // ]]>
                	    </script>';

                        echo "</td></tr>
                            <tr>
                                <td valign='top' width='25%' class='formmezo tac'>&nbsp;</td>
                                <td valign='top' width='25%' class='formmezo'>$word[ds_period]</td>
                                <td valign='top' width='50%' colspan='2' class='formmezo tal'>$word[ds_values]</td>
                            </tr>";

        echo $stdout;

        
        /*mysql_query("insert into stat_cache (dateadd,ordnum,val) 
                     values (now(),'$graph_ordnum','$graph_val')");
        $cache_id=mysql_insert_id();	
        echo "<tr>
              <td class=BACKCOLOR colspan='3' align='center' valign='center'>
              <img src='grafnew/stat_demog.php?cache_id=$cache_id&demog_id=$m[id]&group_id=$group_id' align='center' valign='center'>
	      </td>
	      </tr>
	      ";*/
     }
   }
   elseif ($m["variable_type"]=="enum") {

     $selfield="enum_value";
     $enumvals=array();
     $res=mysql_query("select count(*) from users_$title where validated='yes' and 
                       length($tablevar)>1 and not isnull($tablevar) and validated='yes' and robinson='no'  $filtadd");
     if ($res && mysql_num_rows($res)) {
        $count=mysql_result($res,0,0);
     }
     if ($count) {
        $graph_ordnum="";
        $graph_val="";     
        $ii=1;
        if ($m["multiselect"]=="no") {
            $u_data=array();
            $r4=mysql_query("select $tablevar as col_name,count(*) as num from users_$title where validated='yes' and robinson='no' $filtadd group by $tablevar");
            while ($ll=mysql_fetch_row($r4)) {
                $u_data[substr($ll[0],1,strlen($ll[0])-2)]=$ll[1];
            }
        }
        $res3=mysql_query("select id,enum_option from demog_enumvals where deleted='no' and demog_id='$demog_id'");
        $resChart=mysql_query("select id,enum_option from demog_enumvals where deleted='no' and demog_id='$demog_id'");

        if ($res3 && mysql_num_rows($res3)) {
            
            $chartData = array();
            $stdout = "";
            while ($l=mysql_fetch_row($res3)) {
              $ecount=0;
              if ($m["multiselect"]=="yes") {
                  $qpart="$tablevar like '%,$l[0],%'";
                  $r31=mysql_query("select count(*) from users_$title where $qpart and validated='yes' and robinson='no'
                      $filtadd");
                  if ($r31 && mysql_num_rows($r31))
                     $ecount=mysql_result($r31,0,0);
              } else {
                  if (isset($u_data[$l[0]])) $ecount=$u_data[$l[0]];
              }
              if ($maxrecords)
                  $percent="&nbsp;(".number_format($ecount*100/$maxrecords,2)."%)";
              else
                  $percent="";
              if ($notnullcount)
                  $nnpercent="&nbsp;(".number_format($ecount*100/$notnullcount,2)."%)";
              else
                  $nnpercent="";
              $per=htmlspecialchars($l[1]);
              //$enumvals["$l[2]"]=$l[1];
              if ($ii>1) {
                 $graph_ordnum.=",";
                 $graph_val.=",";
              }
              $graph_ordnum.="$ii";
              $graph_val.="$ecount";
                if ($ii%2 == 1) $bgcolor='oddbgcolor';
                else $bgcolor = 'evenbgcolor';
                  $stdout .= "<tr class='$bgcolor'>
                   <td valign='top' width=20%><span class='szoveg'>$ii.</span></td>	   
                   <td valign='top' width=40%><span class='szoveg'>$per</span></td>
                   <td valign='top' width=40%><span class='szoveg'>$ecount / $maxrecords <b>$percent</b></span></td>
                   <td valign='top' width=40%><span class='szoveg'>[ $ecount / $notnullcount <b>$nnpercent</b> ]</span></td>	   
                   </tr>\n";
              $chartData[] = array($ecount, $per);
	          $ii++;
           }
        }
                                                
                        echo '<tr><td colspan="4" class=BACKCOLOR>';
                        //var_dump($chartData);

                        if ($m['multiselect']=="no") {
                        
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
                        } else {
                            
                            /*echo '<pre>';
                            var_dump($chartData);
                            echo '</pre>';*/

                        echo '<script type="text/javascript" src="'.$_MX_var->baseUrl.'/amcharts/amcolumn/swfobject.js"></script>
                        <div id="flashcontent" align="center">
            		        <strong>You need to upgrade your Flash Player</strong>
                	    </div>
                        <script type="text/javascript">
            		    // <![CDATA[		
            		        var so = new SWFObject("'.$_MX_var->baseUrl.'/amcharts/amcolumn/amcolumn.swf", "amcolumn", "920", "320", "8", "#FFFFFF");
                            so.addParam("wmode", "transparent");
                            so.addVariable("path", "");
                            so.addVariable("settings_file", encodeURIComponent("'.$_MX_var->baseUrl.'/amcharts/maxima/settings-100stack.php?language='.$language.'"));
                            so.addVariable("chart_data", "<chart><series>';
                            
                            foreach ($chartData as $key=>$item) {
                                    echo '<value xid=\''.$key.'\'>'.$item[1].'</value>';
                            }
                            echo '</series><graphs>';

                            $chartValues = array();
                            foreach ($chartData as $key=>$itemArray) {
                                    foreach ($itemArray as $key2=>$item) {
                                    if ($key2==0) {
                                        $chartValues[0][$key] = $item;
                                        $chartValues[1][$key] = $notnullcount-$item;
                                    }
                                }
                            }
                            
                            $boolState = array(
                                0 => $word['boolTrue'],//"Igaz",
                                1 => $word['boolFalse']//"Hamis"
                            );

                            foreach ($chartValues as $arrayKey=>$array) {
                                echo '<graph gid=\''.$arrayKey.'\' title=\''.$boolState[$arrayKey].'\'>';
                                foreach ($array as $itemKey=>$item) {
                                    echo '<value xid=\''.$itemKey.'\'>'.$item.'</value>';
                                }
                                echo '</graph>';
                            }
                            
                            //echo <value xid="0">20</value><value xid="1">28</value><value xid="2">30</value></graph>';

                            echo '</graphs></chart>");
                            
                            so.write("flashcontent");
            		    // ]]>
                        </script>';

                            /*echo '<pre>';
                            var_dump($chartValues);
                            echo '</pre>';*/

                        }

                        echo '</td></tr>';

            echo "<tr><td class=BACKCOLOR valign='top' colspan='4'>$word[t_nof_values] ".mysql_num_rows($res3)."</td></tr>

                    <tr>
                        <td valign='top' width='25%' class='formmezo tac'>&nbsp;</td>
                        <td valign='top' width='25%' class='formmezo'>$word[ds_period]</td>
                        <td valign='top' width='50%' colspan='2' class='formmezo tal'>$word[ds_values]</td>
                    </tr>";

        echo $stdout;

        mysql_query("insert into stat_cache (dateadd,ordnum,val) values (now(),'$graph_ordnum','$graph_val')");
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
        $stdout="";
        $chartData = array();
        $chartTitles = array();
        $count=0;
        foreach ($subq as $subq_id=>$subq_vals) {
           $graph_ordnum="";
           $graph_val="";     
           $ii=1;
           $stdout .= "<tr><td class='bgcolor formmezo' valign='top' colspan=4>".htmlspecialchars($optvals["$subq_id"])."</td></tr>
                       <tr>
                           <td valign='top' width='25%' class='formmezo tac'>&nbsp;</td>
                           <td valign='top' width='25%' class='formmezo'>$word[ds_period]</td>
                           <td valign='top' width='50%' colspan='2' class='formmezo tal'>$word[ds_values]</td>
                       </tr>";
           $chartData[$count] = array();
           $chartTitles[$count] = htmlspecialchars($optvals["$subq_id"]);
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
                if ($ii%2 == 1) $bgcolor='oddbgcolor';
                else $bgcolor = 'evenbgcolor';
                  $stdout .= "<tr class='$bgcolor'>
                   <td valign='top' width=25%><span class='szoveg'>$ii.</span></td>	   
                   <td valign='top' width=25%><span class='szoveg'>$per</span></td>
                   <td valign='top' width=25%><span class='szoveg'>$ecount / $maxrecords <b>$percent</b></span></td>
                   <td valign='top' width=25%><span class='szoveg'>[ $ecount / $notnullcount <b>$nnpercent</b> ]</span></td>	   
                   </tr>\n"; 
                   $chartData[$count][$ii] = array($ecount, $per);
	          $ii++;
           }

           //echo '<pre>';
           //var_dump($chartData);
           //echo '</pre>';

           //mysql_query("insert into stat_cache (dateadd,ordnum,val) values (now(),'$graph_ordnum','$graph_val')");
           //$cache_id=mysql_insert_id();	
           /*$stdout .=  "<tr>
              <td class=BACKCOLOR colspan='3' align='center' valign='center' style='border-bottom:1px #000 solid;'>
              <img src='grafnew/stat_demog.php?cache_id=$cache_id&demog_id=$m[id]&group_id=$group_id' align='center' valign='center'>
	       </td>
	       </tr>\n";*/

                        $stdout .= '<tr><td colspan="4" class=BACKCOLOR>';
                        //echo '<pre>';
                        //var_dump($chartData);
                        //echo '</pre>';

                        $stdout .= '
                        <script type="text/javascript" src="'.$_MX_var->baseUrl.'/amcharts/amcolumn/amcolumn.js"></script>
                        <div id="flashcontent_'.$count.'" align="left">
            		        <strong>You need to upgrade your Flash Player</strong>
                	    </div>
                        <script type="text/javascript">
            		    // <![CDATA[		
            		        var so = new SWFObject("'.$_MX_var->baseUrl.'/amcharts/amcolumn/amcolumn.swf", "amcolumn", "'.(180*count($chartData[$count])).'", "300", "8", "#FFFFFF");
                            so.addParam("wmode", "transparent");
                            so.addVariable("path", "");
                            so.addVariable("settings_file", encodeURIComponent("'.$_MX_var->baseUrl.'/amcharts/maxima/column.xml"));
                            

                            so.addVariable("chart_data", "<chart><series>';
                            
                            foreach ($chartData[$count] as $key=>$item) {
                                $stdout .= '<value xid=\''.$key.'\'>'.$item[1].'</value>';
                            }

                            $stdout .= '</series><graphs><graph gid=\'0\' title=\''.htmlspecialchars($optvals["$subq_id"]).'\'>';
                            
                                foreach ($chartData[$count] as $key=>$item) {
                                    $stdout .= '<value xid=\''.$key.'\'>'.$item[0].'</value>';
                                }
                                //$key++;
                                //$chartData[$count][$key] = array($notnullcount-$count2, $word['demog_matrix_neither']);
                                //$stdout .= '<slice title=\''.$word['demog_matrix_neither'].'\' pull_out=\'false\'>'.($notnullcount-$count2).'</slice>';

/*
                            so.addVariable("chart_data", "<chart><series><value xid=\'0\'>2000</value><value xid=\'1\'>2001</value><value xid=\'2\'>2002</value></series><graphs><graph gid=\'0\' title=\'First title\'><value xid=\'0\'>6</value><value xid=\'1\'>36</value><value xid=\'2\'>34</value></graph></graphs></chart>");

*/


                            $stdout .= '</graph></graphs></chart>");





                            so.write("flashcontent_'.$count.'");
            		    // ]]>
                        </script>';
                        $stdout .= '</td></tr>';

            $count++;
        }
                   
                        echo '<tr><td colspan="4" class=BACKCOLOR>';
                        
                        /*echo '<pre>';
                        var_dump($chartData);
                        echo '</pre>';
                        */

                        echo '<script type="text/javascript" src="'.$_MX_var->baseUrl.'/amcharts/amcolumn/swfobject.js"></script>
                        <div id="flashcontent" align="center">
            		        <strong>You need to upgrade your Flash Player</strong>
                	    </div>
                        <script type="text/javascript">
            		    // <![CDATA[		
            		        var so = new SWFObject("'.$_MX_var->baseUrl.'/amcharts/amcolumn/amcolumn.swf", "amcolumn", "920", "320", "8", "#FFFFFF");
                            so.addParam("wmode", "transparent");
                            so.addVariable("path", "");
                            so.addVariable("settings_file", encodeURIComponent("'.$_MX_var->baseUrl.'/amcharts/maxima/settings-stack.php?language='.$language.'"));
                            so.addVariable("chart_data", "<chart><series>';
                            
                            foreach ($chartTitles as $key=>$item) {
                                    echo '<value xid=\''.$key.'\'>'.$item.'</value>';
                            }

                            echo '</series><graphs>';

                            $chartValues = array();
                            foreach ($chartData as $key=>$itemArray) {
                                foreach ($itemArray as $key2=>$item) {
                                    $chartValues[$key2][$key] = $item[0];
                                }
                            }

                            foreach ($chartValues as $arrayKey=>$array) {
                                echo '<graph gid=\''.$arrayKey.'\' title=\''.$chartData[0][$arrayKey][1].'\'>';
                                foreach ($array as $itemKey=>$item) {
                                    echo '<value xid=\''.$itemKey.'\'>'.$item.'</value>';
                                }
                                echo '</graph>';
                            }
                            
                            //echo <value xid="0">20</value><value xid="1">28</value><value xid="2">30</value></graph>';

                            echo '</graphs></chart>");
                            
                            so.write("flashcontent");
            		    // ]]>
                        </script>';
                        //var_dump($chartValues);
                        echo '</td></tr>';
        
        echo $stdout;
   }
   else {
     $selfield="other_value";
     $res=mysql_query("select count(*) from users_$title where 
                       validated='yes' and not isnull($tablevar) and length($tablevar)>0 $filtadd");
     if ($res && mysql_num_rows($res)) {
        $count=mysql_result($res,0,0);
     }
     if ($count) {
        $ii=1;
        echo "<form action=mygroups14.php>
              <tr>
              <input type='hidden' name='group_id' value='$group_id'>
              <input type='hidden' name='demog_id' value='$demog_id'>
              <input type='hidden' name='filt_demog' value='$filt_demog'>
              <td class=BACKCOLOR valign='top' colspan='4'>
	          <span class='szovegvastag'>$word[most_freq1]
              <input type='text' name='maxonpage' value='$maxonpage' size='3'> 
              $word[most_freq2]
              <input type='submit' name='go' value='$word[go]'></span></td>
	          </tr>
              </form>\n";
        $res3=mysql_query("select count(*) as cnt,$tablevar from users_$title where 
	                       validated='yes' and robinson='no' 
                           and not isnull($tablevar) and length($tablevar)>0 $filtadd
	                       group by $tablevar having cnt>1 order by cnt desc limit $maxonpage");  
        if ($res3 && mysql_num_rows($res3)) {
           while ($l=mysql_fetch_row($res3)) {
	      $per=htmlspecialchars($l[1]);
            if ($maxrecords)
                $percent="&nbsp;(".number_format($l[0]*100/$maxrecords,2)."%)";
            else
                $percent="";
          if ($notnullcount)
              $nnpercent="&nbsp;(".number_format($l[0]*100/$notnullcount,2)."%)";
          else
              $nnpercent="";
              if ($ii%2 == 1) $bgcolor = 'oddbgcolor';
              else $bgcolor = 'evenbgcolor';
              echo "<tr class='$bgcolor'>
                 <td valign='top' width=25%><span class='szoveg'>$ii.</span></td>	   
                 <td valign='top' width=25%><span class='szoveg'>$per</span></td>
                 <td valign='top' width=25%><span class='szoveg'>$l[0] / $maxrecords <b>$percent</b></span></td>
                 <td valign='top' width=25%><span class='szoveg'>[ $l[0] / $notnullcount <b>$nnpercent</b> ]</span></td>	   
		        </tr>";
	      $ii++;
           }
        }
     }
   }
   echo "
     </table>
     </td>
     </tr>
     </table>
     <br>
     ";      
}

?>
