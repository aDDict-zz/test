<?php
	session_start();
	header("Content-type:text/html; charset=UTF-8");
	include_once('libs/Smarty.class.php');
    include_once('inc/_variables.php');
	include_once('inc/smarty.config.php');
	include_once('inc/page_functions.php');
	include_once('inc/db_prop.inc.php');
	$smarty->cache = 0; 
	//die( $smarty->cache );
	
  $connection = mysql_connect($host, $user, $psw) or die(mysql_error()); 
	$db = mysql_select_db($data_base, $connection) or die(mysql_error());
	mysql_query("Set names 'UTF8'") or die(mysql_error());
	
	$smarty = new Smarty; 
    $smarty->assign("var", $_HI_var); //deb( $_HI_var,1 );

    $news_id = isset($_REQUEST["news_id"]) ? intval($_REQUEST["news_id"]) : 0;
    $smarty->assign("news_id", $news_id);
    if (isset($_REQUEST["from"])) $smarty->assign("newslink_from", $_REQUEST["from"]);
    if (!$news_id) {
        if(!empty($_COOKIE['Hirek'])){
            $user = explode(':', $_COOKIE['Hirek']);
            if(is_numeric($user[0]) && $user[0]!='' && $user[1]!=''){			
                $_SESSION['logged'] = $_COOKIE['Hirek'];
            }
        }
        if(!isset($_SESSION["logged"]) || $_SESSION['logged']==''){
            $_SESSION['logged'] = '-1:';
            //header('Location: register.php');
            //exit();
        }
        list($__user_id, $__user_email) = explode(":", $_SESSION['logged']);
        if ($__user_id != -1) {
            setcookie('Hirek', $__user_id.":".$__user_email, time()+(60*60*24*3600));
        }
    } else {
        $__user_id = -1;
        $__user_email = '';
    }
	
    $__user_id = intval($__user_id);
    $__user_email = mysql_real_escape_string($__user_email);
	
	$smarty->assign('__user_id', $__user_id);
	$smarty->assign('__user_email', $__user_email);
    if (!empty($_REQUEST["regalert"]) && $_REQUEST["regalert"] == 1) {
        $smarty->assign("onloadscript", "alert('Kedves Felhasználónk!\\n\\nKöszönjük regisztrációját!\\nJelentkezése igazolásaként egy emailt küldtünk Önnek.');");
    }

    $pages = get_pages();
    $page_id = isset($_REQUEST["page_id"]) ? $_REQUEST["page_id"] : 1;
    if (isset($_REQUEST["page_url"])) {
        $page_url = trim($_REQUEST["page_url"]);
        foreach($pages as $page) {
            if ($page["page_url"] == $page_url) {
                $page_id = $page["page_id"];
            }
        }
    }
    $smarty->assign("page_id", $page_id);  $smarty->assign("page_id", 1);   //deb( get_pages() );

    $smarty->assign("pages", get_pages());
    
    if (isset($pages[$page_id])) {
        $smarty->assign("PAGE_TITLE", $pages[$page_id]["page_title"]);
        $smarty->assign("PAGE_DESCRIPTION", $pages[$page_id]["page_description"]);
        $smarty->assign("PAGE_KEYWORDS", $pages[$page_id]["page_keywords"]);
    }

    $valasz = isset($_REQUEST["valasz"]) ? $_REQUEST["valasz"] : 0;
    if ($valasz) {
        $smarty->assign("onloadscript", "valaszFlash();");
    }
    $smarty->assign("valasz", $valasz);
    $smarty->assign("maxi_hirekson", maxi_hirekson());
//$ff = $smarty->display('index.html');  die( "SS" . $ff );
	$smarty->display('index.html');	
	//$smarty->display('index_sorry.html');	
    
    function maxi_hirekson() { //
        $maxi = "";
        if (!isset($_COOKIE['maxi_hirekson'])) {
            $maxi = '
            <div id="layer_content" style="position: absolute; top: 200px; left: 50%; margin-left: -155px; z-index: 15;">
            <div id="layerDiv">
            <script type="text/javascript" src="http://ad.hirekmedia.hu/ad.php?adslot=j-1617"></script>
            </div>
            </div>
            ';
            setcookie('maxi_hirekson',1,time()+365*24*3600);
        }
        return $maxi;
    }
    
    
	
?>
