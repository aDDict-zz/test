<?

include "auth.php";

$automatic_type=get_http("automatic_type","");

if ($automatic_type=="web") {
    $weare=22;
    $sgweare=103;
}
else {
    $automatic_type="mail";
    $weare=13;
    $sgweare=102;
}

include "cookie_auth.php";  
  
$group_id=get_http("group_id",'');
$multiid=get_http("multiid",'');
$testhtml=get_http("testhtml",'');
$automatic_type=get_http("automatic_type",'');

if ($group_id) {
    $mres = mysql_query("select groups.* from groups,members where groups.id=members.group_id
                         and groups.id='$group_id' and user_id='$active_userid' 
                         and (membership='owner' or membership='moderator' $admin_addq)");
    if ($mres && mysql_num_rows($mres))
        $rowg=mysql_fetch_array($mres);  
    else {
        header("Location: index.php?no_group=1"); exit; }
    $title=$rowg["title"];
    $multiid="";
}
else {
    $mres = mysql_query("select multi.* from multi,multi_members where multi.id=multi_members.group_id
                         and multi.id='$multiid' and user_id='$active_userid' 
                         and membership='moderator'");
    if ($mres && mysql_num_rows($mres))
        $rowg=mysql_fetch_array($mres);  
    else {
        header("Location: index.php?no_group=1"); exit; }
    $group_id="";
    $title=$rowg["title"];
}

if ($automatic_type=="web") {
    $fields=array(
        "header"=>array("textarea","",0,1),
        "footer"=>array("textarea","",0,1),
        "validator_page"=>array("textarea","",0,1),
        "unsublink_ok"=>array("textarea","",0,1),
        "unsublink_notok"=>array("textarea","",0,1)
        /*, Never used fields
        "maint_notify_delete_ok"=>array("textarea","",0,1),
        "maint_notify_delete_notok"=>array("textarea","",0,1)
        */
    );
}
else {
    $fields=array(
        "subscribe_subject"=>array("input","",255),
        "subscribe_body"=>array("textarea","",0),
        "already_subs_subject"=>array("input","",255),
        "already_subs"=>array("textarea","",0),
        "welcome_yesno"=>array("radio","",0),
        "welcome_subject"=>array("input","",255)
    );
    if ($group_id) {    // this field has different names in groups and multi tables :(
        $fields["intro"]=array("textarea","",0);
    }
    else {
        $fields["welcome_message"]=array("textarea","",0);
    }
    $fields["subs_expired_subject"]=array("input","",255);
    $fields["subs_expired"]=array("textarea","",0);
    $fields["unsub_mail_subject"]=array("input","",255);
    $fields["unsub_mail"]=array("textarea","",0);
    $fields["unsub_validation_subject"]=array("input","",255);
    $fields["unsub_validation_ok"]=array("textarea","",0);
    $fields["unsub_validation_notok"]=array("textarea","",0);
    $fields["mail_sender"]=array("input","",255);
    // these two fields were never used
    // $fields["update_mail_subject"]=array("input","",255);
    // $fields["update_mail"]=array("textarea","",0);
}

if (isset($testhtml) && isset($fields["$testhtml"][3])) {
    if ($testhtml=="header" || $testhtml=="footer") {
        $middlestuff="";
    }
    else {
        $middlestuff=$rowg["$testhtml"];
    }
    print "$rowg[header]$middlestuff$rowg[footer]";
    exit;
}

include "menugen.php";
include "./lang/$language/settings.lang";

$ismsg=0;
if (isset($_POST["enter"])) {
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
        if ($meta[1]=="mandatory" && empty($value)) {
            $errors[]=$word["st_mand1"].$word["st_$field"].$word["st_mand2"];
        }
        if ($meta[0]=="radio") {
            if ($value!="yes") {
                $value="no";
            }
        }
        $sets[]="$field='$value'";
    }
    if (count($errors)==0) {
        $sqldata=implode(",",$sets);
        $msg=$word["data_changed"];
        if ($group_id) {
        	$q="update groups set $sqldata where id='$group_id'";
            mysql_query($q);
		    logger($q,$group_id,"","","groups");
        }
        else {
        	$q="update multi set $sqldata where id='$multiid'";
            mysql_query($q);
            logger($q,$group_id,"","multi_id=$multiid","multi");
            print mysql_error();
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

$hiddens=array(array("automatic_type",$automatic_type),array("multiid",$multiid),array("group_id",$group_id),array("enter","1"));
$hidden="";
$getvars="";
foreach ($hiddens as $hide) {
    $hidden.="<input type='hidden' name='$hide[0]' value=\"$hide[1]\"/>";
    if ($hide[0]!="enter") {
        $getvars.="&$hide[0]=$hide[1]";
    }
}

$formw=650;

print "<form action='mygroups10.php' method='post' name='funky'>$hidden
       <table width=100% border=0 cellspacing=1 cellpadding=0 class='addborder'>";

if ($ismsg) {
    print "<tr><td colspan='2' width='100%'><table width=100% border=0 cellspacing=0 cellpadding=2><tr>
               <td class='formmezo' width='100%'>$msg</td>
           </tr></table></td></tr>";
}

$data_from_multi=array();
if ($group_id) {
    $res=mysql_query("select m.* from multi m, multigroup mg where mg.groupid='$group_id' 
                      and mg.multiid=m.id order by m.title");                      
    logger($q,$group_id,"","","multi,multigroup");
    print mysql_error();
    if ($res && mysql_num_rows($res)) {
        $gmulopts=array("<option value='0'>$word[select]</option>");
        while ($km=mysql_fetch_array($res)) {
            $gmulopts[]="<option value='$km[id]'>$km[title]</option>";
            if (isset($get_from_multi) && $get_from_multi==$km["id"]) {
                $data_from_multi=$km;
            }
        }
        print "<tr><td width='200' style='vertical-align:top;' class='bgvilagos2'><span class=szoveg>$word[get_from_multi]</span></td>
                   <td width='670' style='vertical-align:top;' class='bgvilagos2'><span class=szoveg>
                   <select name='get_from_multi' style='width:$formw"."px;' onchange=\"var f=document.funky; var ind=f.get_from_multi.selectedIndex; location='mygroups10.php?get_from_multi='+f.get_from_multi[ind].value+'$getvars';\">". implode("\n",$gmulopts);
        print "</select></span></td></tr>\n";
    }
}

reset ($fields);
while (list($field,$meta)=each($fields)) {
    $value="";
    $widget="";
    $field=="intro"?$getfield="welcome_message":$getfield=$field; // this field has different names in groups and multi tables :(
    if (isset($_POST) && isset($_POST["$field"])) {
        $value=htmlspecialchars(slasher($_POST["$field"],0));
    }
    elseif ($field!="name" && !empty($data_from_multi["$getfield"])) {
        $value=htmlspecialchars($data_from_multi["$getfield"]);
    }
    elseif (isset($rowg) && isset($rowg["$field"])) {
        $value=htmlspecialchars($rowg["$field"]);
    }
    else {
        $value="";
    }
    $varname=$word["st_$field"].":";
    if (isset($meta[3])) {
        $varname.="<br><a href='mygroups10.php?testhtml=$field$getvars' target='_blank'>$word[test]</a>";
    }
    if ($meta[0]=="output") {
        $widget="$value";
    }
    elseif ($meta[0]=="radio") {
        $opts=array("yes","no");
        foreach ($opts as $opt) {
            $value==$opt?$checked="checked":$checked="";
            $widget.="<input type='radio' name='$field' value=\"$opt\"$checked/> ".$word["st_$opt"]."&nbsp;&nbsp;&nbsp;&nbsp;";
        }
    }
    elseif ($meta[0]=="textarea") {
        $widget="<textarea name='$field' style='width:$formw"."px; height:150px;' class='oinput'>$value</textarea>";
    }
    else {
        $widget="<input name='$field' value=\"$value\" style='width:$formw"."px;' maxlength='$meta[2]' class='oinput'/>";
    }
    print "<tr><td width='200' style='vertical-align:top;' class='bgvilagos2 vat'><span class=szoveg>$varname</span></td>
               <td width='670' style='vertical-align:top;' class='bgvilagos2'><span class=szoveg>$widget<br><br></span></td></tr>\n";
}
print "<TR><TD align='center' colspan='2'><INPUT class='tovabbgomb' type=submit name='saveall' value=\"$word[submit3]\"></TD></TR>
       </TABLE>
       </form>\n";	  
  
include "footer.php";
?>
