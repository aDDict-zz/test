<?php 
	session_start();
	
	header("Content-type: text/html; charset=utf-8");				
	
	if($_SESSION['hirek_admin_logged']!=""){
		list($__user_id, $__user_name) = explode(":", $_SESSION['hirek_admin_logged']);
	}
	
	
	if($__user_id!=""){
		include("../inc/db_prop.inc.php");
		include("../inc/common.php");
		include("../inc/agencies_functions.php");
		
		global	$db, $connection;
		$connection = mysql_connect($host, $user, $psw) or die(mysql_error());
		mysql_select_db($data_base, $connection) or die(mysql_error());
		mysql_query("SET NAMES 'utf8'") or die(mysql_error());
		
		switch($_REQUEST['action']){
			case "get_css":
					$query = "Select id, name From cat_css Order by name";					
					echo(mysqlFetchAjax($query));
				break;			
			case "post_rss":				
					$agency_id = $_REQUEST['agency_id'];					
					$urls = make_url($_REQUEST['rss_url']);
					$query = "Select id From rss_feeds Where rss_url='"."http://".$urls[0]."' Or rss_url='"."http://".$urls[1]."'";						
					$result = mysql_query($query) or die(mysql_error());
					$row = mysql_fetch_assoc($result);
					if($row['id']==""){
						$dadd = time();
						if($_REQUEST['type']==1){				
							$query = "Insert into rss_feeds (feed_type, agency_id, rss_name, rss_url, rss_description, period, create_uid, create_date, modify_uid, modify_date, status, news_order) 
									Values (".$_REQUEST['type'].", '".$_REQUEST['agencies']."', '".$_REQUEST['rss_name']."', '".$_REQUEST['rss_url']."', '".$_REQUEST['description']."',  ".$_REQUEST['period'].", 
									".$__user_id.", ".$dadd.", ".$__user_id.", ".$dadd.", ".$_REQUEST['status'].", '$_REQUEST[news_order]')";
							mysql_query($query) or die(mysql_error());			
						}else{
							$matches = $_REQUEST['match_link'].",".$_REQUEST['match_title'].",".$_REQUEST['match_lead'];
							$query = "Insert into rss_feeds (feed_type, agency_id, rss_name, rss_url, rss_description, pattern, matches, aux_url, period, create_uid, create_date, modify_uid, modify_date, status, news_order) 
									Values (".$_REQUEST['type'].", '".$_REQUEST['agencies']."', '".$_REQUEST['rss_name']."', '".$_REQUEST['rss_url']."', '".$_REQUEST['description']."', '".addslashes($_REQUEST['pattern'])."', '".$matches."', '".$_REQUEST['aux_url']."', ".$_REQUEST['period'].", 
									".$__user_id.", ".$dadd.", ".$__user_id.", ".$dadd.", ".$_REQUEST['status'].", '$_REQUEST[news_order]')";
									
							mysql_query($query) or die(mysql_error());			
						}
						$rss_id = mysql_insert_id($connection);					
						echo('		  <tr id="rss_'.$rss_id.'_'.$agency_id.'">');
						echo('			<td>'.$rss_id.'</td>');
						echo('			<td><a href="'.$_REQUEST['rss_url'].'" target="_blank">'.$_REQUEST['rss_name'].'</a></td>');
						echo('			<td>[ <a href="#" onclick="showRSSMod(\''.$rss_id.'\', \''.$agency_id.'\', this);return false;">M&oacute;dos&iacute;t</a> ]</td>');
						echo('			<td>[ <a href="#" onclick="delRSS(\''.$rss_id.'\', \''.$agency_id.'\', \''.$_REQUEST['rss_name'].'\'); return false;">T&ouml;r&ouml;l</a> ]</td>');
						echo('		  </tr>');
					}else{
						echo("-1");
					}
					
				
				break;
			case "add_rss":
				if($_REQUEST['agency_id'])	{
					$agencies = get_all_agencies();					
					$agency_id = $_REQUEST['agency_id'];
					echo('<table width="100%" border="0" cellspacing="0" cellpadding="5">');
					echo('	<tr>');
					echo('	  <td colspan="2" class="page_title">&Uacute;j h&iacute;rfolyam</td>');
					echo('	</tr>');
					echo('  <tr>');
					echo('    <td>');
					echo('		<table border="0" cellspacing="0" cellpadding="5">');
					echo('		  <tr>');
					echo('			<td>H&iacute;rforr&aacute;s:</td>');
					echo('			<td>');
					echo('				<select id="agencies__'.$agency_id.'">');
					for($i=0;$i<count($agencies);$i++){
						if($agency_id==$agencies[$i]['agency_id'])	echo('<option value="'.$agencies[$i]['agency_id'].'" selected>'.$agencies[$i]['agency_name'].'</option>');
							echo('<option value="'.$agencies[$i]['agency_id'].'">'.$agencies[$i]['agency_name'].'</option>');	
					}
					echo('				</select>');
					echo('			</td>');
					echo('		  </tr>');
					echo('		  <tr>');
					echo('			<td>Tipus:</td>');
					echo('			<td>');
					echo('				<select id="type__'.$agency_id.'" onChange="changeRSSType(this, \'\', \''.$agency_id.'\');">');					
					echo('					<option value="1" selected>RSS</option>');
					echo('					<option value="2">HTML</option>');					
					echo('				</select>');
					echo('			</td>');
					echo('		  </tr>');
					echo('		  <tr>');
					echo('			<td>H&iacute;rfolyam:</td>');
					echo('			<td><input type="text" id="rss_name__'.$agency_id.'" value="" /></td>');
					echo('		  </tr>');
					echo('		  <tr>');
					echo('			<td>H&iacute;rfolyam URL:</td>');
					echo('			<td><input type="text" id="rss_url__'.$agency_id.'" value="" /></td>');
					echo('		  </tr>');
					echo('		  <tr>');
					echo('			<td>Le&iacute;r&aacute;s:</td>');
					echo('			<td><textarea id="description__'.$agency_id.'" rows="10"></textarea></td>');
					echo('		  </tr>');
					echo('		  <tr>');
					echo('			<td colspan="2"><input type="button" name="" value="V&eacute;grehajt" class="button" onclick="postRSS(\''.$agency_id.'\'); return false;"/></td>');
					echo('		  </tr>');
					echo('		 </table> ');
					echo('	</td>');
					echo('    <td valign="top">');
					echo('		<table border="0" cellspacing="0" cellpadding="5">');
					echo('			<tr>');
					echo('				<td colspan="2">');					
					echo('					<table width="100%" border="0" cellspacing="0" cellpadding="5" id="rss_type__'.$agency_id.'" style="display:none;visibility:hidden;">');					
					echo('					  <tr>');
					echo('						<td>Regul&aacute;ris kifejez&eacute;s:</td>');
					echo('						<td><input type="text" id="pattern__'.$agency_id.'" value="" /></td>');
					echo('					  </tr>');
					echo('					  <tr>');
					echo('						<td>Kieg&eacute;sz&iacute;t&#337; URL:</td>');
					echo('						<td><input type="text" id="aux_url__'.$agency_id.'" value="" /></td>');
					echo('					  </tr>');
					echo('					  <tr>');
					echo('						<td>H&iacute;r URL tal&aacute;lat:</td>');
					echo('						<td><input type="text" id="match_link__'.$agency_id.'" value="" /></td>');
					echo('					  </tr>');
					echo('					  <tr>');
					echo('						<td>H&iacute;r c&iacute;m tal&aacute;lat:</td>');
					echo('						<td><input type="text" id="match_title__'.$agency_id.'" value="" /></td>');
					echo('					  </tr>');
					echo('					  <tr>');
					echo('						<td>H&iacute;r bevezet&#337; tal&aacute;lat:</td>');
					echo('						<td><input type="text" id="match_lead__'.$agency_id.'" value="" /></td>');
					echo('					  </tr>');
					echo('					</table>');
					echo('				</td>');
					echo('			  </tr>');
					echo('			  <tr>');
					echo('				<td>Ellen&#337;rz&eacute;si peri&oacute;dus:</td>');
					echo('				<td><input type="text" id="period__'.$agency_id.'" value="" dir="rtl" /> (perc)</td>');
					echo('			  </tr>');
					echo('			  <tr>');
					echo('				<td>St&aacute;tusz:</td>');
					echo('				<td>');
					echo('					<select id="rss_status__'.$agency_id.'">');
					echo('						<option value="1" selected>Akt&iacute;v</option>');
					echo('						<option value="0">Inakt&iacute;v</option>');
					echo('					</select>');
					echo('				</td>');
					echo('			  </tr>');
					echo('		</table>');
					echo('	</td>');
					echo('  </tr>');
					echo('</table>');
				}	
				break;
			case "del_rss":
				if($_REQUEST['rss_id']){
					$query = "Delete From rss_feeds Where id=".$_REQUEST['rss_id'];
					mysql_query($query) or die(mysql_error());
					$query = "Delete From rss_categories Where rss_id=".$_REQUEST['rss_id'];
					mysql_query($query) or die(mysql_error());
				}
				break;
			case  "del_agency":
				if($_REQUEST['agency_id']){					
					$query = "Delete From rss_categories Where rss_id in (Select rss_id From rss_feeds Where agency_id=".$_REQUEST['agency_id'].")";
					mysql_query($query) or die(mysql_error());
					$query = "Delete From rss_feeds Where agency_id=".$_REQUEST['agency_id'];					
					mysql_query($query) or die(mysql_error());
					$query = "Delete From agencies Where agency_id=".$_REQUEST['agency_id'];
					mysql_query($query) or die(mysql_error());
				}
				break;
			case "update_rss":
				if($_REQUEST['rss_id']!=""){
					$urls = make_url($_REQUEST['rss_url']);
					$query = "Select count(*) as nr From rss_feeds Where (rss_url='"."http://".$urls[0]."' Or rss_url='"."http://".$urls[1]."') And id!=".$_REQUEST['rss_id']."";
					$result = mysql_query($query) or die(mysql_error());
					$row = mysql_fetch_array($result);
					if($row['nr']!=0){
						echo("-1");
					}else{
						$dadd = time();
						if($_REQUEST['type']=="1"){
							$query = "Update rss_feeds Set 
										feed_type=".$_REQUEST['type'].",
										agency_id='".$_REQUEST['agencies']."',
										rss_name='".$_REQUEST['rss_name']."',
										rss_url='".$_REQUEST['rss_url']."',
										rss_description='".$_REQUEST['description']."',
										period=".$_REQUEST['period'].",
										modify_uid=".$__user_id.",
										modify_date=".$dadd.", 
										status=".$_REQUEST['status'].",
                                        news_order='".$_REQUEST['news_order']."'
										Where id=".$_REQUEST['rss_id']." Limit 1";
							mysql_query($query) or die(mysql_error());			
						}else{
							$matches = $_REQUEST['match_link'].",".$_REQUEST['match_title'].",".$_REQUEST['match_lead'];
							$query = "Update rss_feeds Set 
										feed_type=".$_REQUEST['type'].",
										agency_id='".$_REQUEST['agencies']."',
										rss_name='".$_REQUEST['rss_name']."',
										rss_url='".$_REQUEST['rss_url']."',
										rss_description='".$_REQUEST['description']."',
										pattern='".addslashes($_REQUEST['pattern'])."',
										matches='".$matches."',
										aux_url='".$_REQUEST['aux_url']."',
										period=".$_REQUEST['period'].",
										modify_uid=".$__user_id.",
										modify_date=".$dadd.", 
										status=".$_REQUEST['status'].",
                                        news_order='".$_REQUEST['news_order']."'
										Where id=".$_REQUEST['rss_id']." Limit 1";					
							mysql_query($query) or die(mysql_error());									
						}
					}
				}	
				break;
			case "get_rss_prop":
				if($_REQUEST['rss_id']!=""){
					$agencies = get_all_agencies();
					$rss_feed = get_rss_feed_by_id($_REQUEST['rss_id']);
					$agency_id = $_REQUEST['agency_id'];
					echo('<table width="100%" border="0" cellspacing="0" cellpadding="5">');
					echo('	<tr>');
					echo('	  <td colspan="2" class="page_title">H&iacute;rfolyam m&oacute;dos&iacute;t&aacute;sa</td>');
					echo('	</tr>');
					echo('  <tr>');
					echo('    <td>');
					echo('		<table border="0" cellspacing="0" cellpadding="5">');
					echo('		  <tr>');
					echo('			<td>H&iacute;rforr&aacute;s:</td>');
					echo('			<td>');
					echo('				<select id="agencies_'.$rss_feed['id'].'_'.$agency_id.'">');
					for($i=0;$i<count($agencies);$i++){
						if($rss_feed['agency_id']==$agencies[$i]['agency_id'])	echo('<option value="'.$agencies[$i]['agency_id'].'" selected>'.$agencies[$i]['agency_name'].'</option>');
							else echo('<option value="'.$agencies[$i]['agency_id'].'">'.$agencies[$i]['agency_name'].'</option>');
					}
					echo('				</select>');
					echo('			</td>');
					echo('		  </tr>');
					echo('		  <tr>');
					echo('			<td>Tipus:</td>');
					echo('			<td>');
					echo('				<select id="type_'.$rss_feed['id'].'_'.$agency_id.'" onChange="changeRSSType(this, \''.$rss_feed['id'].'\', \''.$agency_id.'\');">');
					if($rss_feed['feed_type']==1){
						echo('<option value="1" selected>RSS</option>');
						echo('<option value="2">HTML</option>');
					}else if($rss_feed['feed_type']==2){
						echo('<option value="1">RSS</option>');
						echo('<option value="2" selected>HTML</option>');
					}
					echo('				</select>');
					echo('			</td>');
					echo('		  </tr>');
					echo('		  <tr>');
					echo('			<td>H&iacute;rfolyam:</td>');
					echo('			<td><input type="text" id="rss_name_'.$rss_feed['id'].'_'.$agency_id.'" value="'.$rss_feed['rss_name'].'" /></td>');
					echo('		  </tr>');
					echo('		  <tr>');
					echo('			<td>H&iacute;rfolyam URL:</td>');
					echo('			<td><input type="text" id="rss_url_'.$rss_feed['id'].'_'.$agency_id.'" value="'.$rss_feed['rss_url'].'" /></td>');
					echo('		  </tr>');
					echo('		  <tr>');
					echo('			<td>Le&iacute;r&aacute;s:</td>');
					echo('			<td><textarea id="description_'.$rss_feed['id'].'_'.$agency_id.'" rows="10">'.$rss_feed['rss_description'].'</textarea></td>');
					echo('		  </tr>');
					echo('		  <tr>');
					echo('			<td colspan="2"><input type="button" name="" value="V&eacute;grehajt" class="button" onclick="updateRSS(\''.$rss_feed['id'].'\', \''.$agency_id.'\'); return false;"/></td>');
					echo('		  </tr>');
					echo('		 </table> ');
					echo('	</td>');
					echo('    <td valign="top">');
					echo('		<table border="0" cellspacing="0" cellpadding="5">');
					echo('			<tr>');
					echo('				<td colspan="2">');
					if($rss_feed['feed_type']==1){
						echo('<table width="100%" border="0" cellspacing="0" cellpadding="5" id="rss_type_'.$rss_feed['id'].'_'.$agency_id.'" style="display:none;visibility:hidden;">');
					}else if($rss_feed['feed_type']==2){
						echo('<table width="100%" border="0" cellspacing="0" cellpadding="5" id="rss_type_'.$rss_feed['id'].'_'.$agency_id.'">');
					}					
					echo('					  <tr>');
					echo('						<td>Regul&aacute;ris kifejez&eacute;s:</td>');
					echo('						<td><input type="text" id="pattern_'.$rss_feed['id'].'_'.$agency_id.'" value="'.$rss_feed['pattern'].'" /></td>');
					echo('					  </tr>');
					echo('					  <tr>');
					echo('						<td>Kieg&eacute;sz&iacute;t&#337; URL:</td>');
					echo('						<td><input type="text" id="aux_url_'.$rss_feed['id'].'_'.$agency_id.'" value="'.$rss_feed['aux_url'].'" /></td>');
					echo('					  </tr>');
					echo('					  <tr>');
					echo('						<td>H&iacute;r URL tal&aacute;lat:</td>');
					echo('						<td><input type="text" id="match_link_'.$rss_feed['id'].'_'.$agency_id.'" value="'.$rss_feed['match_link'].'" /></td>');
					echo('					  </tr>');
					echo('					  <tr>');
					echo('						<td>H&iacute;r c&iacute;m tal&aacute;lat:</td>');
					echo('						<td><input type="text" id="match_title_'.$rss_feed['id'].'_'.$agency_id.'" value="'.$rss_feed['match_title'].'" /></td>');
					echo('					  </tr>');
					echo('					  <tr>');
					echo('						<td>H&iacute;r bevezet&#337; tal&aacute;lat:</td>');
					echo('						<td><input type="text" id="match_lead_'.$rss_feed['id'].'_'.$agency_id.'" value="'.$rss_feed['match_lead'].'" /></td>');
					echo('					  </tr>');
					echo('					</table>');
					echo('				</td>');
					echo('			  </tr>');
					echo('			  <tr>');
					echo('				<td>Ellen&#337;rz&eacute;si peri&oacute;dus:</td>');
					echo('				<td><input type="text" id="period_'.$rss_feed['id'].'_'.$agency_id.'" value="'.$rss_feed['period'].'" dir="rtl" /> (perc)</td>');
					echo('			  </tr>');
echo('		  <tr>');
echo('			<td>Hírek időrendi sorrendje:</td>');
echo('			<td>');
echo('				<select id="news_order_'.$rss_feed['id'].'_'.$agency_id.'">');
echo('                    <option value="desc">Csökkenő</option>');
echo('                    <option value="asc" ' .($rss_feed["news_order"] == "asc" ? "selected" : "") . ' >Növekvő</option>');
echo('				</select>');
echo('			</td>');
echo('		  </tr>		  ');
					echo('			  <tr>');
					echo('				<td>St&aacute;tusz:</td>');
					echo('				<td>');
					echo('					<select id="rss_status_'.$rss_feed['id'].'_'.$agency_id.'">');
					if($rss_feed['status']==1){
						echo('<option value="1" selected>Akt&iacute;v</option>');
						echo('<option value="0">Inakt&iacute;v</option>');
					}else if($rss_feed['status']==0){
						echo('<option value="1">Akt&iacute;v</option>');
						echo('<option value="0" selected>Inakt&iacute;v</option>');
					}					
					echo('					</select>');
					echo('				</td>');
					echo('			  </tr>');
					echo('		</table>');
					echo('	</td>');
					echo('  </tr>');
					echo('</table>');
				}
				break;
			case "update_agency":
				if($_REQUEST['agency_id']!=""){
					$urls = make_url($_REQUEST['agency_url']);
					$query = "Select count(*) as nr From agencies Where (agency_url='"."http://".$urls[0]."' Or agency_url='"."http://".$urls[1]."') And agency_id!=".$_REQUEST['agency_id']."";
					$result = mysql_query($query) or die(mysql_error());
					$row = mysql_fetch_array($result);
					if($row['nr']!=0){
						echo("-1");
					}else{
						$dadd = time();
						$query = "Update agencies Set 
									agency_name='".$_REQUEST['agency_name']."',
									agency_url='".$_REQUEST['agency_url']."',
									agency_favicon='".$_REQUEST['agency_favicon']."',
									agency_description='".$_REQUEST['description']."',
									agency_type='".$_REQUEST['agency_type']."',
									modify_uid=".$__user_id.",
									modify_date=".$dadd."
									Where agency_id=".$_REQUEST['agency_id']." Limit 1";
						mysql_query($query) or die(mysql_error());								
					}
				}
				break;
			case "get_agency_prop":
				if($_REQUEST['agency_id']!=""){
					$agency = get_agency_by_id($_REQUEST['agency_id']);
					$rss_feeds = get_rss_feeds_by_agency($_REQUEST['agency_id']);					
					echo('<table width="100%" border="0" cellspacing="0" cellpadding="5">');
					echo('  <tr>');
					echo('    <td valign="top" width="33%">');
					echo('		<table border="0" cellspacing="0" cellpadding="5">');
					echo('		  <tr>');
					echo('			<td colspan="2" class="page_title">H&iacute;rforr&aacute;s m&oacute;dos&iacute;t&aacute;sa</td>');
					echo('		  </tr>');
					echo('		  <tr>');
					echo('			<td>H&iacute;rforr&aacute;s:</td>');
					echo('			<td><input type="text" id="agency_name_'.$agency['agency_id'].'" value="'.$agency['agency_name'].'" /></td>');
					echo('		  </tr>');
					echo('		  <tr>');
					echo('			<td>H&iacute;rforr&aacute;s URL:<br />(http:// kötelező)</td>');
					echo('			<td><input type="text" id="agency_url_'.$agency['agency_id'].'" value="'.$agency['agency_url'].'" /></td>');
					echo('		  </tr>');
					echo('		  <tr>');
					echo('			<td>Favicon URL:<br />(http:// kötelező)</td>');
					echo('			<td><input type="text" id="agency_favicon_'.$agency['agency_id'].'" value="'.$agency['agency_favicon'].'" /></td>');
					echo('		  </tr>');
					echo('		  <tr>');
					echo('		  	<td>Le&iacute;r&aacute;s:</td>');
					echo('			<td><textarea id="description_'.$agency['agency_id'].'" rows="10">'.$agency['agency_description'].'</textarea></td>');
					echo('		  </tr>');
					echo('		  <tr>');
					echo('		  	<td>Tipus:</td>');
					echo('			<td>');
					echo('				<select id="agency_type_'.$agency['agency_id'].'">');
					if($agency['agency_type']==1){
						echo('					<option value="1" selected>RSS</option>');
						echo('					<option value="2">HTML</option>');
					}else if($agency['agency_type']==2){
						echo('					<option value="1">RSS</option>');
						echo('					<option value="2" selected>HTML</option>');
					}
					echo('				</select>');
					echo('			</td>');
					echo('		  </tr>');
					echo('		  <tr>');
					echo('		  	<td colspan="2"><input type="button" name="" value="V&eacute;grehajt" class="button" onclick="updateAgency(\''.$agency['agency_id'].'\');" /></td>');
					echo('		  </tr>');
					echo('		</table>');
					echo('	</td>');
					echo('  <td valign="top">');
					echo('		<table width="100%" border="0" cellspacing="0" cellpadding="5" align="left">');
					echo('		  <tr id="related_rss_'.$agency['agency_id'].'">');
					echo('			<td colspan="4"><span class="page_title">Kapcsol&oacute;do h&iacute;rfolyamok</span> [ <a href="#" onclick="addNewRss(\''.$agency['agency_id'].'\', this);return false;" style="font-size:10px;">&Uacute;j h&iacute;rfolyam</a>]</td>');
					echo('		  </tr>');
					for($i=0;$i<count($rss_feeds);$i++){
						echo('		  <tr id="rss_'.$rss_feeds[$i]['id'].'_'.$agency['agency_id'].'">');
						echo('			<td>'.$rss_feeds[$i]['id'].'</td>');
						echo('			<td><a href="'.$rss_feeds[$i]['rss_url'].'" target="_blank">'.$rss_feeds[$i]['rss_name'].'</a></td>');
						echo('			<td>[ <a href="#" onclick="showRSSMod(\''.$rss_feeds[$i]['id'].'\', \''.$agency['agency_id'].'\', this);return false;">M&oacute;dos&iacute;t</a> ]</td>');
						echo('			<td>[ <a href="#" onclick="delRSS(\''.$rss_feeds[$i]['id'].'\', \''.$agency['agency_id'].'\', \''.$rss_feeds[$i]['rss_name'].'\'); return false;">T&ouml;r&ouml;l</a> ]</td>');
						echo('		  </tr>');
					}
					echo('		</table>');					
					echo('	  </td>');
					echo('  </tr>');
					echo('</table>');

				}
				break;
			case "update_rss":
				if($_REQUEST['rss_id']){
					$dadd = time();
					if($_REQUEST['type']=="1"){
						$query = "Update rss_feeds Set 
									feed_type=".$_REQUEST['type'].",
									agency_id='".$_REQUEST['agency']."',
									rss_name='".$_REQUEST['rss_name']."',
									rss_url='".$_REQUEST['rss_url']."',
									period=".$_REQUEST['period'].",
									modify_uid=".$__user_id.",
									modify_date=".$dadd.",
									status=".$_REQUEST['status'].",
                                    news_order='".$_REQUEST['news_order']."'
									Where id=".$_REQUEST['rss_id']." Limit 1";
						mysql_query($query) or die(mysql_error());			
					}else{
						$matches = $_REQUEST['match_link'].",".$_REQUEST['match_title'].",".$_REQUEST['match_lead'];
						$query = "Update rss_feeds Set 
									feed_type=".$_REQUEST['type'].",
									agency_id='".$_REQUEST['agencies']."',
									rss_name='".$_REQUEST['rss_name']."',
									rss_url='".$_REQUEST['rss_url']."',
									pattern='".addslashes($_REQUEST['pattern'])."',
									matches='".$matches."',
									aux_url='".$_REQUEST['aux_url']."',
									period=".$_REQUEST['period'].",
									modify_uid=".$__user_id.", 
									modify_date=".$dadd.", 
									status=".$_REQUEST['status'].",
                                    news_order='".$_REQUEST['news_order']."'
									Where id=".$_REQUEST['rss_id']." Limit 1";					
						mysql_query($query) or die(mysql_error());			
					}
				}
				break;
			case "get_rss_info":
				if($_REQUEST['rss_id']!=""){
					$query = "Select agency_id, agency_name From agencies Order by agency_name";
					$result = mysql_query($query) or die(mysql_error());
					$i=0;
					while($row = mysql_fetch_assoc($result)){
						$agencies[$i] = array(
							'agency_id'=>$row['agency_id'],
							'agency_name'=>$row['agency_name']
						);
						$i++;						
					}					
					$query = "Select id, feed_type, agency_id, rss_name, rss_url, pattern, matches, aux_url, period, status, news_order From rss_feeds Where id=".$_REQUEST['rss_id'];
					$result = mysql_query($query) or die(mysql_error());
					$row = mysql_fetch_assoc($result);
					echo("<ul style=\"padding:0;margin:0\" id=\"edit_rss_".$_REQUEST['cat_id']."_".$_REQUEST['rss_id']."\">");
					echo("<li>");
					echo("H&iacute;rforr&aacute;s:<br /><select name=\"agencies\">");
						for($i=0;$i<count($agencies);$i++){
							if($agencies[$i]['agency_id']==$row['agency_id'])	echo("<option value=\"".$agencies[$i]['agency_id']."\" selected>".$agencies[$i]['agency_name']."</option>");
								else	echo("<option value=\"".$agencies[$i]['agency_id']."\">".$agencies[$i]['agency_name']."</option>");
						}
					echo("</select>");
					echo("</li>");
					echo("<li>");
					echo("Tipus:<br /><select name=\"type\" onchange=\"changeType(this, '".$_REQUEST['cat_id']."', '".$_REQUEST['rss_id']."');\">");
						if($row['feed_type']==1)	echo("<option value=\"1\" selected>RSS</option><option value=\"2\">HTML</option>");
							else if($row['feed_type']==2) echo("<option value=\"1\">RSS</option><option value=\"2\" selected>HTML</option>");	
					echo("</select>");
					echo("</li>");
					echo("<li>H&iacute;rfolyam:<br /><input type=\"text\" name=\"rss_name\" value=\"".$row['rss_name']."\" /></li>");
					echo("<li>H&iacute;rfolyam URL:<br /><input type=\"text\" name=\"rss_url\" value=\"".$row['rss_url']."\" /></li>");
					if($row['feed_type']==2){
						echo("<ul style=\"padding:0;margin:0\" id=\"rss_type_".$_REQUEST['cat_id']."_".$_REQUEST['rss_id']."\">");
					}else	echo("<ul style=\"padding:0;margin:0;display:none; visibility:hidden;\" id=\"rss_type_".$_REQUEST['cat_id']."_".$_REQUEST['rss_id']."\">"); 
						echo("<li>Regul&aacute;ris kifejez&eacute;s:<br /><input type=\"text\" name=\"pattern\" value=\"".htmlspecialchars($row['pattern'])."\" /></li>");
						echo("<li>Kieg&eacute;sz&iacute;t&#337; URL:<br /><input type=\"text\" name=\"aux_url\" value=\"".$row['aux_url']."\" /></li>");
						$matches = explode(",", $row['matches']);
						echo("<li>H&iacute;r URL tal&aacute;lat:<br /><input type=\"text\" name=\"match_link\" value=\"".$matches[0]."\" /></li>");
						echo("<li>H&iacute;r c&iacute;m tal&aacute;lat:<br /><input type=\"text\" name=\"match_title\" value=\"".$matches[1]."\" /></li>");
						echo("<li>H&iacute;r bevezet&#337; tal&aacute;lat:<br /><input type=\"text\" name=\"match_lead\" value=\"".$matches[1]."\" /></li>");
						echo("</ul>");
					
					echo("<li>Ellen&#337;rz&eacute;si peri&oacute;dus:<br /><input type=\"text\" name=\"period\" value=\"".$row['period']."\" dir=\"rtl\" /></li>");
echo('		  <li>');
echo('			Hírek időrendi sorrendje:<br/><select name="news_order"');
echo('                    <option value="desc">Csökkenő</option>');
echo('                    <option value="asc" ' .($row["news_order"] == "asc" ? "selected" : "") . ' >Növekvő</option>');
echo('				</select>');
echo('		  </li>		  ');
					echo("<li>");
					echo("St&aacute;tusz:<br /><select name=\"status\">");
						if($row['status']==1)	echo("<option value=\"1\" selected>Akt&iacute;v</option><option value=\"0\">Inakt&iacute;v</option>");
							else if($row['status']==0) echo("<option value=\"1\">Akt&iacute;v</option><option value=\"0\" selected>Inakt&iacute;v</option>");
					echo("</select>");
					echo("</li>");
					echo("<li><input type=\"button\" value=\"V&eacute;grehajt\" class=\"button\" onclick=\"updateRSS('".$_REQUEST['cat_id']."', '".$row['id']."');\" /></li>");
					echo("</ul>");
					
				}
				break;
			case "del_rss_from_category":
				if($_REQUEST['rss_id']!="" && $_REQUEST['cat_id']!=""){
					$query = "Delete From rss_categories Where rss_id=".$_REQUEST['rss_id']." And cat_id=".$_REQUEST['cat_id'];
					mysql_query($query) or die(mysql_error());
				}
				break;
			case "add_new_rss":
				$query = "Select count(*) as nr From rss_categories Where rss_id=".$_REQUEST['rss_id']." And cat_id=".$_REQUEST['cat_id'];
				$results = mysql_query($query) or die(mysql_error());
				$row = mysql_fetch_assoc($results);
				if($row['nr']!=0){
					echo("-1"); // rss already added
				}else{
					$query = "Insert Into rss_categories (rss_id, cat_id, date_add) Values (".$_REQUEST['rss_id'].", ".$_REQUEST['cat_id'].", now())";
					mysql_query($query) or die(mysql_error());
					$query = "Select id, rss_name, rss_url From rss_feeds Where id=".$_REQUEST['rss_id'];
					echo(mysqlFetchAjax($query));
				}
				break;
			case "search_rss":
				$query = "Select id, rss_name From rss_feeds Where rss_name like '%".$_REQUEST['keyword']."%' Order by rss_name";
				echo(mysqlFetchAjax($query));
				break;
			case "update_category":							
				if($_REQUEST['id']!=""){// && $_REQUEST['title']!="" && $_REQUEST['url']!="" && $_REQUEST['news_nr']!="" && $_REQUEST['page_id']!=""){										
					$query = "Update categories Set 
						cat_name='".$_REQUEST['title']."', 
						cat_url='".$_REQUEST['url']."', 
						cat_css_id=".$_REQUEST['cat_css_id'].",
						cat_css='".$_REQUEST['cat_css_name']."',
						cat_type=".$_REQUEST['cat_type'].",
						cat_html='".$_REQUEST['cat_html']."',
						cat_sql='".$_REQUEST['cat_sql']."',
						modify_uid=".$__user_id.",
						modify_date=".time()."
						Where cat_id=".$_REQUEST['id']." Limit 1";
					mysql_query($query) or die(mysql_error());
					$query = "Update page_categories Set news_nr=".$_REQUEST['news_nr']." Where page_id=".$_REQUEST['page_id']." And cat_id=".$_REQUEST['id']." Limit 1";
					mysql_query($query) or die(mysql_error());
				}
				break;
			case "get_rss":
				if($_REQUEST['id']!=""){
					$query = "Select  rss_feeds.id as rss_id, rss_name, rss_url, period
							   From rss_categories
							   Left Join rss_feeds On rss_feeds.id=rss_categories.rss_id
							   Where cat_id=".$_REQUEST['id'];
					$result = mysql_query($query) or die(mysql_error());
					echo("<ul class=\"feed\" id=\"box_rss_".$_REQUEST['id']."\">");
					while($row = mysql_fetch_array($result)){
						echo("<li id=\"rss_".$row['rss_id']."\"><div style=\"float:right;\"><a href=\"#\" onclick=\"openEditRSS(this, '".$row['rss_id']."', '".$_REQUEST['id']."');\">Szerkeszt</a>&nbsp;<img src=\"../i/closeMod.gif\" style=\"cursor:pointer;\" onclick=\"delRSSFromCategory('".$_REQUEST['id']."', '".$row['rss_id']."');\" /></div><a href=\"".$row['rss_url']."\" id=\"rss_link_".$row['rss_id']."_".$_REQUEST['id']."\">".$row['rss_name']."</a></li>");
					}
					echo("</ul>");					
				}
				break;
			case "add_founded_htmlbox":
				if($_REQUEST['page_id']!="" && $_REQUEST['htmlbox_id']!=""){
					$query = "Select count(*) as nr From page_categories Where type = 5 and cat_id=".$_REQUEST['htmlbox_id']." And page_id=".$_REQUEST['page_id'];
					$result = mysql_query($query) or die(mysql_error());
					$row = mysql_fetch_array($result);
					if($row['nr']==0){
						$dadd = time();
						$query = "Insert into page_categories (page_id, cat_id, cat_column, cat_position, create_uid, create_date, modify_uid, modify_date, type) 
							Values (".$_REQUEST['page_id'].", ".$_REQUEST['htmlbox_id'].", 0, 1, ".$__user_id.", ".$dadd.", ".$__user_id.", ".$dadd.", 5)";
						mysql_query($query) or die(mysql_error());
						$query = "Select h.title cat_name, cat_column, cat_position, h.id as id, h.url as cat_url, news_nr, 0 cat_css_id, '' cat_css, 5, h.html cat_html, '' cat_sql
							From page_categories
							Left Join htmlbox h On page_categories.cat_id=h.id
							Where page_categories.type = 5 and h.id=".$_REQUEST['htmlbox_id']." And page_id=".$_REQUEST['page_id'];					
						echo(mysqlFetchAjax($query));					
					}else echo("-1"); //ez a kategoria mar be van sorolva ebbe az oldalba
				}
				break;
			case "add_founded_category":
				if($_REQUEST['page_id']!="" && $_REQUEST['cat_id']!=""){
					$query = "Select count(*) as nr From page_categories Where type = 1 and cat_id=".$_REQUEST['cat_id']." And page_id=".$_REQUEST['page_id'];
					$result = mysql_query($query) or die(mysql_error());
					$row = mysql_fetch_array($result);
					if($row['nr']==0){
						$dadd = time();
						$query = "Insert into page_categories (page_id, cat_id, cat_column, cat_position, create_uid, create_date, modify_uid, modify_date) 
							Values (".$_REQUEST['page_id'].", ".$_REQUEST['cat_id'].", 0, 1, ".$__user_id.", ".$dadd.", ".$__user_id.", ".$dadd.")";
						mysql_query($query) or die(mysql_error());
						$query = "Select cat_name, cat_column, cat_position, categories.cat_id as id, cat_url, news_nr, cat_css_id, cat_css, cat_type, cat_html, cat_sql
							From page_categories
							Left Join categories On page_categories.cat_id=categories.cat_id
							Where page_categories.type =1 and categories.cat_id=".$_REQUEST['cat_id']." And page_id=".$_REQUEST['page_id'];					
						echo(mysqlFetchAjax($query));					
					}else echo("-1"); //ez a kategoria mar be van sorolva ebbe az oldalba
				}
				break;
			case "search_htmlbox":
					$query = "Select id, title  From htmlbox Where title like '%".$_REQUEST['keyword']."%' Order by title";
					echo(mysqlFetchAjax($query));
				break;
			case "search_category":
					$query = "Select cat_id, cat_name  From categories Where cat_name like '%".$_REQUEST['keyword']."%' Order by cat_name";
					echo(mysqlFetchAjax($query));
				break;
			case "remove_rss_box":
				if($_REQUEST['page_id']!="" && $_REQUEST['cat_id']!=""){
					$query = "Delete From page_categories Where page_id=".$_REQUEST['page_id']." And cat_id=".$_REQUEST['cat_id'];
					mysql_query($query) or die(mysql_error());
				}
				break;
			case "add_new_category":
				if($_REQUEST['page_id']!="" && $_REQUEST['cat_name']!=""){					
					$query = "Select cat_id, cat_url, cat_type, cat_css_id, cat_css, cat_html, cat_sql From categories Where cat_name='".$_REQUEST['cat_name']."'";					
					$result = mysql_query($query) or die(mysql_error());					
					$row = mysql_fetch_assoc($result);
					$dadd = time();					
					if($row['cat_id']!=""){						
						$query = "Select count(*) as nr From page_categories Where cat_id=".$row['cat_id']." And page_id=".$_REQUEST['page_id'];
						$result = mysql_query($query) or die(mysql_error());
						$row2 = mysql_fetch_array($result);
						if($row2['nr']!=0){
							echo("-1|"); 
						}else{							
							$query = "Insert into page_categories (page_id, cat_id, cat_column, cat_position, create_uid, create_date, modify_uid, modify_date) 
										Values (".$_REQUEST['page_id'].", ".$row['cat_id'].", 0, 1, ".$__user_id.", ".$dadd.", ".$__user_id.", ".$dadd.")";
							mysql_query($query) or die(mysql_error());
							echo("-2|".encodeText($_REQUEST['cat_name'])."|".$row['cat_id']."|".encodeText($row['cat_url'])."|".$row['cat_type']."|".$row['cat_css_id']."|".encodeText($row['cat_css'])."|".encodeText($row['cat_html'])."|".encodeText($row['cat_sql']));
						}
					}else{						
						$urls = make_url($_REQUEST['cat_url']);
						switch($_REQUEST['cat_type']){
							case 1://rss
								$query = "Insert into categories (cat_name, cat_url, cat_css_id, cat_css, cat_type, create_uid, create_date, modify_uid, modify_date) 
									Values ('".$_REQUEST['cat_name']."', '".$urls[0]."', ".$_REQUEST['cat_css_id'].", '".$_REQUEST['cat_css']."', 1, ".$__user_id.", ".$dadd.", ".$__user_id.", ".$dadd.")";
								break;
							case 2://html
								$query = "Insert into categories (cat_name, cat_url, cat_css_id, cat_css, cat_type, cat_html, create_uid, create_date, modify_uid, modify_date) 
									Values ('".$_REQUEST['cat_name']."', '".$urls[0]."', ".$_REQUEST['cat_css_id'].", '".$_REQUEST['cat_css']."', 2, '".$_REQUEST['cat_content']."', ".$__user_id.", ".$dadd.", ".$__user_id.", ".$dadd.")";
								break;
							case 3://SQL
								$query = "Insert into categories (cat_name, cat_url, cat_css_id, cat_css, cat_type, cat_sql, create_uid, create_date, modify_uid, modify_date) 
									Values ('".$_REQUEST['cat_name']."', '".$urls[0]."', ".$_REQUEST['cat_css_id'].", '".$_REQUEST['cat_css']."', 3, '".$_REQUEST['cat_content']."', ".$__user_id.", ".$dadd.", ".$__user_id.", ".$dadd.")";
								break;		
						}												
						mysql_query($query) or die(mysql_error());						
						$cat_id = mysql_insert_id($connection);
						$query = "Insert into page_categories (page_id, cat_id, cat_column, cat_position, create_uid, create_date, modify_uid, modify_date) 
										Values (".$_REQUEST['page_id'].", ".$cat_id.", 0, 1, ".$__user_id.", ".$dadd.", ".$__user_id.", ".$dadd.")";
						mysql_query($query) or die(mysql_error());
						echo("1|".encodeText($_REQUEST['cat_name'])."|".$cat_id."|".encodeText($urls[0])."|".$_REQUEST['cat_type']."|".$_REQUEST['cat_css_id']."|".encodeText($_REQUEST['cat_css'])."|".encodeText($_REQUEST['cat_content']));
					}
				}
				break;
			case "get_page_categories":
				if($_REQUEST['page_id']!=""){					
					$query = "Select cat_name, cat_column, cat_position, categories.cat_id as id, cat_url, news_nr, cat_css_id, cat_css, cat_type, cat_html, cat_sql
							From page_categories
							inner Join categories On page_categories.cat_id=categories.cat_id
							Where page_categories.type = 1 and page_id=".$_REQUEST['page_id']."
                            
                            union all
                            
                            Select h.title cat_name, cat_column, cat_position, h.id,  h.url, pc.news_nr, 0, '', 5, h.html, ''
                            from page_categories pc 
                            inner join htmlbox h on pc.cat_id = h.id
                            where pc.type = 5 and pc.page_id = '$_REQUEST[page_id]'  
					
							Order by cat_column, cat_position";					
					echo(mysqlFetchAjax($query));
				}
				break;
			case "resort":				
				$_first = explode(";", $_REQUEST["first"]);		
				$_second = explode(";", $_REQUEST["second"]);
				$_third = explode(";", $_REQUEST["third"]);
				$__page_id = $_REQUEST['page_id'];
				for($i=0;$i<count($_first);$i++){
					if($_first[$i]!=""){					
						$query = "Update page_categories Set cat_column=0, cat_position=".($i+1)." Where page_id=".$__page_id." And cat_id=".substr($_first[$i], 4).";";						
						mysql_query($query) or die(mysql_error());						
					}
				}
				for($i=0;$i<count($_second);$i++){
					if($_second[$i]!=""){
						$query = "Update page_categories Set cat_column=1, cat_position=".($i+1)." Where page_id=".$__page_id." And cat_id=".substr($_second[$i], 4).";";
						mysql_query($query) or die(mysql_error());
					}
				}
				for($i=0;$i<count($_third);$i++){
					if($_third[$i]!=""){
						$query = "Update page_categories Set cat_column=2, cat_position=".($i+1)." Where page_id=".$__page_id." And cat_id=".substr($_third[$i], 4).";";						
						mysql_query($query) or die(mysql_error());
					}
				}
				break;
		}
	}
?>
