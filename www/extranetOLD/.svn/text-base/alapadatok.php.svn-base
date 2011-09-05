<?
include "auth.php";

$weare=145;

include "cookie_auth.php";  
  
$group_id=addslashes($group_id);
$mres = mysql_query("select groups.* from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and user_id='$active_userid' 
                     and (membership='owner' or membership='moderator' $admin_addq)");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else {
    header("Location: index.php?no_group=1"); exit; }
$title=$rowg["title"];

$fields=array(
    "name"=>array("input","mandatory",100,1),
    "admin_phones"=>array("input","",255,0),
    "admin_emails"=>array("input","",255,0),
    "cloudmark_email"=>array("input","",255,0),
    "subscribe_id"=>array("output","",0,0),
    "check_subscribe_id"=>array("output","",0,0),
    "sms_maxima0"=>array("checkbox","",0,0),
    "sms_sender_engine"=>array("checkbox","",0,0),
    "log_maxima0"=>array("log","",0,0),
    "log_sender_engine"=>array("log","",0,0),
	"domains_linked_images"=>array("input","",500,1),
    "subs_expiration_days"=>array("select","",0,1)
);

include "menugen.php";
include "./lang/$language/settings.lang";
include "./lang/$language/alapadatok.lang";

$ismsg=0;
if (isset($_POST["enter"])) {
    reset ($fields);
    $errors=array();
    $sets=array();
    $group_sets=array();
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
        if ($field=="domains_linked_images") {
            $v=explode(",",$value);
            $value=array();
            foreach ($v as $vv) {
                $vv=trim($vv);
                if (!empty($vv)) {
                    $value[]=$vv;
                }
            }
            $value=implode(",",$value);
            $ovalue=slasher($value,0);
        }
        if ($meta[0]=="checkbox") {
            if ($value!="yes") {
                $value="no";
            }
        }
        if ($meta[0]!="log") {
            $meta[3]?$group_sets[]="$field='$value'":$sets[]="$field='$value'";
        }
    }
    if (count($errors)==0) {
        $gqp=slasher(trim($_POST["question_pos"]))=="yes"?"above":"normal";
        $q="update groups set question_position='$gqp' where id='$group_id'";
        mysql_query($q);
        $sqldata=implode(",",$sets);
        $group_sqldata=implode(",",$group_sets);
        $msg=$word["data_changed"];
        $res=mysql_query("select * from alapadatok where group_id='$group_id'");
        if ($res && mysql_num_rows($res)) {
            //print("update alapadatok set $sqldata where group_id='$group_id'");
            $q="update alapadatok set $sqldata where group_id='$group_id'";
            mysql_query($q);
            logger($q,$group_id,"","","alapadatok");
            //print mysql_error();            
        }
        else {
        	$q="insert into alapadatok set $sqldata,group_id='$group_id'";
            mysql_query($q);
            logger($q,$group_id,"","","alapadatok");
        }
        $q="update groups set $group_sqldata where id='$group_id'";
        mysql_query($q);
        logger($q,$group_id,"","group_id=$group_id","groups");
    }
    if (count($errors)) {
        $ismsg=1;
        $msg=implode("<br>",$errors);
    }
    else {
        $ismsg=2;
    }
}

$hiddens=array(array("group_id",$group_id),array("enter","1"));
$hidden="";
$getvars="";
foreach ($hiddens as $hide) {
    $hidden.="<input type='hidden' name='$hide[0]' value=\"$hide[1]\"/>";
    if ($hide[0]!="enter") {
        $getvars.="&$hide[0]=$hide[1]";
    }
}

print "<form action='alapadatok.php' method='post' name='funky'>$hidden
       <table width=100% border=0 cellspacing=1 cellpadding=0 class='addborder'>";

if ($ismsg) {
    print "<tr><td colspan='2' width='100%'><table width=100% border=0 cellspacing=0 cellpadding=2><tr>
               <td class='formmezo' width='100%'>$msg</td>
           </tr></table></td></tr>";
}
$res=mysql_query("select question_position from groups where id='$group_id'");
$gqp=mysql_fetch_array($res);
$gqp=$gqp["question_position"]=="above"?" checked":"";
$res=mysql_query("select a.*,g.subscribe_id,g.check_subscribe_id,g.domains_linked_images,g.subs_expiration_days from groups g left join alapadatok a on a.group_id=g.id where g.id='$group_id'");
logger($q,$group_id,"","","alapadatok");
if ($res && mysql_num_rows($res)) {
    $alap=mysql_fetch_array($res);
}
$alap["name"]=$rowg["name"];

$formw=300;
reset ($fields);
while (list($field,$meta)=each($fields)) {
    $value="";
    $widget="";
    if (isset($_POST) && isset($_POST["$field"])) {
        $value=htmlspecialchars(slasher($_POST["$field"],0));
    }
    elseif (isset($alap) && isset($alap["$field"])) {
        $value=htmlspecialchars($alap["$field"]);
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
    elseif ($meta[0]=="log") {
        $widget="<a style='cursor:pointer; font-weight: bold; font-family: Arial, Helvetica, sans-serif; font-size: 10pt; text-decoration:underline; ' onclick='popwindow(\"alapadatok_logs.php?group_id=$group_id&logtype=". str_replace("log_","",$field) ."\",\"$field\")'>log</a>";
    }
    elseif ($meta[0]=="checkbox") {
        if ($value!="yes") {
            $value="no";
        }
        $checked=$value=="yes"?" checked":"";
        $widget="<input type='checkbox'$checked name='$field' value='yes'>";
    }
    elseif ($meta[0]=="select") {
        $possvals=array(-7,0,7,14,21,28);
        $widget="<select name='$field' style='width:$formw"."px;' class='oinput'>";
        foreach ($possvals as $pv) {
            if ($pv == 0) {
                $exopt = $word["sta_notexpire"];
            }
            else {
                $exopt = ($pv/7) . " $word[sta_week]";
            }
            $widget.="<option value='$pv'" . ($pv==$value?" selected":"") . ">$exopt</option>";
        }
        $widget.="</select>";
    }
    elseif ($meta[0]=="textarea") {
        $widget="<textarea name='$field' style='width:$formw"."px; height:150px;' class='oinput'>$value</textarea>";
    }
    else {
        $widget="<input name='$field' value=\"$value\" style='width:$formw"."px;' maxlength='$meta[2]' class='oinput'/>";
    }
    print "<tr><td width='200' style='vertical-align:top;' class='bgvilagos2'><span class=szoveg>$varname</span></td>
               <td width='670' style='vertical-align:top;' class='bgvilagos2'><span class=szoveg>$widget</span></td></tr>\n";
}
print "<tr><td width='200' style='vertical-align:top;' class='bgvilagos2'><span class=szoveg>$word[st_question_pos]</span></td>
        <td width='670' style='vertical-align:top;' class='bgvilagos2'><span class=szoveg><input type='checkbox'$gqp name='question_pos' value='yes'></span></td></tr>\n";
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
