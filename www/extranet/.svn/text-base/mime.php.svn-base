<?
include "auth.php";
$weare=20;

include "mime_str.php";

// print mx_html_email_encode(29683,"teszt101017@example.com");
// http://office.manufacture.co.yu/maxima/mime.php
// http://office.manufacture.co.yu/maxima/mime.php?preview=ccd5a73f345d86d12b339,teszt101017@example.com

$group_id=get_http("group_id",0);
$message_id=get_http("message_id",0);
$plaintext=get_http("plaintext",0);
$xml=get_http("xml",0);
$for_egyperc=0;

$unsubscribe_mail_prefix="leir";
$local_hostname="maxima.hu";
$userdata=array();
$is_html_view=0;
include "_htmlview_auth.php";
if ($is_html_view==0 && !$for_egyperc) {
    include "cookie_auth.php";
    if ($active_membership=="affiliate") {
        exit;
    }
    $mres = mysql_query("select title,num_of_mess,membership from groups,members 
                         where groups.id=members.group_id and groups.id='$group_id' 
                         and (membership='owner' or membership='moderator' or membership='client' or membership='support' $admin_addq)
                         and user_id='$active_userid'");
    if ($mres && mysql_num_rows($mres)) {
        $rowg=mysql_fetch_array($mres);  
    }
    else {
        exit;
    }
    !empty($admin_addq)?$act_memb="moderator":$act_memb=$rowg["membership"];
    if ($act_memb=="client") {
        $rcl=mysql_query("select * from message_client where message_id='$message_id' and user_id='$active_userid'");
        if (!($rcl && mysql_num_rows($rcl))) {
            exit;
        }
    }
}

$message_id=intval($message_id);
$res=mysql_query("select * from messages where group_id='$group_id' and id='$message_id'");
if ($res && mysql_num_rows($res))
    $row=mysql_fetch_array($res);
else
    exit;

mysql_query("set names latin1");
$query=mysql_query("select header,body,ads_egyperc from mailarchive where id='$message_id'");
if ($query && mysql_num_rows($query)) {
  $l=mysql_fetch_row($query);
  mysql_query("set names utf8");
  // substitute ads with empty gifs.
  $l[1]=ereg_replace("\{ad-img-[0-9]+\}","",$l[1]);
  // remove unsubstituted contents
  $l[1]=eregi_replace("{p-[^}]+}","",$l[1]);
  $l[1]=eregi_replace("{h-[^}]+}","",$l[1]);

  if ($is_html_view==1) {
    mx_make_user_tags($l[1]);
	$l[1]=eregi_replace("{[a-z_]+}","",$l[1]);
  }
  $string=$l[0]."\n\n".$l[1];
  $deblen0=strlen($l[0]);
  $deblen1=strlen($l[1]);
  $string=ereg_replace("\r\n","\n",$string);
  $string=ereg_replace("\n","\n",$string);
  #print "&lt;DEBUG&gt; header length:$deblen0 body length:$deblen1<br><br>";
  $ads_egyperc=$l[2];
}
mysql_query("set names utf8");

if ($is_html_view || $for_egyperc) {
    $index="1&preview=$preview";
}
else {
    $index="1&message_id=$message_id&group_id=$group_id";
}

$num_parts = 1;
$bodypart = 1;
$textparts = 0;
$atc = '';
$msg = '&nbsp;';
if ( !str_fetchstructure($string,0,strlen($string))) 
  echo('Not a valid string');
if (!empty($images)) {
  while (list($img, $view) = each($images)) {
    if (strlen($img) > 0) {
      if ($img[0] == '<') $img = substr($img, 1);
      if ($img[strlen($img)-1] == '>') $img = substr($img, 0, strlen($img)-1);
      $msg = str_replace("cid:$img", $view, $msg);
    }
  }
}

if (!empty($images_not_inline)) {
  $msg .= '<hr>';
  while (list($img, $view) = each($images_not_inline)) {
    if (strlen($img) > 0) {
      $msg .= '<img src="'.$view.'"><br>';
    }
  }
}

if (!$is_html_view) {
    $msg=ereg_replace("\{l\}([^\{]+)\{/l\}","\\1",$msg);
    $msg=ereg_replace("\{l[ \r\n][^\}]+\}([^\{]+)\{/l\}","\\1",$msg);
}
if ($for_egyperc && $plaintext) {
    $msg = preg_replace('/.*{real-content-start}/is','',$msg);
    $msg = preg_replace('/{real-content-end}.*/is','',$msg);
    $msg = preg_replace('|(\w+)://([^\s"<]{0,25})([^\s"<]*)([\w#?/&=])|', '<A href="\1://\2\3\4" target="_blank">\1://\2...</A>', $msg);
    $msg=ereg_replace("</?pre>","",$msg);
    $msg=ereg_replace("^.*{html_version_link}","",$msg);
    $msg=ereg_replace("__+","",$msg);
    $msg=ereg_replace("^[\r\n]+","",$msg);
    $msg=ereg_replace("{egyperceslink}","",$msg);
}
elseif ($for_egyperc || $is_html_view) {
    $egyperceslink="http://www.egyperc.hu/forum/uzenet/id/$message_id";
    if ($for_egyperc) {
        $msg=ereg_replace("\"{egyperceslink}\"","\"#\" onclick=\"opener.location='$egyperceslink'; window.close();\"",$msg);
        $msg=ereg_replace("'{egyperceslink}'","'#' onclick='opener.location=\"$egyperceslink\"; window.close();'",$msg);
    }
    else {
        $msg=ereg_replace("{egyperceslink}",$egyperceslink,$msg);
    }
}

$eads=explode("\n",$ads_egyperc);
foreach ($eads as $ead) {
    if (ereg("^([0-9]+)\|(text|upload|ctnet)\|(.*)",$ead,$erg)) {
        if ($erg[2]=="text" || $erg[2]=="ctnet") {
            $banner=base64_decode($erg[3]);
        }
        else {
            $banner=$erg[3];
        }
        $replp=$erg[2]=="ctnet"?"ct":"";
        $msg=str_replace("{ad-${replp}link-$erg[1]}",$banner,$msg);
    }
}
$msg=ereg_replace("{ad-link-([0-9]+)}","<script type='text/javascript' src='http://ad.hirekmedia.hu/outs/getall.php?a=\\1&ssr=1'></script>",$msg);
$msg=ereg_replace("{ad-ctlink-([0-9]+)}","",$msg);

$subject=htmlspecialchars(quoted_printable_decode(decode_mime_string($row["subject"])));
$msg=eregi_replace("<title>[^<]*</title>","<title>$subject</title>",$msg);

if ($is_html_view==1) {
    $msg=eregi_replace("<br><a[^>]+>AMENNYIBEN AL.BBI LEVEL.NK OLVASHATATLAN FORM.BAN VAGY K.PEK N.LK.L JELENIK MEG, K.RJ.K KATTINTSON IDE!</a><br><br>","",$msg);
    $msg=ereg_replace("<!-- *weblink_start *-->","<!--",$msg);
    $msg=ereg_replace("<!-- *weblink_end *-->","-->",$msg);
}

if ($for_egyperc && $xml) {
    
    $subject=htmlspecialchars(quoted_printable_decode(decode_mime_string($message_subject)));
    $msg=htmlspecialchars($msg);

	$msg = preg_replace('/\x03/', '', $msg);

    $xmlfile="<?xml version=\"1.0\" encoding=\"utf-8\"?>
<message>
<id>$message_id</id>
<group_id>$group_id</group_id>
<cim>$subject</cim>
<datum>$message_date</datum>
<text><![CDATA[
$msg
]]></text>
</message>";

    print $xmlfile;
}
else {
    echo $msg;
}

//echo '<table>'.$atc.'</table>';


function mx_make_user_tags(&$message) {

    global $_MX_var,$hw_udat,$group_name,$local_hostname,$message_id,$unsubscribe_mail_prefix,$group_id;

    $hw_udat["ui_vezeteknev"]=mx_name_normalize($hw_udat["ui_vezeteknev"]);
    $hw_udat["ui_keresztnev"]=mx_name_normalize($hw_udat["ui_keresztnev"]);

    $demog_var_enums=array();
    $demog_var_needed=array();
    $demog_var_groups=array();
    $demog_var_types=array();
    $demog_var_ids=array();
    $demog_var_multiselects=array();
    $st = mysql_query("select distinct demog.id,variable_name,variable_type,multiselect 
                       from vip_demog,demog where vip_demog.demog_id=demog.id and vip_demog.group_id='$group_id'");
    while ($row = mysql_fetch_row($st)) {
        $demog_var_ids["$row[1]"]=$row[0];
        $demog_var_types["$row[1]"]=$row[2];
        $demog_var_multiselects["$row[1]"]=$row[3];
    }
    foreach ($hw_udat as $var=>$value) {
        // single demog var or multiple demog vars in braces separated by spaces
        if (ereg("^ui_([0-9a-z_]+)$",$var,$rg)) {
            $variable_name=$rg[1];
            if (preg_match("/\{$variable_name\}/i",$message)) {
                $demog_var_needed[]=$variable_name;        # hash of demog vars to be selected from users_* tables
                $demog_var_groups[]=$variable_name;    # hash of demog groups to be substituted in custom messages
            }
            if (preg_match("/\{$variable_name-url\}/i",$message)) {
                $demog_var_needed[]=$variable_name;        # hash of demog vars to be selected from users_* tables
                $demog_var_groups[]=$variable_name;    # hash of demog groups to be substituted in custom messages
            }
            if (preg_match("/\{$variable_name( [<a-z][^}]*)\}/i",$message,$rg)) {
                $demog_var_needed[]=$variable_name;        # hash of demog vars to be selected from users_* tables
                $demog_var_groups[]="$variable_name$rg[1]";    # hash of demog groups to be substituted in custom messages
                $otherdemogs = split(" ",$rg[1]);
                foreach ($otherdemogs as $otherd) {
                    $demog_var_needed[]=$otherd;
                }
            }
            if ($demog_var_types["$variable_name"]=="enum") {
                $demog_id=$demog_var_ids["$variable_name"];
                $q2 = "select id,enum_option from demog_enumvals where demog_id='$demog_id'";
                $stdi2 = mysql_query($q2);
                while ($row = mysql_fetch_row($stdi2)) {
                    $enumid=$row[0];
                    $demog_var_enums["$variable_name"]["$enumid"]=$row[1];
                }
            }
        }
    }
    foreach ($demog_var_groups as $dvg) {
        $dvgs = split(" ", $dvg);
        $dvg_r = "";
        $glue = "";
        $char_replace="";
        foreach ($dvgs as $dvgs_part) {
            if (strlen($dvgs_part) && isset($hw_udat["ui_$dvgs_part"])) {
                $subpart=$hw_udat["ui_$dvgs_part"];
                if ($demog_var_types["$dvgs_part"]=="enum") {
                    $enumvals="";
                    $enumids = split (",",$subpart);
                    foreach ($enumids as $valui) {
                        $valui=intval($valui);
                        if ($valui) {
                            if (strlen($enumvals)) {
                                $enumvals.=";";
                            }
                            $enumvals.=$demog_var_enums["$dvgs_part"]["$valui"];
                        }
                    }
                    $subpart=$enumvals;
                }
                $dvg_r .= "$glue$subpart";
            }
            elseif (preg_match("/^<([^>]+)>/",$dvgs_part)) {
                $char_replace=$rg[1];
            }
            else {
                $dvg_r .= "$glue$dvgs_part";
            }
            $glue=" ";
        } 
        if (strlen($char_replace)) {
            $dvg_r = mx_char_replace($dvg_r,$char_replace);
        }
        $message=str_replace("{" . "$dvg}",$dvg_r,$message);
        $dvg_r = rawurlencode($dvg_r);
        $dvg_r = str_replace("_","%5F",$dvg_r);
        $dvg_r = str_replace("%","_",$dvg_r);
        $dvg_r = "__mue__" . $dvg_r;
        $message=str_replace("{" . "$dvg-url}",$dvg_r,$message);
    }
    //print("<!--select url,unique_id,url_replace,name from feedback where message_id='$message_id'-->");
    $st = mysql_query("select url,unique_id,url_replace,name from feedback where message_id='$message_id'");
    while ($row = mysql_fetch_row($st)) {
        $url=$row[0];
        if (strlen($row[2])) {
            $url="<$row[2]> $url";
        }
        $unique_id=$row[1];
        $link_name=$row[3];
        $unique_url = "http://$group_name.$local_hostname/re/". substr($unique_id,0,3). "$hw_udat[id]" . substr($unique_id,-7) . "z";
        $message = str_replace("{l$link_name}$url{/l}","$unique_url",$message);
    }
    $md5 = md5("$group_id:$message_id:mojnedapitas:$hw_udat[id]");
    $message=str_replace("{unsubscribelink}","http://${unsubscribe_mail_prefix}.${local_hostname}/$group_id/$message_id/$hw_udat[id]/${md5}z",$message);
    $message=str_replace("{html_version_link}","http://${group_name}.${local_hostname}/htmlview/" . mx_html_email_encode($message_id,$hw_udat["id"]),$message);
    $message =str_replace("{groupname}",$group_name,$message);
    $go1x1="http://$group_name.$local_hostname/go1x1/$message_id/$hw_udat[id]";
    $message=str_replace("{1x1}",$go1x1,$message);
    $message=str_replace("{unsubscribe}","${unsubscribe_mail_prefix}-$message_id\@$group_name.${local_hostname}",$message);
    $message=str_replace("{userid}","$hw_udat[id]",$message);
}

function mx_html_email_encode($message_id,$email) {

    $r2=mysql_query("select create_date from messages where id='$message_id'");
    if ($r2 && mysql_num_rows($r2)) {
        $k=mysql_fetch_array($r2);
        $md5=substr(md5("$k[create_date]DombosFest2007$email$message_id"),3,17);
        return substr($md5,0,5) . base_convert($message_id,10,16) . substr($md5,5) . "," . $email;
    }
    return "*";
}

function mx_char_replace($data,$replace) {

    for ($i=0;$i<strlen($replace);$i+=2) {
        if (strlen(substr($replace,$i+1,1))) {
            $from = substr($replace,$i,1);
            $to = substr($replace,$i+1,1);
            $data = str_replace($from,$to);
        }
    }
    return $data;
}

function mx_name_normalize($to) {

    // todo: for all including non-utf8!
    $rri = explode(" ",$to);
    $final_subs_text = "";
    foreach ($rri as $subs_text) {
        $encoding="utf-8";
        $slen=mb_strlen($subs_text,$encoding);
        if ($slen>0) {
            $final_subs_text .= mb_strtoupper(mb_substr($subs_text,0,1,$encoding),$encoding);
            if ($slen>1) {
                $final_subs_text .= mb_strtolower(mb_substr($subs_text,1,$slen-1,$encoding),$encoding);
            }
        }
        $final_subs_text .= " ";
    }
    $final_subs_text=ereg_replace(" +$","",$final_subs_text);
    return $final_subs_text;
}
?>
