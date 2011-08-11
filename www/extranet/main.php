<?php

error_reporting(E_ERROR);

function getAnotherRelevantGroups($demog_id){
  $out = " a változtatás az alábbi csoportokat érinti:<br /><ul style='color:red;list-style-type: none;'>";  
  $PDO = getPDO::get();
  $res = $PDO->query("
    select
      g.title as title
    from
      groups g
    left join
      vip_demog vd
    on
      vd.group_id = g.id
    left join
      demog d
    on
      d.id = vd.demog_id
    where
      d.id = {$demog_id}
  ")->fetchAll(PDO::FETCH_ASSOC);
  
  foreach($res as $v){
    $out .= "<li><b>{$v["title"]}</b></li>\n";
  }
  
  return "{$out}</ul>";
}

?>