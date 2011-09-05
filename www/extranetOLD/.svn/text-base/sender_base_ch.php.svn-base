<?
include "auth.php";
$weare=24;
if (get_http('base_id','')) $subweare=241;
else $subweare=242;
include "cookie_auth.php";
include "sender_gen.php";

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
    "name"=>array("input","mandatory",100),
    "subject"=>array("subject","",200),
    "emphasized"=>array("emphasized","",200),
    "upload_image"=>array("file","",0),
    "upload_attachment"=>array("file","",0),
    "upload_text_plain"=>array("file2","",0),
    "plain"=>array("textarea","",0),
    "upload_text_html"=>array("file2","",0),
    "html"=>array("textarea","mandatory",0)
);

$htmlprops=array("textcolor","linkcolor","adtextcolor1","adlinkcolor1","adtextcolor2","adlinkcolor2","imptextcolor","implinkcolor",
"text-01","text-02","text-03","text-04","text-05","text-06","text-07","text-08","text-09","text-10",
"text-11","text-12","text-13","text-14","text-15","text-16","text-17","text-18","text-19","text-20"
);
foreach ($htmlprops as $hp) {
    if (ereg("^text-",$hp)) {
        $fields["$hp"]=array("input","",4000);
    }
    else {
        $fields["$hp"]=array("color","",7,"#000000");
    }
}

$base_id=intval($base_id);
$r2=mysql_query("select * from sender_base where group_id='$group_id' and id='$base_id'");
if ($r2 && mysql_num_rows($r2)) {
    $brow=mysql_fetch_array($r2);
}
else {
    $brow=array();
    $base_id=0;
}

$ismsg=0;
$encimages=array();
$contentlist_plain=array();
$contentlist_html=array();
if (isset($_POST["enter"])) {
    reset ($fields);
    $errors=array();
    $sets=array();
    $propsets=array();
    if(isset($_SESSION["maximaupload"])) {
        $ins=array();
        foreach ($_SESSION["maximaupload"] as $u) {
            $ins[]="('$base_id','$u[path]','$u[type]','$u[filesize]','$u[width]','$u[height]')";
        }
        if (count($ins)>0) {
            $ins=implode(',',$ins);
            $query="insert into sender_base_uploaded_files (base_id,path,type,filesize,width,height) values $ins";
            mysql_query($query);
        }
    }
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
        if ($meta[0]=="color" && !eregi("^#[0-9a-f]{3}([0-9a-f]{3})?$",$value)) {
            $errors[]="$word[sb_color_error]: css-$field";
        }
        if (in_array($field,$htmlprops)) {
            if (isset($_POST["${field}_desc"])) {
                $value_desc=slasher(trim($_POST["${field}_desc"]));
            }
            else {
                $value_desc="";
            }
            $propsets["$field"]=array($value,$value_desc);
        }
        elseif ($meta[0]!="file" && $meta[0]!="file2" && $meta[0]!="output") {
            if ($field=="html") {
                $imgsres=mysql_query("select path from sender_base_uploaded_files where base_id='$base_id' and type='image'");
                $imgs=array();
                while ($imgsrec=mysql_fetch_array($imgsres)) {
                    $im=explode('/',$imgsrec['path']);
                    $imgs[]=array(end($im),$imgsrec['path']);
                }
                if (get_magic_quotes_gpc()) {
                    $value=stripslashes($value);
                }
                foreach ($imgs as $k=>$im) {
                    $pattern='/<img src="(\.\/)?'.$im[0].'"/';
                    $replace='<img src="'.$_MX_var->uploadurl.$im[1].'"';
                    $value=preg_replace($pattern,$replace,$value);
                }
                if (get_magic_quotes_gpc()) {
                    $value=addslashes($value);
                }
                $_POST["html"]=$value;
            }
            $sets[]="$field='$value'";
        }
    }
    if (count($errors)==0) {
        $sqldata=implode(",",$sets);
        $msg=$word["data_changed"];
        if ($base_id) {
        	$query="update sender_base set $sqldata where id='$base_id' and group_id='$group_id'";
            mysql_query($query);
           	logger($query,$group_id,"","base_id=$base_id","sender_base");    
        }
        else {
        	$query="insert into sender_base set $sqldata,group_id='$group_id',date=now()";
            mysql_query($query) or die(mysql_error());
            $base_id=mysql_insert_id();
           	logger($query,$group_id,"","","sender_base");
        }
        $succ = mx_mail_generator($base_id,"mime",1);
        if ($succ[0]) {
            $errors[]=$succ[1];
        }
        $encimages=$succ[2];
        $contentlist_plain=$succ[3];
        $contentlist_html=$succ[4];
        mx_html_props($base_id,$propsets);
    }
    if (count($errors)) {
        $ismsg=1;
        $msg=implode("<br>",$errors);
    }
    else {
        $ismsg=2;
    }
    $_SESSION["maximaupload"]=array();
}

$hiddens=array(array("base_id",$base_id),array("group_id",$group_id),array("enter","1"));
$hidden="";
$getvars="";
foreach ($hiddens as $hide) {
    $hidden.="<input type='hidden' name='$hide[0]' value=\"$hide[1]\"/>";
    if ($hide[0]!="enter") {
        $getvars.="&$hide[0]=$hide[1]";
    }
}

$formw=660;

print "<form action='sender_base_ch.php' method='post' name='funky'>$hidden
            <table width=100% border=0 cellspacing=1 cellpadding=0 style='border:1px $_MX_var->main_table_border_color solid;'>
                <tr>
                    <!--<td>
                        <span class='szovegvastag'><a href='sender_base.php?group_id=$group_id'>$word[base]</a></span>
                    </td>-->
                    <td align='right'>
                        <span class='szovegvastag'>&nbsp;<a href='sender_base_preview.php?group_id=$group_id&base_id=$base_id' target='_blank'>$word[base_preview]</a> / <a href='sender_timer_ch.php?group_id=$group_id&base_id=$base_id' target='_blank'>$word[send]</a> <!--<a href='sender_base.php?group_id=$group_id'>$word[back]</a>--></span>
                    </td>
                </tr>";

if ($ismsg) {
    print "<tr><td colspan='2' width='100%'><table width=100% border=0 cellspacing=0 cellpadding=2><tr>
               <td class='formmezo' width='100%'>$msg</td>
           </tr></table></td></tr>";
}
if (count($encimages)) {
    $imglist="";
    foreach ($encimages as $img) {
        $img=htmlspecialchars($img);
        $imglist.='(<a href="javascript:;" onclick="img_preview(\''.$img.'\');">'.$word['preview_image'].'</a>) '.$img.'<br>';
    }
    print "<tr><td colspan='2' width='100%'><table width=100% border=0 cellspacing=0 cellpadding=2><tr>
               <td width='100%'>
               <span class='szovegvastag'>$word[encimages]:<br></span>
               <span class='szoveg'>". $imglist ."</span><br>
               <span class='szovegvastag'>$word[contentlist_plain]:<br></span>
               <span class='szoveg'>". nl2br(htmlspecialchars(implode(", ",$contentlist_plain))) ."</span><br>
               <span class='szovegvastag'>$word[contentlist_html]:<br></span>
               <span class='szoveg'>". nl2br(htmlspecialchars(implode(", ",$contentlist_html))) ."</span>
               </td>
           </tr></table></td></tr>";
}

$r2=mysql_query("select * from sender_base_property where sender_base_id='$base_id'");
if ($r2 && mysql_num_rows($r2)) {
    while ($br=mysql_fetch_array($r2)) {
        $brow["$br[property]"]=$br["value"];
        $brow_desc["$br[property]"]=$br["description"];
    }
}

reset ($fields);
while (list($field,$meta)=each($fields)) {
    $value="";
    $widget="";
    if (isset($_POST) && isset($_POST["$field"])) {
        $value=htmlspecialchars(slasher($_POST["$field"],0));
    }
    elseif (isset($brow) && isset($brow["$field"])) {
        $value=htmlspecialchars($brow["$field"]);
    }
    elseif (isset($meta[3])) {
        $value=$meta[3];
    }
    else {
        $value="";
    }
    if (ereg("^text-",$field)) {
        $varname=strtoupper($field);
    }
    elseif (in_array($field,$htmlprops)) {
        $varname="CSS-" . strtoupper($field);
    }
    else {
        $varname=$word["sb_$field"].":";
    }
    if (in_array($field,$htmlprops)) {
        $desc_value="";
        if (isset($_POST) && isset($_POST["${field}_desc"])) {
            $desc_value=htmlspecialchars(slasher($_POST["${field}_desc"],0));
        }
        elseif (isset($brow_desc) && isset($brow_desc["$field"])) {
            $desc_value=htmlspecialchars($brow_desc["$field"]);
        }
        $desc_value=htmlspecialchars($brow_desc["$field"]);
        $widget="<input name='${field}_desc' value=\"$desc_value\" style='width:160px; font-style: italic; color:#555; border:0; background-color:#eee;'/>
                 <input name='$field' value=\"$value\" style='width:500px;' maxlength='$meta[2]' class='oinput'/>";
    }
    elseif ($meta[0]=="output") {
        $widget="$value";
    }
    elseif ($meta[0]=="radio") {
        $opts=array("yes","no");
        foreach ($opts as $opt) {
            $value==$opt?$checked="checked":$checked="";
            $widget.="<input type='radio' name='$field' value=\"$opt\"$checked/> ".$word["sb_$opt"]."&nbsp;&nbsp;&nbsp;&nbsp;";
        }
    }
    elseif ($meta[0]=="textarea") {
        $widget="<textarea id='textarea_$field' name='$field' style='width:$formw"."px; height:250px;' class='oinput'>$value</textarea>";
    }
    elseif ($meta[0]=="file") {
        $widget="<input type='file' name='$field' class='oinput' id='$field'>";
        $widget.="<span id='loading_$field' class='none'><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/loader.gif' alt='loading'></span>";
        if ($base_id) {
            $widget.="<div>".list_uploaded(intval($base_id),$field)."</div>";
        }
        $widget.="<div id='uploaded_$field' class='none'></div>";
    }
    elseif ($meta[0]=="file2") {
        $widget="<input type='file' name='$field' class='oinput' id='$field'>";
        $widget.="<span id='loading_$field' class='none'><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/loader.gif' alt='loading'></span>";
    }
    else {
        $widget="<input name='$field' value=\"$value\" style='width:$formw"."px;' maxlength='$meta[2]' class='oinput'/>";
    }
    print "<tr><td width='100' class='bgvilagos2'><span class=szoveg>$varname</span></td>
               <td width='670' class='bgvilagos2'><span class=szoveg>$widget</span></td></tr>\n";
    if ($field=="html") {
	print "<TR><TD align='center' colspan='2'><INPUT class='tovabbgomb' type=submit name='saveall' value=\"$word[submit3]\"></TD></TR> \n";	  
    }
}
print "</TABLE>
       </form>\n";	  
  
function mx_html_props ($tid,&$propsets) {

    if ($tid) {
        foreach ($propsets as $prop=>$value) {
            $r=mysql_query("select * from sender_base_property where property='$prop' and sender_base_id='$tid'");
            if ($r && mysql_num_rows($r)) {
                mysql_query("update sender_base_property set value='$value[0]',description='$value[1]' where property='$prop' and sender_base_id='$tid'");
            }
            else {
                mysql_query("insert into sender_base_property set value='$value[0]',description='$value[1]',property='$prop',sender_base_id='$tid'");
            }
        }
    }
}

$_SESSION["maximaupload"]=array();
include "footer.php";
?>
