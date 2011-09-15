<?php	
	function send_mail($to,$from,$subject,$template,$datas){
		include_once 'smarty.config.php';
        
		$mail_to = $to;
		$mail_smarty = new Smarty;
		$mail_smarty->template_dir = $template_dir."emails/";
		$mail_smarty->compile_dir = $compile_dir."emails/";		
		$mail_smarty->assign('data',$datas);
		$mail_body = $mail_smarty->fetch($template);
		$from = ($from == '') ? "admin@hirek.hu" : $from;
		$header = "";
		$header .= "From: $from<$from>\n";
		$header .= "Content-Type: text/html; charset=utf-8 \n";		
        //print "sending mail: $to, $from, $subject, $template";
		return mail($mail_to, $subject, $mail_body, $header);
	}
					
	function make_url($link_url){
		$link_pattern1 = "/(http|ftp):(\\\\|\/\/)(.*?)[#|\\\\|\\?|\\.|\/]*\\s$/"; 
		$link_pattern2 = "/(.*?)[#|\\\\|\\?|\\.|\/]*\\s$/"; 
		$link_pattern  = "/^(http|ftp):(\\\\|\/\/)/";
		preg_match($link_pattern, stripslashes($link_url)." ", $www);				
		if(count($www)!="0"){
			preg_match($link_pattern1, stripslashes($link_url)." ", $matches);
			$link_url = $matches[3];
		}else{						
			preg_match($link_pattern2, stripslashes($link_url)." ", $matches);
			$link_url = $matches[1];
		}				
		$www_link_url = $link_url;
		if(!preg_match("/^(www.)/", $www_link_url)) $www_link_url = "www.".$www_link_url;
		return array($link_url, $www_link_url);
	}
	
	function encodeText($text){
		$special_chars = array(
			":"=>"&#xxx;",
			"|"=>"&#yyy;",
		);
		return strtr($text, $special_chars);
	}
	
	function mysqlFetchAjax($query){
		$result = mysql_query($query) or die(mysql_error());
        $answer = "";
		while($row = mysql_fetch_row($result)){
			$temp = "";
			for($i=0;$i<count($row);$i++){
				if($i==count($row)-1)	$temp .= encodeText($row[$i]);
					else $temp .= encodeText($row[$i])."|";
			}
			$answer .= $temp.":";			
		}
		return $answer;
	}


	function registration($r){
	  if($r[user_name]=='') $error[] = "A `Felhasználó név` mező kitöltése kötelező!";
	  if(!is_valid_email($r[email])) $error[] = "Valós e-mail cím megadása kötelező!";
	  if(check_common_user_by_name($r[user_name])!='') $error[] = "Ezzel az felhasználó névvel már regisztrált valaki!";
	  if(get_user_by_mail($r[email])!='') $error[] = "Ezzel az e-mail címmel már regisztrált valaki!";
	  if($r[password]=='') $error[] = "Jelszó megadása kötelező!";
	  if($r[password]!=$r[password2]) $error[] = "A két jelszó nem egyezik!";
	  if(count($error)==0){
		  $user = get_user_by_mail($r[email]);
		  $bundled_tags[unbundled] = array();
		  if($user){
		    $query = "INSERT INTO users SET user_id='$user[userid]', user_name='$user[username]', password='$user[password]',
						salt='$user[salt]', email='$user[email]',	reg_date='".time()."';";
				db_query($query,DB);
				$_SESSION[pl_logged] = "$user[username]:$user[userid]";
				$user_id = $user[userid];
				$hashedpassword = $user[password];
		  }else{
				$salt = fetch_user_salt();
				$hashedpassword = md5(md5($r[password]).$salt);
				$query = "INSERT INTO common_users SET username='$r[user_name]', password='$hashedpassword',
						passworddate=NOW(), email='$r[email]', salt='".addslashes($salt)."';";
				db_query($query,DB_COMMON);
				$user_id = db_getOne("SELECT LAST_INSERT_ID();");
				$query= "INSERT INTO users SET user_id='$user_id', user_name='$r[user_name]', password='$hashedpassword',
						email='$r[email]', salt='".addslashes($salt)."', reg_date='".time()."';";
				db_query($query,DB);
				$_SESSION[logged] = "$r[user_name]:$user_id";
		  }
			setcookie("login","$user_id:$hashedpassword",time()+60*60*24*30,"/",$__host);
	    return $r[user_name];
	  }else{
	    return $error;
	  }
	}

	function auto_login(){
	  $login_cookie = $_COOKIE[login];
	  if ($login_cookie != ''){
			list($user_id,$hashedpassword) = explode(':',$login_cookie);
			$user_name = db_getOne("SELECT user_name FROM users WHERE user_id='$user_id' AND password='$hashedpassword';");
			if($user_name!=''){
				$_SESSION[logged] = "$user_name:$user_id";
				$host = explode(".", $_SERVER['HTTP_HOST']);
				if(count($host) == 2){
					$__host = $host[0].".".$host[1];
				}else if(count($host) == 3){
					$__host = $host[1].".".$host[2];
				}
				setcookie("login",$login_cookie,time()+60*60*24*30,"/",$__host);
			}
	  }
	}

	function log_in($email,$password){
	  if($email == '' or $password == ''){
	    return -1;
	  }else{
			$salt = db_getOne("SELECT salt FROM users WHERE email='$email';");
			if(!$salt){
				$salt = db_getOne("SELECT salt FROM common_users WHERE email='$email';",DB_COMMON);
				if(!$salt){
				  return -1;
				}else{
					$hashedpassword = md5(md5($password).$salt);
					$common_row = db_getRow("SELECT userid, username FROM common_users WHERE email='$email' And password='$hashedpassword';");
					if(!$common_row){
					  return -1;
					}else{
					  $bundled_tags[unbundled] = array();
						db_query("INSERT INTO users SET user_id='$common_row[userid]', user_name='$common_row[username]', email='$email', password='$hashedpassword', salt='".addslashes($salt)."', reg_date='".time()."';",DB);
						$user_id = $common_row[userid];
						$_SESSION['hirek_hu_logged'] = "$common_row[username]:$user_id";
						$host = explode(".", $_SERVER['HTTP_HOST']);
						if(count($host) == 2){
							$__host = $host[0].".".$host[1];
						}else if(count($host) == 3){
							$__host = $host[1].".".$host[2];
						}
						setcookie("login","$user_id:$hashedpassword",time()+60*60*24*30,"/",$__host);
						return $common_row[username];
					}
				}
			}else{
				$hashedpassword = md5(md5($password).$salt);
				$user = db_getRow("SELECT * FROM users WHERE email='$email' AND password='$hashedpassword';");
				if($user[user_id]==''){
				  return -1;
				}else{
					$_SESSION['hirek_hu_logged'] = "$user[user_name]:$user[user_id]";
					$host = explode(".", $_SERVER['HTTP_HOST']);
					if(count($host) == 2){
						$__host = $host[0].".".$host[1];
					}else if(count($host) == 3){
						$__host = $host[1].".".$host[2];
					}
					setcookie("login","$user[user_id]:$hashedpassword",time()+60*60*24*30,"/",$__host);
					return $user[user_name];
				}
			}
		}
	}

	function is_valid_email($email){
		return preg_match('#^[a-z0-9.!\#$%&\'*+-/=?^_`{|}~]+@([0-9.]+|([^\s]+\.+[a-z]{2,6}))$#si', $email);
	}
	
	function get_user_by_mail($email){
		return db_getRow("SELECT * FROM common_users WHERE email='$email';",DB_COMMON);
	}

	function check_common_user_by_name($username){
		return db_getOne("SELECT username FROM common_users WHERE username='$username';",DB_COMMON);
	}

	function __goto($p_start, $items_pp, $p_nr, $p_current=-1, $items_total=-1){
		global $smarty;
		$total_pages = ceil($items_total/$items_pp);
		if($p_current>=1) $prev = $p_current - 1;
			else $prev = 1;
		if($p_current<$total_pages) $next = $p_current + 1;
			else $next = $total_pages;
		if($p_current<floor($p_nr/2)) $start = 1;
			else $start = $p_current - floor($p_nr/2);
		if($start+$p_nr>=$total_pages && $p_nr<$total_pages) $start = abs($total_pages-$p_nr);
		if($start <= 0)	 $start = 1;
		$end = $p_nr;
		if($start+$end>=$total_pages) $end = abs($total_pages-$start+1);
		for($i=0;$i<$end;$i++) {
			$pages[]=array("link" => $start+$i);
		}

		$__GT=array(
			"pages" => $pages,
			"current" => $p_current,
			"limit" => $items_pp,
			"prev" => array("link" => $prev),
			"next" => array("link" => $next),
			"total_items" => $items_total,
			"total_pages" => $total_pages
		  );
		$smarty->assign("__GT", $__GT);
		return true;
	}

	function time_to_date($timetoconvert){
		return date("Y.m.d H:i:s",$timetoconvert);
	}

	function get_date($timetoconvert,$separator='.'){
		$timetoconvert = ($timetoconvert > 0) ? $timetoconvert : 0;
		return date("Y".$separator."m".$separator."d",$timetoconvert);
	}

	function date_to_time($datetoconvert){
		list($cyear,$cmonth,$cday,$chour,$cminute,$csecond) = split('[-./ :]',$datetoconvert);
		return mktime($chour,$cminute,$csecond,$cmonth,$cday,$cyear);
	}

	function fetchSmarty($query, $db_name=''){
    if($db_name != ''){
			$db = mysql_select_db($db_name, CONNECTION);
    }
		$result = mysql_query($query) or die(mysql_error());
		if(mysql_num_rows($result) > 0){
			$array = array();
			while ($dbrow = mysql_fetch_assoc($result)){
				$tmp_array = array();
				foreach($dbrow as $key=>$value){
					$tmp_array[$key]=$value;
				}
				array_push($array, $tmp_array);
			}
			return $array;
		}else{
		    return false;
		}
	}

	function db_getRow($query,$db_name=''){
    if($db_name != ''){
			$db = mysql_select_db($db_name, CONNECTION);
    }
		$result = mysql_query($query) or die(mysql_error());
		if(mysql_num_rows($result)>0){
			return mysql_fetch_assoc($result);
		}else	return false;
	}

	function db_getOne($query,$db_name=''){
    if($db_name != ''){
			$db = mysql_select_db($db_name, CONNECTION);
    }
		$result = mysql_query($query) or die(mysql_error());
		if(mysql_num_rows($result)>0){
			$ret = mysql_fetch_row($result);
			return $ret[0];
		}else return false;
	}

	function db_getCol($query,$db_name=''){
    if($db_name != ''){
			mysql_select_db($db_name, CONNECTION);
    }
		$result = mysql_query($query) or die(mysql_error());
		if(mysql_num_rows($result)>0){
		    while($row = mysql_fetch_row($result)){
		        $ret[] = $row[0];
		    }
			return $ret;
		}else return false;
	}

	function db_query($query,$db_name=''){
    if($db_name != ''){
			mysql_select_db($db_name, CONNECTION);
    }
    $result = mysql_query($query) or die (mysql_error());
		return $result;
	}

	function printvar($var){
		echo	"<div style='background-color:#efefef'><pre>\n";
		if ($var)
			print_r($var);
		else
			var_dump($var);
		echo	"\n</pre></div>\n";
	}

	function fetch_user_salt($length=3){
		for ($i = 0; $i < $length; $i++)
			$salt .= chr(rand(32, 126));
		return $salt;
	}

?>
