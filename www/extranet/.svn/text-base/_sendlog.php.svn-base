<?

class MxSendLog 
{  
    var $message_id=0;
    var $group="";
    var $log_file=array();

    function MxSendLog($group,$message_id) {

        global $_MX_var;

        $this->group=$group;
        $this->message_id=intval($message_id);
        $res=mysql_query("select m.send_plan,m.spool,g.title from messages m,groups g where m.id='$this->message_id' and m.group_id=g.id");
        if ($res && mysql_num_rows($res)) {
            $k=mysql_fetch_array($res);
            if (ereg("^robin",$k["title"])) {
                $_MX_var->send_log_root[0]="/usr/local/robin/verify";
            }
            if ($k["send_plan"]>0) {
                $this->send_plan=$k["send_plan"];
            }
            if (isset($_MX_var->spooldirs["$k[spool]"])) {
                $_MX_var->send_log_root[1]=$_MX_var->spooldirs["$k[spool]"] . "/logs";
            }
        }
        $log0=$_MX_var->send_log_root[0]."/$this->group.$this->message_id";
        $this->log_file[0]="$log0.ok";
        if (is_file("$log0.notok")) {
            $this->log_file[0]="$log0.notok";
        }
        $logmod=0;
        if ($this->message_id==intval($this->message_id)) {
            $logmod=$this->message_id%32;
        }
        $this->log_file[1]=$_MX_var->send_log_root[1]."/$logmod/$this->message_id";
    }

    function GetMsgStatus($what=array(-1,0,1)) {

        global $_MX_var,$word;

        $ret=array(-1,-1,-1);
//return $ret;
        if (in_array(-1,$what)) {
            $ret[0]=$this->GetRcptNum();
        }
        for ($i=0;$i<2;$i++) {
            if (in_array($i,$what)) {
                $ret[$i+1]=$this->SentNum($i);
            }
        }
        $finished=0;
        if ($ret[1]>$ret[0]) { $ret[1]=$ret[0]; }
        if ($ret[2]>$ret[0]) { $ret[2]=$ret[0]; }
#print ("$ret[0] $ret[1] $ret[2]\n");
        if ($ret[0]<=0 || $ret[2]==$ret[0]) {
            $msg="<span class='szoveg'>$word[send_ended]</span>";
            $finished=1;
        }
        else {
            $p1=round(100*$ret[1]/$ret[0])."%";
            $p2=round(100*$ret[2]/$ret[0])."%";
            $msg="<table cellpadding='3' cellspacing='0' border='0'><tr><td align='center'><span class='szoveg'>$p1<br>($ret[1])</span></td><td align='center'><span class='szoveg'>$p2<br>($ret[2])</span></td></tr></table>";
        }
        return "*|$finished|$msg";
    }
    
    function GetRcptNum() {

        if (isset($this->send_plan)) {
            return $this->send_plan;
        }
        $nrow=$this->Shell("grep '^N [0-9]\+ ' " . $this->log_file[0]);
        if (ereg("^N ([0-9]+) ",$nrow,$regs)) {
            return $regs[1];
        }
        return 0;
    }

    function SentNum($level) {

//return 0;

        switch ($level) {
            case 0: $command="grep '^# ' ". $this->log_file[0] ." | wc -l"; break;
            case 1: $command="wc -l " . $this->log_file[1] . ".* | tail -n1"; break;
            default: return 0;
        }
        $res=$this->Shell($command);
        $res=ereg_replace("^[ \t]+","",$res);
        return intval($res);
    }

    function Shell($command) {

#print "$command<br>";
        $res="";
        if ($pp=popen($command,"r")) {
            while ($buff=fgets($pp,25000)) {
                $res.=$buff;
            }
            pclose($pp);
        }
#print "=$res<br>";
        return $res;
    }
}

