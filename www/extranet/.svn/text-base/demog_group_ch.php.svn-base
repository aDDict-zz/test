<?
include "auth.php";
$weare=60;
if (get_http('demog_group_id','')) $subweare=601;
else $subweare=602;
include "cookie_auth.php";
include "common.php";

$mres = mysql_query("select title,num_of_mess,membership 
                     from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and (membership='owner' or membership='moderator' $admin_addq)
                     and user_id='$active_userid'");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else {
    exit; }

$title=$rowg["title"];
$active_membership=$rowg["membership"];

$error=array();

include "menugen.php";
include "./lang/$language/demog.lang";

$addnewname = get_http('addnewname','');
$demog_group_id = get_http('demog_group_id','');
$enter = get_http('enter','');
$group_id = get_http('group_id','');
$dgrp = get_http('dgrp','');


if ($addnewname) {
    $addnewname=mysql_escape_string($addnewname);
    $res=mysql_query("select id from demog_group where group_id='$group_id' and name='$addnewname'");
    if ($res && mysql_num_rows($res)) {
        $demog_group_id=mysql_result($res,0,0);
    }
    else {
    	$q="insert into demog_group set name='$addnewname',group_id='$group_id'";
        $res=mysql_query($q);
        if ($res) {
            $demog_group_id=mysql_insert_id();
            $error[]=$word["e_success"];
        }
        logger($q,$group_id,"","","demog,demog");
    }
}

$demog_group_id=intval($demog_group_id);
if ($demog_group_id) {
    $res=mysql_query("select * from demog_group where group_id='$group_id' and id='$demog_group_id'");
    if ($res && mysql_num_rows($res)) {
        $l=mysql_fetch_array($res);
        $stat_text=htmlspecialchars($l["name"]." > ");
    }
    else {
        $demog_group_id=0;
    }
}
            
if ($enter=="yes")
    demog_group_enter();

if ($demog_group_id) {
    $stat_text="$stat_text $word[dg_change]";
}
else {
    $stat_text=$word["dg_new"];
}
    
printhead();

$alldata=array("name"=>"input");

if ($demog_group_id && count($error)==0) {
    $res=mysql_query("select * from demog_group where id='$demog_group_id'");
    if ($res && mysql_num_rows($res)) {
        $l=mysql_fetch_array($res);
    }
}
$errorlist=implode("<br>",$error);

echo "<form method='post' action='demog_group_ch.php' name='dgf'>
      <input type='hidden' name='enter' value='yes'>
      <input type='hidden' name='demog_group_id' value='$demog_group_id'>
      <input type='hidden' name='group_id' value='$group_id'>
      <tr>
      <td colspan='2' bgcolor='white'><span class='szoveg'>$errorlist&nbsp;</span></td>
      </tr>\n";
reset($alldata);

while (list($data,$widget)=each($alldata)) {
    if (isset($l) && isset($l["$data"])) {
        $value=htmlspecialchars($l["$data"]);
    }
    elseif (isset($_POST) && isset($_POST["$data"])) {
        $value=htmlspecialchars(slasher($_POST["$data"],0));
    }
    else {
        $value="";
    }
    $qtext=htmlspecialchars($word["dg_$data"]);
    if (in_array($data,array("landing_page","landing_page_inactive"))) {
        $qtext.="<br><a href='form_landview.php?demog_group_id=$demog_group_id&group_id=$group_id&lpview=$data' target='_blank'>El&#337;nézet</a>";
    }
    if ($widget=="input") {
        $qw="<input class=formframe type='text' name='$data' value=\"$value\" size='35'><input type=button class='tovabbgomb' value='$word[dg_new]' onclick=\"location='demog_group_ch.php?group_id=$group_id&addnewname='+escape(document.dgf.name.value);\">";
    }
    elseif ($widget=="textarea") {
        $qw="<textarea class='formframe' name='$data' wrp='virtual' rows='12' cols='70'>$value</textarea>";
    }
    else {
        $value=="yes"?$ch="checked":$ch="";
        $qw="<input type='checkbox' name='$data' $ch value='yes'>";
    }
    echo "<tr>
        <td valign='top' bgcolor='white'><span class='szoveg'>$qtext</span></td>
        <td valign='top' bgcolor='white'><span class='szoveg'>$qw</span></td>
        </tr>\n";    
}
$col=array();
$prev_demog_group=-1;
$res=mysql_query("select d.id,d.question,vd.demog_group_id from vip_demog vd,demog d where vd.group_id='$group_id' 
                  and d.id=vd.demog_id order by vd.ordernum");
logger($q,$group_id,"","","vip_demog,demog");
if ($res && mysql_num_rows($res)) {
    while ($k=mysql_fetch_array($res)) {
    $prev_demog_group=$k["demog_group_id"];
        $index=$ci%3;
    if ($demog_group_id && $demog_group_id==$k["demog_group_id"]) {
        $sel="checked";
    }
    else {
        $sel="";
    }
	$col["$k[demog_group_id]"][$ci]="<input type='checkbox' name='dgrp[$k[id]]' $sel value='1'> ".htmlspecialchars($k["question"])." <br>";
    $ci++;
    }
}
$dnames["0"]=$word["ft_common"];
$rs=mysql_query("select id,name from demog_group where group_id='$group_id' order by name");
if ($rs && mysql_num_rows($rs)) {
    while ($k=mysql_fetch_array($rs)) {
        $dnames["$k[id]"]=$k["name"];
    }
}
echo "
    <tr>
    <td align=center colspan=2><table width='100%' cellpadding='2' cellspacing='1' border='0'>\n";
reset($col);

/*print '<pre>';
print_r($dnames);
print '</pre>';*/

foreach ($dnames as $key=>$value) 
{
    echo "
    <tr>
    <td colspan='3' class='bgkiemelt2'><span class='szovegvastag'><a href='demog_group_ch.php?group_id=$group_id&demog_group_id=$key'>".htmlspecialchars($value)."</a>:</span></td>
    </tr><tr>";
	$c=1;
	if (is_array($col[$key])) {
        foreach ($col[$key] as $k=>$v)
        {
            echo "<td width='33%' bgcolor='white' valign='top'><span class='szoveg'>".$v."</span></td>";		
            if ($c%3==0) echo"</tr><tr>";
            $c++;
        }
    }
	if ($c%3==0) echo"<td bgcolor='white'>&nbsp;</td></tr>";
	if ($c%3==2) echo"<td colspan='2' bgcolor='white'>&nbsp;</td></tr>";
}
    
echo "    </table></td>
    </tr>
    <tr>
    <td align=center colspan=2>
    <input type=submit class='tovabbgomb' value='$word[submit3]'>
    </td>
    </tr></form>";
printfoot();
include "footer.php";
  

function demog_group_enter() {
    global $_MX_var,$group_id, $error, $demog_group_id, $_POST, $word;

    $alldata=array("name"=>"input");

    $set="";
    $glue="";
    
    reset($alldata);
    while (list($data,$widget)=each($alldata)) {
        if (isset($_POST) && isset($_POST["$data"])) {
            $value=$_POST["$data"];
        }
        else {
            $value="";
        }
        if (strlen($value)==0) {
            $error[]=$word["e_$data"];
        }
        $set.=$glue."$data='".slasher($value)."'";
        $glue=",";
    }
    if (count($error)==0) {
        if ($demog_group_id) {
        	$q="update demog_group set $set where id='$demog_group_id' and group_id='$group_id'";
            mysql_query($q);
        	logger($q,$group_id,"","demog_group_id=$demog_group_id","demog_group");            
        }
        else {
        	$q="insert into demog_group set $set,group_id='$group_id'";
            $res=mysql_query($q);
            if ($res) {
                $demog_group_id=mysql_insert_id();
            }
        	logger($q,$group_id,"","","demog_group");
        }
        $error[]=$word["e_success"];
        if ($demog_group_id) {
            $res=mysql_query("select id,demog_id,demog_group_id from vip_demog where group_id='$group_id'");
            if ($res && mysql_num_rows($res)) {
                while ($k=mysql_fetch_array($res)) {
                    if (isset($_POST["dgrp"]["$k[demog_id]"]) && $k["demog_group_id"]!=$demog_group_id) {
                    	$q="update vip_demog set demog_group_id='$demog_group_id' where id='$k[id]'";
                        mysql_query($q);
                        logger($q,$group_id,"","demog_group_id=$demog_group_id","vip_demog");
                    }
                    if (!isset($_POST["dgrp"]["$k[demog_id]"]) && $k["demog_group_id"]==$demog_group_id) {
                    	$q="update vip_demog set demog_group_id='0' where id='$k[id]'";
                        mysql_query($q);
                        logger($q,$group_id,"","","vip_demog");
                    }
                }
            }
        }
    }
}

function printhead() {

    global $_MX_var,$stat_text,$userlist_yn,$filt_demog_options,$group_id,$pagenum,$word,$demog_group_id;

    $links="";

    echo "<TABLE cellSpacing=0 cellPadding=1 width=\"100%\" class='bgColor' border=0>
        <TR>
        <TD>
        <TABLE cellSpacing=1 cellPadding=0 width=\"100%\" class='bgColor' border=0>
        <tr>
        <td class=bgkiemelt2 align=left><span class=szovegvastag><!--<a href='demog_group.php?group_id=$group_id'>$word[dg_title]</a> &gt; -->$stat_text</span></td>
        <td class=bgkiemelt2 align=right><span class=szovegvastag>$links&nbsp;</span></td>
        </tr>\n";
}

function printfoot() {

    echo "</table>
        </td>
        </tr>
        </table>";
}

