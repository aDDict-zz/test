<?
include "auth.php";
$weare=82;
if (get_http('message_category_id','')) {
    $subweare=821;
}
else {
    $subweare=822;
}
include "cookie_auth.php";

$Amfsort=get_cookie("Amfsort");
$Amfppage=get_cookie("Amfppage");

$fsort = (isset($_GET['fsort']) || empty($Amfsort)) ? get_http('fsort',4) : $Amfsort;
$maxPerPage = (isset($_GET['maxPerPage']) || empty($Amfppage)) ? get_http('maxPerPage',25) : $Amfppage;

setcookie("Amfsort",$fsort,time()+30*24*3600);
setcookie("Amfppage",$maxPerPage,time()+30*24*3600);

$mres = mysql_query("select title,num_of_mess,membership 
                     from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and (membership='owner' or membership='moderator' $admin_addq)
                     and user_id='$active_userid'");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else {
    header("Location: $_MX_var->baseUrl/index.php"); exit; }

include "menugen.php";
include "./lang/$language/sender.lang";

$fields=array(
    "name"=>array("input","mandatory",100)
);

$message_category_id=intval(get_http("message_category_id",0));
$r2=mysql_query("select * from message_category where group_id='$group_id' and id='$message_category_id'");
if ($r2 && mysql_num_rows($r2)) {
    $brow=mysql_fetch_array($r2);
}
else {
    $brow=array();
    $message_category_id=0;
}

$glue="";
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
        if ($meta[1]=="mandatory" && empty($value)) {
            $errors[]=$word["s_mand1"].$word["sb_$field"].$word["s_mand2"];
        }
        if ($meta[0]=="checkbox" || $meta[0]=="radio") {
            if ($value!="yes") {
                $value="no";
            }
        }
        if ($field=="codeset") {
            if (!isset($supported_codesets["$value"])) {
                list($value,$x)=each($supported_codesets);
            }
        }
        $sets[]="$field='$value'";
    }
    if (count($errors)==0) {
        $sqldata=implode(",",$sets);
        $msg=$word["data_changed"];
        if ($message_category_id) {
        	$query="update message_category set $sqldata where id='$message_category_id' and group_id='$group_id'";
            mysql_query($query);
            logger($query,$group_id,"","message_category id=$message_category_id","message_category");
        }
        else {
        	$query="insert into message_category set $sqldata,group_id='$group_id',dateadd=now()";
            mysql_query($query);
            logger($query,$group_id,"","","message_category");            
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

$hiddens=array(array("message_category_id",$message_category_id),array("group_id",$group_id),array("enter","1"));
$hidden="";
$getvars="";
foreach ($hiddens as $hide) {
    $hidden.="<input type='hidden' name='$hide[0]' value=\"$hide[1]\"/>";
    if ($hide[0]!="enter") {
        $getvars.="&$hide[0]=$hide[1]";
    }
}

$formw=650;

print "<form action='message_category_ch.php' method='post' name='funky'>$hidden
            <table width=100% border=0 cellspacing=1 cellpadding=0 class='bordercolor'>
                ";

if ($ismsg) {
    print "<tr><td colspan='2' width='100%'><table width=100% border=0 cellspacing=0 cellpadding=2><tr>
               <td class='formmezo' width='100%'>$msg</td>
           </tr></table></td></tr>";
}

$widget="";
reset ($fields);
while (list($field,$meta)=each($fields)) {
    $value="";
    $owidget="";
    if (isset($_POST) && isset($_POST["$field"])) {
        $value=htmlspecialchars(slasher($_POST["$field"],0));
    }
    elseif (isset($brow) && isset($brow["$field"])) {
        $value=htmlspecialchars($brow["$field"]);
    }
    else {
        $value="";
    }
    $varname=$word["sb_$field"].":";
    if ($meta[0]=="output") {
        $owidget="$value";
    }
    elseif ($meta[0]=="radio") {
        $opts=array("yes","no");
        foreach ($opts as $opt) {
            $value==$opt?$checked="checked":$checked="";
            $owidget.="<input type='radio' name='$field' value=\"$opt\"$checked/> ".$word["sb_$opt"]."&nbsp;&nbsp;&nbsp;&nbsp;";
        }
    }
    elseif ($field=="codeset") {
        $dependent_name=$value;
        $owidget="<select name='codeset'>";
        foreach ($supported_codesets as $cs => $desc) {
            $sel=($cs==$value?" selected":"");
            $owidget.="<option value='$cs'$sel>$desc</option>";
        }
    }
    elseif ($meta[0]=="textarea") {
        $owidget="<textarea name='$field' style='width:$formw"."px; height:150px;' class='oinput'>$value</textarea>";
    }
    else {
        $owidget="<input name='$field' value=\"$value\" style='width:$formw"."px;' maxlength='$meta[2]' class='oinput'/>";
    }
    print "<tr><td width='100' class='bgvilagos2'><span class=szoveg>$varname</span></td>
               <td width='670' class='bgvilagos2'><span class=szoveg>$owidget</span></td></tr>\n";
}
print "<TR><TD align='center' colspan='2'><INPUT class='tovabbgomb' type=submit name='saveall' value=\"$word[submit3]\"></TD></TR>
       </TABLE>
       </form>\n";	  
  
include "footer.php";
?>
