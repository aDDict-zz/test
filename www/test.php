<?php
setuserstokuponvilag();


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

?>
