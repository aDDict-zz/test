<?
include 'auth.php';

$uid=$_GET["uid"];

$qmark="?";
$spec_conn=array("/"=>"/","q/"=>"?");
$uidparts=array();
$customparams="";
$uid=str_replace("__pipe__","|",$uid);
if (ereg("\|",$uid)) {
    $uidparts=explode("|",$uid);
    $uid=$uidparts[0];
}
$member_part="";
if (ereg("^(m|u)(.+)$",$uid,$urs)) {
    $uid=$urs[2];
    $urs[1]=="u"?$member_part="auto_":$member_part="member_";
}
$from_html_version='no';
if (ereg("^(.+)z$",$uid,$urz)) {
    $uid=$urz[1];
    $from_html_version='yes';
}
$unique_id = mysql_escape_string(substr($uid,0,3).substr($uid,-7));
$userid = mysql_escape_string(substr($uid,3,strlen($uid)-10));
$query = mysql_query("select id,url,url_replace,message_id from ${member_part}feedback where unique_id='$unique_id'");
if ($query && mysql_num_rows($query)) {
    $l = mysql_fetch_row($query);
    if (count($uidparts)) {
        $hashpart="";
        $cparts=array();
        if (count($uidparts)==2) {
            if (ereg("^__mue__(.+)",$uidparts[1],$ugs)) {
                $customparams=rawurldecode(str_replace("_","%",$ugs[1]));
            }
            else {
                $customparams=mx_url_prepare($uidparts[1],$l[2]);
            }
        }
        elseif (count($uidparts)==3 && isset($spec_conn["$uidparts[1]"])) {
            $customparams=mx_url_prepare($uidparts[2],$l[2]);
            $qmark=$spec_conn["$uidparts[1]"];
        }
        else {
            for ($i=1;$i<count($uidparts);$i+=2) {
                if ($uidparts[$i]=="hash") {
                    // tehat ha tobb hash erteket adnak meg veletlenul, akkor az utolsot veszi
                    $hashpart="#".mx_url_prapare($uidparts[$i+1],$l[2]);
                }
                else {
                    if (ereg("^__mue__(.+)",$uidparts[$i+1],$ugs)) {
                        $cparts[]="$uidparts[$i]=" . rawurldecode(str_replace("_","%",$ugs[1]));
                    }
            else {
                $cparts[]=rawurlencode($uidparts[$i])."=".mx_url_prepare($uidparts[$i+1],$l[2]);
            }
                }
            }
            $customparams=implode("&",$cparts);
        }
    }
	$HTTP_USER_AGENT=mysql_escape_string($_SERVER["HTTP_USER_AGENT"]);
	$ra=mysql_escape_string($_GET["ra"]);
    mysql_query("insert into ${member_part}trackf ( id, user_id, feed_id, date, http_user_agent, remote_addr,from_html_version) values ( '0', '$userid', '$l[0]', now(), '$HTTP_USER_AGENT', '$ra','$from_html_version')");
	$group_name=mysql_escape_string($_GET["group_name"]);
    if (empty($member_part)) {
        mysql_query("update users_$group_name set last_clicked=now() where id='$userid'");
        mysql_query("update users_$group_name set clicklist=concat(clicklist,',$l[3],') where id='$userid' and clicklist not like '%,$l[3],%'");
    }
    $url=$l[1];
    if (!empty($customparams)) {
        $urlhash="";
        if (ereg("^(.+)(#[^#]*)$",$url,$urgs)) {
            $url=$urgs[1];
            $urlhash=$urgs[2];
        }
        // feltetelezzuk, hogy vagy csak az url-ben vagy csak a parameterkben lesz hash; 
        // ha mind a kettoben, akkor nem sok okosat lehet csinalni, igy a legjobb, es eszreveszik a hibat... 
        if (ereg("\?",$url)) {
            $url="$url&$customparams$urlhash$hashpart";
        }
        else {
            $url="$url$qmark$customparams$urlhash$hashpart";
        }
    }
    $url = preg_replace("[\r\n]","",$url);
    if ($group_name=="zigor2") {
        print("Location: $url");
    }
    else {
        header("Location: $url");
    }
}

function mx_url_prepare($url,$replace) {

    for ($i=0;$i<strlen($replace);$i+=2) {
        if (strlen(substr($replace,$i+1,1))) {
            $url=str_replace(substr($replace,$i,1),substr($replace,$i+1,1),$url);
        }
    }
    //return rawurlencode($url);
    return $url;
}
?>
