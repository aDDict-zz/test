<?php

//error_reporting(E_ALL);


#if(isset($_GET["group"]) && isset($_GET["form"]) && isset($_GET["newgroup"]))
#  cloneFormAndDemogsByGroup($_GET["group"], $_GET["form"], $_GET["newgroup"]);
#else
#  echo "need params";

#cloneFormAndDemogsByGroup(1487, 174, 1643);
#cloneFormAndDemogsByGroup(1488, 175, 1642);
#cloneFormAndDemogsByGroup(1489, 176, 1645);
#cloneFormAndDemogsByGroup(1557, 231, 1644);

/**
* @method cloneFormAndDemogsByGroup
* @param {Number} group
* @param {Number} form_id
* @param {Number} new_group_id
* it clones everything!
*/
function cloneFormAndDemogsByGroup($group, $form_id, $new_group_id) {
  // example query: group=1566&form=243&newgroup=1641
  $PDO = getPDO::get();

  $PDO->query("set names 'utf8'");

  // step 1., table form, creating a new element with a given group_id
  $PDO->query( getSQLInsert($form_id, "form", array("group_id" => $new_group_id)) );

  $lastInsertIdArr = $PDO->query("
    select id from form order by id desc limit 0,1
  ")->fetchAll(PDO::FETCH_ASSOC);
  $newFormId = $lastInsertIdArr[0]["id"];

  // step 2., table form_element, cloning the relevant records with the given form ids and setting up the show properties of the form elements
  $form_elements = $PDO->query("
    select id from form_element where form_id = {$form_id} order by id asc;
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

     $resDepPar = $PDO->query("
      select count(id) as c from form_element_parent_dep where form_element_id = {$formElementId["id"]};
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

      $dependencies = array();
      foreach($res as $depIds){
        $PDO->query( getSQLInsert($depIds["id"], "form_element_dep", array("form_element_id" => $newFormElementId)) );
        $dependencies[] = $PDO->lastInsertId();
      }
      $depOld         = $PDO->query("select dependency from form_element where id = {$formElementId["id"]}")->fetchAll(PDO::FETCH_ASSOC);
      $thisDependency = replaceOldDependencies($depOld[0]["dependency"],$dependencies);
      $PDO->query("update form_element set dependency = '{$thisDependency}' where id = {$newFormElementId}");
    }

    if($resDepPar[0]["c"] > 0){
      $res = $PDO->query("
        select * from form_element_parent_dep where form_element_id = {$formElementId["id"]};
      ")->fetchAll(PDO::FETCH_ASSOC);

      $dependencies = array();
      foreach($res as $depIds){
        $PDO->query( getSQLInsert($depIds["id"], "form_element_parent_dep", array("form_element_id" => $newFormElementId)) );
        $dependencies[] = $PDO->lastInsertId();
      }
      $depOld         = $PDO->query("select parent_dependency from form_element where id = {$formElementId["id"]}")->fetchAll(PDO::FETCH_ASSOC);
      $thisDependency = replaceOldDependencies($depOld[0]["parent_dependency"],$dependencies);  echo "parent_dependency:   " . $thisDependency . "<br />\n";
      $PDO->query("update form_element set parent_dependency = '{$thisDependency}' where id = {$newFormElementId}");
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

  // pages with their dependencies
  $pages = $PDO->query("
    select * from form_page where form_id = {$form_id} and group_id = {$group} order by page_id asc;
  ")->fetchAll(PDO::FETCH_ASSOC);

  if(count($pages) > 0) {
    foreach($pages as $page) {

      $PDO->query("
        insert
          into
            form_page
              (group_id,form_id,page_id,prev_button_text,next_button_text,boxes,prev_button_url,next_button_url,active,admeasure,dependency,parent_dependency,specvalid)
            values
              ({$new_group_id},{$newFormId},{$page['page_id']},'{$page['prev_button_text']}','{$page['next_button_text']}',{$page['boxes']},'{$page['prev_button_url']}','{$page['next_button_url']}','{$page['active']}','{$page['admeasure']}','{$page['dependency']}','{$page['parent_dependency']}','{$page['specvalid']}')
      ");

      $pagesDeps = $PDO->query("
        select count(*) as c from form_page_dep where form_id = {$form_id} and page_id = {$page['page_id']};
      ")->fetchAll(PDO::FETCH_ASSOC);

      if($pagesDeps[0]["c"] > 0) {
        $res = $PDO->query("
          select * from form_page_dep where form_id = {$form_id} and page_id = {$page['page_id']};
        ")->fetchAll(PDO::FETCH_ASSOC);

        $dependencies = array();

        foreach($res as $pageDeps){
          $PDO->query("
            insert
              into
                form_page_dep
                  (form_id,page_id,dependent_id,dependent_value,neg)
                values
                  ({$newFormId},{$pageDeps['page_id']},{$pageDeps['dependent_id']},'{$pageDeps['dependent_value']}','{$pageDeps['neg']}')
          ");
          $lastInsertId   = $PDO->lastInsertId();
          $dependencies[] = $lastInsertId;
        }
        $thisDependency = replaceOldDependencies($page["dependency"],$dependencies);  echo "form_page_dep:   " . $thisDependency . "<br />\n";
        $PDO->query("
          update form_page set dependency = '{$thisDependency}' where form_id = {$newFormId} and page_id = {$page['page_id']};
        ");
      }
    }
  }

  // form_page_box
  $pages = $PDO->query("
    select * from form_page_box where form_id = {$form_id} and group_id = {$group} order by box_id asc;
  ")->fetchAll(PDO::FETCH_ASSOC);
  if(count($pages) > 0){
    foreach($pages as $page) {
      $PDO->query(getSQLInsert("", "form_page_box", array("group_id" => $new_group_id, "form_id" => $newFormId, "box_id" => "{$page["box_id"]}"), "select * from form_page_box where form_id = {$form_id} and group_id = {$group} and box_id = '{$page["box_id"]}'"));
    }
  }
}

echo "Done!";

/**
* @method getSQLInsert
* @param {Number}       id
* @param {String}       table
* @param {Array/String} arr
* @param {String}       query
* @return String
* it creates an sql insert by the table structure with the given parameters
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
  $pattern    = "/([0-9]{1,15})/";
  $parts      = preg_split($pattern,$old);
  $dependency = "";
  for($i = 0; $i < count($parts); $i++) {
    if(isset($new[$i]))
      $dependency .= $parts[$i] .= $new[$i];
  }
  return $dependency;
}

/*
  |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\
  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\
  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /
  |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/
*/

class getPDO{
  public function &get(){
    static $obj;
    $params = array(
        "host"  => "localhost",
        "db"    => "maxima",
        "user"  => "roto",
        "psw"   => "barto2k6"
    );
    if (!is_object($obj)){
        $obj = new PDO("mysql:host={$params["host"]};dbname={$params["db"]}", $params["user"], $params["psw"]);
    }
    return $obj;
  }
}

#class getPDO{
#  public function &get(){
#    static $obj;
#    $params = array(
#        "host"  => "localhost",
#        "db"    => "maxima",
#        "user"  => "root",
#        "psw"   => "v"
#    );
#    if (!is_object($obj)){
#        $obj = new PDO("mysql:host={$params["host"]};dbname={$params["db"]}", $params["user"], $params["psw"]);
#    }
#    return $obj;
#  }
#}
