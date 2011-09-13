<?
require_once "_variables.php";

$_MX_testversion = "";
$_MX_testsite = "";

if (preg_match("'(alfa|beta).(maxima.hu|lightmail.hu|kutatocentrum.hu|researchcenter.hu)'",$_SERVER["HTTP_HOST"],$regs)) {
    $_MX_testversion = $regs[1];
    $_MX_testsite = $regs[2];
}

if (!isset($from_offline_cgi) && $_SERVER["HTTP_HOST"]!=$_MX_var->mainDomain && empty($_MX_testversion)) {
    header("HTTP/1.1 301 Moved Permanently" );
    header("Location: http://$_MX_var->mainDomain$_SERVER[REQUEST_URI]");
    exit;
}
if (!empty($_MX_testversion)) {
    $_MX_var->publicBaseUrl = "http://" . $_SERVER["HTTP_HOST"];
    if ($_MX_testsite == "maxima.hu") {
        $_MX_var->publicBaseDir="/var/www/maxima/$_MX_testversion/www";
    }
    if ($_MX_testsite == "lighmail.hu") {
        $_MX_var->publicBaseDir="/var/www/www.lightmail.hu/$_MX_testversion/svn";
    }
}

if (!isset($_MX_public_database)) {
    $_MX_public_database = 0;
}
$_MX_var->db_connect("main",$_MX_public_database);

session_start();
header("Expires: Mon, 5 Sep 1991 12:49:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT"); 
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

$ints = array("id","group_id","base_id","delete_id","form_id","message_id","multiid","weare");
foreach ($ints as $intset) {
    if (isset($_REQUEST["$intset"])) {
        $$intset = intval($_REQUEST["$intset"]);
    }
    else {
        $$intset = 0;
    }
}

$language = select_lang();
$plusdot = "";

// As the project is being prepared to be moved to php5, we should use this function to get data from GET/POST
function get_http($var,$default,$sql_escape=1) {

    // see comment for rewrute rule params below.
    if (isset($_GET["$var"])) {
        return rawurldecode(slasher($_GET["$var"],$sql_escape));
    }
    elseif (isset($_POST["$var"])) {
        return slasher($_POST["$var"],$sql_escape);
    }
    else {
        return $default;
    }
}

function get_cookie($var,$sql_escape=1) {
    if (isset($_COOKIE["$var"])) {
        return slasher($_COOKIE["$var"],$sql_escape);
    }
    else {
        return null;
    }
}

function hsc($string, $noquote=0)
{
   // a version of htmlspecialchars, the goal is not to change '&' to '&amp;' so
   // preserving the other substitusions of htmlspecialchars.
   
   if ($noquote) {
       $trans = array(">"=>"&gt;","<"=>"&lt;");
   }
   else {
       $trans = array(">"=>"&gt;","<"=>"&lt;","\""=>"&quot;","'"=>"&#039;","&"=>"&amp;");
   }
   return strtr($string,$trans);
}

function slasher($string,$add=1) {
    # this function is ment for data coming throurh get/post/cookie.
    # returns $string with or without slashes, taking into consideration
    # setting in php configuration.
    # $add=-1  --  without slashes, with htmlspecialchars for html
    # $add=0   --  without slashes
    # $add=1   --  with slashes
    $s=get_magic_quotes_gpc();
    switch ($add) {
        case -1: { if ($s)
                      return htmlspecialchars(stripslashes($string));
                   else
                      return htmlspecialchars($string); 
                   break; }
        case 0:  { if ($s)
                      return stripslashes($string);
                   else
                      return $string; 
                   break; }
        case 1:  { if ($s)
                      return $string;
                   else
                      return addslashes($string); 
                   break; }
        default: return $string;
    }
}

function select_lang () {
    global $_MX_var;

    if (!empty($_GET["language"]) && valid_lang($_GET["language"])) // directly set
        $language = $_GET["language"];
    elseif (isset($_COOKIE["maxima_lang"]) && valid_lang($_COOKIE["maxima_lang"])) // cookie
        $language = $_COOKIE["maxima_lang"];
    else
        $language=$_MX_var->default_lang;
    setcookie('maxima_lang', $language, time()+31536000);
    return $language;
}

function valid_lang ($language) {
	global $_MX_var;
	return (in_array($language,$_MX_var->supported_langs) && @is_dir("./lang/$language"));
}
?>
