<?
include "auth.php";
$weare=35;
include "cookie_auth.php";  
include "common.php";  

foreach ($_POST as $var=>$val) {
    $$var=$val;
}

$mres = mysql_query("select title,num_of_mess,name from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and user_id='$active_userid' 
                     and (membership='owner' or membership='moderator' $admin_addq)");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else {
    header("Location: $_MX_var->baseUrl/index.php?no_group=1"); exit; }
$title=$rowg["title"];
logger($query,$group_id,"","","filter");        
$checked=array("multival_unfold"=>"","ext_dl"=>"","all"=>"","email_only"=>"","select"=>"","validated_date"=>"");
$errors=array();

$sctorby=array( array("sql"=>"vip_demog.ordernum","name"=>"Változók sorrendje szerint","formorder"=>0),
                array("sql"=>"demog.question","name"=>"Név szerint","formorder"=>0)
                );
$fill_time_options=array(array("id"=>0,"name"=>"-- kérdőív --"));

$frms=mysql_query("select id,title from form where group_id=$group_id");
if ($frms && mysql_num_rows($frms)) {
   while ($fr=mysql_fetch_array($frms)) {
        $sctorby[]=array("sql"=>"$fr[id]","name"=>"Kérdőív: ".$fr["title"],"formorder"=>1);            
        $fill_time_options[]=array("id"=>$fr["id"],"name"=>$fr["title"]);
   }
}

$supported_codepages=array();
$rec=mysql_query("select codepage,description from codepages order by sortorder");
while ($kec=mysql_fetch_array($rec)) {
    $supported_codesets["$kec[codepage]"]=$kec["description"];
}

$enter=get_http("enter","");
$xmlreq_id=get_http("xmlreq_id",0);

if ($enter) {
    if (isset($_POST["codeset"]) && isset($supported_codesets["$_POST[codeset]"])) {
        $codeset=$_POST["codeset"];
    }
    else {
        list($codeset,$x)=each($supported_codesets);
    }
    $report_columns="minden demog info";
    $report_filter="(nincs szűrő)";
    if ($filt_demog) {
        $rf=mysql_query("select name from filter where id=" . intval($filt_demog));
        if ($rf && mysql_num_rows($rf)) {
            $report_filter=mysql_result($rf,0,0);
        }
    }
    $params=array();
    $params[]=intval($filt_demog);
    $params[]=";";      // always use these main/multi delimiters;
    $params[]="pipe";   // this may change in future
    if (isset($multival_unfold)) { 
        $multival_unfold=1; 
        $checked["multival_unfold"]=" checked";
    } 
    else { 
        $multival_unfold=0; 
    }
    if (isset($validated_date)) { 
        $validated_date=1; 
        $checked["validated_date"]=" checked";        
    } 
    else { 
        $validated_date=0; 
    }        
    $params[]=$multival_unfold;
    $cfiltpart="";
    $email_only=0;
    if ($columns=="email_only") {
        $email_only=1;
        $checked["email_only"]=" checked";
    }
    elseif ($columns=="select") {
        $repf=array();
        $checked["select"]=" checked";
        $cfilt=array();
        foreach ($_POST as $pname=>$pval) {
            if (ereg("^csel([0-9]+)$",$pname,$prg)) {
                $cfilt[]=$prg[1];
            }
        }
        if (count($cfilt)) {
            if ($_POST["fsort"]<2) {
                $cfiltpart=implode(",",$cfilt);
                $cfilt=array();
                $rq=mysql_query("select demog.id,demog.question from demog inner join vip_demog on demog.id=vip_demog.demog_id 
                                 where demog.id in($cfiltpart) and vip_demog.group_id='$group_id' order by field(demog.id,$cfiltpart)");
                while ($vk=mysql_fetch_array($rq)) {
                    $cfilt[]=$vk["id"];
                    $repf[]=$vk["question"];
                }
                $cfiltpart=implode(",",$cfilt);
                $report_columns=implode(", ",$repf);
            }                
        }
        else {
            $email_only=1;
        }
    }
    else {
        $checked["all"]=" checked";
    }
    if ($email_only) {
        $report_columns="email";
    }
    if ($oby_formid && $cfiltpart=="" && !$email_only) { //ha kerdoiv szerint volt sorrendbe rakva akkor megis kiszedem a kerdeseket hogy meglegyen a sorrend a  perl scriptnek
        $srto=mysql_query("SELECT demog_id FROM form_element where form_id=$oby_formid order by page,box_id,sortorder");
        $oby_formid=$ob["sql"];
        if ($srto && mysql_num_rows($srto)) {
            while ($di=mysql_fetch_array($srto)) {
                $dmg_ids[]=$di["demog_id"];
            }
        }             
        $dmg_ids=array_reverse($dmg_ids);
        $dmgids=implode(",",$dmg_ids);
        $cfilt=array();
        $rq=mysql_query("select demog.id,demog.question from demog inner join vip_demog on demog.id=vip_demog.demog_id 
                         where demog.id in($dmgids) and vip_demog.group_id='$group_id' order by field(demog.id,$dmgids)");
        while ($vk=mysql_fetch_array($rq)) {
            $cfilt[]=$vk["id"];
        }
        $cfiltpart=implode(",",$cfilt);
    }
//print "**$cfiltpart<br>";
//exit;

    $params[]=$email_only;
    $params[]=$cfiltpart;
    $params[]=mysql_escape_string($export_column);
    $params[]=$validated_date;
    $params[]=mysql_escape_string($fill_time);
    foreach ($sctorby as $k =>$ob) {
        if ($_POST["fsort"]==$k) {
            if ($ob["formorder"]) {
                $params[]=$ob["sql"];
            } 
            else {
                $params[]=0;
            }                
        }
    }
    $ipaddc="";
    $ipaddd="";
    $ext_dl_regen=get_http("ext_dl_regen",1800);
    if (isset($ext_dl)) { 
		$liveexp=true;
        $checked["ext_dl"]=" checked";
        $ext_dl_sqlc=",ext_dl,ext_dl_regen,ext_dl_username,ext_dl_password,ext_dl_auth"; 
        $ext_dl_sqld=",'yes',$ext_dl_regen";
        if (strlen($ext_dl_username)>5 && strlen($ext_dl_password)>5) {
            $ext_dl_sqld.=",'". mysql_escape_string($ext_dl_username) ."'";
            $ext_dl_sqld.=",'". mysql_escape_string($ext_dl_password) ."'";
        }
        else {
            //$errors[]=$word["ext_dl_nouserpass"];
            $errors[]="A live exporthoz legalább hat karakterból álló usernév és jelszó kell.";
        }
        $ext_dl_password=htmlspecialchars($ext_dl_password);
        $ext_dl_username=htmlspecialchars($ext_dl_username);
        if ($ext_dl_auth=="in-url") {
            $has_ip=array();
            $iperr=0;
            for ($i=0;$i<3;$i++) {
                if (!empty($_POST["ext_dl_ip$i"])) {
                    $ip_ok=0;
                    if (ereg("^([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+)?$",$_POST["ext_dl_ip$i"],$rg)) {
                        if ($rg[1]<256 && $rg[2]<256 && $rg[3]<256 && $rg[4]<256) {
                            $ip_ok=1;
                        }
                    }
                    if ($ip_ok) {
                        $has_ip[]=$_POST["ext_dl_ip$i"];
                    }
                    else {
                        $errors[]="Hibás IP cím: ". htmlspecialchars($_POST["ext_dl_ip$i"]);
                    }
                }
            }
            if (count($has_ip)==0) {
                $errors[]="Ha az usernév és a jelszó az URL-ben van megadva, legalább egy IP címet kell definiálni.";
            }
            $ipaddc=",ext_dl_ips";
            $ipaddd=",'". implode(",",$has_ip) ."'";
        }
        else {
            $ext_dl_auth="www-basic";
        }
        $ext_dl_sqld.=",'$ext_dl_auth'";
    } 
    else { 
		$liveexp=false;
        $ext_dl_sqlc=",ext_dl"; 
        $ext_dl_sqld=",'no'"; 
    }
    if (strlen($description)<5) $errors[]="A leirás mező kitöltése kötelező.";
    if (count($errors)==0 && $saveall) {
		if (!empty($xmlreq_id))
		{
			$query="update xmlreq set 	job_type='export_member',
										status='queued',
										job_input='". implode("|",$params) ."',
										date=now(),
										group_id='$group_id',
										description='". mysql_escape_string($description) ."'";
			$col=explode(",",$ext_dl_sqlc);
			$colval=explode(",",$ext_dl_sqld);
			for ($i=1;$i<count($col);$i++)
			{
				$query.=" ,".$col[$i]."=".$colval[$i];
			}
			if ($ipaddc!=null) $query.=" ".$ipaddc."=".substr($ipaddd,1,strlen($ipaddd)); 
			$query.=" where id=".$xmlreq_id; 			
			mysql_query($query);
    		logger($query,$group_id,"","xmlreq_id=$xmlreq_id","export");        													
			if ($liveexp==true)
				header("location: liveexport.php?group_id=$group_id");
			else
	        	header("location: xmlreqlist.php?group_id=$group_id");
			exit;
		}
		else {
            $from="reports@maxima.hu";
            $reply="toth.tamas@hirek.hu";
            $body="
$active_username ($active_emailname) a $rowg[name] ($rowg[title]) csoportban tagok exportálását kezdte meg, az export paraméterei: 
leírás: $description
szűrő: $report_filter 
adatok: $report_columns

---------------------------------
Maxima reports
";
            foreach ($_MX_var->export_email_report as $report_email) {
                mail("$report_email","Maxima export",$body,"From: $from\nReply-To: $reply\nContent-Type: text/plain;\n\tcharset=\"utf-8\"");
            }
            $q="insert into xmlreq (job_type,status,job_input,date,group_id,codeset,description$ext_dl_sqlc$ipaddc) values 
                     ('export_member','queued','". implode("|",$params) ."',
                         now(),'$group_id','$codeset','". mysql_escape_string($description) ."'$ext_dl_sqld$ipaddd)";
            mysql_query($q);
            $xmlreq_id=mysql_insert_id();
            logger($q,$group_id,"","","export");	
			if ($liveexp==true)
				header("location: liveexport.php?group_id=$group_id");
			else
	        	header("location: xmlreqlist.php?group_id=$group_id");
            exit;
		}
    }
    $description=htmlspecialchars($description);
    for ($i=0;$i<3;$i++) {
        $_POST["ext_dl_ip$i"]=htmlspecialchars($_POST["ext_dl_ip$i"]);
    }
}
else {
    $ext_dl_username="";
    $ext_dl_password="";
    $description="";
    $checked["all"]=" checked";
	if (!empty($xmlreq_id))
	{
		$rst=mysql_query("select * from xmlreq where group_id='$group_id' and ext_dl='yes' and id=$xmlreq_id");
		$row=mysql_fetch_array($rst);
		$description=htmlspecialchars($row["description"]);
		$jobinput=explode("|",htmlspecialchars($row["job_input"]));
		$filt_demog=$jobinput[0];
		$ext_dl_auth=htmlspecialchars($row["ext_dl_auth"]);
		if ($row["ext_dl"]=='yes') $checked[ext_dl]=" checked";
		if ($jobinput[3]==1) $checked[multival_unfold]=" checked";
		if ($jobinput[4]==1) $checked[email_only]=" checked";
		$demog_id=explode(",",$jobinput[5]);
        if ($jobinput[5]>null) $checked[select]=" checked";
		if ($jobinput[6]>1) $checked[validated_date]=" checked";        
		$ext_dl_username=htmlspecialchars($row["ext_dl_username"]);
		$ext_dl_password=htmlspecialchars($row["ext_dl_password"]);
		$ext_dl_ips=explode(",",htmlspecialchars($row["ext_dl_ips"]));
		$ext_dl_ip0=$ext_dl_ips[0];
		$ext_dl_ip1=$ext_dl_ips[1];
		$ext_dl_ip2=$ext_dl_ips[2];
	}
}
if (!is_array($demog_id)) $demog_id=array();
include "menugen.php";
include "./lang/$language/export_import.lang";
include "./lang/$language/form.lang";
include "./lang/$language/xmlreqlist.lang";
$filtsel="<select name='filt_demog' class='oinput'><option value='0'>$word[all_members]</option>";
$vres=mysql_query("select * from filter where group_id='$group_id' and archived='no' order by name");
if ($vres && mysql_num_rows($vres)) {
   while ($vk=mysql_fetch_array($vres)) {
      $selected=$filt_demog==$vk["id"]?" selected":"";
      $filtsel.="<option value='$vk[id]'$selected>$vk[name]</option>";		
   }
}
$filtsel.="</select>";

$authsel="<select name='ext_dl_auth' class='oinput' onchange='document.getElementById(\"ipdiv\").style.display=this.selectedIndex==1?\"block\":\"none\";'>";
$auth_opts=array("www-basic","in-url");
foreach ($auth_opts as $ao) {
    $selected=$ext_dl_auth==$ao?" selected":"";
    $authsel.="<option value='$ao'$selected>". $word["ext_dl_auth_$ao"] ."</option>";
}
$authsel.="</select>";

print "<table border=0 cellspacing=0 cellpadding=1 class='bgcolor' width=100%>
<tr><td class=formmezo>
&nbsp;$word[exp_imp_exp]
</td></tr><tr><td>
<table border=0 cellspacing=1 cellpadding=1 bgcolor=#eeeeee width=100%>
  <form method='post' action='csv_export.php' name='fexp'>
  <input type='hidden' name='enter' value='1'>  
  <input type='hidden' name='group_id' value='$group_id'>  \n";
if (count($errors)) {
    print "<tr><td><span class='szovegvastag'>";
    print implode("<br>",$errors);
    print "</span></td></tr>\n";
}

if ($ext_dl_auth=="in-url") { 
    $disp_inurl="block"; 
}
else { 
    $disp_inurl="none"; 
}

$codeset_options="";
foreach ($supported_codesets as $cs => $desc) {
    $sel=($cs==$codeset?" selected":"");
    $codeset_options.="<option value='$cs'$sel>$desc</option>";
}

$ext_dl_regen_options=array("half_hour"=>30*60,"one_hour"=>60*60,"half_day"=>12*60*60,"one_day"=>24*60*60,"one_week"=>7*24*60*60);
$ext_dl_regen_options_output="";
foreach ($ext_dl_regen_options as $name=>$val) {
    $ext_dl_regen_options_output.="<option value='$val'".($val==$row["ext_dl_regen"]?" selected='selected'":"").">".$word["job_regen_$name"]."</option>";
}

print "
  <tr>
    <td class=bgvilagos2><span class='szoveg'>$word[description]:&nbsp;<input name='description' value=\"$description\" size='60'></span></td>
  </tr>  
  <tr>
    <td class=bgvilagos2><span class='szoveg'>$word[filter]:&nbsp;$filtsel</span></td>
  </tr>  
  <tr>
    <td class=bgvilagos2><span class='szoveg'>$word[file_charset]:&nbsp;<select name='codeset'>$codeset_options</select></span></td>
  </tr>  
  <tr>
    <td class='bgvilagos2 tbordercolor' ><span class='szoveg'><input type='checkbox' name='ext_dl' value='yes'$checked[ext_dl]>$word[ext_dl]</span></td>
  </tr>  
  <tr>
    <td class=bgvilagos2><span class='szoveg'> 
    <div>$word[ext_dl_username]:<input name='ext_dl_username' value=\"$ext_dl_username\"> $word[ext_dl_password]:<input name='ext_dl_password' value=\"$ext_dl_password\"></div>
    <div id='ipdiv' style='display:$disp_inurl;'>IP 1:<input name='ext_dl_ip0' value=\"$ext_dl_ip0\"> IP 2:<input name='ext_dl_ip1' value=\"$ext_dl_ip1\">IP 3:<input name='ext_dl_ip2' value=\"$ext_dl_ip2\"></div>
    </span></td>
  </tr>  
  <tr>
    <td class='bgvilagos2 bbordercolor'><span class='szoveg'>$word[ext_dl_auth]:&nbsp;$authsel</span> &nbsp; $word[job_refresh]: <select name='ext_dl_regen'>$ext_dl_regen_options_output</select></td>
  </tr>
  <tr>
    <td class=bgvilagos2 valign='top'><span class='szoveg'>
	<input type='hidden' name='delim_main' value=','>
	<input type='hidden' name='delim_multi' value=';'>
    <input type='hidden' name='xmlreq_id' value='$xmlreq_id'>
    $word[e_type_text] &nbsp;
    <select name='export_column' class='oinput'>
        <option value='data'>$word[e_type_d]</option>
        <option value='code'>$word[e_type_c]</option>
        <option value='id'>$word[e_type_i]</option>    
    </select>
    </td>
  </tr>
  <tr>
    <td class=bgvilagos2 valign='top'><span class='szoveg'>
	<input type='checkbox' name='multival_unfold' value='1'$checked[multival_unfold]> $word[multival_unfold]
    </td>
    </tr>
  <tr>
    <td class=bgvilagos2 valign='top'><span class='szoveg'>
	<input type='checkbox' name='validated_date' value='1'$checked[validated_date]> $word[validated_date]
    </td>
  </tr>";

if (count($fill_time_options)>1) {
    print "<tr>
    <td class=bgvilagos2 valign='top'><span class='szoveg'> kitöltési idő: 
    <select name='fill_time'>\n";
    foreach ($fill_time_options as $fto) {
        $selected=$fto["id"]==$fill_time?"selected":"";
        print "<option $selected value='$fto[id]'>$fto[name]</option>\n";
    }
    print "</select></td></tr>\n";
}
print "<tr>
    <td class=bgvilagos2 valign='top'><span class='szoveg'>
	<input type='radio' name='columns' value='all'$checked[all]> $word[columns_all]
    </td>
  </tr>
  <tr>
    <td class=bgvilagos2 valign='top'><span class='szoveg'>
	<input type='radio' name='columns' value='email_only'$checked[email_only]> $word[email_only]
    </td>
  </tr>
  <tr>
    <td><table border='0' cellpadding='0' cellspacing='0' width='100%'><tr>
    <td class=bgvilagos2 width='50%'><span class='szoveg'><input id='selected_cols_ch' type='radio' name='columns' value='select'$checked[select]> $word[columns_select]</td>
    <td class=bgvilagos2 width='50%' align='right'>
    <select onChange='JavaScript: document.fexp.force.value=1; this.form.submit();' name=fsort>";

    $dmg_ids=array();
    foreach ($sctorby as $k =>$ob) {
        if ($_POST["fsort"]==$k) {
            $sel="selected";
            if ($ob["formorder"]) {
                $srto=mysql_query("SELECT demog_id FROM form_element where form_id=$ob[sql] order by page,box_id,sortorder");
                $oby_formid=$ob["sql"];
                if ($srto && mysql_num_rows($srto)) {
                    while ($di=mysql_fetch_array($srto)) {
                        $dmg_ids[]=$di["demog_id"];
                    }
                }             
                $dmg_ids=array_reverse($dmg_ids);
                $dmgids=implode(",",$dmg_ids);
                $ord="order by demog.variable_name='email' desc, field(demog.id,$dmgids) desc";
            } else {
                $ord="order by demog.variable_name='email' desc, ".$ob["sql"];
            }                
        } else {
            $sel="";
        }            
        print "<option $sel value='$k'>$ob[name]</option>";
    }

/*
if ($_POST["fsort"]!="question")
{	
	print 	"<option selected value='ordernum'>$word[orderbysort]</option>
    		<option value='question'>$word[orderbyname]</option>";
	$ord="vip_demog.".htmlspecialchars($_POST["fsort"]);
}
else
{
	print 	"<option value='ordernum'>$word[orderbysort]</option>
    		<option selected value='question'>$word[orderbyname]</option>";
	$ord="demog.".htmlspecialchars($_POST["fsort"]);
}*/

print "</select>&nbsp;&nbsp;
	<a href=\"javascript:select_all()\"><img src='$_MX_var->application_instance/gfx/selectall.gif' border='0' alt='$word[select_all]'></a><a href=\"javascript:deselect_all()\"><img src='$_MX_var->application_instance/gfx/selectnone.gif' border='0' alt='$word[select_none]'></a></td>
    </tr></table></td>
  </tr>
  <tr>
    <td style='background-color:$_MX_var->main_table_border_color;'><table border='0' cellpadding='0' cellspacing='1' width='100%'><tr>\n";

$i=0;
$rq=mysql_query("select demog.question,demog.variable_name,demog.id,demog.code from demog,vip_demog where demog.id=vip_demog.demog_id and vip_demog.group_id='$group_id' $ord");
if ($rq && mysql_num_rows($rq)) {
    while ($mm=mysql_fetch_array($rq)) 
	{
        $check="";
        if ((isset($_POST["csel$mm[id]"])) || (in_array($mm['id'],$demog_id))) {
            $check=" checked";
        }
        if (isset($_POST["force"]) && !empty($_POST["force"])) {
            if (in_array($mm[id],$dmg_ids)) $check=" checked"; else $check="";
        }
        print "<td class='bgvilagos2' valign='middle' width='33%'><input onclick='document.getElementById(\"selected_cols_ch\").checked=true;' type='checkbox' name='csel$mm[id]' value='1'$check> ". mysql_escape_string("[" . $mm["code"] . "] " . $mm["question"]) ."</td>";
        if ($i%3==2) {
            print "</tr><tr>";
        }
        $i++;
    }
}
$kk=$i%3;
if ($kk) {
    for ($i=0;$i<3-$kk;$i++) {
        print "<td class='bgvilagos2'>&nbsp;</td>";
    }
}

print "</tr></table></td>
   </tr>
   <tr>
   <td align='center' class=bgvilagos2><input type='hidden' name='oby_formid' value='$oby_formid'><input type='hidden' name='force' value='0'><input class='tovabbgomb' type='submit' name='saveall' value='$word[export]'>
  </td>
  </tr>
  </form>
</table>
</td>
</tr></table>
<script>
function select_all()
{
  len = document.fexp.elements.length;
  var i=0;
  for(i=0; i < len; i++) {
    if (document.fexp.elements[i].type == 'checkbox' && document.fexp.elements[i].name!='multival_unfold')
    { document.fexp.elements[i].checked = true }
  }

}
function deselect_all()
{
  len = document.fexp.elements.length;
  var i=0;
  for(i=0; i < len; i++) {
    if (document.fexp.elements[i].type == 'checkbox' && document.fexp.elements[i].name!='multival_unfold')
    { document.fexp.elements[i].checked = false }
  }

}
</script>
";

?>
