<?
include "auth.php";
$sgweare=19;
$weare=19;
include "cookie_auth.php";  
include "common.php";
$language=select_lang();
include "./lang/$language/filter.lang";

$group_id=intval($group_id);
$mres = mysql_query("select title from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and (membership='owner' or membership='moderator')
                     and user_id='$active_userid'");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else {
    header("Location: $_MX_var->baseUrl/index.php"); exit; }

$group_part=" (vip_demog.group_id='$group_id') ";

$_MX_iframe = 1;
include "menugen.php";

$vgparam=get_http("vgparam",-1);

if (!isset($vgparam)) {
    $vgparam=-1;
}
else {
    $vgparam=intval($vgparam);
}

$v_vsel[0]="";
$v_vsel[1]="";

if ($vgparam>-1) {
    $i=0;
    $r2=mysql_query("select distinct variable_name,variable_type,question,demog_id from vip_demog,demog where 
                     vip_demog.demog_id=demog.id and $group_part and demog_group_id='$vgparam' order by question");
    if ($r2 && $qnum=mysql_num_rows($r2)) {
        while ($mm=mysql_fetch_array($r2)) {
            $side=floor($i/($qnum/2));
            if (!empty($v_vsel[$side])) {
                $v_vsel[$side].="<br>";
            }
            if ($mm["variable_type"]=="enum" || $mm["variable_type"]=="matrix") {
                $v_vsel[$side].="<a href='#' onclick=\"javascript:enumwindow($mm[demog_id])\">".htmlspecialchars($mm["question"])."</a>&nbsp;";
                /*$subvar1="";
                $subvar2="";
                if ($mm["variable_type"]=="matrix") {
                    $subvar1.="<select name='q_enums1$i' onchange=\"addBody('/'+this.form.q_enums1$i.options[this.form.q_enums1$i.selectedIndex].value)\"><option value=' '>-- részkérdések --</option>";
                }
                $subvar2.="<select name='q_enums$i' onchange=\"addBody('\''+this.form.q_enums$i.options[this.form.q_enums$i.selectedIndex].value+'\'')\"><option value=' '>$word[options]</option>";
                $r3=mysql_query("select id,enum_option,vertical from demog_enumvals where demog_id='$mm[demog_id]'");
                if ($r3 && mysql_num_rows($r3)) {
                    while ($k3=mysql_fetch_array($r3)) {
                        $sls=addslashes($k3["enum_option"]); 
                        if ($k3["vertical"]=="no") {
                            $subvar2.="<option value='$sls'>".htmlspecialchars($k3["enum_option"])."</option>";
                        }
                        elseif ($mm["variable_type"]=="matrix") {
                            $subvar1.="<option value='$k3[id]'>$k3[id] ".htmlspecialchars($k3["enum_option"])."</option>";
                        }
                    }
                }
                if ($mm["variable_type"]=="matrix") {
                    $subvar1.="</select>";
                }
                $subvar2.="</select>";
                $v_vsel[$side].="$subvar1$subvar2";*/
            }
            else {
                $v_vsel[$side].="<a href=\"javascript:addBody('$mm[variable_name]')\">".htmlspecialchars($mm["question"])."</a>&nbsp;";
                $v_vsel[$side].="<input type='text' name='qdata$i' onBlur=\"addBody('\''+this.form.qdata$i.value+'\'')\">";
            }
            $i++;
        }
    }
}
else {
    $v_vsel[0].="<a href=\"javascript: addBody('[spec_birthday]')\">$word[spec_birthday]</a>";
    $v_vsel[0].="<br><a href=\"javascript: addBody('[spec_last_clicked]')\">$word[spec_last_clicked1]</a>&nbsp;<input type='text' name='qdata$i' onBlur=\"addBody('\''+this.form.qdata$i.value+'\'')\" size='4'>&nbsp;$word[spec_last_clicked2]";
    $i++;
    $v_vsel[0].="<br><a href=\"javascript: addBody('[spec_not_sent]')\">$word[spec_not_sent1]</a>&nbsp;<input type='text' name='qdata$i' onBlur=\"addBody('\''+this.form.qdata$i.value+'\'')\" size='4'>&nbsp;$word[spec_not_sent2]";
    $i++;
    $v_vsel[0].="<br>$word[spec_max_explain]<br><a href=\"javascript: addBody('[spec_max_lastclick]')\">$word[spec_max_max]</a>&nbsp;<input type='text' name='qdata$i' onBlur=\"addBody('\''+this.form.qdata$i.value+'\'')\" size='4'>&nbsp;$word[spec_max_lastclick]";
    $i++;
    $v_vsel[0].="<br><a href=\"javascript: addBody('[spec_max_lastsent]')\">$word[spec_max_max]</a>&nbsp;<input type='text' name='qdata$i' onBlur=\"addBody('\''+this.form.qdata$i.value+'\'')\" size='4'>&nbsp;$word[spec_max_lastsent]";
    $i++;
    $v_vsel[0].="<br><a href=\"javascript: addBody('[spec_max_random_'+Math.round(Math.random()*10000)+']')\">$word[spec_max_max]</a>&nbsp;<input type='text' name='qdata$i' onBlur=\"addBody('\''+this.form.qdata$i.value+'\'')\" size='4'>&nbsp;$word[spec_max_random]";
    $n_year=date('Y');
    $n_month=date('m');
    $n_day=date('d');
    $n_hour=date('H');
    $v_vsel[0].="<br>$word[spec_subscribed1]<input type='text' name='q_sub_year' size='4' value='$n_year' maxlength='4'>-<input type='text' name='q_sub_month' size='2' value='$n_month' maxlength='2'>-<input type='text' name='q_sub_day' size='2' value='$n_day' maxlength='2'>:<input type='text' name='q_sub_hour' size='2' value='$n_hour' maxlength='2'> <select name='q_sub_lg'><option value='<'>$word[spec_before]</option><option value='>'>$word[spec_after]</option></select> $word[spec_subscribed2]&nbsp;<input type='button' name='subbutt1' onclick=\"addBody('[spec_subscribed]'+this.form.q_sub_lg.options[this.form.q_sub_lg.selectedIndex].value+'\''+Number(this.form.q_sub_year.value)+'-'+Number(this.form.q_sub_month.value)+'-'+Number(this.form.q_sub_day.value)+' '+Number(this.form.q_sub_hour.value)+':00:00\'')\" value='ok'>";
    $v_vsel[0].="<br>$word[spec_validated1]<input type='text' name='q_val_year' size='4' value='$n_year' maxlength='4'>-<input type='text' name='q_val_month' size='2' value='$n_month' maxlength='2'>-<input type='text' name='q_val_day' size='2' value='$n_day' maxlength='2'>:<input type='text' name='q_val_hour' size='2' value='$n_hour' maxlength='2'> <select name='q_val_lg'><option value='<'>$word[spec_before]</option><option value='>'>$word[spec_after]</option></select> $word[spec_validated2]&nbsp;<input type='button' name='subbutt2' onclick=\"addBody('[spec_validated]'+this.form.q_val_lg.options[this.form.q_val_lg.selectedIndex].value+'\''+Number(this.form.q_val_year.value)+'-'+Number(this.form.q_val_month.value)+'-'+Number(this.form.q_val_day.value)+' '+Number(this.form.q_val_hour.value)+':00:00\'')\" value='ok'>";
    $v_vsel[0].="<br>$word[spec_tstamp1]<input type='text' name='q_tst_year' size='4' value='$n_year' maxlength='4'>-<input type='text' name='q_tst_month' size='2' value='$n_month' maxlength='2'>-<input type='text' name='q_tst_day' size='2' value='$n_day' maxlength='2'>:<input type='text' name='q_tst_hour' size='2' value='$n_hour' maxlength='2'> <select name='q_tst_lg'><option value='<'>$word[spec_before]</option><option value='>'>$word[spec_after]</option></select> $word[spec_tstamp2]&nbsp;<input type='button' name='subbutt2' onclick=\"addBody('[spec_tstamp]'+this.form.q_tst_lg.options[this.form.q_tst_lg.selectedIndex].value+'\''+Number(this.form.q_tst_year.value)+'-'+Number(this.form.q_tst_month.value)+'-'+Number(this.form.q_tst_day.value)+' '+Number(this.form.q_tst_hour.value)+':00:00\'')\" value='ok'>";
    $v_vsel[0].="<br>$word[spec_message1]<input type='button' name='messbutt1' value='...' onClick='window.open(\"message_select.php?group_id=$group_id&spec=1\",\"message_select\", \"toolbar=no,location=no,directories=no,status=no,scrollbars=yes,width=650,height=500\"); return false;'> $word[spec_message2]";
    $v_vsel[0].="<br>$word[spec_ct1] <input type='button' name='messbutt2' value='...' onClick='window.open(\"message_select.php?group_id=$group_id&spec=2\",\"message_select\", \"toolbar=no,location=no,directories=no,status=no,scrollbars=yes,width=650,height=500\"); return false;'> $word[spec_ct2]";
    $megyelist="<select name='q_megye' onchange=\"javascript: addBody('[spec_megye_'+this.form.q_megye.options[this.form.q_megye.selectedIndex].value+']')\">";
    $megyelist2="<select name='q_megye2' onchange=\"javascript: addBody('[spec_ceges_megye_'+this.form.q_megye2.options[this.form.q_megye2.selectedIndex].value+']')\">";
    $res=mysql_query("select * from megye order by nev");
    if ($res && mysql_num_rows($res)) 
        while ($k=mysql_fetch_array($res)) {
            $megyelist.="<option value='$k[id]'>$k[nev]</option>";    
            $megyelist2.="<option value='$k[id]'>$k[nev]</option>";    
             
        }
    $v_vsel[1].="$word[spec_megye]&nbsp;$megyelist</select>";
    $i++;
    $v_vsel[1].="<BR>$word[spec_ceg_megye]&nbsp;$megyelist2</select>";
    $i++;
    $v_vsel[1].="<br><a href=\"javascript: addBody('[spec_telepules]')\">$word[spec_telepules]</a>&nbsp;<input type='text' name='qdata$i' onBlur=\"addBody('\''+this.form.qdata$i.value+'\'')\">";
    $roman="<option value='01'>I</option><option value='02'>II</option><option value='03'>III</option><option value='04'>IV</option><option value='05'>V</option><option value='06'>VI</option><option value='07'>VII</option><option value='08'>VIII</option><option value='09'>IX</option><option value='10'>X</option><option value='11'>XI</option><option value='12'>XII</option><option value='13'>XIII</option><option value='14'>XIV</option><option value='15'>XV</option><option value='16'>XVI</option><option value='17'>XVII</option><option value='18'>XVIII</option><option value='19'>XIX</option><option value='20'>XX</option><option value='21'>XXI</option><option value='22'>XXII</option><option value='23'>XXIII</option>\n";
    $v_vsel[1].="<br>$word[spec_kerulet]&nbsp;<select name='q_kerulet' onchange=\"javascript: addBody('[spec_kerulet_'+this.form.q_kerulet.options[this.form.q_kerulet.selectedIndex].value+']')\">$roman</select>";
    $ksh="";
    $r7=mysql_query("select distinct ksh_regio from irsz_tabla order by ksh_regio");
    while ($k7 = mysql_fetch_array($r7)) {
        $ksh .= "<option>$k7[ksh_regio]</option>";
    }
    $v_vsel[1].="<br>$word[spec_ksh]&nbsp;<select name='q_ksh' onchange=\"javascript: addBody('[spec_ksh_'+this.form.q_ksh.options[this.form.q_ksh.selectedIndex].value+']')\">$ksh</select>";
    $teltip="";
    $r7=mysql_query("select distinct teltip from irsz_tabla order by teltip");
    while ($k7 = mysql_fetch_array($r7)) {
        $teltip .= "<option>$k7[teltip]</option>";
    }
    $v_vsel[1].="<br>$word[spec_telepules_tipus]&nbsp;<select name='q_telepules_tipus' onchange=\"javascript: addBody('[spec_telepules_tipus_'+this.form.q_telepules_tipus.options[this.form.q_telepules_tipus.selectedIndex].value+']')\">$teltip</select>";
    $i++;

    /*$nest="<select name='q_enums$i' onchange=\"addBody('[spec_nest_'+this.form.q_enums$i.options[this.form.q_enums$i.selectedIndex].value+']')\"><option value=' '>$word[spec_nest]</option>";
    $res=mysql_query("select * from filter where group_id='$group_id' and id!='$id' order by name");
    if ($res && mysql_num_rows($res)) 
        while ($k=mysql_fetch_array($res)) 
            $nest.="<option value='$k[name]'>$k[name]</option>";
    $nest.="</select>";
    $v_vsel[1].="<br>$nest";*/

    $v_vsel[1].="<br>$word[spec_nest]<input type='button' name='messbutt22s' value='...' onClick='window.open(\"filter_select.php?group_id=$group_id&spec=1\",\"nest_select\", \"toolbar=no,location=no,directories=no,status=no,scrollbars=yes,width=650,height=500\"); return false;'>";
    $i++;
    $v_vsel[1].="<br><a href=\"javascript: addBody('[spec_sms]')\">$word[spec_sms]:</a><br>$word[spec_sms_explain]";
    $i++;
    $v_vsel[1].="<br><a href=\"javascript: addBody('[spec_not_sent_sms]')\">$word[spec_not_sent_sms1]</a>&nbsp;<input type='text' name='qdata$i' onBlur=\"addBody('\''+this.form.qdata$i.value+'\'')\" size='4'>&nbsp;$word[spec_not_sent_sms2]";
    $v_vsel[1].="<br>$word[spec_sms1]<input type='button' name='messbutt1s' value='...' onClick='window.open(\"sms_message_select.php?group_id=$group_id&spec=1\",\"sms_select\", \"toolbar=no,location=no,directories=no,status=no,scrollbars=yes,width=650,height=500\"); return false;'> $word[spec_sms2]";
    $v_vsel[1].="<br>$word[spec_sms_message1]<input type='button' name='messbutt1' value='...' onClick='window.open(\"sms_message_select.php?group_id=$group_id&spec=2\",\"sms_message_select\", \"toolbar=no,location=no,directories=no,status=no,scrollbars=yes,width=650,height=500\"); return false;'> $word[spec_sms_message2]";
    $uglist="<select name='q_ug' onchange=\"javascript: addBody('[spec_ug_'+this.form.q_ug.options[this.form.q_ug.selectedIndex].value+']')\"><option value=''>$word[spec_ug]</option>";
    $res=mysql_query("select * from user_group where group_id='$group_id' order by name");
    if ($res && mysql_num_rows($res)) 
        while ($k=mysql_fetch_array($res)) 
            $uglist.="<option value='$k[id]'>$k[name]</option>";    
    $v_vsel[1].="<br>$uglist</select>";
    $bblist="<select name='score_bounced' onchange=\"javascript: addBody('[spec_score_bounced_'+this.form.score_bounced.options[this.form.score_bounced.selectedIndex].value+']')\"><option value='0'>$word[spec_score_bounced]</option>";
    for ($i=0;$i<6;$i++)
        $bblist.="<option value='$i'>".$word["spec_score_bounced$i"]."</option>";    
    $v_vsel[1].="<br>$bblist</select>";
    $tlist="<select name='score_trust' onchange=\"javascript: addBody('[spec_score_trust_'+this.form.score_trust.options[this.form.score_trust.selectedIndex].value+']')\"><option value=''>$word[spec_score_trust]</option>";
    $res=mysql_query("select * from user_group where group_id='$group_id' order by name");
    for ($i=0;$i<5;$i++)
        $tlist.="<option value='$i'>".$word["spec_score_trust$i"]."</option>";    
    $v_vsel[1].="<br>$tlist</select>";
    $v_vsel[1].="<br>$word[spec_affiliate1]<input type='button' name='messbutt1' value='...' onClick='window.open(\"affiliate_select.php?group_id=$group_id&spec=1\",\"affiliate_select\", \"toolbar=no,location=no,directories=no,status=no,scrollbars=yes,width=650,height=500\"); return false;'> $word[spec_affiliate2]";
    $i++;
    $v_vsel[1].="<br><a href=\"javascript: addBody('[spec_no_message]')\">$word[spec_no_message]</a>";
}

print "
<script language='JavaScript'>
function addBody (text) {
    parent.addBody(text);
}
function enumwindow(demog_id) {
    window.open(\"$_MX_var->baseUrl/mygroups15_edit_select.php?group_id=$group_id&demog_id=\"+demog_id, \"m_d_i\", \"width=600,height=300,left=150,top=200,scrollbars=yes,resizable=yes\"); return false;
}
</script>
<TABLE cellSpacing=0 cellPadding=0 width='100%' border=0>
<form name='dummy'>
<tr>
<td class=BACKCOLOR valign='top' align='left' width='50%'><span class='szoveg'>
$v_vsel[0]</span></td>
<td class=BACKCOLOR valign='top' align='left' width='50%'><span class='szoveg'>
$v_vsel[1]</span></td>
</tr>
</form>
</table>\n";

include "footer.php";
?>
