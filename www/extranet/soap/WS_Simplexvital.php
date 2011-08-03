<?php

include('../auth.php');

class WS_Simplexvital {  

    private $client_ids = array("2882f539aa7e296c7479d1d2e4715595"=>array("accepted_ips"=>array("all","192.168.250.254","93.92.248.121"),
                                                                          "name"=>"simplexvital.hu",
                                                                          "allowed_actions"=>array("Subscribe","Validate","GetUserData","UpdateUserData","Order")),
                                "2e0919d6f12e9ed7221e4a36cd6a0e37"=>array("accepted_ips"=>array("all"),
                                                                          "name"=>"maxima.hu",
                                                                          "allowed_actions"=>array("Synchronize","Subscribe","Validate","UpdateUserData")));
    private $errors = array("invalid_client"        =>-11,
                            "invalid_client_ip"     =>-12,
                            "password_too_short"    =>-14,
                            "email_already_exists"  =>-16,
                            "register_unsuccessful" =>-17,
                            "order_unsuccessful"  =>-18,
                            "invalid_client_action" =>-19,
                            "invalid_email"         =>-20,
                            "invalid_validator_code"=>-21,
                            "validation_unsuccessful"=>-22,
                            "update_unsuccessful"   =>-23
                    ); 
    private $userparams = array();    

    private function GetUserParams() {
        
        $res = mysql_query("select d.variable_name,d.variable_type from demog d,vip_demog vd where d.id=vd.demog_id and vd.group_id=1585");
        while ($k=mysql_fetch_array($res)) {
            $this->userparams["$k[variable_name]"] = $k["variable_type"];
        }
    }

    /** 
     * Synchronizes user data - tries to update, if the user does not exist then subscribes and validates
     * 
     * @param string    $client_id  Client id, used to identify the client
     * @param mixed     $userdata
     * @return string   email on success <0 otherwise
     */  
	public function Synchronize($client_id,$userdata) {

        $this->log("start","synchronize");
        if (($ret = $this->check_client($client_id,"Synchronize"))<0) {
            return $ret;
        }
        $this->log("success");  // end logging here, the functions below will use their own logs
        $result = $this->Subscribe($client_id,$userdata);
        if (in_array($result,array(-15,-16))) {  // email already exists, try to update
	        return $this->UpdateUserData($client_id,$userdata);
        }
        elseif (substr($result,0,1)!="-") {
	        return $this->Validate($client_id,$result);
        }
        else {
            return $result;
        }
    }

    /** 
     * Subscribes new user
     * 
     * @param string    $client_id  Client id, used to identify the client
     * @param mixed     $userdata
     * @return string   Validator code on success <0 otherwise
     */  
	public function Subscribe($client_id,$userdata) {

        $this->log("start","subscribe");
        if (($ret = $this->check_client($client_id,"Subscribe"))<0) {
            return $ret;
        }
        if (empty($userdata["email"]) || !preg_match("/@/",$userdata["email"])) {
            return $this->error("invalid_email");
        }
        $res = mysql_query("select id from users_simplexvital where ui_email='" . mysql_escape_string($userdata["email"]) . "'");
        if ($res && mysql_num_rows($res)) {
            return $this->error("email_already_exists");
        }
        if (mb_strlen($userdata["jelszo"],"UTF-8")<8) {
            return $this->error("password_too_short");
        }
        $validator_code = md5(rand() . "PIkula,$userdata[email]");
        $sql="insert into users_simplexvital set validated='no',robinson='no',bounced='no',tstamp=now(),data_changed=now(),date=now(),validator_code='$validator_code'";
        $this->GetUserParams();
        foreach ($this->userparams as $param=>$type) {
            if ($type=="enum") {
                $data = "," . preg_replace("/[^0-9]/","",$userdata["$param"]) . ",";
            }
            else {
                $data = mysql_escape_string($userdata["$param"]);
            }
            $sql .= ",ui_$param='$data'";
        }
        if ($res=mysql_query($sql)) {
            $this->log("success");
            return $validator_code;
        }
        else {
            return $this->error("register_unsuccessful");
        }
	}

    /** 
     * Updates $email's user data - if a parameter is empty it's not updated
     * 
     * @param string    $client_id  Client id, used to identify the client
     * @param mixed     $userdata
     * @return int      1 on success <0 otherwise
     */  
	public function UpdateUserData($client_id,$userdata) {

        $this->log("start","updateuserdata");
        if (($ret = $this->check_client($client_id,"UpdateUserData"))<0) {
            return $ret;
        }
        $email=mysql_escape_string($userdata["email"]);
        $sql = "select id from users_simplexvital where ui_email='$email'";
        $res = mysql_query($sql);
        if ($res && mysql_num_rows($res)) {
            $k = mysql_fetch_array($res);
            $sql = "update users_simplexvital set tstamp=now(),data_changed=now()";
            $this->GetUserParams();
            foreach ($this->userparams as $param=>$type) {
                if (isset($userdata["$param"])) {
                    if ($type=="enum") {
                        $data = "," . preg_replace("/[^0-9]/","",$userdata["$param"]) . ",";
                    }
                    else {
                        $data = mysql_escape_string($userdata["$param"]);
                    }
                    if ($param!="email") {
                        $sql .= ",ui_$param='$data'";
                    }
                }
            }
            if (!$res=mysql_query("$sql where id='$k[id]'")) {
                return $this->error("update_unsuccessful");
            }
            else {
                $this->log("success");
                return 1;
            }
        }
        else {
            return $this->error("invalid_email");
        }
	}

    /** 
     * Gets user data
     * 
     * @param string    $client_id  Client id, used to identify the client
     * @param string    $email 
     * @return mixed    associative array of user data if the user exists (validated or not), <0 otherwise
     */  
	public function GetUserData($client_id,$email) {

        $this->log("start","getuserdata");
        if (($ret = $this->check_client($client_id,"GetUserData"))<0) {
            return $ret;
        }
        $email=mysql_escape_string($email);
        $sql = "select * from users_simplexvital where ui_email='$email'";
        $res = mysql_query($sql);
        if ($res && mysql_num_rows($res)) {
            return mysql_fetch_array($res);
        }
        else {
            return $this->error("invalid_email");
        }
    }

    /** 
     * Validates user
     * 
     * @param string    $client_id  Client id, used to identify the client
     * @param string    $validator_code String returned by Subscribe()
     * @return string   email if the validation was successful, <0 otherwise
     */  
	public function Validate($client_id,$validator_code) {

        $this->log("start","validate");
        if (($ret = $this->check_client($client_id,"Validate"))<0) {
            return $ret;
        }
        if (strlen($validator_code)<16) {
            return $this->error("invalid_validator_code");
        }
        $validator_code=mysql_escape_string($validator_code);
        $sql = "select id,ui_email from users_simplexvital where validator_code='$validator_code'";
        $res = mysql_query($sql);
        if ($res && mysql_num_rows($res)) {
            $k=mysql_fetch_array($res);
            $sql = "update users_simplexvital set validated='yes',validated_date=now() where ui_email='" . mysql_escape_string($k["ui_email"]) . "'";
            if (!$res=mysql_query($sql)) {
                return $this->error("validation_unsuccessful");
            }
            else {
                $this->log("success");
                return $k["ui_email"];
            }
        }
        else {
            return $this->error("invalid_validator_code");
        }
    }

    /** 
     * Called upon a successful order
     * 
     * @param string    $client_id   Client id, used to identify the client
     * @param string    $email       Email
     * @param integer   $timestamp   Unix timestamp of the order   
     * @param float     $quantity    Paid quantity   
     * @param integer   $product_id  Product id   
     * @return integer >=1 on success (unique for all orders) <0 otherwise
     */  
	public function Order($client_id,$email,$timestamp,$quantity,$product_id) {

        $this->log("start","order");
        if (($ret = $this->check_client($client_id,"Order"))<0) {
            return $ret;
        }
        $email = mysql_escape_string($email);
        $sql = "select id from users_simplexvital where ui_email='$email'";
        $res = mysql_query($sql);
        if ($res && mysql_num_rows($res)) {
            $user_id=mysql_result($res,0,0);
        }
        else {
            return $this->error("invalid_email");
        }
        $timestamp = mysql_escape_string($timestamp);
        $quantity = floatval($quantity);
        $sql = "insert into users_simplexvital_order set dateadd=now(),logfile='$this->logfile',timestamp='$timestamp',user_id='$user_id',quantity='$quantity',product_id='$product_id'";
        if (!$res=mysql_query($sql)) {
            return $this->error("order_unsuccessful");
        }
        $order_id = mysql_insert_id();
        if (empty($order_id)) {
            return $this->error("order_unsuccessful");
        }
        $sql = "update users_simplexvital set ui_simplexvital_termek_1='$product_id', ui_simplexvital_datum_1=now(), ui_simplexvital_mennyiseg_1='$quantity' where ui_email='$email'";
        if (!$res=mysql_query($sql)) {
            return $this->error("order_unsuccessful");
        }
        $this->log("success");
        return $order_id;
	}

    private function check_client($client_id,$action) {

        if (strlen($client_id)<16 || !isset($this->client_ids["$client_id"])) {
            return $this->error("invalid_client");
        }
        $cli =& $this->client_ids["$client_id"];
        if (!in_array($action,$cli["allowed_actions"])) {
            return $this->error("invalid_client_action");
        }
        if (!in_array("all",$cli["accepted_ips"]) && !in_array($_SERVER["REMOTE_ADDR"],$cli["accepted_ips"])) {
            return $this->error("invalid_client_ip");
        }
        return 1;
    }

    private function error($name,$additional="") {

        $ret = $this->errors["$name"];
        $this->log("error","$ret - $name $additional");
        return $ret; 
    }

    private function log($event,$param="") {

        if ($event=="start") {
            $this->logtype = $param;
        }
        if (in_array($this->logtype,array("getuserdata"))) {  // not logged at all
            return;
        }
        if ($event=="start") {
            $this->logfile = "/var/spool/subscribe/soap/server/sxv_$param/" . time() . "-" . mt_rand(10000,99999);
            if ($fp = fopen("$this->logfile.notok","w")) {
                fwrite($fp,@file_get_contents('php://input'));
                fwrite($fp,"\n$_SERVER[REMOTE_ADDR]\n");
                fclose($fp);
            }
        }
        elseif ($event=="success") {
            if (in_array($this->logtype,array("subscribe","updateuserdata","validate","order","synchronize"))) { // log file remains even if successful
                rename("$this->logfile.notok","$this->logfile.ok");
            }
            else {
                unlink("$this->logfile.notok");
            }
        }
        else {
            if ($fp = fopen("$this->logfile.notok","a")) {
                fwrite($fp,"$event: $param\n");
                fclose($fp);
            }
        }
    }
}
?>
