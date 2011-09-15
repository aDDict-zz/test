<?php
	class Tabs{
		private $memcache;
		private $dbCon;
		
		
		public function __construct($dbCon){
			include_once('memcached.config.php');
			$this->memcache = new Memcache;			
			$this->dbCon = $dbCon;
			if (! @$this->memcache->pconnect($memcached_host, $memcached_port))
				$this->memcache=false;							

			
		}
		
		public function clearAllItems(){
			if ($this->memcache) {
				if($this->memcache->flush());
			}
		}
		
		public function getStats(){
			if (!$this->memcache) return false;
			echo '<pre>';
			print_r($this->memcache->getStats());
			echo '</pre>';
		}
		
		private function sortTabs($tabs){
			for($i=0;$i<count($tabs);$i++){
				for($j=$i+1;$j<count($tabs);$j++){
					if($tabs[$i]['pos']>$tabs[$j]['pos']){
						$temp = $tabs[$i];
						$tabs[$i] = $tabs[$j];
						$tabs[$j] = $temp;
					}
				}
			}			
			return $tabs;			
		}
		
		public function getTabs($userId, &$tabs){			
if (!empty($_REQUEST["debug"])) print "memcache: " . ($this->memcache ? "true" : "false");
			if ($this->memcache) {
				$tabs = $this->memcache->get($userId);
			} else {
				$tabs = false;
			}
			

			if(!$tabs){ 
				//result not found in memcache return false;
				//get results from database
				$query = "Select user_pages From users2 Where user_id=".$userId;
				$result = mysql_query($query, $this->dbCon) or die(mysql_error());
				$result = mysql_fetch_assoc($result);
				$tabs = unserialize($result['user_pages']);				
				if ($this->memcache) $this->memcache->add($userId, $tabs, false, 30);				
				return false;
			}else{
				//get result from memcache return true;
				return true;
			}
		}
		
		public function updateTabs($userId, $values){			
			if ($this->memcache) {
				$this->memcache->replace($userId, $values, false, 30);
			}
			$query = "Update users2 Set user_pages='".serialize($values)."' Where user_id=".$userId;
			mysql_query($query)	or die(mysql_error());
		}
		
		public function addNewTab($userID, $pageID){
			$query = "Insert Into user_pages2 (user_id, page_id) Values (".$userID.", ".$pageID.")";					
			mysql_query($query) or die(mysql_error());
		}
		
		public function deleteTab($userID, $pageID){
			include_once '../inc/feed_functions.php';
			include_once '../inc/page_functions.php';
			
			$result = getUserPage($userID, $pageID);
			$box = $result['page_structure'];
			$feedIDs = '';
			if(is_array($box)==1){
				foreach($box as $key=>$values){
					if(trim($box[$key]['id'])!='')$feedIDs .= $box[$key]['id'].', ';
				}
				$feedIDs = substr($feedIDs, 0, strlen($feedIDs)-2);
				removeUserFeeds($userID, $feedIDs);
			}
			$query = "Delete From user_pages2 Where user_id=$userID And page_id=$pageID Limit 1";						
			mysql_query($query) or die(mysql_error());
		}
		
		public function deleteTabs($userId){
			if ($this->memcache) {
				$this->memcache->delete($userId, 0);
			}
		}
	}
?>
