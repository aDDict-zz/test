<?

class MxPassword 
{  
    
    var $from_admin=0;
    var $email="";
    var $user_id=0;
    var $password=0;

    function MxPassword($user_id,$email,$password,$from_admin=0) {

        $this->user_id=$user_id;
        $this->email=$email;
        $this->password=$password;
        $this->from_admin=$from_admin;
    }
    
    function Check() {

        if (strlen($this->password)<8) {
            return "A jelszó legalább 8 karakter hosszú kell hogy legyen.";
        }
        $quality=0;
        if (ereg("[a-z]",$this->password)) {
            $quality++;
        }
        if (ereg("[A-Z]",$this->password)) {
            $quality++;
        }
        if (ereg("[0-9]",$this->password)) {
            $quality++;
        }
        if (ereg("[^A-Za-z0-9]",$this->password)) {
            $quality++;
        }
        if ($quality<2) {
            return "A jelszóban kis/nagybetűk, számok vagy egyéb karakterek is kellenek hogy legyenek.";
        }
        for ($i=0;$i<strlen($this->email)-3;$i++) {
            if (strpos($this->password,substr($this->email,$i,4))!==false) {
                return "A jelszóban nem lehet felhasználni az email cím részeit.";
            }
        }
        if ($this->user_id) {
            $res=mysql_query("select password from last_passwords where user_id='$this->user_id'");
            if ($res && mysql_num_rows($res)) {
                while ($k=mysql_fetch_array($res)) {
                    if ($k["password"]==$this->password) {
                        return "A régi jelszavakat nem lehet újra felhasználni.";
                    }
                }
            }
        }
        return "accepted";
    }

    function TrackLast($user_id=0) {

        if (!$user_id) {
            $user_id=$this->user_id;
        }
        if ($user_id) {
            mysql_query("insert into last_passwords (user_id,password,dateadd) 
                         values ($user_id,'" . mysql_escape_string($this->password) . "',now())");
        }
    }
}

