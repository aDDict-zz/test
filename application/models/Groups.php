<?php

class Application_Model_Groups extends Zend_Db_Table_Abstract {
  
  protected $_name = 'groups';

  public function getGroup($id){
    $id = (int)$id;
    $row = $this->fetchRow('id = ' . $id);
    if (!$row) {
        throw new Exception("Could not find row $id");
    }
    return $row->toArray();
  }
  
  public function fetchAll(){
    return array(
      "elso" => array(
        "a1" => 1,
        "a2" => 2
      ),
      "masodik" => "2"
    );
  }
}