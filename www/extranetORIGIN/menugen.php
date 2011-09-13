<?
// html output starts here, so select languages here. (some scripts do not use menu, be careful.)
// common.php is very important, think about it, too, in such scripts.
include_once "common.php";
include_once "decode.php";
include "./lang/$language/menugen.lang";
include "./lang/$language/form.lang";
include "./lang/$language/sender.lang";
include "./lang/$language/sms.lang";
include "./lang/$language/demog.lang";
include "./lang/$language/admin_user.lang";

$nogroup = get_http('no_group','');

?>
<html>
<head>
<title><?=$_MX_var->application_instance_name;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="shortcut icon" href="http://<?= ($_MX_var->application_instance=="kc"?"www.kutatocentrum.hu/favicon.ico":"images.szereteknyerni.hu/maxima_favicon.ico") ?>"/>
<meta name="author" CONTENT="Hírek Média és Internet Technológiai Kft.">
<meta name="copyright" CONTENT="Hírek Média és Internet Technológiai Kft.">
<meta name="description" CONTENT="Magyarország vezetõ online direkt marketing rendszere. Célzott email-, sms kampányok 35 szûrési lehetõséggel, adatbázis építés és hírlevél-üzemeltetés.">
<meta name="keywords" CONTENT="direkt marketing,email,sms,kampány,permission,opt-in,marketing,adatbázis,célzott,hatékony,targetál">
<?
$application_instance_css = array("reset","maxima","simplemodal/basic","jquery.wysiwyg","print");
$application_instance_css_ie = array("ie");
$application_instance_js = array("jquery-1.3.2.min","jquery.simplemodal-1.3","jquery.wysiwyg","ajaxupload.3.9","jquery_scripts");
//attach css files
foreach ($application_instance_css as $css) {?>
    <link rel="stylesheet" type="text/css" href="<?=$_MX_var->baseUrl;?>/<?=$_MX_var->application_instance;?>/css/<?=$css;?>.css?v=<?=filemtime($_MX_var->baseDir.'/'.$_MX_var->application_instance.'/css/'.$css.'.css');?>" media="<?if($css=='print'){?>print<?}else{?>screen<?}?>" />
<?}?>
<!--[if IE]>
<?
foreach ($application_instance_css_ie as $iecss) {?>
<link type="text/css" href="<?=$_MX_var->baseUrl;?>/<?=$_MX_var->application_instance;?>/css/<?=$iecss;?>.css?v=<?=filemtime($_MX_var->baseDir.'/'.$_MX_var->application_instance.'/css/'.$iecss.'.css');?>" rel="stylesheet" media="screen" />
<?}?>
<![endif]-->
<?
//attach js files
foreach ($application_instance_js as $js) {?>
    <script type="text/javascript" src="<?=$_MX_var->baseUrl;?>/js/<?=$js;?>.js?v=<?=filemtime($_MX_var->baseDir.'/js/'.$js.'.js');?>"></script>
<?}?>
    <script type="text/javascript">var baseUrl = '<?=$_MX_var->baseUrl;?>'; var nogroup = '<?=$nogroup;?>'; var nogroup_message = '<?=$word['nogroup_message'];?>'; var superadmin = '<?=$_MX_superadmin;?>'; var no_file_selected = '<?=$word["no_file_selected"];?>'; var auid = 0;</script>
</head>

<body>
<? if (!isset($_MX_iframe) && !isset($_MX_popup)): ?>
    <div class="bg">
        <div class="center">
            <div class="header_menu">
                <?foreach($_MX_var->supported_langs as $k=>$lang){?>
                <a href="?language=<?=$lang;?>" class="<?=$lang;?>_flag"><?=$_MX_var->langs[$k];?></a>
                <span class="separator">|</span>
                <?}?>
                Bejelentkezve mint
                <b><?=$active_username;?></b>
                <span class="separator">|</span>                
                <a href='password.php?multiid=<?=$multiid;?>&group_id=<?=$group_id;?>'><?=$word['menu_mydata'];?></a>
                <span class="separator">|</span>
                <a class="logout icon" title="<?=$word['menu_logout'];?>" href="logout.php"><?=$word['menu_logout'];?></a>
                <div class="clear"></div>
            </div><!--/header_menu-->
            <div class="clear"></div>
            <div class="header_pad">
                <div class="fl">
                    <a href="<?=$_MX_var->baseUrl;?>"><img src="<?=$_MX_var->baseUrl;?>/<?=$_MX_var->application_instance;?>/gfx/<?= ($_MX_var->application_instance=="lightmail"?"logo.gif":"maxima-170.png") ?>" alt="logo" /></a>
                </div>
    <?
    # cookie_auth.php takes care about authentication,
    # scripts take care about whether specific user has access to specific group and script
    # after all that is this script included.
    # if multiid is given, we should generate menu for multigroups.
    # if group_id is given, we should generate menu for groups.
    # if none of these is given, we are at index.php and there is no need for menu. 

    $group_id=intval(get_http("group_id",0));
    $multiid=intval(get_http("multiid",0));
    $menu=0;
    if ($group_id && $weare) {
        $r1=mysql_query("select * from groups where id='$group_id'");
        if ($r1 && mysql_num_rows($r1)) {
            $groupdata=mysql_fetch_array($r1);
            $statistics=$groupdata["statistics"];
            $outsource=$groupdata["outsource"];
            $sms_send=$groupdata["sms_send"];
            if (!empty($groupdata["name"])) {
                $active_title="$groupdata[name] <span class='activelisttitle'>($groupdata[title])</span>";
            }
            else {
                $active_title=$groupdata["title"];
            }
            $title=$groupdata["title"];
            $menu=1;
            $active_what=$word["menu_what_list"];
            $membership='';
            $query = mysql_query("select membership from members where user_id='$active_userid' and group_id='$group_id'");
            if ($query && mysql_num_rows($query))
                $membership=mysql_result($query,0,0);
            if ($membership == 'owner') $owner=1;
            if ($membership == 'owner' || $membership == 'moderator') $modown=1;
        }
    }
    if ($multiid) {
        $r1=mysql_query("select * from multi where id='$multiid'");
        if ($r1 && mysql_num_rows($r1)) {
            $multidata=mysql_fetch_array($r1);
            $menu=2;
            $active_what=$word["menu_what_multi"];
            $active_title=$multidata["title"];
            $membership='';
            $query = mysql_query("select membership from multi_members where user_id='$active_userid' and group_id='$multiid'");
            if ($query && mysql_num_rows($query))
                $membership=mysql_result($query,0,0);
            if ($membership == 'owner') $owner=1;
            if ($membership == 'owner' || $membership == 'moderator') $modown=1;
        }
    }
    if (!$menu) {
        $active_what=$word["menu_what_list"];
        $active_title=$word["menu_what_none"];
    }

    $request_uri=ereg_replace("^\/[a-zA-Z0-9_]+\/","/",$_SERVER["REQUEST_URI"]);
    $request_uri=ereg_replace("&language=[a-z]{2}","",$request_uri);
    $request_uri=addslashes($_MX_var->baseUrl . $request_uri);

    $language=="hu"?$sel_lang="en":$sel_lang="hu";
    if (strstr($request_uri,"?"))
        $request_uri.="&language=$sel_lang";
    else
        $request_uri.="?language=$sel_lang";
    ?>                
    <!-- header_right_stuff *****************************************************************************************************************************-->
    <div id="header_right_stuff" class="fr" style="width:750px;margin-top:10px">
        <div class="pb5 tal">
            <p class="bold"><?=$active_title;?></p>
        </div>
        <?
        $quq="select count(m.id) as linecount, sum(m.send_plan) as sum_send_plan, sum(m.tlb_count) as sum_tlb_count
            from messages m,groups g,members p where m.tlb_finished='no' and m.send_plan>0 
            and m.group_id=g.id and g.id=p.group_id and p.user_id='$active_userid' and p.membership in ('owner','moderator')";
        $qur=mysql_query($quq);
        if ($quk=mysql_fetch_array($qur)) {
          if ($quk["linecount"]) {
                if ($quk["sum_send_plan"]) $percent = min(100,round(max(0,$quk["sum_tlb_count"])*100/$quk["sum_send_plan"]));
                else $percent = 0;
            ?>
            <div class="procstatus">

            <div class="fl">
            <?=$word['proc_list'];?> <span class="bold" id="proc_count"><?=$quk['linecount'];?></span> <?=$word['run_proc'];?> <span class="bold" id="proc_percent"><?=$percent;?></span>
            <b>%</b> <?=$word['sent'];?>
            </div>

            <div class="fr">
                <input type="checkbox" id="autoopen" <?if(!empty($_COOKIE["proclist_autoopen"])){?>checked="checked"<?}?> />
                <script type="text/javascript">
                    $(document).ready(function(){
                        if($("#autoopen").is(':checked')) {
                            $("#show_proclist").trigger('click');
                        }
                    });
                </script>
                <span id="autoopen_desc" class="fs9" style="margin-right:30px;"><?=$word["keep_list_open"];?></span>
                <a href="javascript:void(0);" id="show_proclist"><?=$word["open_list"];?></a>
            </div>

            </div>

            <div class="vishid" id="proclist">
                <div id="content"></div>
            </div>     
        <?}}?>

        <div class='felso2'>
            <div style="float:right;margin-top:3px;">
                <p>
        <?
            //get the data for the top menu

            $topmenurow = array();

            if ($weare==23) {
                $topmenurow[] = '[<span class="topactivemenu">'.$word['menu_select'].'</span>]';
            }
            else {
                $topmenurow[] = '[<a href="index.php?group_id='.$group_id.'">'.$word['menu_select'].'</a>]';
            }

            $rml=mysql_query("select id from members where user_id='$active_userid' and membership='moderator' limit 1");
            if ($weare==100) {
                $topmenurow[] = '[<span class="topactivemenu">'.$word['multi_groups'].'</span>]';
                if ($rml && mysql_num_rows($rml)) {
                    $mainnav = "<a href='multi.php?multiid=$multiid'>$word[multi_groups]</a>";
                }
            }
            else {
                if ($rml && mysql_num_rows($rml)) {
                    $topmenurow[] = "[<a href='multi.php?multiid=$multiid'>$word[multi_groups]</a>]";
                }
            }
            $rlusr=mysql_query("select view_logs from user where id='$active_userid'");
            if ($rlusr && mysql_num_rows($rlusr)) {
                if ($_MX_superadmin) {
                    if ($weare == 200) {
                        $topmenurow[] = '[<span class="topactivemenu">'.$word["menu_top_log"].'</span>]';
                        $mainnav = '<a href="admin_log.php">'.$word["menu_top_log"].'</a>';
                    }
                    else {
                        $topmenurow[] = '[<a href="admin_log.php">'.$word["menu_top_log"].'</a>]';
                    }

                    if ($weare == 201) { 
                        $topmenurow[] = '[<span class="topactivemenu">'.$word["admin_statistic"].'</span>]';
                        $mainnav = '<a href="admin_statistic.php">'.$word["admin_statistic"].'</a>';
                    } 
                    else {
                        $topmenurow[] = '[<a href="admin_statistic.php">'.$word["admin_statistic"].'</a>]';
                    }
                    
                    if ($weare == 202) {
                        $topmenurow[] = '[<span class="topactivemenu">'.$word["admin_user"].'</span>]';
                        $mainnav = '<a href="admin_user.php">'.$word["admin_user"].'</a>';
                    } 
                    else {
                        $topmenurow[] = '[<a href="admin_user.php">'.$word["admin_user"].'</a>]';
                    }
                } 
                else {
                    if (mysql_result($rlusr,0,0)=="yes") {
                        if ($weare == 200) {
                            $topmenurow[] = '[<span class="topactivemenu">'.$word["menu_top_log"].'</span>]';
                            $mainnav = '<a href="admin_log.php">'.$word["menu_top_log"].'</a>';
                        }
                        else {
                            $topmenurow[] = '[<a href="admin_log.php">'.$word["menu_top_log"].'</a>]';
                        }
                    }
                }
                $topmenurow[] = '[<a href="javascript:void(0);" id="open_help">Help</a>]';
            }              

            foreach ($topmenurow as $k=>$item) {
                print $item;
            }
        ?>
                </p>
            </div>
        </div>
    </div>
    <!-- header_right_stuff *****************************************************************************************************************************-->

    <div class="menu<?if(!empty($_COOKIE["proclist_autoopen"])){?> menubg<?}?>">
    <?       
    #look at language files for catalogue of 'weare' parameters
    //$menu = 2;
    $multi = '';
    $dbpages="";
    if ($menu==1) {
        $allowed_pages["affiliate"]=array(20,41,42,43);
        $allowed_pages["client"]=array(20);
        $allowed_pages["support"]=array(20,4,50,51);
        if ($title=='knorr' || $title=='knorrtest') {
            $spec_knorr=1;
            $spec_knorr_nop="";
        }
        else {
            $spec_knorr=0;
            $spec_knorr_nop=" and page_id not in (46,47)";
        }
        if ($modown) {
            $spec_knorr==1?$dbpages="":$dbpages="and p.id not in (46,47)";
        }
        elseif ($membership=="admin") {
            $dbpages="and p.id in (0";
            $r=mysql_query("select page_id from page_user where group_id='$group_id' and user_id='$active_userid' $spec_knorr_nop");
            if ($r && mysql_num_rows($r)) {
                while ($mm=mysql_fetch_array($r))
                    $dbpages.=",$mm[page_id]";
            }
            $dbpages.=")";
        }
        else {
            $dbpages="and p.id in (0";
            for ($i=0;$i<count($allowed_pages["$membership"]);$i++)
                $dbpages.=",".$allowed_pages["$membership"][$i];
            $dbpages.=")";
        }

        $multi = 'no';

        $ereg_1 = "&group_id=$group_id";
        $ereg_2 = "?group_id=$group_id";

        $wearename = 'weare';

        $sg = '';
    }

    if ($menu==2) {
        $allowed_pages["affiliate"]=array(241,242);
        if ($modown) {
            $dbpages="";
        }
        else {
            $dbpages="and p.id in (0";
            for ($i=0;$i<count($allowed_pages["$membership"]);$i++)
                $dbpages.=",".$allowed_pages["$membership"][$i];
            $dbpages.=")";
        }

        $multi = 'yes';

        $ereg_1 = "&multiid=$multiid";
        $ereg_2 = "?multiid=$multiid";

        $wearename = 'sgweare';

        $sg = 'sg';
    }

    $res=mysql_query("select p.id as pid,pg.id as pgid,p.php,p.inmenu from page p,pagegroup pg where pg.id=p.pagegroup 
                      and p.type='protected' and p.multi='$multi' $dbpages
                      order by pg.sortorder,p.sortorder");
    $prev_pgid=-1;
    $found_menu=0;
    $menustr=array();
    $mainmenu=array();
    $firstphp="";
    $row_beg="";
    $row_sb="";
    $row_se="";
    $row_end="";
    $maincat = 0;
    if ($res && mysql_num_rows($res)) {
        while ($mm=mysql_fetch_array($res)) {
            if (!isset($menustr[$mm["pgid"]])) {
                $menustr[$mm["pgid"]] = "";
            }
            if ($prev_pgid!=$mm["pgid"] && $prev_pgid!=-1) {
                if ($found_menu==1) {
                    $found_menu=2;
                    $maincat = $prev_pgid;
                    $mainmenu[$prev_pgid] = '<div class="dispinl"><span class="current" id="mmenu'.$prev_pgid.'">'.$word["menu_main$prev_pgid"].'</span>';
                    $mainnav = '<a href="'.$firstphp.'">'.$word["menu_main$prev_pgid"].'</a>';
                }
                else {
                    $mainmenu[$prev_pgid] = '<div class="dispinl"><a href="'.$firstphp.'" id="mmenu'.$prev_pgid.'">'.$word["menu_main$prev_pgid"].'</a>';
                }
                $firstphp="";
                if ($found_menu==0)
                    $menustr[$mm["pgid"]]="";
            }
            $prev_pgid=$mm["pgid"];
            ($membership=="affiliate" && $mm["pid"]==20)?$php="threadlist_aff.php":$php=$mm["php"];
            (ereg("\?",$php))?$php.=$ereg_1:$php.=$ereg_2;
            //if ($found_menu<2) {
                if ($weare==$mm["pid"]) {
                    $found_menu=1;
                    if ($weare != 21 && $weare != 27 && $weare != 34){
                        $menustr[$mm["pgid"]].=$row_sb.'<span class="current" style="padding-left:13px;">'.$word["menu_$sg$mm[pid]"].'</span>'.$row_se."\n";
                        $midnav = '<a href="'.$php.'">'.$word["menu_$sg$mm[pid]"].'</a>';
                    }
                }
                elseif ($mm["inmenu"]=="yes") {
                    if (($weare == 27 && $mm["pid"] == 25) || ($weare == 21 && $mm["pid"] == 20)) {
                        $menustr[$mm["pgid"]].=$row_sb.'<span class="current" style="padding-left:13px;">'.$word["menu_$sg$mm[pid]"].'</span>'.$row_se."\n"; 
                    }       
                    else {
                        $menustr[$mm["pgid"]].=$row_beg.'<a class="alsomenu" href="'.$php.'">'.$word["menu_$sg$mm[pid]"].'</a>'.$row_end."\n";
                    }
                }
                
                /*if (($weare == 27 && $mm["pid"] == 25) || ($weare == 21 && $mm["pid"] == 666)) {
                    $menustr[$mm["pgid"]].=$row_sb.'<span class="current" style="padding-left:13px;">'.$word["menu_$mm[pid]"].'</span>'.$row_se."\n";
                    $midnav = '<a href="'.$php.'">'.$word["menu_$mm[pid]"].'</a>';
                } */               
            //}
            if (empty($firstphp))
                $firstphp=$php;
        }
        if ($found_menu==1) {
            $mainmenu[$prev_pgid] = '<div class="dispinl"><span class="current" id="mmenu'.$prev_pgid.'">'.$word["menu_main$prev_pgid"].'</span>';
        }
        else {
            $mainmenu[$prev_pgid] = '<div class="dispinl"><a href="'.$firstphp.'" id="mmenu'.$prev_pgid.'">'.$word["menu_main$prev_pgid"].'</a>';
        }
        if ($found_menu==0)
            $menustr[$mm["pgid"]]="";
    }

    if (count($mainmenu)) {
        print '<span class="separator">|</span>';
    }

    //print the main menu
    foreach ($mainmenu as $mmid=>$mmenu) {
        print $mmenu;
        if (!empty($menustr[$mmid])) {
	        print '<div class="dropmenudiv smenumarg tal" id="smenu'.$mmid.'" style="display:none;">'.$menustr[$mmid].'</div>';
        }
        print '</div><span class="separator">|</span>';
    }

    $parms="group_id=$group_id";
    $fd = array();
    $fd['id'] = get_http('form_id','');
    $baseid = get_http('base_id','');
    $contsid = get_http('contents_id','');
    $timerid = get_http('timer_id','');
    $messid = get_http('message_id','');
    $demid = get_http('demog_id','');
    $demgrid = get_http('demog_group_id','');
    $id = get_http('id','');
    $chuserid = get_http('chuser_id','');
    $subpages=array (
        "241"=>'<a href="sender_base_ch.php?'.$parms.'&base_id='.$baseid.'">'.$word['base_change'].'</a>',
        "242"=>'<a href="sender_base_ch.php?'.$parms.'">'.$word["base_new"].'</a>',
        
        "801"=>'<a href="sender_contents_ch.php?'.$parms.'&contents_id='.$contsid.'">'.$word["contents_change"].'</a>',
        "802"=>'<a href="sender_contents_ch.php?'.$parms.'">'.$word["contents_new"].'</a>',
        
        "821"=>'<a href="message_category_ch.php?'.$parms.'&message_category_id='.$contsid.'">'.$word["message_category_change"].'</a>',
        "822"=>'<a href="message_category_ch.php?'.$parms.'">'.$word["message_category_new"].'</a>',

        "811"=>'<a href="sender_timer_ch.php?'.$parms.'&timer_id='.$timerid.'">'.$word["timer_change"].'</a>',
        "812"=>'<a href="sender_timer_ch.php?'.$parms.'">'.$word["timer_new"].'</a>',
        
        "171"=>'<a href="mygroups13_edit.php?'.$parms.'&demog_id='.$demid.'">'.$word['base_change'].'</a>',
        //"172"=>'<a href="mygroups13_edit.php?'.$parms.'">'.$word["demog_new"].'</a>',
        
        "601"=>'<a href="demog_group_ch.php?'.$parms.'&demog_group_id='.$demgrid.'">'.$word["dg_change"].'</a>',
        "602"=>'<a href="demog_group_ch.php?'.$parms.'5">'.$word['dg_new'].'</a>',
        
        //"191"=>'<a href="mygroups15_edit.php?'.$parms.'&id='.$id.'">'.$word["vf_change"].'</a>',
        //"192"=>'<a href="mygroups15_edit.php?'.$parms.'">'.$word["newfilt_adv"].'</a>',
        //"193"=>'<a href="mygroups15_edit.php?'.$parms.'">'.$word["newfilt_wiz"].'</a>',

        "2021"=>'<a href="admin_newuser.php?chuser_id='.$chuserid.'">'.$word['au_edit'].'</a>',
        "2022"=>'<a href="admin_newuser.php">'.$word['au_new_user'].'</a>',

        "2011"=>'<a href="admin_megye_varos.php?'.$parms.'">Megye/város/irszám hibák</a>',

        "viral"=>'<a href="form_viral.php?'.$parms.'&form_id='.$fd['id'].'">'.$word['iform_viral'].'</a>',
        "data_forward"=>'<a href="data_forward.php?'.$parms.'&form_id='.$fd['id'].'">'.$word['iform_data_forward'].'</a>',
        "css"=>'<a href="form_css.php?'.$parms.'&form_id='.$fd['id'].'">'.$word['iform_css'].'</a>',
        "elements"=>'<a href="form_elements.php?'.$parms.'&form_id='.$fd['id'].'">'.$word['iform_elements'].'</a>',
        "change"=>'<a href="form_ch.php?'.$parms.'&form_id='.$fd['id'].'">'.$word['iform_change'].'</a>',
        "export"=>'<a href="form_generate.php?'.$parms.'&form_id='.$fd['id'].'">'.$word['iform_export'].'</a>'
        );    

    if (isset($subweare)) {
        $subnav = $subpages[$subweare];
    }

    if ($weare == 21) {
        $midnav = '<a href="threadlist.php?'.$parms.'">'.$word["menu_20"].'</a>';
        $subnav = '<a href="clickthrough.php?'.$parms.'&message_id='.$messid.'">'.$word["menu_21"].'</a>';
    }

    if ($weare == 27) {
        $midnav = '<a href="sms_list.php?'.$parms.'">'.$word["menu_25"].'</a>';
        $subnav = '<a href="sms_threads.php?'.$parms.'&message_id='.$messid.'">'.$word["st_threads"].'</a>';
    }

    if ($weare == 36) {
        $mainnav = '<a href="password.php">'.$word["menu_mydata"].'</a>';
    }
    ?>                    

                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="clear"></div>
                <div class="navigation fl">

                     <a href="<?=$_MX_var->baseUrl;?>" class="bold"><?=$word['menu_select']?></a>
                     <?if(isset($mainnav) || isset($midnav) || isset($subnav)){?><span class="fs9">::</span><?}?>
                     <?if(isset($mainnav)){?> <?=$mainnav;?><?}?>
                     <?if(isset($midnav)){?> <span class="fs9">&gt;</span> <?=$midnav;?><?}?>
                     <?if(isset($subnav)){?> <span class="fs9">&gt;</span> <?=$subnav;?><?}?>

                </div>
                <div class="clear"></div>

                <div class="gray_border">
                    <div class="bceee">
    <?
    $row_beg="<td class=bottominactive>\n";
    $row_end="</td>\n";
    $row_sb="<td class=bottomactive>\n";
    $row_se="</td>";

    ($_MX_var->test_version=="yes")?$TESTADDON="":$TESTADDON="and p.test='no'";

    //echo "<div style='border-color:#000; border-style:solid; border-width:1px 0;'>";
    echo "</div>\n";	
    flush();

    include "help.php";
endif; // _MX_iframe && _MX_popup
?>
