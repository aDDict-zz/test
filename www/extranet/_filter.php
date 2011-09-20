<?

class MxFilter 
{  

    var $params = array(
        "sortm"=>4,
        "maxPerPage"=>25,
        "show_user_list"=>"no",
        "user_status"=>'',
        "filtaff"=>'',
        "filt_demog"=>'',
        "filt_ug"=>'',
        "filt_email"=>'',
        "user_id"=>0
    );
    var $cookie = array("sortm","maxPerPage","show_user_list","filt_email");
    var $total_users = 0;
    var $for_popup = 0;
    
    function MxFilter($group,$for_popup=0) {
        
        $this->group = $group;
        $this->for_popup = $for_popup;
    }
    
    function GetParams() {

        $group_id = $this->group["id"];

        foreach ($this->params as $parm=>$default) {
            $cval = get_cookie("Am$parm");
            if (isset($_GET["$parm"]) || empty($cval)) {
                $this->params["$parm"] = get_http($parm,$default);
            }
            elseif (in_array($parm,$this->cookie)) {
                $this->params["$parm"] = $cval;
            }
        }
        if ($this->params["show_user_list"] != 'no' && $this->params["show_user_list"] != 'yes') {
            $this->params["show_user_list"]='no';
        }
        if ($this->params["user_status"]!='robinson' && $this->params["user_status"]!='all' && $this->params["user_status"]!='bounced') {
            $this->params["user_status"]='normal';
        }
        $this->query="select id from members where group_id='$group_id' and user_id='" . $this->params["filtaff"] . "'";
        $res=mysql_query($this->query);
        if (!($res && mysql_num_rows($res))) {
            $this->params["filtaff"]=0;
        }
        if (get_http('filt_clear','')) {
            $this->params["filt_demog"] = '';
        }
        if (get_http('filt_email_clear','')) {
            $this->params["filt_email"] = '';
        }
        if (get_http('ug_clear','')) {
            $this->params["filt_ug"] = '';
        }
        foreach ($this->params as $parm=>$default) {
            // remember only these in cookies; the rest should be passed through get or post
            if (in_array($parm,$this->cookie)) {
                setcookie("Am$parm",$this->params["$parm"],time()+30*24*3600);
            }
        }
    }
    
    function GetSql() {

        global $_MX_var,$word;

        $title = $this->group["title"];
        $group_id = $this->group["id"];

        if ($this->params["filtaff"]) {
            $filtaffpart=" and users_$title.aff='" . $this->params["filtaff"] . "' ";
        }
        else {
            $filtaffpart="";
        }

        $filter_cache_diff=0;
        $filter_cache_num=0;
        $found_filter=0;
        $this->filt_demog_options="";
        $r3=mysql_query("select id,name,cache_num,to_days(cache_date) as cddn,to_days(now()) as ndn
                         from filter where group_id='$group_id' and archived='no' order by name");
        if ($r3 && mysql_num_rows($r3)) {
            while ($k3=mysql_fetch_array($r3)) {
                if ($k3["id"]==$this->params["filt_demog"]) {
                    $selected="selected";
                    $found_filter=1;
                    $filter_cache_num=$k3["cache_num"];
                    $filter_cache_diff=($k3["cddn"]+$_MX_var->filter_cache_expire)-$k3["ndn"]; 
                    #so if this is>=0, cached data is not yet expired. See common.php for more.
                    $filter_cache_age=$k3["ndn"]-$k3["cddn"];
                }
                else {
                    $selected="";
                }
                $this->filt_demog_options .= "<option $selected value='$k3[id]'>$k3[name]</option>";
            }
        }
        if (!$found_filter) {
            $this->params["filt_demog"]=0;
        }

        $sfilt_email=slasher($this->params["filt_email"],1);
        if (!empty($this->params["filt_email"])) {
            $email_filt_part=" and ui_email like '%$sfilt_email%' ";
        }
        else {
            $email_filt_part="";
        }

        $found_ug=0;
        $this->ug_options="";
        $r3=mysql_query("select id,name from user_group where group_id='$group_id' order by name");
        if ($r3 && mysql_num_rows($r3)) {
            while ($k3=mysql_fetch_array($r3)) {
                if ($k3["id"]==$this->params["filt_ug"]) {
                    $selected="selected";
                    $found_ug=1;
                }
                else
                    $selected="";
                $this->ug_options.="<option $selected value='$k3[id]'>$k3[name]</option>";
            }
        }
        if (!$found_ug) {
            $this->params["filt_ug"]=0;
        }

        $sfilt_email=slasher($this->params["filt_email"],1);
        if (!empty($this->params["filt_email"])) {
            $email_filt_part=" and ui_email like '%$sfilt_email%' ";
        }
        else {
            $email_filt_part="";
        }

        # in the redefinition of 'normal member - teljes jogu tag', normal members are those who are 
        # validated, not robinson AND not bounced. The validation system has been changed in 2002-03,
        # since then there will be no new users in users_* tables with flag validated='no', but it is
        # needed here for backward compatibility, for older subscribes. Now not valiadated users will
        # not be listed by this script, they are scattered among users_$title (old subs system),
        # validation and multivalidation tables, number of not validated subs will be in stats only.
        # carefully with bounced flag, because it is built into filter engine, whether to use or not. 

        $status_bounced="";
        if ($this->params["user_status"]=='all') {
            $status_str="";
            $this->date_type="date";
            $this->date_type_string=$word["subs_date"];
        }
        elseif ($this->params["user_status"]=='robinson') {
            $status_str=" and robinson='yes' and validated='yes'";
            $this->date_type="unsub_date";
            $this->date_type_string=$word["unsub_date"];
        }
        elseif ($this->params["user_status"]=='bounced') {
            $status_str=" and bounced='yes' and validated='yes'";
            $this->date_type="date";
            $this->date_type_string=$word["subs_date"];
        }
        else {
            $status_str=" and validated='yes' and robinson='no' ";
            $this->date_type="validated_date";
            $this->date_type_string=$word["validated_date"];
            $status_bounced=" and bounced='no' ";
        }

        logger("",$group_id,"","$status_str","users_$title");	

        $r2 = mysql_query("select count(*) from users_$title where validated='yes' and robinson='no' and bounced='no'");
        if ($r2 && mysql_num_rows($r2)) {
            $this->total_users=mysql_result($r2,0,0);
        }

        $this->mass_demog_change=0;
        $this->maxrecords=0;
        # you won't beleive, but this expression is often true, usually they will look for number of normal members whose data satisfies some filter.
        $distinct_qpart="distinct ";
        $this->from_cache = 0;
        if ($this->params["filt_demog"] && $this->params["user_status"]=="normal" && empty($this->params["filt_email"]) && empty($filtaffpart) && $filter_cache_diff>=0 
                        && $this->params["show_user_list"]!="yes" && get_http("clear_filt_cache","")!="yes" && !$this->params["filt_ug"]) {
            if ($this->total_users && $this->total_users>=$filter_cache_num) {
                $f_percent="(".number_format($filter_cache_num/$this->total_users*100,2)."%)";
            }
            else {
                $f_percent="&nbsp;";
            }
            $this->maxrecords = $filter_cache_num;
            $this->stat_text = "$word[total_of] $this->total_users $word[of_those] $filter_cache_num $f_percent $word[satisfies]\n"; 
            if (!$this->for_popup) {
                $this->stat_text .= "<br>[$word[from_cache] ($filter_cache_age $word[days_old])</span>
                                     <a href=members.php?group_id=$group_id&filt_demog=" . $this->params["filt_demog"] . "&clear_filt_cache=yes>
                                     $word[cache_refresh]</a><span class='szovegvastag'>]";
            }
            $this->from_cache = 1;
        }
        if (!$this->params["filt_demog"]) {
            if ($this->params["filt_ug"]) {
                $joinpart=",user_group_members where users_$title.id=user_group_members.user_id 
                           and user_group_members.user_group_id='" . $this->params["filt_ug"] . "'";
            }
            else {
                $joinpart=" where 1";
            }
            $this->query="from users_$title$joinpart $status_str $status_bounced $email_filt_part $filtaffpart";
            if (empty($this->params["filt_email"]) && empty($filtaffpart) && !$this->params["filt_ug"]) {
                $res=mysql_query("select count(*) $this->query");
                if ($res && mysql_num_rows($res)) {
                    $this->maxrecords=mysql_result($res,0,0);
                }
                $this->stat_text="$word[total_of] $this->maxrecords $word[m_members]";
                $not_normal_filt="";
            }
            else {
                $res=mysql_query("select count(distinct users_$title.id) $this->query");
                if ($res && mysql_num_rows($res)) {
                    $this->maxrecords=mysql_result($res,0,0);
                }
                if ($this->total_users) {
                    $f_percent="(".number_format($this->maxrecords/$this->total_users*100,2)."%)";
                }
                else {
                    $f_percent="&nbsp;";
                }
                $this->stat_text="$word[total_of] $this->total_users $word[of_those] $this->maxrecords $f_percent $word[satisfies]";
                $not_normal_filt=" +filter";
            }
        }
        else {
            if ($this->params["filt_ug"]) {
                $joinpart=",user_group_members where users_$title.id=user_group_members.user_id 
                           and user_group_members.user_group_id='" . $this->params["filt_ug"] . "' and";
            }
            else {
                $joinpart=" where";
            }
            $filtres = "";
            $filter_error="filter_ok";         //die( "$_MX_var->filter_engine " . $this->params["filt_demog"] ); 
            if ($pp=popen("$_MX_var->filter_engine " . $this->params["filt_demog"],"r")) {
                while ($buff=fgets($pp,25000)) {
                    $filtres.=$buff;
                }
                pclose($pp);
            }
            $filtarr = explode("\n",$filtres);
            if (trim($filtarr[0]) == "filter_ok") {
                $filter_qpart=trim($filtarr[1]);
                $limitord=trim($filtarr[2]);
                $limitnum=trim($filtarr[3]);
                $syntax_error=trim($filtarr[4]);
                $syntax_error_text=trim($filtarr[5]);
            }
            else {
                $filter_error="$word[filt_engine_error]: $filtarr[0]";
            }
            if ($syntax_error==1) {
                $filter_error="$word[filt_syntax_error]: $syntax_error_text";
            }     
            # bounced='no' is built into filter engine, however, in these special cases it is not needed.
            if ($this->params["user_status"]=='bounced' || $this->params["user_status"]=='all' || $this->params["user_status"]=='robinson') {
                $filter_qpart=str_replace(" users_$title.bounced='no' "," 1 ",$filter_qpart);
            }
            if (!empty($limitord)) {
                $distinct_qpart="";
            }
            else {
                $distinct_qpart="distinct ";
            }
            $this->query = "from users_$title$joinpart ($filter_qpart) $status_str $email_filt_part $filtaffpart"; //die( $this->query );
            if (empty($this->params["filt_email"]) && empty($filtaffpart) && $this->params["user_status"]=='normal' && !$this->params["filt_ug"]) {
                $this->mass_demog_change=1;
                $this->update_query = "($filter_qpart) $status_str $email_filt_part $filtaffpart";
            }
            if (!$this->from_cache) {
                $this->maxrecords=0;
                $res=mysql_query("select count($distinct_qpart users_$title.id) $this->query");
                if ($res && mysql_num_rows($res)) {
                    $this->maxrecords=mysql_result($res,0,0);
                }
                if (!empty($limitord) && $this->maxrecords>$limitnum) {
                    $this->maxrecords=$limitnum;
                }
                if (empty($this->params["filt_email"]) && empty($filtaffpart) && $this->params["user_status"]=='normal' && !$this->params["filt_ug"]) {
                    mysql_query("update filter set cache_num='$this->maxrecords',cache_date=now() where id='" . $this->params["filt_demog"] . "'");
                }
                if ($this->total_users) {
                    $f_percent="(".number_format($this->maxrecords/$this->total_users*100,2)."%)";
                }
                else {
                    $f_percent="&nbsp;";
                }
                $this->stat_text="$word[total_of] $this->total_users $word[of_those] $this->maxrecords $f_percent $word[satisfies].";
                $not_normal_filt=" +filter";
            }
            if ($filter_error!="filter_ok") {
                $this->stat_text.="<br>$filter_error";
            }
        }
        if ($this->params["user_status"]=='all') {
            $this->stat_text="$word[total_of] $this->maxrecords $word[user] [$word[st_all]$not_normal_filt]";
        }
        elseif ($this->params["user_status"]=='robinson') {
            $this->stat_text="$word[total_of] $this->maxrecords $word[user] [$word[st_unsub]$not_normal_filt]";
        }
        elseif ($this->params["user_status"]=='bounced') {
            $this->stat_text="$word[total_of] $this->maxrecords $word[user] [$word[st_bounced]$not_normal_filt]";
        }
        $this->first=get_http('first',0);
        $this->pagenum=get_http('this->pagenum',0);
        $this->params["maxPerPage"]=intval($this->params["maxPerPage"]);
        if($this->params["maxPerPage"]<1) $this->params["maxPerPage"] = 25;
        $this->pagenum=intval($this->pagenum);
        if($this->pagenum<1) $this->pagenum=1;
        if($this->first<0) $this->first=0;
        if(empty($this->first) && $this->pagenum) $this->first=($this->pagenum-1)*$this->params["maxPerPage"];
        if(!$this->first) $this->first = 0;
        if($this->first>=$this->maxrecords) $this->first = (ceil($this->maxrecords / $this->params["maxPerPage"])-1) * $this->params["maxPerPage"];
        $this->pagenum=(int)($this->first / $this->params["maxPerPage"]) + 1;
        $this->maxpages = ceil($this->maxrecords / $this->params["maxPerPage"]);
        $this->LastPage = (ceil($this->maxrecords / $this->params["maxPerPage"])-1) * $this->params["maxPerPage"];
        $this->OnePageLeft = $this->first - $this->params["maxPerPage"]; if($this->OnePageLeft<1) $this->OnePageLeft = -1;
        $this->OnePageRight = $this->params["maxPerPage"] + $this->first; if($this->OnePageRight>$this->LastPage) $this->OnePageRight = $this->LastPage;

        $this->unique_addid=0;
        if (empty($unique_col)) {
            $unique_col="email";
            $this->unique_addid=1;
        }
        if ($unique_col=="email") {
            $this->unique_title=$word["t_email"];
        }
        else {
            $this->unique_title=$unique_col;
        }
        if ($this->unique_addid) {
            $this->unique_title.=" (id)";
        }

        if (!$this->params["sortm"]) {
            $this->params["sortm"]=1;
        }
        if (!empty($limitord)) {
            $order="order by $limitord";
        }
        else {    
            switch ($this->params["sortm"]) {
                case 1: $order = "order by ui_$unique_col asc"; if ($this->unique_addid) $order.=",id asc"; break;
                case 2: $order = "order by ui_$unique_col desc"; if ($this->unique_addid) $order.=",id desc"; break;
                case 3: $order = "order by $this->date_type asc"; break;
                case 4: $order = "order by $this->date_type desc"; break;
            }
        }

        if ($this->first+$this->params["maxPerPage"]>$this->maxrecords) {
            $limitend=$this->maxrecords-$this->first;
        }
        else {
            $limitend=$this->params["maxPerPage"];
        }

        $this->query = "select $distinct_qpart ui_$unique_col,users_$title.id,$this->date_type,last_clicked,last_sent,mess_total 
                  $this->query $order limit $this->first,$limitend";
    }
}
