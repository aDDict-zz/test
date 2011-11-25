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
      
      $vars = $this->_db->query("
        select
          lv.id     as id,
          lv.var    as variable,
          lc.var    as category
        from
          lang_variables lv
        left join
          lang_cat lc
            on lv.cat_id = lc.id
        where cat_id = ?;
        ",
        array($cat)
      )->fetchAll();
      
      foreach($vars as $i => $v) {
        $r = $this->_db->query("
          select
            value
          from
            lang_values
          where
            group_id = ?
          and
            var_id = ?;
          ",
          array($defLangid,$v['id'])
        )->fetchAll();   
        $vars[$i]['word'] = (isset($r[0]) ? $r[0]['value'] : "");
      }
      
      /*$result = $this->_db->query("
        select
          l_var.id     as id,
          l_var.var    as variable,
          l_cat.var    as category,
          l_val.value  as word
        from
          lang_variables l_var
          
        join
          lang_values l_val
        on
          l_var.id = l_val.var_id
          
        join
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
      )->fetchAll();*/
      
      $paginator = Zend_Paginator::factory($vars);
      $paginator->setItemCountPerPage($itemCount);
      $paginator->setCurrentPageNumber($page);
     
      foreach ($paginator as $item) {
        
        $res = $this->_db->query("
          select value from lang_values where var_id = {$item['id']} and group_id = {$langid};
        ")->fetchAll();
        

        
        $item["foreign_word"] = (isset($res[0]) ? $res[0]['value'] : "");
        $items[]              = $item;
        
      }
      
      return array(
        'success' => true,
        'results' => $paginator->getTotalItemCount(),
        'rows'    => $items
      );
      
    }
    
    public function getGroups() {
      $res = array();  
      $result = $this->_db->query("
        select * from lang_groups; 
      ")->fetchAll();
      foreach($result as $r) {
        $res[] = array(
          'lang'    => $r['val'],
          'langval' => "{$r['id']}|{$r['flag']}"
        );
      }
      
      return array(
        'success' => true,
        'rows'    => $res
      );
    }
    
    public function getCats() {
      $res = array();  
      $result = $this->_db->query("
        select * from lang_cat; 
      ")->fetchAll();
      foreach($result as $r) {
        $res[] = array(
          'cat'    => $r['var'],
          'catval' => "{$r['id']}"
        );
      }
      
      return array(
        'success' => true,
        'rows'    => $res
      );
    }
    
    public function updateRow($params) {
      $this->_db->query();
    }
    
    public function deleteCategory($id) {
      $this->_db->query("
        delete from lang_cat where id = ?;
      ",
      array($id)
      );
    }
    
    public function addCategory($cat) {
      $this->_db->query("
        insert into lang_cat(var) values(?);
      ",
      array($cat)
      );
    }
    
    public function addLanguage($lang) {
      $flag = substr($lang, 0, 2);
      $this->_db->query("
        insert into lang_groups(val,flag) values(?,?);
      ",
      array($lang,$flag)
      );
    }
    
    public function deleteLanguage($id) {
      $this->_db->query("
        delete from lang_groups where id = ?;
      ",
      array($id)
      );
    }
}

