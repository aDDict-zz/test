<?php

$charset = "utf-8"; //"utf-8"  //iso-8859-1
header("Content-Type: text/html; charset={$charset}");


#  $header = array(
#    "Host"              => "192.168.0.107",
#    "private_key"       => "5d9d92e300be43a6f47fbe28c41ad215",
#    "User-Agent"        => "Mozilla/5.0 Firefox/3.6.12",
#    "Accept"            => "text/html",  //,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
#    "Accept-Language"   => "en-us,en;q=0.5",
#    "Accept-Encoding"   => "deflate",
#    "Content-Type"      => "text/html; charset=iso-8859-1",
#    "Accept-Charset"    => "ISO-8859-1,utf-8;q=0.7,*;q=0.7"
#  );
#  
#  
#  $req = new HttpReq("tr.affiliate.hu","get_security_key.php",$header); // die( print_r( $req ) );  //?private_key=5d9d92e300be43a6f47fbe28c41ad215

##  $req = new HttpReq("amazon.com","/",$header);

#  $result = $req->request("get");  die( print_r( $result ) );


#class HttpReq{
#  
#  public function __construct($ip, $route, $header) {
#    $this->ip       = $ip;
#    $this->route    = $route;
#    $this->header   = $header;
#  }
#  
#  public function request($method) {
#    $result = "bad request";
#    switch($method) {
#      case "get":
#        $result = $this->get();
#      break;
#      case "post":
#        $result = $this->post();
#      break;
#    }
#    return $result;
#  }
#  
#  private function socketOpen() {
#    return fsockopen($this->ip, 80, $errno, $errstr);
#  }
#  
#  private function setHeader($method) {
#    $out  = "";
#    $crlf = "\r\n";
#    switch($method) {
#      case "get":
#        $out .= "GET {$this->route} HTTP/1.0\r\n";
#      break;
#      case "post":
#        $out .= "POST {$this->route} HTTP/1.0\r\n";
#      break;
#    }
#    foreach($this->header as $k => $v) {
#      $out .= "{$k}: {$v}\r\n";
#    }
#    return $out."Connection: Close\r\n\r\n";
#  }
#  
#  private function get() {
#    $header = $this->setHeader("get");
#    $fp     = $this->socketOpen();
#    $out    = "";
#    if($fp) { //echo $header;
#      stream_set_timeout($fp, 0, 1000);
#      fwrite($fp, $header);
#      while (!feof($fp)) { 
#          $out .= fgets($fp, 512);
#      }
#      fclose($fp);
#    }
#    return $out;
#  }
#  
#  private function post() {
#    $header = $this->setHeader("get");
#    $fp     = $this->socketOpen();
#    $out    = "";
#    if($fp) {
#      stream_set_timeout($fp, 0, 3000);
#      fwrite($fp, $header);
#      while (!feof($fp)) {
#          $out .= fgets($fp, 512);
#      }
#      fclose($fp);
#    }
#    return $out;
#  }
#  
#}

//15925

#$str = "";
#$str = mysql_escape_string($str);
#showResult("
#   select * from form_element where id = 15925;
# ");

#  $str = 'A következőkben mutatunk Önnek egy TV reklámot. Látta-e Ön ezt a HÉRA TV reklámot mostanában?<br /><br /><div style="with:100;text-align:center"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" 
# codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,16,0" 
# width="427" height="350" >
# <param name="movie" value="http://www.maxima.hu/extranet/video_a_kc_web.swf">
# <param name="quality" value="high">
# <param name="play" value="true">
# <param name="LOOP" value="false">
# <embed src="http://www.maxima.hu/extranet/video_a_kc_web.swf" width="427" height="350" play="true" loop="false" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" 
# type="application/x-shockwave-flash">
# </embed>
# </object></div>';
#  $str = mysql_escape_string($str);
# showResult("
#   update form_element set question = '{$str}' where id = 15939;
# ");

#  $str = 'A következőkben mutatunk Önnek egy TV reklámot. Látta-e Ön ezt a HÉRA TV reklámot mostanában?<br /><br /><div style="with:100;text-align:center"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" 
# codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,16,0" 
# width="427" height="350" >
# <param name="movie" value="http://www.maxima.hu/extranet/video_b_kc_web.swf">
# <param name="quality" value="high">
# <param name="play" value="true">
# <param name="LOOP" value="false">
# <embed src="http://www.maxima.hu/extranet/video_b_kc_web.swf" width="427" height="350" play="true" loop="false" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" 
# type="application/x-shockwave-flash">
# </embed>
# </object></div>';
#  $str = mysql_escape_string($str);
# showResult("
#   update form_element set question = '{$str}' where id = 15940;
# ");


#  $str = 'A következőkben mutatunk Önnek egy TV reklámot. Látta-e Ön ezt a HÉRA TV reklámot mostanában?<br /><br /><div style="with:100;text-align:center"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" 
# codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,16,0" 
# width="427" height="350" >
# <param name="movie" value="http://www.maxima.hu/extranet/video_c_kc_web.swf">
# <param name="quality" value="high">
# <param name="play" value="true">
# <param name="LOOP" value="false">
# <embed src="http://www.maxima.hu/extranet/video_c_kc_web.swf" width="427" height="350" play="true" loop="false" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" 
# type="application/x-shockwave-flash">
# </embed>
# </object></div>';
#  $str = mysql_escape_string($str);
# showResult("
#   update form_element set question = '{$str}' where id = 15944;
# ");

//showResult("select * from form_element where id = 15939");

//showResult("select * from user order by id desc limit 0,1");

#print_r(getNewGroupAttrs(1629));

#if(isset($_GET["id"]) && isset($_GET["table"]))
#  echo getSQLInsert($_GET["id"], $_GET["table"]);
#else
#  echo "need params";

//copyTableAttributes("user", "testTable");

#if(isset($_GET["group"]) && isset($_GET["form"]) && isset($_GET["newgroup"]))
#  cloneFormAndDemogsByGroup($_GET["group"], $_GET["form"], $_GET["newgroup"]);
#else
#  echo "need params";

//joinGroups2Members(1554, 59446); //59446  Tamas, 81241 sajat
//joinGroups2Members(1629, 59446); 59446

//copymembersgroups(59446,81241);
#echo "sfsdfds";
//echo getSQLInsert("15827", "form_element", "", "");

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
  $PDO        = getPDO::get();
  $PDO->query("insert into members(user_id,group_id,membership,create_date,modify_date,tstamp,trusted_affiliate,kedvenc) values({$user_id},{$group_id},'moderator','2011-09-02','2011-09-02',NOW(),'no','no')");
}

function showResult($sql){
  $PDO        = getPDO::get();
  $res = $PDO->query($sql)->fetchAll(PDO::FETCH_ASSOC); 
  
  if(preg_match("/insert into/", $sql))
    echo "lastinsertid:   " . $PDO->lastInsertId();
  else
    print_r($res);
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
      
      //TODO  a beirt form_element_dep recordot vissza kell irni a form_elementbe, tobb is van !!!! 
      
      foreach($res as $depIds){
        $PDO->query( getSQLInsert($depIds["id"], "form_element_dep", array("form_element_id" => $newFormElementId)) );
        $lastInsertId = $PDO->lastInsertId();
        $thisids = $PDO->query("select dependency from form_element where id = {$newFormElementId}")->fetchAll(PDO::FETCH_ASSOC);
        //if()
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
  
  // egyeb stuff
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
