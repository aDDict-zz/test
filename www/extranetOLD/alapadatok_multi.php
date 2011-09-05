<?
include "auth.php";
include "common.php";
$weare=146;
$sgweare=146;

include "cookie_auth.php";

$multiid=intval(get_http("multiid",0));
$query="select * from multi,multi_members where multi.id=multi_members.group_id
                         and multi.id='$multiid' and user_id='$active_userid'
                         and membership='moderator'";
$mres = mysql_query($query);
logger($query,0,"","multiid=".$multiid,"multi");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);
else {
    header("Location: index.php"); exit; }

$fields=array(
    "name"=>array("input","mandatory",100),
    "index_grouping"=>array("checkbox","",0,0),
    "subscribe_id"=>array("output","",0,0),
    "check_subscribe_id"=>array("output","",0,0),
    "subs_expiration_days"=>array("select","",0,1)
);

include "menugen.php";
include "./lang/$language/settings.lang";
include "./lang/$language/alapadatok_multi.lang";

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
        /*
        else {
            $value="";
        }
        */
        if ($meta[1]=="mandatory" && empty($value)) {
            $errors[]=$word["st_mand1"].$word["st_$field"].$word["st_mand2"];
        }
        if ($meta[0]=="checkbox") {
            if ($value!="yes") {
                $value="no";
            }
        }
        if (isset($_POST["$field"])) {
            $sets[]="$field='$value'";
        }
    }
    if (count($errors)==0) {
        $sqldata=implode(",",$sets);
        $msg=$word["data_changed"];
        $query="update multi set $sqldata where id='$multiid'";
        mysql_query($query);
        logger($query,0,"","multiid=".$multiid,"multi");
    }
    if (count($errors)) {
        $ismsg=1;
        $msg=implode("<br>",$errors);
    }
    else {
        $ismsg=2;
    }
}

$hiddens=array(array("subscribe_id",$rowg['subscribe_id']),array("check_subscribe_id",$rowg['check_subscribe_id']),array("multiid",$multiid),array("enter","1"));
$hidden="";
$getvars="";
foreach ($hiddens as $hide) {
    $hidden.="<input type='hidden' name='$hide[0]' value=\"$hide[1]\"/>";
    if ($hide[0]!="enter") {
        $getvars.="&$hide[0]=$hide[1]";
    }
}

print "<form action='alapadatok_multi.php' method='post' name='funky'>$hidden
       <table width=100% border=0 cellspacing=1 cellpadding=0 class='addborder'>";

if ($ismsg) {
    print "<tr><td colspan='2' width='100%'><table width=100% border=0 cellspacing=0 cellpadding=2><tr>
               <td class='formmezo' width='100%'>$msg</td>
           </tr></table></td></tr>";
}

$formw=300;
reset ($fields);
while (list($field,$meta)=each($fields)) {
    $value="";
    $widget="";
    if (isset($_POST) && isset($_POST["$field"])) {
        $value=htmlspecialchars(slasher($_POST["$field"],0));
    }
    elseif (isset($_POST) && isset($_POST["enter"])) {
        $value="";
    }
    elseif (isset($rowg) && isset($rowg["$field"])) {
        $value=htmlspecialchars($rowg["$field"]);
    }
    else {
        $value="";
    }
    $varname=$word["st_$field"].":";
    if ($meta[0]=="output") {
        if ($field=="check_subscribe_id") {
            $widget=$word["$value"];
        }
        else {
            $widget="$value";
        }
    }
    elseif ($meta[0]=="checkbox") {
        if ($value!="yes") {
            $value="no";
        }
        $checked=$value=="yes"?" checked":"";
        $widget="<input type='checkbox'$checked name='$field' value='yes'>";
    }
    elseif ($meta[0]=="select") {
        $possvals=array(-7,7,14,21,28);
        $widget="<select name='$field' style='width:$formw"."px;' class='oinput'>";
        foreach ($possvals as $pv) {
            $widget.="<option value='$pv'" . ($pv==$value?" selected":"") . ">" . ($pv/7) . " $word[sta_week]</option>";
        }
        $widget.="</select>";
    }
    elseif ($meta[0]=="textarea") {
        $widget="<textarea name='$field' style='width:$formw"."px; height:150px;' class='oinput'>$value</textarea>";
    }
    else {
        $widget="<input name='$field' value=\"$value\" style='width:$formw"."px;' maxlength='$meta[2]' class='oinput'/>";
    }
    print "<tr><td width='200' class='bgvilagos2'><span class=szoveg>$varname</span></td>
               <td width='670' class='bgvilagos2'><span class=szoveg>$widget</span></td></tr>\n";
}
print "<TR><TD align='center' colspan='2'><INPUT class='tovabbgomb' type=submit name='saveall' value=\"$word[submit3]\"></TD></TR>
</TABLE>
</form>
<script>
function popwindow(page,same,width,height) {
  if (!width) {width=750;}
  if (!height) {height=500}
  winopts = 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width='+width+',height='+height;
  return window.open(page, 'pw'+same, winopts); }
</script>
\n";

include "footer.php";
?>
