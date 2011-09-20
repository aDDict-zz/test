<?php

error_reporting(E_ERROR);


#function getHttpReq($ip, $route $method, $header) {

#  $fp = fsockopen($ip, 80, $errno, $errstr, 30);
#  if (!$fp) {
#      return "$errstr ($errno)<br />\n";
#  } else {
#      $out = "GET {$route} HTTP/1.1\r\n";
#      $out .= "Host: tr.affiliate.hu\r\n";
#      $out .= "Connection: Close\r\n\r\n";
#      fwrite($fp, $out);
#      while (!feof($fp)) {
#          echo fgets($fp, 128);
#      }
#      fclose($fp);
#  }
#}

class HttpReq{
  
  public function __construct($ip, $route, $header) {
    $this->ip       = $ip;
    $this->route    = $route;
    $this->header   = $header;
  }
  
  public function request($method) {
    $result = "bad request";
    switch($method) {
      case "get":
        $result = $this->get();
      break;
      case "post":
        $result = $this->post();
      break;
    }
    return $result;
  }
  
  private function socketOpen() {
    return fsockopen($this->ip, 80, $errno, $errstr);
  }
  
  private function setHeader($method) {
    $out  = "";
    $crlf = "\r\n";
    switch($method) {
      case "get":
        $out .= "GET {$this->route} HTTP/1.0\r\n";
      break;
      case "post":
        $out .= "POST {$this->route} HTTP/1.0\r\n";
      break;
    }
    foreach($this->header as $k => $v) {
      $out .= "{$k}: {$v}\r\n";
    }
    return $out."Connection: Close\r\n\r\n";
  }
  
  private function get() {
    $header = $this->setHeader("get");
    $fp     = $this->socketOpen();
    $out    = "";
    if($fp) { //echo $header;
      stream_set_timeout($fp, 0, 1000);
      fwrite($fp, $header);
      while (!feof($fp)) { 
          $out .= fgets($fp, 512);
      }
      fclose($fp);
    }
    return $out;
  }
  
  private function post() {
    $header = $this->setHeader("get");
    $fp     = $this->socketOpen();
    $out    = "";
    if($fp) {
      stream_set_timeout($fp, 0, 3000);
      fwrite($fp, $header);
      while (!feof($fp)) {
          $out .= fgets($fp, 512);
      }
      fclose($fp);
    }
    return $out;
  }
  
}

#function http_request( 
#                        $verb = 'GET',             /* HTTP Request Method (GET and POST supported) */ 
#                        $ip,                       /* Target IP/Hostname */ 
#                        $port = 80,                /* Target TCP port */ 
#                        $uri = '/',                /* Target URI */ 
#                        $getdata = array(),        /* HTTP GET Data ie. array('var1' => 'val1', 'var2' => 'val2') */ 
#                        $postdata = array(),       /* HTTP POST Data ie. array('var1' => 'val1', 'var2' => 'val2') */ 
#                        $cookie = array(),         /* HTTP Cookie Data ie. array('var1' => 'val1', 'var2' => 'val2') */ 
#                        $custom_headers = array(), /* Custom HTTP headers ie. array('Referer: http://localhost/ */ 
#                        $timeout = 1000,           /* Socket timeout in milliseconds */ 
#                        $req_hdr = false,          /* Include HTTP request headers */ 
#                        $res_hdr = false           /* Include HTTP response headers */ 
#                    ) 
#{ 
#    $ret = ''; 
#    $verb = strtoupper($verb); 
#    $cookie_str = ''; 
#    $getdata_str = count($getdata) ? '?' : ''; 
#    $postdata_str = ''; 

#    foreach ($getdata as $k => $v) 
#                $getdata_str .= urlencode($k) .'='. urlencode($v) . '&'; 

#    foreach ($postdata as $k => $v) 
#        $postdata_str .= urlencode($k) .'='. urlencode($v) .'&'; 

#    foreach ($cookie as $k => $v) 
#        $cookie_str .= urlencode($k) .'='. urlencode($v) .'; '; 

#    $crlf = "\r\n"; 
#    $req = $verb .' '. $uri . $getdata_str .' HTTP/1.1' . $crlf; 
#    $req .= 'Host: '. $ip . $crlf; 
#    $req .= 'User-Agent: Mozilla/5.0 Firefox/3.6.12' . $crlf; 
#    $req .= 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8' . $crlf; 
#    $req .= 'Accept-Language: en-us,en;q=0.5' . $crlf; 
#    $req .= 'Accept-Encoding: deflate' . $crlf; 
#    $req .= 'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7' . $crlf; 
#    
#    foreach ($custom_headers as $k => $v) 
#        $req .= $k .': '. $v . $crlf; 
#        
#    if (!empty($cookie_str)) 
#        $req .= 'Cookie: '. substr($cookie_str, 0, -2) . $crlf; 
#        
#    if ($verb == 'POST' && !empty($postdata_str)) 
#    { 
#        $postdata_str = substr($postdata_str, 0, -1); 
#        $req .= 'Content-Type: application/x-www-form-urlencoded' . $crlf; 
#        $req .= 'Content-Length: '. strlen($postdata_str) . $crlf . $crlf; 
#        $req .= $postdata_str; 
#    } 
#    else $req .= $crlf; 
#    
#    if ($req_hdr) 
#        $ret .= $req; 
#    
#    
#    echo $req;
#    
#    if (($fp = @fsockopen($ip, $port, $errno, $errstr)) == false) 
#        return "Error $errno: $errstr\n"; 
#    
#    stream_set_timeout($fp, 0, $timeout * 1000); 
#    
#    fputs($fp, $req); 
#    while ($line = fgets($fp)) $ret .= $line; 
#    fclose($fp); 
#    
#    if (!$res_hdr) 
#        $ret = substr($ret, strpos($ret, "\r\n\r\n") + 4); 
#    
#    return $ret; 
#} 

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
