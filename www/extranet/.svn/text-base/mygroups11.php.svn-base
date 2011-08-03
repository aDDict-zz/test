<?
include "auth.php";
$weare=15;
include "cookie_auth.php";
include "common.php";
set_time_limit(0);

$mres = mysql_query("select title,num_of_mess,unique_col from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and user_id='$active_userid' 
                     and (membership='owner' or membership='moderator' $admin_addq)");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else {
    header("Location: $_MX_var->baseUrl/index.php?no_group=1"); exit; }
$title=$rowg["title"];
$unique_col=$rowg["unique_col"];

// first, we need to create the demog lists (for the member import):
// - the one explaining to the user how should the csv input look like
// - and the other one for the offline job, to tell it what the format is,
//   and, most important, in which order are the demog infos coming.

// the first column is always the unique column.
if (empty($unique_col)) {
    $unique_col="email";
}
$input_format = "<b>$unique_col</b>";
$ids=array("0");

$r2=mysql_query("select variable_name,demog.id,variable_type,multiselect from vip_demog,demog 
                 where vip_demog.demog_id=demog.id and vip_demog.group_id='$group_id' order by vip_demog.ordernum");
logger($query,$group_id,"","","vip_demog,demog");                 
if ($r2 && mysql_num_rows($r2)) {
    while ($mm=mysql_fetch_array($r2)) {
        if ($mm["variable_name"]==$unique_col) {
            $ids[0]=$mm["id"];
        }
        else {
            $ids[]=$mm["id"];
            $input_format .= ";$mm[variable_name]";
            if ($mm["multiselect"]=="yes") {
                $input_format.="|$mm[variable_name]2|$mm[variable_name]3...";
            }
        }
    }
}

// authenticator tells how to identificate users for the member groups.
if (isset($_POST["authenticator"])) {
    $authenticator=$_POST["authenticator"];
}
if ($authenticator==$unique_col) {
    $authenticatior_other_checked="checked";
    $authenticatior_id_checked="";
}
else {
    $authenticatior_id_checked="checked";
    $authenticatior_other_checked="";
    $authenticator="id";
}

// the delimiters and member data update type are important for the member import.
$poss_delim_main=array("",";","pipe",",","\t");
$delim_main=1;
if (isset($_POST["delim_main"])) {
    $delim_main=intval($_POST["delim_main"]);
}
if ($delim_main==0 || !isset($poss_delim_main[$delim_main])) {
    $delim_main=1;
}
//for now, there will be no options:
$delim_main=1;
$poss_delim_multi=array("","pipe",";"," ");
$delim_multi=1;
if (isset($_POST["delim_multi"])) {
    $delim_multi=intval($_POST["delim_multi"]);
}
if ($delim_multi==0 || !isset($poss_delim_multi[$delim_multi])) {
    $delim_multi=1;
}
//for now, there will be no options:
$delim_multi=1;
$poss_member_update=array("","old_user_noupdate","old_user_notrusted","old_user_trusted","old_user_active_notrusted","old_user_active_trusted");
$member_update=1;
if (isset($_POST["member_update"])) {
    $member_update=intval($_POST["member_update"]);
}
if ($member_update==0 || !isset($poss_member_update[$member_update])) {
    $member_update=1;
}
$poss_firstline=array("","fl_regular","fl_ignore","fl_demogdata");
$firstline=1;
if (isset($_POST["firstline"])) {
    $firstline=intval($_POST["firstline"]);
}
if ($firstline==0 || !isset($poss_firstline[$firstline])) {
    $firstline=1;
}
$affiliate_id=0;
if (isset($_POST["affiliate_id"])) {
    $affiliate_id=intval($_POST["affiliate_id"]);
}
$update_by_id=0;
if (isset($_POST["update_by_id"])) {
    $update_by_id=1;
}
$update_by_id?$update_by_id_checked="checked":$update_by_id_checked="";

$supported_codesets=array();
$rec=mysql_query("select codepage,description from codepages order by sortorder");
while ($kec=mysql_fetch_array($rec)) {
    $supported_codesets["$kec[codepage]"]=$kec["description"];
}

$what_upload_checked=$what_text_checked=$addto_member_group_checked=$addto_group_checked="";
if (isset($_POST["what"]) && $_POST["what"]=="text") {
    $what="text";
    $codeset="utf8";
}
else {
    $what="upload";
    if (isset($_POST["codeset"]) && isset($supported_codesets["$_POST[codeset]"])) {
        $codeset=$_POST["codeset"];
    }
    else {
        list($codeset,$x)=each($supported_codesets);
    }
}
${"what_$what"."_checked"}="checked";
if (isset($_POST["addto"]) && $_POST["addto"]=="member_group") {
    $addto="member_group";
}
else {
    $addto="group";
}
${"addto_$addto"."_checked"}="checked";

// need it for the slasher function
include_once "common.php";

$raw_data="";
if (isset($_POST["raw_data"])) {
    $raw_data=slasher($_POST["raw_data"],0);
}
$errors=array();

$user_group_name=get_http("user_group_name","");

if (get_http("enter",0)) {
    if ($addto=="member_group" && !eregi("^[a-z]+[0-9a-z]*$", $user_group_name)) {
        $errors[]="err_member_group_name";
    }
    if ($what=="upload" && (empty($_FILES["csv_file"]) || $_FILES["csv_file"]["tmp_name"]=="")) {
        $errors[]="no_file_selected";
    }
    if (count($errors)==0) { 
        $language="hu";
        $filepath=$_MX_var->member_import_temp_dir . md5(time().$REMOTE_ADDR);
        if ($addto=="member_group") {
            $job_type="import_subgroup";
            $additional_params="|$user_group_name|$authenticator";
        }
        else {
            $job_type="import_member";
            $additional_params="|$poss_firstline[$firstline]|$poss_delim_main[$delim_main]|$poss_delim_multi[$delim_multi]|$poss_member_update[$member_update]|". implode(",",$ids). "|$affiliate_id|$update_by_id";
        }
        if ($what=="upload" && !empty($_FILES["csv_file"]) && $_FILES["csv_file"]["tmp_name"]!="none") {
            move_uploaded_file($_FILES["csv_file"]["tmp_name"],"$filepath");
        }
        elseif ($what=="text") {
            if ($fp=fopen($filepath,"w")) {
                fwrite($fp,"$raw_data\n");
                fclose($fp);
            }
            else {
                $errors[]="err_noinput";
            }
        }
        else {
            $errors[]="err_noinput";
        }
        if (count($errors)==0) { 
            $progress_max=filesize($filepath);
            $q="insert into xmlreq (job_type,status,job_input,date,progress_max,group_id,description,codeset) values 
                         ('$job_type','queued','$filepath$additional_params',now(),
                          '$progress_max','$group_id','$user_group_name','$codeset')";
            mysql_query($q);
    		logger($q,$group_id,"","","xmlreq");                          
            header("location: xmlreqlist.php?group_id=$group_id");
            exit;
        }

    }
}

include "menugen.php";
include "./lang/$language/export_import.lang";

if (count($errors)) { 
    print "<TABLE cellSpacing=0 cellPadding=0 width='100%' style='border:1px $_MX_var->main_table_border_color solid;'>
           <tr>
           <td class=BACKCOLOR colspan='4' align='left'>
           <span class='szovegvastag'>";
    foreach ($errors as $error) {
        print "$word[$error]<br>";
    }
    print "</span></td></tr></table><br>";
}

$user_group_name=htmlspecialchars($user_group_name);

echo "<form  method='post' enctype='multipart/form-data'>
<table border=0 cellspacing=0 cellpadding=1 bgcolor=$_MX_var->main_table_border_color width=100%>
<tr><td class=formmezo>
&nbsp;$word[exp_imp_imp]
</td></tr>
<tr><td>
<table border=0 cellspacing=1 cellpadding=1 bgcolor=#eeeeee width=100%>
  <tr><td><span class='szoveg'>
  <input type='radio' name='addto' value='group' $addto_group_checked>&nbsp;$word[explain_group]<br>
  $word[explain2]:
  <br><br>
  <textarea style='width:960px;height:50px;'>$input_format</textarea>
  <br>
  ...<br>
  $word[explain3]<br>
  </span></td></tr>
</table>
</td></tr>
<tr><td>
<table border=0 cellspacing=1 cellpadding=1 bgcolor=#eeeeee width=100%>
  <tr><td><span class='szoveg'>
  <input type='radio' name='addto' value='member_group' $addto_member_group_checked>&nbsp;$word[explain_member_group_a]&nbsp;<input type='text' name='user_group_name' value=\"$user_group_name\">&nbsp;$word[explain_member_group_b]<br>
  $word[explain2]:
  <br><br>
  id<br>
  id<br>
  ...<br>
  $word[where_authenticator]&nbsp;<input type='radio' name='authenticator' value='id' $authenticatior_id_checked>user_id&nbsp;<input type='radio' name='authenticator' value='$unique_col' $authenticatior_other_checked>$unique_col
  </span></td></tr>
</table>
</td></tr>
<tr><td>
<table border=0 cellspacing=1 cellpadding=1 bgcolor=#eeeeee width=100%>
  <tr>
    <td class=bgvilagos2 valign='top'><span class='szoveg'>
  <input type='hidden' name='enter' value='1'>
  <input type='hidden' name='group_id' value='$group_id'>  
    <!--$word[delimiter]:
    <select name='delim_main'>-->";
//$delimnames=array("",";","|",",",$word["tabulator"]);
/*$delimnames=array("",",");
for ($j=1;$j<count($poss_delim_main);$j++) {
    $j==$delim_main?$sel=" selected":$sel="";
    print "<option value='$j'$sel>$delimnames[$j]</option>";
}
print "</select><br>
    $word[delimiter_multi]:
    <select name='delim_multi'>";
$delimnames=array("","|",";","[$word[space]]");
for ($j=1;$j<count($poss_delim_multi);$j++) {
    $j==$delim_multi?$sel=" selected":$sel="";
    print "<option value='$j'$sel>$delimnames[$j]</option>";
}*/
print "<!--</select>--><br>
    $word[old_user_update]:
    <select name='member_update'>";
for ($j=1;$j<count($poss_member_update);$j++) {
    $j==$member_update?$sel=" selected":$sel="";
    print "<option value='$j'$sel>".$word["$poss_member_update[$j]"]."</option>";
}
print "</select><br>
    $word[fl_options]:
    <select name='firstline'>";
for ($j=1;$j<count($poss_firstline);$j++) {
    $j==$firstline?$sel=" selected":$sel="";
    print "<option value='$j'$sel>".$word["$poss_firstline[$j]"]."</option>";
}

$codeset_options="";
foreach ($supported_codesets as $cs => $desc) {
    $sel=($cs==$codeset?" selected":"");
    $codeset_options.="<option value='$cs'$sel>$desc</option>";
}

print "</select><br>Affiliate id:<input name='affiliate_id' value='$affiliate_id' size='10'>
    </span></td>
  </tr>
  <tr>
    <td class=bgvilagos2 valign='top'><span class='szoveg'>
    <input type='checkbox' name='update_by_id' value='1' $update_by_id_checked> $word[update_by_id]</span></td>
  </tr>
  <tr>
    <td class=bgvilagos2 valign='top'><span class='szoveg'>
    <input type='radio' name='what' value='upload' $what_upload_checked>$word[file]:<br>    
    <INPUT TYPE='hidden' name='MAX_FILE_SIZE' value='5000000'>
    <INPUT TYPE='file' size='40' name='csv_file'> $word[file_charset]: <select name='codeset'>$codeset_options</select></span></td>
  </tr>
  <tr>
    <td class=bgvilagos2 valign='top'><span class='szoveg'>
    <input type='radio' name='what' value='text' $what_text_checked>$word[text]:<br>    
    <textarea rows='12' cols='70' name='raw_data'>";

if (isset($raw_data)) {
    print htmlspecialchars($raw_data);
}
print "</textarea></span></td>
  </tr>
  <tr>
    <td align='left' class=bgvilagos2><input class='tovabbgomb' type='submit' name='saveall' value='$word[import]' onclick='if (!checkFileUpload(\"csv_file\",\"what\",\"upload\")) return false;'>
  </td>
  </tr>
</table>
</td>
</tr>
</table>
</form>
";
?>
