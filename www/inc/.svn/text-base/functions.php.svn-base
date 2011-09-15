<?php	
	function get_hot_news_pages($checked, $cols){		
		$query = "Select page_id, page_name, page_url From pages Where visible=1 Order by order_by ASC";
		$result = mysql_query($query) or die(mysql_error());
		$total = mysql_num_rows($result);
		$rows = ceil($total/$cols);
		$i=0;
		$j=0;
		while($row = mysql_fetch_assoc($result)){
			if($checked!=""){
				if(strpos($checked, "-".$row['page_url']."-")===false) $checked_page = 0;
					else $checked_page = 1;
			}else $checked_page = 1;
			if($j==$rows){
				$i++;
				$j=0;
			}
			$categories[$i][$j] = array(
				'page_id'=>$row['page_id'],
				'page_name'=>$row['page_name'], 
				'page_url'=>$row['page_url'], 
				'checked'=>$checked_page
			);
			$j++;
		}
		
		return $categories;
	}
	function get_hot_news($checked, $limit, $lead, $special){
		if($lead==true){
			$query = "Select news.id as news_id, news_title, news_lead, pages.page_id as page_id, page_url, agency_name, news.dadd as dadd 
						   From news
						   Left Join rss_categories On rss_categories.rss_id=news.rss_id
						   Left Join page_categories On page_categories.cat_id=rss_categories.cat_id
						   Left Join pages On page_categories.page_id=pages.page_id
						   Where pages.page_id is not null And pages.page_id!=1
						   Order by dadd DESC 
						   Limit ".$limit;
		}else{
			$query = "Select news.id as news_id, news_title, pages.page_id as page_id, page_url, agency_name, news.dadd as dadd 
						   From news
						   Left Join rss_categories On rss_categories.rss_id=news.rss_id
						   Left Join page_categories On page_categories.cat_id=rss_categories.cat_id
						   Left Join pages On page_categories.page_id=pages.page_id
						   Where pages.page_id is not null And pages.page_id!=1
						   Order by dadd DESC 
						   Limit ".$limit;
		}			   
		$result = mysql_query($query) or die(mysql_error());
		$i=0;
		while($row = mysql_fetch_array($result)){
			if($checked!=""){
				if(strpos($checked, "-".$row['page_url']."-")===false) $checked_page = 0;
					else $checked_page = 1;			
			}else $checked_page = 1;
			
			$dadd = explode(" ", $row['dadd']);
			$dadd = explode(":", $dadd[1]);			
			if($special) $lead = htmlspecialchars($row['news_lead']);
				else $lead = $row['news_lead'];
				
			$hot_news[$i] = array(
				'news_id'=>$row['news_id'],
				'news_title'=>$row['news_title'],
				'news_lead'=>$lead,
				'page_id'=>$row['page_id'],
				'page_url'=>$row['page_url'],
				'agency_name'=>$row['agency_name'],
				'dadd'=>$dadd[0].":".$dadd[1], 
				'checked'=>$checked_page
			);
			$i++;
		}
		
		return $hot_news;
	}
	
	function get_all_pages(){
		$query = "Select page_id, page_name From pages Order by page_name;";
		$result = mysql_query($query) or die(mysql_error());
		$i=0;
		while($row = mysql_fetch_assoc($result)){
			$categories[$i] = array(
				'page_id'=>$row['page_id'],
				'page_name'=>$row['page_name']
			);
			$i++;
		}
		return $categories;
	}
	
	function get_all_agencies(){
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
		return $agencies;
	}
	
	function get_all_search_periods(){
		$periods = array("Mai nap", "Ut&oacute;bbi h&eacute;t nap", "Ut&oacute;bbi 30 nap", "Ut&oacute;bbi 90 nap");
		return $periods;	
	}
	
	function get_main_seach_results($q, $lead, $agency, $period, $category){
		$keyword = explode(" ", $_GET['q']);
		$where = "";
		$news_title = "";
		$news_lead = "";
		for($i=0;$i<count($keyword);$i++){
			if($i!=0){
				$news_title .=  "And news_title like '%".$keyword[$i]."%' ";
				if($lead==1) $news_lead .= "And news_lead like '%".$keyword[$i]."%' ";				
			}else{
				$news_title .=  "news_title like '%".$keyword[$i]."%' ";
				if($lead==1) $news_lead .= "news_lead like '%".$keyword[$i]."%' ";					
			}
		}
		if($news_lead!="")	$where = "((".$news_title.") Or (".$news_lead.")) ";
			else $where = $news_title;
		switch($period){
			case 0: //today
				$dadd = date("Y-m-d H:i:s", mktime (0,0,0, date("m"), date("d"), date("Y")));
				break;
			case 1: //last 7 days
				$dadd = date("Y-m-d H:i:s", mktime (0,0,0, date("m"), date("d")-7, date("Y")));
				break;
			case 2://last 30 days
				$dadd = date("Y-m-d H:i:s", mktime (0,0,0, date("m"), date("d")-30, date("Y")));
				break;
			case 3://last 90 days
				$dadd = date("Y-m-d H:i:s", mktime (0,0,0, date("m"), date("d")-90, date("Y")));
				break;			
			default://last 30 days
				$dadd = date("Y-m-d H:i:s", mktime (0,0,0, date("m"), date("d")-30, date("Y")));
				break;	
		}
		if($agency!="") $where .= " And agency_id=".$agency;
		if($category!=""){
			$query = "Select rss_id
					   From page_categories 
					   Left Join rss_categories On page_categories.cat_id=rss_categories.cat_id
					   Where page_id=".$category;
			$result = mysql_query($query) or die(mysql_error());
			$rss_ids = array();
			while($row = mysql_fetch_assoc($result)){
				if($row['rss_id']!="")
					array_push($rss_ids, $row['rss_id']);
			}
			$where .= " And rss_id in (".implode(", ", $rss_ids).") ";
		}		
		$query = "Select count(distinct(news_title)) as nr
				   From news 				   
				   Where ".$where." And dadd>='".$dadd."'";					

		$result = mysql_query($query) or die(mysql_error());
		$row = mysql_fetch_array($result);
		return $row['nr'];
	}
	function feed_search_rss($q, $lead, $agency, $period, $category, $from, $limit){
		$keyword = explode(" ", $_GET['q']);
		$where = "";
		$news_title = "";
		$news_lead = "";	
		for($i=0;$i<count($keyword);$i++){
			if($i!=0){
				$news_title .=  "And news_title like '%".$keyword[$i]."%' ";
				if($lead==1) $news_lead .= "And news_lead like '%".$keyword[$i]."%' ";				
			}else{
				$news_title .=  "news_title like '%".$keyword[$i]."%' ";
				if($lead==1) $news_lead .= "news_lead like '%".$keyword[$i]."%' ";					
			}
		}
		if($news_lead!="")	$where = "((".$news_title.") Or (".$news_lead.")) ";
			else $where = $news_title;
		switch($period){
			case 0: //today
				$dadd = date("Y-m-d H:i:s", mktime (0,0,0, date("m"), date("d"), date("Y")));
				break;
			case 1: //last 7 days
				$dadd = date("Y-m-d H:i:s", mktime (0,0,0, date("m"), date("d")-7, date("Y")));
				break;
			case 2://last 30 days
				$dadd = date("Y-m-d H:i:s", mktime (0,0,0, date("m"), date("d")-30, date("Y")));
				break;
			case 3://last 90 days
				$dadd = date("Y-m-d H:i:s", mktime (0,0,0, date("m"), date("d")-90, date("Y")));
				break;			
			default://last 30 days
				$dadd = date("Y-m-d H:i:s", mktime (0,0,0, date("m"), date("d")-30, date("Y")));
				break;	
		}
		if($agency!="") $where .= " And agency_id=".$agency;
		if($category!=""){
			$query = "Select rss_id
					   From page_categories 
					   Left Join rss_categories On page_categories.cat_id=rss_categories.cat_id
					   Where page_id=".$category;
			$result = mysql_query($query) or die(mysql_error());
			$rss_ids = array();
			while($row = mysql_fetch_assoc($result)){
				if($row['rss_id']!="")
					array_push($rss_ids, $row['rss_id']);
			}
			$where .= " And rss_id in (".implode(", ", $rss_ids).") ";
		}		
		$query = "Select 
				   news_title, id, agency_name, dadd, news_lead 
				   From news 
				   Where ".$where." And dadd>='".$dadd."' 
				   Group by news_title
				   Order by dadd DESC Limit ".($from*$limit).", ".$limit;				
		$result = mysql_query($query) or die(mysql_error());		
		$i = 0;
		while($row = mysql_fetch_array($result)){
			$news[$i] = array(
				'id'=>$row['id'],
				'news_title'=>$row['news_title'],
				'url'=>$row['news_url'],
				'news_lead'=>htmlspecialchars($row['news_lead']), 
				'dadd'=>make_date($row['dadd']),
				'agency_name'=>$row['agency_name'], 
			);
			$i++;
		}

		return $news;
	}
	
	function main_search($q, $lead, $agency, $period, $category, $from, $limit){
		$keyword = explode(" ", $_GET['q']);
		$where = "";
		$news_title = "";
		$news_lead = "";		
		for($i=0;$i<count($keyword);$i++){
			if($i!=0){
				$news_title .=  "And news_title like '%".$keyword[$i]."%' ";
				if($lead==1) $news_lead .= "And news_lead like '%".$keyword[$i]."%' ";				
			}else{
				$news_title .=  "news_title like '%".$keyword[$i]."%' ";
				if($lead==1) $news_lead .= "news_lead like '%".$keyword[$i]."%' ";					
			}
		}
		if($news_lead!="")	$where = "((".$news_title.") Or (".$news_lead.")) ";
			else $where = $news_title;
		switch($period){
			case 0: //today
				$dadd = date("Y-m-d H:i:s", mktime (0,0,0, date("m"), date("d"), date("Y")));
				break;
			case 1: //last 7 days
				$dadd = date("Y-m-d H:i:s", mktime (0,0,0, date("m"), date("d")-7, date("Y")));
				break;
			case 2://last 30 days
				$dadd = date("Y-m-d H:i:s", mktime (0,0,0, date("m"), date("d")-30, date("Y")));
				break;
			case 3://last 90 days
				$dadd = date("Y-m-d H:i:s", mktime (0,0,0, date("m"), date("d")-90, date("Y")));
				break;			
			default://last 30 days
				$dadd = date("Y-m-d H:i:s", mktime (0,0,0, date("m"), date("d")-30, date("Y")));
				break;	
		}
		if($agency!="") $where .= " And agency_id=".$agency;
		if($category!=""){
			$query = "Select rss_id
					   From page_categories 
					   Left Join rss_categories On page_categories.cat_id=rss_categories.cat_id
					   Where page_id=".$category;
			$result = mysql_query($query) or die(mysql_error());
			$rss_ids = array();
			while($row = mysql_fetch_assoc($result)){
				if($row['rss_id']!="")
					array_push($rss_ids, $row['rss_id']);
			}
			$where .= " And rss_id in (".implode(", ", $rss_ids).") ";
		}		
		$query = "Select 
				   news_title, id, agency_name, dadd, news_lead
				   From news 
				   Where ".$where." And dadd>='".$dadd."' 
				   Group by news_title
				   Order by dadd DESC Limit ".($from*$limit).", ".$limit;				
		   
		$result = mysql_query($query) or die(mysql_error());
		$i = 0;
		while($row = mysql_fetch_array($result)){
			$news[$i] = array(
				'id'=>$row['id'],
				'title'=>$row['news_title'],
				'url'=>$row['news_url'],
				'lead'=>$row['news_lead'], 
				'dadd'=>make_date($row['dadd']),
				'agency'=>$row['agency_name'], 
			);
			$i++;
		}

		return $news;
	}
	
	function make_date($date){
		$months = array(
			'01'=>'Janu&aacute;r',
			'02'=>'Febru&aacute;r',
			'03'=>'M&aacute;rcius',
			'04'=>'&Aacute;prilis',
			'05'=>'M&aacute;jus',
			'06'=>'J&uacute;nius',
			'07'=>'J&uacute;lius',
			'08'=>'Augusztus',
			'09'=>'Szeptember',
			'10'=>'Okt&oacute;ber',
			'11'=>'November',
			'12'=>'December',
		);
		$temp = explode(" ", $date);
		$date = explode("-", $temp[0]);
		$time = explode(":", $temp[1]);
		//2006. ápr 10. 12:37 
		return $date[0].". ".$months[$date[1]]." ".$date[2].". ".$time[0].":".$time[1];
	}
?>