<?php
  
  include "auth.php";
  include "common.php";
  include "_form.php";
  
  $group_id=543;
  $form_id=75;
  
  $res=mysql_query("select * from form where id='{$form_id}' and group_id='{$group_id}'"); //echo "select * from form where id='{$form_id}' and group_id='{$group_id}'"; die();
  $formdata=mysql_fetch_array($res,MYSQL_ASSOC);
  
  $_MX_form = new MxForm($group_id,"",0,0);
  
  //$vv = $_MX_form->MakeForm();   //print_r($vv);
  
  //print_r($_MX_form);
  
  print_r( get_class_methods( $_MX_form ) );
  
  //$_MX_form->MakeMenu('elements',$formdata);
  
  //print_r( $formdata["landing_page"] );
  
?>