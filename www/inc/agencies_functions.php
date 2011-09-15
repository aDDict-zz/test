<?php
	function get_rss_feeds_by_agency($id){
		$query = "Select feed_type, rss_name, rss_url, id From rss_feeds Where agency_id=".$id;
		$result = mysql_query($query) or die(mysql_error());
		$i=0;
        $rss_feeds = array();
		while($row = mysql_fetch_array($result)){
			$rss_feeds[$i] = array(
				'id'=>$row['id'],
				'rss_url'=>$row['rss_url'],
				'rss_name'=>$row['rss_name'],
				'feed_type'=>$row['feed_type']
			);
			$i++;
		}
		return $rss_feeds;
	}
	
	function get_agency_by_id($id){
		$query = "Select agency_id, agency_name, agency_url, agency_description, agency_type, agency_favicon From agencies Where agency_id=".$id;
		$result = mysql_query($query) or die(mysql_error());
		return mysql_fetch_assoc($result);
	}
	
	function get_agencies_result($name, $url, $type){
		$where = "";
		if($name!=""){
			$name = explode(" ", $name);				
			$where_name = "";
			for($i=0;$i<count($name);$i++){
				if($i==0) $where_name = "agency_name like '%".$name[$i]."%' ";
					else $where_name .= "And agency_name like '%".$name[$i]."%' ";
			}
		}
		if($url!=""){
			$url = explode(" ", $url);
			$where_url = "";
			for($i=0;$i<count($url);$i++){
				if($i==0) $where_url = "agency_url like '%".$url[$i]."%' ";
					else $where_url .= "And agency_url like '%".$url[$i]."%' ";
			}
		}

		if($where_name!="" && $where_url!="") $where .= "((".$where_name.") Or (".$where_url.")) ";
		if($where_name!="" && $where_url=="")	$where .= " (".$where_name.") ";
		if($where_name=="" && $where_url!="") $where .= " (".$where_url.") ";
		if($type!="" && $where!="") $where .= " And agency_type=".$type;
			else if($type!="" && $where=="") $where .= " agency_type=".$type;
		if($where!="") $where = " Where ".$where;
		$query = "Select count(agency_id) as nr
				   From agencies".$where;
		$result = mysql_query($query) or die(mysql_error());		
		$row = mysql_fetch_assoc($result);
		return $row['nr'];		
	}
	function get_all_agencies(){
		$query = "Select agencies.agency_id as agency_id, agency_name, agency_url, agency_type, count(id) as news_flow_nr
				   From agencies 
				   Left Join rss_feeds On rss_feeds.agency_id=agencies.agency_id
				   Group by agencies.agency_id
				   Order by agency_name";
		$result = mysql_query($query) or die(mysql_error());
		$i=0;
		while($row = mysql_fetch_assoc($result)){
			$agencies[$i] = array(
				'agency_id'=>$row['agency_id'],
				'agency_name'=>$row['agency_name'],
				'agency_url'=>$row['agency_url'], 
				'agency_type'=>$row['agency_type'], 
				'news_flow_nr'=>$row['news_flow_nr']
			);
			$i++;
		}
		return $agencies;		
	}
	
	function get_agencies($name='', $url='', $type='', $from=0, $limit=0){
		$where = "";
		if($name!=""){
			$name = explode(" ", $name);				
			$where_name = "";
			for($i=0;$i<count($name);$i++){
				if($i==0) $where_name = "agency_name like '%".$name[$i]."%' ";
					else $where_name .= "And agency_name like '%".$name[$i]."%' ";
			}
		}
		if($url!=""){
			$url = explode(" ", $url);
			$where_url = "";
			for($i=0;$i<count($url);$i++){
				if($i==0) $where_url = "agency_url like '%".$url[$i]."%' ";
					else $where_url .= "And agency_url like '%".$url[$i]."%' ";
			}
		}

		if($where_name!="" && $where_url!="") $where .= "((".$where_name.") Or (".$where_url.")) ";
		if($where_name!="" && $where_url=="")	$where .= " (".$where_name.") ";
		if($where_name=="" && $where_url!="") $where .= " (".$where_url.") ";
		if($type!="" && $where!="") $where .= " And agency_type=".$type;
			else if($type!="" && $where=="") $where .= " agency_type=".$type;
		if($where!="") $where = " Where ".$where;
		
		if($from == 0 && $limit == 0) $limit = '';
			else $limit = "Limit ".($from*$limit).", ".$limit;
		$query = "Select agencies.agency_id as agency_id, agency_name, agency_url, agency_type, count(id) as news_flow_nr
				   From agencies 
				   Left Join rss_feeds On rss_feeds.agency_id=agencies.agency_id".$where."
				   Group by agencies.agency_id
				   Order by agency_name ".$limit;
		$result = mysql_query($query) or die(mysql_error());
		$i=0;
		while($row = mysql_fetch_assoc($result)){
			$agencies[$i] = array(
				'agency_id'=>$row['agency_id'],
				'agency_name'=>$row['agency_name'],
				'agency_url'=>$row['agency_url'], 
				'agency_type'=>$row['agency_type'], 
				'news_flow_nr'=>$row['news_flow_nr']
			);
			$i++;
		}
		return $agencies;		
	}
	
	function get_rss_feed_by_id($id){
		$query = "Select id, feed_type, agency_id, rss_name, rss_url, rss_description, pattern, matches, aux_url, period, status, news_order From rss_feeds Where id=".$id;
		$result = mysql_query($query) or die(mysql_error());
		$row = mysql_fetch_assoc($result);
		$matches = explode(",", $row['matches']);
		$rss = array(
			'id'=>$row['id'],
			'feed_type'=>$row['feed_type'],
			'agency_id'=>$row['agency_id'],
			'rss_name'=>$row['rss_name'],
			'rss_url'=>$row['rss_url'],
			'rss_description'=>$row['rss_description'],
			'pattern'=>htmlspecialchars($row['pattern']),
			'aux_url'=>$row['aux_url'],
			'match_link'=>$matches[0],
			'match_title'=>isset($matches[1]) ? $matches[1] : "",
			'match_lead'=>isset($matches[2]) ? $matches[2] : "",
			'period'=>$row['period'],
			'status'=>$row['status'],
            'news_order'=>$row['news_order'],
		);		
		return $rss;
	}
	
	function get_rss_feeds(){
		$query = "Select agency_name, agency_url,id, rss_name, rss_url, feed_type
				   From rss_feeds
				   Left Join agencies On agencies.agency_id=rss_feeds.agency_id
				   Order by agency_name";
		$result = mysql_query($query) or die(mysql_error());
		$i=0;
		while($row = mysql_fetch_assoc($result)){
			$rss_feeds[$i] = array(
				'agency_name'=>$row['agency_name'],
				'agency_url'=>$row['agency_url'],
				'id'=>$row['id'],
				'rss_name'=>$row['rss_name'],
				'rss_url'=>$row['rss_url'],
				'feed_type'=>$row['feed_type']				
			);
			$i++;
		}
		return $rss_feeds;
	}
?>
