<?php

class Application_Model_User extends Zend_Db_Table_Abstract {
  
  protected $_name = 'user';
  
  public function getUser($username){
    
    $result = $this->_db->query(
      "select username, password from user where username = ?",
      array($username)
    )->fetchAll(); 
    
    return $result;
    
    /*$row  = $this->fetchRow('username = ' . $username);
    if (!$row) {
        throw new Exception("Could not find row $id");
    }
    return $row->toArray();*/
  }
}