<?
function get_url() {
	return $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
}

function logger($query="",$group_id=0,$action="",$info="",$table_name="",$multigroup=0) {
	global $_MX_var,$active_userid,$weare,$sgweare,$adminweare;

    $logger_actions=array("insert"=>"hozzáadás","delete"=>"törlés","update"=>"módositás","select"=>"lekérdezés","login"=>"bejelentkezés","logout"=>"kijelentkezés");

	$qr=strtolower(substr($query,0,6));
	if ($action==null && $query!=null) 
		$action=$logger_actions[$qr];
	else
		$action=$logger_actions[$action];	
	if ($action==null) $action=$logger_actions["select"];	
	if ($active_userid==null) $active_userid=0;
	$query=mysql_escape_string($query);
	$info=mysql_escape_string($info);
    $weare=intval($weare);
    $sgweare=intval($sgweare);
	$query="insert into tracking set user_id=$active_userid,date=now(),table_name='$table_name',action='$action',group_id=$group_id,url='".get_url()."',query='$query',info='$info',weare='$weare',sgweare='$sgweare',adminweare='$adminweare',multigroup='$multigroup'";
//	echo $query;
	mysql_query($query);
	$fh = fopen($_MX_var->logfile_path, 'a') or die("Error 3");
	$stringData = $query."\n";
	fwrite($fh, $stringData);
	fclose($fh);	
}

function mx_ppos_unsub($email,$groups,$source="php_manual") {

    global $_MX_var;

    if ($pp=popen("$_MX_var->unsub_ppos_script $email $groups $source","r")) {
        while ($buff=fgets($pp,25000)) {
            $ppres.=$buff;
        }
        pclose($pp);
        $pparr = explode("\n",$ppres);
        return $pparr[0];
    }
    else {
        return "script_noopen";
    }
}

function rewrite_rule_string($string,$tolower=1) {

    $trans=array("á"=>"a", "Á"=>"A", "é"=>"e", "É"=>"E", "í"=>"i", "Í"=>"I", "Ó"=>"O", "ó"=>"o", "ö"=>"o", "Ő"=>"O", "Ö"=>"O", "ü"=>"u", "ő"=>"o", "ú"=>"u", "Ú"=>"U", "Ü"=>"U", "Ű"=>"U", "ű"=>"u", "Č"=>"C", "č"=>"c", "Ć"=>"C", "ć"=>"c", "Đ"=>"DJ", "đ"=>"dj", "Š"=>"S", "š"=>"s", "Ž"=>"Z", "ž"=>"z");
    $string=strtr($string,$trans);
    $string=preg_replace('/[!?]/',"",$string);
    $string=preg_replace('/[^a-zA-Z0-9\.-_]+/',"-",$string);
    $string=preg_replace('/-{2,}/',"-",$string);
    $string=preg_replace('/(^-|-$)/',"",$string);

    if ($tolower) {
        $string=strtolower($string);
    }
    return $string;
}

function mx_csv_headers($filename) {

    global $debug;
    
    if (!$debug) {
        $headers[]="Pragma: public";
        $headers[]="Expires: 0"; // set expiration time
        $headers[]="Cache-Control: must-revalidate, post-check=0, pre-check=0";
        $headers[]="Content-Disposition: attachment; filename=$filename";
        $headers[]="Content-Transfer-Encoding: binary";
        $headers[]="Content-Type: text/csv";
        foreach ($headers as $header) {
            header($header);
        }
    }
}

?>
