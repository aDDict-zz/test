<?
header('Content-Type: text/html; charset=utf-8');
include "auth.php";
include "_form.php";
$weare=34;
$subweare="export";
include "cookie_auth.php";
include "common.php";
$language=select_lang();
include "./lang/$language/form.lang";
include "./lang/$language/export_import.lang";
include "./lang/$language/dategen.lang";
$mres = mysql_query("select title,num_of_mess,membership 
                     from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and (membership='owner' or membership='moderator' $admin_addq)
                     and user_id='$active_userid'");
if ($mres && mysql_num_rows($mres)) {
    $rowg=mysql_fetch_array($mres);  
}
else {
    exit; 
}

$form_id = get_http("form_id",0);
$res=mysql_query("select * from form where id='$form_id' and group_id='$group_id'");
if ($res && mysql_num_rows($res))
    $formdata=mysql_fetch_array($res);
else
    exit;

$debug=0;

$supported_codepages=array();
$rec=mysql_query("select codepage,description from codepages order by sortorder");
while ($kec=mysql_fetch_array($rec)) {
    $supported_codesets["$kec[codepage]"]=$kec["description"];
}

$_MX_form = new MxForm($group_id,"",0,0);
$_MX_form->InitForm($form_id);

$submit = get_http("submit","");
$test = abs(intval(get_http("test",0)));
$preview = get_http("preview",0);
$errors = array();
$messages = array();
if ($preview) {
    $submit = "preview";
}

$hbanner_name="";
$hbanner_prefix="";
$hbanner_length=12;
if ($submit) {
    if (isset($_POST["codeset"]) && isset($supported_codesets["$_POST[codeset]"])) {
        $codeset=$_POST["codeset"];
    }
    else {
        list($codeset,$x)=each($supported_codesets);
    }
    if ($submit=="preview" || $submit=="generate") {
        if (count($errors)==0) {
            $genform = new MxForm($group_id,$rowg["title"],$preview,0,0,$test);
            $form_id=intval($form_id);
            if ($genform->InitForm($form_id)) {
                $genform->MakeForm();
            }
            exit;
        }
    }
    if ($submit=="export_elements") {
        if (count($errors)==0) {
            mx_export_elements(get_http("export_elements_type","general"));
        }
    }
    if ($submit=="export_dependencies") {
        if (count($errors)==0) {
            mx_export_dependencies();
        }
    }
    if ($submit=="addurl") {
        $social = get_http("social","");
        if (!empty($social)) {
            $soclist="";
            if ($social!="all") {
                $soclist="id =" . intval($social) . " and";
            }
            mysql_query("insert into form_banner (name,form_id,social_network_id,prefix) 
                         select name,$form_id,id,prefix from form_social_network where $soclist
                         id not in (select distinct social_network_id from form_banner where form_id=$form_id)");
        }
        else {
            $banner_name=get_http("banner_name","");
            if (empty($banner_name)) {
                $errors[]="A banner link nevét kötelező megadni";
            }
            $banner_length=intval(get_http("banner_length",0));
            if ($banner_length<6) {
                $banner_length=6;
            }
            if ($banner_length>32) {
                $banner_length=32;
            }
            $banner_prefix=get_http("banner_prefix",0);
            if (!ereg("^[a-z]{1,3}$",$banner_prefix)) {
                $errors[]="A banner prefix minimum 1, maximum 3 ékezet nélküli kisbetűből állhat";
            }
            else {
                $res=mysql_query("select * from form_banner where form_id=$form_id and prefix='$banner_prefix'");
                if ($res && mysql_num_rows($res)) {
                    $errors[]="Már létezik ilyen banner prefix";
                }
            }
            $hbanner_name=htmlspecialchars($banner_name);
            $hbanner_prefix=htmlspecialchars($banner_prefix);
            $hbanner_length=htmlspecialchars($banner_length);
            if (count($errors)==0) {
                mysql_query("insert into form_banner (name,form_id,total_length,prefix) values ('" . mysql_escape_string($banner_name) . "',$form_id,$banner_length,'$banner_prefix')");
            }
        }
    }
    if ($submit=="delurl") {
        mysql_query("delete from form_banner where id=" . intval(get_http("delid",0)));
    }
    if ($submit=="export_cids") {
        mx_csv_headers("$rowg[title]_cids.csv");
        $res=mysql_query("select cid from $rowg[title]_cid");
        while ($k=mysql_fetch_array($res)) {
            print "$k[cid]\n";
        }
        exit;
    }
    if ($submit=="import_cids") {
        $filepath=$_MX_var->member_import_temp_dir . md5(time().$REMOTE_ADDR);
        $csv_file=$_FILES['cids']['tmp_name'];
        if (!empty($csv_file) && $csv_file!="none") {
            $import_success=0;
            move_uploaded_file("$csv_file","$filepath");
            $handle = fopen($filepath, "r");
            if ($handle) {
                while (($data = fgetcsv($handle, 65536, ";")) !== FALSE) {
                    $cid=$data[0];
                    if (strlen($cid)<6) {
                        $errors[]="Túl rövid cid a " . ($import_success+1) . ". sorban, legalább 6 karakter hosszú kell hogy legyen.";
                        break;
                    }
                    else {
                        if ($res=mysql_query("insert into $rowg[title]_cid set cid='" . mysql_escape_string($cid) . "'")) {
                            $import_success++;
                        }
                        else {
                            $errors[]="Import hiba (223)";
                            break;
                        }
                    }
                    $irow++;
                }
                fclose($handle);
            }
            unlink($filepath);
            if ($import_success) {
                $messages[]="$import_success cid importja volt sikeres.";
            }
        }
        else {
            $errors[]="Import hiba (227)";
        }
    }
}

include "menugen.php";
include "./lang/$language/form.lang";
    
$_MX_form->MakeMenu($subweare,$formdata);

$hiddens = "<input type='hidden' name='form_id' value='$form_id'><input type='hidden' name='group_id' value='$group_id'>";
$getlink = "form_generate.php?form_id=$form_id&group_id=$group_id";

if (count($errors)) {
    print "<div style='margin:12px 0;'><span class='szovegvastag'>Hiba: " . implode("<br>",$errors) . "</span></div>";
}
if (count($messages)) {
    print "<div style='margin:12px 0;'><span class='szovegvastag'>" . implode("<br>",$messages) . "</span></div>";
}

print "<form method='post' name='cssform' action='form_generate.php' style='border:0;margin:0;'>
       <input type='hidden' name='submit' value='generate' style='margin-top:6px;'>$hiddens
       <TABLE cellSpacing=1 cellPadding=3 width=\"100%\" class='bgcolor addpadding' border=0>
       <tr>
       <td colspan='2' class='bgkiemelt2'>
       <span class='szovegvastag'>$word[iform_generate]</span>
       </td>
       </tr>
       <tr>
       <td style='width:25px;' class='bgvilagos2'><input type='checkbox' name='test' value='1'></td>
       <td class='bgvilagos2'><span class='szoveg'>$word[iform_generate_test]</span>
       </td>
       </tr>
       <tr>
       <td colspan='2' class='bgkiemelt2'>
       <input type='submit' name='go' class='tovabbgomb' value='$word[submit3]'>
       </td>
       </tr>
       </table></form>\n";

$social="";
$res = mysql_query("select * from form_social_network order by id");
while ($k=mysql_fetch_array($res)) {
    $social .= "<a href='$getlink&submit=addurl&social=$k[id]'><img style='margin:0 1px;' src='$_MX_var->baseUrl/$k[icon]'></a>";
}
$social .= "<a href='$getlink&submit=addurl&social=all'>Mindegyiket</a>";

print "<form method='post' name='cssform' action='form_generate.php' style='border:0;margin:0;'>
       <input type='hidden' name='submit' value='addurl'>$hiddens
       <TABLE cellSpacing=1 cellPadding=3 width=\"100%\" class='bgcolor addpadding' border=0 style='margin-top:6px;'>
       <tr>
       <td colspan='5' class='bgkiemelt2'>
       <span class='szovegvastag'>$word[iform_banners]</span>
       </td>
       </tr>
       <tr>
       <td colspan='5' class='bgvilagos2'>Megosztások hozzáadása: $social</span>
       </td>
       </tr><tr>
       <td style='background-color: white;'><span class='szovegvastag'>Név</span></td>
       <td style='background-color: white;'><span class='szovegvastag'>cid hossz</span></td>
       <td style='background-color: white;'><span class='szovegvastag'>prefix</span></td>
       <td style='background-color: white;'><span class='szovegvastag'></span></td>
       <td style='background-color: white;'><span class='szovegvastag'>hivatkozás</span></td>
       </tr><tr>
       <td class='bgvilagos2'><span class='szoveg'><input class='oinput' style='width:100px' name='banner_name' value=\"$hbanner_name\"></span></td>
       <td class='bgvilagos2'><span class='szoveg'><input class='oinput' style='width:40px' name='banner_length' value=\"$hbanner_length\"></span></td>
       <td class='bgvilagos2'><span class='szoveg'><input class='oinput' style='width:40px' name='banner_prefix' value=\"$hbanner_prefix\"></span></td>
       <td class='bgvilagos2' colspan='2'><span class='szoveg'><input type='submit' name='go' class='tovabbgomb' value='Egyéb banner link hozzáadása'></span></td>
       </tr>";

$res = mysql_query("select b.*,s.share_url,s.icon from form_banner b left join form_social_network s on b.social_network_id=s.id where b.form_id=$form_id order by id");
if ($res && mysql_num_rows($res)) {
    while ($k=mysql_fetch_array($res)) {
        $hivatkozas = $formdata["live_url"] . (strstr($formdata["live_url"],"?")?"&":"?") . "banner=$k[prefix]";
        if ($k["social_network_id"]) {
            $hivatkozas=$k["share_url"] . rawurlencode($hivatkozas);
        }
        print "<tr>
           <td style='background-color: white;'><span class='szoveg'>$k[name]</span></td>
           <td style='background-color: white;'><span class='szoveg'>$k[total_length]</span></td>
           <td style='background-color: white;'><span class='szoveg'>$k[prefix]</span></td>
           <td style='background-color: white;'><span class='szoveg'><a href='$getlink&submit=delurl&delid=$k[id]'>Törlés</a></span></td>
           <td style='background-color: white;'><div style='width:700px; height:25px; overflow:auto; margin-left:6px;'><span class='szoveg'>$hivatkozas</span></div></td>
           </tr>";
    }
}

$social=$_MX_form->SocialNetworkLinks();
if (!empty($social)) {
    print "<tr>
       <td style='background-color: white;' colspan='4'>Hivatkozás minden megosztásra:<span class='szoveg'></span></td>
       <td style='background-color: white;'><div style='width:700px; height:75px; overflow:auto; margin-left:6px;'><span class='szoveg'>" . htmlspecialchars($social) . "</span></div></td>
       </tr>";
}

$codeset_options="";
foreach ($supported_codesets as $cs => $desc) {
    $sel=($cs==$codeset?" selected":"");
    $codeset_options.="<option value='$cs'$sel>$desc</option>";
}

print "</table></form>\n";

print "<form method='post' name='cssform' action='form_generate.php' style='border:0;margin:0;'>
       <input type='hidden' name='submit' value='export_elements'>$hiddens
       <TABLE cellSpacing=1 cellPadding=3 width=\"100%\" class='bgcolor addpadding' border=0 style='margin-top:6px;'>
       <tr>
       <td colspan='2' class='bgkiemelt2'>
       <span class='szovegvastag'>$word[iform_export_elements]</span>
       </td>
       </tr>
       <tr>
       <td colspan='2' class='bgvilagos2'>
       <span class='szoveg'>$word[file_charset]:&nbsp;<select name='codeset'>$codeset_options</select></span>
       </td>
       </tr>
       <tr>
       <td colspan='2' class='bgvilagos2'>
       <span class='szoveg'>$word[iform_export_elements_type]:&nbsp;<select name='export_elements_type'>
            <option value='general'>$word[iform_export_elements_type_general]</option>
            <option value='spss_variables'>SPSS variables</option>
            <option value='spss_values'>SPSS values</option>
       </select></span>
       </td>
       </tr>
       <tr>
       <td colspan='2' class='bgkiemelt2'>
       <input type='submit' name='go' class='tovabbgomb' value='$word[submit3]'>
       </td>
       </tr>
       </table></form>\n";

print "<form method='post' name='cssform' action='form_generate.php' style='border:0;margin:0;'>
       <input type='hidden' name='submit' value='export_dependencies'>$hiddens
       <TABLE cellSpacing=1 cellPadding=3 width=\"100%\" class='bgcolor addpadding' border=0 style='margin-top:6px;'>
       <tr>
       <td colspan='2' class='bgkiemelt2'>
       <span class='szovegvastag'>$word[iform_export_dependencies]</span>
       </td>
       </tr>
       <tr>
       <td colspan='2' class='bgvilagos2'>
       <span class='szoveg'>$word[file_charset]:&nbsp;<select name='codeset'>$codeset_options</select></span>
       </td>
       </tr>
       <tr>
       <td colspan='2' class='bgkiemelt2'>
       <input type='submit' name='go' class='tovabbgomb' value='$word[submit3]'>
       </td>
       </tr>
       </table></form>\n";

print "<form method='post' name='cssform' action='form_generate.php' style='border:0;margin:0;' enctype='multipart/form-data'>
       <input type='hidden' name='submit' value='import_cids'>$hiddens
       <TABLE cellSpacing=1 cellPadding=3 width=\"100%\" class='bgcolor addpadding' border=0 style='margin-top:6px;'>
       <tr>
       <td colspan='2' class='bgkiemelt2'>
       <span class='szovegvastag'>CID import</span>
       </td>
       </tr>
       <tr>
       <td colspan='2' class='bgvilagos2'>
       csv file: <input type='file' name='cids'/>
       </td>
       </tr>
       <tr>
       <td colspan='2' class='bgkiemelt2'>
       <input type='submit' name='go' class='tovabbgomb' value='$word[submit3]'>
       </td>
       </tr>
       </table></form>\n";

print "<form method='post' name='cssform' action='form_generate.php' style='border:0;margin:0;'>
       <input type='hidden' name='submit' value='export_cids'>$hiddens
       <TABLE cellSpacing=1 cellPadding=3 width=\"100%\" class='bgcolor addpadding' border=0 style='margin-top:6px;'>
       <tr>
       <td colspan='2' class='bgkiemelt2'>
       <span class='szovegvastag'>CID export</span>
       </td>
       </tr>
       <tr>
       <td colspan='2' class='bgkiemelt2'>
       <input type='submit' name='go' class='tovabbgomb' value='$word[submit3]'>
       </td>
       </tr>
       </table></form>\n";

include "footer.php";

function mx_export_elements($type="general") {

    global $form_id;

    $types = array("general","spss_variables","spss_values");
    if (!in_array($type,$types)) {
        $type = $types[0];
    }

    mx_csv_headers("valtozo_export_$form_id" . "$type.csv");

    $query="select fe.page,fe.box_id,fe.question as feq,fe.widget,d.* from form_element fe left join demog d on fe.demog_id=d.id 
            where fe.form_id='$form_id'";
    if ($type=="general") {
        mx_csv_row(array("Oldal","Doboz","Változó","Kód","Kérdés","Változótípus","Enum/mátrix értékek"));
    }
    if ($type=="spss_variables") {
        $query .= " and d.id is not null";
    }
    if ($type=="spss_values") {
        $query .= " and d.variable_type in ('enum','matrix')";
    }
    $query .= " order by fe.page, fe.box_id, fe.sortorder";
    $res=mysql_query($query);
    if ($res && $count=mysql_num_rows($res)) {
        while ($k=mysql_fetch_array($res)) {
            if (empty($k["variable_type"]) || $k["variable_type"]=="NULL") {
                $variable_type=$k["widget"];
            }
            else {
                $variable_type=$k["variable_type"];
                if ($k["multiselect"]=="yes") {
                    $variable_type.="|multi";
                }
            }
            if ($type=="general") {
                $fields = array($k["page"],$k["box_id"],$k["variable_name"],$k["code"],strlen($k["feq"])?$k["feq"]:$k["question"],$variable_type);
            }
            elseif ($type=="spss_variables") {
                $fields = array($k["code"],strlen($k["feq"])?$k["feq"]:$k["question"]);
            } 
            $firstfield=1;
            if ($type!="spss_variables") {
                $r2=mysql_query("select * from demog_enumvals where demog_id='$k[id]' and deleted='no' order by vertical,id");
                if ($r2 && mysql_num_rows($r2)) {
                    while ($z=mysql_fetch_array($r2)) {
                        if ($type=="spss_values") {
                            $fields = array(($firstfield?$k["code"]:""),$z["code"],$z["enum_option"]);
                            mx_csv_row($fields);
                            $firstfield=0;
                        }
                        else {
                            $fields[]= ($z["vertical"]=="yes"?"reszkerdes":"ertek") . "_$z[enum_option]";
                            $fields[]= (strlen($z["code"])?"id_$z[code]":"");
                        }
                    }
                }
            }
            if ($type!="spss_values") {
                mx_csv_row($fields);
            }
        }
    }
    exit;
}

function mx_export_dependencies() {

    global $_MX_form;

    mx_csv_headers("valtozo_export_$_MX_form->form_id.csv");

    mx_csv_row(array("Elem feltételek"));
    mx_csv_row(array("Oldal","Doboz","Változó","Változótípus","Feltétel","Szülő objektum"));
    $query="select fe.page,fe.box_id,fe.question as feq,fe.widget,fe.dependency,fe.parent_dependency,fe.parent,
            d.* from form_element fe left join demog d on fe.demog_id=d.id 
            where fe.form_id='$_MX_form->form_id' and (length(dependency) or length(parent_dependency)) order by fe.page, fe.box_id, fe.sortorder";
    $res=mysql_query($query);
    if ($res && $count=mysql_num_rows($res)) {
        while ($k=mysql_fetch_array($res)) {
            if (empty($k["variable_type"]) || $k["variable_type"]=="NULL") {
                $variable_type=$k["widget"];
            }
            else {
                $variable_type=$k["variable_type"];
                if ($k["multiselect"]=="yes") {
                    $variable_type.="|multi";
                }
            }
            $fields = array($k["page"],$k["box_id"],$k["variable_name"],$variable_type);
            $demogs = $_MX_form->get_dependency($k,"element","text");
            $fields[] = $demogs;
            $demogs = $_MX_form->get_dependency($k,"element","text",1);
            $fields[] = $demogs;
            mx_csv_row($fields);
        }
    }
    mx_csv_row(array());
    mx_csv_row(array("Oldal feltételek"));
    mx_csv_row(array("Oldal","","","","Feltétel"));
    $query="select page_id,dependency from form_page where form_id='$_MX_form->form_id' and length(dependency) order by page_id";
    $res=mysql_query($query);
    if ($res && $count=mysql_num_rows($res)) {
        while ($k=mysql_fetch_array($res)) {
            mx_csv_row(array($k["page_id"],"","","",$_MX_form->get_dependency($k,"page","text")));
        }
    }
    exit;
}

function mx_csv_row($fields) {

    global $debug,$codeset;

    for ($i=0;$i<count($fields);$i++) {
        $fields[$i]=str_replace("\"","\"\"",$fields[$i]);
        $fields[$i]="\"$fields[$i]\"";
    }
    $row = implode(";",$fields);
    if ($codeset!="utf8") {
        $row = @iconv("utf8","$codeset//IGNORE",$row);
    }

    print $row . ($debug?"<br>":"\n");
}

?>
