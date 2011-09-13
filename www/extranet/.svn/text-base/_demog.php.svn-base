<?

class MxDemog 
{  
    var $dtypes=array("text","date","enum","phone","email","number","matrix","nick");
    var $params=array("group_id"=>0,"perpage"=>50,"sname"=>"","sdesc"=>"","dtype"=>"","dgroup"=>"sel","sort"=>0,"off"=>0);
    
    function MxDemog() {

        global $_MX_var,$_GET,$_POST;

        foreach ($this->params as $parm=>$pdef) {
            if (isset($_GET["$parm"])) { $GLOBALS["$parm"]=slasher($_GET["$parm"],0); }
            elseif (isset($_POST["$parm"])) { $GLOBALS["$parm"]=slasher($_POST["$parm"],0); }
            else { $GLOBALS["$parm"]=$pdef; }
            $GLOBALS["s$parm"]=mysql_escape_string($GLOBALS["$parm"]);
        }
    }
    
    function GetParams($hidden=0,$exclude=array()) {

        $pl=array();
        foreach ($this->params as $parm=>$default) {
            if ($GLOBALS["$parm"]!=$default && !in_array($parm,$exclude)) {
                if ($hidden) {
                    $pl[]="<input type='hidden' name='$parm' value='". htmlspecialchars($GLOBALS["$parm"]) ."'>";
                }
                else {
                    $pl[]="$parm=". rawurlencode($GLOBALS["$parm"]);
                }
            }
        }
        $hidden?$glue="":$glue="&";
        return (implode($glue,$pl));
    }

    function Add ($group_id,$new=0,$source="post",$data=array()) {

        global $word,$_MX_superadmin,$_MX_change_variables;

        $error="";
        $addenums=array();
        $params=array("question","variable_name","variable_type","copy_demog_id","demog_id","grlist","multiselect","multi_append","code");
        if ($source=="post") {
            foreach ($params as $p) {
                $$p=get_http($p,"",0);
            }
        }
        elseif ($source=="csv") {
            $copy_demog_id=0;
            $grlist=$data[0];
            $new=(empty($data[1])?1:0);
            $variable_name=$data[2];
            $code=$data[3];
            $question=$data[4];
            $tdat=explode("|",$data[5]);
            $variable_type=$tdat[0];
            if (in_array("multi",$tdat)) {
                $multiselect="yes";
            }
            if (in_array("append",$tdat)) {
                $multi_append="yes";
            }
            if (!$new) {
                $res=mysql_query("select id,groups from demog where variable_name='" . mysql_escape_string($variable_name) . "'");
                if ($res && mysql_num_rows($res)) {
                    $demog_id=mysql_result($res,0,0);
                    if (!(mysql_result($res,0,1)=="$group_id" || $_MX_superadmin || $_MX_change_variables)) {
                        $error .= "A $variable_name demog info nem változtatható<br>";
                    }
                }
                else {
                    $error .= "Nem létezik $variable_name demog info, nem lehet módosítani<br>";
                }
            }
            for ($i=6;$i<count($data);$i+=2) {
                if (ereg("^(reszkerdes|ertek)_(.+)$",$data[$i],$ev) && (ereg("^id_(.+)$",$data[$i+1],$cd) || empty($data[$i+1]))) {
                    $addenums[]=array("enum_option"=>trim($ev[2]),
                                      "code"=>(empty($data[$i+1]) ? "" : trim($cd[1])),
                                      "vertical"=>($ev[1]=="reszkerdes" ? "yes" : "no"),
                                      "optdesc"=>"");
                }
                elseif (!empty($data[$i]) || !empty($data[$i+1])) {
                    $error .= "Hibás enum érték megadás (" . ($i+1) . "., " . ($i+2) . ". oszlopok)<br>";
                }
            }
        }
        foreach ($params as $p) {
            ${"s$p"}=mysql_escape_string($$p);
        }

        if (!in_array($variable_type,$this->dtypes)) {
            if ($source=="csv") {
                $error.="Nemlétező változó típus: '$variable_type'<br>";
            }
            else {
                $variable_type=$this->dtypes[0];
            }
        }
        if (empty($question)) {
            $error.="$word[vd_qerror]<br>";
        }
        if ($new && !ereg("^[a-z][a-z0-9_]*$",$variable_name)) {
            $error.="$word[vd_vnerror]<br>";
        }
        elseif ($new) {
            $r2=mysql_query("select id from demog where variable_name='$variable_name'");
            if ($r2 && mysql_num_rows($r2)) {
                $error.="$word[vd_vnxerror]<br>";
            }
        }
        if ($multiselect!="yes") {
            $multiselect="no";
        }
        if ($multiselect=="yes" && $multi_append=="yes") {
            $multi_append="yes";
        }
        else {
            $multi_append="no";
        }
        $groupsa = array();
        if (!$_MX_superadmin) {
            $groupsa[]= $group_id;
        }
        $titles=explode(",",$grlist);
        while (list(,$title)=each($titles)) {
            if (!empty($title)) {
                $title=addslashes($title);
                $resgr=mysql_query("select id from groups where title='$title'");
                if ($resgr && mysql_num_rows($resgr)) {
                    if (!in_array(mysql_result($resgr,0,0),$groupsa)) {
                        $groupsa[]= mysql_result($resgr,0,0);
                    }
                }
                else {
                    $error.="$title $word[ed_nogroup]<br>";   
                }
            }
        }
        $groups=implode(",",$groupsa);
        if (empty($error)) {
            if ($new) {
                $q="insert into demog (question,variable_name,variable_type,dateadd,groups,multiselect,multi_append,tstamp,code) values 
                                      ('$squestion','$variable_name','$variable_type',now(),'$groups','$multiselect','$multi_append',now(),'$scode')";
                mysql_query($q);
                $demog_id=mysql_insert_id();
                logger($q,$group_id,"","","demog");
                if ($copy_demog_id && $demog_id) {
                    if ($variable_type=="enum" || $variable_type=="matrix") {
                        $cfiltadd=$variable_type=="matrix"?"":" and vertical='no'";
                        $r9=mysql_query("select * from demog_enumvals where demog_id='$copy_demog_id' and deleted='no'$cfiltadd");
                        logger($q,$group_id,"","demog_id=$copy_demog_id","demog_enumvals");
                        if ($r9 && mysql_num_rows($r9)) {
                            while ($k9=mysql_fetch_array($r9)) {
                                $addenums[]=array("enum_option"=>$k9["enum_option"],"code"=>$k9["code"],"vertical"=>$k9["vertical"],"optdesc"=>$k9["optdesc"]);
                            }
                        }
                    }
                    $copy_demog_id=0;
                }
            }          
            else {
                $q="update demog set code='$scode',question='$squestion',multiselect='$multiselect',multi_append='$multi_append', groups='$groups',tstamp=now() where id='$demog_id'";
                mysql_query($q);
                logger($q,$group_id,"","demog_id=$demog_id","demog");                         
            }
            if (count($addenums)) {
                foreach ($addenums as $ae) {
                    foreach ($ae as $aer=>$ael) {
                        $ae["$aer"]=mysql_escape_string($ael);
                    }
                    $already = mysql_query("select id from demog_enumvals where demog_id='$demog_id' and enum_option='$ae[enum_option]'");
                    if ($already) {
                        if (mysql_num_rows($already)) {
                            mysql_query("update demog_enumvals set tstamp=now(),optdesc='$ae[optdesc]',vertical='$ae[vertical]',code='$ae[code]' 
                                         where demog_id='$demog_id' and enum_option='$ae[enum_option]'");
                        }
                        else {
                            mysql_query("insert into demog_enumvals (demog_id,enum_option,tstamp,optdesc,vertical,code)
                                         values ('$demog_id','$ae[enum_option]',now(),'$ae[optdesc]','$ae[vertical]','$ae[code]')");
                        }
                    }
                }
            }
            $new=0;
        }
        return array($demog_id,$error);
    }
}
