<?php

$charset = "utf-8"; //"utf-8"  //iso-8859-1
header("Content-Type: text/html; charset={$charset}");

#print_r(getNewGroupAttrs(1629));

#if(isset($_GET["id"]) && isset($_GET["table"]))
#  echo getSQLInsert($_GET["id"], $_GET["table"]);
#else
#  echo "need params";


#if(isset($_GET["group"]) && isset($_GET["form"]) && isset($_GET["newgroup"]))
#  cloneFormAndDemogsByGroup($_GET["group"], $_GET["form"], $_GET["newgroup"]);
#else
#  echo "need params";

//joinGroups2Members(1554, 59446); //59446  Tamas, 81241 sajat
//joinGroups2Members(1629, 59446);

function joinGroups2Members($group_id, $user_id){
  $PDO        = getPDO::get();
  $PDO->query("insert into members(user_id,group_id,membership,create_date,modify_date,tstamp,trusted_affiliate,kedvenc) values({$user_id},{$group_id},'moderator','2011-09-02','2011-09-02',NOW(),'no','no')");
}

//showResult("select id from form where group_id = 1629");


function showResult($sql){
  $PDO        = getPDO::get();
  $res = $PDO->query($sql)->fetchAll(PDO::FETCH_ASSOC); print_r($res);
}


addFormElementDeps(305,230);

//305
function addFormElementDeps($toID, $frID){
	$PDO  = getPDO::get();
  $res  = $PDO->query("
    select fe.id, fed.dependent_id, fed.id as fedId from form_element fe
    join form_element_dep fed on fe.id = fed.form_element_id
    where fe.form_id = {$frID} 
  ")->fetchAll(PDO::FETCH_ASSOC);
  
  foreach($res as $result){
    $thisRes  = $PDO->query("select id from form_element where demog_id = {$result["dependent_id"]} and form_id = {$toID}")->fetchAll(PDO::FETCH_ASSOC);
    $PDO->query( getSQLInsert($result["fedId"], "form_element_dep", array("form_element_id" => $thisRes[0]["id"])) );
  }
}


// example query: group=1554&form=230&newgroup=1629
function cloneFormAndDemogsByGroup($group, $form_id, $new_group_id){
  $PDO        = getPDO::get();
  
  // step 1., table form, creating a new element with a given group_id
  $PDO->query( getSQLInsert($form_id, "form", array("group_id" => $new_group_id)) );
  
  $lastInsertIdArr = $PDO->query("
    select id from form order by id desc limit 0,1
  ")->fetchAll(PDO::FETCH_ASSOC);
  $newFormId = $lastInsertIdArr[0]["id"];
  
  // step 2., table form_element, cloning the relevant records with the given form ids and setting up the show properties of the form elements
  $form_elements = $PDO->query("
    select id from form_element where form_id = {$form_id};
  ")->fetchAll(PDO::FETCH_ASSOC);
  
  foreach($form_elements as $formElementId){
    $PDO->query( getSQLInsert($formElementId["id"], "form_element", array("form_id" => $newFormId)) );
    
    $lastInsertIdArr = $PDO->query("
      select id from form_element order by id desc limit 0,1
    ")->fetchAll(PDO::FETCH_ASSOC);
    $newFormElementId = $lastInsertIdArr[0]["id"];
    
    $resEnum = $PDO->query("
      select count(id) as c from form_element_enumvals where form_element_id = {$formElementId["id"]};
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    $resDep = $PDO->query("
      select count(id) as c from form_element_dep where form_element_id = {$formElementId["id"]};
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    if($resEnum[0]["c"] > 0){
      $res = $PDO->query("
        select * from form_element_enumvals where form_element_id = {$formElementId["id"]};
      ")->fetchAll(PDO::FETCH_ASSOC);
      
      
      
      foreach($res as $enumVals){
        $PDO->query( getSQLInsert($enumVals["id"], "form_element_enumvals", array("form_element_id" => $newFormElementId)) );
      }
    }
    
    if($resDep[0]["c"] > 0){
      $res = $PDO->query("
        select * from form_element_dep where form_element_id = {$formElementId["id"]};
      ")->fetchAll(PDO::FETCH_ASSOC);
      
      
      
      foreach($res as $depIds){
        $PDO->query( getSQLInsert($depIds["id"], "form_element_dep", array("form_element_id" => $newFormElementId)) );
      }
    }
  }
  
  // step 3., table vip_demog, cloning the relevant records with the given group_id
  $vip_demogs = $PDO->query("
    select id from vip_demog where group_id = {$group};
  ")->fetchAll(PDO::FETCH_ASSOC);
  
  foreach($vip_demogs as $vipDemogId){
    $PDO->query( getSQLInsert($vipDemogId["id"], "vip_demog", array("group_id" => $new_group_id)) );
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
      if($k == $thisArr[0]){
        $keys[]   = mysql_escape_string($thisArr[0]);
        $values[] = mysql_escape_string($arr[$thisArr[0]]);
      }
      else if($k != "id"){
        $keys[]   = mysql_escape_string($k);
        $values[] = mysql_escape_string($v);
      }
    }
  }
  else if(gettype($arr) == "string")
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


function getNewGroupAttrs($group_id){
  $PDO        = getPDO::get();
  return $PDO->query("
    select * from groups where id={$group_id}
  ")->fetchAll(PDO::FETCH_ASSOC);
}

function updateUserTime($id){
	$PDO        = getPDO::get();
	$PDO->query("update user set password_modify = NOW() where id = {$id}");
}


function setuserstokuponvilag(){
  $PDO        = getPDO::get();
  $group_id   = 1627;
  $thisRes    = $PDO->query("
    select demog_enumvals_id,title,default_aff from form_element_enumvals where form_element_id='15369'
  ");

  $res = $thisRes->fetchAll(PDO::FETCH_ASSOC);

  $cache = "";
  foreach($res as $key => $val){
    if($cache != $val["default_aff"]){
      $r = $PDO->query("select count(*) as counter from members where user_id = '{$val["default_aff"]}' and group_id = '{$group_id}'")->fetchAll(PDO::FETCH_ASSOC); 
      if($r[0]["counter"] == 0){
        $sql = "
                insert
                  into
                    members
                  (user_id,group_id,membership,create_date,modify_date,affiliate_members,tstamp,trusted_affiliate,kedvenc)
                values
                  ({$val["default_aff"]},{$group_id},'affiliate','2011-08-30','2011-08-30',0,'2011-08-30 16:57:44','yes','no')
                ";
        $PDO->query($sql);
      }
      $cache = $val["default_aff"];
    }  
  }
}

/*
  |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\
  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\
  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /
  |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/
*/

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



?>
