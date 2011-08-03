<?
include "auth.php";
$type=intval($type);
$weare=150;
include "cookie_auth.php";  
include "common.php";
include "_form.php";
$language=select_lang();
include "./lang/$language/statistics.lang";

$mres = mysql_query("select title,num_of_mess,membership from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and user_id='$active_userid'
                     and (membership='owner' or membership='moderator' $admin_addq)");
if ($mres && mysql_num_rows($mres))
    $row=mysql_fetch_array($mres);  
else {
    header("Location: $_MX_var->baseUrl/index.php?no_group=1"); exit; }

if (!$tpl) {
    include "class.FastTemplate.php";
    $tpl=new FastTemplate("./templates");
    $tpl->no_strict();
}
$tpl->define( array( "dategen" => "form_dategen.tpl"));

if (isset($_GET["endlink_export"])) {
    $endlink_id = intval(get_http("endlink_export",0));
    $start_daynum = intval(get_http("start_daynum",0));
    $end_daynum = intval(get_http("end_daynum",0));
    mx_csv_headers("endlink_export_$endlink_id.csv");
    $query="select fe.* from form_endlink fe,form f where fe.id='$endlink_id' and fe.form_id=f.id and f.group_id='$group_id'";
    $ress=mysql_query($query);            
    if ($kk=mysql_fetch_array($ress)) {            
        $_MX_form = new MxForm($group_id,"",0,0);
        $_MX_form->InitForm($kk["form_id"]);
        if ($stat_sql=$_MX_form->get_dependency($kk,"endlink","statistic_sql")) {
            $query = "select distinct cid from form_save_temporary where maximaname='mxform$kk[form_id]' and (to_days(dateadd)>='$start_daynum') and (to_days(dateadd)<='$end_daynum') and $stat_sql";
            $resss=mysql_query($query);
            while ($z=mysql_fetch_row($resss)) {                            
                print "$z[0]\n";
            }                                   
        }
    }
    exit;
}

$min_start_daynum=730851; // 2001-01-01
$max_end_daynum=744364; // 2037-12-31

$nowyear=date('Y');
$dropdown_year=2001;
$nav_id="form_stat";

$tpl->assign("FORM_ACTION","subs_stat.php");
$tpl->assign("HIDDEN_PARTS","<input type='hidden' name='type' value='$type'><input type='hidden' name='group_id' value='$group_id'><input type='hidden' name='aff' value='$aff'>");
$tpl->assign("AFF_HIDDEN_PARTS","<input type='hidden' name='type' value='$type'><input type='hidden' name='group_id' value='$group_id'>");
$tpl->assign("GROUP_ID",$group_id);
include "dategen.php";
$tpl->parse("DATEGEN","dategen");

$dategen=$tpl->fetch("DATEGEN");
include "menugen.php";
echo '<script language="JavaScript" type="text/javascript" src="./js/other.js"></script>';

$first = intval(get_http("first",0));
$pagenum = intval(get_http("pagenum",0));
$sortmmm = get_http("sortmmm",'');
$search_text = get_http("search_text",'');
$formfilt = intval(get_http("formfilt",0));

$formfilt=intval(get_http("formfilt",0));
if ($sortmmm==null) $sortmmm.= "dateadd";
if ($sortmmm!="title") $ob=" fs.$sortmmm"; else $ob=" f.title";
if ($sortmmm=="dateadd") $ob.= " desc";
if ($search_text) $wh= " and (f.title like '%$search_text%' or fs.status like '%$search_text%' or fs.comments like '%$search_text%')"; else $where="";
if ($formfilt) $wh= " and f.id=$formfilt";

$query="select fs.*,f.title from form_statistics fs inner join form f on fs.form_id=f.id where f.group_id='$group_id' and  (to_days(dateadd)>='$start_daynum') and (to_days(dateadd)<='$end_daynum') $wh";

$res=mysql_query($query);
$c=1;
$query="select id,title from form where group_id=$group_id group by title order by date desc";
$forms=array();
$ress=mysql_query($query);
while ($fs=mysql_fetch_array($ress)) {
    if ($formfilt==$fs["id"]) {
        $fs["selected"]="selected";
    }
    $forms[]=$fs;
}
if (empty($formfilt)) {
    $forms[0]["selected"]="selected";
}

$maxrecords=mysql_num_rows($res);
$maxPerPage = 50;
$pagenum=intval($pagenum);

if($pagenum<1) $pagenum=1;
if($first<0) $first=0;
if(!strlen($first) && $pagenum) $first=($pagenum-1)*$maxPerPage;
if(!$first) $first = 0;
if($first>=$maxrecords) $first = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;

$pagenum=(int)($first / $maxPerPage) + 1;
$maxpages = ceil($maxrecords / $maxPerPage);
$LastPage = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;

$OnePageLeft = $first - $maxPerPage; 
if($OnePageLeft<1) $OnePageLeft = -1;

$OnePageRight = $maxPerPage + $first; 
if($OnePageRight>$LastPage) $OnePageRight = $LastPage;

echo PrintNavigation();

if ($res && $count=mysql_num_rows($res)) {
    $fids=array();
    //get fills list
    $query="select fs.*,f.title from form_statistics fs inner join form f on fs.form_id=f.id where f.group_id='$group_id' and (to_days(dateadd)>='$start_daynum') and (to_days(dateadd)<='$end_daynum') $wh order by $ob limit $first,$maxPerPage";
    //print $query.'<br>';
    $res=mysql_query($query);
    while ($k=mysql_fetch_array($res)) {
        if (!in_array($k["form_id"],$fids)) $fids[]=$k["form_id"];
        if ($c%2==0) $bg="background-color: #EEEEEE"; 
        else $bg="";
        $tp.="<tr style='vertical-align: top; $bg'>
              <td>".($c+($pagenum-1)*$maxPerPage).".)</td>
              <td>$k[dateadd]</td>
              <td>$k[title]</td>
              <td>$k[status]</td>
              <td>".sec_to_text($k[fill_time])."</td>
              <td>";
        $tmp=explode("|:|",$k[comments]);
        foreach ($tmp as $i=>$com) {
            if ($com!="") $tp.= ($i+1).".)&nbsp;".$com."<br>";
        }
        $tp.="</td></tr>";
        ++$c;
    }

    //number of succesfully filled forms
    $query="select fs.member_id, fs.fill_time from form_statistics fs inner join form f on fs.form_id=f.id where f.group_id='$group_id' and (to_days(dateadd)>='$start_daynum') and (to_days(dateadd)<='$end_daynum') $wh order by $ob";
    //print $query.'<br>';
    $stat=array();
    $memberids = array();
    $res=mysql_query($query);
    $c=0;
    $ftc=0;
    $ftsum=0;
    while ($k=mysql_fetch_array($res)) {
        if (!in_array($k['member_id'],$memberids)) {
            $stat[$c]=$k["fill_time"];
            if ($k["fill_time"]<24*60*60) { // ignore unrealisticly long fill times
                $ftsum+=$k["fill_time"];
                $ftc++;
            }
            $memberids[] = $k['member_id'];
            ++$c;
        }
    }
    sort($stat);
  
    $fastest=0;
    $fcnt=0;
    
    while ($fastest<=0 && $fcnt<count($stat)) {
        if ($stat[$fcnt]>0) {
            $fastest=$stat[$fcnt];
        }
        $fcnt++;
    }
    $yy=0;          
    $endlink_stat=array();
    if (count($fids)) {
        foreach ($fids as $f=>$fk) {
            $_MX_form = new MxForm($group_id,"",0,0);
            $_MX_form->InitForm($fk);
            $query="select * from form_endlink where form_id='$fk'";
            $ress=mysql_query($query);            
            while ($kk=mysql_fetch_array($ress)) {            
                if ($stat_sql=$_MX_form->get_dependency($kk,"endlink","statistic_sql")) {
                    if ($stat_sql!="(())") {
                        $query = "select count(*) from form_save_temporary where maximaname='mxform$fk' and (to_days(dateadd)>='$start_daynum') and (to_days(dateadd)<='$end_daynum') and $stat_sql";
    //print "$query<br>";
                        $resss=mysql_query($query);
                        if ($z=mysql_fetch_row($resss)) {                            
                            $yy+=$z[0];
                            $endlink_stat[]=array("title"=>$kk["title"],"aborted"=>$z[0],"endlink_id"=>$kk["id"]);
                        }      
                    }                             
                }
            }
        }
    }
    //forms total
    $query="select count(*) from form_save_temporary where maximaname='mxform$fk' and (to_days(dateadd)>='$start_daynum') and (to_days(dateadd)<='$end_daynum')";
    //print $query.'<br>';
    $fallnd=mysql_fetch_row(mysql_query($query));
    //print $fallnd[0];
    if ($fallnd[0]>0) {
        $stmsg="<table width='980' border='1'><tr><td>$word[t_form_number]</td><td>$word[t_n_finished_endlink]</td><td>$word[t_n_finished_or]</td></tr><tr style='vertical-align:top'><td>$c  [".number_format($c*100/$fallnd[0],2)."%]</td><td>";    
        $stmsg.="Összesen: $yy [".number_format($yy*100/($fallnd[0]),2)."%]<br>";
        if (count($endlink_stat)) {
            foreach ($endlink_stat as $es) {
                $stmsg.="$es[title] endlink: $es[aborted]&nbsp;&nbsp;[" . number_format($es["aborted"]*100/($fallnd[0]),2) . "%] <a href='form_statistic.php?group_id=$group_id&endlink_export=$es[endlink_id]&start_daynum=$start_daynum&end_daynum=$end_daynum'>CID lista letöltés</a><br>";
            }
        }
        $stmsg.="</td><td>".($fallnd[0]-$c-$yy)." [".number_format(($fallnd[0]-$c-$yy)*100/$fallnd[0],2)."%]</td></tr></table>";
    }
    
    $stmsg.="<table width='980'><tr>";
    $stmsg.="<td style='text-align:center; width:326px;'><b>$word[t_fastest_fill]</b> ".sec_to_text($fastest)."</td>";
    $stmsg.="<td style='text-align:center; width:326px;'><b>$word[t_slowest_fill]</b> ".sec_to_text($stat[$ftc-1])."</td>";
    $stmsg.="<td style='text-align:center;'><b>$word[t_average]</b> ".sec_to_text(round($ftsum/$ftc))."</td>";
    $stmsg.="</tr></table><br/>";
    
    $lepes = round($ftsum/$ftc/60/5)*60;
    $lepesek = array();
    $chartDat = array();
    for ($i=1; $i<11; $i++) {
        //$stmsg.=sec_to_text($lepes*$i)."<br>";
        $lepesek[] = $lepes*$i;
        $chartDat[] = 0;
    }
    
    foreach($stat as $s) {
        if (($s+0)>=$fastest && ($s+0)<=$stat[$c-1]) {
            for($j=0; $j<count($lepesek); $j++) {
                if ($lepesek[$j]>$s+0) {
                    $chartDat[$j]++;
                    break;
                }
            }
            if ($j==count($lepesek)-1) {
                $chartDat[$j]++;
            }
        }
    }

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
                            so.addVariable("settings_file", encodeURIComponent("'.$_MX_var->baseUrl.'/amcharts/maxima/settings-pie.php?language='.$language.'"));

                            so.addVariable("chart_data", "<pie>';
                            

                                for ($i=0; $i<count($lepesek); $i++) {
                                $title="";
                                if ($i==0) {
                                    $title = "0s-";
                                } else {
                                    $title = sec_to_text($lepesek[$i-1])."- ";
                                }
                                $title .= sec_to_text($lepesek[$i]);
                                    
                                if ($i==count($lepesek)-1) $title .= "++";
                                    //echo " : ".$chartDat[$i]."<br/>";
                                    echo '<slice title=\''.$title.'\' pull_out=\'false\'>'.$chartDat[$i].'</slice>';

                                }

                            
                            echo '</pie>");
                            
                            so.write("flashcontent");
            		    // ]]>
                        </script>';

    echo "$stmsg<table width='100%'>";
    echo "<tr><td class='formmezo' width='50'>$word[id_name]</td><td class='formmezo' width='150'>$word[t_fdate]</td><td class='formmezo'>$word[t_form_title]</td><td class='formmezo' width='70'>$word[t_state]</td><td class='formmezo' width='80'>$word[t_fill_time]</td><td class='formmezo'>$word[t_comm]</td></tr>";
    echo $tp;
}

//****[ PrintNavigation ]*****************************************************
function PrintNavigation() {
    global $_MX_var,$group_id,$pagenum,$LastPage,$OnePageLeft,$OnePageRight,$sortmmm,$filt_demog,$forms;
    global $_MX_var,$pagenum,$maxpages,$first,$word,$maxPerPage,$search_text,$stmsg,$dategen;

    $sel_sort[$sortmmm] = "selected";
    $params="group_id=$group_id&search_text=$search_text&sortmmm=$sortmmm";
 
    $navig="
    <tr><td colspan='10'><table border=0 cellspacing=0 cellpadding=0 width='100%'>  
    <tr>
    <td>
    <table border=0 cellspacing=0 cellpadding=0 width='100%'>
    <tr>
        <td class='formmezo' align='left' width='33%'>
    <table border='0' cellspacing='0' cellpadding='0'>
          <form name=inputs>
          <input type='hidden' name='group_id' value='$group_id'>
          <input type='hidden' name='type' value='6'>
      <tr>";
    if ($first>0) {
        $navig.= " 
            <td nowrap align='right'>              <a href='$_MX_var->baseUrl/form_statistic.php?$params&first=0'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down2.gif' width='20' height='14' border='0'></a>
              <a href='$_MX_var->baseUrl/form_statistic.php?$params&first=$OnePageLeft'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down1.gif' width='20' height='14' border='0'></a>
            </td>";
    }
    else {
        $navig.= " 
            <td nowrap align='right'>&nbsp;&nbsp;
              <img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down2_dead.gif' width='20' height='14' border='0'>
              <img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down1_dead.gif' width='20' height='14' border='0'>
            </td>";
    }

    $navig.= " 
            <td nowrap align='right'> 
              <input type='text' name='pagenum' size='3' maxlength='3' value='$pagenum'>
            </td>
            <td nowrap class='formmezo'>&nbsp;/ $maxpages</td>";
    
    if ($first<$LastPage) {
        $navig.= " 
            <td nowrap align='right'> &nbsp;
              <a href='$_MX_var->baseUrl/form_statistic.php?$params&first=$OnePageRight'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up1.gif' width='20' height='14' border='0'></a>
              <a href='$_MX_var->baseUrl/form_statistic.php?$params&first=$LastPage'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up2.gif' width='20' height='14' border='0'></a>&nbsp; </td>";
    }
    else {
        $navig.= " 
            <td nowrap align='right'>&nbsp;&nbsp;
              <img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up1_dead.gif' width='20' height='14' border='0'>
              <img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up2_dead.gif' width='20' height='14' border='0'>
            </td>";
    }
    $navig.= " 
          </tr>
        </table>
          </td>
    </tr>
  </table>
      </td>
        <td class='formmezo' align='center' width='33%'>
        <table border='0' cellspacing='0' cellpadding='0'>
          <tr> 
  <input type='hidden' name='group_id' value='$group_id'>
            <td nowrap class='formmezo' align='center'>$word[view]:</td>
            <td nowrap align='center' class='formmezo'>&nbsp;$maxPerPage
            </td>
            <td nowrap class='formmezo' align='center'> $word[members_page]</td>
          </tr>
        </table>
      </td>
        <td class='formmezo' align='right' width='33%'>
        <table border='0' cellspacing='0' cellpadding='0'>
          <tr>           
  <input type='hidden' name='group_id' value='$group_id'>
  <input type='hidden' name='type' value='6'>
            <td nowrap class='formmezo'>$word[sort_order]: </td>
            <td nowrap>
              <select onChange='JavaScript: this.form.submit();' name=sortmmm>
                <option value='dateadd' $sel_sort[dateadd]>$word[t_fdate]</option>
                <option value='title' $sel_sort[title]>$word[t_form_title]</option>
                <option value='status' $sel_sort[status]>$word[t_state]</option>
                <option value='fill_time' $sel_sort[fill_time]>$word[t_fill_time]</option>
              </select>
            </td>
          </tr>
        </table>
       </td>
    </tr>
    <tr>
        <td class='formmezo'>$dategen
        </td>
        <form name=inputs>        
        <td class='formmezo' style='text-align: center'>$word[t_form_title]:
        <select onChange='JavaScript: this.form.submit();' name=formfilt>";
        $navig.="<option value='0'>- - - - -</option>";  
        foreach ($forms as $f=>$fk) {
            $navig.="<option $fk[selected] value='$fk[id]'>$fk[title]</option>";
        }        
    $navig.= "</select>        
        </td>
        <td class='formmezo' align=right> 
            <table border='0' cellspacing='0' cellpadding='0'>
            <tr>
            <input type='hidden' name='group_id' value='$group_id'>
            <input type='hidden' name='group_id' value='$group_id'>
            <input type='hidden' name='group_id' value='$group_id'>
                <td nowrap class='formmezo'>$word[t_search_text]: </td>
                <td nowrap>
                    <input type='text' name='search_text' value='$search_text' onkeypress='checkEnter(event)'>                    
                </td>
            </tr>
            </table>
        </td>
    </tr>
    </table></form></td></tr>";

    return $navig;
}

function sec_to_text($sec) {
    $s="";
    $days=floor($sec/(86400));
    $h_m=floor(($sec-$days*86400)/3600);
    $m_m=floor(($sec-$days*86400-$h_m*3600)/60);
    $s_m=floor($sec-$days*86400-$h_m*3600-$m_m*60);

    if($days>0){
        $s.=$days." d";
        if($h_m>0) $s.=$h_m."h ";
    } 
    elseif($h_m>0) {
        $s.=$h_m."h ";
        if($m_m>0) $s.=$m_m."m ";
    } 
    elseif($m_m>0) {
        $s.=$m_m."m ";
        if($s_m>0) $s.=$s_m."sec ";
    } 
    else {
        $s.=$s_m."sec ";
    }
    return $s; 
}
?>
