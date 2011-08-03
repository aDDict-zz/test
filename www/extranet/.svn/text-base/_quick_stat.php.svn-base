<?php

class MaxQucikStat {
    var $demogs=array("nem","szuletesnap","beo","study","jobstatus_new");
    var $group_id=0;
    var $message_id=0;
    var $title="";
    var $csv_output=0;
    function MaxQucikStat($group_id=0,$message_id=0,$title="",$csv_output=0,$force_demogs="") {         
        if (is_array($force_demogs)) $this->demogs=$force_demogs;
        $this->group_id=$group_id;
        $this->message_id=$message_id;
        $this->title=$title; 
        $this->csv_output=$csv_output;
        $this->calc_stat();
    }
    function calc_stat() {
        global $_MX_var,$word;
        $stat=array();
        $csv_content="Demog;Lehetséges értékek;Előfordulás\n\n";
        $q="select d.* from demog d,vip_demog vd where d.variable_name in('".implode("','",$this->demogs)."') and d.id=vd.demog_id and vd.group_id=$this->group_id";
        $res=mysql_query($q);
        if ($res && mysql_num_rows($res)) {
            while ($l=mysql_fetch_row($res)) {
                $col_name="ui_$l[2]";
                $sum=0;
                $tmp=$birthday=array();
                if ($l[2]=="szuletesnap") {
                    $month=date('m');
                    $day=date('d');        
                    $year=date('Y');
                    $min=$year-8;
                    $max=$year;
                    $res3=mysql_query("select count(*) from users_{$this->title} where validated='yes' and robinson='no' and bounced='no' and ui_szuletesnap!='0000-00-00' and clicklist like '%,{$this->message_id},%' and ui_szuletesnap>='$min-$month-$day' and ui_szuletesnap<'$max-$month-$day'"); 
                    if ($res3 && mysql_num_rows($res3)) {
                        $l3=mysql_fetch_row($res3);                    
                        if ($l3[0]) {
                            $birthday["0-8 ".$word["years_old"]]=array("0-8 ".$word["years_old"],$l3[0]);
                            $sum=$l3[0];
                        }
                    }
                    for ($bs=8;$bs<70;$bs+=3) {
                        $up=$bs+3;
                        $min=$year-$up;
                        $max=$year-$bs;
                        $res3=mysql_query("select count(*) from users_{$this->title} where validated='yes' and robinson='no' and bounced='no' and ui_szuletesnap!='0000-00-00' and clicklist like '%,{$this->message_id},%' and ui_szuletesnap>='$min-$month-$day' and ui_szuletesnap<'$max-$month-$day'"); 
                        if ($res3 && mysql_num_rows($res3)) {
                            $l3=mysql_fetch_row($res3);
                            $n_o=($bs-8)/3+2;
                            if ($l3[0]) {
                                $birthday["$bs-$up ".$word["years_old"]]=array("$bs-$up ".$word["years_old"],$l3[0]);
                                $sum+=$l3[0];                        
                            }   
                        }
                    }   
                    $res3=mysql_query("select count(*) from users_{$this->title} where validated='yes' and robinson='no' and bounced='no' and ui_szuletesnap!='0000-00-00' and clicklist like '%,{$this->message_id},%' and ui_szuletesnap>='$min-$month-$day' and ui_szuletesnap<'$max-$month-$day'");  
                    if ($res3 && mysql_num_rows($res3)) {
                        $l3=mysql_fetch_row($res3);                    
                        if ($l3[0]) {
                            $birthday["71-100 ".$word["years_old"]]=array("71-100 ".$word["years_old"],$l3[0]);
                            $sum+=$l3[0];
                        }              
                    }
                    $tmp=$birthday;
                } else {
                    $res1=mysql_query("select id,enum_option from demog_enumvals where deleted='no' and demog_id='$l[0]'");
                    $de=array();
                    while ($l1=mysql_fetch_row($res1)) {
                        $de[','.$l1[0].',']=$l1[1];
                    }                    
                    $res2=mysql_query("select $col_name,count(*) from users_{$this->title} where validated='yes' and robinson='no' and bounced='no' and $col_name!='' and clicklist like '%,{$this->message_id},%' group by $col_name");
                    $sum=0;
                    $tmp=array();
                    while ($l2=@mysql_fetch_row($res2)) {
                        if (isset($de[$l2[0]])) {
                            $l2[0]=$de[$l2[0]];
                            $tmp[$l2[0]]=$l2;
                            $sum+=$l2[1];
                        }                        
                    }
                }
                if (count($tmp)) $csv_content.=$l[1]."\n";                
                foreach($tmp as $k=>$v) {
                    $value=$v[1];
                    if ($sum>0) $value.=" (".number_format($v[1]*100/$sum,2)."%)"; else $value.=" (0%)";

                        $tmp[$k][1]=$value;
                        $csv_content.=";".$tmp[$k][0].";".$tmp[$k][1]."\n";
                }        
                if (count($tmp)) $csv_content.="\n\n";                
                $stat[$l[1]]=$tmp;                                
            }                
        }
        if ($this->csv_output) {
            $filename=$this->title."_stat_".date("Y.m.d").".csv";
            header("Content-type: application/octet-stream");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Disposition: attachment; filename=$filename");
            header("Pragma: no-cache");
            header("Expires: 0");
            $csv_content = chr(255).chr(254).mb_convert_encoding( $csv_content, 'UTF-16LE', 'UTF-8'); 
            print $csv_content;
        } else {
            echo "<pre>";
            print_r ($stat);
        }            
    }
}    
?>
