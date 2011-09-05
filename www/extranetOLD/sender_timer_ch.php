<?
include "auth.php";
$weare=81;
if (get_http('timer_id','')) $subweare=811;
else $subweare=812;
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

include "./lang/$language/sender.lang";

$get_base_id=get_http("base_id",0); // if $_GET['base_id'] is available, use this base as default.

$fix_group_id=get_http("fix_group_id",$group_id);

$prevgroupid=get_cookie('prevgroupid');
if (!empty($prevgroupid)) {
    setcookie('prevgroupid','',time()-3600*24);
}

$fields=array(
    "name"=>array("input","mandatory",100),
    "mod_group_id"=>array("select","",0),
    "base_id"=>array("select","mandatory",0),
    "subject"=>array("input","mandatory",255),
    "emphasized"=>array("input","",255),
    "sender_id"=>array("select","mandatory",0),
    "filter_id"=>array("select","",0),
    "message_category_id"=>array("select","",0),
    "stype"=>array("select","",0),
    "sdays"=>array("cbs","",0),
    "smonthday"=>array("input","",2),
    "smod"=>array("select","",0),
    "sdate"=>array("input","",16),
    "stime"=>array("input","",5),
    "test_email"=>array("input","",255),
    "notice"=>array("input","",100),
    "active"=>array("checkbox","",0)
);

$timer_id=intval(get_http("timer_id",0));
$r2=mysql_query("select * from sender_timer where id='$timer_id'");
//$r2=mysql_query("select * from sender_timer where group_id='$group_id' and id='$timer_id'");
if ($r2 && mysql_num_rows($r2)) {
    $brow=mysql_fetch_array($r2);
    $timings=array();
}
else {
    $brow=array();
    $timer_id=0;
    $timings=array("now"=>array());
}

$timings["single"]=array("sdate","active");
$timings["cyclical"]=array("sdays","stime","active");
$timings["2hetente"]=array("smod","stime","active");
$timings["3hetente"]=array("smod","stime","active");
$timings["4hetente"]=array("smod","stime","active");
$timings["havonta"]=array("smonthday","stime","active");

$timingspec=array("sdate","stime","sdays","smonthday","smod","active");
                
$ismsg=0;
if (isset($_POST["enter"]) && $_POST["enter"]==1) {
    reset ($fields);
    $errors=array();
    $sets=array();
	$check=array();
    $tmp_group_id="";
    
    if ($_POST['mod_group_id']!=$group_id) {
        //$tmp_group_id=$group_id;
        $which_group_id=slasher(trim($_POST["mod_group_id"]));
    }
    else {
        $which_group_id=$group_id;
    }
    
    $subject=$emphasized="";
    while (list($field,$meta)=each($fields)) {
        if (isset($_POST["$field"])) {
            $ovalue=slasher(trim($_POST["$field"]),0);
            $value=slasher(trim($_POST["$field"]));
        }
        else {
            $value="";
        }
        if ($field=="subject") {
            $subject=$value;
        }
        if ($field=="emphasized") {
            $emphasized=$value;
        }
        if ($field=="stype") {
            if (!isset($timings["$value"])) {
                $value="now";
            }
            $detype=$value;
        }
        elseif (($field=="base_id" || $field=="filter_id" || $field=="sender_id" || $field=="message_category_id") && !ereg("^[0-9]+$",$value)) {
            $value="";
        }
        if (!in_array($field,$timingspec) || in_array($field,$timings["$detype"])) {
            if ($meta[1]=="mandatory" && empty($value)) {
                $errors[]=$word["s_mand1"].$word["st_$field"].$word["s_mand2"];
            }
            if ($field=="sdays") {
                $value="";
                for ($i=0; $i<7; $i++) {
                    (isset($_POST["sdays$i"]))?$value.="X":$value.="-";
                }
            }
            elseif ($field=="sdate") {
                $d="[^0-9]";
                if (ereg("^([0-9]{1,4})$d([0-9]{1,2})$d([0-9]{1,2})$d([0-9]{1,2})$d([0-9]{1,2})",$value,$r)) {
                    $r[1]=str_pad($r[1],4,"200",STR_PAD_LEFT);
                    $r[2]=str_pad($r[2],2,"0",STR_PAD_LEFT);
                    $r[3]=str_pad($r[3],2,"0",STR_PAD_LEFT);
                    $r[4]=str_pad($r[4],2,"0",STR_PAD_LEFT);
                    $r[5]=str_pad($r[5],2,"0",STR_PAD_LEFT);
                    $value="$r[1]-$r[2]-$r[3] $r[4]:$r[5]:00";
                    $_POST["sdate"]=$value;
                }
                else {
                    $errors[]=$word["st_date_error"];
                }
            }
            elseif ($field=="stime") {
                $d="[^0-9]";
                if (ereg("^([0-9]{1,2})$d([0-9]{1,2})",$value,$r)) {
                    $r[1]=str_pad($r[1],2,"0",STR_PAD_LEFT);
                    $r[2]=str_pad($r[2],2,"0",STR_PAD_LEFT);
                    $value="$r[1]:$r[2]";
                    $_POST["stime"]=$value;
                }
                else {
                    $errors[]=$word["st_time_error"];
                }
            }
            elseif ($field=="smonthday") {
                $value=intval($value);
                if ($value<1 || $value>31) {
                    $errors[]=$word["st_monthday_error"];
                }
            }
            elseif ($field=="smod") {
                if (ereg("^[0-9][0-9]?$",$value)) {
                    if ($detype=="2hetente" && $value>13 || $detype=="3hetente" && $value>20) {
                        $errors[]=$word["st_badsmod"];
                    }
                }
                else {
                    $errors[]=$word["st_nosmod"];
                }
            }
            elseif ($field=="sender_id") {
                $zr=mysql_query("select * from members where group_id='$which_group_id' and user_id='$value' 
                                 and membership in ('moderator','owner','support','sender')");
                if (!($zr && mysql_num_rows($zr))) {
                    $errors[]=$word["sender_id_error"];
                }
            }
            elseif ($meta[0]=="checkbox") {
                if ($value!="yes") {
                    $value="no";
                }
            }

			$check["$field"]=$value;
            # for test mails and immediate sends, the fields listed in the array need to have special values.
            if (!( (isset($_POST["test_button"]) || $_POST["stype"]=="now") && in_array($field,array("test_email","stype","sdate","active")))) {
                if ($field!="group_id" && $field!="subject" && $field!="emphasized") { // we don't need the hidden group_id, since we have a category dropdown (mod_group_id)
                    if ($field=="mod_group_id") { $field='group_id'; }
                    $sets[]="$field='$value'";
                }
            }
        }
    }
    if (count($errors)==0) {

        if (!empty($subject) && !empty($get_base_id)) {
            $q="update sender_base set subject='$subject',emphasized='$emphasized' where id='$get_base_id'";
            mysql_query($q);
        }

		$changes="";	
		$res=mysql_query("select * from sender_timer where id='$timer_id'");
		//$res=mysql_query("select * from sender_timer where id='$timer_id' and group_id='$group_id'");
		$k=mysql_fetch_array($res);

        $sqldata=implode(",",$sets);
        $doredirect=0;
        $immediate=0;
        if (isset($_POST["test_button"])) {
            $_POST["test_email"]=str_replace(";",",",$_POST["test_email"]);
            $mails=explode(",",slasher(trim($_POST["test_email"])));
            $mailok=array();
            foreach ($mails as $tmail) {
                if (eregi("^[\+\._a-z0-9-]+@([0-9a-z][0-9a-z-]*[0-9a-z]\.)+[a-z]{2,4}$", $tmail)) {
                    $mailok[]=$tmail;
                }
            }
            if (count($mailok)) {
                mysql_query("insert into sender_timer set $sqldata,dateadd=now(),
                             status='prepared',test_email='" . implode(",",$mailok) . "',
                             test='yes',stype='now',sdate=now(),active='yes'");
                $immediate=mysql_insert_id();
            }
            $msg=count($mailok) . " teszt email.";
        }
        elseif ($_POST["stype"]=="now") {
            mysql_query("insert into sender_timer set $sqldata,dateadd=now(),status='prepared',stype='now',sdate=now(),active='yes'");
            if (!$immediate=mysql_insert_id()) {
                $msg="Sikertelen kiküldés";
            }
            $doredirect=1;
        }
        else {
            $msg=$word["data_changed"];
            if ($timer_id) {
                mysql_query("update sender_timer set $sqldata where id='$timer_id'");
                //mysql_query("update sender_timer set $sqldata where id='$timer_id' and group_id='$which_group_id'");
            }
            else {
                mysql_query("insert into sender_timer set $sqldata,status='queued'");
                print mysql_error();
                $timer_id=mysql_insert_id();
            }
        }
		if (mysql_num_rows($res)>0) {
			foreach ($k as $key => $element) { 
				if ($check[$key] != NULL and $check[$key] != $element) {
					$changes.= "*#*".$key.":: ".$element." -> ".$check[$key]." ";
				}
			}
			$chtype="st_modositva";
		}		
		else {
			foreach ($check as $key => $element) { 
 				$changes.= "*#*".$key."::  -> ".$element.", ";
			}
			$chtype="st_letrehozva";
		}
		if ($changes != NULL) {
			mysql_query("insert into sender_log set user_id='$active_userid',group_id='$which_group_id',timer_id='$timer_id',log_desc='$changes',date_mod=now(),chtype='$chtype'");
		}
        if ($immediate) { //echo "$_MX_var->sender_engine now-$immediate >/dev/null &"; die();
            system("$_MX_var->sender_engine now-$immediate >/dev/null &");
            if ($doredirect) {
                header ("Location: $_MX_var->baseUrl/sender_timer.php?group_id=$fix_group_id");
                exit;
            }
        }
    }
    if (count($errors)) {
        $ismsg=1;
        $msg=implode("<br>",$errors);
    }
    else {
        $ismsg=2;
    }
    /*
    if ($_POST['group_id']!=$group_id) {
        $group_id=$tmp_group_id;
    }
    */
}

$hiddens=array(array("timer_id",$timer_id),array("fix_group_id",$fix_group_id),array("group_id",$group_id),array("enter","1"));
$hidden="";
$getvars="";
foreach ($hiddens as $hide) {
    $hidden.="<input type='hidden' name='$hide[0]' value=\"$hide[1]\"/>";
    if ($hide[0]!="enter") {
        $getvars.="&$hide[0]=$hide[1]";
    }
}

include "menugen.php";

print "<form action='sender_timer_ch.php?group_id=$group_id' method='post' name='funky'>$hidden
       <table width=100% border=0 cellspacing=1 cellpadding=0 class='bordercolor'>
       <!--<TR><TD><span class='szovegvastag'><a href='sender_timer.php?group_id=$group_id'>$word[timer]</a></span></TD><TD align='right'><span class='szovegvastag'><a href='sender_timer.php?group_id=$group_id'>$word[back]</a></span></TD></TR>-->";

if ($ismsg) {
    print "<tr><td colspan='2' width='100%'><table width=100% border=0 cellspacing=0 cellpadding=2><tr>
               <td class='formmezo' width='100%'>$msg</td>
           </tr></table></td></tr>";
}

reset ($fields);
$detype="";

$_group_id=0; // needed to determine if the group_id has been changed, and filter and senders with them (via ajax) as well
while (list($field,$meta)=each($fields)) {
    $value="";
    $widget="";
    if (isset($_POST) && isset($_POST["$field"])) {
        $value=htmlspecialchars(slasher($_POST["$field"],0));
    }
    elseif (isset($_POST["saveall"]) && $meta[0]=="checkbox") {
        $value="";
    }
    elseif (isset($brow) && isset($brow["$field"])) {
        $value=htmlspecialchars($brow["$field"]);
    }
    else {
        if ($field=="sdate") {
            $value=date("Y-m-d H:i");
        }
        elseif ($field=="stime") {
            $value=date("H:i");
        }
        elseif ($field=="stype") {
            $value="now";
        }
        else {
            $value="";
        }
    }
    if ($field=="mod_group_id") {
        $_group_id=$value;
    }

    $varname=$word["st_$field"].":";
    if ($field=="stype") {
        $detype=$value;
    }
    if (!in_array($field,$timingspec) || (isset($timings["$detype"]) && in_array($field,$timings["$detype"]))) {
        if ($meta[0]=="output") {
            $widget="$value";
        }
        elseif ($field=="sdays") {
            $widget="";
            for ($i=0; $i<7; $i++) {
                if (isset($_POST["enter"])) {
                    (isset($_POST["sdays$i"]))?$checked="checked":$checked="";
                }
                else {
                    (substr($value,$i,1)=="X")?$checked="checked":$checked="";
                }
                $widget.="<input type='checkbox' name='$field$i' value='yes' $checked>".$word["st_day$i"]."&nbsp;&nbsp;&nbsp;";
            }
        }
        elseif ($meta[0]=="checkbox") {
            $value=="yes"?$checked="checked":$checked="";
            $widget.="<input type='checkbox' name='$field' value=\"yes\"$checked/>";
        }
        elseif ($meta[0]=="select") {
            if ($field=="smod") {
                $onch="";
                $opts=array("x"=>$word["st_select"]);
                $mimax=$detype=="2hetente"?14:21;
                for ($mi=0;$mi<$mimax;$mi++) {
                    $sr=mysql_query("select from_days(to_days(now())+$mi),mod(to_days(now())+$mi,$mimax)");
                    if ($sr && mysql_num_rows($sr)) {
                        $optname=mysql_result($sr,0,0);
                        $optval=mysql_result($sr,0,1);
                        $opts["$optval"]=$optname;
                    }
                    else {
                        print "2-3 error:";mysql_error();exit;
                    }
                }
            }
            elseif ($field=="stype") {
                $opts=array();
                reset ($timings);
                while (list($tim,$x)=each($timings)) {
                    $opts["$tim"]=$word["st_$tim"];
                }
                $onch=" onchange=\"document.funky.enter.value='2';SetCookie('prevgroupid',$group_id);document.funky.submit();\"";
            }
            else {
                $opts=array("x"=>$word["st_select"]);
                $onch="";
                if ($field=="base_id") {
                    if (!empty($timer_id)) {
                        /*
                        $sql=mysql_query("select group_id from sender_base where id='$value'") or die(mysql_error());
                        $rec=mysql_fetch_array($sql);
                        $w_group_id=$rec['group_id'];
                        */
                        $w_group_id=$group_id;
                    }
                    elseif (!empty($prevgroupid)) {
                        $w_group_id=$prevgroupid;
                    }
                    else {
                        $w_group_id=$group_id;
                    }
                    $q="select id,name from sender_base where (group_id='$w_group_id' or id='$value') order by name";
                    $default="";
                    if (!empty($get_base_id)) {
                        $default=$get_base_id;
                    }
                    $onch=" onchange='load_subject();'";
                }
                elseif ($field=="sender_id") {
                    $q="select u.id,concat(u.email,' <',u.name,'>') as name, if(u.email=concat('".slasher($groupdata["title"])."','@egyperces.hu'),1,0) default_sender from members m, user u 
                        where m.group_id='".((!empty($_group_id))?$_group_id:$group_id)."' and m.membership in ('moderator','owner','support','sender') 
                        and m.user_id=u.id order by u.email";
                }
                elseif ($field=="mod_group_id") {
                    $q="select g.id,concat(g.name,' (',g.title,')') as name from groups g, members m where g.id=m.group_id and m.membership in ('moderator','admin','owner','sender') and user_id='$active_userid' order by g.name";
                    if (!isset($_POST["enter"])) {
                        $value=$group_id;
                    }
                    $onch=" onchange='load_base();'";
                }
                elseif ($field=="message_category_id") {
                    $q="select id,name from message_category where group_id='".((!empty($_group_id))?$_group_id:$fix_group_id)."' order by name";
                }
                else {
                    $q="select id,name from filter where group_id='".((!empty($_group_id))?$_group_id:$fix_group_id)."' and archived='no' order by name";
                }
                $r6=mysql_query($q);
                $default_sender="";
                if ($r6 && mysql_num_rows($r6)) {
                    while ($k6=mysql_fetch_array($r6)) {
                        $opts["$k6[id]"]=htmlspecialchars($k6["name"]);
                        if ($field=="sender_id" && $k6["default_sender"]==1) {
                            $default_sender=$k6["id"];
                        }
                    }
                }
            }
            $widget="<select name='$field' class='oinput' $onch>";
            foreach ($opts as $opt=>$optd) {
                ($value==$opt || ($opt==$default && $field=="base_id") || (empty($value) && $field=="sender_id" && $opt==$default_sender))?$selected="selected":$selected="";
                $widget.="<option $selected value='$opt'>$optd</option>";
                if ($field=="base_id" && empty($get_base_id) && !empty($timer_id)) {
                    $get_base_id=$value;
                }
            }
            $widget.="</select>";
            $widget.="<span id='loading_$field' class='none'><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/loader.gif' alt='loading'></span>";
        }
        elseif ($meta[0]=="textarea") {
            $widget="<textarea name='$field' style='height:150px;' class='oinput'>$value</textarea>";
        }
        else {
            if ($field=="subject") {
                if (empty($value)) {
                    $q="select subject from sender_base where id='$get_base_id' limit 1";
                    //die($q);
                    $r=mysql_query($q);
                    if ($r && mysql_num_rows($r)) {
                        $s=mysql_fetch_array($r);
                        if (!empty($s["subject"])) {
                            $value=$s["subject"];
                        }
                    }
                }
                $widget="<input name='$field' value=\"$value\" maxlength='$meta[2]' class='oinput'/>";
            }
            elseif ($field=="emphasized") {
                if (empty($value)) {
                    $q="select emphasized from sender_base where id='$get_base_id' limit 1";
                    //die($q);
                    $r=mysql_query($q);
                    if ($r && mysql_num_rows($r)) {
                        $s=mysql_fetch_array($r);
                        if (!empty($s["emphasized"])) {
                            $value=$s["emphasized"];
                        }
                    }
                }
                $widget="<input name='$field' value=\"$value\" maxlength='$meta[2]' class='oinput'/>";
            }
            else {
                $widget="<input name='$field' value=\"$value\" maxlength='$meta[2]' class='oinput'/>";
                if ($field=="test_email") {
                    $widget.="<input class='tovabbgomb' type='submit' name='test_button' value='Teszt'>";
                }
            }
        }
        print "<tr><td width='150' class='bgvilagos2'><span class=szoveg>$varname</span></td>
                   <td width='620' class='bgvilagos2'><span class=szoveg>$widget</span></td></tr>\n";
    }
}
print "<TR><TD align='center' colspan='2'><INPUT class='tovabbgomb' type=submit name='saveall' value=\"$word[submit3]\" onclick=\"return confirmSendNow();\"></TD></TR>
       </TABLE>
       </form>\n";	  
  
include "footer.php";

?>
