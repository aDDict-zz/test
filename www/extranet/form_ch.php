<?
include "auth.php";
include "decode.php";
$weare=34;
$subweare="change";
include "cookie_auth.php";
include "common.php";
include "_form.php";

foreach ($_POST as $var=>$val) {
    $$var = $val;
}
foreach ($_GET as $var=>$val) {
    $$var = slasher($val);
}

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

$form_id=slasher($form_id);
$res=mysql_query("select * from form where id='$form_id' and group_id='$group_id'");
if ($res && mysql_num_rows($res)) {
    $formdata=mysql_fetch_array($res);
}
else {
    $form_id=0;
    $formdata=array();
}

$error=array();

if (isset($_POST["enter"])) {
    form_enter();
    $res=mysql_query("select * from form where id='$form_id' and group_id='$group_id'");
    if ($res && mysql_num_rows($res)) {
        $formdata=mysql_fetch_array($res);
    }
}

include "menugen.php";
include "./lang/$language/form.lang";
if ($form_id) {
    $stat_text=$word["iform_change"];
}
else {
    $stat_text=$word["iform_new"];
}

$_MX_form = new MxForm($group_id,"",0,0);
$_MX_form->MakeMenu($subweare,$formdata);

if (isset($_GET["delel"])) {
    $query="delete from form_endlink where id=$delel";
    mysql_query($query);
}
if (isset($_GET["addel"])) {
    $query="insert into form_endlink (form_id) values ($form_id)";
    mysql_query($query);
}
print "<form method='post' action='form_ch.php' style='border:0;margin:0;'>
       <input type='hidden' name='enter' value='yes'>
       <input type='hidden' name='form_id' value='$form_id'>
       <input type='hidden' name='group_id' value='$group_id'>
       <TABLE cellSpacing=1 cellPadding=0 width=\"100%\" class='bgcolor hlines' border=0>\n";

if ($form_id) {
    print "<tr>
    <td bgcolor='#ffffff' align=right colspan='6'><span class=szoveg>
        <a href='form_ch.php?form_id=$form_id&group_id=$group_id&addel=1'>$word[iform_new_el]</a>&nbsp;&nbsp;
       <a href='#' onClick='window.open(\"form_element_verify.php?form_id=$form_id&group_id=$group_id\", \"fefr\", \"width=650,height=500,scrollbars=yes,resizable=yes\"); return false;'>$word[fe_verify]</a>&nbsp;</span></td>
       </tr>\n";
}

$alldata=array(
"html_name"=>"input",
"title"=>"input",
"live_url"=>"input",
"header"=>"textarea",
"footer"=>"textarea",
"intro_page"=>"textarea",
"quitted_page"=>"textarea",
"filled_out_page"=>"textarea",
"invalid_cid"=>"textarea",
"form_inactive"=>"textarea",
"landing_page"=>"textarea",
"landing_page_inactive"=>"textarea",
"updater"=>"yesno",
"hidden_subscribe"=>"yesno",
"pages"=>"input",
"numbering"=>"yesno",
"affiliate_id"=>"input",
"collect_page"=>"input",
"multigroup"=>"input",
"groups"=>"textarea",
"automatic_group_subscribe"=>"yesno10",
"megszolitas"=>"yesnotegezmagaz",
"save_data_to_cookie"=>array("no", "yes", "maxima"),
"prev_button_text"=>"input",
"next_button_text"=>"input",
"prev_button_url"=>"input",
"next_button_url"=>"input",
"ga_code"=>"textarea",
"ga_virtual"=>"input",
"code_in_question"=>"yesnociq",
);
//"automatic_group_subscribe"=>"yesno10","megszolitas"=>"yesnotegezmagaz","admeasure"=>"input");

if ($form_id && empty($error)) {
    $res=mysql_query("select * from form where id='$form_id'");
    if ($res && mysql_num_rows($res)) {
        $l=mysql_fetch_array($res);
    }
}
if (!$form_id && empty($error)) {
    $l["header"]="<html><head><title>{TITLE}</title></head><body>";
    $l["footer"]="</body></html>";
    $l["hidden_subscribe"]="yes";
    $l["pages"]="1";
    $l["collect_page"]="http://192.168.0.107/maxima/form_collect.php";
    $l["landing_page"]="$l[header]\n\n$l[footer]";
    $l["landing_page_inactive"]="$l[header]\n\n$l[footer]";
    $l["ga_virtual"]="regisztracio";
}
$errorlist="";
for ($i=0;$i<count($error);$i++) {
    $errorlist.=htmlspecialchars($word["$error[$i]"])."<br>";
}
if (!empty($errorlist)) {
    print "<tr><td colspan='2' bgcolor='white'><span class='szoveg'>$errorlist&nbsp;</span></td></tr>\n";
}
reset($alldata);
$counter = 0;
while (list($data,$widget)=each($alldata)) {
    if ($counter%2)
        $bgrnd="class='bgvilagos2 trborder'";
    else
        $bgrnd="bgcolor=white class='trborder'";

    $counter++;    
    
    if (isset($l) && isset($l["$data"])) {
        $value=htmlspecialchars($l["$data"]);
    }
    elseif (isset($_POST) && isset($_POST["$data"])) {
        $value=htmlspecialchars(slasher($_POST["$data"],0));
    }
    else {
        $value="";
        if ($data=="affiliate_id") {
            $value="0"; 
        }
    }
    if (isset($word["ipagech_$data"]))
        $qtext=htmlspecialchars($word["ipagech_$data"]);
    else
        $qtext=htmlspecialchars($word["ich_$data"]);
    if (in_array($data,array("landing_page","landing_page_inactive","intro_page","filled_out_page","invalid_cid","quitted_page","form_inactive"))) {
        $qtext.="<br><a href='form_landview.php?form_id=$form_id&group_id=$group_id&lpview=$data' target='_blank'>Előnézet</a>";
    }
    if (is_array($widget)) {
        $qw="<select name='$data'>";
        foreach ($widget as $opt) {
            $sel=$opt==$value?" selected":"";
            $qw.="<option value='$opt'$sel>" . htmlspecialchars($word["ich_${data}_${opt}"]) . "</option>";
        }
        $qw.="</select>";
    }
    elseif ($widget=="input") {
        $qw="<input class=formframe type='text' name='$data' value=\"$value\" style='width:570px;'>";
    }
    elseif ($widget=="textarea") {
        $qw="<textarea class='formframe' style='width:570px; height:200px;' name='$data' wrap='virtual'>$value</textarea>";
    }
    elseif ($widget=="yesnotegezmagaz") {
        $value=="tegez"?$ch="checked":$ch="";
        $qw="<input type='checkbox' name='$data' $ch value='tegez'>";
    }
    elseif ($widget=="code_in_question") {
        $value=="yes"?$ch="checked":$ch="";
        $qw="<input type='checkbox' name='$data' $ch value='yes'>";
    }
    elseif ($widget=="yesno10") {
        $value=="1"?$ch="checked":$ch="";
        $qw="<input type='checkbox' name='$data' $ch value='1'>";
    }
    else {
        $value=="yes"?$ch="checked":$ch="";
        $qw="<input type='checkbox' name='$data' $ch value='yes'>";
    }
    echo "<tr>
        <td valign='top' bgcolor='white' $bgrnd><span class='szoveg'>$qtext</span></td>
        <td valign='top' bgcolor='white' $bgrnd><span class='szoveg'>$qw</span></td>
        </tr>\n";    
}
//print endlinks
$res=mysql_query("select * from form_endlink where form_id=$form_id");
if ($res && mysql_num_rows($res)) {
    echo "<tr><td colspan='2' class='bgkiemelt2'><span class='szovegvastag'>$word[form_endlinks]</span></td></tr>";
    while ($w=mysql_fetch_array($res)) {
        if ($w["dependency"]) $style_em="style='color: #993300; font-weight: bold;'"; else $style_em="";
        echo "<tr>
            <td valign='top' bgcolor='white'><span class='szoveg'><input class=formframe type='text' name='eltitle_$w[id]' value=\"$w[title]\" size='35'>
            <br /><a name='form_endlink_$w[id]' $style_em href='form_element_options.php?form_endlink_id=$w[id]&group_id=$group_id&form_id=$form_id'>$word[fe_depend]</a> 
            <br /><a href='form_landview.php?form_id=$form_id&group_id=$group_id&elid=$w[id]' target='_blank'>El&#337;nézet</a>        <br /><a href='form_ch.php?form_id=$form_id&group_id=$group_id&delel=$w[id]'>$word[form_specdel]</a>        
            </span></td>
            <td valign='top' bgcolor='white'><span class='szoveg'><textarea class='formframe' name='endlink_$w[id]' wrap='virtual' style='width:570px; height:200px;'>$w[html]</textarea></span></td>
            </tr>\n"; 
    }
}

echo "<tr>
    <td align=center colspan=2>
    <input type=submit class='tovabbgomb' value='$word[submit3]'>
    </td>
    </tr></form>";
include "footer.php";

// \\ // \\ // \\ // \\ // \\ // \\ // \\ // \\ // \\ // \\ // \\ // \\ // \\ // \\ // \\ // \\ // \\ // \\ // \\ // \\ // \\ 

function form_enter() {
    global $_MX_var,$group_id, $error, $form_id, $_POST;

    $alldata=array("html_name"=>"input","title"=>"input","live_url"=>"input","updater"=>"yesno",
                   "header"=>"textarea","footer"=>"textarea","hidden_subscribe"=>"yesno",
                   "pages"=>"input","numbering"=>"yesno","affiliate_id"=>"input","landing_page"=>"textarea","intro_page"=>"textarea",
                   "filled_out_page"=>"textarea","invalid_cid"=>"textarea","form_inactive"=>"textarea","quitted_page"=>"textarea","collect_page"=>"input",
                   "multigroup"=>"input","groups"=>"textarea","landing_page_inactive"=>"textarea",
                   "automatic_group_subscribe"=>"yesno10","megszolitas"=>"yesnotegezmagaz","save_data_to_cookie"=>array("no","yes","maxima"),
                   "prev_button_text"=>"input","next_button_text"=>"input","prev_button_url"=>"input","next_button_url"=>"input",
                   "ga_code"=>"textarea",
                   "ga_virtual"=>"input",
                   "code_in_question"=>"yesnociq");
                   //"automatic_group_subscribe"=>"yesno10","megszolitas"=>"yesnotegezmagaz","admeasure"=>"input");

    $set="";
    $glue="";
    foreach ($_POST as $pvar=>$pvalue) {
        if (ereg("^eltitle_([0-9]+)$",$pvar,$prg)) {
            mysql_query("update form_endlink set title='$pvalue' where id='$prg[1]' and form_id='$form_id'");
        }
        if (ereg("^endlink_([0-9]+)$",$pvar,$prg)) {
            mysql_query("update form_endlink set html='$pvalue' where id='$prg[1]' and form_id='$form_id'");
        }
    } 
    reset($alldata);
    while (list($data,$widget)=each($alldata)) {
        if (isset($_POST) && isset($_POST["$data"])) {
            $value=$_POST["$data"];
        }
        else {
            $value="";
        }
        if (($data=="updater" || $data=="hidden_subscribe") && $value!="yes") {
            $value="no";
        }
        if ($data=="pages") {
            $value=abs(intval($value));
            if ($value<1 || $value>200) {
                $error[]="e_$data";
            }
        }    
        if ($data=="numbering" && $value!="yes") {
            $value="no";
        }
        if (strlen($value)==0 && $data!="hidden_subscribe" && $data!="multigroup" && $data!="automatic_group_subscribe" && $data!="groups" && $data!="code_in_question" && $data!="megszolitas" && $data!="ademasure" && $data!="invalid_cid" && $data!="form_inactive" && $data!="filled_out_page" && $data!="quitted_page" && $data!="intro_page" && $data!="prev_button_text" && $data!="next_button_text" && $data!="prev_button_url" && $data!="next_button_url" && $data!="live_url" && $data!="ga_code" && $data!="ga_virtual") {
            $error[]="e_$data";
        }
        if ($data=="affiliate_id" && !ereg("^[0-9]+$",$value)) {
            $error[]="e2_aff";
        }
        if ($data=="collect_page" && strlen($value)>0 && !ereg("^http://",$value)) {
            $value="http://$value";
        }
        if (is_array($widget)) {
            if (!in_array($value,$widget)) {
                $value=$widget[0];
            }
        }
        elseif ($widget=="yesnotegezmagaz" && $value!="tegez") {
            $value="magaz";
        }
        elseif ($widget=="code_in_question" && $value!="yes") {
            $value="no";
        }
        elseif ($widget=="yesno10" && $value!="1") {
            $value="0";
        }
        $set.=$glue."$data='".slasher($value)."'";
        $glue=",";        
    }
    if (count($error)==0) {
        if ($form_id) {
            mysql_query("update form set $set where id='$form_id' and group_id='$group_id'");
        }
        else {
            $res=mysql_query("insert into form set $set,group_id='$group_id',date=now()");
            //print("<br>insert into form set $set,group_id='$group_id',date=now()");
            if ($res) {
                $form_id=mysql_insert_id();
            }
        }
        if (count($error)==0) {
            // This is here to support the changing the number of pages
            $pages = $_POST['pages'];
            // First, fields on deleted pages should be removed from the form
            mysql_query("DELETE FROM form_element
                          WHERE form_id = '$form_id' AND page > '$pages'");
            // Delete the boxes
            mysql_query("DELETE FROM form_page_box
                          WHERE form_id = '$form_id' AND page_id > '$pages'");
            // Now delete the pages
            mysql_query("DELETE FROM form_page
                          WHERE form_id = '$form_id' AND page_id > '$pages'");
//            header ("location: form.php?form_id=$form_id&group_id=$group_id");
//            exit;
        }
    }
}
?>
