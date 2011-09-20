<?  //die( "dsfsdfdsfdsf" );
// this script must have access to maxima database, so that form data and the variables could be verified.
// change this include to direct connect if applicable.
include "auth.php";
require_once "_subscribe.php";
include "common.php";
include "_form.php";


#if( $_POST['data__'] == "301" ) {


##  $header = array(
##    "Host"              => "192.168.0.107",
##    "private_key"       => "5d9d92e300be43a6f47fbe28c41ad215",
##    "User-Agent"        => "Mozilla/5.0 Firefox/3.6.12",
##    "Accept"            => "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
##    "Accept-Language"   => "en-us,en;q=0.5",
##    "Accept-Encoding"   => "deflate",
##    "Content-Type"      => "text/html; charset=iso-8859-1",
##    "Accept-Charset"    => "ISO-8859-1,utf-8;q=0.7,*;q=0.7"
##  );
#  
#  
##  $req = new HttpReq("tr.affiliate.hu","get_security_key.php",$header); // die( print_r( $req ) );
##  $result = $req->request("get"); 

#  $f = fopen("http://tr.affiliate.hu/get_security_key.php?private_key=5d9d92e300be43a6f47fbe28c41ad215", "r"); //die( print_r( $f  ) );
#  $aff_sec_key = fread($f, 1024);
#  fclose($f); 
#  $aff_reg_id = md5( $_POST[ "email" ] );
#  $thisScript = "
#    <script type=\"text/javascript\"><!--//<![CDATA[
#    var aff_campaign_id = 466;
#    var aff_url = location.protocol=='https:'?'https://tr.affiliate.hu':'http://tr.affiliate.hu';
#    var aff_sec_key = '{$aff_sec_key}'; 
#    var aff_reg_id = '{$aff_reg_id}'; 
#    document.write(\"<scri\" + \"pt type='text/javascript' src='\" + aff_url);
#    document.write(\"/aff_reg_js.php\");
#    document.write(\"'><\/scr\" + \"ipt>\");
#  //]]>--></script>
#  "; 
#} else {
#  $thisScript = "";
#} 

$DEBUG=0;
if (0 && ereg("^tbjanos",$_POST["email"])) {
	$DEBUG=1;
}

if ($DEBUG) {
    print "<pre>";
    print_r ($_POST);
    print "</pre>";
    print "<pre>";
    print_r ($_SESSION);
    print "</pre>";
}

// id of the 'from' enum variable, default affiliates can be assigned to its options.
$_MX_from_demog_id=22;

$othergroups=array();
$subscribe_data = array (
    "values"=>array(array("data-charset","utf8")),
    "othergroups"=>array()
);

$form_id=mysql_escape_string($_POST["data__"]);
// new forms will have this hidden variable, old forms are assumed to send data in cp1250,
// convert the old data to utf8 and always send the data further to validate in utf8.
// possible problem: somebody might sent one-byte encoding but with this hidden variable,
// leading to sql errors because of the wrong charset in validate.
if (!(isset($_POST["___charset___"]) && $_POST["___charset___"]=="utf-8") && $form_id<110) {
    foreach ($_POST as $hvar=>$hval) {
        $_POST["$hvar"] = @iconv("cp1250","utf-8//IGNORE",$hval);
    }
}
$specaff = 0;

$form_id=mysql_escape_string($_POST["data__"]);
$res=mysql_query("select * from form where id='$form_id'");
if ($res && mysql_num_rows($res)) {
    $formdata=mysql_fetch_array($res);
    $r8=mysql_query("select title,name from groups where id='$formdata[group_id]'");
    if ($r8 && mysql_num_rows($r8)) {
        strlen(mysql_result($r8,0,1))?$gnreplace=mysql_result($r8,0,1):$gnreplace=mysql_result($r8,0,0);
        $formdata["landing_page"]=eregi_replace("{group}",$gnreplace,$formdata["landing_page"]);
        $formdata["landing_page_inactive"]=eregi_replace("{group}",$gnreplace,$formdata["landing_page_inactive"]);
        $_MX_form = new MxForm($formdata["group_id"],"",0,0); //die( print_r( $_MX_form ) );
        if ($_MX_form->InitForm($form_id)) {
            $social=$_MX_form->SocialNetworkLinks();
            $formdata["landing_page"] = eregi_replace("{SOCIAL_NETWORK}",$social,$formdata["landing_page"]);
            $formdata["landing_page_inactive"] = eregi_replace("{SOCIAL_NETWORK}",$social,$formdata["landing_page_inactive"]);
        }
    }
    if ($formdata["active"]=="yes") {
        $formdata["landing_page"]=eregi_replace("{TITLE}",$formdata["title"],$formdata["landing_page"]);
    }
    else {
        print eregi_replace("{TITLE}",$formdata["title"],$formdata["landing_page_inactive"]);
        mx_end_form("Inactive");
    }
}
else {
    mx_end_form("Error 1: $form_id");
}
$res=mysql_query("select title,unique_col from groups where id='$formdata[group_id]'");
if ($res && mysql_num_rows($res)) {
    $gdata=mysql_fetch_array($res);
}
else {
    print $formdata["landing_page"];
    mx_end_form("Error 2: $formdata[group_id]");
}

if (isset($_POST["megosztotta"]) && ereg("([a-z0-9_]+)===([0-9]+)",$_POST["megosztotta"],$dreg)) {
    $res = mysql_query("select de.* from demog_enumvals de,demog d where de.id='$dreg[2]' and d.variable_name='$dreg[1]' and d.id=de.demog_id");
    if ($res && mysql_num_rows($res)) {
        $cy = mysql_fetch_array($res);
        $res = mysql_query("select de.id from demog_enumvals de,demog d where de.enum_option='" . mysql_escape_string($cy["enum_option"]) . "' and d.variable_name='megosztotta' and d.id=de.demog_id");
        if ($res) {
            if (mysql_num_rows($res)) {
                $_POST["megosztotta"] = mysql_result($res,0,0);
            }
            else {
                mysql_query("insert into demog_enumvals (demog_id,enum_option,tstamp,optdesc,vertical,code) values (5698,
                        '" . mysql_escape_string($cy["enum_option"]) . "',now(),'" . mysql_escape_string($cy["optdesc"]) . "','$cy[vertical]','" . mysql_escape_string($cy["code"]) . "')");
                $_POST["megosztotta"] = mysql_insert_id();
            }
        }
    }
}

$affiliates=array();
$res=mysql_query("select fee.* from form_element fe, form_element_enumvals fee where 
                  fe.form_id='$form_id' and fe.demog_id='$_MX_from_demog_id' and fee.form_element_id=fe.id");
if ($res && mysql_num_rows($res)) {
    while ($k=mysql_fetch_array($res)) {
        $affiliates["$k[demog_enumvals_id]"]=$k["default_aff"];
    }
}

$group_subscribe_id="";
if ($formdata['multigroup']) {
    $gtitle = $formdata['multigroup'];
    $group_subscribe_table="multi";
} 
else {
    $gtitle = $gdata['title'];
    $group_subscribe_table="groups";
}
$rgs=mysql_query("select subscribe_id from $group_subscribe_table where title='$gtitle'");
if ($rgs && mysql_num_rows($rgs)) {
    $group_subscribe_id=mysql_result($rgs,0,0);
}

// track which fields are filled in. If it is set so, if all the fields are filled in in a given box
// we shall subscribe the user to some additional groups.
// values set here are also used for automatic emails below
$filled=array();
    
if ($form_id) {
    $r2=mysql_query("select fe.widget,d.variable_name,d.id,d.variable_type,d.multiselect,fe.multi_append,fe.id feid
                     from form_element fe left join demog d on fe.demog_id=d.id 
                     where fe.form_id='$form_id' and fe.widget not in ('separator','comment') order by fe.page,fe.sortorder");
}
else {
    $r2=mysql_query("select '' as widget,d.variable_name,d.id,d.variable_type,d.multiselect from vip_demog vd,demog d 
                     where vd.group_id='$formdata[group_id]' and vd.demog_id=d.id order by d.id");
    reset($_POST);
    foreach ($_POST as $pvar=>$pvalue) {
        if (ereg("^cim__(.+)$",$pvar,$prg)) {
            $_POST["$prg[1]"]=$pvalue;
        }
        if (ereg("^ceg_cim__(.+)$",$pvar,$prg)) {
            $_POST["$prg[1]"]=$pvalue;
        }
        if (ereg("^tel__(.+)$",$pvar,$prg)) {
            $_POST["$prg[1]"]=$pvalue;
        }
        if (ereg("^mob__(.+)$",$pvar,$prg)) {
            $_POST["$prg[1]"]=$pvalue;
        }        
    }
}

function decryptString($code) {
    $text = "";
    $codeArray = explode(",",base64_decode($code));
    foreach ($codeArray as $digitData) {
        $digitArray = explode("-", $digitData);
        $ascii = hexdec($digitArray[0]) - $digitArray[1];
        $text .= chr($ascii);
    }
    return $text;
}

$type_ref=array();
$captcha_error = false;
$captcha_array = array();
if ($r2 && mysql_num_rows($r2)) {
    while ($k=mysql_fetch_array($r2)) {

        $type_ref["$k[variable_name]"]=$k["variable_type"];

        $variable_name=$k["variable_name"];

        if (empty($k["widget"])) {
            switch ($k["variable_type"]) {
                case "enum": $k["multiselect"]=="yes"?$k["widget"]="checkbox":$k["widget"]="select"; break;
                case "matrix": $k["multiselect"]=="yes"?$k["widget"]="checkbox_matrix":$k["widget"]="radio_matrix"; break;
                case "enum_other": $k["multiselect"]=="yes"?$k["widget"]="checkbox_other":$k["widget"]="radio_other"; break;
                case "date": $k["widget"]="datum";
                default : $k["widget"]="input";  
            }
        }

        if($k['widget']=="captcha") {
            $k["variable_name"] = "captcha__$k[feid]";
            $captcha_array[] = $k['variable_name'];
            if (trim(strtoupper($_POST[$k["variable_name"]])) != trim(decryptString($_SESSION["maxima_captcha_".$k["variable_name"]]))) {
                $captcha_error = true;
            }
        }
        elseif ($k["widget"]=="cim") {
            $cim_input_array=array("hazszam","emelet","ajto","utcanev","utca_nev","utca_tipus");
            foreach ($cim_input_array as $cim_input) {
                if (get_magic_quotes_gpc()) {
                    $value=stripslashes($_POST["cim__$cim_input"]);
                }
                else {
                    $value=$_POST["cim__$cim_input"];
                }
                $subscribe_data["values"][]=array($cim_input,$value);
            }
        }
        elseif ($k["widget"]=="ceg_cim") {
            $ceg_cim_input_array=array("street_name_company","street_type_company","street_number_company","floor_company","door_company");
            foreach ($ceg_cim_input_array as $ceg_cim_input) {
                if (get_magic_quotes_gpc()) {
                    $value=stripslashes($_POST["ceg_cim__$ceg_cim_input"]);
                }
                else {
                    $value=$_POST["ceg_cim__$ceg_cim_input"];
                }
                $subscribe_data["values"][]=array($ceg_cim_input,$value);
            }
        }
        elseif ($k["widget"]=="tel") {
            $tel_input_array=array("tel_korzet","tel_szam");
            foreach ($tel_input_array as $tel_input) {
                if (get_magic_quotes_gpc()) {
                    $value=stripslashes($_POST["tel__$tel_input"]);
                }
                else {
                    $value=$_POST["tel__$tel_input"];
                }
                $subscribe_data["values"][]=array($tel_input,$value);
            }
        }
        elseif ($k["widget"]=="mob") {
            $mob_input_array=array("mobil_korzet","mobil_szam");
            foreach ($mob_input_array as $mob_input) {
                if (get_magic_quotes_gpc()) {
                    $value=stripslashes($_POST["mob__$mob_input"]);
                }
                else {
                    $value=$_POST["mob__$mob_input"];
                }
                $subscribe_data["values"][]=array($mob_input,$value);
            }
        }        
        elseif ($k["variable_type"]=="date") {
            $yname=$variable_name."__y";
            $year=intval($_POST["$yname"]);
            $mname=$variable_name."__m";
            $month=intval($_POST["$mname"]);
            $dname=$variable_name."__d";
            $day=intval($_POST["$dname"]);
            if (($year==0 && $month==0 && $day==0) || !checkdate ($month, $day, $year) || $year<1000 || $year>2999) {
                $value="0000-00-00";
            }
            else {
                $month0=$month<10?"0$month":"$month";
                $day0=$day<10?"0$day":"$day";
                $value="$year-$month0-$day0";
                $filled["$k[id]"]=$value;
            }
            $subscribe_data["values"][]=array($variable_name,$value);
        }
        elseif ($k["variable_type"]=="enum" || $k["variable_type"]=="matrix") {
            $multiapp_sign="";
            if ($k["multiselect"]=="yes") {
                if ($k["multi_append"]!="default") {
                    $multiapp_sign=$k["multi_append"]=="yes"?"+":"-";
                }
            }
            $devals=array();
            $devopts=array();
            $r9=mysql_query("select id,enum_option,vertical from demog_enumvals where demog_id='$k[id]' and deleted='no'");
            if ($r9 && mysql_num_rows($r9)) {
                while ($k9=mysql_fetch_array($r9)) {
                    $devals[]=$k9["id"];
                    $devopts["$k9[id]"]=$k9["enum_option"];
                    $devert["$k9[id]"]=$k9["vertical"];
                }
            }
            if ($k["widget"]=="checkbox" && $k["multiselect"]=="yes") {
                for ($i=0;$i<count($devals);$i++) {
                    $varname=$variable_name."__".$devals[$i];
                    if ($_POST["$varname"]==1) {
                        $subscribe_data["values"][]=array($variable_name,"$devals[$i]$multiapp_sign");
                        if ($k["variable_name"]=="newsletter_inside") {
                            $othergroups[]=$devopts["$devals[$i]"];
                        }
                        $filled["$k[id]"].=",$devals[$i],";
                    }
                }
            }
//checkbox matrix generated: <input type="checkbox" name="zauto2900__2894" value="1" ...
            elseif ($k["widget"]=="checkbox_matrix" && $k["multiselect"]=="yes") {
                for ($i=0;$i<count($devals);$i++) {
                    if ($devert["$devals[$i]"]=="yes") {    // reszkerdesek
                        for ($j=0;$j<count($devals);$j++) {
                            if ($devert["$devals[$j]"]=="no") {     //values
                                $varname=$variable_name.$devals[$i]."__".$devals[$j];
                                if ($_POST["$varname"]==1) {
                                    $subscribe_data["values"][]=array($variable_name,"$devals[$i]m$devals[$j]$multiapp_sign");
                                    $filled["$k[id]"].=",$devals[$i]_$devals[$j],"; // not quite right for matrices, fix this later.
                                }
                            }
                        }
                    }
                }
            }
            elseif ($k["widget"]=="multiselect" && $k["multiselect"]=="yes" && is_array($_POST["$variable_name"])) {
                reset($_POST["$variable_name"]);
                foreach ($_POST["$variable_name"] as $value) {
                    if (in_array($value,$devals)) {
                        $subscribe_data["values"][]=array($variable_name,"$value$multiapp_sign");
                        if ($k["variable_name"]=="newsletter_inside") {
                            $othergroups[]=$devopts["$value"];
                        }
                        $filled["$k[id]"].=",$value,";
                    }
                }
            }
//radio_matrix generated:    <input type="radio" name="zupper2890" value="2882" ...
            elseif ($k["widget"]=="radio_matrix") {
                for ($i=0;$i<count($devals);$i++) {
                    if ($devert["$devals[$i]"]=="yes") {    // reszkerdesek
                        for ($j=0;$j<count($devals);$j++) {
                            if ($devert["$devals[$j]"]=="no" && $_POST["$variable_name$devals[$i]"]==$devals[$j]) {     //values
                                $subscribe_data["values"][]=array($variable_name,"$devals[$i]m$devals[$j]");
                                $filled["$k[id]"].=",$devals[$i]_$devals[$j],"; // not quite right for matrices, fix this later.
                            }
                        }
                    }
                }
            }
            else {
                $_POST["$variable_name"]=trim($_POST["$variable_name"]);
                if (empty($_POST["$variable_name"]) && isset($_POST["___default___$variable_name"])) {
                    $_POST["$variable_name"]=$_POST["___default___$variable_name"];
                }
                if (in_array($_POST["$variable_name"],$devals)) { 
                    $subscribe_data["values"][]=array($variable_name,$_POST["$variable_name"]);
                    if ($k["variable_name"]=="newsletter_inside") {
                        $othergroups[]=$devopts["$_POST[$variable_name]"];
                    }
                    $filled["$k[id]"].=",$_POST[$variable_name],";
                }
            }
        }
        else {
            if (get_magic_quotes_gpc()) {
                $value=stripslashes($_POST["$variable_name"]);
            }
            else {
                $value=$_POST["$variable_name"];
            }
            if (!empty($value)) {
                $filled["$k[id]"]=$value;
            }
            // you have to do this because of the subscribe letter format
            $value=ereg_replace("[\r\n]"," ",$value);
            if ($k["variable_type"]=="number" && !ereg("^-?([0-9]+\.)?[0-9]+$",$value)) {
                $value=0;
            }
            if (!empty($value)) {
                $subscribe_data["values"][]=array($variable_name,$value);
            }
            if ($variable_name == 'from') {
                  $specaff = $affiliates[$value];
            } 
            if ($variable_name == 'email') {
                $email = $value;
            } 
        }
        if ($variable_name == 'from') {
            $spvalue = $_POST["$variable_name"];
            $specaff = $affiliates[$spvalue];
        }
    }
}
$checkarr = array();
foreach ($subscribe_data["values"] as $carr) {
    $checkarr[]=$carr[0];
}
if (isset($_POST["__psv__"])) {
    $psv=explode("|",rawurldecode($_POST["__psv__"]));
    for ($j=0;$j<count($psv);$j+=2) {
        if (isset($psv[$j+1]) && !in_array($psv[$j],$checkarr)) {
            if (isset($type_ref["$psv[$j]"]) && in_array($type_ref["$psv[$j]"],array("enum","matrix"))) {
                    $filled["$psv[$j]"]="";
                    $psvp=explode(",",$psv[$j+1]);
                    foreach ($psvp as $psvpp) {
                        $psvpp=str_replace("_","m",$psvpp);
                        $filled["$psv[$j]"].=",$psvpp,";
                        $subscribe_data["values"][]=array($psv[$j],$psvpp);
                    }
            }
            else {
                //$psv[$j+1] = @iconv("cp1250","utf-8//IGNORE",$psv[$j+1]);
                $psv[$j+1]=str_replace("%u0171","ű",$psv[$j+1]);
                $psv[$j+1]=str_replace("%u0151","ő",$psv[$j+1]);
                $psv[$j+1]=str_replace("%u0170","Ű",$psv[$j+1]);
                $psv[$j+1]=str_replace("%u0150","Ő",$psv[$j+1]);
                $subscribe_data["values"][]=array($psv[$j],$psv[$j+1]);
            }
            if ($psv[$j]=="cid") {
                $_POST["cid"]=$psv[$j+1];
            }
        }
    }
}

$bdebug="";

$enum_widgets=array("checkbox","checkbox_other","radio","radio_other","select","multiselect","radio_matrix","checkbox_matrix");

$othergroups=array_merge($othergroups,split("[\r\n]",$formdata["groups"]));

// check for boxes which can imply group subscription
$ree=mysql_query("select subscribe_groups,page_id,box_id from form_page_box where length(subscribe_groups)>0 and form_id='$form_id'");
if ($ree && mysql_num_rows($ree)) {
    while ($kee=mysql_fetch_array($ree)) {
        $re2=mysql_query("select demog_id,default_value from form_element where page='$kee[page_id]' and form_id='$form_id' and box_id='$kee[box_id]' and widget not in ('separator','comment')");
        $go_subs_box=1;
        if ($re2 && mysql_num_rows($re2)) {
            while ($ke2=mysql_fetch_array($re2)) {
                // check first if this item has been displayed or not. If not, we should not take it into account 
                // for box implied subscription, it can be empty.
                $isdefault=0;
                // the item is filled in with the default value, we'll consider this not filled
                if ($filled["$ke2[demog_id]"]==$ke2["default_value"] || $filled["$ke2[demog_id]"]==",$ke2[default_value],") {
                    $isdefault=1;
                }
                // item is shown but not filled in, stop box subscribe
                if (!$nodepend && (!isset($filled["$ke2[demog_id]"]) || $isdefault)) {  
                    // nice, but there is an exception: one of mobile or phone is sufficient.
                    if (!( ($ke2["demog_id"]==6 && isset($filled["7"])) || ($ke2["demog_id"]==7 && isset($filled["6"]))  )) {
                        $go_subs_box=0;
                    }
                }
            }
        }
        // all elements which should be filled are filled.
        if ($go_subs_box==1) {
            $othergroups[]=$kee["subscribe_groups"];
        } 
    }
}

// now check if group subscribe per condition is set up for some form elements.
$res=mysql_query("select * from form_element_subscribe where form_id='$form_id' and length(dependency)>0 and length(groups)>0");
if ($res && mysql_num_rows($res)) {
    while ($ke2=mysql_fetch_array($res)) {
        $nodepend=dep_check("form_element_subscribe",$ke2["id"],$ke2["dependency"]);
        if (!$nodepend) {
            $othergroups[]=$ke2["groups"];
        }
    } 
}

// check for automatic emails
$automatic_emails=array();
$re2=mysql_query("select * from form_email where form_id='$form_id'");
$go_subs_box=1;
if ($re2 && mysql_num_rows($re2)) {
    while ($ke2=mysql_fetch_array($re2)) {
        $nodepend=dep_check("form_email",$ke2["id"],$ke2["dependency"]);
        if (!$nodepend && !isset($automatic_emails["$ke2[base_id]"])) {
            $automatic_emails["$ke2[base_id]"]=1;
        }        
    }
}

if ($specaff) { 
    $subscribe_data["affiliate"] = $specaff; 
} 
else {
    $subscribe_data["affiliate"] = $formdata["affiliate_id"];
}

$subscribe_data["hidden_subscribe"] = $formdata["hidden_subscribe"];
$subscribe_data["othergroups"]=array();
for ($i=0;$i<count($othergroups);$i++) {
    $vergr=mysql_escape_string(str_replace("\r","",$othergroups[$i]));
    if (!empty($vergr)) {
        $vres=mysql_query("select title,unique_col,subscribe_id from groups where title='$vergr'");
        if ($vres && $kres=mysql_fetch_array($vres)) {
            // if the subscribe is not multigroup, or the unique_col is different, clone subscription for additional groups;
            // subscribe-id can only authorize for a single group or a single multigroup
            if ($kres["unique_col"]!=$gdata["unique_col"]) {
                $clone_subscribe_data = $subscribe_data;
                $clone_subscribe_data["values"][]=array("subscribe-id",$kres["subscribe_id"]);
                $clone_subscribe_data["idname"] = $kres["unique_col"]; 
                $clone_subscribe_data["group"] = $vergr;
                if (empty($captcha_error)) {
                    $_MX_subscribe = new MxSubscribe();
                    $_MX_subscribe->FormCollectSubscribe($clone_subscribe_data);
                    foreach ($captcha_array as $captcha_key) {
                        unset($_SESSION['maxima_captcha_'.$captcha_key]);
                    }
                }
            }
            elseif (!in_array($vergr,$subscribe_data["othergroups"])) {
                $subscribe_data["othergroups"][] = $vergr;
            }
        }
    }
}

// these parameters are linked to the form's group, therefor not needed in cloned subscribes
if (isset($_POST["___vct___"]) && ereg("^[0-9]+h[0-9a-f]{11}[0-9]+$",$_POST["___vct___"],$regs) ) {
    $subscribe_data["values"][]=array("vct-id",$_POST["___vct___"]);
}
foreach ($automatic_emails as $aem_base=>$aem_data) {
    // note that this will work only if the subsriber is new in form's group (and not in some other groups)
    $subscribe_data["values"][]=array("form-email","$aem_base-$formdata[group_id]");
}
$subscribe_data["values"][]=array("form-id",$form_id);
$subscribe_data["values"][]=array("subscribe-id",$group_subscribe_id);
$subscribe_data["idname"] = $gdata["unique_col"]; 
$subscribe_data["group"] = $gtitle; 
if (empty($captcha_error)) {
    $_MX_subscribe = new MxSubscribe();
    $_MX_subscribe->FormCollectSubscribe($subscribe_data);
    foreach ($captcha_array as $captcha_key) {
        unset($_SESSION['maxima_captcha_'.$captcha_key]);
    }
}
foreach ($subscribe_data["values"] as $carr) { 
    $formdata["landing_page"] = preg_replace("/\{$carr[0]\}/i",$carr[1],$formdata["landing_page"]);
}
if ($form_id==282 && isset($_POST["cid"]) && strlen($_POST["cid"])!=12) {
    print '<html><head><meta http-equiv="content-type" content="text/html; charset=utf-8" /><title>KutatóCentrum - mondja el véleményét a kérdőívről</title></head><body style="background-color: #ffffff; vertical-align: top; margin: 0px; padding: 0px;">
<div align="center"><iframe style="height:820px; width:710px;" frameborder="0" scrolling="no" src="http://www.kutatocentrum.hu/kutatasok/endlink/?cid=' . $_POST["cid"] . '&queries_which=35594"></iframe></div>
</body></html>';
}
else {
    if ($form_id==278/* && $form_id==301*/) {
        $security_key = "";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://tr.affiliate.hu/get_security_key.php?private_key=2e5525bb87ad391329e2fc8493bf2e11");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $security_key = curl_exec($ch);
        curl_close($ch);    
        $formdata["landing_page"] = str_replace("CHANGE_TO_SECURITY_KEY",$security_key,$formdata["landing_page"]);
        $formdata["landing_page"] = str_replace("CHANGE_TO_REG_ID","8",$formdata["landing_page"]);
    }
    if($form_id == 301) {
      $f = fopen("http://tr.affiliate.hu/get_security_key.php?private_key=5d9d92e300be43a6f47fbe28c41ad215", "r"); //die( print_r( $f  ) );
      $aff_sec_key = fread($f, 1024);
      fclose($f); 
      $aff_reg_id = md5( $_POST[ "email" ] );
      $formdata["landing_page"] = str_replace("CHANGE_TO_SECURITY_KEY",$aff_sec_key,$formdata["landing_page"]);
      $formdata["landing_page"] = str_replace("CHANGE_TO_REG_ID",$aff_reg_id,$formdata["landing_page"]);
    }
    print $formdata["landing_page"];
}




if ($form_id==291) {
    $email_order_to = "support@simplexvital.hu";
    // $email_order_to = "tbjanos@manufaktura.rs";
    $email_order_cc = array("kristok.balazs@hirekmedia.hu","gott.julia@hirekmedia.hu","toth.tamas@hirekmedia.hu");
    // $email_order_cc = array("tbjanos@gmail.com","tbjanos@freemail.hu");
    $sender_email = "support@simplexvital.hu";
    $return_path = $sender_email;
    $subject = "SimplexVital uj kerdoiv kitolto";
    $headers = "From: $sender_email\n";
    $headers .= "Reply-To: $sender_email\n";
    $headers .= "Return-Path: <".$return_path.">\n";
    foreach ($email_order_cc as $cc) {
        $headers .= "Cc: $cc\n";
    }
    $headers .= "Content-Type: text/plain; charset=\"utf-8\"\n";
    $email_content = "e-mail: $email\n";
    if (!mail($email_order_to,$subject,$email_content,$headers,"-f$return_path")) {
        $error = "Could not send email";
    }
}

mx_end_form();

function mx_end_form($status="success") {

    global $_MX_var,$form_id,$subscribe_data;

    $comment="";
    foreach ($_POST as $pvar=>$pvalue) {
        if (ereg("^tcom_(.+)$",$pvar,$prg)) {
            $comment.=$pvalue."|:|";
        }
    }
    $idvalue="";
    foreach ($subscribe_data["values"] as $carr) { 
        if ($carr[0]==$subscribe_data["idname"]) {
             $idvalue = mysql_escape_string($carr[1]);
        }
    }
    $comment=mysql_escape_string($comment);
    $time=intval($_POST["___time___"]);
    mysql_query("insert into form_statistics (form_id,fill_time,status,dateadd,comments,httpua,member_id,postvars) values 
                 ($form_id,$time,'$status',now(),'$comment','" . mysql_escape_string($_SERVER["HTTP_USER_AGENT"]) . "','$idvalue','" . 
                    mysql_escape_string(serialize($_POST))
                 . "')");
    exit;
}

function dep_check ($obj,$id,$dep) {

    global $_MX_var,$form_id,$enum_widgets,$filled;

    $re2=mysql_query("select * from ".$obj."_dep where ".$obj."_id='$id'");
    if ($re2 && mysql_num_rows($re2)) {
        while ($dl=mysql_fetch_array($re2)) {
            eval('$D'.$dl[id].'=1;');
            $rd=mysql_query("select d.variable_name,fe.widget,fe.id from form_element fe, demog d where 
                             fe.demog_id='$dl[dependent_id]' and fe.demog_id=d.id and form_id='$form_id'");
            if ($rd && mysql_num_rows($rd)) {
                $zd=mysql_fetch_array($rd);
                if (in_array($zd["widget"], $enum_widgets)) {
                    $deparr=explode(",",$dl["dependent_value"]);
                    if (strpos($dl["dependent_value"],"*")>=0) {                    
                        foreach ($deparr as $depenum) {
                            if (ereg("^[0-9_]+$",$depenum) && ereg(",$depenum,",$filled["$dl[dependent_id]"])) {
                                eval('$D'.$dl[id].'=0;');
                            }
                        }
                    } 
                }
                else {
                    if (!empty($filled["$dl[dependent_id]"]) && $dl["dependent_value"]=="*" || $filled["$dl[dependent_id]"]==",$dl[dependent_value]," || $filled["$dl[dependent_id]"]==$dl["dependent_value"]) {
                        eval('$D'.$dl[id].'=0;');
                    }
                }
            }
        }
        $res=1;
        $dep=preg_replace('/D/','$D',$dep);
        if ($dep!=null) eval('$res='.$dep.';');
        return $res;
    }
}
?>
