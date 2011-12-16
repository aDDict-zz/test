<?
header('Content-Type: text/html; charset=utf-8');

$start=$_POST["start"];

if(empty($start))$start=$_GET["start"];

if(empty($start))$start=0;

$limit=$_POST["limit"];

if(empty($limit))$limit=$_GET["limit"];

if(empty($limit))$limit=10;

$sort=$_REQUEST["sort"];

$dir=$_REQUEST["dir"];

if (isset($_REQUEST["locale"])) {
    $locale = $_REQUEST["locale"];
}

$query=$_REQUEST["query"];
if (isset($query) && get_magic_quotes_gpc()) {
    $query = stripslashes($query);
}

$fields=$_REQUEST["fields"];
if (isset($fields) && get_magic_quotes_gpc()) {
    $fields = stripslashes($fields);
}

/*$q=utf8_decode(html_entity_decode($_POST["q"]));
if(empty($q))$q=utf8_decode(html_entity_decode($_GET["q"]));
if(empty($q))$q="";*/
$q=/*utf8_decode(*/html_entity_decode($_REQUEST["q"])/*)*/;
if (isset($q) && get_magic_quotes_gpc()) {
    $q = stripslashes($q);
}

$action=utf8_decode($_REQUEST["action"]);

/*if (get_magic_quotes_gpc()) {
    $action = stripslashes($action);
}*/

/*if(empty($action))$action=$_GET["action"];
if(empty($action))$action="";*/

$callback=utf8_decode($_POST["callback"]);
if(empty($callback))$callback=$_GET["callback"];
/*if (get_magic_quotes_gpc()) {
    $callback = stripslashes($callback);
}*/

$fn=$callback;

$rqk=$_SERVER["HTTP_COOKIE"];
//$rqk=substr($rqk,0,strpos($rqk,"\n")-1);

//$exec= "wget \"http://192.168.1.200:8100/frontend/service?start=".$start."&limit=".$limit."&q=".$q."&action=".$action."&callback=".$callback."&prettyprint=1\" --save-headers --header \"Cookie: ".$rqk."\" -q -O -";
//$exec= 'wget "http://192.168.1.200/frontend/service?start='.$start.'&limit='.$limit.'&sort='.$sort.'&dir='.$dir.(isset($query)?'&query='.$query:'').(isset($fields)?'&fields='.$fields:'').($q!=''?'&q='.$q:'').'&action='.$action.'&callback='.$callback.'&prettyprint=1" --save-headers --header "Cookie: '.$rqk.'" -q -O -';
//$exec= "wget 'http://192.168.1.200/frontend/service?start=".$start."&limit=".$limit."&sort=".$sort."&dir=".$dir.(isset($locale)?"&locale=".$locale:"").(isset($query)?"&query=".$query:"").(isset($fields)?"&fields=".$fields:"").($q!=""?"&q=".$q:"")."&action=".$action."&callback=".$callback."&prettyprint=1' --save-headers --header 'Cookie: ".$rqk."' -q -O -";
//:8100



require_once("proxy.php"); 
die();

$exec= "http://192.168.1.200/frontend/service?start=".$start."&limit=".$limit."&sort=".$sort."&dir=".$dir.(isset($locale)?"&locale=".$locale:"").(isset($query)?"&query=".$query:"").(isset($fields)?"&fields=".$fields:"").($q!=""?"&q=".$q:"")."&action=".$action."&callback=".$callback."&prettyprint=1&query=";

echo $exec . "___";

//echo "proxy.php?proxy_url=" .$exec;

//print_r ($_REQUEST);
//print $exec;
//exit($exec);

//$jstr=shell_exec($exec);

$jsttr=str_replace("\r","",$jstr)." ";

$pos=strpos($jsttr,"\n\n");

$headers=substr($jsttr,0,$pos+1);

$content=substr($jsttr,$pos+2,-1);

$cpos=stripos($headers,"set-cookie:");

if($cpos!=false)
{
	$cook=substr($headers,$cpos);
	$cook=substr($cook,0,stripos($cook,";"));
	header($cook);
}

//$jsttr=str_replace("\n","",$jstr)." ";

print $content;

/*

print "\n---cook---\n";

print $cook."\n---exec---\n";

print $exec."\n---jsttr---\n";

print $jsttr."\n---rqk---\n";

print $rqk."\n";

print "\n";

*/



?>
