<?php
	session_start();	
	include_once('../../inc/db_prop.inc.php');
	include_once('../../inc/feed_functions.php');
	include_once('../../libs/Smarty.class.php');
	include_once('../../inc/smarty.config.php');
	
	global	$db, $connection;
	$connection = mysql_connect($host, $user, $psw) or die(mysql_error());
	$db = mysql_select_db($data_base, $connection) or die(mysql_error());
	mysql_query("SET NAMES 'utf8'") or die(mysql_error());
	
	$smarty = new Smarty;
	
	$smarty->template_dir = $admin_template_dir;
	$smarty->compile_dir = $admin_compile_dir;
	
	if($_SESSION['hirek_admin_logged']==""){				
		exit;
	}else{
		list($__user_id, $__user_name) = explode(":", $_SESSION['hirek_admin_logged']);
		$smarty->assign("__user_id", $__user_id);
		$smarty->assign("__user_name", $__user_name);
	}
	

	switch($_POST['action']){
		case "cat_search":
			$q = trim($_POST['q']);
			
			$q = explode(' ', trim($_POST['q']));
			$where = '';
			for($i=0;$i<count($q);$i++){
				if($i==0) $where = " cat_name like '%".$q[$i]."%' ";
					else $where .= " And cat_name like '%".$q[$i]."%' ";
			}
			
			$query = "Select cat_id, cat_name From fixed_categories Where ".$where;
			$result = mysql_query($query) or die(mysql_error());
			$i=0;
			while($row = mysql_fetch_assoc($result)){
				$categories[$i] = array(
					'cat_id'=>$row['cat_id'],
					'cat_name'=>$row['cat_name']
				);
				$i++;
			}
			header('Content-type:text/xml; charset=UTF-8');
			$smarty->assign('categories', $categories);
			$smarty->display('resort_feeds_categories.html');
		case "add_link":
			$link_id = $_POST['link_id'];
			$cat_id = $_POST['cat_id'];
			if($link_id!='' && $cat_id!=''){
				$query = "Select count(*) as nr From fixed_cat_links Where cat_id=".$cat_id." And link_id=".$link_id;
				$result =  mysql_query($query) or die(mysql_error());
				$row = mysql_fetch_assoc($result);
				if($row['nr']!=0){
					echo "false";
				}else{
					$query = "Insert into fixed_cat_links Set cat_id=".$cat_id.", link_id=".$link_id;
					mysql_query($query) or die(mysql_error());
					
					$query = "Select * From external_links Where link_id=".$link_id;
					$result = mysql_query($query) or die(mysql_error());
					$row = mysql_fetch_assoc($result);
					
					$smarty->assign('cat_id', $cat_id);
					$smarty->assign('link_id', $row['link_id']);
					$smarty->assign('link_name', $row['link_name']);
					$smarty->assign('link_url', $row['link_url']);
					$smarty->assign('link_title', $row['link_title']);
					
					$smarty->display('external_link.html');
				}
			}
			break;
		case "search_links":
			$q = explode(' ', trim($_POST['q']));
			$where = '';
			for($i=0;$i<count($q);$i++){
				if($i==0) $where = " link_name like '%".$q[$i]."%' ";
					else $where .= " And link_name like '%".$q[$i]."%' ";
			}
			
			$query = "Select link_id, link_name From external_links Where ".$where." Order by link_name";
			$result = mysql_query($query) or die(mysql_error());
			$i=0;
            $links = array();
			while($row = mysql_fetch_assoc($result)){
				$links[$i] = array(
					'link_id'=>$row['link_id'],
					'link_name'=>$row['link_name'],
				);
				$i++;
			}
			header('Content-type:text/xml; charset=UTF-8');
			$smarty->assign('links', $links);
			$smarty->display('external_links_options.html');
			break;
		case "update_link":
			$link_id = $_POST['link_id'];
			$link_name = trim($_POST['link_name']);
			$link_url = parseURL(trim($_POST['link_url']));
			$link_title = trim($_POST['link_title']);
			if($link_url!='' && $link_title!='' && $link_id!=''){
				$query = "Update external_links Set link_name='".$link_name."', link_url='".$link_url."', link_title='".$link_title."' Where link_id=".$link_id." Limit 1";
				mysql_query($query) or die(mysql_error());
			}
			break;
		case "get_link":
			$link_id = $_POST['link_id'];
			if($link_id!=''){
				$query = "Select * From external_links Where link_id=".$link_id;
				$result = mysql_query($query) or die(mysql_error());
				$row = mysql_fetch_assoc($result);
				
				$smarty->assign('link', $row);
				$smarty->display('add_edit_external_link.html');
			}
			break;
		case "remove_link":
			$link_id = $_POST['link_id'];
			$cat_id = $_POST['cat_id'];
			if($link_id!='' && $cat_id!=''){
				$query = "Delete From fixed_cat_links Where cat_id=".$cat_id." And link_id=".$link_id." Limit 1";
				mysql_query($query) or die(mysql_error());
			}
			break;
		case "create_link":
			$link_name = trim($_POST['link_name']);
			$link_url = parseURL(trim($_POST['link_url']));
			$link_title = trim($_POST['link_title']);
			$cat_id = $_POST['cat_id'];
			if($link_name!='' && $link_url!=''){
				$query = "Select * From external_links Where link_url='".$link_url."'";
				$result = mysql_query($query) or die(mysql_error());
				$row = mysql_fetch_array($result);
				$link_props = $row;
				$link_id = $link_props['link_id'];
				if($link_id!=''){
					$query = "Select count(*) as nr From fixed_cat_links Where cat_id=".$cat_id." And link_id=".$link_id;
					$result = mysql_query($query) or die(mysql_error());
					$row = mysql_fetch_assoc($result);
					if($row['nr']!=0)	echo 1;//"Ezt a linket mar felvitte valaki es a kategoria is tartlmazza!";
						else{
							echo 2;
							$query = "Insert Into fixed_cat_links Set cat_id=".$cat_id.", link_id=".$link_id;
							mysql_query($query) or die(mysql_error());
							
							$smarty->assign('cat_id', $cat_id);
							$smarty->assign('link_name', $link_props['link_name']);
							$smarty->assign('link_url', $link_props['link_url']);
							$smarty->assign('link_title', $link_props['link_title']);
							$smarty->assign('link_id', $link_id);
							$smarty->display('external_link.html');	
						}	
				}else{
					$query = "Insert Into external_links Set link_name='".$link_name."', link_url='".$link_url."', link_title='".$link_title."';";
					mysql_query($query) or die(mysql_error());
					$link_id = mysql_insert_id();
					
					$query = "Insert Into fixed_cat_links Set cat_id=".$cat_id.", link_id=".$link_id;
					mysql_query($query) or die(mysql_error());
					$smarty->assign('cat_id', $cat_id);
					$smarty->assign('link_name', $link_name);
					$smarty->assign('link_url', $link_url);
					$smarty->assign('link_title', $link_title);
					$smarty->assign('link_id', $link_id);
					$smarty->display('external_link.html');	
				}
				
			}
			break;
		case "update_cat_html":
			$html = trim(mysql_escape_string($_POST['html']));
			$cat_id = $_POST['cat_id'];
			if($html!='' && $cat_id!=''){
				$query = "Update fixed_categories Set cat_html='".$html."' Where cat_id=".$cat_id." Limit 1";
				mysql_query($query) or die(mysql_error());
			}
			break;
		case "update_cat_rss":
			$rss_url = $_POST['rss_url'];
			$cat_id = $_POST['cat_id'];
			if($rss_url!='' && $cat_id!=''){
				$query = "Update fixed_categories Set cat_rss='".$rss_url."' Where cat_id=".$cat_id." Limit 1";
				mysql_query($query) or die(mysql_error());
			}
			break;
		case "remove_cat":
			$page_id = $_POST['page_id'];
			$cat_id = $_POST['cat_id'];
			if($page_id!='' && $cat_id!=''){
				$query = "Select fixed_categories From pages Where page_id=".$page_id." Limit 1";
				$result = mysql_query($query) or die(mysql_error());
				$row = mysql_fetch_assoc($result);
				
				$categories = unserialize($row['fixed_categories']);
				foreach($categories as $key=>$box){
					foreach($box as $k=>$box_id){
						if($box_id==$cat_id){
							unset($categories[$key][$k]);
							break;
						}	
					}
				}
				
				$query = "Update pages Set fixed_categories='".serialize($categories)."' Where page_id=".$page_id." Limit 1";
				mysql_query($query) or die(mysql_error());
			}
			
			break;
		case "get_category":
			$cat_id = $_POST['cat_id'];						
			if($cat_id!=''){
				$query = "Select * From fixed_categories Where cat_id=".$cat_id." Limit 1";
				$result = mysql_query($query) or die(mysql_error());
				$row = mysql_fetch_assoc($result);
				
				$smarty->assign('cat_id', $cat_id);
				$smarty->assign('cat_name', $row['cat_name']);
				$smarty->assign('cat_type', $row['cat_type']);
				$smarty->assign('cat_rss', $row['cat_rss']);
				$smarty->assign('cat_html', $row['cat_html']);
				
				$query = "Select external_links.link_id as link_id, link_name, link_url, link_title 
							   From fixed_cat_links
							   Left Join external_links On external_links.link_id=fixed_cat_links.link_id
							   Where cat_id=".$cat_id;
				$result = mysql_query($query) or die(mysql_error());
				$i=0;
                $links = array();
				while($row = mysql_fetch_assoc($result)){
					$links[$i] = array(
						'link_id'=>$row['link_id'],
						'link_name'=>$row['link_name'],
						'link_url'=>$row['link_url'],
						'link_title'=>$row['link_title']
					);
					$i++;
				}
				$smarty->assign('links', $links);
				
				$smarty->display('fixed_categories_box.html');
			}
			break;
		case "resort_page":
			$page_id  = $_POST['page_id'];
			if($page_id!=-1){
				$col_one_cat = $_POST['column_one'];
				$col_two_cat = explode(',', $_POST['column_two'][0]);			
				$categories = array(
					'0'=>array(0 => $col_one_cat),
					'1'=>$col_two_cat
				);
				if($col_one_cat=='') unset($categories[0]);
				if($_POST['column_two'][0]=='') unset($categories[1]);
			}else{
				$col_two_cat = explode(',', $_POST['column_one'][0]);			
				$categories = array(					
					'1'=>$col_two_cat
				);
				
			}
			if($page_id){
				$query = "Update pages Set fixed_categories='".serialize($categories)."' Where page_id=".$page_id." Limit 1";
				mysql_query($query) or die(mysql_error());
			}			
			break;
		case "add_new_box":
			$cat_id = $_POST['cat_id'];
			$cat_name = $_POST['cat_name'];
			$column_id = $_POST['column_id'];
			$box_row = $_POST['row'];
			$page_id = $_POST['page_id'];
			
			if($cat_id!='' && $page_id!=''){
				$query = "Select cat_type, cat_rss, cat_html From fixed_categories Where cat_id=".$cat_id." Limit 1";
				$result = mysql_query($query) or die(mysql_error());
				$row = mysql_fetch_assoc($result);
				
				$smarty->assign('cat_type', $row['cat_type']);
				$smarty->assign('cat_rss', $row['cat_rss']);
				$smarty->assign('cat_html', $row['cat_html']);
				$smarty->assign('cat_id', $cat_id);
				$smarty->assign('cat_name', $cat_name);
				
				$query = "Select fixed_categories From  pages Where page_id=".$page_id." Limit 1";
				$result = mysql_query($query) or die(mysql_error());
				$row = mysql_fetch_assoc($result);
				$categories = unserialize($row['fixed_categories']);
				
				$categories[$column_id][$box_row] = $cat_id;
				
				$query = "Update pages Set fixed_categories='".serialize($categories)."' Where page_id=".$page_id." Limit 1";
				mysql_query($query) or die(mysql_error());
				
				$query = "Select external_links.link_id as link_id, link_name, link_url, link_title 
							   From fixed_cat_links
							   Left Join external_links On external_links.link_id=fixed_cat_links.link_id
							   Where cat_id=".$cat_id;
				$result = mysql_query($query) or die(mysql_error());
				$i=0;
                $links = array();
				while($row = mysql_fetch_assoc($result)){
					$links[$i] = array(
						'link_id'=>$row['link_id'],
						'link_name'=>$row['link_name'],
						'link_url'=>$row['link_url'],
						'link_title'=>$row['link_title']
					);
					$i++;
				}
				$smarty->assign('links', $links);
				
				$smarty->display('fixed_categories_box.html');
			}
			break;
		case "update_cat":
			$cat_id =	trim($_POST['cat_id']);
			$cat_name = trim($_POST['cat_name']);
			$type = $_POST['type'];
			if($cat_id!='' && $cat_name!=''){
				$query = "Update fixed_categories Set cat_name='".$cat_name."', cat_type=".$type." Where cat_id=".$cat_id." Limit 1";
				mysql_query($query) or die(mysql_error());
			}
			break;
		case "add_new_cat":
			$cat_name = $_POST['cat_name'];
			if(trim($cat_name)!=''){
				$query = "Select count(*) as nr From fixed_categories Where cat_name='".$cat_name."';";
				$result = mysql_query($query) or die(mysql_error());
				$row = mysql_fetch_array($result);
				if($row['nr']!=0){
					echo "false";					
				}else{
					$query = "Insert into fixed_categories Set cat_name='".$cat_name."'";
					mysql_query($query) or die(mysql_error());
					echo mysql_insert_id();
				}
			}
			break;
		case "show_fixed_categories":
			header('Content-type:text/xml; charset=UTF-8');
			$query = "Select cat_id, cat_name From fixed_categories Order by cat_name;";
			$result = mysql_query($query) or die(mysql_error());
			$i=0;
			while($row = mysql_fetch_assoc($result)){
				$categories[$i] = array(
					'cat_id'=>$row['cat_id'],
					'cat_name'=>$row['cat_name']
				);
				$i++;
			}
			$smarty->assign('categories', $categories);
			$smarty->display('fixed_cat_list.html');
			break;
	}
?>
