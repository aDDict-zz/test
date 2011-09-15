<?php
	include('../inc/agencies_functions.php');
	
	switch($_POST['action']){
		case "del_agency":
			if($_POST['agency_id']){
				$query = "Delete From agencies Where agency_id=".$_POST['agency_id'];
				mysql_query($query) or die(mysql_error());				
			}
			header("Location: ".$_SERVER['HTTP_REFERER']);
			exit;
			break;
		case "add_agency":			
			$urls = make_url($_POST['agency_url']);
			$query = "Select agency_id From agencies Where agency_url='"."http://".$urls[0]."' Or agency_url='"."http://".$urls[0]."'";			
			$result = mysql_query($query) or die(mysql_error());
			$row = mysql_fetch_assoc($result);
			if($row['agency_id']==""){
				$dadd = time();
				$query = "Insert into agencies (agency_name, agency_url, agency_favicon, agency_description, agency_type, create_uid, create_date, modify_uid, modify_date) 
						Values ('".$_POST['agency_name']."', '".$_POST['agency_url']."', '".$_POST['agency_favicon']."', '".$_POST['description']."' , ".$_POST['agency_type'].", 
						".$__user_id.", ".$dadd.", ".$__user_id.", ".$dadd.")";
				mysql_query($query) or die(mysql_error());
				$agency_id = mysql_insert_id($connection);
				$_SESSION['message'] = "Sikeres felvitel!";			
				header("Location: ".$_SERVER['HTTP_REFERER']."&agency_id=".$agency_id);
				exit();
			}else{
				$_SESSION['message'] = "Ez a h&iacute;rforr&aacute;s m&aacute;r szerepel az adatb&aacute;zisban!<br />Ha kiv&aacute;nja m&oacute;dos&iacute;tani most megeteheti.";			
				header("Location: ".$_SERVER['HTTP_REFERER']."&agency_id=".$row['agency_id']);
				exit();
			}
			break;
		case "update_agency":
			if($_POST['agency_id']!=""){
				$dadd = time();
				$query = "Update agencies Set 
							agency_name='".$_POST['agency_name']."',
							agency_url='".$_POST['agency_url']."',
							agency_description='".$_POST['description']."',
							agency_favicon='".$_POST['agency_favicon']."',
							agency_type='".$_POST['agency_type']."',
							modify_uid=".$__user_id.",
							modify_date=".$dadd."
							Where agency_id=".$_POST['agency_id']." Limit 1";
				mysql_query($query) or die(mysql_error());			
				$_SESSION['message'] = "Sikeres m&oacute;dos&iacute;t&aacute;s!";
				header("Location: ".$_SERVER['HTTP_REFERER']."&agency_id=".$_POST['agency_id']);
				exit();
			}
			break;
		case "del_news_flow":
			if($_POST['rss_id']){
				$query = "Delete From rss_feeds Where id=".$_POST['rss_id'];
				mysql_query($query) or die(mysql_error());
				$query = "Delete From rss_categories Where rss_id=".$_POST['rss_id'];
				mysql_query($query) or die(mysql_error());
			}
			header("Location: ".$_SERVER['HTTP_REFERER']);
			exit;
			break;
		case "add_rss":
			$urls = make_url($_POST['rss_url']);
			$query = "Select id From rss_feeds Where rss_url='"."http://".$urls[0]."' Or rss_url='"."http://".$urls[0]."'";						
			$result = mysql_query($query) or die(mysql_error());
			$row = mysql_fetch_assoc($result);
			if($row['id']==""){
				$dadd = time();
				if($_POST['type']==1){				
					$query = "Insert into rss_feeds (feed_type, agency_id, rss_name, rss_url, rss_description, period, create_uid, create_date, modify_uid, modify_date, status, news_order) 
							Values (".$_POST['type'].", '".$_POST['agencies']."', '".$_POST['rss_name']."', '".$_POST['rss_url']."', '".$_POST['description']."',  ".$_POST['period'].", 
							".$__user_id.", ".$dadd.", ".$__user_id.", ".$dadd.", ".$_POST['status'].", '" . $_POST['news_order'] ."')";
					mysql_query($query) or die(mysql_error());			
				}else{
					$matches = $_POST['match_link'].",".$_POST['match_title'].",".$_POST['match_lead'];
					$query = "Insert into rss_feeds (feed_type, agency_id, rss_name, rss_url, rss_description, pattern, matches, aux_url, period, create_uid, create_date, modify_uid, modify_date, status, news_order) 
							Values (".$_POST['type'].", '".$_POST['agencies']."', '".$_POST['rss_name']."', '".$_POST['rss_url']."', '".$_POST['description']."', '".addslashes($_POST['pattern'])."', '".$matches."', '".$_POST['aux_url']."', ".$_POST['period'].", 
							".$__user_id.", ".$dadd.", ".$__user_id.", ".$dadd.", ".$_POST['status'].", '" . $_POST['news_order']."')";
							
					mysql_query($query) or die(mysql_error());			
				}
				$rss_id = mysql_insert_id($connection);
				$_SESSION['message'] = "Sikeres felvitel!";
				header("Location: ".$_SERVER['HTTP_REFERER']."&rss_id=".$rss_id);
				exit();
			}else{
				$_SESSION['message'] = "Ez a h&iacute;rfolyam m&aacute;r szerepel az adatb&aacute;zisban!<br />Ha kiv&aacute;nja m&oacute;dos&iacute;tani most megeteheti.";			
				header("Location: ".$_SERVER['HTTP_REFERER']."&rss_id=".$row['id']);
				exit();
			}
			break;
		case "update_rss":
			if($_POST['rss_id']!=""){
				$dadd = time();
				if($_POST['type']=="1"){
					$query = "Update rss_feeds Set 
								feed_type=".$_POST['type'].",
								agency_id='".$_POST['agencies']."',
								rss_name='".$_POST['rss_name']."',
								rss_url='".$_POST['rss_url']."',
								rss_description='".$_POST['description']."',
								period=".$_POST['period'].",
								modify_uid=".$__user_id.",
								modify_date=".$dadd.", 
								status=".$_POST['status'].",
                                news_order='".$_POST['news_order']."'
								Where id=".$_POST['rss_id']." Limit 1";
					mysql_query($query) or die(mysql_error());			
				}else{
					$matches = $_POST['match_link'].",".$_POST['match_title'].",".$_POST['match_lead'];
					$query = "Update rss_feeds Set 
								feed_type=".$_POST['type'].",
								agency_id='".$_POST['agencies']."',
								rss_name='".$_POST['rss_name']."',
								rss_url='".$_POST['rss_url']."',
								rss_description='".$_POST['description']."',
								pattern='".addslashes($_POST['pattern'])."',
								matches='".$matches."',
								aux_url='".$_POST['aux_url']."',
								period=".$_POST['period'].",
								modify_uid=".$__user_id.",
								modify_date=".$dadd.", 
								status=".$_POST['status'].",
                                news_order='".$_POST['news_order']."'
								Where id=".$_POST['rss_id']." Limit 1";					
					mysql_query($query) or die(mysql_error());			
				}
				$_SESSION['message'] = "Sikeres m&oacute;dos&iacute;t&aacute;s!";
				header("Location: ".$_SERVER['HTTP_REFERER']."&rss_id=".$_POST['rss_id']);
				exit();
			}
			break;
	}
	
	switch($__sub_id){
		case 1:
			$smarty->assign('agency_name', $_GET['agency_name']);
			$smarty->assign('agency_url', $_GET['agency_url']);
			$smarty->assign('agency_type', $_GET['agency_type']);				
			if($_GET['total'])	$total_pages = $_GET['total'];						
				else $total_pages = get_agencies_result($_GET['agency_name'], $_GET['agency_url'], $_GET['agency_type']);			
			$max_page_nr=10;
			
			if($_GET['page'])	$ppage= $_GET['page'];
				else $ppage= 1;		
			if ($ppage > $max_page_nr){
				$p_start=floor(($ppage-1) / $max_page_nr);
				$p_start=$p_start*$max_page_nr+1;
			}else
				$p_start=1; 			
			if($_GET['plimit'])		 $plimit = $_GET['plimit'];
				else $plimit = 10;  
			$url = "id=3&sub_id=1&agency_name=".$_GET['agency_name']."&agency_url=".$_GET['agency_url']."&agency_type=".$_GET['agency_type'];				
			$smarty->assign('url', $url);
			$smarty->assign('total_pages', $total_pages);
			__goto($p_start, $plimit, $max_page_nr, $ppage, $total_pages);				
					
			$smarty->assign('agencies', get_agencies($_GET['agency_name'], $_GET['agency_url'], $_GET['agency_type'],$ppage-1, $plimit));
			break;
		case 2:			
			$smarty->assign('message', $_SESSION['message']);
			$_SESSION['message'] = "";			
			if($_GET['agency_id']) $smarty->assign('agency', get_agency_by_id($_GET['agency_id']));
			break;	
		case 3:
			$smarty->assign('rss', get_rss_feeds())	;
			break;	
		case 4:
			$smarty->assign('message', $_SESSION['message']);
			$_SESSION['message'] = "";	
			if($_GET['rss_id']) $smarty->assign('rss', get_rss_feed_by_id($_GET['rss_id']));
			$smarty->assign('agencies', get_agencies());
			break;	
	}
?>
