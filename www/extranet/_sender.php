<?

class MxSender 
{  
    // time window for send in seconds
    var $styles=array(
        "background-color:#ddd;",
        "background-color:#fff;",
        "background-color:#fff; font-weight:bold;",
        "background-color:#fff; font-weight:bold; color:#f30; ",
        "background-color:#f30; font-weight:bold; color:#fff; "
    );
    var $styles2=array(
        "background-color:#ddd;",
        "background-color:#fff;",
        "background-color:#fff;",
        "background-color:#fff;",
        "background-color:#fff;",
    );
    var $next="";

    function MxSender() { }

    // $id is an id from sender_timer table
    function GetStatus($id,$mode) {

        global $_MX_var,$word;

        $id=intval($id);
        
        $q="select active,stype,last_error,status,sdays,sdate,stime,last_sent,smonthday,smod,
            unix_timestamp(last_sent) as last,
            unix_timestamp(now()) as snow,
            unix_timestamp(sdate) as sdatestamp,
            time_to_sec(curtime()) as cnow,
            time_to_sec(concat(stime,':00')) as cdatestamp,
            to_days(now()) as moddays,
            dayofmonth(now()) as dofm,
            month(now()) as monow
            from sender_timer where id='$id'";
        $res=mysql_query($q);
        if (!($res && mysql_num_rows($res))) {
            return -1;
        }
        //$word["day0"]="vasďż˝nap"
        $scedule="";
        $k=mysql_fetch_array($res);
        if ($k["status"]=="processing") {
            $send_window = 5 * 3600; // sending the mails may last very long, shout only in extreme cases 
                                     // (and started sendings are monitored more closely by other means anyway)
        }
        elseif ($k["status"]=="prepared") {
            $send_window = 60;   // should be more than enough for the sender engine to start the immediate send
        }
        else {
            $send_window = 1020; // queued message, allow 15+2 minutes for it to start before saying permanent error
        }
        if ($k["stype"]=="single" || $k["stype"]=="now") {
            $oldmess=$k["snow"]-$k["sdatestamp"] > $send_window;
            $scedule=$k["sdate"];
        }
        elseif ($k["stype"]=="cyclical") {
            $day=date("w");
            $oldmess=!(substr($k["sdays"],$day,1)=="X" && $k["cnow"]-$k["cdatestamp"] < $send_window && $k["cnow"]>$k["cdatestamp"]);
            $start=$day;
            if (substr($k["sdays"],$day,1)=="X" && $k["cnow"]>$k["cdatestamp"]+120) {
                $start=$day+1;
            }
            for ($i=$start; $i<$start+7; $i++) {
                $ind=$i;
                if ($ind>=7) { $ind-=7; }
                if (empty($scedule) && substr($k["sdays"],$ind,1)=="X") {
                    if ($ind==$day && $start==$day) {
                        $dayname=$word["today"];
                    }
                    else {
                        $dayname=$word["day$ind"];
                    }
                    $scedule="$dayname $k[stime]";
                    if (!ereg("^0000",$k["last_sent"])) {
                        $scedule.=" ($word[last_sent]: $k[last_sent])";
                    }
                }
            }
        }
        elseif ($k["stype"]=="havonta") {
            $oldmess=!($k["dofm"]==$k["smonthday"] && $k["cnow"]-$k["cdatestamp"] < $send_window && $k["cnow"]>$k["cdatestamp"]);
            $month=$k["monow"];
            if ($k["dofm"]>$k["smonthday"] || $k["dofm"]==$k["smonthday"] && $k["cnow"]>$k["cdatestamp"]+120) {
                $month=$month==12?1:$month+1;
            }
            $scedule=$word["month$month"]." $k[smonthday]., $k[stime]";
            if (!ereg("^0000",$k["last_sent"])) {
                $scedule.=" ($word[last_sent]: $k[last_sent])";
            }
        }
        elseif ($k["stype"]=="2hetente" || $k["stype"]=="3hetente") {
            $modulo=$k["stype"]=="2hetente"?14:21;
            $nowmod=$k["moddays"]%$modulo;
            $today=$k["smod"]==$nowmod;
            $oldmess=!($today && $k["cnow"]-$k["cdatestamp"] < $send_window && $k["cnow"]>$k["cdatestamp"]);
            if ($today) {
                $next=($k["cnow"]>$k["cdatestamp"]+120)?$modulo:0; 
            }
            else {
                $next=($nowmod<$k["smod"])?$k["smod"]-$nowmod:$k["smod"]-$nowmod+$modulo;
            }
            $explain=$next?"$next nap múlva":"ma";
            $scedule="$explain, $k[stime]";
            $this->next=$scedule;
            if (!ereg("^0000",$k["last_sent"])) {
                $scedule.=" ($word[last_sent]: $k[last_sent])";
            }
        }
        if ($k["status"]=="queued") {
            if (strlen($k["last_error"])) {
                if ($oldmess) {
					if ($mode=="archive") {
						$text="$word[permanent_error_unsent]: $k[last_error]";
						$important=1;
					}
					else {
						$text="$word[sceduled_to_send]: $scedule";
                    	$important=1;
					}
                }
                else {
                    $text="$word[temporary_error_unsent]: $k[last_error]";
                    $important=2;
                }
            }
            elseif ($oldmess) {
                if ($k["stype"]=="single") {
                    $text="$word[single_unsent]: $scedule";
                    $important=3;
                }
                else {
                    $text="$word[sceduled_to_send]: $scedule";
                    $important=1;
                }
            }
            else {
                $text="$word[sceduled_to_send]: $scedule";
                $important=1;
            }
        }
        elseif ($k["status"]=="processing" || $k["status"]=="prepared") {   // immediate sends are set initially as 'prepared'
            if (strlen($k["last_error"])) {
                if ($oldmess) {
                    $text="$word[permanent_severe_error_unsent]: $k[last_error]";
                    $important=4;
                }
                else {
                    $text="$word[temporary_error_retrying]";
                    $important=2;
                }
            }
            elseif ($oldmess) {
                $text="$word[permanent_severe_error_unsent]";
                $important=4;
            }
            else {
                $text="$word[sending_message]";
                $important=2;
            }
        }
        elseif (($k["stype"]=="single" || $k["stype"]=="now") && $k["status"]=="ready") {
            $text="$word[sent_message]";
            $important=0;
        }
        else {
            $text="$word[permanent_severe_error_unsent]";
            $important=4;
        }
        $ret=array($text,$this->styles["$important"],$this->styles2["$important"],$this->next);
        return $ret;
    }
}

/*
single:
s1. queued
    s1a. to be sent in the future
    s1b. sending date in past more than 15 min -> erroneous data, either problem with setup or in engine
    s1c. there is en error message
        s1c1. last error message within 15 min => retrying soon.
        s1c2. last error message is old, PERMANENT error
s2. processing
    s2a. no error message - the mailing is being sent
    s2b. there is an error message.
        s2b1. last error message within 15 min => retrying now.
        s2b2. last error message is old, BUG, the sending process died
s3. ready - message has been sent, no problem.


cyclical: [always display date of the last send]
c1. queued
    c1a. to be sent in the future
    c1b. there is en error message
        c1b1. last error message within 15 min => retrying soon.
        c1b2. last error message is old, PERMANENT error
c2. processing
    c2a. no error message - the mailing is being sent
    c2b. there is an error message.
        c2b1. last error message within 15 min => retrying now.
        c2b2. last error message is old, BUG, the sending process died
*/

