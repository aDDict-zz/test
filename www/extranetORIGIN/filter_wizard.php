<?
include "auth.php";
include "cookie_auth.php";  
include "common.php";
$language=select_lang();
include "./lang/$language/filter.lang";

$mres = mysql_query("select title,invite_text from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and user_id='$active_userid' 
                     and (membership='owner' or membership='moderator')");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else {
    header("Location: $_MX_var->baseUrl/index.php"); exit; }
$title=$rowg["title"];

$id=get_http('id',0);

$res=mysql_query("select id,name,cache_num,query_text,to_days(cache_date) as cddn,to_days(now()) as ndn
                  from filter where id='$id'");
logger($q,$group_id,"","filter_id=$id","filter");
if ($res && mysql_num_rows($res)) {
   $k=mysql_fetch_array($res);
   $what=$word["vf_edit"];
   $new=0;
   $filter_name=$k["name"];
   $filter_cache_num=$k["cache_num"];
   $filter_cache_diff=($k["cddn"]+$_MX_var->filter_cache_expire)-$k["ndn"]; 
   #so if this is>=0, cached data is not yet expired. See common.php for more.
   $filter_cache_age=$k["ndn"]-$k["cddn"]; 
   $original_text=$k["query_text"];
}
else {
   $what=$word["vf_addnew"];
   $new=1;
   $filter_cache_diff=-1;
}

if ($k["ftype"]=="advanced") {
    header("Location: mygroups15.php?group_id=$group_id&id=$id");
    exit;
}

$sgweare=19;
$weare=19;
if (get_http('id','')) $subweare=191;
else $subweare=193;
include "menugen.php";

$vipinfo=" vip_demog.group_id='$group_id' ";

$demog_count=0;
$r2=mysql_query("select count(*) from vip_demog,demog where 
                 vip_demog.demog_id=demog.id and $vipinfo");
if ($r2 && mysql_num_rows($r2)) 
    $demog_count=mysql_result($r2,0,0);

$maxstep=$demog_count+1;

# new data entered from some previous step, prev_step says where are we coming from
$prev_step=get_http('prev_step','');
$enter=get_http('enter','');
$variable_name=get_http('variable_name','');
$demog_data=get_http('demog_data','');
$name=get_http('name','');
$spec_birthday=get_http('spec_birthday','');
$spec_last_clicked=get_http('spec_last_clicked','');
$spec_last_clicked_days=get_http('spec_last_clicked_days','');
$spec_not_sent=get_http('spec_not_sent','');
$spec_not_sent_days=get_http('spec_not_sent_days','');
$spec_max=get_http('spec_max','');
$spec_max_num=get_http('spec_max_num','');
$spec_max_type=get_http('spec_max_type','');
$spec_subscribed=get_http('spec_subscribed','');
$q_sub_year=get_http('q_sub_year','');
$q_sub_month=get_http('q_sub_month','');
$q_sub_day=get_http('q_sub_day','');
$q_sub_hour=get_http('q_sub_hour','');
$q_sub_lg=get_http('q_sub_lg','');
$spec_message=get_http('spec_message','');
$spec_message_ids=get_http('spec_message_ids','');
$messbutt1=get_http('messbutt1','');
$messbutt2=get_http('messbutt2','');
$spec_ct=get_http('spec_ct','');
$spec_ct_ids=get_http('spec_ct_ids','');
$save_val=get_http('save_val','');
$go_step=get_http('go_step',0);
$go_prev=get_http('go_prev','');
$go_next=get_http('go_next','');
$finish=get_http('finish','');

if ($enter) {
    $error="";
    if ($prev_step==1) {
        if (!ereg("^[a-z][a-z0-9_]*$",$name))
            $error.="$word[vf_vnerror]<br>";
        else {
            $r2=mysql_query("select id from filter where name='$name' and id<>'$id'");
            if ($r2 && mysql_num_rows($r2))
                $error.="$word[vf_vnxerror]<br>";
        }
        if (empty($error)) {
            if ($new) {
            	$q="insert into filter (name,group_id,ftype,tstamp) values
		                    ('$name','$group_id','wizard',now())";
                mysql_query($q);
                $id=mysql_insert_id();
				logger($q,$group_id,"","filter_id=$id","filter");
            }	      
            else {
            	$q="update filter set name='$name',tstamp=now() where id='$id'";
                mysql_query($q);
                logger($q,$group_id,"","filter_id=$id","filter");		                    
            }
            $q="delete from filter_data where special='yes' and filter_id='$id'";
            mysql_query($q);
            logger($q,$group_id,"","filter_id=$id","filter_data");		                    
            if ($spec_birthday) {
	            $q="insert into filter_data (filter_id,special,variable,tstamp) 
                                 values ('$id','yes','spec_birthday',now())";
                $r3=mysql_query($q);
	            logger($q,$group_id,"","","filter_data");
	        }
            $spec_last_clicked_days=intval($spec_last_clicked_days);
            if ($spec_last_clicked) {
            	$q="insert into filter_data (filter_id,special,variable,value,tstamp) 
                                 values ('$id','yes','spec_last_clicked','$spec_last_clicked_days',now())";
                $r3=mysql_query($q);
	            logger($q,$group_id,"","","filter_data");                                 
			}
            $spec_not_sent_days=intval($spec_not_sent_days);
            if ($spec_not_sent) {
            	$q="insert into filter_data (filter_id,special,variable,value,tstamp) 
                                 values ('$id','yes','spec_not_sent','$spec_not_sent_days',now())";
                $r3=mysql_query($q);
	            logger($q,$group_id,"","","filter_data");
			}
            $spec_max_num=intval($spec_max_num);
            if ($spec_max && $spec_max_type==1) {
            	$q="insert into filter_data (filter_id,special,variable,value,tstamp) 
                                 values ('$id','yes','spec_max_lastclick','$spec_max_num',now())";
                $r3=mysql_query($q);
	            logger($q,$group_id,"","","filter_data");                                 
			}
            if ($spec_max && $spec_max_type==2) {
            	$q="insert into filter_data (filter_id,special,variable,value,tstamp) 
                                 values ('$id','yes','spec_max_lastsent','$spec_max_num',now())";
                $r3=mysql_query($q);
	            logger($q,$group_id,"","","filter_data");                                 
			}
            if ($spec_max && $spec_max_type==3) {
            	$q="insert into filter_data (filter_id,special,variable,value,tstamp) 
                                 values ('$id','yes','spec_max_random','$spec_max_num',now())";
                $r3=mysql_query($q);
				logger($q,$group_id,"","","filter_data");                    
			}
            if ($spec_subscribed) {
                $q_sub_year=intval($q_sub_year);
                $q_sub_month=intval($q_sub_month);
                $q_sub_day=intval($q_sub_day);
                $q_sub_hour=intval($q_sub_hour);
                if ($q_sub_lg!=1)
                    $q_sub_lg=2;
            	$q="insert into filter_data (filter_id,special,variable,value,tstamp) 
                                 values ('$id','yes','spec_subscribed','$q_sub_year|$q_sub_month|$q_sub_day|$q_sub_hour|$q_sub_lg',now())";
                $r3=mysql_query($q);
				logger($q,$group_id,"","","filter_data");
            }
            if ($spec_validated) {
                $q_val_year=intval($q_val_year);
                $q_val_month=intval($q_val_month);
                $q_val_day=intval($q_val_day);
                $q_val_hour=intval($q_val_hour);
                if ($q_val_lg!=1)
                    $q_val_lg=2;
                $q="insert into filter_data (filter_id,special,variable,value,tstamp) 
                                 values ('$id','yes','spec_validated','$q_val_year|$q_val_month|$q_val_day|$q_val_hour|$q_val_lg',now())";
                $r3=mysql_query($q);
				logger($q,$group_id,"","","filter_data");
            }
            if ($spec_message) {
                $spec_message_ids=slasher($spec_message_ids);
                $q="insert into filter_data (filter_id,special,variable,value,tstamp) 
                                 values ('$id','yes','spec_message','$spec_message_ids',now())";
                $r3=mysql_query($q);
				logger($q,$group_id,"","","filter_data");
            }
            if ($spec_ct) {
                $spec_ct_ids=slasher($spec_ct_ids);
                $q="insert into filter_data (filter_id,special,variable,value,tstamp) 
                                 values ('$id','yes','spec_ct','$spec_ct_ids',now())";
                $r3=mysql_query($q);
				logger($q,$group_id,"","","filter_data");
            }
            $filter_name=$name;
            $new=0;
            $filt_query=update_query_text();
        }
    }
    elseif ($variable_name) {
        $value="";
        if (is_array($demog_data)) {
            while (list(,$val)=each($demog_data)) {
                if (!empty($val)) {
                    if (!empty($value))
                        $value.="|";
                    $value.=$val;
                }
            }
        }
        mysql_query("delete from filter_data where filter_id='$id' and variable='$variable_name'");
		logger($q,$group_id,"","filter_id=$id","filter_data");
        if (!empty($value)) {
            $value=slasher($value);
            $variable_name=slasher($variable_name);
            $q="insert into filter_data (filter_id,variable,special,value,tstamp)
                         values ('$id','$variable_name','no','$value',now())";
            mysql_query($q);
			logger($q,$group_id,"","","filter_data");
        }
        $filt_query=update_query_text();
    }
}

if ($go_step)
    $step=$go_step;
if ($go_prev)
    $step=$prev_step-1;
if ($go_next)
    $step=$prev_step+1;
if ($save_val)
    $step=$prev_step;

if (!empty($error)) {
    $step=$prev_step;
    $finish=0;
}    

if ($new) {
    $step=1;
    $finish=0;
}

if ($finish) {
    # show final statistics.
    if ($filter_cache_diff<0 || $clear_filt_cache=="yes") {
        unset($filtres);
        $filter_error="filter_ok";         
        if ($pp=popen("$_MX_var->filter_engine $id","r")) {
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
        $qq="select count(*) from users_$title where validated='yes' and robinson='no' and bounced='no' 
             and ($filter_qpart)";
        //echo nl2br(htmlspecialchars($qq))."--$limitord--$limitnum";
        $res=mysql_query($qq);
        if ($res && mysql_num_rows($res)) 
            $users=mysql_result($res,0,0);
        else
            $users=0;
        if (!empty($limitord) && $users>$limitnum)
            $users=$limitnum;
        mysql_query("update filter set cache_num='$users',cache_date=now() where id='$id'");
        if ($filter_error!="filter_ok")
            $users=0;
        $cacheaddon="";
    }
    else {
        $users=$filter_cache_num;
        $cacheaddon="<br>
                     [$word[from_cache] ($filter_cache_age $word[days_old]) 
                     <a href=filter_wizard.php?group_id=$group_id&id=$id&clear_filt_cache=yes&finish=1>
                     $word[cache_refresh]</a>]<br>";
    }
    $step=$maxstep+1;
    printhead();
    echo "<input type='hidden' name='variable_name' value='$variable_name'>
          <tr><td class='backcolor' colspan='3'><span class='szovegvastag'>
          $word[vf_name]:&nbsp;$filter_name&nbsp;</span></td></tr>
          <tr><td class='backcolor' colspan='3'>&nbsp;</td></tr>
          <tr><td class='backcolor' colspan='3'>
          $word[total_of] $users $word[satisfies].$cacheaddon</td></tr>
          <tr><td class='backcolor' align='center' width=33%>
          <a href=mygroups15.php?group_id=$group_id>$word[vf_back]</a></td>
          <td class='backcolor' align='center' width=33%>&nbsp;</td>
          <td class='backcolor' align='center' width=33%>
          <a href=filter_wizard.php?group_id=$group_id&id=$id>$word[vf_beginning]</a></td></tr>";
}
else {
    # step: which step of wizard to show. 
    $step=intval($step);
    if ($step<1)
        $step=1;
    if ($step>$maxstep)
        $step=$maxstep;
    printhead();

    if ($step==1) {
        echo "<tr><td class='backcolor' colspan='3'><span class='szoveg'>
        $word[vf_name]:&nbsp;<input type='text' name='name' value='$filter_name'>
        &nbsp;</span></td></tr><tr><td class='backcolor' colspan='3'>&nbsp;</td></tr>";
        $spec_birthday=0;
        $spec_last_clicked=0;
        $spec_last_clicked_days="";
        $r3=mysql_query("select * from filter_data where special='yes' and filter_id='$id'");
        if ($r3 && mysql_num_rows($res)) {
            while ($m=mysql_fetch_array($r3)) {
                if ($m["variable"]=="spec_birthday")
                    $spec_birthday=1;
                if ($m["variable"]=="spec_last_clicked") {
                    $spec_last_clicked=1;
                    $spec_last_clicked_days=$m["value"];
                }
                if ($m["variable"]=="spec_not_sent") {
                    $spec_not_sent=1;
                    $spec_not_sent_days=$m["value"];
                }
                if ($m["variable"]=="spec_max_lastclick") {
                    $spec_max=1;
                    $spec_max_num=$m["value"];
                    $smaxtypesel[1]="checked";
                }
                if ($m["variable"]=="spec_max_lastsent") {
                    $spec_max=1;
                    $spec_max_num=$m["value"];
                    $smaxtypesel[2]="checked";
                }
                if ($m["variable"]=="spec_max_random") {
                    $spec_max=1;
                    $spec_max_num=$m["value"];
                    $smaxtypesel[3]="checked";
                }
                if ($m["variable"]=="spec_subscribed") {
                    $spec_sub="checked";
                    $qs=explode("|",$m["value"]);
                    if ($qs[4]==2)
                        $sub_lg_sel="selected";
                }
                if ($m["variable"]=="spec_validated") {
                    $spec_val="checked";
                    $qv=explode("|",$m["value"]);
                    if ($qv[4]==2)
                        $val_lg_sel="selected";
                }
                if ($m["variable"]=="spec_message") {
                    $spec_mess="checked";
                    $spec_message_ids=$m["value"];
                }
                if ($m["variable"]=="spec_ct") {
                    $spec_ctch="checked";
                    $spec_ct_ids=$m["value"];
                }
            }
        }
        if (empty($spec_sub)) {
            $qs[0]=date('Y');
            $qs[1]=date('m');
            $qs[2]=date('d');
            $qs[3]=date('H');
        }
        if (empty($spec_val)) {
            $qv[0]=date('Y');
            $qv[1]=date('m');
            $qv[2]=date('d');
            $qv[3]=date('H');
        }
        $spec_birthday?$bdsel="checked":$bdsel="";
        $spec_last_clicked?$slcsel="checked":$slcsel="";
        $spec_not_sent?$snssel="checked":$snssel="";
        $spec_max?$smaxsel="checked":$smaxsel="";
        echo "<tr><td class='backcolor' colspan='3'><span class='szoveg'>
              <input type='checkbox' name='spec_birthday' value='1' $bdsel>&nbsp;
              $word[spec_birthday]
              </span></td></tr>
              <tr><td class='backcolor' colspan='3'><span class='szoveg'>
              <input type='checkbox' name='spec_last_clicked' value='1' $slcsel>&nbsp;
              $word[spec_last_clicked1] &nbsp;
              <input type='text' name='spec_last_clicked_days' value='$spec_last_clicked_days' size='4'>
              &nbsp;$word[spec_last_clicked2]
              </span></td></tr>
              <tr><td class='backcolor' colspan='3'><span class='szoveg'>
              <input type='checkbox' name='spec_not_sent' value='1' $snssel>&nbsp;
              $word[spec_not_sent1] &nbsp;
              <input type='text' name='spec_not_sent_days' value='$spec_not_sent_days' size='4'>
              &nbsp;$word[spec_not_sent2]
              </span></td></tr>
              <tr><td class='backcolor' colspan='3'><span class='szoveg'>
              <input type='checkbox' name='spec_max' value='1' $smaxsel>&nbsp;
              $word[spec_max_max] &nbsp;
              <input type='text' name='spec_max_num' value='$spec_max_num' size='4'>
              <br>&nbsp;&nbsp;<input type='radio' name='spec_max_type' value='1' $smaxtypesel[1]>
              &nbsp;$word[spec_max_lastclick]
              <br>&nbsp;&nbsp;<input type='radio' name='spec_max_type' value='2' $smaxtypesel[2]>
              &nbsp;$word[spec_max_lastsent]
              <br>&nbsp;&nbsp;<input type='radio' name='spec_max_type' value='3' $smaxtypesel[3]>
              &nbsp;$word[spec_max_random]
              </span></td></tr>
              <tr><td class='backcolor' colspan='3'><span class='szoveg'>
              <input type='checkbox' name='spec_subscribed' value='1' $spec_sub>&nbsp;
              $word[spec_subscribed1]<input type='text' name='q_sub_year' size='4' value='$qs[0]' maxlength='4'>-<input type='text' name='q_sub_month' size='2' value='$qs[1]' maxlength='2'>-<input type='text' name='q_sub_day' size='2' value='$qs[2]' maxlength='2'>:<input type='text' name='q_sub_hour' size='2' value='$qs[3]' maxlength='2'> <select name='q_sub_lg'><option value='1'>$word[spec_before]</option><option value='2' $sub_lg_sel>$word[spec_after]</option></select> $word[spec_subscribed2]
              </span></td></tr>
              <tr><td class='backcolor' colspan='3'><span class='szoveg'>
              <input type='checkbox' name='spec_validated' value='1' $spec_val>&nbsp;
              $word[spec_validated1]<input type='text' name='q_val_year' size='4' value='$qv[0]' maxlength='4'>-<input type='text' name='q_val_month' size='2' value='$qv[1]' maxlength='2'>-<input type='text' name='q_val_day' size='2' value='$qv[2]' maxlength='2'>:<input type='text' name='q_val_hour' size='2' value='$qv[3]' maxlength='2'> <select name='q_val_lg'><option value='1'>$word[spec_before]</option><option value='2' $val_lg_sel>$word[spec_after]</option></select> $word[spec_validated2]
              </span></td></tr>
              <tr><td class='backcolor' colspan='3'><span class='szoveg'>
              <input type='checkbox' name='spec_message' value='1' $spec_mess>&nbsp;
              $word[spec_message1] &nbsp;
              <input type='text' name='spec_message_ids' value='$spec_message_ids'>
              <input type='button' name='messbutt1' value='...' onClick='window.open(\"message_select.php?group_id=$group_id&wizard=1\",\"message_select\", \"toolbar=no,location=no,directories=no,status=no,scrollbars=yes,width=650,height=500\"); return false;'>
              &nbsp;$word[spec_message2]
              <input type='button' value='töröl' onClick=\"document.filtform.spec_message_ids.value=''\"><br>
              <input type='checkbox' name='spec_ct' value='1' $spec_ctch>&nbsp;
              $word[spec_ct1] &nbsp;
              <input type='text' name='spec_ct_ids' value='$spec_ct_ids'>
              <input type='button' name='messbutt2' value='...' onClick='window.open(\"message_select.php?group_id=$group_id&wizard=2\",\"message_select\", \"toolbar=no,location=no,directories=no,status=no,scrollbars=yes,width=650,height=500\"); return false;'>
              &nbsp;$word[spec_ct2]
              <input type='button' value='töröl' onClick=\"document.filtform.spec_ct_ids.value=''\">
              </span></td></tr>\n";
    }
    else {
        $limit=$step-2;
        $r2=mysql_query("select variable_name,variable_type,question,demog_id 
                         from vip_demog,demog where 
                         vip_demog.demog_id=demog.id and $vipinfo
                         order by demog.question asc limit $limit,1");
        if ($r2 && mysql_num_rows($r2)) {
            $variable_name=mysql_result($r2,0,0);
            $variable_type=mysql_result($r2,0,1);
            $question=mysql_result($r2,0,2);
            $demog_id=mysql_result($r2,0,3);
            echo "<input type='hidden' name='variable_name' value='$variable_name'>
                <tr><td class='backcolor' colspan='3'><span class='szovegvastag'>
                $word[vf_name]:&nbsp;$filter_name&nbsp;</span></td></tr>
                <tr><td class='backcolor' colspan='3'>&nbsp;</td></tr>";
            $r3=mysql_query("select value from filter_data where filter_id='$id'
                             and variable='$variable_name' and special='no'");
            if ($r3 && mysql_num_rows($r3)) 
                $value="|".mysql_result($r3,0,0)."|";
            else
                $value="";
            if ($demog_id==3) {
                $ages=array("0-18","18-25","25-30","30-35","35-40","40-45","45-50","50-60","60-99");
                echo "<tr><td class='backcolor' colspan='3'><span class='szovegvastag'>
                       $word[vf_info]: $word[vf_age]</span></td></tr>";
                $i=0;
                while (list(,$val)=each($ages)) {
                    ereg("\|$val\|",$value)?$sel="checked":$sel="";
                    echo "<tr><td class='backcolor' colspan='3'><span class='szoveg'>
                          <input type='checkbox' name='demog_data[$i]' value='$val' $sel>&nbsp;$val
                          </span></td></tr>";
                    $i++;
                }
            }
            elseif ($variable_type=="enum") {
                echo "<tr><td class='backcolor' colspan='3'><span class='szovegvastag'>
                       $word[vf_info]: $question</span></td></tr>";
                $r3=mysql_query("select * from demog_enumvals where demog_id='$demog_id'");
                $i=0;
                if ($r3 && mysql_num_rows($r3)) {
                    while ($k3=mysql_fetch_array($r3)) {
                        ereg("\|".$k3["id"]."\|",$value)?$sel="checked":$sel="";
                        echo "<tr><td class='backcolor' colspan='3'><span class='szoveg'>
                              <input type='checkbox' name='demog_data[$i]' value='$k3[id]' $sel>
                              &nbsp;$k3[enum_option]
                              </span></td></tr>";
                        $i++;
                    }
                }
            }
            else {
                echo "<tr><td class='backcolor' colspan='3'><span class='szovegvastag'>
                       $word[vf_info]: $question</span></td></tr>";
                $othervals=explode("|",$value);
                $i=0;
                while (list(,$val)=each($othervals)) {
                    if (!empty($val)) {
                        $val=htmlspecialchars($val);
                        echo "<tr><td class='backcolor' colspan='3'><span class='szoveg'>
                            <input type='text' name='demog_data[$i]' value=\"$val\">
                            </span></td></tr>";
                        $i++;
                    }
                }
                echo "<tr><td class='backcolor' colspan='3'><span class='szoveg'>
                    <input type='text' name='demog_data[$i]' value='$val'>
                    </span></td></tr>
                    <tr><td class='backcolor' colspan='3'><span class='szoveg'>
                    <input type='submit' name='save_val' value='$word[submit3]'>
                    </span></td></tr>";

            }
        }
    }
    if ($step>1) 
        $backbutton="<input type='submit' name='go_prev' value='$word[vf_prev]'>";
    else
        $backbutton="&nbsp;";
    if ($step<$maxstep) 
        $nextbutton="<input type='submit' name='go_next' value='$word[vf_next]'>";
    else
        $nextbutton="&nbsp;";
    echo "<tr><td class='backcolor' colspan='3'>&nbsp;</td></tr>
          <tr><td class='backcolor' align='center' width=33%>$backbutton</td>
          <td class='backcolor' align='center' width=33%>
          <input type='submit' name='finish' value='$word[vf_finish]'></td>
          <td class='backcolor' align='center' width=33%>$nextbutton</td></tr>";
}

printfoot();

include "footer.php";

function printhead() {

    global $_MX_var,$k,$group_id,$what,$error,$word,$step,$id,$maxstep,$finish;

    if ($finish)
        $stepno=" - $word[vf_end]";
    else
        $stepno=" - $step/$maxstep $word[vf_step]";

    echo "
    <script language='JavaScript'>
      function addid(id,wizard) {
        if (wizard==1) {
            idlist=document.filtform.spec_message_ids.value;
            if (idlist.length) {
                document.filtform.spec_message_ids.value=idlist+','+id;
            }
            else {
                document.filtform.spec_message_ids.value=id;
            }
        }
        else {
            idlist=document.filtform.spec_ct_ids.value;
            if (idlist.length) {
                document.filtform.spec_ct_ids.value=idlist+','+id;
            }
            else {
                document.filtform.spec_ct_ids.value=id;
            }
        }
      }
    </script>
    <TABLE cellSpacing=0 cellPadding=0 width='100%' border=0>
      <TR>
        <TD class=MENUBORDER width='100%'>
          <TABLE cellSpacing=1 cellPadding=1 width='100%' border=0>
    <tr>
    <td colspan='3' class=bgkiemelt2 valign='top' align='left'><span class='szovegvastag'>
    $what$stepno<br>$error&nbsp;
    </span></td>
    </tr>      
    <form method=post name='filtform'>
    <input type='hidden' name='enter' value='1'>
    <input type='hidden' name='group_id' value='$group_id'>
    <input type='hidden' name='prev_step' value='$step'>
    <input type='hidden' name='id' value='$id'>
    ";
}

function printfoot() {

    global $_MX_var,$vipinfo,$id,$group_id,$word;

    $ci=0;
    $col[0]="<a href=filter_wizard.php?group_id=$group_id&id=$id>$word[vf_beginning]</a>";
    $col[1]="&nbsp;";
    $col[2]="&nbsp;";
    $res=mysql_query("select variable_name,variable_type,question,demog_id 
                      from vip_demog,demog where 
                      vip_demog.demog_id=demog.id and $vipinfo order by demog.question asc");
    if ($res && $qnum=mysql_num_rows($res)) {
        while ($k=mysql_fetch_array($res)) {
            $ci++;
            $percol=ceil($qnum/3);
            if ($ci<=$percol)
                $colnum=0;
            elseif ($ci<=$percol*2)
                $colnum=1;
            else
                $colnum=2;
            if (!empty($col[$colnum]))
                $col[$colnum].="<br>";
            $go_step=$ci+1;
            if ($k["demog_id"]==3)
                $dname="$k[question] [$word[vf_age]]";
            else
                $dname=$k["question"];
            $col[$colnum].="<a href=filter_wizard.php?group_id=$group_id&id=$id&go_step=$go_step>$dname</a>";
        }
    }
    
    echo "
    <tr><td class='backcolor' colspan='3'>&nbsp;</td></tr>
    <tr><td class='backcolor' align='left' valign='top' width=33%>$col[0]</td>
    <td class='backcolor' align='left' valign='top' width=33%>$col[1]</td>
    <td class='backcolor' align='left' valign='top' width=33%>$col[2]</td></tr>
    </form>
    </table>
    </td>
    </tr>
    </table>
    ";
}

function update_query_text() {

    global $_MX_var,$id,$filter_cache_diff,$original_text;
    
    $filt_query="";
    $r2=mysql_query("select filter_data.*,demog.id as demog_id,demog.variable_type 
                     from demog,filter_data where filter_data.filter_id='$id'
                     and demog.variable_name=filter_data.variable and filter_data.special='no'");
    if ($r2 && mysql_num_rows($r2)) {
        while ($k=mysql_fetch_array($r2)) {
            $or_part="";
            $or_parts=0;
            if ($k["demog_id"]==3) {
                $values=explode("|",$k["value"]);
                if (is_array($values)) {
                    while (list(,$val)=each($values)) {
                        if (!empty($val)) {
                            if ($or_parts)
                                $or_part.=" or ";
                            ereg("([0-9]+)-([0-9]+)",$val,$regs);
                            $min=$regs[1];
                            $max=$regs[2];
                            $or_part.=" [spec_age_$min"."_$max] ";
                            $or_parts++;
                        }
                    }
                }
            }
            elseif ($k["variable_type"]=="enum") {
                $enumvals=array();
                $r3=mysql_query("select id,enum_option from demog_enumvals where demog_id='$k[demog_id]'");
                if ($r3 && mysql_num_rows($r3)) 
                    while ($k3=mysql_fetch_array($r3)) 
                        $enumvals["$k3[id]"]=$k3["enum_option"];
                $values=explode("|",$k["value"]);
                if (is_array($values)) {
                    while (list(,$val)=each($values)) {
                        if (!empty($val)) {
                            if ($or_parts)
                                $or_part.=" or ";
                            $or_part.="$k[variable] = '$enumvals[$val]'";
                            $or_parts++;
                        }
                    }
                }
            }
            else {
                $values=explode("|",$k["value"]);
                if (is_array($values)) {
                    while (list(,$val)=each($values)) {
                        if (!empty($val)) {
                            if ($or_parts)
                                $or_part.=" or ";
                            $asl=addslashes($val);
                            $or_part.="$k[variable] like '$asl'";
                            $or_parts++;
                        }
                    }
                }
            }
            if ($or_parts) {
                if (!empty($filt_query))
                    $filt_query.=" and ";
                if ($or_parts>1)
                    $or_part=" ( $or_part ) ";
                $filt_query.=$or_part;
            }
        }
    }
    $r2=mysql_query("select * from filter_data where filter_id='$id' and special='yes'");
    if ($r2 && mysql_num_rows($r2)) {
        while ($k=mysql_fetch_array($r2)) {
            if ($k["variable"]=="spec_birthday") {
                if (!empty($filt_query))
                    $filt_query.=" and ";
                $filt_query.=" [spec_birthday] ";
            }
                $spec_birthday=1;
            if ($k["variable"]=="spec_last_clicked") {
                if (!empty($filt_query))
                    $filt_query.=" and ";
                $filt_query.=" [spec_last_clicked] = '$k[value]' ";
            }       
            if ($k["variable"]=="spec_not_sent") {
                if (!empty($filt_query))
                    $filt_query.=" and ";
                $filt_query.=" [spec_not_sent] = '$k[value]' ";
            }       
            if ($k["variable"]=="spec_max_lastclick") {
                if (!empty($filt_query))
                    $filt_query.=" and ";
                $filt_query.=" [spec_max_lastclick] = '$k[value]' ";
            }       
            if ($k["variable"]=="spec_max_lastsent") {
                if (!empty($filt_query))
                    $filt_query.=" and ";
                $filt_query.=" [spec_max_lastsent] = '$k[value]' ";
            }       
            if ($k["variable"]=="spec_max_random") {
                if (!empty($filt_query))
                    $filt_query.=" and ";
                $filt_query.=" [spec_max_random] = '$k[value]' ";
            }       
            if ($k["variable"]=="spec_subscribed") {
                if (!empty($filt_query))
                    $filt_query.=" and ";
                $qs=explode("|",$k["value"]);
                if ($qs[4]==1)
                    $operator="<";
                else
                    $operator=">";
                $filt_query.=" [spec_subscribed]$operator'$qs[0]-$qs[1]-$qs[2] $qs[3]:00:00' ";
            }       
            if ($k["variable"]=="spec_validated") {
                if (!empty($filt_query))
                    $filt_query.=" and ";
                $qs=explode("|",$k["value"]);
                if ($qs[4]==1)
                    $operator="<";
                else
                    $operator=">";
                $filt_query.=" [spec_validated]$operator'$qs[0]-$qs[1]-$qs[2] $qs[3]:00:00' ";
            }       
            if ($k["variable"]=="spec_message") {
                $ids=explode(",",$k["value"]);
                if (is_array($ids)) {
                    while (list(,$message_id)=each($ids)) {
                        $message_id=intval($message_id);
                        if ($message_id) {
                            if (!empty($filt_query))
                                $filt_query.=" and ";
                            $filt_query.=" [spec_message_$message_id] ";
                        }
                    }
                }
            }       
            if ($k["variable"]=="spec_ct") {
                $ids=explode(",",$k["value"]);
                if (is_array($ids)) {
                    while (list(,$message_id)=each($ids)) {
                        $message_id=intval($message_id);
                        if ($message_id) {
                            if (!empty($filt_query))
                                $filt_query.=" and ";
                            $filt_query.=" [spec_ct_$message_id] ";
                        }
                    }
                }
            }       
        }
    }
    if ($filt_query!=$original_text) {
        $fqs=addslashes($filt_query);
        #query text has changed, so cache is no longer valid.
        $filter_cache_diff=-1;
        mysql_query("update filter set query_text='$fqs',tstamp=now(),cache_date='2000-01-01 00:00:00' where id='$id'");
    }
    return $filt_query;
}
?>
