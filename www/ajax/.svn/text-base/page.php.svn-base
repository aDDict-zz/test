<?php
	ini_set("display_errors", true);	
	session_start();
	header('Content-type:text/html; charset=UTF-8');
	include_once('../inc/db_prop.inc.php');
	include_once('../inc/page_functions.php');
	include_once('../libs/Smarty.class.php');
	include_once('../inc/smarty.config.php');
	include_once('../inc/lastRSS.php');
	
	$smarty = new Smarty;
	
	$smarty->template_dir = $template_dir;
	$smarty->compile_dir = $compile_dir;
	
	global $connection, $db;
	
	$connection = mysql_connect($host, $user, $psw) or die(mysql_error());
	$db = mysql_select_db($data_base, $connection) or die(mysql_error());
	mysql_query("Set names 'UTF8'") or die(mysql_error());
    
    if (!isset($_SESSION["logged"])) $_SESSION["logged"] = "-1:";
	list($__user_id, $__user_email) = explode(":", $_SESSION['logged']);
    if (empty($__user_id)) $__user_id = -1;

    if (!isset($_REQUEST['default'])) $_REQUEST['default'] = 0;
    
    $__user_id = intval($__user_id);
    $__user_email = mysql_real_escape_string($__user_email);
	#$__user_id = 1;

    $new_item_column = 0;

	switch($_REQUEST['action']){		
		case "update_box":
			if($_REQUEST['box_id'] && $__user_id !=-1){
				include_once('../inc/feed_functions.php');
				if($_REQUEST['page_id']=="") $page_id = 0;
					else $page_id = intval($_REQUEST['page_id']);
				$box_id = intval($_REQUEST['box_id']);
				if($_REQUEST['default']==1)	$result = getUserDefaultPage($__user_id, intval($_REQUEST['page_id']));
					else $result = getUserPage($__user_id, $page_id);
				$box = $result['page_structure'];
				
				$box[$box_id]['cr'] = $_REQUEST['box_color'];
				$box[$box_id]['in'] = $_REQUEST['box_items_nr'];
				if($_REQUEST['default']==1)	{
                    saveUserDefaultPage($__user_id, $page_id, serialize($box), -1);
                } else {
                    saveUserPage($__user_id, $page_id, serialize($box), -1);
                }
				updateUserFeed($__user_id, $box[$box_id]['id'], $_REQUEST['box_title'], parseURL($_REQUEST['box_feed']));
			}
			break;
		case "remove_box":		
			if($_REQUEST['page_id']!="" && $__user_id !=-1){			
				if($_REQUEST['general']==1)	$result = getUserDefaultPage($__user_id, intval($_REQUEST['page_id']));				
					else $result = getUserPage($__user_id, intval($_REQUEST['page_id']));
				$box = $result['page_structure'];
				$box_id = intval($_REQUEST['box_id']);
				include_once('../inc/feed_functions.php');
				
				switch($_REQUEST['type']){
					case 1:if($box[$box_id]['ow']==2) removeUserFeed($__user_id, $box[$box_id]['id'], 2);break;
					case 2:removeWebNote($__user_id, $box[$box_id]['id']);break;
				}
				
				
				$col = $box[$box_id]['cl'];
				$row = $box[$box_id]['rw'];
				unset($box[$box_id]);
				$i = $row;
				foreach($box as $key=>$b){					
					if($box[$key]['cl'] == $col && $box[$key]['rw'] > $row){						
						$box[$key]['rw'] = $box[$key]['rw']-1;
						$i++;
					}
				}
				if($_REQUEST['general']==1)	saveUserDefaultPage($__user_id, intval($_REQUEST['page_id']), serialize($box), -1);
					else saveUserPage($__user_id, intval($_REQUEST['page_id']), serialize($box), -1);				
			}	
			break;
		case "update_strucure":
			if($_REQUEST['page_id']!="" && $__user_id !=-1){
				if($_REQUEST['default']==1)	{
                    $result = getUserDefaultPage($__user_id, intval($_REQUEST['page_id']));
                } else {
                    $result = getUserPage($__user_id, intval($_REQUEST['page_id']));
                }
				$box = $result['page_structure'];
				if($_REQUEST['firstCol']!=""){
					$col = explode(",", $_REQUEST['firstCol']);					
					for($i=0;$i<count($col);$i++){					
						$box[$col[$i]]['cl'] = 0;
						$box[$col[$i]]['rw'] = $i;
					}
				}
				if($_REQUEST['secondCol']!=""){					
					$col = explode(",", $_REQUEST['secondCol']);
					for($i=0;$i<count($col);$i++){
						$box[$col[$i]]['cl'] = 1;
						$box[$col[$i]]['rw'] = $i;
					}
				}												
				if($_REQUEST['thirdCol']!=""){					
					$col = explode(",", $_REQUEST['thirdCol']);
					for($i=0;$i<count($col);$i++){
						$box[$col[$i]]['cl'] = 2;
						$box[$col[$i]]['rw'] = $i;
					}
				}
				if($_REQUEST['default']==1) {
                    saveUserDefaultPage($__user_id, intval($_REQUEST['page_id']), serialize($box), -1);
                } else {
                    saveUserPage($__user_id, intval($_REQUEST['page_id']), serialize($box), -1);				
                }
            }
			
			break;
		case "add_new_tab":
			if($__user_id !=-1){
				include_once('../inc/memcache.php');
				$tabs = new Tabs($connection);
				$tabs->getTabs($__user_id, $result);			
				$i = count($result);
				$result[$i] = array('title'=>$_REQUEST['tab_name'], 'pos'=>$_REQUEST['tab_id'], 'id'=>$_REQUEST['tab_id']);
				$tabs->updateTabs($__user_id, $result);				
				$tabs->addNewTab($__user_id, intval($_REQUEST['tab_id']));
			}
			break;
		case "resort_tabs":
			if($__user_id !=-1){
				include_once('../inc/memcache.php');
				
				$tabs = new Tabs($connection);
				
				$ids = explode(',', $_REQUEST['ids']);
				$titles = explode(',', $_REQUEST['titles']);
				
				for($i=0;$i<count($ids);$i++){
					$result[$i] = array(
						'title'=>$titles[$i], 'pos'=>$i, 'id'=>$ids[$i]
					);
				}			
				
				$tabs->updateTabs($__user_id, $result);
				if($_REQUEST['on_delete']=="true" && $_REQUEST['tab_id']!=""){
					$tabs->deleteTab($__user_id, intval($_REQUEST['tab_id']));
				}
			}
			break;
		case "get_tabs":		
			if($__user_id!=-1){								
				include_once('../inc/memcache.php');
				$tabs = new Tabs($connection);				
				$tabs->getTabs($__user_id, $result);								
			}else{			
				$result[0] = array(
					'title'=>'Saját oldal',
					'pos'=>0,
					'id'=>0
				);
			}
			
			$smarty->assign('tabs', $result);
			$smarty->display('tabs.html');
			
			break;
		case "get_default_page_structure":
				include_once('../inc/feed_functions.php');
				if($_REQUEST['page_id']=="") $page_id = 0;
					else $page_id = intval($_REQUEST['page_id']);

				$fixed_categories = getFixedCategories($page_id);								
				$smarty->assign('fixed_categories', $fixed_categories);
				if($__user_id!=-1){
					$result = getUserDefaultPage($__user_id, $page_id);						
					$structure = $result['page_structure'];
				}else{
					$result['page_structure'] = getDefaultPageStructure($page_id);					
					$result['max_box_id'] = count($result['page_structure']);
					$structure = $result['page_structure'][0];
				}										
				
				$struc = array();
				
				if(is_array($structure)==1){					
					foreach($structure as $key=>$box){							
              //          print "new box, type: " . $structure[$key]['ty'] . ", id: " .$structure[$key]['id'] . "editable" .$structure[$key]['id']  . "<br>";
                        if ($structure[$key]['ty'] == 5 && $structure[$key]['id']) {
                            $feed = getHtmlboxById($structure[$key]['id']);
                        //    print "id: " . $structure[$key]['id'] . "<br><pre>";print_r($feed);
                        } elseif ($structure[$key]['ty']==1 && $structure[$key]['id']){
							if($structure[$key]['ow']==1) {
                                $feed = getDefaultFeedById($structure[$key]['id']);
                            } else {
                                $feed = getUserFeedById($__user_id, $structure[$key]['id']);
                            }
						}
						
                        if ($feed["type"] == 4) { //keyword based rss
//                            $keywords = str_replace("||"," ", $feed["feed"]);
                            $keywords = $feed["feed"];
                            $keywords_encoded = urlencode(str_replace("'", "\'", $keywords));
                        } else {
                            $keywords = "";
                            $keywords_encoded = "";
                        }
						$struc[$structure[$key]['cl']][$structure[$key]['rw']] = array(
                            'id'=>isset($feed['id']) ? $feed["id"] : 0,
							'title'=>$feed['title'], 
							'feed'=>$feed['feed'], 
							'feed_encoded'=>urlencode($feed['feed']),
                            'keywords'=>$keywords,
                            'keywords_encoded'=>$keywords_encoded,
                            'feed_type'=>$feed['type'],
							'feed_favicon'=>$feed['feed_favicon'],
							'agency_url'=>$feed['agency_url'],
							'closed'=>0, 
							'items_nr'=>$structure[$key]['in'], 
							'color'=>$structure[$key]['cr'], 
							'type'=>$structure[$key]['ty'], 
							'bid'=>$key, 
							'rid'=>$structure[$key]['id'],
							'editable'=>$structure[$key]["ty"] == 5 ? (isset($structure[$key]['ed']) ? $structure[$key]['ed'] :1) : ($__user_id == -1 ? 0 : 1),
							'moveable'=>$structure[$key]['mb'], 
							'closeable'=>1,
                            'owner'=>$structure[$key]['ow'],
						);							
					}
				}
									
                for ($i=0;$i<3;$i++) {
                    if (isset($struc[$i])) {
                        ksort($struc[$i], SORT_NUMERIC);
                    } else {
                        $struc[$i] = "";
                    }
                }
				
				$smarty->assign('struc', $struc);
				for($i=0;$i<20;$i++){
					$items[$i] = $i+1;
				}
				$smarty->assign('items', $items);
				$smarty->assign('max_box_id', $result['max_box_id']);
				$smarty->assign('page_id', $page_id);

                $smarty->assign('page_prefix', '');

                $adslot =  get_adslot($page_id);
				$smarty->display('page.html');
			    	
			break;
		case "get_page_structure":
				include_once('../inc/feed_functions.php');
				$fixed_categories = getFixedCategories(-1);				
				$smarty->assign('fixed_categories', $fixed_categories);
				
				if($__user_id!=-1){
					if($_REQUEST['page_id']=="") $page_id = 0;
						else $page_id = intval($_REQUEST['page_id']);
					
					$result = getUserPage($__user_id, $page_id);					
					$structure = $result['page_structure'];					
                    $max_box_id = $result["max_box_id"];
					$struc = array();
					
					if(is_array($structure)==1){					

						foreach($structure as $key=>$box){						
                            unset($feed);
                            if ($structure[$key]['ty'] == 5 && $structure[$key]['id']) {
                                $feed = getHtmlboxById($structure[$key]['id']);
							} elseif ($structure[$key]['ty']==1 && $structure[$key]['id']) {
								$feed = getUserFeedById($__user_id, $structure[$key]['id']);							
                            }
                            if (isset($feed)) {
                                if ($feed["type"] == 4) { //keyword based rss
    //                                $keywords = str_replace("||", " ", $feed["feed"]);
                                    $keywords_encoded = urlencode(str_replace("'", "\'", $feed["feed"]));
                                } else {
                                    $keywords = "";
                                    $keywords_encoded = "";
                                }
                            }
							$struc[$structure[$key]['cl']][$structure[$key]['rw']] = array(
								'title'=>isset($feed) ? $feed['title']:'', 
								'feed'=>isset($feed) ? $feed['feed'] :'', 
								'feed_encoded'=>isset($feed) ? urlencode($feed['feed']) : '',
                                'keywords' => $keywords,
                                'keywords_encoded'=>$keywords_encoded,
								'feed_favicon'=>isset($feed) ? $feed['feed_favicon'] :'',
								'agency_url'=>isset($feed) ? $feed['agency_url'] : '',
								'closed'=>0, 
								'items_nr'=>$structure[$key]['in'], 
								'color'=>$structure[$key]['cr'], 
                                'feed_type'=>isset($feed) ? $feed['type'] : '',
								'type'=> $structure[$key]['ty'], 
								'bid'=>$key, 
								'rid'=>$structure[$key]['id'],
                                'editable'=>$structure[$key]["ty"] == 5 ? (isset($structure[$key]['ed']) ? $structure[$key]['ed'] :1) : ($__user_id == -1 ? 0 : 1),
								'moveable'=>$structure[$key]['mb'], 
								'closeable'=>1,
                                'owner'=>$structure[$key]['ow'],
							);
						}
					}
                    for ($i=0;$i<3;$i++) {
                        if (isset($struc[$i])) {
                            ksort($struc[$i], SORT_NUMERIC);
                        } else {
                            $struc[$i] = "";
                        }
                    }
					
					$smarty->assign('struc', $struc);
					for($i=0;$i<20;$i++){
						$items[$i] = $i+1;
					}
					$smarty->assign('items', $items);
					$smarty->assign('max_box_id', $result['max_box_id']);					
					
					$smarty->assign('page_id', $page_id);
				}else{
					if($_REQUEST['page_id']=="") $page_id = 0;
						else $page_id = intval($_REQUEST['page_id']);
					$smarty->assign('max_box_id', 0);
					if($struc[0]=='')	$struc[0] = '';
					if($struc[1]=='')	$struc[1] = '';
					if($struc[2]=='')	$struc[2] = '';
					
					$smarty->assign('struc', $struc);
					
					$smarty->assign('page_id', $page_id);
				}
				
				
				
                $adslot =  get_adslot($page_id);
                $smarty->assign('page_prefix', 's');
				$smarty->display('page.html');				
			break;
		case "add_webseach":
				if($__user_id!=-1){
					include_once('../inc/feed_functions.php');
					if($_REQUEST['default']==1)	{
                        $result = getUserDefaultPage($__user_id, intval($_REQUEST['page_id']));
                    } else {
                        $result = getUserPage($__user_id, intval($_REQUEST['page_id'])); 
                    }
					
					$box = $result['page_structure'];
					$max_box_id = $result['max_box_id'];
					if(is_array($box)==1){
						foreach($box as $key=>$values){
							if($box[$key]['cl']==$new_item_column){
								$box[$key]['rw'] = $box[$key]['rw'] + 1;			
							}						
						}
					}
					
					$max_box_id++; 								
					$box[$max_box_id] = array(
						'cl'=>$new_item_column, //column
						'rw'=>0, //row
						'cr'=>2, // header color 2 - green
						'in'=>10, // items nr
						'ed'=>0, //editable 1 - true
						'mb'=>1, //moveable 1 -true, 
						'ty'=>3, //type 3 - WEBSEARCH
						'id'=>'', // feed_id
						'bi'=>$max_box_id, //unique box id
						'ow'=>2 // owner 1 = hirek.hu, 2 = user
					);
					
					if($_REQUEST['default']==1)	saveUserDefaultPage($__user_id, intval($_REQUEST['page_id']), serialize($box), $max_box_id);
						else saveUserPage($__user_id, intval($_REQUEST['page_id']), serialize($box), $max_box_id);
	
					$smarty->assign('sb_bid', $max_box_id);
				}
				$smarty->display('search_box.html');
			break;	
		case "add_webnote":
				if($__user_id!=-1){
					include_once('../inc/feed_functions.php');
					if($_REQUEST['default']==1)	{
                        $result = getUserDefaultPage($__user_id, intval($_REQUEST['page_id']));
                    } else {
                        $result = getUserPage($__user_id, intval($_REQUEST['page_id']));
                    }
					
					$box = $result['page_structure'];
					$max_box_id = $result['max_box_id'];
					if(is_array($box)==1){
						foreach($box as $key=>$values){
							if($box[$key]['cl']==$new_item_column){
								$box[$key]['rw'] = $box[$key]['rw'] + 1;			
							}						
						}
					}
					
					$max_box_id++; 
					include_once '../lang/hu/webnote.php';
					$webnote_id = addWebnote($__user_id, "", $max_box_id);
					
					$box[$max_box_id] = array(
						'cl'=>$new_item_column, //column
						'rw'=>0, //row
						'cr'=>2, // header color 2 - green
						'in'=>10, // items nr
						'ed'=>1, //editable 1 - true
						'mb'=>1, //moveable 1 -true, 
						'ty'=>2, //type 2 - WEBNOTE
						'id'=>$webnote_id, // feed_id
						'bi'=>$max_box_id, //unique box id
						'ow'=>2 // owner 1 = hirek.hu, 2 = user
					);
					
					$smarty->assign('new', 1);
					$smarty->assign('wn_id', $webnote_id);
					$smarty->assign('wn_bid', $max_box_id);
                    $smarty->assign("__default_text", $__default_text);
					
					if($_REQUEST['default']==1)	saveUserDefaultPage($__user_id, intval($_REQUEST['page_id']), serialize($box), $max_box_id);
						else saveUserPage($__user_id, intval($_REQUEST['page_id']), serialize($box), $max_box_id);
	
					$smarty->assign('max_box_id', $max_box_id);
                }
				
				$smarty->display('webnote.html');
			break;
		case "add_new_html_box":
            include_once('../inc/feed_functions.php');					
            $general = isset($_REQUEST["general"]) && $_REQUEST["general"] == 1 ? 1 : 0;
            $htmlbox_id = intval($_REQUEST["htmlbox_id"]);
            $default = isset($_REQUEST["default"]) ? $_REQUEST["default"] : 0; //add to users own page (Sajat oldal) or to default pages (Cimlap, Itthon, ....)
            $htmlbox_url = $_REQUEST["htmlbox_url"];
            $htmlbox_link = $_REQUEST["htmlbox_link"];
            $htmlbox_title = get_magic_quotes_gpc() ? stripslashes($_REQUEST["htmlbox_title"]) : $_REQUEST["htmlbox_title"];
            $htmlbox = getHtmlboxById($htmlbox_id);											
            if ($__user_id != -1) {
                //add box to users page - copied from "add_new_rss_box"
                if($default)	{
                    $result = getUserDefaultPage($__user_id, intval($_REQUEST['page_id']));
                } else {
                    $result = getUserPage($__user_id, intval($_REQUEST['page_id']));
                }
                $box = $result['page_structure'];
                $max_box_id = $result['max_box_id'];
                $cl = $new_item_column;
                $cls = array(0=>0, 1=>0, 2=>0);
                if(is_array($box)==1){
                    foreach($box as $key=>$values){
                        $cls[$box[$key]['cl']]++;
                        if($box[$key]['cl']==$new_item_column){
                            $box[$key]['rw'] = $box[$key]['rw'] + 1;			
                        }						
                    }
                }
                $cl = $cls[0] <= $cls[1] ? ($cls[0] <= $cls[2] ? 0 : 2) : ($cls[1] <= $cls[2] ? 1 : 2);
                $max_box_id++; 
                $box[$max_box_id] = array(
                    'cl'=>$cl, //column
                    'rw'=>0, //row
                    'cr'=>2, // header color 2 - green
                    'in'=>10, // items nr
                    'ed'=>$htmlbox["editable"] == "no" ? 0 : 1, //editable 1-true
                    'mb'=>1, //moveable 1 -true, 
                    'ty'=>5, //type 5 - HTML box 
                    'id'=>$htmlbox_id, // htmlbox_id
                    'bi'=>$max_box_id, //unique box id
                    'ow'=>2 // owner 1 = hirek.hu, 2 = user
                );
                $htmlbox["bid"] = $max_box_id;
                #print "default: $default<pre>";print_r($box);print "</pre>";
                if($default) {
                    saveUserDefaultPage($__user_id, intval($_REQUEST['page_id']), serialize($box), $max_box_id);
                } else {
                    saveUserPage($__user_id, intval($_REQUEST['page_id']), serialize($box), $max_box_id);
                }
                //end of: add box to users page 
                    
            } else {
            }
            $smarty->assign('cl', $cl);
            $smarty->assign("htmlbox", $htmlbox);
            $smarty->display('html_box.html');
            break;
		case "add_new_rss_box":
            $general = isset($_REQUEST["general"]) && $_REQUEST["general"] == 1 ? 1 : 0;
            if($__user_id!=-1){
                include_once('../inc/feed_functions.php');
                if($general){
                    $feedResult = getFeedById(intval($_REQUEST['feed_id']));											
                    $feedId = saveUserFeed($__user_id, $feedResult['rss_name'], $feedResult['rss_url'], $feedResult['agency_favicon'], $feedResult['agency_url'], 2);
                } else {
                    $feedId = intval($_REQUEST['feed_id']);
                }
                $default = $_REQUEST['default'];
                if($default!=1)	{
                    $result = getUserPage($__user_id, intval($_REQUEST['page_id']));
                } else {
                    $result = getUserDefaultPage($__user_id, intval($_REQUEST['page_id']));
                }
                $box = $result['page_structure'];
                $max_box_id = $result['max_box_id'];
                $cl = $new_item_column;
                $cls = array(0=>0, 1=>0, 2=>0);
                if(is_array($box)==1){
                    foreach($box as $key=>$values){
                        $cls[$box[$key]['cl']]++;
                        if($box[$key]['cl']==$new_item_column){
                            $box[$key]['rw'] = $box[$key]['rw'] + 1;			
                        }						
                    }
                }
                $cl = $cls[0] <= $cls[1] ? ($cls[0] <= $cls[2] ? 0 : 2) : ($cls[1] <= $cls[2] ? 1 : 2);
                $max_box_id++; 
                $box[$max_box_id] = array(
                    'cl'=>$cl, //column
                    'rw'=>0, //row
                    'cr'=>2, // header color 2 - green
                    'in'=>10, // items nr
                    'ed'=>1,//editable 1-true
                    'mb'=>1, //moveable 1 -true, 
                    'ty'=>1, //type 1 - FEED
                    'id'=>$feedId, // feed_id
                    'bi'=>$max_box_id, //unique box id
                    'ow'=>2 // owner 1 = hirek.hu, 2 = user
                );
                
                if($default!=1)	saveUserPage($__user_id, intval($_REQUEST['page_id']), serialize($box), $max_box_id);
                    else saveUserDefaultPage($__user_id, intval($_REQUEST['page_id']), serialize($box), $max_box_id);
                    
                $url = parseURL($_REQUEST['feed_link']);
                $http_get = get_headers('http://'.$url.'/favicon.ico');
                $g =  strtolower(trim($http_get[0]));
                if(preg_match("/^http\/[^\s]+\s+404/", $g))	{
                    $favicon = '';
                } else {
                    $favicon = 'http://'.$url.'/favicon.ico';
                }
                if (!$general && $f = getUserFeedById($__user_id, intval($_REQUEST['feed_id']))) {
                    $feed_type = $f["type"];
                } else {
                    $feed_type = 1;
                }

                $feed_name = get_magic_quotes_gpc() ? stripslashes($_REQUEST["feed_name"]) : $_REQUEST["feed_name"];
                $feed = array(
                    
                    'feed_id' => $_REQUEST['feed_id'], 
                    'rss_name' => $feed_name,
                    'rss_url' => 'http://'.parseURL($_REQUEST['feed_url']),
                    'keywords' => rawurldecode($_REQUEST['feed_url']),
                    'agency_favicon' => $favicon,
                    'agency_url' => 'http://'.$url,
                    'editable'=>1,
                    'type'=>$feed_type,
                    'user_feed_id' =>$feedId,
                );
                $smarty->assign('feed', $feed);					
                for($i=0;$i<20;$i++){
                    $items[$i] = $i+1;
                }
                $smarty->assign('cl', $cl);
                $smarty->assign('max_box_id', $max_box_id);
                $smarty->assign('items', $items);
            }else{
                include_once('../inc/feed_functions.php');					
                if($general){
                    $feedResult = getFeedById(intval($_REQUEST['feed_id']));					
                }
                $feedId = intval($_REQUEST['feed_id']);					
                    
                $url = parseURL($_REQUEST['feed_link']);
                $url = parseURL($_REQUEST['feed_link']);
                $http_get = get_headers('http://'.$url.'/favicon.ico');
                $g =  strtolower(trim($http_get[0]));
                if(preg_match("/^http\/[^\s]+\s+404/", $g))	{
                    $favicon = '';
                } else {
                    $favicon = 'http://'.$url.'/favicon.ico';
                }
                if (!$general && $f = getFeedById(intval($_REQUEST['feed_id']))) {
                    $feed_type = $f["type"];
                } else {
                    $feed_type = 1;
                }
                $feed = array(
                    'feed_id' => intval($_REQUEST['feed_id']), 
                    'rss_name' => $_REQUEST['feed_name'],
                    'rss_url' => 'http://'.parseURL($_REQUEST['feed_url']),
                    'keywords' => rawurldecode($_REQUEST['feed_url']),
                    'agency_favicon' =>$favicon,
                    'agency_url' => 'http://'.$url,
                    'editable'=>0,
                    'type'=>$feed_type,
                    'user_feed_id' =>$feedId,
                );	
                $smarty->assign('feed', $feed);					
                for($i=0;$i<20;$i++){
                    $items[$i] = $i+1;
                }
                $smarty->assign('max_box_id', 0);
                $smarty->assign('items', $items);
            }
            $smarty->display('rss_box.html');
				
			break;			
		case "get_hints":
            $hints = "";
            $hints .= "var pageHints = new Array();";
            $hints .= "pageHints['register'] = 'Az oldal kiemelt szolgáltatásait regisztrált felhasználóink élvezhetik. A regisztrációt (email+jelszó) követően, Ön azonnal létrehozhatja egyéni hírportálját.';";
            if ($__user_id == -1) {
                $hints .= "pageHints['uj_oldal'] = pageHints['register'];";
            } else {
                $hints .= "pageHints['uj_oldal'] = 'Hozzon létre egy vagy több Új oldalt (új fület), így több, teljesen egyedi oldal-összeállítási lehetőség áll az Ön rendelkezésére.';";
            }
            $hints .= "pageHints['addContent'] = 'Ha szeretné új hírforrásokat felvenni, vagy teljesen egyéni, kulcsszó alapú témafigyelést kíván beállítani, kattintson ide.';";
            $hints .= "pageHints['uj_hirforras_felvetele'] = 'Vigyen fel új hírforrást, URL-mezőbe másolja be a megfelelő URL-t, majd nyomja meg a Felvisz gombot.';";
            $hints .= "pageHints['kiemelt_hirforrasok'] = 'Itt találhatóak azok az előre beállított hírforrások, melyek közül kiválaszthatja, és testre szabhatja az Önt leginkább érdeklő oldalakat.';";
            $hints .= "pageHints['tematikus_hirforrasok'] = 'A Tematikus hírforrások alatti listában szűkített témaköröket talál: sporthíreken belül például választhat, hogy csak autós vagy vízilabdás hírek jelenjenek meg.';";
            $hints .= "pageHints['kulcsszavas_hirforrasok'] = 'Új hírforrást létrehozhat kulcsszavak alapján is, így a hírdobozban csak olyan hírek jelennek meg, melyek tartalmazzák az adott szavakat.';";
            $hints .= "pageHints['kereso'] = 'Keressen a hirek.hu archívumában található cikkekben vagy az interneten.';";
            $hints .= "pageHints['jegyzet'] = 'Készítsen jegyzeteket, feljegyzéseket, amelyek mindig szem előtt lesznek az oldalon.';";
            print $hints;    
            break;
	}	
    function get_adslot($page_id) {
        global $__user_id;
        
        if (eregi("(Googlebot|Slurp)",$_SERVER["HTTP_USER_AGENT"])) {
            return "";
        }


        $adslots = array(
           1 =>array("adslot_id"=>"hhu_cimlap","url"=>"http://ad.hirekmedia.hu/ad.php?adslot=j-1110","napi_url"=>"http://ad.hirekmedia.hu/ad.php?adslot=j-1124"),
           4 =>array("adslot_id"=>"hhu_itthon","url"=>"http://ad.hirekmedia.hu/ad.php?adslot=j-1111","napi_url"=>"http://ad.hirekmedia.hu/ad.php?adslot=j-1128"),
           5 =>array("adslot_id"=>"hhu_kulfold","url"=>"http://ad.hirekmedia.hu/ad.php?adslot=j-1112","napi_url"=>"http://ad.hirekmedia.hu/ad.php?adslot=j-1129"),
           2 =>array("adslot_id"=>"hhu_sport","url"=>"http://ad.hirekmedia.hu/ad.php?adslot=j-1113","napi_url"=>"http://ad.hirekmedia.hu/ad.php?adslot=j-1132"),
           6 =>array("adslot_id"=>"hhu_bulvar","url"=>"http://ad.hirekmedia.hu/ad.php?adslot=j-1114","napi_url"=>"http://ad.hirekmedia.hu/ad.php?adslot=j-1123"),
           7 =>array("adslot_id"=>"hhu_kultura","url"=>"http://ad.hirekmedia.hu/ad.php?adslot=j-1115","napi_url"=>"http://ad.hirekmedia.hu/ad.php?adslot=j-1130"),
           3 =>array("adslot_id"=>"hhu_auto","url"=>"http://ad.hirekmedia.hu/ad.php?adslot=j-1116","napi_url"=>"http://ad.hirekmedia.hu/ad.php?adslot=j-1122"),
           8 =>array("adslot_id"=>"hhu_infotech", "url"=>"http://ad.hirekmedia.hu/ad.php?adslot=j-1117","napi_url"=>"http://ad.hirekmedia.hu/ad.php?adslot=j-1127"),
           9 =>array("adslot_id"=>"hhu_tudomany",  "url"=>"http://ad.hirekmedia.hu/ad.php?adslot=j-1118","napi_url"=>"http://ad.hirekmedia.hu/ad.php?adslot=j-1133"),
           10 =>array("adslot_id"=>"hhu_gazdasag","url"=>"http://ad.hirekmedia.hu/ad.php?adslot=j-1119","napi_url"=>"http://ad.hirekmedia.hu/ad.php?adslot=j-1126"),
           11 =>array("adslot_id"=>"hhu_eletmod","url"=>"http://ad.hirekmedia.hu/ad.php?adslot=j-1120","napi_url"=>"http://ad.hirekmedia.hu/ad.php?adslot=j-1125"),
           -1 =>array("adslot_id"=>"hhu_sajat", "url"=>"http://ad.hirekmedia.hu/ad.php?adslot=j-1121","napi_url"=>"http://ad.hirekmedia.hu/ad.php?adslot=j-1131"),
        );
        
        $now = time();
        $adslot =  $__user_id != -1 || !isset($adslots[$page_id]) ? $adslots[-1] : $adslots[$page_id];
        if($fp = fopen($adslot["url"], 'r')) {
            fclose($fp);
        }
            
        $cname = "$adslot[adslot_id]_knapi";
        if (isset($_COOKIE["$cname"]) && strlen($_COOKIE["$cname"])) {
            $track_cookie=eregi_replace("[^0-9a-f]","",$_COOKIE["$cname"]);
        } else {
            $track_cookie=substr(md5(mt_rand(1,10000).$_SERVER["REMOTE_ADDR"]),4,15);
        }
        setcookie($cname,$track_cookie,time()+31536000,'/');
        

        $r=mysql_query("select last from admeasure where visitor='$track_cookie' and page='$page_id' limit 1");
        if ($z = mysql_fetch_row($r)) {
            $last=$z[0];
            mysql_query("update admeasure set last=$now where visitor='$track_cookie' and page='$page_id'");
        } else {
            $ip=mysql_real_escape_string($_SERVER["REMOTE_ADDR"]);
            mysql_query("insert into admeasure set last=$now,visitor='$track_cookie',page='$page_id',ip='$ip'");
        } 
        if ($now-$last>60*24*24) {  //instead 60*24*24 there should be seconds since the day started
            if($fp = fopen($adslot["napi_url"], 'r')) {
                fclose($fp);
            }
        }

    }
?>
