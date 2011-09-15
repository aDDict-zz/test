<?php
	
	function deleteUserById($user_id){
		$query = "Delete From users2 Where user_id=".$user_id." Limit 1";
		mysql_query($query) or die(mysql_error());
		$query = "Delete From user_default_pages2 Where user_id=".$user_id;
		mysql_query($query) or die(mysql_error());
		$query = "Delete From user_feeds2 Where user_id=".$user_id;
		mysql_query($query) or die(mysql_error());
		$query = "Delete From user_pages2 Where user_id=".$user_id;
		mysql_query($query) or die(mysql_error());
		$query = "Delete From user_webnotes2 Where user_id=".$user_id;
		mysql_query($query) or die(mysql_error());
	}
	
	function updateUser($user){
		$query = "Update users2 Set user_email='".$user['user_email']."', user_password='".$user['user_password']."' Where user_id=".$user['user_id'];
		mysql_query($query) or die(mysql_error());
	}
	
	function getUserById($uid){
		$query = "Select user_id, user_email, user_password From users2 Where user_id=".$uid;
		$result = mysql_query($query) or die(mysql_error());
		return mysql_fetch_assoc($result);
	}
	
	function getUsersNr($email){
		$email = explode(' ', trim($email));
		$where = '';
		$l=0;
		for($i=0;$i<count($email);$i++){
			if(trim($email[$i])!=''){
				if($l==0) $where = " Where user_email like '%".$email[$i]."%' ";
					else $where .= " And user_email like '%".$email[$i]."%' ";
				$l++;	
			}
		}
		
		$query = "Select count(user_id) as nr From users2 ".$where." ";
		$result = mysql_query($query) or die(mysql_error());
		$ret = mysql_fetch_assoc($result);
		return $ret['nr'];
	}
	
	function getUsers($email, $from=0, $limit=0){
		$email = explode(' ', trim($email));
		$where = '';
		$l=0;
		for($i=0;$i<count($email);$i++){
			if(trim($email[$i])!=''){
				if($l==0) $where = " Where user_email like '%".$email[$i]."%' ";
					else $where .= " And user_email like '%".$email[$i]."%' ";
				$l++;	
			}
		}
		if($from == 0 && $limit == 0) $limit = '';
			else $limit = "Limit ".($from*$limit).", ".$limit;

		$query = "Select user_id, user_email From users2 ".$where." ".$limit;
		$result = mysql_query($query) or die(mysql_error());
		$i=0;
		while($row = mysql_fetch_assoc($result)){
			$users[$i] = array(
				'user_id'=>$row['user_id'],
				'user_email'=>$row['user_email']
			);
			$i++;
		}
		return $users;
	}
	
?>
