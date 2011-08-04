<?

class MxForm
{
    var $rcid="";
    var $test=0;
    var $form_id=0;
    var $preview=0;
    var $updater=0;
    var $group_id=0;
    var $udata=array();   // a users_* row for updater forms
    var $html_name="";    // html title
    var $header="";       // html header
    var $footer="";       // html footer
    var $title="";
    var $pages=1;         // number of pages on the form
    var $cpage="http://www.maxima.hu/form_collect.php";        // form target (collector page)
    var $numbering="no";  // page numbers
    var $css_objects=array("outer_div"=>array("div.fd","bgcolor","border","padding"),
                   "text_between_divs"=>array("div.mxtb","bgcolor","border","padding","fontsize","fontfamily"),
                   "box_divs"=>array("div.mxb","bgcolor","border","padding","width"),
                   "button_divs"=>array("div.mxbut","bgcolor","border","padding","width"),
                   "box_title"=>array(".mxtit","color","fontsize","fontfamily"),
                   "inner_divs"=>array("div.mx","bgcolor","border","padding"),
                   "left_td"=>array("td.mxl","bgcolor","border","padding","width","fontsize","fontfamily"),
                   "right_td"=>array("td.mxr","bgcolor","border","padding","width"),
                   "matrix_td_hor"=>array("td.mxhor","border"),
                   "matrix_td_ver"=>array("td.mxver","border"),
                   "matrix_td_hor_odd"=>array("td.mxhorodd","bgcolor"),
                   "matrix_td_hor_even"=>array("td.mxhoreven","bgcolor"),
                   "matrix_td_ver_odd"=>array("td.mxverodd","bgcolor"),
                   "matrix_td_ver_even"=>array("td.mxvereven","bgcolor"),
                   "matrix_td_padding"=>array("td.mxpad","padding"),
                   "matrix_td_mouseover"=>array("td.mxhover","bgcolor"),
                   "matrix_td_selected"=>array("td.mxselected","bgcolor"),
                   "text"=>array(".mxt","color","fontsize","fontfamily","border"),
                   "input"=>array("input.mx","width","height","bgcolor","fontsize","fontfamily","color","border"),
                   "capinput"=>array(".capinput","width","bgcolor","height","fontsize","fontfamily","color","border"),
                   "capimg"=>array(".capimg","width","height","margin-left","margin-right"),
                   "select"=>array("select.mx","width","bgcolor","fontsize","fontfamily","border"),
                   "textarea"=>array("textarea.mx","width","bgcolor","fontsize","fontfamily","height","border"),
                   "navig"=>array("input.nav","bgcolor","color","fontsize","fontfamily","border"),
                   "separator"=>array("hr.mx","width","bgcolor","height","color","border"),
                   "comment"=>array(".comment","width","bgcolor","fontsize","fontfamily","color")
                   );
    var $i=0;             // counter for the widgets on the form.
    var $inform=array();  // array of demogs present in the form, needed for updater forms.
    var $cimlist=array();
    var $cim=array (
        "cim"=>array("utca_nev","utca_tipus","hazszam","emelet","ajto"),
        "ceg_cim"=>array("street_name_company","street_type_company","street_number_company","floor_company","door_company")
    );        
    var $tel=array (    
        "tel"=>array("tel_korzet","tel_szam"),
        "mob"=>array("mobil_korzet","mobil_szam")
    );
    var $mx_cim="";
    var $blacklist=array('cid','from','newsletter_inside','queries_which','from_history','last_from','mailer');
    var $readonly=array(); // blacklist: not to appear in update forms; readonly: not to appear in any form
    var $viral="no";
    var $inact="";
    var $output="";
    var $specpages=array("intro_page","filled_out_page","quitted_page","invalid_cid","form_inactive");
    // Until now (2008-07-10) we had no possibility to define dependency on special widgets.
    // Now we need that for the new 'tel' and 'mob' widgets.
    // The problem is that we use the demog_id as dependent_id which is in this case zero.
    // To fix this quickly, this ugly thing is needed below, here and in js script an integer is expected everywhere,
    // assuming that we will have less then 100000 demogs (this is I think a reasonable assumption)
    var $spec_widget_ids=array(
        "mob"=>100001,
        "tel"=>100002
    );
    var $enum_widgets=array("checkbox","radio","select","multiselect","checkbox_matrix","radio_matrix");
    var $has_dependent_elements=array();
    var $ga_virtual="";

    function MxForm($group_id,$group_title,$preview,$updater,$unique_id="",$test='0') 
    {
        $this->group_id=$group_id;
        $this->group_title=$group_title; // slightly redundant, but we'll know this already from auth.
        $this->preview=$preview;
        if (empty($this->preview)) {
            $this->preview=0;
        }
        $this->updater=$updater;
        $this->unique_id=$unique_id;
	    $this->test=$test;
	    $this->rcid=mt_rand(10000000,99999999);
    }
    
    function CssDefaults($object,$property) 
    {
        $css_defaults=array("bgcolor"=>"","border"=>"0 fff","padding"=>"2","width"=>"300","color"=>"000","margin-left"=>0,"margin-right"=>0,
                            "fontsize"=>"11","fontfamily"=>"Verdana, Arial, Helvetica, sans-serif","height"=>"40",
                            "left_td width"=>"310","right_td width"=>"310","input bgcolor"=>"dff","box_divs width"=>630,"input height"=>"16",
                            "capinput width"=>"94","capinput height"=>"20","capimg width"=>"84","capimg height"=>"24");
        if (isset($css_defaults["$object $property"])) {
            return $css_defaults["$object $property"];
        }
        return $css_defaults["$property"];

    }

    function InitForm($form_id,$user_id=0) 
    { //echo "fokk"; die();
        $svt=mysql_query("select demog_id from form_viral where group_id='$this->group_id'"); //echo "select demog_id from form_viral where group_id='$this->group_id'"; die();
        if ($svt && mysql_num_rows($svt)) {
            while ($svtt=mysql_fetch_array($svt)) {
                $this->readonly[]=$svtt["demog_id"];
            }
        } //print_r($svt); die();
        $svt->finish;  //print_r(get_class_methods($svt)); die();
        $this->form_id=slasher($form_id);
        $this->updater?$uadd=" and updater='yes'":$uadd="";
        $res=mysql_query("select * from form where id='$this->form_id' and group_id='$this->group_id'$uadd");
        if ($res && mysql_num_rows($res)) { //echo "fokk"; die();
            $this->SetFormDefaults($res);
        }
        elseif ($this->updater) { // no form set, try to find an updater form, needed only for live, hence the active='yes' check.
            $res=mysql_query("select * from form where group_id='$this->group_id'$uadd and active='yes' order by id desc limit 1");
            if ($res && mysql_num_rows($res)) {
                $this->SetFormDefaults($res);
            }
            else { // no updater form is available, try to get some reasonable defaults.
                $res=mysql_query("select header,footer from groups where id='$this->group_id'"); //echo "select header,footer from groups where id='$this->group_id'";die();
                if ($res && mysql_num_rows($res)) {
                    $k=mysql_fetch_array($res);
                    $this->header=$k["header"];
                    $this->footer=$k["footer"];
                }
            }
        }
        else {
            return false;
        }
        $inact=array();
        $res=mysql_query("select page_id,box_id from form_page_box where form_id='$this->form_id' and active='no'");
        if ($res && mysql_num_rows($res)) {
            while ($k=mysql_fetch_array($res)) {
                $inact[]="page='$k[page_id]' and box_id='$k[box_id]'";
            }
        }
        if (count($inact)) {
            $this->inact=" and not (". implode(" or ",$inact) . ")";
        }
        if ($this->updater) {
            $res=mysql_query("select * from users_$this->group_title where id='$user_id'");
            if ($res && mysql_num_rows($res)) {
                $this->udata=mysql_fetch_array($res);
            }
            elseif (!$this->preview) {
                return false;  // hard to update without a user (except for preview)
            }
        }
        return true;
    }

    // sets defaults if a form is available.
    function SetFormDefaults($myres) {
    
        $k=mysql_fetch_array($myres); //print_r($k); die();
        $this->form_id=$k["id"];
        $this->header=$k["header"];
        $this->footer=$k["footer"];
        $this->title=$k["title"];
        $this->pages=$k["pages"];
        $this->cpage=addslashes($k["collect_page"]);
        $this->cluster_target="form_set_maxima.php";
        $this->numbering=addslashes($k["numbering"]);
        $this->viral=$k["viral"];
        $this->megszolitas=$k["megszolitas"];
        $this->save_data_to_cookie=$k["save_data_to_cookie"];
        $this->sqlres=$k; //echo $this->header; die();
    }

    function MakeForm() 
    {
        global $_MX_var,$word,$_GET,$_MX_var;
//echo $this->header . "<br />";
        $this->header=eregi_replace("{TITLE}",$this->title,$this->header);
        $this->header=eregi_replace("{pagenumber}","<div id='__hpnum__' style='display:inline;'>1</div>",$this->header);
        $set_currentdate=""; //echo $this->footer . "<br />";  die();
        if (ereg("{currentdate}","$this->header$this->footer")) { //echo "QEQWEWQEWQEQWE"; die();
            $this->header=eregi_replace("{currentdate}","<div id='__hcdate__' style='display:inline;'></div>",$this->header);
            $this->footer=eregi_replace("{currentdate}","<div id='__fcdate__' style='display:inline;'></div>",$this->footer);
            $set_currentdate="
var cdtw=new Array('vasárnap','hétfő','kedd','szerda','csütörtök','péntek','szombat');
var cdtm=new Array('január','február','március','április','május','június','július','augusztus','szeptember','október','november','december');
var cdtn=new Date();var cdt=cdtn.getFullYear();var cdtt=cdtn.getMonth();cdt+=' '+cdtm[cdtt]+' '+cdtn.getDate()+'. ';cdtt=cdtn.getDay();cdt+=cdtw[cdtt]; mx_inner('__hcdate__',cdt);mx_inner('__fcdate__',cdt);
            ";
        }
        if (eregi("(<body[^>]+onload=['\"]?)",$this->header,$regs)) {
            $this->header=str_replace($regs[1],"$regs[1]mx_load();",$this->header);
        }
        elseif (eregi("(<body)",$this->header,$regs)) {
            $this->header=str_replace($regs[1],"$regs[1] onload='mx_load();' ",$this->header);
        }
        // for updater forms, if we take the header from the automatic web pages, they might not have the body tag,
        // but calling mx_load is essential.
        else {
            $this->header="<html><head></head><body onload='mx_load();'>\n";
        }
        if (!eregi("html",$this->footer)) {
            $this->footer="\n</body></html>";
        }
        $specpages="";
        $specpages_div="";
        foreach ($this->specpages as $sp) {
            $issp=0;
            if (!empty($this->sqlres["$sp"])) {
                $issp=1;
                $specpages_div .= str_replace("{BUTTON}","<span id='navr$sp'></span>","<div id='$sp' style='display:none;'>" . $this->sqlres["$sp"] . "</div>\n");
            }
            $specpages .= "var $sp=$issp;\n";
        } //echo $this->header; die();
        $res=mysql_query("select * from form_endlink where form_id='$this->form_id' order by id");
        $endlink_pages = array();
        $endlink_data = array();
        $ksi=0;
        if ($res && mysql_num_rows($res)) {
            while ($ks=mysql_fetch_array($res)) {
                $sp="endlink$ks[id]";
                $endlink_pages[]="'$sp'";
                $specpages_div .= str_replace("{BUTTON}","<span id='navr$sp'></span>","<div id='$sp' style='display:none;'>" . $ks["html"] . "</div>\n");
                $endlink_data[]="mxendlink[$ksi]=new Object();";
                $endlink_data[]="mxendlink[$ksi].dependency='$ks[dependency]';";
                $endlink_data[]=$this->print_dependency("endlink",$ksi,$ks["id"],1);
                $ksi++;
            }
        }
        $specpages .= "var mxendlink=new Array(); var endlink_pages=new Array(" . implode(",",$endlink_pages) . ")\n" . implode("\n",$endlink_data) . "\n";
        $specpages_div=str_replace("{BUTTON}","<span id='navr0'></div>",$specpages_div);
        $social=$this->SocialNetworkLinks();
        $specpages_div = eregi_replace("{SOCIAL_NETWORK}",$social,$specpages_div);
        $this->header=eregi_replace("(<body[^>]+>)","\\1\n$specpages_div<div id='form_wrapper' style='display:block;'>",$this->header);
        $this->footer=eregi_replace("(</body>)","</div>\\1",$this->footer);
        if (!empty($this->sqlres["ga_code"])) {
            $this->footer=preg_replace("|(</body>)|i","\n" . $this->sqlres["ga_code"] . "\n\\1",$this->footer);
            $this->ga_virtual=$this->sqlres["ga_virtual"];
            if (empty($this->ga_virtual)) {
                $this->ga_virtual="regisztracio";
            }
            if (!preg_match("|^/|",$this->ga_virtual)) {
                $this->ga_virtual = "/" . $this->ga_virtual;
            }
        }
        include ("jspacker.php");
        if (!$this->preview && !$this->updater) { // no point in saving updater forms (?)
            header("Content-Type: application/octet-stream");
            header("Content-Disposition: attachment;filename=form_generate.zip");
        }
        $this->output=eregi_replace("(<body[^>]+>)","\\1\n<a name='teteje'></a>\n",$this->header)."\n";
        $this->output .= "<script>\n$specpages";
        $mx_page=1;
        $mx_force_preview="";
        if (isset($_GET["show_page"]) && $this->preview) {
            $mx_page=max(1,abs(intval($_GET["show_page"])));
            $mx_force_preview="var mx_force_preview=1;\n";
        }
        $this->output .= "var mx_page=$mx_page;\n$mx_force_preview";
        $this->output .=  "var test=".$this->test.";\n";
        $this->output .=  "var preview=".$this->preview.";\n";
        $cookie_name=$this->save_data_to_cookie=="yes"?"mxform$this->form_id":"";
        $this->output .= "var mx_cookie='$cookie_name';\n";
        $maxima_name=$this->save_data_to_cookie=="maxima"?"mxform$this->form_id":"";
        $this->output .= "var mx_maxima='$maxima_name';\n";
        $this->output .= "var ga_virtual='$this->ga_virtual';\n";
        if ($this->preview) {
            include "form_get_maxima.php"; 
            $mx_maxima_getstr=mx_form_get($maxima_name,1);
        }
        elseif ($this->save_data_to_cookie=="maxima") {
            $mx_maxima_getstr="<? include_once \"$_MX_var->baseDir/form_get_maxima.php\"; print mx_form_get(\"$maxima_name\",0); ?>";
        }
        else {
            $mx_maxima_getstr="";
        }
        $mx_maxima_getstr=ereg_replace("[\r\n']"," ",$mx_maxima_getstr);
        $this->output .= "var mx_maxima_getstr='$mx_maxima_getstr';\n";

        ob_start();
        include("form_engine.js");
        $jstemp = ob_get_contents();
        ob_end_clean();
		if (0 && !$this->preview) { // no need for this at preview...
            $myPacker = new JavaScriptPacker($jstemp, 'Normal', true, true);
            $this->output.= $myPacker->pack();
		}
		else {
			$this->output.=$jstemp;
		} //echo $jstemp; die();
        ob_start();
        include("form_xmlreq.js");            
        $this->output .= ob_get_contents();
        ob_end_clean();
        
        // headers are done, prepare css.
        $this->default_values="";
        $this->default_values_hidden="";
        $dbvals=array();
        $res=mysql_query("select * from form_css where form_id='$this->form_id' order by object_name");
        if ($res && $count=mysql_num_rows($res)) {
            while ($k=mysql_fetch_array($res)) {
                $dbvals["$k[object_name]"]=$k["value"];
            }
        }
        $props="td.hcb{text-align:center; border-width:0px;}\ntd.mtx{height:22px;}\ntd.mxbord{border-bottom-width: 0px; border-right-width: 0px; }\ntd.mxbordleft{border-left-width: 0px; }\ntd.mxbordtop{border-top-width: 0px; }\n\n";
        $tablewidth=0;
        while (list($object,$properties)=each($this->css_objects)) {
            $tag=$properties[0];
            $tdesc="$tag {";
            if ($tag=="div.mx") {
                $tdesc.="display: none;";
            }
            if ($tag=="td.mxl") {
                $tdesc.="text-align: left;";
            }
            for ($i=1;$i<count($properties);$i++) {
                $property=$properties[$i];
                if (isset($dbvals["$object $property"])) {
                    $val=$dbvals["$object $property"];
                }
                else {
                    $val=$this->CssDefaults($object,$property);
                }
                if ($property=="bgcolor" && $val=="") { 
                    if (!in_array($object,array("matrix_td_hor_odd","matrix_td_hor_even","matrix_td_ver_odd","matrix_td_ver_even","matrix_td_mouseover","matrix_td_selected"))) {
                        $tdesc.="background-color:transparent; ";
                    }
                }
                elseif ($property=="bgcolor") { 
                    $tdesc.="background-color:#$val; ";
                }
                elseif ($property=="color") { 
                    $tdesc.="color: #$val; ";
                }
                elseif ($property=="fontfamily") { 
                    $tdesc.="font-family: $val; ";
                }
                elseif ($property=="fontsize") { 
                    $valuearr=explode(" ",$val);
                    $tdesc.="font-size: $valuearr[0]"."px; ";
                    if ($valuearr[1]) { $tdesc.=" font-weight: bold; "; } else { $tdesc.=" font-weight: normal; "; }
                    if ($valuearr[2]) { $tdesc.=" font-style: italic; "; }
                    if ($valuearr[3]) { $tdesc.=" text-decoration: underline; "; }
                }
                elseif ($property=="border") { 
                    $vals=explode(" ",$val);
                    if ($object=="matrix_td_hor") {
                        $bordername="border-top";
                    }
                    elseif ($object=="matrix_td_ver") {
                        $bordername="border-left";
                    }
                    else {
                        $bordername="border";
                    }
                    $tdesc.="$bordername: $vals[0]px #$vals[1] solid; ";
                }
                else { 
                    $tdesc.="$property: $val"."px; ";
                }
                if ($object=="input" && $property=="width") {
                    $this->input_width=$val;  // this is used for the 'cim' special widget and for the additional text to the right
                }
                if ($property=="width" && ($tag=="td.mxl" || $tag=="td.mxr")) {
                    $tablewidth+=intval($val);
                }
            }
            $tdesc.="} \n";
            $props.=$tdesc;
        }
        //$props.="table.mx { width:${tablewidth}px; }\n";
        $this->output .= "</script>\n<style>\n$props\n</style>\n<script language='javascript'>\nfunction mx_widgets() {\n";
        count($this->readonly)?$rof=" and (d.id is null or d.id not in (". implode(",",$this->readonly) ."))":$rof="";
        // now, first get widgets from the form (if there is a form)
        if ($this->form_id) {
            $r2=mysql_query("select count(*) cnt,page from form_element where form_id='$this->form_id' and (length(dependency) or length(parent_dependency))
                             and widget not in ('separator','comment','hidden') group by page");
            while ($z=mysql_fetch_array($r2)) {
                // this needs to be refined later to add only those pages that have elements that depend on another within the page
                $this->has_dependent_elements["$z[page]"] = 1;
            }
            $image_files=array();
            $r2=mysql_query("select fe.question,fe.widget,d.variable_name,fe.mandatory,fe.page,fe.box_id,d.id did,d.variable_type,fe.image,
                             fe.image_position,fe.max_num_answer,fe.rotate,
                             fe.parent_dependency,fe.dependency,fe.parent,fe.id feid,fe.hide_option,fe.direction,fe.default_value,fe.maxlength,fe.possible_values,
                             fe.errmsg,fe.additionaltext,fe.question_position,d.code as demog_code from form_element fe left join demog d on fe.demog_id=d.id 
                             where fe.form_id='$this->form_id'$rof$this->inact order by fe.page,fe.box_id,fe.sortorder");
            if ($r2 && mysql_num_rows($r2)) {
                while ($z=mysql_fetch_array($r2)) {
                    $filenames=array();
                    if ($z["image"]) {
                        $iordby="order by field(id,$z[image])";
                        $r3=mysql_query("select filename from form_images where id in($z[image]) $iordby");
                        while ($fn=mysql_fetch_array($r3)) {                    
                            $filenames[]='"'.$fn["filename"].'"';
                            $image_files[]=$fn["filename"];                                                
                        }
                    }
                    $z["filename"]="var mx_ifns=new Array(".implode(",",$filenames).")";
                    if ($this->updater) {
                        if ($z["did"]) {
                            $this->inform[]=$z["did"];
                        }
                        if ($this->VarSet($z["variable_name"],$z["widget"])) {
                            $z["default_value"]=$this->udata["ui_$z[variable_name]"];
                            $this->MakeWidget($z);
                        }
                    }
                    else {
                        $this->MakeWidget($z);
                    }
                }
            }
        }
        // if this is an updater form, but no form_id, check for all demog variables.
        elseif ($this->updater) { 
            /*$notin="";
            if (count($this->inform)) {
                $notin="and d.id not in (". implode(",",$this->inform) .")";
            }*/
            $q="select d.question,d.variable_name,d.id as did,d.variable_type,d.multiselect from vip_demog vd,demog d 
                where vd.group_id='$this->group_id' and vd.demog_id=d.id $rof order by d.id";
            $r2=mysql_query($q);
            if ($r2 && mysql_num_rows($r2)) {
                while ($z=mysql_fetch_array($r2)) {
                    if (!in_array($z["variable_name"],$this->blacklist)) {
                        switch ($z["variable_type"]) {
                            case "enum": $z["multiselect"]=="yes"?$z["widget"]="checkbox":$z["widget"]="select"; break;
                            case "matrix": $z["multiselect"]=="yes"?$z["widget"]="checkbox_matrix":$z["widget"]="radio_matrix"; break;
                            case "enum_other": $z["multiselect"]=="yes"?$z["widget"]="checkbox_other":$z["widget"]="radio_other"; break;
                            case "date": $z["widget"]="datum"; break;
                            default : $z["widget"]="input";  
                        }
                        if (in_array($z["variable_name"],$this->cim["cim"])) {
                            in_array("cim",$this->cimlist)?$z["widget"]="":$z=array("widget"=>"cim","question"=>"Cím");
                        }
                        elseif (in_array($z["variable_name"],$this->cim["ceg_cim"])) {
                            in_array("ceg_cim",$this->cimlist)?$z["widget"]="":$z=array("widget"=>"ceg_cim","question"=>"Cég címe");
                        }
                        if (!empty($z["widget"]) && $this->VarSet($z["variable_name"],$z["widget"])) {
                            $z["default_value"]=$this->udata["ui_$z[variable_name]"];
                            $z["mandatory"]="yes"; // ?...
                            $z["page"]=1;
                            $z["dependency"]="";
                            $z["parent_dependency"]="";
                            $z["hide_option"]="";
                            $z["direction"]="";
                            $z["maxlength"]=0;
                            $z["errmsg"]="";
                            $z["additionaltext"]="";
                            $z["question_position"]="normal";
                            $this->MakeWidget($z,1);
                        }
                    }
                }
            }
        }

        if ($this->updater) {
            $this->output .= "mxe[$this->i]=new mx_widget('___update___','hidden','no','','',0,0,'hidden','','$this->unique_id','',0,0,'','');\n";
        }
        else { // for MAN subscribes
            $this->output .= "mxe[$this->i]=new mx_widget('___mpr___','hidden','no','','',0,0,'hidden','','<?=$"."mpr?>','',0,0,'','');\n";
        }
        $this->i++;
        // all new forms will need this, to tell form_collect which charset is the data in so that it can tell the same to subscribe.
        $this->output .= "mxe[$this->i]=new mx_widget('___charset___','hidden','no','','',0,0,'hidden','','utf-8','',0,0,'','');\n";
        $this->i++;
        // time in seconds user spent on filling in the form.
        $this->output .= "mxe[$this->i]=new mx_widget('___time___','hidden','no','','',0,0,'hidden','','','',0,0,'','');\n";
        $this->i++;
        if ($this->viral=="yes") {
            $this->output .= "mxe[$this->i]=new mx_widget('___vct___','hidden','no','','',0,0,'hidden','','<?=$"."_GET[vct]?>','',0,0,'','');\n";
            $this->i++;
        }
        // Build the button list and page data
        if ($this->form_id) {
            $query=mysql_query("select prev_button_text,next_button_text,prev_button_url,next_button_url from form where id='$this->form_id'");            
            $global_buttons=mysql_fetch_array($query);

            $query=mysql_query("select page_id,prev_button_text,next_button_text,prev_button_url,next_button_url,
                                dependency,active,admeasure,parent_dependency,specvalid from form_page where form_id='$this->form_id'");
            $_buttons = array();
            if ($query && mysql_num_rows($query)) {
                while ($row = mysql_fetch_array($query)) {
                    $_buttons[$row['page_id']] = $row;
                }
            }
        }
        for ($i = 1; $i <= $this->pages; ++$i) {
            $admeasure="";
            $parent="";
            $specvalid="";
            if (isset($_buttons[$i])) {
                if ($_buttons[$i]['prev_button_url']) {
                    $prev = '<input type="image" border="0" src="'. htmlspecialchars($_buttons[$i]['prev_button_url']).'" ';
                } elseif ($global_buttons['prev_button_url']) {
                    $prev = '<input type="image" border="0" src="'. htmlspecialchars($global_buttons['prev_button_url']).'" ';
                } elseif ($_buttons[$i]['prev_button_text']!="<<") {
                    $prev = '<input type="button" class="nav" value=" '. htmlspecialchars($_buttons[$i]['prev_button_text']).' " ';
                } else {
                    $prev = '<input type="button" class="nav" value=" '. htmlspecialchars($global_buttons['prev_button_text']).' " ';
                }
                if ($_buttons[$i]['next_button_url']) {
                    $next = '<input type="image" border="0" src="'. htmlspecialchars($_buttons[$i]['next_button_url']).'" ';
                } elseif ($global_buttons['next_button_url']) {
                    $next = '<input type="image" border="0" src="'. htmlspecialchars($global_buttons['next_button_url']).'" ';
                } elseif ($_buttons[$i]['next_button_text']!=">>") {
                    $next = '<input type="button" class="nav" value=" '. htmlspecialchars($_buttons[$i]['next_button_text']).' " ';
                } else {
                    $next = '<input type="button" class="nav" value=" '. htmlspecialchars($global_buttons['next_button_text']).' " ';
                }                

                $admeasure=$_buttons[$i]["admeasure"];
                $parent=$_buttons[$i]["parent_dependency"];
                $specvalid=$_buttons[$i]["specvalid"];
            } 
            else {
                $prev = '<input type="button" class="nav" value=" &lt;&lt; " ';
                $next = '<input type="button" class="nav" value=" &gt;&gt; " ';
            }
            $active = $_buttons[$i]['active']=='no'?"false":"true";
            $dependency = str_replace("'","\\'",$_buttons[$i]['dependency']);
            $this->output .= "mxpage[$i]=new mx_pg('$prev','$next','$dependency',$active,'$admeasure','$parent','$specvalid');\n";
            if (!empty($dependency)) {
                $this->print_dependency("page",$i,$_buttons[$i]['page_id']);
            }
        }
        // Build the box list
        if ($this->form_id) {
            $i=0;
            $last_id='';
            $query=mysql_query("select distinct page, box_id from form_element 
                                where form_id='$this->form_id' and box_id!='0'$this->inact");
            if ($query && mysql_num_rows($query)) {
                while ($row = mysql_fetch_array($query)) {
                    $page=$row['page'];
                    $box=$row['box_id'];
                    if ("$page$box"!=$last_id) {
                        $subQuery=mysql_query("select text_before, text_after, title from form_page_box where 
                                               form_id='$this->form_id' and page_id='$row[page]' and box_id='$row[box_id]'");
                        if ($subQuery && mysql_num_rows($subQuery)) {
                            $subRow = mysql_fetch_array($subQuery);
                            $before = str_replace("'","\'",$subRow['text_before']);
                            $before = str_replace("\r\n","\\n",$before);
                            $after  = str_replace("'","\'",$subRow['text_after']);
                            $after  = str_replace("\r\n","\\n",$after);
                            $title  = str_replace("'","\'",$subRow['title']);
                        } 
                        else {
                            $before = $after = '';
                        }
                        $this->output .= "mxbox[$i]=new mx_box($row[page],'$row[box_id]','$before','$after','$title');\n";
                        ++$i;
                    }
                    $last_id="$page$box";
                }
            }
        }
        $mname=",'január','február','március','április','május','június','július','augusztus','szeptember','október','november','december','év','hónap','nap'";
        $mx_err="";
        for ($i=0;$i<10;$i++) { $i?$mx_err.=",":1; $mx_err.="'".$word["gen_err$i$this->megszolitas"]."'"; }
        $this->formlang="hu";
        if ($this->form_id==195 || $this->form_id==231 || $this->form_id==234 || $this->form_id==256 || $this->form_id==274) {
            $this->formlang="ro";
            $mname=",'ianuarie','februarie','martie','aprilie','mai','iunie','iulie','august','septembrie','octombrie','noiembrie','decembrie','anul','luna','zi'";
            $mx_err="'Nu aţi completat','câmpul.', 'Dată greşită', 'Adresă de e-mail greşită', 'Număr greşit- trebuie să conţină numai cifre', 'Parola şi confirmarea acesteia nu corespund.', 'alegeţi', 'Număr de telefon fix greşit.', 'Număr de telefon mobil greşit.','toate câmpurule solicitate. Vă rugăm bifaţi pe fiecare rând cel puţin un răspuns!'";
        }
/*
$word["gen_err0magaz"]="Nem töltötte ki a(z)";
$word["gen_err0tegez"]="Nem töltötted ki a(z)";
$word["gen_err1tegez"]=$word["gen_err1magaz"]="mezőt.";
$word["gen_err2tegez"]=$word["gen_err2magaz"]="Hibás dátum";
$word["gen_err3tegez"]=$word["gen_err3magaz"]="Hibás e-mail cím";
$word["gen_err4tegez"]=$word["gen_err4magaz"]="Hibás szám - csak számjegyekből állhat";
$word["gen_err5tegez"]=$word["gen_err5magaz"]="A jelszó és megerősítése nem egyezik meg.";
$word["gen_err6magaz"]="válasszon";
$word["gen_err6tegez"]="válassz";
$word["gen_err7magaz"]="Helytelen vezetékes telefonszám.";
$word["gen_err7tegez"]="Helytelen vezetékes telefonszám.";
$word["gen_err8magaz"]="Helytelen mobil telefonszám.";
$word["gen_err8tegez"]="Helytelen mobil telefonszám.";
$word["gen_err9tegez"]="mező minden sorát. Kérünk minden sorban jelölj legalább egy választ!";
$word["gen_err9magaz"]="mező minden sorát. Kérjük minden sorban jelöljön legalább egy választ!";
*/
        if ($this->form_id==217 || $this->form_id==176 || $this->form_id==235 || $this->form_id==253 || $this->form_id==275) {
            $this->formlang="sl";
            $mx_err="'Vyplňte, prosím, políčko',''";
            for ($i=2;$i<10;$i++) { $i?$mx_err.=",":1; $mx_err.="'".$word["gen_err$i$this->megszolitas"]."'"; }
        }
        if ($this->form_id==218 || $this->form_id==224 || $this->form_id==175 || $this->form_id==232 || $this->form_id==255 || $this->form_id==273) {
            $this->formlang="cz";
            $mx_err="'Nevyplnili jste buňku',''";
            for ($i=2;$i<10;$i++) { $i?$mx_err.=",":1; $mx_err.="'". ($i==4?"Please type your age correctly!":$word["gen_err$i$this->megszolitas"]) ."'"; }
        }
        if ($this->form_id==296) {
            $this->formlang="en";
            $mx_err="'You haven\\'t completed the','cell'";
            for ($i=2;$i<10;$i++) { $i?$mx_err.=",":1; $mx_err.="'". ($i==9?"cell":$word["gen_err$i$this->megszolitas"]) ."'"; }
        }
        $half=floor($this->input_width/2);
        $this->output .=  "}
var mname=new Array(''$mname);
var mx_err=new Array($mx_err);
var mx_pagenum=$this->pages;
var mx_formlang='$this->formlang';
var mxf;
var data='$this->form_id';
var cpage='$this->cpage';
var cluster_target='$this->cluster_target';
var numbering='$this->numbering';
var half='width:$half"."px; margin-right:4px;'
mx_init();
function mx_load() {
$this->default_values$set_currentdate }$this->mx_cim
</script>
";
        $this->output .= eregi_replace("{pagenumber}","<div id='__fpnum__' style='display:inline;'>1</div>",$this->footer);
        if (!$this->preview && !$this->updater) { // no point in saving updater forms (?)
            $dir=$_MX_var->form_imagepath . "form_$this->form_id" . time();
            if (!is_dir($dir)) mkdir($dir);
            if (!is_dir($dir."/images")) mkdir($dir."/images");
            if (!$this->html_name) {
                $this->html_name="form_generate.php";
            }
            $fh = fopen($dir."/".$this->html_name, 'w') or die("can't open file");
            fwrite($fh, $this->output);
            fclose($fh);
            if ($this->save_data_to_cookie=="maxima") {
                $fh = fopen($dir."/form_set_maxima.php", 'w') or die("can't open file");
                fwrite($fh, "<?\ninclude \"$_MX_var->baseDir/form_set_maxima.php\"\n?>");
                fclose($fh);
            }
			foreach ($image_files as $ifile) { // no need for this at preview...
				copy($_MX_var->form_imagepath.$ifile,$dir."/images/".$ifile);
			}
            //  code to create zip archive
            chdir($dir);
            @unlink("form_generate.zip");
            system("zip -rq form_generate.zip *");
            readfile("$dir/form_generate.zip");
        }
        else {
            print $this->output;
        }
        /* print "<script>
        window.location='form.php?group_id=$this->group_id';
        </script>";*/
    }

    function VarSet($variable_name,$widget) {

        if ($widget=="cim" || $widget=="ceg_cim") {
            foreach ($this->cim["$widget"] as $var) {
                if (!empty($this->udata["ui_$var"])) {
                    return true;
                }
            }
        }
        elseif ($widget=="datum" && $this->udata["ui_$variable_name"]=="0000-00-00") {
            return false;
        }
        elseif (!empty($this->udata["ui_$variable_name"])) {
            return true;
        }
        return false;
    }

    function MakeCim($type) {

        $cim=$this->cim["$type"];
        $cim_optionsarr=array();
        $cim_optvalsarr=array();
        $res=mysql_query("select de.id,de.enum_option,de.demog_id from demog d,demog_enumvals de where 
                          d.variable_name='$cim[1]' and d.id=de.demog_id order by de.enum_option");
        $ord=1;
        if ($res && mysql_num_rows($res)) {
            while ($k=mysql_fetch_array($res)) {
                $opt=ereg_replace("['\"\r\n]","",$k["enum_option"]);
                $cim_optionsarr[]="'$opt'";
                $cim_optvalsarr[]=$k["id"];
                $cord["$k[id]"]=$ord++;
            }
        }
        $cim_options=implode(",",$cim_optionsarr);
        $cim_optvals=implode(",",$cim_optvalsarr);
        if (!isset($this->input_width) || $this->input_width<200) {
            $this->input_width=200;
        }
        $utcanev_width=ceil($this->input_width*0.35)."px";
        $utca_tipus_width=ceil($this->input_width*0.2)."px";
        $hazszam_width=ceil($this->input_width*0.08)."px";
        $emelet_width=ceil($this->input_width*0.06)."px";
        $ajto_width=ceil($this->input_width*0.06)."px";
        $dv=array();
        for ($i=0;$i<count($cim);$i++) {
            if (isset($this->udata["ui_$cim[$i]"])) { $dv[$i]='value=\\"'. htmlspecialchars($this->udata["ui_$cim[$i]"]) .'\\"'; }
        }
        $dval=str_replace(",","",$this->udata["ui_$cim[1]"]);
        if (isset($cord["$dval"])) {
            $this->default_values.="mxf.${type}__$cim[1].selectedIndex=". $cord["$dval"] .";\n";
        }
        $this->mx_cim.="
function mx_${type}() { 
var ${type}_options=new Array($cim_options);\nvar ${type}_optvals=new Array($cim_optvals);
z(\"<div align='center'><input $dv[0] class='mx mxt' name='${type}__$cim[0]' title='Utcanév' style='width:$utcanev_width;' \"+odp+\"\\\"> <select class='mx mxt' style='width:$utca_tipus_width;' name='${type}__$cim[1]' \"+odp+\"\\\"><option value=' '> -- </option>\");
for(var i=0;i<${type}_options.length;i++) { z(\"<option value=\"+${type}_optvals[i]+\">\"+${type}_options[i]+\"</option>\"); } 
z(\"</select> <input $dv[2] class='mx mxt' style='width:$hazszam_width;' name='${type}__$cim[2]' \"+odp+\"\\\" title='Házszám'>sz. <input $dv[3] class='mx mxt' style='width:$emelet_width;' name='${type}__$cim[3]' \"+odp+\"\\\" title='Emelet'>em. <input $dv[4] class='mx mxt' style='width:$ajto_width;' name='${type}__$cim[4]' title='Ajtó' \"+odp+\"\\\">ajtó</div>\"); }
function mx_${type}_value() { if(mxf.${type}__$cim[1].selectedIndex==0 || mxf.${type}__$cim[0].value=='') {return '';} else { return ''+mxf.${type}__$cim[2].value; } }
    ";
    }
    function MakeTel($type) {

        $tel=$this->tel["$type"];

        if (!isset($this->input_width) || $this->input_width<200) {
            $this->input_width=200;
        }
        $korzet_width=ceil($this->input_width*0.2)."px";
        $szam_width=ceil($this->input_width*0.5)."px";
        $dv=array();
        for ($i=0;$i<count($tel);$i++) {
            if (isset($this->udata["ui_$tel$i]"])) { $dv[$i]='value=\\"'. htmlspecialchars($this->udata["ui_$tel[$i]"]) .'\\"'; }
        }
        $this->mx_cim.="
            function mx_${type}() { 
                z(\"<div>+36 <input $dv[0] onkeypress='return isNumberKey(event)' maxlength='2' class='mx mxt' name='${type}__$tel[0]' title='Körzet' style='width:$korzet_width;' \"+odp+odpf+\"\\\"> <input $dv[1] onkeypress='return isNumberKey(event)' maxlength='7' class='mx mxt' style='width:$szam_width;' name='${type}__$tel[1]' \"+odp+odpf+\"\\\" title='Szám'>\");  
            }
            function mx_${type}_value() { if (mxf.${type}__$tel[0].value=='' || mxf.${type}__$tel[1].value=='') {return '';} else { return ''+mxf.${type}__$tel[1].value; } }
    ";
    }

    function MakeWidget(&$z,$notinform=0) {

        global $_MX_var,$_MX_var; 

        $widget=$z["widget"];
        if ($this->sqlres["code_in_question"]=="yes" && !empty($z["demog_code"])) {
            $z["question"]="[$z[demog_code]] $z[question]";
        }
        $question=ereg_replace("[\r\n]","",$z["question"]);
        $question=str_replace("'","\\'",$question);
        $question2=strtr($question, array("<br>"=>" ","<BR>"=>" ","&$_MX_var->main_table_border_color;"=>"ű","&#368;"=>"Ű","&#337;"=>"ő","&#336;"=>"Ő","&nbsp;"=>" "));
        $question2=ereg_replace("<[^>]+>"," ",$question2);
        $question2=ereg_replace("  +"," ",$question2);
        $question2=ereg_replace("^ ","",$question2);
        $question2=ereg_replace(" $","",$question2);
        $wdval="";
        $special="";
        if ($widget == 'hidden') { $question=''; $question2=''; }
        if ($widget=="hidden") {
            if ($this->preview) {
                if (strlen($z["default_value"])) {
                    $wdval="$z[default_value]";
                }
                else {
                    $wdval=$_GET["$z[variable_name]"];
                }
            }
            elseif (strlen($z["default_value"])) {
                $wdval="<?=isset(\$_GET[$z[variable_name]])?\$_GET[$z[variable_name]]:$z[default_value];?>";
            }
            else {
                $wdval="<?=\$_GET[$z[variable_name]]?>";
            }
        }
        else {
            $wdval=$z["default_value"];
        }
        // for the radio matrix this means that the matrix should act as a 2D radio button set ('hides' vertical siblings)
        if ($widget=="radio_matrix" && $z["hide_option"]=="yes") {
            $special="2D";
        }
        // for the checkbox matrix this means that each row should be filled in
        if ($widget=="checkbox_matrix" && $z["hide_option"]=="yes") {
            $special="fill_each_row";
        }
        if ($widget=='captcha') {
            $mx_widget_id=$widget."__$z[feid]";
        }
        elseif ($widget=='separator' || $widget=='comment' || $widget=='cim' || $widget=='ceg_cim' || $widget=='homepage') {
            $mx_widget_id=$widget."__$this->i";
            if ($widget=="cim" || $widget=="ceg_cim") {
                $this->MakeCim($widget,$mx_widget_id);
                $this->cimlist[]=$widget;
            }
        }
        // we can assume that these will be only once in a form.
        // we need this exact name because of the 'spec_widget_id' thing, look for explanation in the class vars section.
        elseif ($widget=="tel" || $widget=="mob") {
            $mx_widget_id=$widget;
            $this->MakeTel($widget,$mx_widget_id);
        }            
        else {
            $mx_widget_id=$z["variable_name"];
        }
        // the 'cols' variable is used for horizontal checkbox and radio button rows, depends on the 'break_after' value set.
        $cols=1;
        $enum_default=-1;
        if (in_array($widget, $this->enum_widgets)) {
            $optord=array();
            $vertopts=array();
            $enum_default_list=explode(",",ereg_replace("^[^0-9]+","",$z["default_value"]));
            $sgl="";
            $options= "mxe[$this->i].options=new Array(";
            $optvals= "mxe[$this->i].optvals=new Array(";
            $optbr= "mxe[$this->i].optbr=new Array(";
            $optexc= "mxe[$this->i].optexc=new Array(";
            $optvert= "mxe[$this->i].optvert=new Array(";
            $optdep= "mxe[$this->i].optdep=new Array(";
            if ($notinform) {
                $q="select id,enum_option,'' as title,'no' as excludes_others,'no' as excludes_others,vertical from demog_enumvals 
                    where demog_id='$z[did]' and deleted='no' order by enum_option";
            }
            else {
                $q="select d.id,enum_option,fen.title,fen.excludes_others,fen.excludes_others,d.vertical,fen.dependent_value 
                    from demog_enumvals d,form_element_enumvals fen 
                    where demog_id='$z[did]' and form_element_id='$z[feid]' and fen.demog_enumvals_id=d.id 
                    and deleted='no' order by fen.sortorder,d.enum_option";
            }
            $r3=mysql_query($q);
            if ($r3 && mysql_num_rows($r3)) {
                $j=0;
                $thiscol=0;
                while ($w=mysql_fetch_array($r3)) {
                    if ($z["hide_option"]=="yes" && !in_array($widget,array("radio_matrix","checkbox_matrix"))) {
                        $option="";
                    }
                    elseif (!empty($w["title"])) {
                        $option=str_replace("'","\'",$w["title"]);
                    }
                    else {
                        $option=str_replace("'","\'",$w["enum_option"]);
                    }
                    $options.="$sgl'$option'";
                    $optvals.="$sgl$w[id]";
                    $optbr.="$sgl'$w[break_after]'";
                    $optexc.="$sgl'$w[excludes_others]'";
                    $optvert.="$sgl'$w[vertical]'";
                    // a selecteknel, ha a select megjelenese valamelyik x elemtol fugg, akkor azonfelul, hogy 
                    // maga a select megjelenik vagy nem, az x elem erteketol fuggoen mas es mas enum opciokat lehet mutatni. 
                    // csak akkor fog mukodni ha be van allitva a feltetel a selectre (nemcsak az opciokra)
                    // a select csak akkor fog megjelenni, ha egyreszt ra teljesul a feltetel, masreszt legalabb egy opciora is.
                    // javitas: ehhez kell az is, hogy a '*' feltetelkent mukodjon a selectekre es az opciokra is.
                    $optdep.="$sgl'$w[dependent_value]'";
                    $w["vertical"]=="no"?$optord["$w[id]"]=$j++:$vertopts[]=$w["id"];
                    $sgl=",";
                    $thiscol++;
                    if (in_array($widget, array("checkbox_matrix","radio_matrix"))) {
                        if ($w["vertical"]=="no") {
                            $cols++;
                        }
                    }
                    elseif ($w["break_after"]=="yes") {
                        $cols=max($cols,$thiscol);
                        $thiscol=0;
                    } 
                }
                if (in_array($widget, array("checkbox_matrix","radio_matrix"))) {
                    $cols--;
                }
                else {
                    $cols=max($cols,$thiscol);
                }
            }
        }
        $errmsg=str_replace("'","\\'",$z["errmsg"]);
        $additionaltext=str_replace("'","\\'",$z["additionaltext"]);
        $dependency=str_replace("'","\\'",$z["dependency"]);
        $parent_dependency=str_replace("'","\\'",$z["parent_dependency"]);
        $has_dependent_elements=0;
        if (isset($this->has_dependent_elements["$z[page]"])) {
            $has_dependent_elements=1;
        }
        if (!$this->preview && !$this->updater) $prv=false; else $prv=true;
        $this->output .=  "mxe[$this->i]=new mx_widget('$mx_widget_id','$widget','$z[mandatory]','$question','$question2',$z[page],'$z[box_id]','$z[variable_type]','$dependency','$wdval','$z[direction]',$cols,$z[maxlength],'$errmsg','$additionaltext','$z[question_position]','$special','$z[filename]','$z[image_position]','$prv','$z[parent]','$z[possible_values]','$z[max_num_answer]','$z[rotate]','$parent_dependency','$has_dependent_elements');\n";
        if (!empty($dependency)) {
            $this->print_dependency("element",$this->i,$z["feid"]);
        }
        if (!empty($parent_dependency)) {
            $this->print_parent_dependency($this->i,$z["feid"]);
        }
        if (in_array($widget, $this->enum_widgets)) {
             if (!in_array($widget,array("checkbox_matrix","radio_matrix"))) {
                $optbr="\n$optbr,0);";
             }
             else {
                $optbr="";
             }
             if ($widget=="checkbox" || $widget=="checkbox_matrix") {
                $optexc="\n$optexc,0);";
             }
             else {
                $optexc="";
             }
             $this->output .=  str_replace("(,0)","()","$options);\n$optvals,0);\n$optdep,0);$optbr$optexc\n");
        }
        if (in_array($widget, array("checkbox_matrix","radio_matrix"))) {
             $this->output .=  str_replace("(,0)","()","$optvert);\n");
        }
        $this->i++;
        // this is for the case when the default value is one of those which are not added to the enum widget.
        if (count($enum_default_list)) {
            isset($optord["$enum_default_list[0]"])?$enum_default=$optord["$enum_default_list[0]"]:$enum_default=-1;
        }
        if (ereg("^[0-9]+$",$z["default_value"]) && $enum_default==-1 && in_array($widget, $this->enum_widgets)) {
            $this->output .= "mxe[$this->i]=new mx_widget('___default___$mx_widget_id','hidden','no','','',0,0,'hidden','','$z[default_value]','',0,0,'');\n";
            $this->i++;
        }
    }

    function print_parent_dependency($i,$object_id) {

        $q="select f.*,d.variable_name from form_element_parent_dep f left join demog d on f.parent_id=d.id where f.form_element_id='$object_id'";
        $save=array("id"=>"parent_depids","variable_name"=>"parent_deptags","parent_columns"=>"parent_columns");
        foreach ($save as $f=>$t) {
            $$t="";
        }
        $r=mysql_query($q);
        while ($k=mysql_fetch_array($r)) {
            foreach ($save as $f=>$t) {
                $comma=empty($$t)?"":",";
                $$t.="$comma'".str_replace("'","\\'",$k["$f"])."'";
            }
        }
        $deplist="";
        foreach ($save as $f=>$t) {
            $deplist .= "mxe[$i].$t=new Array(" . $$t . ");\n";
        }
        $deplist .= "mxe[$i].parent_value=new Array();\n";
        $this->output .= $deplist;
    }

    function print_dependency($object_type,$i,$object_id,$return_data=0) {

        if ($object_type=="element") {
            $ptag="mxe[$i]";
            $q="select f.*,d.variable_name from form_element_dep f left join demog d on f.dependent_id=d.id where f.form_element_id='$object_id'";
        }
        elseif ($object_type=="endlink") {
            $ptag="mxendlink[$i]";
            $q="select f.*,d.variable_name from form_endlink_dep f left join demog d on f.dependent_id=d.id where f.form_endlink_id='$object_id'";
        }
        else { // page
            $ptag="mxpage[$i]";
            $q="select f.*,d.variable_name from form_page_dep f left join demog d on f.dependent_id=d.id where f.form_id='$this->form_id' and f.page_id='$object_id'";
        }
        $save=array("id"=>"depids","variable_name"=>"deptags","dependent_value"=>"depvals");
        foreach ($save as $f=>$t) {
            $$t="";
        }
        $r=mysql_query($q);
        while ($k=mysql_fetch_array($r)) {
            foreach ($this->spec_widget_ids as $swin=>$swiv) {
                if ($k["dependent_id"]==$swiv) {
                    $k["variable_name"]=$swin;
                }
            }
            foreach ($save as $f=>$t) {
                $comma=empty($$t)?"":",";
                $$t.="$comma'".str_replace("'","\\'",$k["$f"])."'";
            }
        }
        $deplist="";
        foreach ($save as $f=>$t) {
            $deplist .= "$ptag.$t=new Array(" . $$t . ");\n";
        }
        if ($return_data) {
            return $deplist;
        }
        else {
            $this->output .= $deplist;
        }
    }

    function MakeMenu ($admin_page,&$fd,$customt="",$customl="") 
    {
        global $_MX_var,$word;
    
        $this->admin_page=$admin_page;
        $parms="group_id=$this->group_id&form_id=$fd[id]";
        $ml=array();
        if ($fd["id"]) {
            $pages=array (
                "viral"=>array("$word[iform_viral]","form_viral.php"),
                "data_forward"=>array("$word[iform_data_forward]","data_forward.php"),
                "css"=>array("$word[iform_css]","form_css.php"),
                "elements"=>array("$word[iform_elements]","form_elements.php"),
                "change"=>array("$word[iform_change]","form_ch.php"),
                "export"=>array("$word[iform_export]","form_generate.php"),
                "preview"=>array("$word[iform_preview]","","onClick=\"window.open('form_generate.php?$parms&preview=1&cid=$this->rcid','preview');\" HREF='#'"));
            foreach ($pages as $page_id=>$pdat) {
                $page = $pdat[0];
                if ($page_id!=$this->admin_page) {
                    if (isset($pdat[2])) {
                        $page = "<a $pdat[2]>$page</a>";
                    }
                    else {
                        $page = "<a href='$pdat[1]?$parms'>$page</a>";
                    }
                }
                $ml[]=$page;
            }
            if (!empty($customt)) {
                $customt=" &gt $customt";
            }
            $cfunc=$word["iform_$admin_page"];
            if (!empty($customl)) {
                $cfunc="<a href='$customl'>$cfunc</a>";
            }
            $cid="&quot;". htmlspecialchars($fd["title"])."&quot";
        }
        else {
            $cfunc="";
            $cid=$word["iform_new"];
        }
        $this->output .= "
        <div style='border:1px $_MX_var->main_table_border_color solid;'>
            <div class='bgkiemelt2' style='padding:2px;'>
                <span class='szovegvastag'><!--<a href='form.php?group_id=$this->group_id'>$word[iform_qs]</a> &gt;--> Kiválasztott kérdőív: $cid<!--$cfunc$customt--></span>
            </div>\n";
        if (count($ml)) {
            $this->output .= "<div style='text-align:right; padding:2px;'><span class='szovegvastag'>". implode("&nbsp;&nbsp;",$ml) ."</span></div></div>\n";
        }
        $this->output .= "</div>\n";
        print $this->output;
    }

    function SocialNetworkLinks() {

        global $_MX_var;

        $social="";
        $res = mysql_query("select b.*,s.share_url,s.icon from form_banner b inner join form_social_network s on b.social_network_id=s.id where b.form_id=$this->form_id order by id");
        while ($k=mysql_fetch_array($res)) {
            $hivatkozas = $this->sqlres["live_url"] . (strstr($this->sqlres["live_url"],"?")?"&":"?") . "banner=$k[prefix]";
            $hivatkozas=$k["share_url"] . rawurlencode($hivatkozas);
            $social .= "<a onclick='mx_social(\"$hivatkozas\")' style='border:0; cursor:pointer;' target='_blank' title='Megosztás: $k[name]'><img style='margin:0 1px;' src='$_MX_var->baseUrl/$k[icon]'></a>";
        }
        return $social;
    }

    function mx_dep_check($object_type,$object_id,$result_type="text",$parent=0) {

        $res=mysql_query("select * from form_$object_type where id=$object_id");
        if ($res && mysql_num_rows($res)) {
            $k=mysql_fetch_array($res);
            return $this->get_dependency($k,$object_type,$result_type,$parent);
        }
        return false;
    }

    // this function verifies the dependency settings for an object:
    // $object is a table row of the object data, 'dependency' most importantly
    // $object_type is one of element,page,endlink
    // $result_type is one of:
    //      "text": the textual description of the dependency is returned
    //      "expression": expression suitable for the dependency editor
    //      "statistic_sql": an sql is returned that can be used to check for the element dependency in the form_save_temporary table
    // $parent=1 sets parent dependency check

    function get_dependency($object,$object_type,$result_type="text",$parent=0) {

        $textual_rep = array("!"=>" NEM ",")"=>")","("=>"(","or"=>" VAGY ","and"=>" ÉS ");

        if ($parent) {
            $dependency=$object["parent_dependency"];
            $dtbl="form_${object_type}_parent_dep";
            $dfld="parent_id";
        }
        else {
            $dependency=$object["dependency"];
            $dtbl="form_${object_type}_dep";
            $dfld="dependent_id";
        }
        $textual=array();
        $expression=array();
        $statistic_sql=array();
        $dependency = str_replace('||',' or ',$dependency);
        $dependency = str_replace('&&',' and ',$dependency);
        $dependency = preg_replace("/\(([^ ])/","( \\1",$dependency);
        $dependency = preg_replace("/([^ ])\)/","\\1 )",$dependency);
        $parts = explode(" ",$dependency);
        foreach ($parts as $part) {
            if (preg_match("/([0-9]+)/",$part,$regs)) {
                $textpart="";
                $statistic_sql_part="";
                if (ereg("^!",$part)) {
                    $textpart="NEM ";
                    $statistic_sql_part="not ";
                }
                $part=$regs[1];
                $textpart .= "(";
                $statistic_sql_part .= "(";
                $res=mysql_query("select fd.*,d.variable_name,d.variable_type from $dtbl fd,demog d where fd.$dfld=d.id and fd.id=$part");
                if ($res && mysql_num_rows($res)) {
                    $z=mysql_fetch_array($res);
                    $textpart .= $z["variable_name"];
                    if ($parent) {
                        if (strlen($z["parent_columns"])) {
                            $textpart .= " $z[parent_columns] oszlopától";
                        }
                    }
                    else {
                        if ($z["variable_type"]=="enum" || $z["variable_type"]=="matrix") {
                            if ($z["dependent_value"]=="*") {
                                $textpart .= " Bármelyik opció";
                            }
                            elseif ($z["dependent_value"]=="*2") {
                                $textpart .= " Legalább két opció";
                            }
                            else {
                                $vals=array();
                                $r2=mysql_query("select id,enum_option from demog_enumvals where id in (" . str_replace("_",",",$z["dependent_value"]) . ")");
                                if ($r2 && mysql_num_rows($r2)) {
                                    while ($w=mysql_fetch_array($r2)) {
                                        $vals["$w[id]"]="'$w[enum_option]'";
                                    }
                                }
                                $vparts=explode(",",$z["dependent_value"]);
                                $evals=array();
                                $regexp=array();
                                foreach ($vparts as $vp) {
                                    $mparts=array();
                                    $vpp=explode("_",$vp);
                                    foreach ($vpp as $vpid) {
                                        $mparts[]=$vals["$vpid"];
                                    }
                                    $evals[]=implode("=",$mparts);
                                    $regexp[]="formdata regexp '$z[variable_name]\\\\|[0-9,_]*$vp'";
                                }
                                $statistic_sql_part .= implode(" or ",$regexp);
                                $textpart .= " = " . implode(" v. ",$evals);
                            }
                        }
                        elseif ($z["dependent_value"]=="*") {
                            $textpart .= " Bármilyen érték";
                        } 
                        elseif ($z["dependent_value"]=="*O") {
                            $textpart .= " Páratlan";
                        } 
                        elseif ($z["dependent_value"]=="*E") {
                            $textpart .= " Páros";
                        } 
                        elseif (ereg("\\*([0-9]+)",$z["dependent_value"],$zrg)) {
                            $textpart .= " $zrg[1] hosszú";
                        } 
                    }
                    $textpart .= ")";
                    $statistic_sql_part .= ")";
                    $statistic_sql[]=$statistic_sql_part;
                    $textual[]=$textpart;
                    $expression[]=$z["variable_name"];
                }
            }
            elseif (isset($textual_rep["$part"])) {
                $textual[] = $textual_rep["$part"];
                if ($part=="!") {
                    $statistic_sql[]=" not ";
                    $expression[]=" not ";
                }
                else {
                    $statistic_sql[]=$part;
                    $expression[]=$part;
                }
            }
        }
        if (count($textual)==0) {
            return "";
        }
        if ($result_type=="statistic_sql") {
            return "(" . implode(" ",$statistic_sql) . ")";
        }
        if ($result_type=="expression") {
            return implode(" ",$expression);
        }
        $text = implode(" ",$textual);
        if ($object_type=="element" && !empty($text) && $parent && !empty($object["parent"])) {
            $vname=explode("|:|",$object["parent"]);
            if (!empty($vname[2])) {
                $text .= ", A mátrix " . ($vname[2]=="column"?"oszlopai":"sorai") . " függnek a szülő objektumoktól";
            }
            if (!empty($vname[4])) {
                $alw=array();
                $r3=mysql_query("select id,enum_option from demog_enumvals where id in ($vname[4])");
                if ($r3 && mysql_num_rows($r3)) {        
                    while ($k3=mysql_fetch_array($r3)) {            
                        $alw[]=$k3["enum_option"];
                    }                
                }
                $text .= ", Mindig megjelenő kérdések: " . implode("|",$alw);
            }
            if ($vname[5]=="name") {
                $text .= ", Szülő objektumok elemeinek azonosítása a függő objektum elemeinek neve (és nem a sorrend) alapján";
            }
            if ($vname[6]=="1") {
                $text .= ", Nem jelenik meg a függő objektum ha csak egy eleme/sora/oszlopa marad";
            }
            $inhr=mysql_query("select parent_dependency from form_page where form_id='$this->form_id' and page_id='$object[page]'");
            if ($inhr && mysql_num_rows($inhr)) {
                if (mysql_result($inhr,0,0)==$object["variable_name"]) {
                    $text .= ", Az oldal sem jelenik meg ha nem jelenik meg a függő objektum";
                }
            }
        }
        return $text;
    }

    // checks dependency syntax and constructs the javascript dependency string
    // dependency_ids: demog_id=>form_*_dep.id mapping
    // dependency_negation: array of demog_ids that have negation
    function set_dependency($expression,$dependency_ids,$dependency_negation,$parent=0) {

        $expr_rep = array("not"=>"!",")"=>")","("=>"(","or"=>"||","and"=>"&&");
        if (count($dependency_ids)==0) {
            return "";
        }
        $demog_ids = array();
        $res = mysql_query("select id,variable_name from demog where id in (" . implode(",",array_keys($dependency_ids)) . ")");
        while ($k=mysql_fetch_array($res)) {
            $demog_ids["$k[variable_name]"]=$k["id"];
        }

        $expression = preg_replace("/\(([^ ])/","( \\1",$expression);
        $expression = preg_replace("/([^ ])\)/","\\1 )",$expression);
        $dtaparts = explode(' ',$expression);
        $newexp = '';
        $prevpar = array();
        $has_demog = 0;
        $para = 0;
        for ($i=0;$i<count($dtaparts);$i++) {
            $term = $dtaparts[$i];
            if (in_array($term,array(')','(','not','and','or'))) {
                if (!in_array($term,array("or","and")) || $has_demog) {
                    $prevpar[]= $expr_rep["$term"];
                }
                if (in_array($term,array(")","("))) {
                    $para += $term=='('?1:-1;
                }
            }
            elseif (!empty($term)) {
                if (!isset($demog_ids["$term"])) {
                    return "error: Szintaxis: '$term'";
                }
                $neg="";
                if (in_array($demog_ids["$term"],$dependency_negation)) {
                    $neg="!"; // this one is selected from the checkboxes and not the 'not' operator in dependency string
                }
                $newexp .= implode(" ",$prevpar) . " $neg" . ($parent?"PD":"D") . $dependency_ids["$demog_ids[$term]"]; 
                $prevpar = array();
                $has_demog = 1;
            }
        }
        if ($para!=0) {
            return ("error: Zárójelezés");
        }
        foreach ($prevpar as $term) {
            if ($term!="not") {
                $newexp .= " $term";
            }
        }
        $newexp = preg_replace("/  +/"," ",$newexp);
        $newexp = preg_replace("/^ /","",$newexp);
        return $newexp;
    }
}
?>
