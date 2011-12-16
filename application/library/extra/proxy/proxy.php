<?php
//          FILE: proxy.php
//
// LAST MODIFIED: 2006-03-23
//
//        AUTHOR: Troy Wolf <troy@troywolf.com>
//
//   DESCRIPTION: Allow scripts to request content they otherwise may not be
//                able to. For example, AJAX (XmlHttpRequest) requests from a
//                client script are only allowed to make requests to the same
//                host that the script is served from. This is to prevent
//                "cross-domain" scripting. With proxy.php, the javascript
//                client can pass the requested URL in and get back the
//                response from the external server. 
//
//         USAGE: "proxy_url" required parameter. For example:
//                http://www.mydomain.com/proxy.php?proxy_url=http://www.yahoo.com
//

// proxy.php requires Troy's class_http. http://www.troywolf.com/articles
// Alter the path according to your environment.
require_once("class_http.php"); 

if ($_GET["GetApi"]) {
	$proxy_url = "http://192.168.1.202:8080/frontend/GetAPI";
	//uncomment @ home
	//$proxy_url = "http://localhost:8080/frontend/GetAPI";
} else {
 	$proxy_url = "http://192.168.1.202:8080/frontend/service";
	//uncomment @ home 
	//$proxy_url = "http://localhost:8080/frontend/service";
	
	//echo count($_POST).'-'.strlen($HTTP_RAW_POST_DATA);
	//return;
}

// Instantiate the http object used to make the web requests.
// More info about this object at www.troywolf.com/articles
if (!$h = new http()) {
    header("HTTP/1.0 501 Script Error");
    echo "proxy.php failed trying to initialize the http object";
    exit();
}

//'start=".$start."&limit=".$limit."&sort=".$sort."&dir=".$dir.(isset($locale)?"&locale=".$locale:"").(isset($query)?"&query=".$query:"").(isset($fields)?"&fields=".$fields:"").($q!=""?"&q=".$q:"")."&action=".$action."&callback=".$callback."&prettyprint=1&query=";'

/*

$_POST["start"] =0;
$_POST["limit"] =10;
$_POST["q"] ='{class":"category"}';
$_POST["action"] ='eaMetaAutocomplete';
$_POST["prettyprint"] ='1';


*/



$h->url = $proxy_url . '?' . $_SERVER['QUERY_STRING'];
$h->postvars = $_POST;
$h->rawpost = $HTTP_RAW_POST_DATA;
if (!$h->fetch($h->url)) {
    header("HTTP/1.0 501 Script Error");
    echo "proxy.php had an error attempting to query the url";
    exit();
}

// Forward the headers to the client.
$ary_headers = split("\n", $h->header);
foreach($ary_headers as $hdr) { header(str_replace("Path=/frontend", "Path=/", $hdr)); }


// Send the response body to the client.
//if ($_GET["GetApi"]) 
echo $h->body;
//else 
 //echo substr($h->body,0,strlen($h->body)-1).',head:"'.str_replace("\r","",str_replace("\n",",",$h->header)).'"}';
?>
