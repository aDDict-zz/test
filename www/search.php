<?php
	header("Content-type:text/html; charset=UTF-8");
	include_once('libs/Smarty.class.php');	
	include_once('inc/functions.php');
	include_once('inc/db_prop.inc.php');
	include_once('inc/common.php');
	include_once('inc/feed_functions.php');
	include_once('inc/page_functions.php');
	
	$connection = mysql_connect($host, $user, $psw) or die(mysql_error());
	$db = mysql_select_db($data_base, $connection) or die(mysql_error());
	mysql_query("Set names 'UTF8'") or die(mysql_error());
	
	$smarty = new Smarty;
    $smarty->assign("var", $_HI_var);
    $smarty->assign("pages", get_pages());
    $smarty->assign("PAGE_TITLE", $_HI_var->page_title_search);

	$query = "";
	
	$smarty->assign('categories', getFeedCategories());
	$smarty->assign('agencies', get_all_agencies());
	$smarty->assign('periods', get_all_search_periods());
	
	if(!isset($_GET["lead"]) || $_GET['lead']=="") $lead = 0;
		else	$lead = $_GET['lead'];
	$smarty->assign('lead', $lead);
	
	if(!isset($_GET["agency"]) || $_GET['agency']=="") $agency = "";
		else	$agency = $_GET['agency'];
				
	$smarty->assign('agency', $agency);
	
	if(!isset($_GET["period"]) || $_GET['period']=="") $period = 0;
		else  $period = $_GET['period'];
	$smarty->assign('period', $period);			
	
	if(!isset($_GET["category"]) || $_GET['category']=="") $category = "";
		else{
			$category = $_GET['category'];			
		}	
	$smarty->assign('selected_category', $category);			
				
	$smarty->assign("q", $_GET['q']);
	
	if((1 || !$_HI_var->test_site) && $_GET['q']!=""){																
        $debug = 0;
        if (!empty($_GET["debug"]) && $_GET["debug"] == "apro") {
            $debug = 1;
            error_reporting(2047);
            ini_set("display_errors", true);
        }
		include_once '/home/vvv/sites/hirekhu/www/inc/search.config.php';		  											
		
		$max_page_nr=10;
		
		if(!empty($_GET['page']))	$ppage= $_GET['page'];
			else $ppage= 1;		
		if ($ppage > $max_page_nr){
			$p_start=floor(($ppage-1) / $max_page_nr);
			$p_start=$p_start*$max_page_nr+1;
		}else
			$p_start=1; 			
		if(!empty($_GET['plimit']))		 $plimit = $_GET['plimit'];
			else $plimit = 20;  
		
		$q = explode(' ', $_GET['q']);
		
		$query_title = '';
		$query_lead = '';
			
		
		if(count($q)>1){			
			$l = 0;
			for($i=0;$i<count($q);$i++){
				if(trim($q[$i])!=''){
					if(!in_array($q[$i], $banned_words)){
						if($l==0){
							$query_title = 'news_title:'.$q[$i];
							if($lead!=0) $query_lead = 'news_lead:'.$q[$i];
						}else{
							$query_title .= ' AND news_title:'.$q[$i]; 
							if($lead!=0) $query_lead .= ' AND news_lead:'.$q[$i];
						} 
						$l++;
					}	
				}
				
			}
		}else{ 
			if(!in_array($q[0], $banned_words)){				
				$query_title = 'news_title:'.$q[0];
				if($lead!=0) $query_lead = 'news_lead:'.$q[0];
			}			
		}	
		
		$query = '('.$query_title.')';		
		if($lead!=0) $query .= ' OR ('.$query_lead.')';
		if($agency!='')	$query .= " AND agency_id:".$agency;			
		if($category!=''){
			if($query!='')	$query = "(".$query.") AND cat_id:".$category;
				else $query = "cat_id:".$category;

		}  
        if ($debug) {
    		echo $query;
        } 
		//$results = search_lucene($total_results, $lib_path, $xml_path, $index_base_path, $query, (($ppage-1)*$plimit), $ppage*$plimit, $period);
		
#		if($_COOKIE["Hirek"] == "5931:robthot@gmail.com") {
#		  die( "asd" );
#		}
		
        if ($debug) { 
    		echo "search_lucene executed";
        }
		
		$total_pages = $total_results;	
			
		$url = "q=".$_GET['q']."&lead=".$lead."&agency=".$agency."&period=".$period."&category=".$category."&total=".$total_pages."&p=Keres";				
		
		$smarty->assign('url', $url);
		$smarty->assign('total_pages', $total_pages);
		
		__goto($p_start, $plimit, $max_page_nr, $ppage, $total_pages);
		
		$smarty->assign('news', $results);
		
	}
    $smarty->assign("page", "search");


    $query = "Select cat_id, cat_name, cat_rss, cat_html, cat_type, cat_favicon From fixed_categories Where cat_id = '$_HI_var->fixed_category_search'";
    $result = mysql_query($query) or die(mysql_error());
    while($row = mysql_fetch_assoc($result)){
        $links = array();
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
        $search_category = array(				
            'cat_name'=>$row['cat_name'],
            'cat_rss'=>$row['cat_rss'],
            'cat_html'=>stripslashes($row['cat_html']),
            'cat_favicon'=>$row['cat_favicon'],
            'cat_type'=>$row['cat_type'],
            'cat_links'=>$links
        );

        $smarty->assign("search_category", $search_category);

        
    }
    $smarty->assign("searchpage",1);

	$smarty->display('search.html');	
?>
