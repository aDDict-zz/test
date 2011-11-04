<?php
require_once("params.php");


function copyTableDatas($from, $to) {
  $PDO          = getPDO::get();
  $PDO->query("set names 'utf8'");
  $res          = $PDO->query("
    select * from {$from} order by id asc;
  ")->fetchAll(PDO::FETCH_ASSOC);
  foreach($res as $r) {
     $insert = preg_replace("/{$from}/", $to, getSQLInsert($r["id"], $from));
     $PDO->query($insert);
    //echo $insert . "\n";
  }
}

function searchBadChars() {
  $pattern      = "http\:\/\/www\.kutatocentrum\.hu\/adatvedelem\.php";
  $replacement  = "http://www.kutatocentrum.hu/kutatocentrum/adatvedelem";
  $newPattern   = "http\:\/\/www\.kutatocentrum\.hu\/kutatocentrum\/adatvedelem";
  $PDO          = getPDO::get();
  $PDO->query("set names 'utf8'");
  $res          = $PDO->query("
    describe form;
  ")->fetchAll(PDO::FETCH_ASSOC);

  $fields = array();
  foreach($res as $field) {
    $thisField  = $field["Field"];
    $thisType   = $field["Type"];
      if(preg_match("/(varchar)/", $thisType) || preg_match("/(text)/", $thisType)) {
        //$res = $PDO->query("select {$thisField} from form ")
        $fields[] = $thisField;
      }
  }

  $res    = $PDO->query("
    select * from form;
  ")->fetchAll(PDO::FETCH_ASSOC);

  $formIds = array();
  foreach($res as $result) {
    foreach($fields as $field) {
      $res = $PDO->query("select {$field} as res from form where id = {$result["id"]}")->fetchAll(PDO::FETCH_ASSOC);
      if(preg_match("/$newPattern/",$res[0]['res'])){
        if(!in_array($result["id"],$formIds)) {
          $formIds[$result["id"]][$field] = $res[0]['res'];
          if(preg_match("/([^\s.]*\?[^\s.]*)/",$res[0]['res'], $match)) { ///"/([A-Za-z0-9._%+-]*\?[A-Za-z0-9._%+-]*)/"
            if(strlen($match[0]) > 2){
              $origString = $match[0];
              $newString = preg_replace("/\?/", "ő", $match[0]);
              $newVal = str_replace($origString, $newString, $res[0]['res']);
              $newVal = mysql_escape_string($newVal);
              echo "xxxxxxxxxxxxxxxxxxxxxX:   " . $newString . "\n<br />\n\n";
              $PDO->query("update form set {$field} = '{$newVal}' where id = {$result["id"]};");
            }
          }
        }
      }
    }
  }
  //echo count($formIds) . "<br />";
  //print_r($formIds);
}

function updateFormAdatvedelem() {
  $pattern      = "http\:\/\/www\.kutatocentrum\.hu\/adatvedelem\.php";
  $replacement  = "http://www.kutatocentrum.hu/kutatocentrum/adatvedelem";
  $PDO          = getPDO::get();
  $PDO->query("set names 'utf8'");
  $res          = $PDO->query("
    describe form;
  ")->fetchAll(PDO::FETCH_ASSOC);

  $fields = array();
  foreach($res as $field) {
    $thisField  = $field["Field"];
    $thisType   = $field["Type"];
      if(preg_match("/(varchar)/", $thisType) || preg_match("/(text)/", $thisType)) {
        //$res = $PDO->query("select {$thisField} from form ")
        $fields[] = $thisField;
      }
  }

  $res    = $PDO->query("
    select * from form;
  ")->fetchAll(PDO::FETCH_ASSOC);

  foreach($res as $result) {
    foreach($fields as $field) {
      $res = $PDO->query("select {$field} as res from form where id = {$result["id"]}")->fetchAll(PDO::FETCH_ASSOC);
      if(preg_match("/$pattern/",$res[0]['res'])){
        $newField = mysql_escape_string(preg_replace("/$pattern/", "$replacement", $res[0]['res']));
        $PDO->query("update form set {$field} = '{$newField}' where id = {$result["id"]}");
      }
    }
  }
}

/**
* @method setUpFieldLength
* @param {Number} group_id
* set up the varchar length length(255)
*/
function setUpFieldLength($group_id) {
  $PDO    = getPDO::get();
  $title  = "";
  $res    = $PDO->query("
    select title from groups where id = {$group_id};
  ")->fetchAll(PDO::FETCH_ASSOC);
  $title  = $res[0]["title"];

  $res    = $PDO->query("
    describe users_{$title};
  ")->fetchAll(PDO::FETCH_ASSOC);

  $thisField  = "";
  $thisType   = "";
  $thisLength = 0;
  foreach($res as $field) {
    $thisField  = $field["Field"];
    $thisType   = $field["Type"];
    if(preg_match("/(ui_)/", $thisField, $matches)) {
      if(preg_match("/(varchar)(.)([0-9]{1,10})/", $thisType, $matches)) {
        $thisLength = (int)$matches[3];
        $thisType   = $matches[1];

        if($thisType == "varchar") {
          if($thisLength < 255) {
            $q = "ALTER TABLE `users_{$title}` CHANGE `{$thisField}` `{$thisField}` VARCHAR(255)";
            $PDO->query($q);

#            echo $thisLength . "<br />\n";
#            echo $thisField . "<br />\n";
#            echo $thisType . "<br />\n";
#            echo $q . "<br />\n";
#            echo "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx<br />\n";
          }
        }
      }
    }
  }
}

/**
* @method cloneForm
* @param {Number} group
* @param {Number} form_id
* @param {Number} new_group_id
* it clones everything!
*/
function cloneForm($group, $form_id, $new_group_id) {

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
  echo "Done!";
}

/**
* @method getSQLInsert
* @param {Number}       id
* @param {String}       table
* @param {Array/String} arr
* @param {String}       query
* @return String
* it creates an sql insert by the table structure with the given parameters. if it hasnt unique id, the query must identify the record
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

function getNyeremenyjatekUsers() {
	$PDO 			= getPDO::get();
	$content 	= "";
	$res 			= $PDO->query("
		select ui_email,ui_friend_subscribed from users_permission where ui_friend_subscribed like '%,301-%';
	")->fetchAll(PDO::FETCH_ASSOC);
	foreach($res as $result) {
		$content .= "{$result["ui_email"]}\n";
	}
	getCsv($content, "nyeremenyjatekosok.csv");
}

function getCsv($content, $filename) {
	# header("Content-type: application/x-msdownload");
	header("Content-Disposition: attachment; filename={$filename}");
	header("Pragma: no-cache");
	header("Expires: 0");
	echo $content;
}

function copyDemogs($groupFrom, $groupTo) {
	$PDO 		= getPDO::get();
	$query 	= "";
	$res 		= $PDO->query("
		select demog_id from vip_demog where group_id = {$groupFrom};
	")->fetchAll(PDO::FETCH_ASSOC);
	foreach($res as $result) {
		$PDO->query("insert into vip_demog(demog_id, group_id, mandatory, dateadd, ask, changeable, deleted, tstamp) values({$result["demog_id"]}, {$groupTo}, 'no', '2011-09-26', 'yes', 'yes', 'no', '2011-09-26 08:07:04');");
	}
}

function update_question($id, $str){
  $PDO = getPDO::get();
  $res = $PDO->query("
    select question from form_element where id = {$id}
  ")->fetchAll(PDO::FETCH_ASSOC);
  $question = mysql_escape_string($res[0]["question"]);
  $str = $question . mysql_escape_string( $str );
  $PDO->query( "update form_element set question = '{$str}' where id = {$id}" );
}

function copymembersgroups($from,$to){
  $PDO = getPDO::get();
  $res = $PDO->query("
    select group_id from members where user_id = {$from}
  ")->fetchAll(PDO::FETCH_ASSOC);
  foreach($res as $group_id){
    joinGroups2Members($group_id["group_id"],$to);
  }
}

function joinGroups2Members($group_id, $user_id){
  $PDO = getPDO::get();
  $PDO->query("insert into members(user_id,group_id,membership,create_date,modify_date,tstamp,trusted_affiliate,kedvenc) values({$user_id},{$group_id},'moderator','2011-09-02','2011-09-02',NOW(),'no','no')");
}

function showResult($sql){
  $PDO = getPDO::get();
  try{
      $r = $PDO->query($sql);
    } catch(Exception $e) {
  }

  if(gettype($r) == "object")
    print_r($r->fetchAll(PDO::FETCH_ASSOC));
  else
    echo "need a valid sql";
}

function copyTableAttributes($tableFrom, $tableTo){
  $PDO = getPDO::get();
  $origColumns = array();
  $res = $PDO->query("describe {$tableTo}")->fetchAll(PDO::FETCH_ASSOC);
  foreach($res as $columns){
    $origColumns[] = $columns["Field"];
  }
  $res = $PDO->query("describe {$tableFrom}")->fetchAll(PDO::FETCH_ASSOC);
  foreach($res as $columns){
    if(!in_array($columns["Field"],$origColumns)){
      $PDO->query("alter table {$tableTo} add {$columns["Field"]} {$columns["Type"]}");
    }
  }
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

#  |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\
#  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\
#  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /
#  |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/


class getPDO{
  public function &get(){
    static $obj;
    $params = array(
        "host"  => $GLOBALS['params']['host'],
        "db"    => $GLOBALS['params']['db'],
        "user"  => $GLOBALS['params']['user'],
        "psw"   => $GLOBALS['params']['psw']
    );
    if (!is_object($obj)){
        $obj = new PDO("mysql:host={$params["host"]};dbname={$params["db"]}", $params["user"], $params["psw"]);
    }
    return $obj;
  }
}
