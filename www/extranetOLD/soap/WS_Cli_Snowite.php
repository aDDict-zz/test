<?php

class WS_Cli_Snowite {  

    private $developerID = "P5QD1F";
    private $privateKey = "fGJcR8jrQKjzQUx4n02Dzp9b";
    private $urls = array("http://ws-hirek.snowite.fr","http://api.preview.snowite.fr/webservices/hirek","http://office.manufaktura.rs/maxima/soap/snowite");
    private $testmode = 0;
    private $output = "json";

    // array keys: userid, first_name, last_name, email, login, password, expiration_date) {
    public function addUser($params) {

        $password = "";
        $url = "point=0&subscription=x";
        foreach ($params as $var=>$val) {
            if ($var == "password") {
                $password = $val;
            }
            elseif (!is_numeric($var)) {
                $url .= "&$var=" . urlencode($val);
            }
        }
        return $this->execute("adduser",$url,$password);
    }

    // array keys: userid, first_name, last_name, email, expiration_date) {
    public function updateUser($params) {

        $password = "";
        $url = "point=0&subscription=x";
        foreach ($params as $var=>$val) {
            if ($var == "password") {
                $password = $val;
            }
            elseif (!is_numeric($var)) {
                $url .= "&$var=" . urlencode($val);
            }
        }
        return $this->execute("updateuser",$url,$password);
    }

    public function deleteUser($user_id) {

        return $this->execute("deleteuser","userid=$user_id");
    }

    public function getDetails($user_id) {

        return $this->execute("getdetails","userid=$user_id");
    }

    private function execute($action,$params,$password="") {

        $this->ch = curl_init();
        $result = json_decode($this->call("security/initsession.php?developerid=$this->developerID&output=$this->output"));
        $randomString = $result->randomstring;  // randomString a.k.a. sessionKey
        $sessionid = $result->sessionid;
        $cryptedRandomString = $this->crypt_3DES_CBC_withPadding($randomString, $this->privateKey);
		$call = "security/startsession.php?sessionid=$sessionid&cryptedstring=" . $this->textToHex($cryptedRandomString['cryptedString']) . "&iv=" . $this->textToHex($cryptedRandomString['iv']) . "&output=$this->output";
        $result = json_decode($this->call($call));
//print "*$result->sessionkey*$result->iv<br>";
        $accessKey = $result->accesskey;
        $sessionKey = $this->decrypt_3DES_CBC_withPadding($this->hexToText($result->sessionkey), $this->hexToText($result->iv), $this->privateKey);
//print "*$sessionKey<br>";
        $passwordparms = "";
        if (!empty($password)) {
            $cryptedPassword = $this->crypt_3DES_CBC_withPadding($password, $sessionKey);
            $passwordparms = "&cryptedHexPassword=" . $this->textToHex($cryptedPassword["cryptedString"]) . "&hexPasswordIv=" . $this->textToHex($cryptedPassword["iv"]);
        }
        $call = "user/$action.php?sessionid=$sessionid&accesskey=$accessKey&$params&output=$this->output$passwordparms";
//print "$call<br>";
        $result = $this->call($call);
        curl_close($this->ch);    
        return $result;

/*
Password encription:
I propose to use 3DES-CBC encryption to transmit password, with different key for each session.
The idea is when you connect to the platform (initSession() / startSession()) you receive already sessionKey. This session key is the one you should use to encrypt password. Notice than this sessionKey is transmited encrypted with the privateKey.
This system is a little bit complicated, but with it, encryption use different value for each session, and the encryption used (3DES) is hard enough to resist during a session time.
The crypting process is the same than the one used during the startSession(), but you should use the sessionkey rather than the privatekey. It implies also than you replace all password fields with cryptedHexPassword and hexPasswordIv This applies for :
    * User.addUser()
    * User.updateUser()
In the API, when I receive the encrypted password, I decrypted it, create the sha1 hash, and store it in the database. For your side, if you store the sha1 or md5 hash in your database, you may crypt and call WebService before this.
*/
    }

    private function call($path) {

        $url=$this->urls[$this->testmode];
        if ($this->testmode==2) {
            curl_setopt($this->ch, CURLOPT_USERPWD, 'maxima:wiHar91');
        }
//print "<br>$url/$path<br>";
        curl_setopt($this->ch, CURLOPT_URL, "$url/$path");
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, 10);
        //if (($data = curl_exec($this->ch)) === false) {
        $data = curl_exec($this->ch);
//print "$data<br>-----------------------------------------------------------------------------------------<br>";
        return $data;
    }

    private function textToHex($text) {

        $result = '';
        $length = strlen($text);
        for($i=0; $i<$length; $i++)
            $result .= str_pad(base_convert(ord($text[$i]), 10, 16), 2, '0', STR_PAD_LEFT);
        return $result;
    }

    private function hexToText($hexa) {

        $result = '';
        if (strlen($hexa) % 2 == 1)
            $hexa = '0'.$hexa;
        $length = strlen($hexa);
        for($i=0; $i<$length; $i+=2)
            $result .= chr(base_convert(substr($hexa, $i, 2), 16, 10));
        return $result;
    }

    private function crypt_3DES_CBC_withPadding($inputString, $inputKey) {

        $blockSize = mcrypt_get_block_size(MCRYPT_3DES, MCRYPT_MODE_CBC);
        $string = $this->addPadding($inputString, $blockSize);
        $keySize = mcrypt_module_get_algo_key_size(MCRYPT_3DES);
        $key = substr($inputKey , 0, $keySize);
        $iv_taille = mcrypt_get_iv_size(MCRYPT_3DES, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($iv_taille, MCRYPT_RAND);
        $cryptedString = mcrypt_encrypt(MCRYPT_3DES, $key, $string , MCRYPT_MODE_CBC, $iv);
        return array('cryptedString'=>$cryptedString, 'iv'=>$iv);
    }

    private function decrypt_3DES_CBC_withPadding($cryptedString, $iv, $inputKey) {

        $keySize = mcrypt_module_get_algo_key_size(MCRYPT_3DES);
        $key = substr($inputKey , 0, $keySize);
        $string = mcrypt_decrypt(MCRYPT_3DES, $key, $cryptedString, MCRYPT_MODE_CBC, $iv);
        $stringWithoutPadding = $this->deletePadding($string);
        return $stringWithoutPadding;
    }

    private function addPadding($text, $blocksize) {

        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    private function deletePadding($text) {

        $pad = ord($text{strlen($text)-1});
        if ($pad > strlen($text)) return false;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false;
        return substr($text, 0, -1 * $pad);
    }
}
?>
