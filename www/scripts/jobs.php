<?php

header('Content-Type: text/html; charset=utf-8');

require_once("methods.php");

ini_set("display_errors", 1);
error_reporting(1);

#showResult("
#  insert into lang(cat, var, val, flag) values(1,'menu1','thismenu','hu');
#  insert into lang(cat, var, val, flag) values(1,'menu2','ddd','hu');
#  insert into lang(cat, var, val, flag) values(1,'menu3','sfdsf','hu');
#  insert into lang(cat, var, val, flag) values(1,'menu4','fghgfhgfh','hu');
#  insert into lang(cat, var, val, flag) values(1,'menu5','cvbcvb','hu');
#  insert into lang(cat, var, val, flag) values(1,'menu6','xcvxcvxcv','hu');
#  insert into lang(cat, var, val, flag) values(1,'menu7','yyyyyy','hu');
#  insert into lang(cat, var, val, flag) values(1,'menu8','vbcvb','hu');
#  insert into lang(cat, var, val, flag) values(1,'menu9','etrterter','hu');
#  insert into lang(cat, var, val, flag) values(1,'menu10','cvbcvb','hu');
#  insert into lang(cat, var, val, flag) values(1,'menu11','adasd','hu');

#  insert into lang(cat, var, val, flag) values(1,'menu12','thismenu','hu');
#  insert into lang(cat, var, val, flag) values(1,'menu13','ddd','hu');
#  insert into lang(cat, var, val, flag) values(1,'menu14','sfdsf','hu');
#  insert into lang(cat, var, val, flag) values(1,'menu15','fff','hu');
#  insert into lang(cat, var, val, flag) values(1,'menu16','ghfh','hu');
#  insert into lang(cat, var, val, flag) values(1,'menu17','yyyy','hu');
#  insert into lang(cat, var, val, flag) values(1,'menu18','ffffff','hu');
#  insert into lang(cat, var, val, flag) values(1,'menu19','ggggg','hu');
#  insert into lang(cat, var, val, flag) values(1,'menu20','bbbbnnn','hu');
#  insert into lang(cat, var, val, flag) values(1,'menu21','cvbcvb','hu');
#  insert into lang(cat, var, val, flag) values(1,'menu22','werwe','hu');

#  insert into lang(cat, var, val, flag) values(1,'menu23','vbnvbnvbngg','hu');
#  insert into lang(cat, var, val, flag) values(1,'menu24','fdgdfgdfg','hu');
#  insert into lang(cat, var, val, flag) values(1,'menu25','wwwwwwwrwrwer','hu');
#  insert into lang(cat, var, val, flag) values(1,'menu26','dfgdfgdfgdfg','hu');
#  insert into lang(cat, var, val, flag) values(1,'menu27','uiouiouiouio','hu');
#");

#showResult("
#  select id,group_id,send_date,send_plan,tlb_count,implementation,tlb_finished,tlb_finished_date,send_stopped from messages order by id desc limit 25;
#");


##showResult("
##  describe users_kutataspanel;
##");

//delete_qw(40454);

showResult("
  select count(*) from users_kutataspanel where ui_queries_which like '%40454%' limit 0,25
");

#//ui_queries_which
#showResult("
#  select * from users_kutataspanel where ui_queries_which != '' limit 0,25
#");

function delete_qw($id) {

  $PDO          = getPDO::get();
  $PDO->query("set names 'utf8'");
  $res          = $PDO->query("
    select id, ui_queries_which from users_kutataspanel where ui_queries_which like '%{$id}%'
  ")->fetchAll(PDO::FETCH_ASSOC);

  foreach($res as $r) {

    $result          = $PDO->query("
      select ui_queries_which as kw from users_kutataspanel where id = {$r["id"]};
    ")->fetchAll(PDO::FETCH_ASSOC);

    $kw = preg_replace("/{$id}/", "", $result[0]["kw"]);

    $PDO->query("
      update users_kutataspanel set ui_queries_which = '{$kw}' where id = {$r["id"]};
    ");
  }
}

#showResult("
#  select password from user where email = 'magyar.mark@hirekmedia.hu'
#");

#showResult("
#  select * from messages where tlb_finished='yes' order by id desc limit 0,10;
#");

#showResult("
#  select * from messages where tlb_finished='no' order by id desc limit 25;
#");


#showResult("
#  select * from messages where tlb_finished='yes' order by id desc limit 0,10;
#");

#showResult("
#  update messages set tlb_finished='yes' where id = 69403 || id = 69401 || id = 69398
#");


#$dd = exec("ps -A | grep aff");

#die( print_r( $dd ) );

#showResult("
#  update messages set tlb_finished='yes' where id = 69379 || id = 69285 || id = 69283 || id = 69281;
#");

#showResult("
#  select * from messages where tlb_finished='no' order by id desc limit 25;
#");

#showResult("
#  select count(*) from messages where user_id = 80121;
#");

//copyTableDatas("users_kutkchirlevel", "users_kutkcletoltok");

#showResult("
#  describe users_kutkcletoltok  //kutkchirlevel
#");

//showResult("truncate users_kutkcletoltok");

#showResult("
#  select count(id) from users_kutkcletoltok
#");

#showResult("
#  select count(id) from users_kutkchirlevel
#");

#showResult("
#  describe users_kutkcletoltok
#");

#showResult("
#  describe users_kutkchirlevel
#");

# showResult("
# 	truncate affiliate_cache;
# ");


#showResult("
#	select * from affiliate_cache order by id desc limit 0,1;
#");

# showResult("
# 	select count(*) as brutto from messages,user where messages.user_id=user.id and messages.test='no' and group_id=245;
# ");

#showResult("
#	select count(*) from affiliate_cache;
#");



# joinGroups2Members(1642, 81241);
# joinGroups2Members(1643, 81241);
# joinGroups2Members(1644, 81241);
# joinGroups2Members(1645, 81241);

# showResult("
# 	CREATE TABLE IF NOT EXISTS `affiliate_cache` (
#   `id` int(11) NOT NULL AUTO_INCREMENT,
# 	`name` varchar(100) NOT NULL DEFAULT '',
# 	`subject` varchar(255) NOT NULL DEFAULT '',
# 	`create_date` datetime DEFAULT NULL,
# 	`counter` int(11),
# 	`group_id` int(11),
# 	`user_id` int(11),
# 	`brutto` int(11),
#   PRIMARY KEY (`id`)
# ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;
# ");
#
# showResult("describe affiliate_cache");

//updateFormAdatvedelem();

//searchBadChars();

/*showResult("
  update messages set tlb_finished='yes' where id = 69100 || id = 69099 || id = 69159 || id = 69168;
");

showResult("
  select * from messages where tlb_finished='no' order by id desc limit 25;
");*/

/*showResult("
  delete from users_megajob where ui_email = 'numberonet@gmail.';
  select * from users_megajob where ui_email like '%numberonet%';
");*/

/*showResult("
  delete from users_megajob where ui_email = 'bathorylne@';
  select * from users_permission where ui_email like '%bathorylne%';
");*/

/*showResult("
  describe users_megajob
");*/


/*showResult("
  show tables
");*/

/*showResult("
  select * from users_kutataspanel where ui_cid = '2l0e0p1y';
");*/

/*showResult("
  select * from kuttnsrtl_cid where cid = '2l0e0p1y';
");*/



 /*showResult("
 	update users_ottohu set ui_cid = md5(concat(NOW(),'magyar', ui_email, id));
 	update users_ottocz set ui_cid = md5(concat(NOW(),'szlovak', ui_email, id));
 	update users_ottosk set ui_cid = md5(concat(NOW(),'cseh', ui_email, id));
 	update users_ottoro set ui_cid = md5(concat(NOW(),'roman', ui_email, id));
 ");*/

/*showResult("
  select * from filter where id = 21094 || id = 22203 || id = 22204 || id = 22201;
");

showResult("
  select * from filter where name like '%ssi_111017_tobaco_natrep_18_24_ffi_797fo_35%';
");

showResult("
  select * from messages order by id desc limit 0,50;
");*/

/*showResult("
  select * from filter where id = 21094 || id = 22203 || id = 22204 || id = 22201;
");

showResult("
  select * from messages where filter_id = 22204 || filter_id = 22203 limit 0,100;
");*/

//setUpFieldLength( 1631 );

//http://www.kutatocentrum.hu/adatvedelem.php

/*showResult("
  select count(*) from form
");*/

/*showResult("
  select count(*) from form_endlink;
");*/



/*showResult("
  select count(*) from form_page_box where text_after like '%http://www.kutatocentrum.hu/adatvedelem.php%' limit 0,10;
");*/


/*showResult("
  select * from form_endlink where html like '%http://www.kutatocentrum.hu/adatvedelem.php%' limit 0,10;
");*/
