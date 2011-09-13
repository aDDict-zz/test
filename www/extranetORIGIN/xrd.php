<?

ini_set('zlib.output_compression', 'Off');

include "auth.php";

$xrd = get_http('xrd','');
$csvpass = get_http('csvpass','');
$csvuser = get_http('csvuser','');

$params=array();
$xmlreq_id=intval($xrd);
$res=mysql_query("select job_output,group_id,ext_dl,ext_dl_auth,ext_dl_username,ext_dl_password,ext_dl_ips
                  from xmlreq where id='$xmlreq_id'");
if ($res && mysql_num_rows($res)) {
    $dl_path=mysql_result($res,0,0);
    $group_id=mysql_result($res,0,1);
    $ext_dl=mysql_result($res,0,2);
    $ext_dl_auth=mysql_result($res,0,3);
    $ext_dl_username=mysql_result($res,0,4);
    $ext_dl_password=mysql_result($res,0,5);
    $ext_dl_ips=mysql_result($res,0,6);
}
else {
    mx_xrd_logs("no","invalid xmlreq_id");
    exit;
}

$weare=70;
if ($ext_dl=="no") {
    // not an external download, use Maxima's regular cookie based authentication.
    include "cookie_auth.php";
    $mres = mysql_query("select title,num_of_mess,membership 
                         from groups,members where groups.id=members.group_id
                         and groups.id='$group_id' and (membership='owner' or membership='moderator' $admin_addq)
                         and user_id='$active_userid'");
    if ($mres && mysql_num_rows($mres))
        $rowg=mysql_fetch_array($mres);  
    else {
        mx_xrd_logs("no","cookie noauth");
        exit; 
    }
}
else {
    if ($ext_dl_auth=="in-url") {
        // username/password given in the URL
        $ip_ok=0;
        $ips=explode(",",$ext_dl_ips);
        foreach ($ips as $ip) {
            if (strlen($ip) && ($ip==$_SERVER["REMOTE_ADDR"] || ereg("\.$",$ip) && ereg("^$ip",$_SERVER["REMOTE_ADDR"]))) {
                $ip_ok=1;
            }
        }
        if ($ip_ok==0) {
            print "Sikertelen letoltes. $_SERVER[REMOTE_ADDR]";
            $params[]=$ext_dl_ips;
            mx_xrd_logs("no","in-url bad IP");
            exit;
        }
        if ($csvpass!=$ext_dl_password || $csvuser!=$ext_dl_username) {
            print "A letolteshez adja meg a usernevet es a jelszavat.";
            $params[]=$ext_dl_username;
            $params[]=$ext_dl_password;
            $params[]=$csvuser;
            $params[]=$csvpass;
            mx_xrd_logs("no","in-url bad password");
            exit;
        }
    }
    else {
        // Basic HTTP Authentication [ Digest comes with php>=5.1 only :( ]
        if (!isset($_SERVER['PHP_AUTH_USER']) ||  
                    !($_SERVER['PHP_AUTH_USER']==$ext_dl_username && $_SERVER['PHP_AUTH_PW']==$ext_dl_password)) {
            header('WWW-Authenticate: Basic realm="Usernev/jelszo a letolteshez:"');
            header("HTTP/1.0 401 Unauthorized");
            print "A letolteshez adja meg a usernevet es a jelszavat.";
            $params[]=$ext_dl_username;
            $params[]=$ext_dl_password;
            $params[]=$_SERVER['PHP_AUTH_USER'];
            $params[]=$_SERVER['PHP_AUTH_PW'];
            mx_xrd_logs("no","basic http bad password");
            exit;
        }        
    }
}

if (ereg("^($_MX_var->member_import_temp_dir)($group_id-[0-9a-f]{5})/([a-z0-9_-]+).zip$",$dl_path,$regs)) {
    $fs=@filesize($dl_path);
    $params[]=$fs;
    mysql_query("update xmlreq set downloaded='yes' where id='$xmlreq_id'");
    $headers[]="Pragma: public";
    $headers[]="Expires: 0"; // set expiration time
    $headers[]="Cache-Control: must-revalidate, post-check=0, pre-check=0";
    $headers[]="Content-Disposition: attachment; filename=$regs[3].zip";
    $headers[]="Content-Length: $fs";
    $headers[]="Content-Transfer-Encoding: binary";
    $headers[]="Content-Type: application/octet-stream";
    foreach ($headers as $header) {
        //print("$header\n");
        header($header);
    }
    $params[]="*";
    readfile ("$dl_path");
    $params[]="*";
    if ($ext_dl!="yes") {
        @unlink ("$dl_path");
        system("rmdir $regs[1]$regs[2]/");
    }
    mx_xrd_logs("yes","");
    
    /*print ("$dl_path");
    print "<br>";
    print("rmdir $regs[1]$regs[2]/");*/
}
else {
    mx_xrd_logs("no","internal error");
}

function mx_xrd_logs($allowed,$status) {

    global $_MX_var,$xmlreq_id,$params;

    $par=mysql_escape_string(implode("|",$params));
    $_SERVER["REMOTE_ADDR"]=mysql_escape_string($_SERVER["REMOTE_ADDR"]);
    
    mysql_query("insert into xmlreq_logs (xmlreq_id,ip,allowed,status,params,date)
                 values ('$xmlreq_id','$_SERVER[REMOTE_ADDR]','$allowed','$status','$par',now())");

}

?>
