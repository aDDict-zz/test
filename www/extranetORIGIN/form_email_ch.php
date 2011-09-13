<?
include "auth.php";
include "decode.php";
$weare=34;
include "cookie_auth.php";
include "common.php";
$language=select_lang();
include "./lang/$language/form.lang";

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
$form_email_id=slasher($form_email_id);
$res=mysql_query("select * from form where id='$form_id' and group_id='$group_id'");
if ($res && mysql_num_rows($res))
    $formdata=mysql_fetch_array($res);
else
    exit;

$error=array();
    
$fields=array(
    "base_id"=>array("select","mandatory",0),
    "sender_id"=>array("select","mandatory",0)
);

$form_email_id=intval($form_email_id);
$r2=mysql_query("select * from form_email where form_id='$form_id' and id='$form_email_id'");
logger($query,$group_id,"","form_id=$form_id,form_email_id=$form_email_id","form_email");
if ($r2 && mysql_num_rows($r2)) {
    $brow=mysql_fetch_array($r2);
    $what=$word["fem_change"];
}
else {
    $brow=array();
    $form_email_id=0;
    $what=$word["fem_new"];
}

printhead();

$ismsg=0;
if (isset($_POST["enter"]) && $_POST["enter"]==1) {
    reset ($fields);
    $errors=array();
    $sets=array();
    while (list($field,$meta)=each($fields)) {
        if (isset($_POST["$field"])) {
            $ovalue=slasher(trim($_POST["$field"]),0);
            $value=slasher(trim($_POST["$field"]));
        }
        else {
            $value="";
        }
        if (($field=="base_id" || $field=="sender_id") && !ereg("^[0-9]+$",$value)) {
            $value="";
        }
        if ($meta[1]=="mandatory" && empty($value)) {
            $errors[]=$word["s_mand1"].$word["st_$field"].$word["s_mand2"];
        }
        elseif ($field=="sender_id") {
            $zr=mysql_query("select * from members where group_id='$group_id' and user_id='$value' 
                             and membership in ('moderator','owner','support','sender')");
            if (!($zr && mysql_num_rows($zr))) {
                $errors[]=$word["sender_id_error"];
            }
        }
        elseif ($field=="base_id") {
            $zr=mysql_query("select * from sender_base where group_id='$group_id' and id='$value'");
            if (!($zr && mysql_num_rows($zr))) {
                $errors[]=$word["base_id_error"];
            }
        }
        elseif ($meta[0]=="checkbox") {
            if ($value!="yes") {
                $value="no";
            }
        }
        # for test mails, the fields listed in the arrey need to have special values.
        $sets[]="$field='$value'";
    }
    if (count($errors)==0) {
        $sqldata=implode(",",$sets);
        $msg=$word["data_changed"];
        if ($form_email_id) {
        	$query="update form_email set $sqldata where id='$form_email_id' and form_id='$form_id'";
            mysql_query($query);
            logger($query,$group_id,"","form_id=$form_id,form_email_id=$form_email_id","form_email");
        }
        else {
        	$query="insert into form_email set $sqldata,form_id='$form_id'";
            mysql_query($query);
            print mysql_error();
            $form_email_id=mysql_insert_id();
			logger($query,$group_id,"","form_id=$form_id","form_email");
        }
    }
    if (count($errors)) {
        $ismsg=1;
        $msg=implode("<br>",$errors);
    }
    else {
        $ismsg=2;
    }
}

$formw=300;
if ($ismsg) {
    print "<tr><td colspan='2' width='100%'><table width=100% border=0 cellspacing=0 cellpadding=2><tr>
               <td class='formmezo' width='100%'>$msg</td>
           </tr></table></td></tr>";
}

reset ($fields);
$detype="";
while (list($field,$meta)=each($fields)) {
    $value="";
    $widget="";
    if (isset($_POST) && isset($_POST["$field"])) {
        $value=htmlspecialchars(slasher($_POST["$field"],0));
    }
    elseif (isset($brow) && isset($brow["$field"])) {
        $value=htmlspecialchars($brow["$field"]);
    }
    $varname=$word["st_$field"].":";
    if ($meta[0]=="output") {
        $widget="$value";
    }
    elseif ($meta[0]=="checkbox") {
        $value=="yes"?$checked="checked":$checked="";
        $widget.="<input type='checkbox' name='$field' value=\"yes\"$checked/>";
    }
    elseif ($meta[0]=="select") {
        $opts=array("x"=>$word["st_select"]);
        $onch="";
        if ($field=="base_id") {
            $q="select id,name from sender_base where group_id='$group_id' order by name";
        }
        elseif ($field=="sender_id") {
            $q="select u.id,concat(u.email,' <',u.name,'>') as name from members m, user u 
                where m.group_id='$group_id' and m.membership in ('moderator','owner','support','sender') 
                and m.user_id=u.id order by u.email";
        }
        else {
            $q="select id,name from filter where group_id='$group_id' order by name";
        }
        $r6=mysql_query($q);
        if ($r6 && mysql_num_rows($r6)) {
            while ($k6=mysql_fetch_array($r6)) {
                $opts["$k6[id]"]=htmlspecialchars($k6["name"]);
            }
        }
        $widget="<select name='$field' style='width:$formw"."px;' class='oinput' $onch/>";
        foreach ($opts as $opt=>$optd) {
            $value==$opt?$selected="selected":$selected="";
            $widget.="<option $selected value='$opt'>$optd</option>";
        }
    }
    else {
        $widget="<input name='$field' value=\"$value\" style='width:$formw"."px;' maxlength='$meta[2]' class='oinput'/>";
        if ($field=="test_email") {
            $widget.="<input class='tovabbgomb' type='submit' name='test_button' value='Teszt'>";
        }
    }
    print "<tr><td width='160' bgcolor='white'><span class=szoveg>$varname</span></td>
               <td width='480' bgcolor='white'><span class=szoveg>$widget</span></td></tr>\n";
}

print "
<tr>
<td align=center colspan=2>
<input type=submit class='tovabbgomb' value='$word[gobutton]'> <input type='button' class='tovabbgomb' value='$word[close]'  onclick='opener.location=\"form_elements.php?group_id=$group_id&form_id=$form_id\";window.close();'>
</td>
</tr></form>";

printfoot();
include "footer.php";

function printhead() {

    global $_MX_var,$stat_text,$group_id,$word,$formdata,$form_id,$group_id,$form_email_id,$what,$language;


$_MX_popup = 1;
include "menugen.php";

echo "
<form method='post' action='form_email_ch.php'>
<input type='hidden' name='enter' value='1'>
<input type='hidden' name='form_id' value='$form_id'>
<input type='hidden' name='form_email_id' value='$form_email_id'>
<input type='hidden' name='group_id' value='$group_id'>
<TABLE cellSpacing=0 cellPadding=1 width=\"100%\" bgColor=$_MX_var->main_table_border_color border=0>
        <TR>
        <TD>
        <TABLE cellSpacing=1 cellPadding=2 width=\"100%\" bgColor=$_MX_var->main_table_border_color border=0>
        <tr>
		<td class=bgkiemelt2 align=left colspan=3><span class=szovegvastag>&quot;".htmlspecialchars($formdata["title"])."&quot $word[iform_title] &gt; $what</span></td>
		</tr>\n";
}

function printfoot() {

    echo "</table>
        </td>
        </tr>
        </table>
        </form>
        </body>
    </html>\n";
}

