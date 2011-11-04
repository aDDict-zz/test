<?php
error_reporting(E_ALL);




#showResult("
#  update
#    form_page
#      set
#        specvalid = 'mx_243_osszeg_4'
#  where
#    form_id = 332 && page_id = 4;

#  select * from form_page where form_id = 332 and page_id = 4;
#");

#copyDemogs(1487, 1643);
#copyDemogs(1488, 1642);
#copyDemogs(1489, 1645);
#copyDemogs(1557, 1644);

#copyTableAttributes("users_ottohukerdoiv1","users_ottohukerdoiv6");
#copyTableAttributes("users_ottorokerdoiv1","users_ottorokerdoiv6");
#copyTableAttributes("users_ottoczkerdoiv1","users_ottoczkerdoiv6");
#copyTableAttributes("users_ottoskkerdoiv1","users_ottoskkerdoiv6");

#showResult("
#  describe users_ottoczkerdoiv6;

#");

#showResult("
#  select * from users_ottohukerdoiv6;

#");

#showResult("
#  select * from users_ottohukerdoiv1 limit 50,3;

#");

#showResult("
#  describe users_ottoczkerdoiv1;

#");

#showResult("
#  describe users_ottorokerdoiv1;

#");

#showResult("
#  describe users_ottoskkerdoiv1;
#");

#showResult("
#  create table if not exists users_ottohukerdoiv6 like users_ottohukerdoiv1;
#  create table if not exists users_ottoczkerdoiv6 like users_ottoczkerdoiv1;
#  create table if not exists users_ottorokerdoiv6 like users_ottorokerdoiv1;
#  create table if not exists users_ottoskkerdoiv6 like users_ottoskkerdoiv1;
#");

#showResult("
#  select * from user order by  id desc limit 0,5;
#");

#showResult("
#  SELECT count( * )
#FROM form_element fe
#LEFT JOIN form_element_dep fed ON fe.id = fed.form_element_id
#LEFT JOIN form_element_parent_dep fepd ON fe.id = fepd.form_element_id
#WHERE fe.form_id =243
#AND fe.dependency != ''
#ORDER BY `fe`.`id` ASC
#");

#showResult("
#  update `form_element` set parent_dependency = '!PD436' where id = 16248;
#");
//form_element_parent_dep


#showResult("
#  select fe.id, fe.form_id, fe.dependency, fe.parent_dependency, fe.parent, fe.question
#    FROM form_element fe
#  WHERE fe.form_id = 172 and fe.dependency != '' order by fe.id;
#");

#showResult("
#  select fe.id, fe.form_id, fe.dependency, fe.parent_dependency, fe.parent, fe.question
#    FROM form_element fe
#  WHERE fe.form_id = 332 and fe.dependency != '' order by fe.id;
#");

#showResult("
#  select fe.id, fe.form_id, fe.dependency, fe.parent_dependency, fe.question, fed.id, fed.dependent_id, fepd.id, fepd.parent_id
#    FROM form_element fe
#    LEFT JOIN form_element_dep fed on fe.id = fed.form_element_id
#    LEFT JOIN form_element_parent_dep fepd on fed.form_element_id = fepd.form_element_id
#  WHERE fe.form_id = 243 and fe.dependency != '' order by fe.id;
#");

#showResult("
#  select fe.id, fe.form_id, fe.dependency, fe.parent_dependency, fe.question, fed.id, fed.dependent_id, fepd.id, fepd.parent_id
#    FROM form_element fe
#    LEFT JOIN form_element_dep fed on fe.id = fed.form_element_id
#    LEFT JOIN form_element_parent_dep fepd on fed.form_element_id = fepd.form_element_id
#  WHERE fe.form_id = 326 and fe.dependency != '' order by fe.id;
#");

#showResult("

#  SELECT id, dependency, parent_dependency
#FROM `form_element`
#WHERE id =12214 || id =16257

#");


#showResult("

#  select
#          0,fe2.id,
#          parent_id,
#          parent_columns,
#          neg
#        from
#          form_element fe1,
#          form_element fe2,
#          form_element_parent_dep fed
#        where
#          length(fe1.parent_dependency) and length(fe2.parent_dependency) and fe1.form_id=243 and fe2.form_id=326 and fe1.parent=fe2.parent and fe1.id=fed.form_element_id;

#");


/*showResult("
  insert
    into
      form_element_parent_dep

        select
          0,fe2.id,
          parent_id,
          parent_columns,
          neg
        from
          form_element fe1,
          form_element fe2,
          form_element_parent_dep fed
        where
          length(fe1.parent_dependency) and length(fe2.parent_dependency) and fe1.form_id=243 and fe2.form_id=326 and fe1.parent=fe2.parent and fe1.id=fed.form_element_id;




update form_element fe,form_element_parent_dep fed set parent_dependency=concat('PD',fed.id) where length(parent_dependency) and fe.id=fed.form_element_id and fe.form_id=326;
");*/

# showResult( "

#  update messages set tlb_finished='yes' where tlb_finished='no';

# 	select id from messages where tlb_finished='no' order by id desc limit 25;
# " );

#showREsult("
#  update users_ottohukerdoiv5 set ui_otto_forrasszuro = '';
#  update users_ottoczkerdoiv5 set ui_otto_forrasszuro = '';
#  update users_ottorokerdoiv5 set ui_otto_forrasszuro = '';
#  update users_ottoskkerdoiv5 set ui_otto_forrasszuro = '';
#");


#showResult("


#  update users_ottohukerdoiv5 set ui_otto_forrasszuro = concat(',', ui_otto_forrasszuro , ',') where ui_otto_forrasszuro != '';
#  update users_ottoczkerdoiv5 set ui_otto_forrasszuro = concat(',', ui_otto_forrasszuro , ',') where ui_otto_forrasszuro != '';
#  update users_ottorokerdoiv5 set ui_otto_forrasszuro = concat(',', ui_otto_forrasszuro , ',') where ui_otto_forrasszuro != '';
#  update users_ottoskkerdoiv5 set ui_otto_forrasszuro = concat(',', ui_otto_forrasszuro , ',') where ui_otto_forrasszuro != '';


#");

//updateUserTime(81241); updateUserTime(59446);

#distinct ui_email,users_ottohukerdoiv5.id,validated_date,last_clicked,last_sent,mess_total

#showResult("
#  select count(*) from users_ottoskkerdoiv5
#");

#showResult("
#  select count(*) from users_ottoskkerdoiv5 where ui_otto_forrasszuro  like  '%40332%' limit 0,25
#");
#showResult("
#  select count(*) from users_ottoskkerdoiv5 where ui_otto_forrasszuro  like  '%40333%' limit 0,25
#");


#showResult("

#select *
#from users_ottohukerdoiv5 where (( users_ottohukerdoiv5.bounced='no' and ( ( (0 or ui_otto_forrasszuro = ',40332,' ) or (0 or ui_otto_forrasszuro = ',40333,' ) ) ))) and validated='yes' and robinson='no' order by validated_date desc limit 0,25

#");

#showResult("
#  select * from users_ottohukerdoiv5 where ui_otto_forrasszuro = 40333 || ui_otto_forrasszuro = 40332 limit 0,25;
#");


#showResult("
#  select count(distinct ui_cid)
#from users_ottocz where (( users_ottocz.bounced='no' and ( (uglist like '%,13607,%') and ui_cid like '_%' ))) and validated='yes' and robinson='no' order by validated_date desc limit 0,25
#");

#showResult("
#  select count(uo.ui_cid) as counter from users_ottocz uo
#      left join users_ottoczkerdoiv5 uo5 on uo.ui_cid = uo5.ui_cid
#      where (( uo.bounced='no' and ( (uo.uglist like '%,13603,%') and uo.ui_cid like '_%' ))) and uo.validated='yes' and uo.robinson='no';
#");

#showResult("
#  select * from users_ottocz limit 0,1
#");

#showResult("
#  select
#    count(users_ottohukerdoiv5.ui_cid)
#  from
#    users_ottohukerdoiv5
#  left join
#    users_ottohu uo
#  on
#    users_ottohukerdoiv5.ui_cid = uo.ui_cid
#  where (( uo.bounced='no' and ( (uo.uglist like '%,13603,%') and uo.ui_cid like '_%' ))) and uo.validated='yes' and uo.robinson='no'
#");
//where (( users_ottohu.bounced='no' and ( (uglist like '%,13603,%') and ui_cid like '_%' ))) and validated='yes' and robinson='no' order by validated_date
#showResult("
#  select
#    count(*)
#  from
#    users_ottohukerdoiv5
#  where
#    ui_cid in
#    (select users_ottohu.ui_cid from users_ottohu where (( users_ottohu.bounced='no' and ( (uglist like '%,13603,%') and ui_cid like '_%' ))) and validated='yes' and robinson='no' order by validated_date);
#");


#showResult("
#  select
#    *
#  from
#  users_ottohukerdoiv5
#  limit 0,1
#");

#showResult("
#  select
#    count(*)
#  from
#  users_ottohukerdoiv5
#");

// nvk_110916 filter
//distinct ui_email,users_ottohu.id,validated_date,last_clicked,last_sent,mess_total
#showResult("
#  select ui_cid from users_ottohu where (( users_ottohu.bounced='no' and ( (uglist like '%,13603,%') and ui_cid like '_%' ))) and validated='yes' and robinson='no' order by validated_date
#");

// kikuldes_0929 filter
//distinct ui_email,users_ottohu.id,validated_date,last_clicked,last_sent,mess_total
#showResult("
#  select
#    count(ui_email)
#  from
#    users_ottohu
#  where
#    (( users_ottohu.bounced='no' and ( not (messagelist like '%,68524,%') and ui_cid like '_%' ))) and validated='yes' and robinson='no' order by validated_date desc limit 0,25
#");




#showREsult("
#  describe users_kutemailmark10;
#  describe users_kutemailmark11;
#");


//copyTableAttributes("users_kutemailmark10", "users_kutemailmark11");

#showResult("
#  create table if not exists users_kutemailmark11 like users_kutemailmark10;
#");

#showResult("
#select * from users_kutemailmark11
#");

//copyDemogs(1566, 1641);

# copyTableAttributes("users_kuttnsrtl", "users_kuttnsrtlbk");

# showResult( "
# 	select id,group_id,send_date,send_plan,tlb_count,implementation,tlb_finished,tlb_finished_date,send_stopped from messages where tlb_finished='no' order by id desc limit 25
# " );

# showResult("
# 	select * from users_kuttnsrtlbk;
# ");
#
# showResult("
# 	select * from users_kuttnsrtl limit 0,10;
# ");

//,group_id,send_date,send_plan,tlb_count,implementation,tlb_finished,tlb_finished_date,send_stopped

# showResult("
# 	update messages set tlb_finished = 'yes' where tlb_finished='no' && (id != 68662 || id != 68633);
# ");
#
# showResult("
# 	 select * from messages where tlb_finished = 'no' limit 0,20;
# ");

# showResult("
# 	 select count(*) from sender_archive;
# ");


# getNyeremenyjatekUsers();

function getNyeremenyjatekUsers() {
	$PDO 			= getPDO::get();
	$content 	= "";
	$res 			= $PDO->query("
		select ui_email,ui_friend_subscribed from users_permission where ui_friend_subscribed like '%,301-%';
	")->fetchAll(PDO::FETCH_ASSOC);
	foreach($res as $result) {
		$content .= "{$result["ui_email"]}\n";
	}

	getCsv($content, "nyeremenyjatekosok.csv");
}

function getCsv($content, $filename) {
	# header("Content-type: application/x-msdownload");
	header("Content-Disposition: attachment; filename={$filename}");
	header("Pragma: no-cache");
	header("Expires: 0");
	echo $content;
}

#  $str = '<div style="with:100;text-align:center">
#
# 				<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="360" height="295" id="maggi12" align="middle">
# 				<param name="movie" value="http://www.maxima.hu/extranet/images/kcvideo_rtl/maggi12.swf" />
# 				<param name="quality" value="high" />
# 				<param name="bgcolor" value="#ffffff" />
# 				<param name="play" value="true" />
# 				<param name="loop" value="true" />
# 				<param name="wmode" value="window" />
# 				<param name="scale" value="showall" />
# 				<param name="menu" value="true" />
#                 <param name="FlashVars" value="movietitle=http://www.maxima.hu/extranet/images/kcvideo_rtl/maggi12.flv&buttonstyle=http://www.maxima.hu/extranet/images/kcvideo_rtl/kc-player.swf" />
# 				<param name="devicefont" value="false" />
# 				<param name="salign" value="" />
# 				<param name="allowScriptAccess" value="sameDomain" />
# 				<!--[if !IE]>-->
# 				<object type="application/x-shockwave-flash" data="http://www.maxima.hu/extranet/images/kcvideo_rtl/maggi12.swf" width="360" height="295">
# 					<param name="movie" value="http://www.maxima.hu/extranet/images/kcvideo_rtl/maggi12.swf" />
# 					<param name="quality" value="high" />
# 					<param name="bgcolor" value="#ffffff" />
# 					<param name="play" value="true" />
# 					<param name="loop" value="true" />
# 					<param name="wmode" value="window" />
# 					<param name="scale" value="showall" />
# 					<param name="menu" value="true" />
#                     <param name="FlashVars" value="movietitle=http://www.maxima.hu/extranet/images/kcvideo_rtl/maggi12.flv&buttonstyle=http://www.maxima.hu/extranet/images/kcvideo_rtl/kc-player.swf" />
# 					<param name="devicefont" value="false" />
# 					<param name="salign" value="" />
# 					<param name="allowScriptAccess" value="sameDomain" />
# 				<!--<![endif]-->
# 					<a href="http://www.adobe.com/go/getflash">
# 						<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />
# 					</a>
# 				<!--[if !IE]>-->
# 				</object>
# 				<!--<![endif]-->
# 			</object>
#
# 			</div>';
#  $str = mysql_escape_string($str);
# # showResult("
# #   update form_element set question = '' where id = 16154;
# # ");
# # showResult("
# #   update form_element set question = '' where id = 16156;
# # ");
# #
# showResult("
#   update form_element set question = '{$str}' where id = 16135;
# ");
# showResult("
#   update form_element set question = '{$str}' where id = 16121;
# ");




//updateUserTime(81241);


//copyDemogs(1633,1639);

function copyDemogs($groupFrom, $groupTo) {
	$PDO 		= getPDO::get();
	$query 	= "";
	$res 		= $PDO->query("
		select demog_id from vip_demog where group_id = {$groupFrom};
	")->fetchAll(PDO::FETCH_ASSOC);
	foreach($res as $result) {
		$PDO->query("insert into vip_demog(demog_id, group_id, mandatory, dateadd, ask, changeable, deleted, tstamp) values({$result["demog_id"]}, {$groupTo}, 'no', '2011-09-26', 'yes', 'yes', 'no', '2011-09-26 08:07:04');");
	}
}

# showResult("
# 	select demog_id from vip_demog where group_id = 1639;
# ");

# showResult("
# 	create table if not exists users_kuttnsrtlbk like users_kuttnsrtl;
# 	update form set group_id = 1639 where id = 325;
# ");


# showResult("
# 	update users_ottohu set ui_cid = md5(concat('magyar', ui_email, id));
# 	update users_ottocz set ui_cid = md5(concat('szlovak', ui_email, id));
# 	update users_ottosk set ui_cid = md5(concat('cseh', ui_email, id));
# 	update users_ottoro set ui_cid = md5(concat('roman', ui_email, id));
# ");



//copyTableAttributes("users_ottohukerdoiv5", "users_ottoskkerdoiv5");

# showResult("
# 	insert into vip_demog(demog_id, group_id, mandatory, dateadd, ask, changeable, deleted, tstamp)
# 	values(1, 1636, 'no', '2011-09-26', 'yes', 'yes', 'no', '2011-09-26 08:07:04');
# 	insert into vip_demog(demog_id, group_id, mandatory, dateadd, ask, changeable, deleted, tstamp)
# 	values(2, 1636, 'no', '2011-09-26', 'yes', 'yes', 'no', '2011-09-26 08:07:04');
# 	insert into vip_demog(demog_id, group_id, mandatory, dateadd, ask, changeable, deleted, tstamp)
# 	values(6, 1636, 'no', '2011-09-26', 'yes', 'yes', 'no', '2011-09-26 08:07:04');
# ");


# showResult("
# 	select * from users_ottoskkerdoiv5
# ");

/*showResult("
	update form set group_id = 1636 where id = 321;
	update form set group_id = 1637 where id = 323;
	update form set group_id = 1638 where id = 324;
");*/

# showResult( "
# 	select id,group_id,send_date,send_plan,tlb_count,implementation,tlb_finished,tlb_finished_date,send_stopped from messages where tlb_finished='no' order by id desc limit 25
# " );


#  $str = '<div style="with:100;text-align:center"><object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="320" height="240" id="telenorvv5fade_din" align="middle" width="660" height="406">
# 				<param name="movie" value="http://www.maxima.hu/extranet/images/telenorvv5/telenorvv5fade_din.swf" />
# 				<param name="quality" value="high" />
# 				<param name="bgcolor" value="#ffffff" />
# 				<param name="play" value="true" />
# 				<param name="loop" value="true" />
# 				<param name="wmode" value="window" />
# 				<param name="scale" value="showall" />
# 				<param name="menu" value="true" />
#         <param name="FlashVars" value="movietitle=telenorvv5fade.flv&buttonstyle=http://www.maxima.hu/extranet/images/telenorvv5/kc-player.swf" />
# 				<param name="devicefont" value="false" />
# 				<param name="salign" value="" />
# 				<param name="allowScriptAccess" value="sameDomain" />
# 				<object type="application/x-shockwave-flash" data="http://www.maxima.hu/extranet/images/telenorvv5/telenorvv5fade_din.swf" width="660" height="406">
# 					<param name="movie" value="http://www.maxima.hu/extranet/images/telenorvv5/telenorvv5fade_din.swf" />
# 					<param name="quality" value="high" />
# 					<param name="bgcolor" value="#ffffff" />
# 					<param name="play" value="true" />
# 					<param name="loop" value="true" />
# 					<param name="wmode" value="window" />
# 					<param name="scale" value="showall" />
# 					<param name="menu" value="true" />
#           <param name="FlashVars" value="movietitle=telenorvv5fade.flv&buttonstyle=http://www.maxima.hu/extranet/images/telenorvv5/kc-player.swf" />
# 					<param name="devicefont" value="false" />
# 					<param name="salign" value="" />
# 					<param name="allowScriptAccess" value="sameDomain" />
# 					<a href="http://www.adobe.com/go/getflash">
# 						<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />
# 					</a>
# 				</object>
# 			</object></div>';
#  $str = mysql_escape_string($str);
# showResult("
#   update form_element set question = '{$str}' where id = 15994;
# ");
# showResult("
#   update form_element set question = '{$str}' where id = 16008;
# ");



# $charset = "utf-8"; //"utf-8"  //iso-8859-1
# header("Content-Type: text/html; charset={$charset}");





//15437  15439  7503  15439

//update_question(15441, "<br /><br /><div style='text-align:center;width:100%'><img style='margin:0auto;' src='http://www.maxima.hu/extranet/images/o_k/Photos_022_vagott.png' width=500 /></div>");
//update_question(15442, "<br /><br /><div style='text-align:center;width:100%'><img style='margin:0auto;' src='http://www.maxima.hu/extranet/images/o_k/Photos_022_vagott.png' width=500 /></div>");

function update_question($id, $str){
  $PDO = getPDO::get();
  $res = $PDO->query("
    select question from form_element where id = {$id}
  ")->fetchAll(PDO::FETCH_ASSOC);
  $question = mysql_escape_string($res[0]["question"]);

  $str = $question . mysql_escape_string( $str );

  $PDO->query( "update form_element set question = '{$str}' where id = {$id}" );
}





//getHashedEmailsByFilter();

# function getHashedEmailsByFilter() {
# 	$PDO = getPDO::get();
#   $res = $PDO->query("
# SELECT
# 	ui_email
# FROM users_permission
# WHERE (
# (
# users_permission.bounced = 'no'
# AND (
# (
# ui_szuletesnap >= '1956-9-19'
# AND ui_szuletesnap < '1986-9-19'
# )
# AND (
# ( 0
# OR ui_payment_now = ',416,' )
# OR ( 0
# OR ui_payment_now = ',417,' )
# OR ( 0
# OR ui_payment_now = ',418,' )
# OR ( 0
# OR ui_payment_now = ',419,' )
# OR ( 0
# OR ui_payment_now = ',420,' )
# OR ( 0
# OR ui_payment_now = ',421,' )
# OR ( 0
# OR ui_payment_now = ',422,' )
# OR ( 0
# OR ui_payment_now = ',423,' )
# OR ( 0
# OR ui_payment_now = ',424,' )
# OR ( 0
# OR ui_payment_now = ',425,' )
# OR ( 0
# OR ui_payment_now = ',8096,' )
# OR ( 0
# OR ui_jobstatus_new = ',3515,' )
# OR ( 0
# OR ui_jobstatus_new = ',3516,' )
# OR ( 0
# OR ui_jobstatus_new = ',3517,' )
# OR ( 0
# OR ui_jobstatus_new = ',3518,' )
# OR ( 0
# OR ui_jobstatus_new = ',3523,' )
# )
# AND ( 0
# OR ui_auto LIKE '%,103,%' )
# AND NOT (
# aff = '80121'
# )width="427" height="350"
# )
# )
# )
# AND validated = 'yes'
# AND robinson = 'no'
#   ")->fetchAll(PDO::FETCH_ASSOC);
# $out = "";
# foreach($res as $email) {
# 	$out .= md5($email["ui_email"]) . ",\n";
# }
#
# header("Content-type: application/x-msdownload");
# header("Content-Disposition: attachment; filename=log.csv");
# header("Pragma: no-cache");
# header("Expires: 0");
# 	echo $out;
# }

# showResult( "
# 	select id,group_id,send_date,send_plan,tlb_count,implementation,tlb_finished,tlb_finished_date,send_stopped from messages where tlb_finished='no' order by id desc limit 25
# " );


/*showResult( "
	ALTER TABLE `users_kuttnshera` CHANGE `ui_kuttnshera_p3` `ui_kuttnshera_p3` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
" );*/



//15939 15940 15944

#  $str = 'A következőkben mutatunk Önnek egy TV reklámot. Látta-e Ön ezt a HÉRA TV reklámot mostanában?<br /><br /><div style="with:100;text-align:center"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
# codebase="http://download.macromedi#  $str = 'A következőkben mutatunk Önnek egy TV reklámot. Látta-e Ön ezt a HÉRA TV reklámot mostanában?<br /><br /><div style="with:100;text-align:center"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
# codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,16,0"
# width="427" height="350" >
# <param name="movie" value="http://www.maxima.hu/extranet/video_c_kc_web.swf">
# <param name="quality" value="high">
# <param name="play" value="false">
# <param name="LOOP" value="false">
# <param name="autostart" value="false" />
# <embed src="http://www.maxima.hu/extranet/video_c_kc_web.swf" width="427" height="350" play="false" loop="false" play="false" autostart="false" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer"
# type="application/x-shockwave-flash" flashvars="autoplay=false&play=false">
# </embed>
# </object></div>';
#  $str = mysql_escape_string($str);
# showResult("
#   update form_element set question = '{$str}' where id = 15944;copyTableAttributes
# ");a.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,16,0"
# width="427" height="350" >
# <param name="movie" value="http://www.maxima.hu/extranet/video_a_kc_web.swf">
# <param name="quality" value="high">
# <param name="LOOP" value="false">
# <param name="play" value="false" />
# <param name="autostart" value="false" />
# <embed src="http://www.maxima.hu/extranet/video_a_kc_web.swf" width="427" height="350" play="false" loop="false" play="false" autostart="false" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer"
# type="application/x-shockwave-flash" flashvars="autoplay=false&play=false">
# </embed>
# </object></div>';
#  $str = mysql_escape_string($str);
# showResult("
#   update form_element set question = '{$str}' where id = 15939;
# ");
#
#  $str = 'A következőkben mutatunk Önnek egy TV reklámot. Látta-e Ön ezt a HÉRA TV reklámot mostanában?<br /><br /><div style="with:100;text-align:center"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
# codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,16,0"
# width="427" height="350" >
# <param name="movie" value="http://www.maxima.hu/extranet/video_b_kc_web.swf">
# <param name="quality" value="high">
# <param name="play" value="false">
# <param name="LOOP" value="false">
# <param name="autostart" value="false" />
# <embed src="http://www.maxima.hu/extranet/video_b_kc_web.swf" width="427" height="350" play="false" loop="false" play="false" autostart="false" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer"
# type="application/x-shockwave-flash" flashvars="autoplay=false&play=false">
# </embed>
# </object></div>';
#  $str = mysql_escape_string($str);
# showResult("
#   update form_element set question #  $str = 'A következőkben mutatunk Önnek egy TV reklámot. Látta-e Ön ezt a HÉRA TV reklámot mostanában?<br /><br /><div style="with:100;text-align:center"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
# codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,16,0"
# width="427" height="350" >
# <param name="movie" value="http://www.maxima.hu/extranet/video_c_kc_web.swf">
# <param name="quality" value="high">
# <param name="play" value="false">
# <param name="LOOP" value="false">
# <param name="autostart" value="false" />
# <embed src="http://www.maxima.hu/extranet/video_c_kc_web.swf" width="427" height="350" play="false" loop="false" play="false" autostart="false" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer"
# type="application/x-shockwave-flash" flashvars="autoplay=false&play=false">
# </embed>
# </object></div>';
#  $str = mysql_escape_string($str);
# showResult("
#   update form_element set question = '{$str}' where id = 15944;
# ");= '{$str}' where id = 15940;
# ");
#
#
#  $str = 'A következőkben mutatunk Önnek egy TV reklámot. Látta-e Ön ezt a HÉRA TV reklámot mostanában?<br /><br /><div style="with:100;text-align:center"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
# codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,16,0"
# width="427" height="350" >
# <param name="movie" value="http://www.maxima.hu/extranet/video_c_kc_web.swf">
# <param name="quality" value="high">
# <param name="play" value="false">
# <param name="LOOP" value="false">
# <param name="autostart" value="false" />
# <embed src="http://www.maxima.hu/extranet/video_c_kc_web.swf" width="427" height="350" play="false" loop="false" play="false" autostart="false" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer"
# type="application/x-shockwave-flash" flashvars="autoplay=false&play=false">
# </embed>
# </object></div>';
#  $str = mysql_escape_string($str);
# showResult("
#   update form_element set question = '{$str}' where id = 15944;
# ");
#  $str = 'A következőkben mutatunk Önnek egy TV reklámot. Látta-e Ön ezt a HÉRA TV reklámot mostanában?<br /><br /><div style="with:100;text-align:center"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
# codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,16,0"
# width="427" height="350" >
# <param name="movie" value="http://www.maxima.hu/extranet/video_c_kc_web.swf">
# <param name="quality" value="high">
# <param name="play" value="false">
# <param name="LOOP" value="false">
# <param name="autostart" value="false" />
# <embed src="http://www.maxima.hu/extranet/video_c_kc_web.swf" width="427" height="350" play="false" loop="false" play="false" autostart="false" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer"
# type="application/x-shockwave-flash" flashvars="autoplay=false&play=false">
# </embed>
# </object></div>';
#  $str = mysql_escape_string($str);
# showResult("
#   update form_element set question = '{$str}' where id = 15944;
# ");
//copyTableAttributes

//1d8g5p3c, 5i8p3f1i, 7s8j2x1g, 8p8l4f1m, 1v1c3u9a, 8u3r8o3l, 9d6o6y1p, 3x4c4q8s


/*showResult("

select id,tstamp, ui_kuttnshera_p3, ui_kuttnshera_p6, ui_kuttnshera_p8_1, ui_kuttnshera_p8_2, ui_kuttnshera_p8_3, ui_kuttnshera_p8_4, ui_kuttnshera_p8_5, ui_kuttnshera_p8_6, ui_kuttnshera_p8_7, ui_kuttnshera_p8_8, ui_kuttnshera_p8_9, ui_kuttnshera_p8_10 from users_kuttnshera where id = 125

");*/


#showResult("
#  select id,tstamp, ui_kuttnshera_p3, ui_kuttnshera_p6, ui_kuttnshera_p8_1, ui_kuttnshera_p8_2, ui_kuttnshera_p8_3, ui_kuttnshera_p8_4, ui_kuttnshera_p8_5, ui_kuttnshera_p8_6, ui_kuttnshera_p8_7, ui_kuttnshera_p8_8, ui_kuttnshera_p8_9, ui_kuttnshera_p8_10
#
#  from users_kuttnshera
#
#
#  where ui_cid = '1d8g5p3c'
#	or ui_cid = '5i8p3f1i'
#	or ui_cid = '7s8j2x1g'
#	or ui_cid = '8p8l4f1m'
#	or ui_cid = '1v1c3u9a'
#	or ui_cid = '8u3r8o3l'
#	or ui_cid = '9d6o6y1p'
#	or ui_cid = '3x4c4q8s'
#");
#showResult("

#select id,tstamp, ui_kuttnshera_p3, ui_kuttnshera_p6, ui_kuttnshera_p8_1, ui_kuttnshera_p8_2, ui_kuttnshera_p8_3, ui_kuttnshera_p8_4, ui_kuttnshera_p8_5, ui_kuttnshera_p8_6, ui_kuttnshera_p8_7, ui_kuttnshera_p8_8, ui_kuttnshera_p8_9, ui_kuttnshera_p8_10
#
#  from users_kuttnshera

#where id = 90
#
#");

#  where ui_cid = 1d8g5p3c
# or ui_cid = 5i8p3f1i
# 	or ui_cid = 7s8j2x1g
# 	or ui_cid = 8p8l4f1m
# 	or ui_cid = 1v1c3u9a
# 	or ui_cid = 8u3r8o3l
# 	or ui_cid = 9d6o6y1p
# 	or ui_cid = 3x4c4q8s

# showResult("select * from form_element where id = 15944");

//showResult("show tables");
//showResult("describe users_kutsakkompr");

# showResult("SELECT d.variable_name
# FROM vip_demog vd
# LEFT JOIN demog d ON vd.demog_id = d.id
# WHERE vd.group_id =1629");

//showResult("select * from users_kutsakkompr2011");

//showResult("show tables");

# showResult("select * from kutsakkompr2011_cid");
# echo "<br />\nxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx<br />\n";
# showResult("select * from kutsakkompr_cid");

//showResult("select id from groups order by id desc limit 0,3");
# showResult("select * from users_kutsakkompr2011");

//copyTableAttributes("users_kutsakkompr","users_kutsakkompr2011");

# showResult("SELECT vd.id, vd.demog_id, d.variable_name, d.question, variable_type, multiselect
# FROM vip_demog vd, demog d
# WHERE vd.group_id = '1629'
# AND vd.demog_id = d.id
# AND (
# d.question LIKE '%%'
# OR d.variable_name LIKE '%%'
# )
# LIMIT 0 , 15");

//setupPages();

//showResult("SELECT * FROM form_page_box WHERE group_id='1629' AND form_id='310' group by page_id order by page_id");

//copymembersgroups(59446,59446);
//updateUserTime(59446);
//updateUserTime(59446);

# $str = '<div id="flashContent" style="width:100%;height:100%;">
# 			<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="710" height="320" id="kc_kontainer" align="middle">
# 				<param name="movie" value="kc_kontainer.swf" />
# 				<param name="quality" value="high" />
# 				<param name="bgcolor" value="#ffffff" />
# 				<param name="play" value="true" />
# 				<param name="loop" value="true" />
# 				<param name="wmode" value="window" />
# 				<param name="scale" value="showall" />
# 				<param name="menu" value="true" />
# 				<param name="devicefont" value="false" />
# 				<param name="salign" value="" />
# 				<param name="allowScriptAccess" value="sameDomain" />
# 				<object type="application/x-shockwave-flash" data="kc_kontainer.swf" width="710" height="320">
# 					<param name="movie" value="kc_kontainer.swf" />
# 					<param name="quality" value="high" />
# 					<param name="bgcolor" value="#ffffff" />
# 					<param name="play" value="true" />
# 					<param name="loop" value="true" />
# 					<param name="wmode" value="window" />
# 					<param name="scale" value="showall" />
# 					<param name="menu" value="true" />
# 					<param name="devicefont" value="false" />
# 					<param name="salign" value="" />
# 					<param name="allowScriptAccess" value="sameDomain" />
# 					<a href="http://www.adobe.com/go/getflash">
# 						<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />
# 					</a>
# 				</object>
# 			</object>
# 		</div>';
#
# $str = mysql_escape_string($str);

//echo $str;

# showResult("
# 	update form_element set question = '{$str}' where id = 15828;
#
# ");

//getSQLInsert(15827, "form_element");

//showResult("insert into form_element(id,form_id,page,box_id,sortorder,demog_id,question,widget,mandatory,hide_option,direction,default_value,maxlength,errmsg,additionaltext,viralfunc,multi_append,question_position,dependency,image,image_position,parent,possible_values,max_num_answer,rotate,parent_dependency) values(15827,'263','10','A','2','7523','felismeri.......:','radio','yes','no','vertical','','0','','','','no','above','','','before','','','0','no','')");

//showResult("update form_element set question = '{$str}' where id = 15828;");
//showResult("delete from form_element where id = 15827");
//showResult("select * from form_element where id = 15827");

function copymembersgroups($from,$to){
  $PDO = getPDO::get();
  $res = $PDO->query("
    select group_id from members where user_id = {$from}
  ")->fetchAll(PDO::FETCH_ASSOC);
  foreach($res as $group_id){
    joinGroups2Members($group_id["group_id"],$to);
  }
}

function setupPages(){

	$form_id 			= 230;
	$group   			= 1554;
	$new_group_id = 1629;
	$newFormId 		= 310;

	$PDO = getPDO::get();
	// form_page
  $pages = $PDO->query("
    select * from form_page where form_id = {$form_id} and group_id = {$group};
  ")->fetchAll(PDO::FETCH_ASSOC);

#   if(count($pages) > 0){
#     foreach($pages as $page){
#       $sql1 = "
#         insert into form_page
# (group_id,form_id,page_id,prev_button_text,next_button_text,boxes,prev_button_url,next_button_url,active,admeasure,dependency,parent_dependency,specvalid)
#         values
# ({$new_group_id},{$newFormId},'{$page['page_id']}','{$page['prev_button_text']}','{$page['next_button_text']}','{$page['boxes']}','{$page['prev_button_url']}','{$page['next_button_url']}','{$page['active']}','{$page['admeasure']}','{$page['dependency']}','{$page['parent_dependency']}','{$page['specvalid']}');
#       "; //echo $sql1 . "<br />\n";
# 			$PDO->query($sql1);
#     }
#   }

  // form_page_box
  $pages = $PDO->query("
    select * from form_page_box where form_id = {$form_id} and group_id = {$group};
  ")->fetchAll(PDO::FETCH_ASSOC);

  if(count($pages) > 0){
    foreach($pages as $page){
      $sql2 = "
        insert into form_page_box
(group_id,form_id,page_id,box_id,text_before,text_after,title,subscribe_groups,active)
        values
({$new_group_id},{$newFormId},'{$page['page_id']}','{$page['box_id']}','{$page['text_before']}','{$page['text_after']}','{$page['title']}','{$page['subscribe_groups']}','{$page['active']}');
      "; //echo $sql2 . "<br />\n";
			$PDO->query($sql2);
    }
  }
}


//print_r(getNewGroupAttrs(1629));

# if(isset($_GET["id"]) && isset($_GET["table"]))
#  echo getSQLInsert($_GET["id"], $_GET["table"]);
# else
#  echo "need params";

//showResult("delete from form where id = 311 or id = 312 or id = 313");
//showResult("update form set group_id = 1629 where id = 310;");
//showResult("select id from form where group_id = 1554;");

# if(isset($_GET["group"]) && isset($_GET["form"]) && isset($_GET["newgroup"]))
#  cloneFormAndDemogsByGroup($_GET["group"], $_GET["form"], $_GET["newgroup"]);
# else
#  echo "need params";

//joinGroups2Members(1554, 59446); //59446  Tamas, 81241 sajat
//joinGroups2Members(1629, 59446);


// showResult("select id from form where group_id = 1629");
// showResult("select id from form where group_id = 1554");
# echo "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx<br />";
# showResult("
# 	select fe.id from form_element fe
# 	join form_element_dep fed on fe.id = fed.form_element_id
# 	where fe.form_id = 305;
# ");

//deleteAllFormDEpendentStuff();

# function deleteAllFormDEpendentStuff(){
# 	$PDO = getPDO::get();
# # 	$res = $PDO->query("
# # 		select id from form_element where form_id = 305;
# # 	")->fetchAll(PDO::FETCH_ASSOC); //print_r($res);
# #
# # 	foreach($res as $result){
# # 		//echo $result['id'] . "<br />\n";
# # 		$sql1 = "delete from form_element_enumvals where form_element_id = {$result['id']};";
# # 		$sql2 = "delete from form_element_dep where form_element_id = {$result['id']};";
# #
# # 		$PDO->query($sql1);
# # 		$PDO->query($sql2);
# # 	}
#
# 	$PDO->query("delete from form where id = 305");
#
# }

# showResult("
# 	select * from form_element fe
# 	left join form_element_dep fed on fe.id = fed.form_element_id
# 	where fe.form_id = 230 limit 0, 2;
# ");
#
# echo "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx<br />\n";
#
# showResult("
# 	select * from form_element fe
# 	left join form_element_dep fed on fe.id = fed.form_element_id
# 	where fe.form_id = 305 limit 0, 2;
# ");

//setfeds();
// for_element_dep
# function setfeds(){
# 	$PDO = getPDO::get();
#   $res = $PDO->query("
# 	select fe.question question, fe.demog_id demog_id, fe.form_id form_id, fed.id fedid, fed.form_element_id form_element_id, fed.dependent_id dependent_id, fed.dependent_value dependent_value, fed.neg neg from form_element fe
#  	join form_element_dep fed on fe.id = fed.form_element_id
#  	where fe.form_id = 230;
# 	")->fetchAll(PDO::FETCH_ASSOC);
#
# 	$sql = "";
# 	foreach($res as $result){
# 		$thisRes = $PDO->query("
# 			select id, question from form_element where demog_id = {$result['demog_id']} and form_id = 305;
# 		")->fetchAll(PDO::FETCH_ASSOC);
# 		//print_r($thisRes);
# 		$sql = "insert into form_element_dep(form_element_id,dependent_id,dependent_value,neg) values({$thisRes[0]['id']},{$result['dependent_id']},{$result['dependent_value']},'{$result['neg']}');";
# 		echo $sql . "<br />\n";
# 		//$PDO->query($sql);
# 	}
# }

# showResult("
# 	delete from form_element_dep
# 	where form_element_id = 8162
# 	or form_element_id = 8170
#
# 	or form_element_id = 8161
# 	or form_element_id = 8163
# 	or form_element_id = 8171
# 	or form_element_id = 8164
# 	or form_element_id = 8172
# 	or form_element_id = 8165
# 	or form_element_id = 8173
#
# 	or form_element_id = 8166
# 	or form_element_id = 8174
# 	or form_element_id = 8167
# 	or form_element_id = 8175
# 	or form_element_id = 8168
# 	or form_element_id = 8176
# 	or form_element_id = 8169
# 	or form_element_id = 8177
# ");

//echo "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx<br />";
# showResult("select * from form_element where id = 8162");
# showResult("select * from form_element_dep where id = 15459");
//echo "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx<br />";
# showResult("
# 	select fed.id, fed.form_element_id, fed.dependent_id, fed.dependent_value from form_element fe
# 	join form_element_dep fed on fe.id = fed.form_element_id
# 	where fe.form_id = 305;
# ");

# showResult("
# 	select count(*) from form_element where form_id = 305
# ");
# showResult("
# 	select count(*) from form_element where form_id = 230
# ");

//showResult("delete from form_element_dep where id = 8169");

# showResult("
# 	select fed.id from form_element fe
# 	join form_element_dep fed on fe.id = fed.form_element_id
# 	where fe.form_id = 305;
# ");

# showResult("
# 	select fed.id from form_element fe
# 	join form_element_dep fed on fe.id = fed.form_element_id
# 	where fe.form_id = 230;
# ");

# showResult("select id from form_element where form_id = 230");
# showResult("select id from form_element where form_id = 305");





function joinGroups2Members($group_id, $user_id){
  $PDO        = getPDO::get();
  $PDO->query("insert into members(user_id,group_id,membership,create_date,modify_date,tstamp,trusted_affiliate,kedvenc) values({$user_id},{$group_id},'moderator','2011-09-02','2011-09-02',NOW(),'no','no')");
}


function showResult($sql){
  $PDO        = getPDO::get();

	if($sql != ""){
		$res = $PDO->query($sql)->fetchAll(PDO::FETCH_ASSOC); print_r($res);
	} else {
		echo "need a valid sql";
	}
}


//addFormElementDeps(305,230);

//305
function addFormElementDeps($toID, $frID){
	$PDO  = getPDO::get();
  $res  = $PDO->query("
    select fe.id, fed.dependent_id, fed.id as fedId from form_element fe
    join form_element_dep fed on fe.id = fed.form_element_id
    where fe.form_id = {$frID}
  ")->fetchAll(PDO::FETCH_ASSOC);

  foreach($res as $result){
    $thisRes  = $PDO->query("select id from form_element where demog_id = {$result["dependent_id"]} and form_id = {$toID}")->fetchAll(PDO::FETCH_ASSOC);
    $PDO->query( getSQLInsert($result["fedId"], "form_element_dep", array("form_element_id" => $thisRes[0]["id"])) );
  }
}

function copyTableAttributes($tableFrom, $tableTo){
  $PDO = getPDO::get();
  $origColumns = array();
  $res = $PDO->query("describe {$tableTo}")->fetchAll(PDO::FETCH_ASSOC);
  foreach($res as $columns){
    $origColumns[] = $columns["Field"];
  }
  $res = $PDO->query("describe {$tableFrom}")->fetchAll(PDO::FETCH_ASSOC);
  foreach($res as $columns){
    if(!in_array($columns["Field"],$origColumns)){
      $PDO->query("alter table {$tableTo} add {$columns["Field"]} {$columns["Type"]}");
    }
  }
}

// example query: group=1554&form=230&newgroup=1629
function cloneFormAndDemogsByGroup($group, $form_id, $new_group_id){
  $PDO        = getPDO::get();

  // step 1., table form, creating a new element with a given group_id
  $PDO->query( getSQLInsert($form_id, "form", array("group_id" => $new_group_id)) );

  $lastInsertIdArr = $PDO->query("
    select id from form order by id desc limit 0,1
  ")->fetchAll(PDO::FETCH_ASSOC);
  $newFormId = $lastInsertIdArr[0]["id"];

  // step 2., table form_element, cloning the relevant records with the given form ids and setting up the show properties of the form elements
  $form_elements = $PDO->query("
    select id from form_element where form_id = {$form_id};
  ")->fetchAll(PDO::FETCH_ASSOC);

  foreach($form_elements as $formElementId){
    $PDO->query( getSQLInsert($formElementId["id"], "form_element", array("form_id" => $newFormId)) );

    $lastInsertIdArr = $PDO->query("
      select id from form_element order by id desc limit 0,1
    ")->fetchAll(PDO::FETCH_ASSOC);
    $newFormElementId = $lastInsertIdArr[0]["id"];

    $resEnum = $PDO->query("
      select count(id) as c from form_element_enumvals where form_element_id = {$formElementId["id"]};
    ")->fetchAll(PDO::FETCH_ASSOC);

    $resDep = $PDO->query("
      select count(id) as c from form_element_dep where form_element_id = {$formElementId["id"]};
    ")->fetchAll(PDO::FETCH_ASSOC);

    if($resEnum[0]["c"] > 0){
      $res = $PDO->query("
        select * from form_element_enumvals where form_element_id = {$formElementId["id"]};
      ")->fetchAll(PDO::FETCH_ASSOC);



      foreach($res as $enumVals){
        $PDO->query( getSQLInsert($enumVals["id"], "form_element_enumvals", array("form_element_id" => $newFormElementId)) );
      }
    }

    if($resDep[0]["c"] > 0){
      $res = $PDO->query("
        select * from form_element_dep where form_element_id = {$formElementId["id"]};
      ")->fetchAll(PDO::FETCH_ASSOC);



      foreach($res as $depIds){
        $PDO->query( getSQLInsert($depIds["id"], "form_element_dep", array("form_element_id" => $newFormElementId)) );
      }
    }
  }

  // step 3., table vip_demog, cloning the relevant records with the given group_id
  $vip_demogs = $PDO->query("
    select id from vip_demog where group_id = {$group};
  ")->fetchAll(PDO::FETCH_ASSOC);

  foreach($vip_demogs as $vipDemogId){
    $PDO->query( getSQLInsert($vipDemogId["id"], "vip_demog", array("group_id" => $new_group_id)) );
  }

  // egyeb stuff
  // form_banner
  $banners = $PDO->query("
    select id from form_banner where form_id = {$form_id};
  ")->fetchAll(PDO::FETCH_ASSOC);
  if(count($banners) > 0){
    foreach($banners as $banner){
      $PDO->query( getSQLInsert($banner["id"], "form_banner", array("form_id" => $newFormId)) );
    }
  }
  // form_css
  $css = $PDO->query("
    select id from form_css where form_id = {$form_id};
  ")->fetchAll(PDO::FETCH_ASSOC);
  if(count($css) > 0){
    foreach($css as $cs){
      $PDO->query( getSQLInsert($cs["id"], "form_css", array("form_id" => $newFormId)) );
    }
  }
  // form_email
  $emails = $PDO->query("
    select id from form_email where form_id = {$form_id};
  ")->fetchAll(PDO::FETCH_ASSOC);
  if(count($emails) > 0){
    foreach($emails as $email){
      $PDO->query( getSQLInsert($email["id"], "form_email", array("form_id" => $newFormId)) );
    }
  }

  // form_page
  $pages = $PDO->query("
    select * from form_page where form_id = {$form_id} and group_id = {$group};
  ")->fetchAll(PDO::FETCH_ASSOC);

  if(count($pages) > 0){
    foreach($pages as $page){
      $PDO->query( "
        insert into form_page
(group_id,form_id,page_id,prev_button_text,next_button_text,boxes,prev_button_url,next_button_url,active,admeasure,dependency,parent_dependency,specvalid)
        values
({$new_group_id},{$newFormId},'{$page['page_id']}','{$page['prev_button_text']}','{$page['next_button_text']}','{$page['boxes']}','{$page['prev_button_url']}','{$page['next_button_url']}','{$page['active']}','{$page['admeasure']}','{$page['dependency']}','{$page['parent_dependency']}','{$page['specvalid']}');
      " );
    }
  }

  // form_page_box
  $pages = $PDO->query("
    select * from form_page_box where form_id = {$form_id} and group_id = {$group};
  ")->fetchAll(PDO::FETCH_ASSOC);

  if(count($pages) > 0){
    foreach($pages as $page){
      $PDO->query( "
        insert into form_page
(group_id,form_id,page_id,box_id,text_before,text_after,title,subscribe_groups,active)
        values
({$new_group_id},{$newFormId},'{$page['page_id']}','{$page['box_id']}','{$page['text_before']}','{$page['text_after']}','{$page['title']}','{$page['subscribe_groups']}','{$page['active']}');
      " );
    }
  }



}

function getSQLInsert($id, $table, $arr = ""){

  $PDO        = getPDO::get();
  $thisRes    = $PDO->query("
    select * from {$table} where id={$id}
  ")->fetchAll(PDO::FETCH_ASSOC);

  $keys = array(); $values = array();

  if(gettype($arr) == "array"){
    $thisArr = array_keys($arr);
    foreach($thisRes[0] as $k => $v){
      if($k == $thisArr[0]){
        $keys[]   = mysql_escape_string($thisArr[0]);
        $values[] = mysql_escape_string($arr[$thisArr[0]]);
      }
      else if($k != "id"){
        $keys[]   = mysql_escape_string($k);
        $values[] = mysql_escape_string($v);
      }
    }
  }
  else if(gettype($arr) == "string")
    foreach($thisRes[0] as $k => $v){
      if($k != "id"){
        $keys[]   = mysql_escape_string($k);
        $values[] = mysql_escape_string($v);
      }
    }

  $sql          = "";
  $columnlist   = "";
  $columnVals   = "";

  for($i = 0; $i < count($keys); $i++){
    if($i < count($keys) - 1){
      $columnlist   .= "{$keys[$i]},";
      $columnVals   .= "'{$values[$i]}',";
    } else {
      $columnlist   .= "{$keys[$i]}";
      $columnVals   .= "'{$values[$i]}'";
    }
  }

  return "insert into {$table}({$columnlist}) values({$columnVals});";
}


function getNewGroupAttrs($group_id){
  $PDO        = getPDO::get();
  return $PDO->query("
    select * from groups where id={$group_id}
  ")->fetchAll(PDO::FETCH_ASSOC);
}

function updateUserTime($id){
	$PDO        = getPDO::get();
	$PDO->query("update user set password_modify = NOW() where id = {$id}");
}


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

/*
  |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\
  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\
  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /
  |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/
*/

class getPDO{
  public function &get(){
    static $obj;
    $params = array(
        "host"  => "localhost",
        "db"    => "maxima", //"maxima_public",
        "user"  => "roto",
        "psw"   => "barto2k6"
    );
    if (!is_object($obj)){
        $obj = new PDO("mysql:host={$params["host"]};dbname={$params["db"]}", $params["user"], $params["psw"]);
    }
    return $obj;
  }
}



?>
