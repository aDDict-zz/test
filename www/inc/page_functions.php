<?php
	function getFixedCategories($pageID){
        global $__user_id, $_HI_var, $smarty;
		$query = "Select fixed_categories From pages Where page_id=".$pageID;
		$result = mysql_query($query) or die(mysql_error());
		$row = mysql_fetch_assoc($result);
		$fixed_categories = unserialize($row['fixed_categories']);
		
		$fc_ids = array();
		if(is_array($fixed_categories)){
			foreach($fixed_categories as $key=>$box){
				foreach($box as $k=>$box_id){
                    if ($__user_id == -1 || $box_id != $_HI_var->fixed_category_register) {
    					$fc_ids[] = $box_id;
                    } else {
                        unset($fixed_categories[$key][$k]);
                    }
				}
			}
			
			$fc_ids = implode(", ", $fc_ids);

            if (!empty($fc_ids)) {
			
                $query = "Select * From fixed_categories Where cat_id in (".$fc_ids.")";
                $result = mysql_query($query) or die(mysql_error());
                while($row = mysql_fetch_assoc($result)){
                    $links = array();
                    if ($row["cat_type"] == 5) { //keyword box -> html box
                        $row["cat_type"] =2;
                        $row["cat_html"] = "{php include \"http://www.hirek.hu/rss_out.php?kw=$row[cat_keyword]&out=hirek&from=hirek\";}";
                    }
                    if($row['cat_type']==2){
                        $query = "Select link_name, link_url, link_title 
                                       From fixed_cat_links
                                       Left Join external_links On external_links.link_id=fixed_cat_links.link_id
                                       Where cat_id=".$row['cat_id'];
                        $link_result = mysql_query($query) or die(mysql_error())			   ;
                        $i=0;
                        while($l = mysql_fetch_assoc($link_result)){
                            $links[$i] = array(
                                'link_name'=>$l['link_name'],
                                'link_url'=>$l['link_url'],
                                'link_title'=>$l['link_title']
                            );
                            $i++;
                        }
                    }			
                    if($row['cat_type']==1){
                        ///parseFeed('http://'.$row['cat_rss'], $entries);
                        //$max_entries = $entries['entries'];
                    }
                    if ($row["cat_type"] == 2) {
                        $row['cat_html'] = parse_html_box(stripslashes($row['cat_html']), $row["cat_id"], $pageID);
                    }

                    $categories[$row['cat_id']] = array(				
                        'cat_name'=>$row['cat_name'],
                        'cat_rss'=>$row['cat_rss'],
                        'cat_html'=>$row['cat_html'],
                        'cat_favicon'=>$row['cat_favicon'],
                        'cat_type'=>$row['cat_type'],
                        'cat_links'=>$links
                    );
                    
                }
            }
			
			foreach($fixed_categories as $key=>$box){
				foreach($box as $k=>$box_id){				
					$fixed_categories[$key][$k] = array(
						'id'=>$box_id,
						'cat'=>$categories[$box_id]
					);
				}
			}
		}  
        if (!empty($_REQUEST["kw"]) && !empty($_REQUEST["from"])) {
            $kw = mysql_real_escape_string($_REQUEST["kw"]);
            $from = isset($_REQUEST["from"]) ? $_REQUEST["from"] : "";

            $cat_name = $kw;
            if (!empty($from)) {
                $sql = "select title from out_site where site = '" . mysql_real_escape_string($from). "'";
                $r = mysql_query($sql)  or die(mysql_error());
                if ($k = mysql_fetch_assoc($r)) {
                    $cat_name = $k["title"];
                }
            }
            $fc["id"] = 12;
            $fc["cat"] = array(
                "cat_favicon"=>"../i/in.gif",
                "cat_name"=>$cat_name,
                "cat_type"=>1,
                "cat_rss"=>$kw,
                "cat_html"=>"",
                "feed_type"=>4,
            );
            if (!is_array($fixed_categories)) $fixed_categories = array();
            if (!isset($fixed_categories[0])) $fixed_categories[0] = array();
            array_unshift($fixed_categories[0], $fc); 

        }
        if (is_array($fixed_categories)) {
            return $fixed_categories;
        } else {
            return null;
        }
		
	}
	
	function get_all_cat_css(){
		$query = "Select id, name From cat_css Order by name";
		$result = mysql_query($query) or die(mysql_error());
		$i=0;
		while($row = mysql_fetch_assoc($result)){
			$cat_css[$i] = array(
				'id'=>$row['id'],
				'name'=>$row['name']
			);
			$i++;
		}
		return $cat_css;
	}
	
	function get_pages(){
		$query = "Select * From pages Order by order_by";
		$result = mysql_query($query) or die(mysql_error());
        $last = 0;
		while($row = mysql_fetch_array($result)){
			$pages[$row["page_id"]] = array(
				'page_id'=>$row['page_id'],
				'page_name'=>$row['page_name'],
				'page_title'=>$row['page_title'],
				'page_url'=>$row['page_url'],
				'page_keywords'=>$row['page_keywords'],
				'page_template'=>$row['page_template'],
				'page_html'=>$row['page_html'], 
				'page_description'=>$row['page_description'], 
				'status'=>$row['status'],
				'order_by'=>$row['order_by'],
				'visible'=>$row['visible'],
                'last'=>0,
			);
            $last = $row["page_id"];
		}
        $pages[$last]["last"] = 1;
		return $pages;
	}
	
	function get_templates(){
		$query = "Select * From page_templates Order by template_name";
		$result = mysql_query($query) or die(mysql_error());
		$i=0;
		while($row = mysql_fetch_array($result)){
			$templates[$i] = array(
				'template_id'=>$row['template_id'],
				'template_name'=>$row['template_name'],
				'template_url'=>$row['template_url']
			);
			$i++;
		}
		return $templates;
	}
	
	function get_page_by_id($page_id){
		$query = "Select * From pages Where page_id=".$page_id;
		$result = mysql_query($query) or die(mysql_query());
		return mysql_fetch_assoc($result);
	}

	
	function getDefaultPages(){
		$query_pages = "Select page_title, page_id From pages Order by order_by";
		$result = mysql_query($query_pages) or die(mysql_error());
		$i = 0;
		while($row = mysql_fetch_assoc($result)){
			$pages[$i] = array(
				'page_title'=>$row['page_title'],
				'page_id'=>$row['page_id']
			);
			$i++;
		}
		return $pages;
	}
	
	function getDefaultPageStructure($page_id){		
		$quer_ps = "Select cat_column, cat_position, news_nr, rss_feeds.id as rss_id, page_categories.type, 1 editable
					   From page_categories 
					   inner Join categories On categories.cat_id=page_categories.cat_id 
					   Left Join rss_categories On rss_categories.cat_id=categories.cat_id
					   Left Join rss_feeds On rss_categories.rss_id=rss_feeds.id
					   Where page_categories.type = 1 and page_id=".$page_id." And status=1
                    
                    union all
                    
                    Select cat_column, cat_position, news_nr, h.id rss_id, pc.type, h.editable
                    from page_categories pc 
                    inner join htmlbox h on pc.cat_id = h.id
                    where pc.type = 5 and pc.page_id = '$page_id' and h.enabled = 'yes' 
					
                    Order by cat_column, cat_position";		
		$result_ps = mysql_query($quer_ps) or die(mysql_error());				
		$max_box_id = 1;
		while($row2 = mysql_fetch_assoc($result_ps)){			
				$box[$max_box_id] = array(
					'cl'=>$row2['cat_column'], //column
					'rw'=>$row2['cat_position']-1, //row
					'cr'=>2, // header color 2 - green
					'in'=>$row2['news_nr'], // items nr
					'ed'=>$row2['editable'], //editable 1 - true
					'mb'=>1, //moveable 1 -true, 
					'ty'=>$row2["type"], //type 1 - FEED
					'id'=>$row2['rss_id'], // feed_id
					'bi'=>$max_box_id, //unique box id
					'ow'=>1 // owner 1 = hirek.hu, 2 = user
				);
				$max_box_id++;	
		}
        $valid = valid_page_structure($box, $max_box_id);
		return array($box, $max_box_id);
	}
    function addFreshFeeds($page_id, $page_structure, $date_mod) {
        $rss_ids = array();
        $htmlbox_ids = array();
        foreach($page_structure as $idx=>$ps) {
            if ($ps["id"] != "") {
                if ($ps["ty"] == 5) {
                    $htmlbox_ids[] = $ps["id"];
                } else {
                    $rss_ids[] = $ps["id"];
                }   
            }
        }
        $where_rssids = count($rss_ids) ? " and rss_feeds.id not in (" . implode(",", $rss_ids) . ")" : "";
        $where_htmlboxids = count($htmlbox_ids) ? " and h.id not in (" . implode(",", $htmlbox_ids) . ")" : "";
		$sql = "Select cat_column, cat_position, news_nr, rss_feeds.id as rss_id, page_categories.type, 1 editable
					   From page_categories 
					   inner Join categories On categories.cat_id=page_categories.cat_id 
					   Left Join rss_categories On rss_categories.cat_id=categories.cat_id
					   Left Join rss_feeds On rss_categories.rss_id=rss_feeds.id
					   Where page_categories.type = 1 and page_id=".$page_id." And status=1 
                        and (rss_categories.date_add > '$date_mod'|| from_unixtime(page_categories.modify_date) > '$date_mod')
                        $where_rssids 
                    
                    union all
                    
                    Select cat_column, cat_position, news_nr, h.id rss_id, pc.type, h.editable
                    from page_categories pc 
                    inner join htmlbox h on pc.cat_id = h.id
                    where pc.type = 5 and pc.page_id = '$page_id' and h.enabled = 'yes' 
                        and from_unixtime(pc.modify_date) > '$date_mod'
                        $where_htmlboxids
					
                    Order by cat_column, cat_position";		
		$r = mysql_query($sql) or die(mysql_error());				
		$max_box_id = 1;
        $cols = array(0=>0, 1=>0, 2=>0);
        $box = array();
        $modified = 0;
		while($row2 = mysql_fetch_assoc($r)){			
				$box[$max_box_id] = array(
					'cl'=>$row2['cat_column'], //column
					'rw'=>$cols[$row2["cat_column"]], //$row2['cat_position']-1, //row
					'cr'=>2, // header color 2 - green
					'in'=>$row2['news_nr'], // items nr
					'ed'=>$row2['editable'], //editable 1 - true
					'mb'=>1, //moveable 1 -true, 
					'ty'=>$row2["type"], //type 1 - FEED
					'id'=>$row2['rss_id'], // feed_id
					'bi'=>$max_box_id, //unique box id
					'ow'=>1 // owner 1 = hirek.hu, 2 = user
				);
				$max_box_id++;	
                $cols[$row2["cat_column"]]++;
                $modified++;
		}
        foreach ($page_structure as $idx=>$ps) {
            $ps["rw"] += $cols[$ps["cl"]];
            $box[$max_box_id] = $ps;
            $max_box_id++;
        }
        return array($box, $max_box_id, $modified);
    }
	
    function removeDisabledFeeds(&$struc, $user_id) {
		global $connection;
        $modified = 0;
        $ids = array();
        $uids = array();
        $oids = array();
        foreach($struc as $idx=>$res) {
            if (!isset($res["ty"])) {
                    unset($struc[$idx]);
            } else {
                if ($res["ty"] == 1 && $res["id"]) {
                    if ($res["ow"] == 1) {
                        $ids[$res["id"]] = $idx;
                    } elseif ($res["ow"] == 2) {
                        $uids[$res["id"]] = $idx;
                    }
                }
            }
        } 
        if (count($ids)) {
    		$query = "select id from rss_feeds where status = 0 and id in ('" . implode("','", array_keys($ids)) . "')";
	    	$r = mysql_query($query) or die(mysql_error());
		    while($k = mysql_fetch_assoc($r)) {
                $col = $struc[$uids[$k["id"]]]["cl"];
                $row = $struc[$uids[$k["id"]]]["rw"];
                unset($struc[$ids[$k["id"]]]);
                foreach ($struc as $id=>$s) {
                    if ($s["cl"] == $col && $s["rw"] > $row) {
                        $struc[$id]["rw"]--;
                    }
                }
                $modified++;
            }
        }
        if (count($uids)) {
    		$query = "select uf.id from user_feeds2 uf inner join rss_feeds f on uf.feed_url = f.rss_url where f.status = 0 and uf.id in ('" . implode("','", array_keys($uids)) . "')";
	    	$r = mysql_query($query) or die(mysql_error());
		    while($k = mysql_fetch_assoc($r)) {
                $col = $struc[$uids[$k["id"]]]["cl"];
                $row = $struc[$uids[$k["id"]]]["rw"];
                unset($struc[$uids[$k["id"]]]);
                unset($uids[$k["id"]]);
                foreach ($struc as $id=>$s) {
                    if ($s["cl"] == $col && $s["rw"] > $row) {
                        $struc[$id]["rw"]--;
                    }
                }
                $modified++;
            }
        }
        if (count($uids)) {
    		$query = "select id from user_feeds2 where user_id = '$user_id' and  id in ('" . implode("','", array_keys($uids)) . "')";
	    	$r = mysql_query($query) or die(mysql_error());
		    while($k = mysql_fetch_assoc($r)) {
                unset($uids[$k["id"]]);
            }
            foreach($uids as $id=>$idx) {
                $col = $struc[$idx]["cl"];
                $row = $struc[$idx]["rw"];
                unset($struc[$idx]);
                unset($uids[$idx]);
                foreach ($struc as $id=>$s) {
                    if ($s["cl"] == $col && $s["rw"] > $row) {
                        $struc[$id]["rw"]--;
                    }
                }
                $modified++;
            }

        }

        return $modified;
    }
	function getUserDefaultPage($user_id, $page_id){
		global $connection;
		$query = "Select page_structure, max_box_id, date_mod From user_default_pages2 Where user_id=$user_id And page_id=$page_id Limit 1";
		$result = mysql_query($query) or die(mysql_error());

		
		$row = mysql_fetch_assoc($result);
        $page_structure = unserialize($row['page_structure']);
        $modified = removeDisabledFeeds($page_structure, $user_id);
        $rss_ids = array();
        $htmlbox_ids = array();
        list($page_structure, $max_box_id, $mod) = addFreshFeeds($page_id, $page_structure, $row["date_mod"]);
        $modified += $mod;
        $valid = valid_page_structure($page_structure, $max_box_id);
        if ($modified || !$valid) {
            saveUserDefaultPage($user_id, $page_id, serialize($page_structure), $max_box_id);
        }
        $result = array(
			"page_structure"=>$page_structure,
			"max_box_id"=>$max_box_id,
        );
		return $result;
	}
	
	function getUserPage($user_id, $page_id){
		global $connection;
		$query = "Select page_structure, max_box_id From user_pages2 Where user_id=$user_id And page_id=$page_id Limit 1";
		$result = mysql_query($query) or die(mysql_error());
		
		$row = mysql_fetch_assoc($result);
        $page_structure = unserialize($row['page_structure']);
        $max_box_id = $row["max_box_id"];
        $modified = removeDisabledFeeds($page_structure, $user_id);
        $valid = valid_page_structure($page_structure, $max_box_id);
        if ($modified || !$valid) {
            saveUserPage($user_id, $page_id, serialize($page_structure), $max_box_id);				
        }
        $result = array(
			"page_structure"=>$page_structure,
			"max_box_id"=>$max_box_id,
        );

		return $result;
	}
    function valid_page_structure(&$structure, &$max_box_id) {
        $again = true;
        $save_needed = false;
        $max_box_id = 0;
        while($again) {
            $max_box_id = 0;
            $again = false;
            $test = array();
            $testboxid = array();
            foreach($structure as $key=>$box){						
                if (isset($test[$structure[$key]['cl']][$structure[$key]['rw']])) {
                    $again = true;
                    $structure[$key]['rw']++;
                    break;
                }
                $test[$structure[$key]['cl']][$structure[$key]['rw']] = $key;
                if ($key > $max_box_id) $max_box_id = $key;
            }
        } 
        return $save_needed;
    }
	
	function saveUserDefaultPage($userID, $pageID, $pageStructure, $maxBoxID){
		global $connection;
		if($maxBoxID==-1) {
            $query = "Update user_default_pages2 Set page_structure='".$pageStructure."', date_mod = now() Where user_id=$userID And page_id=$pageID";		
        } else {
            $query = "Update user_default_pages2 Set page_structure='".$pageStructure."', max_box_id=".$maxBoxID.", date_mod = now() Where user_id=$userID And page_id=$pageID";
        }
		mysql_query($query) or die(mysql_error());		
	}
	
	function saveUserPage($userID, $pageID, $pageStructure, $maxBoxID){
		global $connection;
		if($maxBoxID==-1)	$query = "Update user_pages2 Set page_structure='".$pageStructure."' Where user_id=$userID And page_id=$pageID";		
			else $query = "Update user_pages2 Set page_structure='".$pageStructure."', max_box_id=".$maxBoxID." Where user_id=$userID And page_id=$pageID";		
		mysql_query($query) or die(mysql_error());		
	}
    function parse_html_box($html, $cat_id, $pageID = 0) {
        $html = str_replace("%page_id", $pageID, $html);
        $idx = 0;
        while (preg_match("/(\{php\s+include\s+\"([^\"\}]+.*)\"\s*;\s*\})/", $html, $m)) { 
            $contents = '';
            $file = $m[2];
            if (!empty($_REQUEST["news_id"])) {
                if (preg_match("/\?/", $file)) {
                    $file .= "&news_id=$_REQUEST[news_id]";
                } else {
                    $file .= "?news_id=$_REQUEST[news_id]";
                }
            }
            $contents = "<div id=\"fixed_content_{$pageID}_{$cat_id}_{$idx}\"></div><script>getFileContents('$file', $cat_id, $pageID, $idx);</script>";
            /*
            if ($handle = fopen($file, "rb")) {
                while (!feof($handle)) {
                  $contents .= fread($handle, 8192);
                }
                fclose($handle);
            }
            */
            $html = str_replace($m[1], $contents, $html);
            $idx++;
        }
        while (preg_match("/(\{php\s+([^\}]+)\})/", $html, $m)) { 
            try {
                eval($m[2]);
            } catch (Exception $e) {
            }
            $html = str_replace($m[1], $html);
        }
        return $html;
    }
		
?>
