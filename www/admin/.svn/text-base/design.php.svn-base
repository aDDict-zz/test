<?php
	function get_cat_colors(){	
		$query = "Select id, name, css From cat_css Order by name";
		$result = mysql_query($query) or die(mysql_error());
		$i=0;
		while($row = mysql_fetch_assoc($result)){
			$cat_colors[$i] = array(
				'id'=>$row['id'],
				'name'=>$row['name'],
				'css'=>$row['css']
			);
			$i++;
		}
		return $cat_colors;
	}
	
	function get_cat_color_by_id($id){
		$query = "Select id, name, css, description From cat_css Where id=".$id;
		$result = mysql_query($query) or die(mysql_error());
		return mysql_fetch_assoc($result);
	}
	switch($_POST['action']){
		case "add_cat_color":
			$query = "Select id From cat_css Where css='".$_POST['css']."'";
			$result = mysql_query($query) or die(mysql_error());
			$row = mysql_fetch_assoc($result);
			if($row['id']==""){
				$dadd = time();
				$query = "Insert into cat_css (name, css, description, create_date, create_uid, modify_uid, modify_date) Values 
					('".$_POST['name']."', '".$_POST['css']."', '".$_POST['description']."', ".$dadd.", ".$__user_id.", ".$dadd.", ".$__user_id.")";
				mysql_query($query) or die(mysql_error());
				$id = mysql_insert_id($connection);
				$_SESSION['message'] = "Sikeres felvitel!";
				header("Location: ".$_SERVER['HTTP_REFERER']."&cat_css_id=".$id);
				exit();			
			}else{
				$_SESSION['message'] = "Ilyen kateg&oacute;ria sz&iacute;n m&aacute;r l&eacute;tezik!.<br />Ha kiv&aacute;nja m&oacute;dos&iacute;tani most megeteheti.";
				header("Location: ".$_SERVER['HTTP_REFERER']."&cat_css_id=".$row['id']);
				exit();			
			}
			break;
		case "update_cat_color":			
			$dadd = time();
			$query = "Update cat_css Set
				name='".$_POST['name']."', 
				css='".$_POST['css']."',
				description='".$_POST['description']."',
				modify_date=".$dadd.",
				modify_uid=".$__user_id."
				Where id=".$_POST['cat_css_id'];
			mysql_query($query) or die(mysql_error());
			$_SESSION['message'] = "Sikeres m&oacute;dos&iacute;t&aacute;s!";
			header("Location: ".$_SERVER['HTTP_REFERER']."&cat_css_id=".$_POST['cat_css_id']);
			exit();
			break;
		case "del_cat_color":
			if($_POST['cat_css_id']!=""){
				$query = "Delete From cat_css Where id=".$_POST['cat_css_id'];
				mysql_query($query) or die(mysql_error());
			}
			header("Location: ".$_SERVER['HTTP_REFERER']);
			exit;	
			break;
	}
	
	switch($__sub_id){
		case 1:
			$smarty->assign('cat_colors', get_cat_colors());
			break;
		case 2:			
			if($_GET['cat_css_id'])
				$smarty->assign('css', get_cat_color_by_id($_GET['cat_css_id']));
			$smarty->assign('message', $_SESSION['message']);
			$_SESSION['message'] = "";			
			break;	
	}
?>