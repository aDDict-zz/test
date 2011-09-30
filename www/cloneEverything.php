<?php

if(isset($_GET["group"]) && isset($_GET["form"]) && isset($_GET["newgroup"]))
  cloneFormAndDemogsByGroup($_GET["group"], $_GET["form"], $_GET["newgroup"]);
else
  echo "need params";


/**
* @method cloneFormAndDemogsByGroup
* @param {Number} group
* @param {Number} form_id
* @param {Number} new_group_id
* it clones everything!
*/
function cloneFormAndDemogsByGroup($group, $form_id, $new_group_id){
  // example query: group=1554&form=230&newgroup=1629
  $PDO = getPDO::get();
  
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
      
      //TODO  a beirt form_element_dep recordot vissza kell irni a form_elementbe, tobb is van !!!! 
      
      foreach($res as $depIds){
        $PDO->query( getSQLInsert($depIds["id"], "form_element_dep", array("form_element_id" => $newFormElementId)) );
        $lastInsertId = $PDO->lastInsertId();
        $thisids = $PDO->query("select dependency from form_element where id = {$newFormElementId}")->fetchAll(PDO::FETCH_ASSOC);
        // just simply write to the dependency
        $thisDependency = "{$thisids[0]["dependency"]}|{$lastInsertId}";
        $PDO->query("update form_element set dependency = '{$thisDependency}' where id = {$newFormElementId}");
      }
      $depOld = $PDO->query("select dependency from form_element where id = {$formElementId["id"]}")->fetchAll(PDO::FETCH_ASSOC);
      $depNew = $PDO->query("select dependency from form_element where id = {$newFormElementId}")->fetchAll(PDO::FETCH_ASSOC);
      replaceOldDependencies($depOld[0]["dependency"],$depNew[0]["dependency"]);
    }
  }
  
  // step 3., table vip_demog, cloning the relevant records with the given group_id
  $vip_demogs = $PDO->query("
    select id from vip_demog where group_id = {$group};
  ")->fetchAll(PDO::FETCH_ASSOC);
  
  foreach($vip_demogs as $vipDemogId){
    $PDO->query( getSQLInsert($vipDemogId["id"], "vip_demog", array("group_id" => $new_group_id)) );
  }
  
  // another stuff
  // form_banner
  $banners = $PDO->query("
    select id from form_banner where form_id = {$form_id};
  ")->fetchAll(PDO::FETCH_ASSOC);
  if(count($banners) > 0){
    foreach($banners as $banner){
      $PDO->query( getSQLInsert($banner["id"], "form_banner", array("form_id" => $newFormId)) );
    }
  }
  // form_css
  $css = $PDO->query("
    select id from form_css where form_id = {$form_id};
  ")->fetchAll(PDO::FETCH_ASSOC);
  if(count($css) > 0){
    foreach($css as $cs){
      $PDO->query( getSQLInsert($cs["id"], "form_css", array("form_id" => $newFormId)) );
    }
  }
  // form_email
  $emails = $PDO->query("
    select id from form_email where form_id = {$form_id};
  ")->fetchAll(PDO::FETCH_ASSOC);
  if(count($emails) > 0){
    foreach($emails as $email){
      $PDO->query( getSQLInsert($email["id"], "form_email", array("form_id" => $newFormId)) );
    }
  }
  
  // form_page
  $pages = $PDO->query("
    select * from form_page where form_id = {$form_id} and group_id = {$group};
  ")->fetchAll(PDO::FETCH_ASSOC);
  
  if(count($pages) > 0){
    foreach($pages as $page){
      $PDO->query(getSQLInsert("", "form_page", array("group_id" => $new_group_id, "form_id" => $newFormId), "select * from form_page where form_id = {$form_id} and group_id = {$group} and page_id = {$page["page_id"]}"));
    }
  }
  
  // form_page_box
  $pages = $PDO->query("
    select * from form_page_box where form_id = {$form_id} and group_id = {$group};
  ")->fetchAll(PDO::FETCH_ASSOC);
  
  if(count($pages) > 0){
    foreach($pages as $page){
      $PDO->query(getSQLInsert("", "form_page_box", array("group_id" => $new_group_id, "form_id" => $newFormId), "select * from form_page_box where form_id = {$form_id} and group_id = {$group} and page_id = {$page["page_id"]}"));
    }
  }
}

/**
* @method getSQLInsert
* @param {Number}       id
* @param {String}       table
* @param {Array/String} arr
* @param {String}       query
* @return String
* it creates an sql query by a table structure with the given parameters
*/
function getSQLInsert($id, $table, $arr = "", $query = ""){

  $PDO        = getPDO::get();
  $query == "" ? $query = "select * from {$table} where id={$id}" : "";
  $thisRes    = $PDO->query($query)->fetchAll(PDO::FETCH_ASSOC); 
  
  $keys       = array();
  $values     = array();
  
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

function replaceOldDependencies($old, $new) {
  echo "$old : $new\n";
}

/*
  |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\
  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\
  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /
  |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/
*/

/**
* returns a singleton pdo instance
*/
class getPDO{
  public function &get(){
    static $obj;
    $params = array(
        "host"  => "localhost",
        "db"    => "maxima",
        "user"  => "root",
        "psw"   => "v"
    );
    if (!is_object($obj)){
        $obj = new PDO("mysql:host={$params["host"]};dbname={$params["db"]}", $params["user"], $params["psw"]);
    }
    return $obj;
  }
}
