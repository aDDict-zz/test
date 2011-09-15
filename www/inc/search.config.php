<?php 
//include("/usr/share/php/java/Java.inc");
  $banned_words = array('es', 'és', 'hogy', 'az', 'azt', 'a');
  $xml_path = '/home/vvv/sites/hirekhu/www/lucene/parse_news.xml';
  $lib_path = '/home/vvv/sites/hirekhu/www/lucene/';
  $index_base_path = '/home/vvv/sites/hirekhu/www/lucene/indexes/'; 
	
	
#function search_lucene(&$total_results, $lib_path, $xml_path, $index_base_path, $q, $limit, $offset, $period) {
#  
##          echo $total_results . "<br />";
##        echo $lib_path . "<br />";
##        echo $xml_path, . "<br />";
##        echo $index_base_path . "<br />";
##        echo $q . "<br />";
##        
##        echo $limit . "<br />";
##        
##        echo $offset . "<br />";
##        echo $period . "<br />";
##  
##  die( "" );
#  
#}
	
	
  function search_lucene(&$total_results, $lib_path, $xml_path, $index_base_path, $q, $limit, $offset, $period){
  
  
#        echo $total_results . "<br />";
#        echo $lib_path . "<br />";
#        echo $xml_path, . "<br />";
#        echo $index_base_path . "<br />";
#        echo $q . "<br />";
#        
#        echo $limit . "<br />";
#        
#        echo $offset . "<br />";
#        echo $period . "<br />";
#  
#  die(  );
  
  
        global $_HI_var;
        $debug = 0;
        if (!empty($_GET["debug"]) && $_GET["debug"] == "apro") {
            $debug = 1;
            error_reporting(2047);
            ini_set("display_errors", true);
        }
        if($q){ 
           if (!$_HI_var->test_site) { 
#                if ($debug) print "java_set_library_path: $lib_path<br>";
#                java_set_library_path($lib_path);
#                if ($debug) print "ok<br>";
#                if ($debug) print "create java object: Search<br>";
#                    $obj = new Java("Search");
#                if ($debug) print "ok<br>";
#                            
#                $obj->setXML($xml_path);
            }

            $dirs = array();
            $dirs[1] = $index_base_path.'arch_'. date('Y_n');
            $dirs[7] = $index_base_path.'arch_'. date('Y_n', mktime(0, 0, 0, date("m"), date("d")-6, date("Y")));
            $dirs[30] = $index_base_path.'arch_'. date('Y_n', mktime(0, 0, 0, date("m"), date("d")-29, date("Y")));
            $dirs[45] = $index_base_path.'arch_'. date('Y_n', mktime(0, 0, 0, date("m"), date("d")-44, date("Y")));
            $dirs[60] = $index_base_path.'arch_'. date('Y_n', mktime(0, 0, 0, date("m"), date("d")-59, date("Y")));
            $dirs[75] = $index_base_path.'arch_'. date('Y_n', mktime(0, 0, 0, date("m"), date("d")-74, date("Y")));
            $dirs[90] = $index_base_path.'arch_'. date('Y_n', mktime(0, 0, 0, date("m"), date("d")-89, date("Y")));
	     die( print_r( $dirs ) );
          $indexdirs = array();
          $indexdirs[] = $dirs[1];
        switch($period){
          case 0: 
            $q .= " AND dadd:" . date("Y-m-d") . "*";
            break;
          case 1:
            $q .= " AND dadd:[" . date('Y-m-d', mktime(0, 0, 0, date("m"), date("d")-6, date("Y"))) . " TO " . date("Y-m-d") . "]";
            if (!in_array($dirs[7], $indexdirs)) {
                $indexdirs[] = $dirs[7];
            }
            break;
          case 2:				    
            $q .= " AND dadd:[" . date('Y-m-d', mktime(0, 0, 0, date("m"), date("d")-29, date("Y"))) . " TO " . date("Y-m-d") . "]";
            if (!in_array($dirs[7], $indexdirs)) {
                $indexdirs[] = $dirs[7];
            }
            if (!in_array($dirs[30], $indexdirs)) {
                $indexdirs[] = $dirs[30];
            }
            break;
          case 3:            
            $q .= " AND dadd:[" . date('Y-m-d', mktime(0, 0, 0, date("m"), date("d")-90, date("Y"))) . " TO " . date("Y-m-d") . "]";
            if (!in_array($dirs[7], $indexdirs)) {
                $indexdirs[] = $dirs[7];
            }
            if (!in_array($dirs[30], $indexdirs)) {
                $indexdirs[] = $dirs[30];
            }
            if (!in_array($dirs[45], $indexdirs)) {
                $indexdirs[] = $dirs[45];
            }
            if (!in_array($dirs[60], $indexdirs)) {
                $indexdirs[] = $dirs[60];
            }
            if (!in_array($dirs[75], $indexdirs)) {
                $indexdirs[] = $dirs[75];
            }
            if (!in_array($dirs[90], $indexdirs)) {
                $indexdirs[] = $dirs[90];
            }
            break;            
				  
        }
	$result = array();
        foreach ($indexdirs as $d) {
            if ($_HI_var->test_site) {
                print "$d<br>";
            } else {
                $obj->setIndexDirectory($d);
            }
            
		if ($debug) print $q; 	
		$obj->initSearch();
#	print "$q, $limit, $offset, $d<br>";	
		$temp = $obj->doSearch($q, $limit, $offset); 

		$e = java_last_exception_get();			
		
		$m = "";	
		try {
			$m = $e->toString();
		
		} catch (Exception $e) {}
		if (!empty($m)) {	  
		   echo $m;
		} else if($temp!=''){														
			$dom = DOMDocument::loadXML(utf8_encode(html_entity_decode((string)$temp)));
			$total = $dom->getElementsByTagName("total");		
			$total_results = $total->item(0)->getAttribute("t");				
			if($total_results > 0){														
				$items = $dom->getElementsByTagName("item");					
				for($i=0;;$i++){					
						if($items->item($i)=="") break;					
						$item = $items->item($i)->childNodes;					
						for($j=0;;$j++){
								if($item->item($j)=="") break;
								if($item->item($j)->nodeType==3 && $item->item($j-1)->nodeValue!=""){															
									$result[$i][$item->item($j-1)->nodeName] = utf8_decode($item->item($j-1)->nodeValue);
								}							
						}					
				}						
			}											
		} 
		
		java_last_exception_get();	
		java_last_exception_clear();			
	    }
	return $result;									
        }
  }	
?>
