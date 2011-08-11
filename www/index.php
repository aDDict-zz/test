<?php

//include "extranet/main.php";

/*
if(!isset($_COOKIE['sajto']))
{
	setcookie("sajto", "ok", time()+360000, "/");
	$sajtokozlemeny="1";
}
*/
session_start();
################# config #############################

$_MX_public_database=1;
include('extranet/auth.php');



if(!empty($_GET["loginstatus"])) {
   header("location: $_MX_var->baseUrl");
   exit;
}

$_homedir=$_MX_var->publicBaseDir;
include($_MX_var->publicBaseDir.'/include/_functions.php');

######################################################

header('Content-Type: text/html; charset=UTF-8');


$pagetmp=explode("/",$_SERVER["REQUEST_URI"]);

if(isset($_POST['page'])) $page=$_POST['page'];
else{
	$page=$pagetmp[1];
}

if($page!="szuresek" && $page!="public")
  include($_MX_var->publicBaseDir.'/include/_header.php');


switch($page)
{
	case 'public':
	    header("HTTP/1.1 301 Moved Permanently" );
            $url = "http://www.maxima.hu";
            for ($i=2;$i<count($pagetmp);$i++) {
                $url .= "/$pagetmp[$i]";
            }
	    header("Location: $url");
	    exit;
	break;
	case 'szuresek':
		include($_MX_var->publicBaseDir.'/include/_szuresek.php');
	break;
	case 'mediaajanlat':
		include($_MX_var->publicBaseDir.'/include/_mediaajanlat.php');
	break;
	case 'impresszum':
		include($_MX_var->publicBaseDir.'/include/_impresszum.php');
	break;
	case 'oldalterkep':
		include($_MX_var->publicBaseDir.'/include/_oldalterkep.php');
	break;
	case 'ugyfeleink':
		include($_MX_var->publicBaseDir.'/include/_referencia_slider.php');
		include($_MX_var->publicBaseDir.'/include/_ugyfeleink.php');
	break;
	case 'referenciak':
		include($_MX_var->publicBaseDir.'/include/_referenciak.php');
	break;
	case "kapcsolat":
		include($_MX_var->publicBaseDir.'/include/_referencia_slider.php');
		if(isset($pagetmp[2]) && $pagetmp[2]=="ajanlatkeres")
			include($_MX_var->publicBaseDir.'/include/_kapcsolat_ajanlatkeres.php');
		else
			include($_MX_var->publicBaseDir.'/include/_kapcsolat.php');
	break;
	case 'szotar':
		include($_MX_var->publicBaseDir.'/include/_referencia_slider.php');
		include($_MX_var->publicBaseDir.'/include/_szotar.php');
	break;
	case 'adatkezeles':
		include($_MX_var->publicBaseDir.'/include/_referencia_slider.php');
		include($_MX_var->publicBaseDir.'/include/_adatkezeles.php');
	break;
	case 'szolgaltatasaink':
		include($_MX_var->publicBaseDir.'/include/_referencia_slider.php');

		if(isset($pagetmp[2]) && is_file($_MX_var->publicBaseDir.'/include/_szolgaltatasaink_'.$pagetmp[2].'.php'))
			include($_MX_var->publicBaseDir.'/include/_szolgaltatasaink_'.$pagetmp[2].'.php');
		else
			include($_MX_var->publicBaseDir.'/include/_szolgaltatasaink_email-marketing.php');
	break;
	default:
		include($_MX_var->publicBaseDir.'/include/_referencia_slider.php');
		include($_MX_var->publicBaseDir.'/include/_index.php');
	break;
}

if($page!="szuresek")
  include($_MX_var->publicBaseDir.'/include/_footer.php');

mysql_close();
?>
