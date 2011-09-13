<?
include "auth.php";
include "cookie_auth.php";

$sortm = get_http('sortm','');

if (empty($sortm) && isset($gr_sortm))
    $sortm=$gr_sortm;
setcookie("gr_sortm",$sortm,time()+30*24*3600);    

$faelemek=get_http("faelemek","");
if (empty($faelemek) && isset($_COOKIE["gr_faelemek"]))
    $faelemek=$_COOKIE["gr_faelemek"];
setcookie("gr_faelemek",$faelemek,time()+30*24*3600);    

$weare=23;
include "menugen.php";
include "./lang/$language/index.lang";

$highlight_border = $_MX_var->application_instance=="kc"?"a01d24":"fdbd1b";

print "
<script>
    function set_border(tr_id,stat) {
        var n=document.getElementById(tr_id);
        for (var c in n.childNodes) {
            var color='#fff';
            if (stat) color='#$highlight_border';
            if (n.childNodes[c].tagName=='TD') {
                if (c=='0') n.childNodes[c].style.borderWidth='2px 0px 2px 2px';                
                if (c==n.childNodes.length-1) n.childNodes[c].style.borderWidth='2px 2px 2px 0px';                                
                n.childNodes[c].style.borderColor=color;
            }                
        }
    }
</script>
";

$num_of_owned_groups=0;
$res=mysql_query("select count(*) from members where user_id='$active_userid' and membership='moderator'");
if ($res && mysql_num_rows($res))
    $num_of_owned_groups=mysql_result($res,0,0);
 
if(!$sortm)
    $sortm=1;
switch ($sortm) {
    case 1: $order = "order by realname"; break;
    case 2: $order = "order by realname desc"; break;
}

$grouped=array();
$grouping=array();
$grouper=array();
$res=mysql_query("select id,if(length(name)>0,name,title) realname from multi where index_grouping='yes' $order");
if ($res && mysql_num_rows($res)) {
    while ($row=mysql_fetch_array($res)) {
        $grouping["$row[id]"]=array("name"=>htmlspecialchars($row["realname"]),"rows"=>"","count"=>0);
        $grouper[]=$row["id"];
    }
}
$grouping["other"]=array("name"=>htmlspecialchars($word["other_groups"]),"rows"=>"","count"=>0);
$grouping["multis"]=array("name"=>htmlspecialchars($word["aff_multi_groups"]),"rows"=>"","count"=>0);

$union="union all
        select if(length(g.name)>0,g.name,g.title) realname, m.membership, m.group_id, g.title, 'multis' as multiid, 'multi' as grouptype
        from multi_members m,multi g
        where g.id=m.group_id and m.user_id='$active_userid' and m.membership='affiliate'";

if (count($grouper)) {
    $sql="select if(length(g.name)>0,g.name,g.title) realname, m.membership, m.group_id, g.title, mg.multiid, 'single' as grouptype
          from members m,groups g left join multigroup mg on g.id=mg.groupid and mg.multiid in (" . implode(",",$grouper) . ")
          where g.id=m.group_id and m.user_id='$active_userid' $union $order";
}
else {
    $sql="select if(length(g.name)>0,g.name,g.title) realname, m.membership, m.group_id, g.title, 0 as multiid, 'single' as grouptype
          from members m,groups g
          where g.id=m.group_id and m.user_id='$active_userid' $union $order";
}

//$jognames=array("owner"=>"T","moderator"=>"M","support"=>"S","admin"=>"A","client"=>"K","affiliate"=>"AFF");
$jognames=array("owner"=>"$word[owner]","moderator"=>"$word[moderator]","support"=>"$word[support]","admin"=>"$word[admin]","client"=>"$word[client]","affiliate"=>"$word[affiliate]","sender"=>"$word[sender]");

$gfa=explode(",",$faelemek);
$gfareal=array();

$notother=0;
$bgi=0;
$res = mysql_query($sql);
if ($res && mysql_num_rows($res)) {
    while($row=mysql_fetch_array($res)) {
        $affiliate="";
        $tmod="";
        $num_of_mess="";
        $last_date="";
        $group_name=$row["realname"] ." </span><span style='font-size:80%'>(" . $jognames["$row[membership]"] . ")";
        $group_id=$row["group_id"];
        $bgi++;
        $rind="other";
        if (!empty($row["multiid"])) {
            $rind=$row["multiid"];
            $notother++;
        }
        $grouping["$rind"]["count"]++;
        if ($grouping["$rind"]["count"]%2)
            $bgrnd="class='bgvilagos2 trborder'";
        else
            $bgrnd="bgcolor=white class='trborder'";

        if ($row["grouptype"]=="multi") {
            $link="subs_stat.php?multiid=$group_id&type=2";
            //$grouprow.="<td $bgrnd align=middle colspan=2>&nbsp;</td>\n";
        }
        else {
            if ($row["membership"]=="affiliate") {
                $link="mygroups_main.php?group_id=$group_id";
            }
            else {
                $link="threadlist.php?group_id=$group_id";
            }
        }

        $grouprow="<tr id='tr_$bgi' style='cursor:pointer;' onclick='document.location=\"$link\"' onmousemove='set_border(\"tr_$bgi\",1);' onmouseout='set_border(\"tr_$bgi\",0)'><TD style='border-width: 2px 0px 2px 2px;' $bgrnd title='$row[title]'><SPAN class=szovegvastag>&nbsp;$group_name</SPAN></TD>\n";
        $modspan=1;
        $modrow="";
        if ($row["grouptype"]=="multi") {
            //$link="subs_stat.php?multiid=$group_id&type=2";
            $grouprow.="<td $bgrnd align=middle colspan=2>&nbsp;</td>\n";
        }
        else {
            //$link="mygroups_main.php?group_id=$group_id";
            //$link="threadlist.php?group_id=$group_id";
            $grouprow.="<td style='border-width: 2px 0px;' $bgrnd align=middle colspan=2>&nbsp;</td>\n";
        }
        if ($row["membership"]=="owner" || $row["membership"]=="moderator")
            $actaddon="[<a href='alapadatok.php?group_id=$group_id'>$word[menu_main2]</a>]";
        else
            $actaddon="";
        $grouprow.="<td style='border-width: 2px 2px 2px 0px;' $bgrnd valign=center align=middle><!--$actaddon"."[<a href='$link'>$word[select_group]</a>]-->&nbsp;</td></tr>\n";
        if (in_array($rind,$gfa) && !in_array($rind,$gfareal)) {
            $gfareal[]=$rind;
        }
        if (!in_array($rind,$gfareal)) {
            $grouping["$rind"]["rows"] .= $grouprow;
        }
  }
}

######################## PRINT

printhead();
if ($bgi) {
    PrintNavigation("Csoportok");
    $gfal="," . implode(",",$gfareal) . ",";
    foreach ($grouping as $gind=>$gdata) {
        if (in_array($gind,$gfareal)) {
            $gsgn="<a href='index.php?faelemek=" . str_replace(",$gind,",",",$gfal) . "'>[+]</a>";
        }
        else {
            $gsgn="<a href='index.php?faelemek=$gfal$gind'>[-]</a>";
        }
        if ($notother && $gdata["count"]) {
            print "<tr><td colspan='4' class='bgkiemelt2' style='padding:2px;'><span class='szovegvastag'>$gsgn&nbsp;$gdata[name]</span></td></tr>";
        }
        print $gdata["rows"];
    }
    PrintSubFoot(); 
}
else {
    PrintEmptyNavigation($word["groups"]);
    echo "<tr>
          <td class=bgkiemelt2 align=center><span class=szovegvastag>$word[no_hits]</span></td>
          <td class=bgkiemelt2 colspan=1>&nbsp;</td>
          <td class=bgkiemelt2 align=center>&nbsp;</td>
          <td class=bgkiemelt2>&nbsp;</td>        
          </tr>\n";
    PrintSubFoot();     
}
  
printfoot();
include "footer.php";

#######################################################
###                  FUNCTIONS               ###
#######################################################

function printhead() {
    global $_MX_var,$multi_error,$word;

    if (!empty($multi_error))
        echo "<br>
              <TABLE cellSpacing=0 cellPadding=1 width=\"100%\" bgColor=$_MX_var->main_table_border_color border=0>
              <TBODY>
              <TR>
              <TD>
              <TABLE cellSpacing=1 cellPadding=0 width=\"100%\" bgColor=#ffffff border=0>
              <tr><td><span class='szovegvastag'>$multi_error</span></td></tr>
              </table></td></tr></table>\n";
    echo "<TABLE cellSpacing=0 cellPadding=1 width=\"100%\" bgColor=$_MX_var->main_table_border_color border=0>
          <TBODY>
          <TR>
          <TD>
          <TABLE cellSpacing=1 cellPadding=0 width=\"100%\" bgColor=$_MX_var->main_table_border_color border=0>
          <TBODY>\n";
}

function printfoot() {
    echo "</table>
          </td>
          </tr>
          </table>\n";
}

function PrintNavigation($print_text,$nosubhead=0) {
    global $_MX_var,$sortm,$word;
  
    for ($i=0;$i<10;$i++) {
        $sel_sort[$i] = "";
    }
    $sel_sort[$sortm] = "selected";
    echo "<tr>
          <td colspan=4 bgcolor=$_MX_var->main_table_border_color>
          <table border=0 cellspacing=0 cellpadding=0 width=100% bgcolor=$_MX_var->main_table_border_color>
          <TR>
          <FORM name=inputs method=post>
          <td align=left class=formmezo colspan=2>&nbsp;$print_text</td> 
          <TD noWrap align=right class=formmezo colspan=2>$word[sort_order]&nbsp;
          <select onChange='JavaScript: this.form.submit();' name=sortm>
          <option value=1 $sel_sort[1]>$word[by_groupname_asc]</option>
          <option value=2 $sel_sort[2]>$word[by_groupname_desc]</option>
          </select>
          </span>
          </td>
          </form>
          </tr>
          </table>
          </td>
          </tr>\n";
    if (!$nosubhead)
        PrintSubHead();
}

function PrintEmptyNavigation($print_text,$nosubhead=0) {
    
    echo "<tr>
          <td colspan=4 bgcolor=$_MX_var->main_table_border_color>
          <table border=0 cellspacing=5 cellpadding=0 width=100% bgcolor=$_MX_var->main_table_border_color>
          <TR>
          <td align=left class=formmezo colspan=2>&nbsp;$print_text</td> 
          <TD noWrap align=right class=formmezo colspan=2>&nbsp;
          </td>
          </tr>
          </table>
          </td>
          </tr>\n";
    if (!$nosubhead)
        PrintSubHead();
}

function PrintSubHead() {
    global $_MX_var,$word;

    echo "<tr>
          <td class=bgkiemelt2 vAlign=center align=middle> &nbsp;$word[groups]&nbsp;</td>
          <td class=bgkiemelt2 vAlign=top align=middle colspan=2><!--&nbsp;$word[messages]&nbsp;--></td>
          <td class=bgkiemelt2 align=center><!--&nbsp;$word[actions]&nbsp;--></td>
          </tr>\n";    
}

function PrintSubFoot() {

    echo "<tr>
          <td colspan=4 bgcolor=white>&nbsp;</td>
          </tr>\n";
}
?>
