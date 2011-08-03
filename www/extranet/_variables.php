<?php

include "main.php";

$_MX_var = new MxVars();
$_MX_var->SetupVersion();

class MxVars {

    private $versions = array(
        "maxima"=>array("live"=>array(
                            "mainDomain"=>"localhost",
                            "publicBaseUrl"=>"http://localhost/maxima",
                            "publicBaseDir"=>"/srv/http/maxima",
                        ),
                        "both"=>array(
                            "main_table_border_color"=>"#606060", // used in print templates, unfortunately it's not in css...
                            "application_instance_name"=>"Maxima 2.0"
                        )
                       ),
        "kc"=>array("live"=>array(
                            "mainDomain"=>"www.kutatocentrum.hu",
                            "publicBaseUrl"=>"http://www.kutatocentrum.hu/",
                            "publicBaseDir"=>"/var/www/kutatocentrum.hu/www/www",
                        ),
                        "both"=>array(
                            "main_table_border_color"=>"#336699", 
                            "application_instance_name"=>"Kutatócentrum"
                        )
                       ),
        "lightmail"=>array("live"=>array(
                            "mainDomain"=>"www.emailmarketing.hu",
                            "altDomains"=>array("lightmail.hu","www.lightmail.hu","emailmarketing.hu","beta.lightmail.hu","alfa.lightmail.hu"),
                            "publicBaseUrl"=>"http://www.emailmarketing.hu/",
                            "publicBaseDir"=>"/var/www/lightmail.hu"
                        ),
                        "both"=>array(
                            "main_table_border_color"=>"#ffa477", 
                            "application_instance_name"=>"LightMail"
                        )
                       )
    );

    // Database
    public $db = array("main"=>array("host"=>"localhost","user"=>"root","password"=>"v","name"=>"maxima","name_public"=>"maxima"),
                       "bounce"=>array("host"=>"localhost","user"=>"root","password"=>"v","name"=>"maxima","name_public"=>"maxima"));  //array("host"=>"sensdfder6.maxima.hu","user"=>"eximl","password"=>"Df46By1","name"=>"eximlogs"));
    public $db_handle = false;
    public $bounce_db_handle = false;

    // Language
    public $supported_langs=array("hu","en");
    public $langs=array("Magyar","English");
    public $default_lang="hu";

    // Miscellaneous
    public $filter_cache_expire=14;     // filter cache expire time in days, set to -1 to turn off filter caching.
    public $sms_delimiter="~";

    function MxVars() { }

    function db_connect($db,$public=0) {
   
        $d =& $this->db["$db"]; //print_r($d); die();
        if (!$d["handle"] = mysql_connect($d["host"], $d["user"], $d["password"])) { 
            print "Error 1";
            exit;
        } //echo $public?$d["name_public"]:$d["name"];
        if (!$succDB = mysql_select_db($public?$d["name_public"]:$d["name"], $d["handle"])) {
            print "Error 2";
            exit;
        }
        $this->sql("set names utf8",$db);
    }

    function sql($query,$db="main") {

        $d =& $this->db["$db"];
        if (empty($d["handle"])) {
            $this->db_connect($db);
        }
        if (empty($d["handle"])) {
            return false;
        }
        return mysql_query($query,$d["handle"]);
    }

    function SetupVersion() {

        if (preg_match("/^(office.manufaktura.rs|192.168.250.1)$/",$_SERVER["HTTP_HOST"])) {
            $this->test_version="yes";
            $index="test";
            $this->possible_superadmin=array(3,76699,80257,80284);  // users that may become superadmins
            $this->export_email_report=array("tbjanos@manufaktura.rs","tbjanos@gmail.com"); // send emails about user data reports to these addresses
            $this->db["bounce"] = $this->db["main"];
            $subtest = "tbjanos";
            $subversion = "beta";
            if (preg_match("'^/~([^/]+)/maxima_(alfa|beta)'",$_SERVER["REQUEST_URI"],$regs)) {
                $subtest = $regs[1];
                $subversion = $regs[2];
            }
            $this->mainDomain = "office.manufaktura.rs";
            $this->publicBaseUrl = "http://office.manufaktura.rs/~$subtest/maxima_$subversion";
            $this->publicBaseDir = "/home/$subtest/projects/maxima_public/branches/$subversion/www";
            $this->uploaddir = "/home/tbjanos/projects/maxima_public/branches/$subversion/www/extranet/upload";
            $this->uploadurl = "http://office.manufaktura.rs/~tbjanos/maxima_$subversion/extranet/upload";
            $this->engine_basedir = "/home/$subtest/projects/maxima_engine/branches/$subversion"; 
        }
        else {
            $this->test_version="no";
            $index="live";
            $this->possible_superadmin=array(80262,59446);  // users that may become superadmins
	        $this->export_email_report=array("trentin.tamas@hirek.hu","fialka.krisztina@hirek.hu"); // send emails about user data reports to these addresses
            $this->uploaddir = "/var/www/maxima/www/www/extranet/upload";
            $this->uploadurl = "http://www.maxima.hu/extranet/upload";
            $this->engine_basedir="/var/www/maxima_engine/www"; 
        }

        foreach ($this->versions as $instance=>$data) {
            if (!isset($this->application_instance) 
                    || $index=="live" && $_SERVER["HTTP_HOST"]==$data["live"]["mainDomain"]
                    || $index=="live" && $_SERVER["HTTP_HOST"]=="kutatocentrum.hu" && $data["live"]["mainDomain"]=="www.kutatocentrum.hu") {
                $this->setVersionData($instance,$data,$index);
            }
            if ($index=="live" && isset($data["live"]["altDomains"])) {
                foreach ($data["live"]["altDomains"] as $alt) {
                    if ($this->getRootDomain($_SERVER["HTTP_HOST"])==$alt) {
                        $this->setVersionData($instance,$data,$index);
                    }
                }
            }
        }
        $this->baseUrl = $this->publicBaseUrl . "/extranet";
        $this->baseDir = $this->publicBaseDir . "/extranet";

        // External applications
        $this->filter_engine = "$this->engine_basedir/filter_engine";
        $this->sender_engine = "$this->engine_basedir/sender_engine";
        $this->sms_engine = "$this->engine_basedir/dsmsrequest";
        $this->validator_engine = "$this->engine_basedir/validate";
        $this->unsub_ppos_script = "$this->engine_basedir/unsub_ppos"; // (perl) script for unsubscribers

        // Directories
        $this->form_imagepath = "$this->engine_basedir/var/form_images/";  // dir for form images and temp dir for form zips
        $this->member_import_temp_dir = "$this->engine_basedir/var/export_csv/";
        $this->logfile_path = "/home/vvv/sites/maxima/log.sql";  // user activity log
        $this->error_log_dir = "/home/vvv/sites/maxima"; // send error logs
        $this->sms_status_log_dir = "/home/vvv/sites/maxima"; // send error logs
        $this->send_log_root = array("/home/vvv/sites/maxima","/home/vvv/sites/maxima"); // logs of the queueing engine and the sender
        $this->spooldirs = array("sender2"=>"/usr/local/maximas2/spool");
    }

    function getRootDomain($domain) {

        if (preg_match("/\.([^.]+\.[^.]+)$/",$domain,$regs)) {
            return $regs[1];
        }
        return $domain;
    }

    function setVersionData($instance,$data,$index) {

        $this->application_instance=$instance;
        if (isset($data["$index"])) {
            foreach ($data["$index"] as $key=>$val) {
                $this->$key=$val;
            }
        }
        foreach ($data["both"] as $key=>$val) {
            $this->$key=$val;
        }
    }
}
?>
