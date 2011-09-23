<?php
error_reporting(E_ALL);

// Variables to be set at install.
// 'enable_track_vars' should be yes.
// The system is independent from magic_quotes or register_globals settings, in other words set them as you like. 
require_once("obj_variables.php");
error_reporting(0); //DELETE this line in live versions! (or better, set it to 0.)
ini_set("display_errors", true);


class HirekVar {  
    var $object_name="hirek";
    var $variables_file = "/home/vvv/sites/hirekhu/xml/_variables.xml";
    var $debug=1;        // if 1, prints sensitive debug data to the screen. MUST be 0 in live version.
        
    function HirekVar($file="", $object="") { 
        if ($file != "") {
            $this->variables_file = $file;
        }
        if ($object != "") {
            $this->object_name = $object;
        }
        
        $_GetVar = new GetVariables($this->variables_file, $this->object_name);
        $variables = $_GetVar->variables; 
        foreach ($variables as $key => $val) { 
            $this->{$key} = $val;
//            print "Variable: $key = $val\n<br>";
        }
        if (preg_match("/^\/(|[^\/]+|(.*\/)[^\/]*)$/", $_SERVER["REQUEST_URI"], $m)) {
            $host = isset($_SERVER["HTTP_X_FORWARDED_HOST"]) ? $_SERVER["HTTP_X_FORWARDED_HOST"] : $_SERVER["HTTP_HOST"];
            $this->baseurl = "http://$host/". (isset($m[2]) ? $m[2] : "");
        }
//        print $this->basedir."<br>\n";
        
    }
}

function deb($stuff, $die = 0){
  if($_COOKIE["Hirek"] == "5931:robthot@gmail.com") {
    switch($die){
      case 1:
        die(print_r($stuff));
      break;
      case 0:
        print_r($stuff);
      break;
    }
  }
}

?>
