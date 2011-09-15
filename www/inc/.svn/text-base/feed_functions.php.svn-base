<?php
    
	function getWebNote($userID, $webNoteID){
		$query = "Select webnote From user_webnotes2 Where user_id=".$userID." And id=".$webNoteID." Limit 1";		
		$result = mysql_query($query) or die(mysql_error());
		$row = mysql_fetch_assoc($result);
		return $row['webnote'];
	}
	
	function updateWebNote($userID, $webNoteId, $content){
		$query = "Update user_webnotes2 Set webnote='".$content."' Where id=".$webNoteId." And user_id=".$userID." Limit 1;";
		mysql_query($query) or die(mysql_error());
	}
	
	function removeWebNote($userID, $webNoteID){
		$query = "Delete From user_webnotes2 Where user_id=".$userID." And id='".$webNoteID."' Limit 1";
		mysql_query($query) or die(mysql_error());
	}
	
	function addWebnote($userID, $content, $maxBoxID){
		#$query = "Insert Into user_webnotes2 Set id=".$maxBoxID.", user_id=".$userID.", webnote='".$content."', dadd='".date("Y-m-d H:i:s")."';";
		$query = "Insert Into user_webnotes2 Set user_id=".$userID.", webnote='".$content."', dadd='".date("Y-m-d H:i:s")."';";
		mysql_query($query) or die(mysql_error());
		return mysql_insert_id();
	}
	
	function updateUserFeed($userID, $feedID, $feedTitle, $feedURL=""){
        if (get_magic_quotes_gpc()) {
            $feedURL = stripslashes($feedURL);
            $feedTitle = stripslashes($feedTitle);
        }
        $furl = empty($feedURL) ? "" : ", feed_url='" . mysql_real_escape_string($feedURL) . "'";
        $feedTitle = mysql_real_escape_string($feedTitle);
		$query = "Update user_feeds2 Set feed_title='".$feedTitle."' $furl  Where user_id=".$userID." And id=".$feedID." Limit 1";
		mysql_query($query) or die(mysql_error());
	}
	
	function removeUserFeeds($userID, $feedIDs){
		$query = "Delete From user_feeds2 Where user_id=$userID And id in (".$feedIDs.") And `type`=2";		
		mysql_query($query) or die(mysql_error());
	}

	function removeUserFeed($userID, $feedID, $type=0){
        $t = $type ? "and `type`='$type'" : "";
		$query = "Delete From user_feeds2 Where user_id=$userID And id=$feedID $t Limit 1";		
		mysql_query($query) or die(mysql_error());
	}
	
	function getDefaultFeedById($feedID){
		$query = "Select rss_name, rss_url, agency_favicon, agency_url, rss_feeds.feed_type
					From rss_feeds 
					Left Join agencies On agencies.agency_id=rss_feeds.agency_id
					Where id=$feedID Limit 1";
		$result = mysql_query($query) or die(mysql_error());
		$result = mysql_fetch_assoc($result);
		return array(
            'id'=>$feedID,
			'title'=>$result['rss_name'], 
			'feed'=>$result['rss_url'], 
			'feed_encoded'=>urlencode($result['rss_url']), 
			'feed_favicon'=>$result['agency_favicon'],
			'agency_url'=>$result['agency_url'],
            'type'=>$result['feed_type'],
		);				
	}
	
	function getUserFeedById($userID, $feedID){
		$query = "Select * From user_feeds2 Where user_id=$userID And id=$feedID";
		$result = mysql_query($query) or die(mysql_error());
		$result = mysql_fetch_assoc($result);
		return array(
			'title'=>$result['feed_title'], 
			'feed'=>$result['feed_url'], 
			'feed_encoded'=>urlencode($result['feed_url']), 
			'feed_favicon'=>$result['feed_favicon'],
			'agency_url'=>$result['agency_url'],
            'type'=>$result['type'],
		);				
	}
	
	function getFeaturedFeeds(){
		$query = "Select id, feed_cat_id, rss_name, rss_url, agency_favicon, agency_url, rss_feeds.feed_type
					From rss_feeds 
					Left Join agencies On agencies.agency_id=rss_feeds.agency_id
					Where rss_feeds.feed_type in (1, 4) And featured=1 Order by rss_name";
		$result = mysql_query($query) or die(mysql_error());
		$i=0;
		while($row = mysql_fetch_assoc($result)){
			$feeds[$i] = array(
				'id'=>$row['id'], 
				'feed_cat_id'=>$row['feed_cat_id'], 
				'rss_name'=>$row['rss_name'], 
				'rss_url'=>$row['rss_url'], 
				'agency_favicon'=>$row['agency_favicon'], 
				'agency_url'=>$row['agency_url'],
                'type'=>$row['feed_type'],
			);
			$i++;
		}
		return $feeds;
	}	
	
	function getUserFeeds($userID){
        $feeds=array();
		$query = "Select id, feed_title, feed_url, feed_favicon, agency_url, type From user_feeds2 Where user_id=$userID And `type` in (1, 4);";
		$result = mysql_query($query) or die(mysql_error());
		$i=0;
		while($row = mysql_fetch_array($result)){
			$feeds[$i] = array(
				'id'=>$row['id'],
				'rss_name'=>$row['feed_title'],
				'rss_url'=>str_replace(array("'","\""), array("\\'", "&quot;"), $row['feed_url']),
                'keywords'=>str_replace(array("'","\""), array("\\'", "&quot;"), $row['feed_url']),
				'agency_favicon'=>$row['feed_favicon'], 
				'agency_url'=>$row['agency_url'],
                'myfeed' => 1,
                'type'=>$row['type'],
			);
			$i++;
		}
		return $feeds;
	}
	
	function saveUserFeed($userID, $feedName, $feedURL, $feedFavIcon, $agencyURL, $type=1){
		$dadd = date("Y-m-d H:i:s");
		$query = "Insert into user_feeds2 (user_id, feed_title, feed_url, feed_favicon, agency_url, `type`, dadd) Values (".$userID.", '".$feedName."', '".$feedURL."', '".$feedFavIcon."', '".$agencyURL."', ".$type.", '".$dadd."')";
		mysql_query($query) or die(mysql_error());		
		return mysql_insert_id();
	}
	
	
	function parseURL($url){
		$link_pattern1 = "/(http|ftp):(\\\\|\/\/)(.*?)[#|\\\\|\\?|\\.|\/]*\\s$/"; 
		$link_pattern2 = "/(.*?)[#|\\\\|\\?|\\.|\/]*\\s$/"; 
		$link_pattern  = "/^(http|ftp):(\\\\|\/\/)/";
		preg_match($link_pattern, stripslashes($url)." ", $www);				
		if(count($www)!="0"){
			preg_match($link_pattern1, stripslashes($url)." ", $matches);
			$link_url = $matches[3];
		}else{						
			preg_match($link_pattern2, stripslashes($url)." ", $matches);
			$link_url = $matches[1];
		}				
		return $link_url;		
	}
	
	function parseFeed($url, &$result, $order = "desc"){
global $debug;
		
		include_once ('../pear/Parser.php');
        $contents = file_get_contents($url);
		$feed = new XML_Feed_Parser($contents);		
		if (!$feed->valid && !empty($contents)) {
            $contents_mod = "";
            while ($contents_mod != $contents) {
                $contents_mod = $contents;
                $contents = preg_replace("/^(.*)(<\?xml)/ims","\$2", $contents_mod); 
                $contents = preg_replace("/^(.*<\/rss>)(.*<\/rss>.*)/ims","\$1", $contents); 
                $contents = preg_replace("/^(.*<\/rss>)(.*)/ims","\$1", $contents); 
                $contents = preg_replace("/<!--(.*?)-->/ms","", $contents); 
                $contents = str_replace(" & ", " &amp; ", $contents);
            }
            $feed = new XML_Feed_Parser($contents);		
            
        }   
        if ($feed->valid) {
			$max_entries = $feed->numberEntries;				
			switch($feed->type){
				case "RSS0.91":
					$head = array(
						'title'=>$feed->title,
						'link'=>$feed->link
					);					
					for($i=0;$i<$max_entries;$i++){
						$entry = $feed->getEntryByOffset($order == "desc" ? $i : $max_entries-$i-1);
						$entries[$i] = array(
							'title'=>$entry->title, 
							'link'=>$entry->link,
							'pubDate'=>$entry->pubDate,
							'description'=>preg_replace("/<.*?>/", "", $entry->description),												
							'author'=>$entry->author,
							'content'=>$entry->content
						);							
					}
					break;
				case "RSS0.92":
					$head = array(
						'title'=>$feed->title,
						'link'=>$feed->link
					);					
					for($i=0;$i<$max_entries;$i++){
						$entry = $feed->getEntryByOffset($order == "desc" ? $i : $max_entries-$i-1);
						$entries[$i] = array(
							'title'=>$entry->title, 
							'link'=>$entry->link,
							'pubDate'=>$entry->pubDate,
							'description'=>preg_replace("/<.*?>/", "", $entry->description),												
							'author'=>$entry->author,
							'content'=>$entry->content
						);							
					}
					break;
				case "RSS1.0":
					$head = array(
						'title'=>$feed->title,
						'link'=>$feed->link
					);					
					for($i=0;$i<$max_entries;$i++){
						$entry = $feed->getEntryByOffset($order == "desc" ? $i : $max_entries-$i-1);
						$entries[$i] = array(
							'title'=>$entry->title, 
							'link'=>$entry->link,
							'pubDate'=>$entry->pubDate,
							'description'=>preg_replace("/<.*?>/", "", $entry->description),														
							'author'=>$entry->author,
							'content'=>$entry->content
						);							
					}
					break;
				case "RSS2.0":
					$head = array(
						'title'=>$feed->title,
						'link'=>$feed->link
					);					
					$i = 0;
					$j = 0;
					for($i=0;$i<$max_entries;$i++){
						$entry = $feed->getEntryByOffset($order == "desc" ? $i : $max_entries-$i-1);
						$title = $entry->title;
						if (!empty($title)) {
							$entries[$j] = array(
								'title'=>$entry->title, 
								'link'=>$entry->link,
								'pubDate'=>$entry->pubDate,
								'description'=>preg_replace("/<.*?>/", "", $entry->description),														
								'author'=>$entry->author,
								'content'=>$entry->content
							);							
							$j++;
						}

					}					
					break;
				case "ATOM":
					
					$head = array(
						'title'=>$feed->title,
						'link'=>$feed->link
					);
					
					for($i=0;$i<$max_entries;$i++){
						$entry = $feed->getEntryByOffset($order == "desc" ? $i : $max_entries-$i-1);
						$entries[$i] = array(
							'title'=>$entry->title, 
							'link'=>$entry->id,
							'pubDate'=>$entry->updated,
							'description'=>preg_replace("/<.*?>/", "", $entry->content),
							'author'=>$entry->author,
							'content'=>$entry->content
						);						
					}
					
					break;
			}			
			$result = array(
				'head'=>$head, 
				'entries'=>$entries
			);
			return true;
		} else {
            return false;
        }
		
		
	}
    function kw_feeditems_sort($a, $b) {
        if ($a["ts_dadd"] == $b["ts_dadd"]) return 0;
        return $a["ts_dadd"] > $b["ts_dadd"] ? -1 : 1;
        
    }
    function parseKeywordsFeed($keywords, &$result, $limit=20) { 
        global $_HI_var;
        $items = array();
        $earliest = $_HI_var->test_site ? 0 : time() - 14 * 24 * 60 * 60;
        foreach ($keywords as $kw) {
            if (!empty($kw)) {
                $where_dadd = $earliest ? "and n.dadd > '" . date("Y-m-d", $earliest) ."'" : "";
                $mkw = preg_replace("/(\s|-)/", "||||", $kw);
                $mkw = preg_replace("/\|\|\|\|/", "( |-)", $mkw);
                $orregexp = $mkw != $kw ? " or n.news_title regexp '$mkw'" : "";
                $orregexp2 = $mkw != $kw ? " or n.news_lead regexp '$mkw'" : "";
                $sql = "select n.*, unix_timestamp(n.dadd) ts_dadd, f.rss_name from news2 n left join rss_feeds f on n.rss_id = f.id where (n.news_title like '%$kw%' or news_lead like '%$kw%' $orregexp $orregexp2) $where_dadd and f.status = 1 order by n.dadd desc limit $limit";
                if (isset($_REQUEST["debug"]) && $_REQUEST["debug"] == "apro") print "$sql<br><br>";
                $r = mysql_query($sql)  or die(mysql_error());
                while ($k = mysql_fetch_assoc($r)) {
                 //   foreach($k as $key=>$val) $k[$key] = htmlspecialchars(trim($val)); 
                    $alr = 0;
                    $k["news_url"] = str_replace("&amp;", "&", $k["news_url"]);
                    $k["news_title"] = trim($k["news_title"]);
                    foreach ($items as $i) if ($i["news_title"] == $k["news_title"] || $i["news_url"] == $k["news_url"]) $alr = 1; //mar hozza lett adva ennek a hirfolyamnak egy masik hirforrasabol

                    if (!$alr) $items[$k["id"]] = $k;
                }
                usort($items,"kw_feeditems_sort");
                $items2 = $items;
                $items = array();
                foreach($items2 as $i) $items[$i["id"]] = $i;
                if (count($items) > 20) {
                    $items = array_slice($items, 0, 20, TRUE);
                }
                if (count($items) <= 20) {
                    $t = array_keys($items);
                    $earliest = array_pop($t);
                }
            }
        }
        $entries = array();
        foreach($items as $item) {
            if (date("Y.m.d", $item["ts_dadd"]) == date("Y.m.d")) {
                $pubDate = "Ma, " . date("G:i", $item["ts_dadd"]);
            } else {
                $pubDate = date("m.d", $item["ts_dadd"]);
            }
            $entries[] = array(
                'id'=>$item["id"],
                'title'=>$item["news_title"], 
                'link'=>$item["news_url"],
                'pubDate'=>$item["dadd"],
                'description'=>preg_replace("/<.*?>/", "", $item["news_lead"]),
                'author'=>"",
                'content'=>$item["news_lead"],
                'title_tail' => (empty($item["rss_name"]) ? "" : " - <i>$item[rss_name]</i>") . " - <i>$pubDate</i>",
		'rss_name' =>empty($item["rss_name"]) ? "" : " - $item[rss_name]",
                'rss_id'=>$item["rss_id"],
                'cat_id'=>$item["cat_id"],
            );						
        }
        $head = array(
            "title"=>"Head cime",
            "link"=>"head link",
        );
        $result = array(
            'head'=>$head, 
            'entries'=>$entries
        );
        return true;
    }
	
	function getFeedCategoryByName($category){
		$query = "Select cat_id, cat_name, cat_title, order_by From feed_categories Where cat_name like '". mysql_real_escape_string($category) . "'";
		$result = mysql_query($query) or die(mysql_error());
		$row = mysql_fetch_assoc($result);
		return $row;
	}
	function getFeedCategoryById($cat_id){
		$query = "Select cat_id, cat_name, cat_title, order_by From feed_categories Where cat_id=".$cat_id;
		$result = mysql_query($query) or die(mysql_error());
		$row = mysql_fetch_assoc($result);
		return $row;
	}
	function getFeedCategories(){
		$query = "Select cat_id, cat_name, cat_title, order_by From feed_categories Where status=1 And visible=1 Order by order_by ";
		$result = mysql_query($query) or die(mysql_error());
		$i=0;
		while($row = mysql_fetch_assoc($result)){
			$feed_categories[$i] = array(
				'cat_id'=>$row['cat_id'], 
				'cat_name'=>$row['cat_name'], 
				'cat_title'=>$row['cat_title'],
				'order_by'=>$row['order_by']
			);
			$i++;
		}
		return $feed_categories;
	}
	
	function getFeedByCat($feed_cat_id){
		$query = "Select id, feed_cat_id, rss_name, rss_url, agency_favicon, agency_url, rss_feeds.feed_type
					From feed_cats
					Left Join rss_feeds On rss_feeds.id=feed_cats.feed_id 
					Left Join agencies On agencies.agency_id=rss_feeds.agency_id
					Where rss_feeds.feed_type in (1, 4) And feed_cats.cat_id=$feed_cat_id Order by rss_name";
		$result = mysql_query($query) or die(mysql_error());
		$i=0;
		while($row = mysql_fetch_assoc($result)){
			$feeds[$i] = array(
				'id'=>$row['id'], 
				'feed_cat_id'=>$row['feed_cat_id'], 
				'rss_name'=>$row['rss_name'], 
				'rss_url'=>$row['rss_url'], 
				'agency_favicon'=>$row['agency_favicon'], 
				'agency_url'=>$row['agency_url'],
                'type'=>$row['feed_type'],
			);
			$i++;
		}
		return $feeds;
	}
		
	
	function getHtmlboxById($id){
        $query = "select * from htmlbox where id = '$id' limit 1";
		$result = mysql_query($query) or die(mysql_error());
        $box = mysql_fetch_assoc($result);
        $query = "select * from htmlbox_params where htmlbox_id = '$id'";
		$result = mysql_query($query) or die(mysql_error());
        $box["params"] = array();
        while ($k = mysql_fetch_assoc($result)) {
            $box["params"][] = $k;
        }
        //add field names to fit in the feed template
        $box["feed_type"] = 5;
        $box["type"] = 5;
        $box["feed_favicon"] = $box["favicon"];
        $box["feed"] = $box["id"];
        $box["agency_url"] = $box["url"];
        return $box;

    }
	function getFeedById($id){
		$query = "Select rss_name, rss_url, agency_favicon, agency_url, rss_feeds.feed_type as type
					From rss_feeds 
					Left Join agencies On agencies.agency_id=rss_feeds.agency_id
					Where id=$id Limit 1";				
		$result = mysql_query($query) or die(mysql_error());
		return mysql_fetch_assoc($result);
	}
    function renameUserFeed($user_id, $feed_id, $name) {
    }
    function deleteUserFeed($user_id, $feed_id) {
        $sql = "delete from rss_feeds where feed_id = '$feed_id' and user_id = '$user_id'";
    }
    function outKeywordsFeed($kw, $count=20) {
        $news = array();
        $keywords = array($kw);
        if(!parseKeywordsFeed($keywords, $news, $count)) {
            $error = 1; 
        } 
        return $news;
    }
?>
