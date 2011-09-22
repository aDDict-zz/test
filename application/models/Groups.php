<?php

class Application_Model_Groups extends Zend_Db_Table_Abstract {
  
  /**
   * Virtual table
   * Tables: members, multi, multigroup, groups, user
   * elements grouped by the multi table(if it has no join, called name is 'egyeb csoportok')
   */
  
  protected $_name = 'groups';

  public function getGroup($id){
    $id   = (int)$id;
    $row  = $this->fetchRow('id = ' . $id);
    if (!$row) {
        throw new Exception("Could not find row $id");
    }
    return $row->toArray();
  }
    
  public function getAll(){
   
   $result = $this->_db->query("
    SELECT
        if( length( g.name ) >0, g.name, g.title ) realname,
        if( length( multi.name ) >0, multi.name, multi.title ) mrealname,
        m.membership,
        m.group_id,
        g.title,
        mg.multiid
        
      FROM
        members m, groups g
        
      LEFT JOIN
        multigroup mg ON g.id = mg.groupid
        
      LEFT JOIN
        multi ON multi.id = mg.multiid
      
      AND mg.multiid IN
        (SELECT id FROM multi WHERE index_grouping='yes')
      
    WHERE g.id = m.group_id
    AND m.user_id = '59446'
   ")->fetchAll(); 
   return $result;
   
  }
  
}