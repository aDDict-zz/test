<?php

class Application_Model_Lang extends Zend_Db_Table_Abstract {

    public function getData($params) {
      
      $items        = array();
      $res          = $this->_db->query("select id from lang_groups where flag = ?", array((isset($params["lang"]) ? $params["lang"] : 'en')))->fetchAll();
      $langid       = $res[0]['id'];
      $defLangid    = 1;
      $cat          = (isset($params["cat"])  ? $params["cat"]    : '1');
      $page         = (isset($params["page"]) ? $params["page"]   : '1');
      $itemCount    = (isset($params["limit"])? $params["limit"]  : '5');
      
      $result = $this->_db->query("
        select
          l_var.id     as id,
          l_var.var    as variable,
          l_cat.var    as category,
          l_val.value  as word
        from
          lang_variables l_var
          
        left join
          lang_values l_val
        on
          l_var.id = l_val.var_id
          
        left join
          lang_cat l_cat
        on
          l_cat.id = l_var.cat_id
          
        where
          l_var.cat_id = ?
        and
          l_val.group_id = ?
        order by
          l_var.id
            asc
        ",
        array($cat,$defLangid)
      )->fetchAll();
      
      $paginator = Zend_Paginator::factory($result);
      $paginator->setItemCountPerPage($itemCount);
      $paginator->setCurrentPageNumber($page);
     
      foreach ($paginator as $item) {
        
        $res = $this->_db->query("
          select value from lang_values where var_id = {$item['id']} and group_id = {$langid};
        ")->fetchAll();
        
        $item["foreign_word"] = $res[0]['value'];
        $items[]              = $item;
        
      }
      
      //die( print_r( get_class_methods($paginator) ) );
      
      return array(
        'success' => true,
        'results' => $paginator->getTotalItemCount(),
        'rows'    => $items
      );
      
    }
}

