<?
include "auth.php";
$weare=34;
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
    exit; 
}

$title=$rowg["title"];
$active_membership=$rowg["membership"];

$form_id=slasher($form_id);
$res=mysql_query("select * from form where id='$form_id' and group_id='$group_id'");
if ($res && mysql_num_rows($res))
    $formdata=mysql_fetch_array($res);
else
    exit;

$data_forward_id=slasher($data_forward_id);
$res=mysql_query("select id from data_forward where id='$data_forward_id' and form_id='$form_id'");
if (!($res && mysql_num_rows($res)))
    $data_forward_id=0;

$error=array();

$alldata=array("name"=>"input","active"=>"checkbox","connect_data"=>"input","connect_username"=>"input","connect_password"=>"input","connect_netloc"=>"input","connect_realm"=>"input");

include "menugen.php";
include "./lang/$language/form.lang";
if ($enter=="yes")
    data_forward_enter();

if ($data_forward_id) {
    $stat_text=$word["dg_change"];
}
else {
    $stat_text=$word["dg_new"];
}
    
$_MX_form = new MxForm($group_id,"",0,0);
$_MX_form->MakeMenu("data_forward",$formdata,$stat_text,"data_forward.php?group_id=$group_id&form_id=$form_id");

if ($data_forward_id && count($error)==0) {
    $res=mysql_query("select * from data_forward where id='$data_forward_id' and form_id='$form_id'");
    if ($res && mysql_num_rows($res)) {
        $l=mysql_fetch_array($res);
    }
}
$errorlist=implode("<br>",$error);

echo "<form method='post' action='data_forward_ch.php' style='border:0;padding:0;'>
      <TABLE cellSpacing=1 cellPadding=0 width=\"100%\" class='bgcolor' border=0>
      <input type='hidden' name='enter' value='yes'>
      <input type='hidden' name='data_forward_id' value='$data_forward_id'>
      <input type='hidden' name='group_id' value='$group_id'>
      <input type='hidden' name='form_id' value='$form_id'>
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
    if ($widget=="input") {
        $qw="<input class=formframe type='text' name='$data' value=\"$value\" size='35'>";
    }
    elseif ($widget=="checkbox") {
        if ($value=="yes") {
            $chk="checked";
        }
        else {
            $chk="";
            $value="no";
        }
        $qw="<input type='checkbox' name='$data' value=\"yes\" $chk>";
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
$inforw=array();
$r=mysql_query("select form_element_id from data_forward_demog where data_forward_id='$data_forward_id'");
while ($k=mysql_fetch_array($r)) {
    $inforw[]=$k["form_element_id"];
}
$col=array();
$ci=0;

$res=mysql_query("select fe.id,d.question as dq,fe.question as feq
                  from form_element fe left join demog d on d.id=fe.demog_id where fe.form_id='$form_id' and 
                  fe.widget not in ('separator','comment','homepage') order by fe.sortorder");
if ($res && mysql_num_rows($res)) {
    while ($k=mysql_fetch_array($res)) {
        $index=$ci%3;
        if (in_array($k["id"],$inforw)) { $sel="checked"; } else { $sel=""; }
        if (empty($k["feq"])) { $question=$k["dq"]; } else { $question=$k["feq"]; }
        $col[$index].="<input type='checkbox' name='dgrp[$k[id]]' $sel value='1'> ".htmlspecialchars($question)." <br>";
        $ci++;
    }
}

echo "<tr>
    <td align=center colspan=2><table width='100%' cellpadding='2' cellspacing='1' border='0'>
    <tr>
    <td width='33%' bgcolor='white' valign='top'><span class='szoveg'>$col[0]</span></td>
    <td width='33%' class='bgwhite' valign='top'><span class='szoveg'>$col[1]</span></td>
    <td width='33%' class='bgwhite' valign='top'><span class='szoveg'>$col[2]</span></td>
    </tr></table></td>
    </tr>
    <tr>
    <td align=center colspan=2>
    <input type=submit class='tovabbgomb' value='$word[submit3]'>
    </td>
    </tr></table></form>";
include "footer.php";
  
// -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- //  

function data_forward_enter() {
    global $_MX_var,$group_id, $error, $data_forward_id, $_POST, $word, $alldata,$form_id;

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
        if ($widget=="checkbox" && $value!="yes") {
            $value="no";
        }
        if (strlen($value)==0 && in_array($data,array("name","connect_data"))) {
            $error[]=$word["dfe_$data"];
        }
        $set.=$glue."$data='".slasher($value)."'";
        $glue=",";
    }
    if (count($error)==0) {
        if ($data_forward_id) {
            mysql_query("update data_forward set $set where id='$data_forward_id' and form_id='$form_id'");
        }
        else {
            $res=mysql_query("insert into data_forward set $set,form_id='$form_id'");
            if ($res) {
                $data_forward_id=mysql_insert_id();
            }
        }
        $error[]=$word["dfe_success"];
        if ($data_forward_id) {
            $res=mysql_query("select id from form_element where form_id='$form_id'");
            if ($res && mysql_num_rows($res)) {
                while ($k=mysql_fetch_array($res)) {
                    if (isset($_POST["dgrp"]["$k[id]"])) {
                        $r2=mysql_query("select id from data_forward_demog where form_element_id='$k[id]' and data_forward_id='$data_forward_id'");
                        if (!($r2 && mysql_num_rows($r2))) {
                           mysql_query("insert into data_forward_demog set form_element_id='$k[id]',data_forward_id='$data_forward_id'");
                        }
                    }
                    else {
                        mysql_query("delete from data_forward_demog where form_element_id='$k[id]' and data_forward_id='$data_forward_id'");
                    }
                }
            }
	    }
    }
}

