<?php

//showResult("show tables");  //ct_news   new_arch



getTables();

function getTables() {
  $PDO = getPDO::get();
  $res = $PDO->query("show tables")->fetchAll(PDO::FETCH_ASSOC);
  foreach($res as $result) { //print_r($result);
    echo "{$result["Tables_in_hirek_old"]}<br />\n";
  }
}

function showResult($sql){
  $PDO        = getPDO::get();
  $res = $PDO->query($sql)->fetchAll(PDO::FETCH_ASSOC); 
  
  if(preg_match("/insert into/", $sql))
    echo "lastinsertid:   " . $PDO->lastInsertId();
  else
    print_r($res);
}

class getPDO{
  public function &get(){
    static $obj;
    $params = array(
        "host"  => "192.168.1.102",
        "db"    => "hirek_old", //"maxima_public",
        "user"  => "wiw_gen",
        "psw"   => "foci06vb"
    );
    if (!is_object($obj)){
        $obj = new PDO("mysql:host={$params["host"]};dbname={$params["db"]}", $params["user"], $params["psw"]);
    }
    return $obj;
  }
}
