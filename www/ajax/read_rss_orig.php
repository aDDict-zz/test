<?php
	session_start();
	include_once('../inc/db_prop.inc.php');
	include_once('../libs/Smarty.class.php');
	include_once('../inc/smarty.config.php');
	include_once('../inc/lastRSS.php');
	include_once('../inc/globals.php');
	include_once('../lang/'.$__lang.'/error.php');
	include_once('../inc/feed_functions.php');

	$smarty = new Smarty;
	
	$smarty->template_dir = $template_dir;
	$smarty->compile_dir = $compile_dir;
	$smarty->cache_dir = $cache_dir;
	
	global $connection, $db;
	$connection = mysql_connect($host, $user, $psw) or die(mysql_error());
	$db = mysql_select_db($data_base, $connection) or die(mysql_error());
	mysql_query("Set names 'UTF8'") or die(mysql_error());
	
    list($__user_id, $__user_email) = explode(":", $_SESSION['logged']);

	#$__user_id = 1;
	switch($_REQUEST['action']){		
		case "read_rss":
            $feed = isset($_REQUEST["feed"]) ? $_REQUEST["feed"] : "";
            $type = isset($_REQUEST["type"]) ? $_REQUEST["type"] : 1;
            if (empty($feed) && isset($_REQUEST["feed_id"])) {
                $feed_id = mysql_real_escape_string($_REQUEST["feed_id"]);
                if ($feed_id) {
                    $sql = "select * from user_feeds2 where id = '$feed_id'";
                    $r = mysql_query($sql)  or die(mysql_error());
                    if ($k = mysql_fetch_assoc($r)) {
                        if ($__user_id == $k["user_id"] || $k["user_id"] == $_HI_var->keywords_feed_user_id) {
                            $feed = $k["feed_url"];
                            $type = $k["type"];
                        }
                    }
                }
            }
			if($feed != ""){													
                header('Content-type:text/html; charset=utf-8');										
                $smarty->cache_lifetime = 3000; 
                $smarty->compile_check = true;
                $smarty->caching = true; 
                $error = 0;
                $assign = 0;
                $marked_news = 0;
                if(isset($_REQUEST["force"]) && $_REQUEST['force']==1) $smarty->force_compile = 1;
                $news_id = isset($_REQUEST["news_id"]) ? mysql_real_escape_string($_REQUEST["news_id"]) : 0;
                if ($type == 1) {
                    $url = 'http://'.parseURL($feed);
                    $query = "select id, news_order from rss_feeds where rss_url = '$url' or rss_url = '$url/'";
                    $r = mysql_query($query) or die(mysql_error());
                    $news_order = "desc";
                    if ($row = mysql_fetch_assoc($r)) {
                        $smarty->assign("rss_link_tail", "&rss=$row[id]");
                        $news_order = $row["news_order"];
                    }
                    if($news_id || !$smarty->is_cached('rss_list.html', md5($url))) { 
                        if(parseFeed($url, $result, $news_order)) {
                            $assign = 1;
                        } else {
                            $error = 1; 
                        }
                    }
                } elseif ($type == 4) {
                    $url = $feed;
                    $feed = mysql_real_escape_string(rawurldecode($feed));
                    $keywords = array($feed);
                    if($news_id || !$smarty->is_cached('rss_list.html', md5($url))) { 
                        if(parseKeywordsFeed($keywords, $result)) {
                            $assign = 1;
                        } else {
                            $error = 1; 
                        }
                    }
                }
                if ($error) {
                    echo $__error['invalid_feed'];
                } else {
                    
                    if ($news_id) {
                        $query = "select * from news2 where id = '$news_id'";
                        $r = mysql_query($query) or die(mysql_error());
                        if ($row = mysql_fetch_assoc($r)) {
                            $news_url = preg_replace("/\/$/", "", trim($row["news_url"]));
                            foreach($result["entries"] as $key=>$item) {
                                $item_url = preg_replace("/\/$/", "", trim($item["link"]));
                                if ($news_url == $item_url) {
                                    $result["entries"][$key]["marked"] = 1;
                                    $result["entries"][$key]["news_id"] = $news_id;
                                    $marked_news = 1;
                                }
                            }
                        }
                    }
                    if ($assign) {
                        $smarty->assign('items', $result['entries']);
                        $smarty->assign('items_nr', $_REQUEST['items_nr']);	
                        if ($marked_news) {
                            $smarty->caching = 0;
                            $smarty->force_compile = 1;
                        }
                    }
                    $smarty->display('rss_list.html', md5($url));
                }
			} else {
                echo $__error['invalid_feed'];			
            }

			break;	
		case "get_feed_header":				
				$url = 'http://'.parseURL($_REQUEST['feed']);
				if(parseFeed($url, $result)){					
					if($__user_id!=-1){
						include_once('../inc/db_prop.inc.php');
						$http_get = get_headers('http://'.parseURL($result['head']['link']).'/favicon.ico');
						if(strtolower(trim($http_get[0]))!=strtolower('HTTP/1.1 404 Not Found'))	$favicon = 'http://'.parseURL($result['head']['link']).'/favicon.ico';
							else $favicon = '';						
						$feed_id = saveUserFeed($__user_id, $result['head']['title'], $url, $favicon, parseURL($result['head']['link']));
					}
					
					header('Content-type:text/html; charset=utf-8');
					echo "data['feedName'] = '".$result['head']['title']."';\ndata['feedUrl']='".$url."';\ndata['link']='".$result['head']['link']."';\ndata['feedId']=".$feed_id;
				}else echo  "";				
				
			break;	
		case "get_keywordsfeed_header":				
            if($__user_id!=-1){
                $title = isset($_REQUEST["title"]) ? $_REQUEST["title"] : "";
                $title = mysql_real_escape_string(trim($title));
                $keywords = isset($_REQUEST["keywords"]) ? $_REQUEST["keywords"] : "";
                /*
                $takw = explode(" ", $keywords);
                $akw = array();
                foreach($takw as $tkw) {
                    $kw = trim($tkw);
                    if (!empty($kw)) {
                        $akw[] = $kw;
                    }
                }
                $kws = mysql_real_escape_string(implode("||", $akw));
                */
                $kws = $keywords;
                if (empty($title)) $title = $kws;
                $favicon = "";
                $link = "";

                $feed_id = saveUserFeed($__user_id, $title, $kws, "", "", $type = 4);
                header('Content-type:text/html; charset=utf-8');
                echo "data['feedName'] = '".$title."';\ndata['feedKeywords']='".$kws."';\ndata['link']='';\ndata['feedId']=".$feed_id;
                
            } else echo "";				
            
            break;
        case "read_htmlbox": 
            $force = isset($_REQUEST["force"]) ? $_REQUEST["force"] : 0;
            $htmlbox_id = isset($_REQUEST["htmlbox_id"]) ? mysql_real_escape_string($_REQUEST["htmlbox_id"]) : 0;
            if ($htmlbox_id && $htmlbox = getHtmlBoxById($htmlbox_id)) {
                //egyelore csak siman kidobjuk a html-t
                header('Content-type:text/html; charset=utf-8');
                print $htmlbox["html"];


            }

            break;
	}					
	
?>
