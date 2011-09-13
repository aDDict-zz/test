<?
include "auth.php";
include "decode.php";
$weare=34;
include "cookie_auth.php";
include "common.php";
$language=select_lang();
include "./lang/$language/form.lang";
include "./lang/$language/filter.lang";

$mres = mysql_query("select title,num_of_mess,membership 
                     from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and (membership='owner' or membership='moderator' $admin_addq)
                     and user_id='$active_userid'");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else {
    exit; 
}
$sword=$_REQUEST["sword"];
$off=$_REQUEST["off"];
$res=mysql_query("select count(d.id) from vip_demog vd,demog d where vd.group_id='$group_id' and vd.demog_id=d.id and (d.question like '%$sword%' or d.variable_name like '%$sword%')");
if ($res && mysql_num_rows($res)) {
    $count=mysql_result($res,0,0);
}
$pagenum=get_http("pagenum","");
if ($_REQUEST["perpage"]==NULL) $perpage=15; else $perpage=$_REQUEST["perpage"];
$pages_total=floor($count/$perpage)+1;
if ($pagenum) $off=$pagenum-1;
if ($off>$pages_total-1) $off=$pages_total-1; 
if ($off<0) $off=0;
$limstart=$off*$perpage;
$title=$rowg["title"];
$active_membership=$rowg["membership"];

$form_id=slasher($form_id);
$res=mysql_query("select * from form where id='$form_id' and group_id='$group_id'");
if ($res && mysql_num_rows($res))
    $formdata=mysql_fetch_array($res);
else
    exit;

$error=array();
    
printhead();

if (isset($changed))
    $errorlist="$changed $word[fe_changed]";
else
    $errorlist="";

echo "<tr>
<td colspan='4' bgcolor='white'><span class='szoveg'>$errorlist&nbsp;</span></td>
</tr>\n";

$fsel=array();
$r2=mysql_query("select demog_id,page,box_id,max_num_answer,rotate from form_element where form_id='$form_id'");
logger($query,$group_id,"","form_id=$form_id","form_element");
if ($r2 && mysql_num_rows($r2)) {
    while ($z=mysql_fetch_array($r2)) {
        $box=$z["box_id"]=='0'? '': $z["box_id"];
        $fsel["$z[demog_id]"]=$z["page"] . $box;
        $fmna["$z[demog_id]"]=$z["max_num_answer"];        
        $frot["$z[demog_id]"]=$z["rotate"];                
    }
}

$index=0;
$res=mysql_query("select vd.id,vd.demog_id,d.variable_name,d.question,variable_type,multiselect from vip_demog vd,demog d where vd.group_id='$group_id' and vd.demog_id=d.id and (d.question like '%$sword%' or d.variable_name like '%$sword%') limit $limstart,$perpage");
if ($res && $count=mysql_num_rows($res)) {
    echo "<tr><td class='bgkiemelt2' align=left width=20%><span class=szovegvastag>$word[t_varname]</span></td>
          <td class='bgkiemelt2' align=left width=30%><span class=szovegvastag>$word[t_question]</span></td>
          <td class='bgkiemelt2' align=left width=25%><span class=szovegvastag>$word[fe_page]<br>(X=$word[fe_nopage])</span></td>
          <td class='bgkiemelt2' align=left width=25%><span class=szovegvastag>$word[max_num_of_answers] / $word[rotate]</td>          
          </tr>\n";
    while ($k=mysql_fetch_array($res)) {
        if ($index%2)
           $bgrnd="class=bgvilagos2";
        else
           $bgrnd="bgcolor=white";
        $varname=htmlspecialchars($k["variable_name"]);
        $question=htmlspecialchars($k["question"]);
        if (!isset($fsel["$k[demog_id]"])) {
            $cpagebox=0;
            $sel0="checked";
        }
        else {
            $cpagebox=$fsel["$k[demog_id]"];
            $sel0="";
        }
		$cpbox='"'.$cpagebox.'"';
		$pagelist="&nbsp;<select name='demog_id[$k[demog_id]]' style='width:80px;' onchange='get_boxes($k[demog_id],this.options[this.selectedIndex].text);'>";
        $pagelist.="<option $sel0 value=0>X</option>";
        $pi=0;
        for ($i=1;$i<=$formdata["pages"];$i++) 
		{
            $cpagebox==$i?$sel="selected":$sel="";
			$pagelist.="<option value='$i' $sel>$i</option>";			
			if ($sel=="selected") $pi=$i;
        }
        $optionlist="<div id='divr_id_$k[demog_id]'>";
        $query=mysql_query("SELECT boxes,page_id FROM form_page WHERE group_id='$group_id' AND form_id='$form_id' group by page_id order by page_id");
			if ($query && mysql_num_rows($query)) 
            {
                while ($row=mysql_fetch_array($query)) {                
                    if ($row["page_id"]==$pi) $dis="display: block"; else $dis="display: none";
                    $optionlist.="<div id='divr_id_$k[demog_id]_$row[page_id]' align='left' style='$dis; width: 100%; '>";
	    			for ($j = 0; $j < $row['boxes']; ++$j) 
    				{
				    	$box=chr(ord('A')+$j);
			    		$cpagebox=="$row[page_id]$box"?$selc="checked":$selc="";
                        $optionlist.="<input type='radio' value='$row[page_id]$box' $selc name='demogr_id[$k[demog_id]]'>$row[page_id]$box&nbsp;";
                    }
                    $optionlist.="</div>";
                }
			}
        $max_num_answer="<td width='25%' $bgrnd valign=top align=left>&nbsp;";
        if (($k["variable_type"]=="matrix" || $k["variable_type"]=="enum") && $k["multiselect"]=="yes") {
            $max_num_answer="<td $bgrnd valign=top align=center><input type='text' name='max_num_answer[$k[demog_id]]' value='".$fmna["$k[demog_id]"]."' size='3'>";
        }
        $rotate="";
        if ($k["variable_type"]=="matrix" || $k["variable_type"]=="enum") {
            if ($frot["$k[demog_id]"]=='yes') $rch="checked"; else $rch="";
            $rotate="<br><input name='rotate[$k[demog_id]]' type='checkbox' $rch>";
        }            
        echo "	<tr>
              	<td $bgrnd style= 'word-wrap:break-word;' valign=top align=left width=10%><span class=szoveg>$varname</span></td>
              	<td $bgrnd valign=top align=left width=50%><span class=szoveg>$question</span>$is_multiselect</td>
             	<td $bgrnd align=left width=25%>$pagelist</select>";

        echo "$optionlist</div></td>";

        echo $max_num_answer."$rotate</td></tr>";
        $index++;
    }
}
else {
    echo "<tr>
          <td bgcolor='white' align=right colspan='4'><span class=szoveg>
          <a href='#' onClick='window.open(\"form_select.php?form_id=$form_id\", \"dfr\", \"width=500,height=450,scrollbars=yes,resizable=yes\"); return false;'>$word[fe_new]</a>&nbsp;</span></td>
          </tr>";
}
echo "<tr>
        <td align=center class=bgkiemelt2 colspan=4>
        <input type=submit class='tovabbgomb' value='$word[submit3]'> <input type='button' class='tovabbgomb' value='$word[close]' onclick='window.opener.location=\"form_elements.php?group_id=$group_id&form_id=$form_id\";window.close();'>
        </td>
        </tr></form>";

printfoot();
include "footer.php";


function printhead() {

    global $_MX_var,$stat_text,$userlist_yn,$filt_demog_options,$group_id,$pagenum,$word, $formdata, $form_id, $group_id, $language;


$_MX_popup = 1;
include "menugen.php";

?>

<script src="xmlreq.js" type="text/javascript"></script>
<script>

function get_boxes(demog_id,comboseltext) {

    var dvcont=document.getElementById("divr_id_"+demog_id).childNodes;
    for (var c=1; c<=dvcont.length; ++c) {
        var dvo=document.getElementById("divr_id_"+demog_id+"_"+c).childNodes;
        for (var q=0; q<dvo.length; ++q) {
            if (dvo[q].tagName=="INPUT") {
                dvo[q].checked=false;
            }
        }            
        document.getElementById("divr_id_"+demog_id+"_"+c).style.display="none";
    }
    if (comboseltext!="X") document.getElementById("divr_id_"+demog_id+"_"+comboseltext).style.display="block";
}

function get_boxes_response_org() {
    var resp = decodeURIComponent(get_boxes_request.response);
    resp=resp.replace(/'/gi, "");
	temparr=resp.split("**");
    var di = 'divr_id['+temparr[0]+']';	
    document.getElementById(di).innerHTML="";
    if (temparr[1]!="") {
        var tmp=temparr[1].split("|:|");
        for (var x=0; x<tmp.length; ++x) {
            var tmpo=tmp[x].split("||");
            var ce=document.createElement("<input>");
            ce.type = "radio";
            ce.name = "demogr_id["+temparr[0]+"]";
            ce.value = tmpo[0];
            document.getElementById("form_in").appendChild(ce);

            if (tmpo[1]!="") ce.checked='true';        
            var ce=document.createElement("<span>");
            ce.innerHTML=tmpo[0];
            document.getElementById(di).appendChild(ce);        
        }
    	document.getElementById('divr_id['+temparr[0]+']').style.display="block";        
    } else {
        document.getElementById('divr_id['+temparr[0]+']').style.display="none";    
    }   
}

</script>
<?php
$sword=$_REQUEST["sword"];
$res=mysql_query("select count(d.id) from vip_demog vd,demog d where vd.group_id='$group_id' and vd.demog_id=d.id and (d.question like '%$sword%' or d.variable_name like '%$sword%')");
if ($res && mysql_num_rows($res)) {
    $count=mysql_result($res,0,0);
}
$off=$_REQUEST["off"];
if ($_REQUEST["perpage"]==NULL) $perpage=15; else $perpage=$_REQUEST["perpage"];
$pages_total=floor($count/$perpage);
if ($count % $perpage >0) $pages_total++;
if ($pagenum) $off=$pagenum-1;
if ($off>$pages_total-1) $off=$pages_total-1; 
if ($off<0) $off=0;
$limstart=$off*$perpage;
$prevpage=$off-1;
$pagenum=$off+1;
$lastpage=$pages_total-1;
$sortsel="<select name='sort' onchange='window.opener.location=\"form_select.php?group_id=$group_id&form_id=$form_id\";'>";
$dgroupsel="<select name='dgroup' style='background-color:#fcc;' onchange='window.opener.location=\"form_select.php?group_id=$group_id&form_id=$form_id\";'>\n";
echo "
<TABLE cellSpacing=0 cellPadding=1 width=\"100%\" class=\"bgcolor\" border=0>
        <TR>
        <TD>
        <TABLE cellSpacing=1 cellPadding=0 width=\"100%\" bgColor=$_MX_var->main_table_border_color border=0>
        <tr>
		<td class=bgkiemelt2 align=left colspan=4><span class=szovegvastag>&quot;".htmlspecialchars($formdata["title"])."&quot $word[iform_title] &gt; $word[fe_new]</span></td>
		</tr>\n";

   print "<tr><td colspan=4><table border='0' cellspacing='0' cellpadding='0' width='100%'>
           <tr>
           <td>
           <table border='0' cellspacing='0' cellpadding='0' width='100%'>
           <tr>
    	   <td class='formmezo' align='left' width='33%'>
	       <table border='0' cellspacing='0' cellpadding='0'>

	       <tr>";
    if ($off>0) {
        print "<td nowrap align='right'>
               <a href='form_select.php?form_id=$form_id&group_id=$group_id&off=0&perpage=$perpage&sword=$sword'><img src='$_MX_var->application_instance/gfx/down2.gif' width='20' height='14' border='0'></a>
               <a href='form_select.php?form_id=$form_id&group_id=$group_id&off=$prevpage&perpage=$perpage&sword=$sword'><img src='$_MX_var->application_instance/gfx/down1.gif' width='20' height='14' border='0'></a>
               </td>";
    }
    else {
        print "<td nowrap align='right'>&nbsp;&nbsp;
               <img src='$_MX_var->application_instance/gfx/down2_dead.gif' width='20' height='14' border='0'>
               <img src='$_MX_var->application_instance/gfx/down1_dead.gif' width='20' height='14' border='0'>
               </td>";
    }
    print "<form action='form_select.php'><input type='hidden' name='form_id' value='$form_id'>
	<input type='hidden' name='group_id' value='$group_id'><input type='hidden' name='perpage' value='$perpage'><input type='hidden' name='sword' value='$sword'><td nowrap align='right'><input type='text' name='pagenum' size='3' maxlength='3' value='$pagenum'></td></form>
           <td nowrap class='formmezo'>&nbsp;/ $pages_total</td>";
    if ($off<$pages_total-1) {
        print "<td nowrap align='right'>
               <a href='form_select.php?form_id=$form_id&group_id=$group_id&off=$pagenum&perpage=$perpage&sword=$sword'><img src='$_MX_var->application_instance/gfx/up1.gif' width='20' height='14' border='0'></a>
               <a href='form_select.php?form_id=$form_id&group_id=$group_id&off=$lastpage&perpage=$perpage&sword=$sword'><img src='$_MX_var->application_instance/gfx/up2.gif' width='20' height='14' border='0'></a>
               </td>";
    }
    else {
        print "<td nowrap align='right'>&nbsp;&nbsp;
               <img src='$_MX_var->application_instance/gfx/up1_dead.gif' width='20' height='14' border='0'>
               <img src='$_MX_var->application_instance/gfx/up2_dead.gif' width='20' height='14' border='0'>
               </td>";
    }

    print "</tr>
           </table>
           </td>
           </tr>
           </table>
           </td>
    	   <td class='formmezo' align='center' width='33%'>$hnoperp
           <table border='0' cellspacing='0' cellpadding='0'>
           <tr> 
           <td nowrap class='formmezo' align='center'>$word[view]:</td>
           <form action='form_select.php'><input type='hidden' name='form_id' value='$form_id'><td nowrap align='center'>
			<input type='hidden' name='group_id' value='$group_id'><input type='hidden' name='sword' value='$sword'><input type='hidden' name='pagenum' value='$pagenum'><input type='text' name='perpage' value='$perpage' size='3' maxlength='3'></td></form>
           <td nowrap class='formmezo' align='center'> $word[demog_perpage]</td>
           </tr>
           </table>
           </td>
    	   <td class='formmezo' align='right' width='33%'>
           <table border='0' cellspacing='0' cellpadding='0'>$hnosort
           <tr> 
           <td nowrap class='formmezo'>$word[t_varname],$word[t_question]:</td>";
			if ($sword!=NULL)
           		print "<form action='form_select.php'><td nowrap><input type='hidden' name='form_id' value='$form_id'>
				<input type='hidden' name='group_id' value='$group_id'><input type='hidden' name='perpage' value='$perpage'><input type='hidden' name='pagenum' value='$pagenum'><input style='BACKGROUND-COLOR: rgb(255, 204, 204);' type='text' name='sword' value='$sword' size='18'></td></form>";
			else
				print "<form action='form_select.php'><td nowrap><input type='hidden' name='form_id' value='$form_id'>
				<input type='hidden' name='group_id' value='$group_id'><input type='hidden' name='perpage' value='$perpage'><input type='hidden' name='pagenum' value='$pagenum'><input type='text' name='sword' value='$sword' size='18'></td></form>";
    print "</tr>
           </table>
           </td>
           </tr>
           </table></td></tr>\n
			<form id='form_in' name='form_in' method='post' action='form_in.php'>
			<input type='hidden' name='action' value='addtoform'>
			<input type='hidden' name='form_id' value='$form_id'>
			<input type='hidden' name='group_id' value='$group_id'>
			<input type='hidden' name='perpage' value='$perpage'>
			<input type='hidden' name='sword' value='$sword'>
			<input type='hidden' name='pagenum' value='$pagenum'>";


}

function printfoot() {

    echo "</table>
        </td>
        </tr>
        </table>
</body>
    </html>\n";
}

