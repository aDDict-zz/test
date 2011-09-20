#!/usr/bin/php
<?

fix_users_kuttnshera();


function fix_users_kuttnshera() {
  $PDO  = getPDO::get();
  
  $exceptions = array();
  $res  = $PDO->query("
    select id from demog_enumvals where enum_option = 'egyikre sem illik'
  ")->fetchAll(PDO::FETCH_ASSOC);
  
  foreach($res as $result) {
    $exceptions[] = $result["id"];
  };
  
  $exceptions[] = 39;
  
  $res  = $PDO->query("
    select count(*) as counter from users_kuttnshera
  ")->fetchAll(PDO::FETCH_ASSOC);
  
  $limit = $res[0]["counter"];
  
  for($i = 0; $i < $limit; $i++) {
  
    $res = null;
    $res  = $PDO->query("
      select
        id,
        
        ui_cid,
        
        ui_kuttnshera_p3,
        
        ui_kuttnshera_p8_1 as a1,
        ui_kuttnshera_p8_2 as a2,
        ui_kuttnshera_p8_3 as a3,
        ui_kuttnshera_p8_4 as a4,
        ui_kuttnshera_p8_5 as a5,
        ui_kuttnshera_p8_6 as a6,
        ui_kuttnshera_p8_7 as a7,
        ui_kuttnshera_p8_8 as a8,
        ui_kuttnshera_p8_9 as a9,
        ui_kuttnshera_p8_10 as a10,
        
        ui_kuttnshera_p9_1 as a11,
        ui_kuttnshera_p9_2 as a12,
        ui_kuttnshera_p9_3 as a13,
        ui_kuttnshera_p9_4 as a14,
        ui_kuttnshera_p9_5 as a15,
        ui_kuttnshera_p9_6 as a16,
        ui_kuttnshera_p9_7 as a17,
        ui_kuttnshera_p9_8 as a18,
        ui_kuttnshera_p9_9 as a19,
        ui_kuttnshera_p9_10 as a20,
        ui_kuttnshera_p9_11 as a21,
        ui_kuttnshera_p9_12 as a22,
        ui_kuttnshera_p9_13 as a23
        
      from
        users_kuttnshera
      limit
      
        {$i},1
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    $kuttnshera_p3      = array();
    $kuttnshera_p3_ids  = array();
    $arr =  explode(",",$res[0]["ui_kuttnshera_p3"]);
    foreach($arr as $el) {
      $thisElement = (int)trim($el);
      if($thisElement != 0 && $thisElement != 39) {
        $r =  $PDO->query("select id, enum_option from demog_enumvals where id = {$thisElement} limit 0,1")->fetchAll(PDO::FETCH_ASSOC);
        $kuttnshera_p3[] = $r[0]["enum_option"];
        $kuttnshera_p3_ids[] = $r[0]["id"];
      }  
    }
    
    
    for($j = 1; $j <= 23; $j++) {
      $newIdCollector = array();
      $idCollector    = array();
      $wordCollector  = array();
      $index = "a{$j}";
      $arr = explode(",",$res[0][$index]);
      foreach($arr as $el) {
        $element = (int)trim($el);
        if(!in_Array($element,$exceptions)) {
          if($element != "") {
            $r      =  $PDO->query("select id, enum_option from demog_enumvals where id = {$element} limit 0,1")->fetchAll(PDO::FETCH_ASSOC);
            $thisEl = $r[0]["enum_option"];
            
            if(!in_array( $thisEl,$kuttnshera_p3 )) {
              if(!in_array($thisEl,$wordCollector)) {
                $re                 =  $PDO->query("select id from demog_enumvals where enum_option = '{$thisEl}' order by id asc limit 0,1")->fetchAll(PDO::FETCH_ASSOC);
                $wordCollector[]    = $thisEl;
                $idCollector[]      = $r[0]["id"];
                $newIdCollector[]   = $re[0]["id"];
              }
            }
          }
        }  
      }
    }
    
    if(count($wordCollector) > 0) {
      $newp3 = implode(",",array_merge( $kuttnshera_p3_ids, $newIdCollector ));
      $PDO->query("update users_kuttnshera set ui_kuttnshera_p3 = '{$newp3}' where id = {$res[0]["id"]}");
      echo "id: {$res[0]["id"]}, values: {$newp3}\n";
    }
    
  }
}




function getSQLInsert($id, $table, $arr = ""){

  $PDO        = getPDO::get();
  $thisRes    = $PDO->query("
    select * from {$table} where id={$id}
  ")->fetchAll(PDO::FETCH_ASSOC);
  
  $keys = array(); $values = array();
  
  if(gettype($arr) == "array"){
    $thisArr = array_keys($arr);
    foreach($thisRes[0] as $k => $v){
			if(in_array($k,$thisArr)){
				$keys[]   = mysql_escape_string($k);		
				$values[] = mysql_escape_string($arr[$k]);
      } else if($k != "id"){
        $keys[]   = mysql_escape_string($k);
        $values[] = mysql_escape_string($v);
      }
    }
  } else if(gettype($arr) == "string")
    foreach($thisRes[0] as $k => $v){
      if($k != "id"){
        $keys[]   = mysql_escape_string($k);
        $values[] = mysql_escape_string($v);
      }
    }
  
  $sql          = "";
  $columnlist   = "";
  $columnVals   = "";
  
  for($i = 0; $i < count($keys); $i++){
    if($i < count($keys) - 1){
      $columnlist   .= "{$keys[$i]},";
      $columnVals   .= "'{$values[$i]}',";
    } else {
      $columnlist   .= "{$keys[$i]}";
      $columnVals   .= "'{$values[$i]}'";
    }
  }
  
  return "insert into {$table}({$columnlist}) values({$columnVals});";
}


function showResult($sql){
  $PDO        = getPDO::get();
	
	if($sql != ""){ 
		$res = $PDO->query($sql)->fetchAll(PDO::FETCH_ASSOC); print_r($res);
	} else {
		echo "need a valid sql";
	}
}

/*
  |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\
  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\
  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /
  |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/
*/

#class getPDO{
#  public function &get(){
#    static $obj;
#    $params = array(
#        "host"  => "localhost",
#        "db"    => "maxima", //"maxima_public",
#        "user"  => "roto",
#        "psw"   => "barto2k6"
#    );
#    if (!is_object($obj)){
#        $obj = new PDO("mysql:host={$params["host"]};dbname={$params["db"]}", $params["user"], $params["psw"]);
#    }
#    return $obj;
#  }
#}

class getPDO{
  public function &get(){
    static $obj;
    $params = array(
        "host"  => "localhost",
        "db"    => "maxima", //"maxima_public",
        "user"  => "root",
        "psw"   => "v"
    );
    if (!is_object($obj)){
        $obj = new PDO("mysql:host={$params["host"]};dbname={$params["db"]}", $params["user"], $params["psw"]);
    }
    return $obj;
  }
}
