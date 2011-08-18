#!/usr/bin/perl 

# this script is used to export users in the background.
# called by .php indirectly through xmlreq table.
 
use DBI;
use MD5;
use Cwd;
$_setchdir = $0;
$_setchdir =~ s/[^\/]*$//;
chdir($_setchdir);
require "./common.pl";     # need this for the logdir
require "./common_functions.pl";
require Text::CSV_XS;
require Text::Iconv;

$dbh = DBI->connect("DBI:mysql:$DB_NAME:$DB_AUTH_HOST:$DB_AUTH_PORT",$DB_AUTH_USER,$DB_AUTH_PW) || die $DBI::errstr ;
$dbh->{RaiseError} = 1 ;
$dbh->do("set names utf8");

$perchunk=25000;                         # members per csv

$sth = $dbh->prepare("select id,job_input,group_id,ext_dl,job_output,codeset
                      from xmlreq where job_type='export_member' and status='queued' and
                      (ext_dl='no' or (ext_dl='yes' and unix_timestamp(now())-unix_timestamp(ext_dl_lastgen)>ext_dl_regen)) 
                      and id=".$ARGV[0]."
                      order by id limit 1");
$sth->execute;
if (@row = $sth->fetchrow_array) {
    undef @job_errors;
    undef @job_output;
    $job_id = $row[0];
    $s2=$dbh->prepare("update xmlreq set status='processing',process_start=now() where id='$job_id'");
    $s2->execute;
    $s2->finish;
    @input = split(/\|/,$row[1]);           # all the needed input.
    $group_id = $row[2];
    $ext_dl=$row[3];
    $codeset=$row[5];
    # retrieve everything in the desired codeset
    $dbh->do("set names $codeset");
    $filter_id = $input[0];
    $delim_main = $input[1];
    my $csv = Text::CSV_XS->new({'binary' => 1, 'sep_char' => $delim_main});
    $delim_multi = $input[2];
    $delim_multi = "|" if ($delim_multi eq "pipe");
    $multival_unfold = $input[3];
    $email_only = $input[4];
    $cfilt = $input[5];
    $exp_col = $input[6];
    $validated_date = $input[7];
    $fill_time = $input[8];
    $sort_by_form = $input[9];
    $stg = $dbh->prepare("select title,unique_col from groups where id='$group_id'");
    $stg->execute;
    if (@gr = $stg->fetchrow_array) {
        $group=$gr[0];
        $title=$group;                      # both needed by sub_engine, fix that later.
        $unique_col=$gr[1];
    }
    else {
        push (@job_errors,"Nemlétezõ csoport: $group_id");
        end_job();
    }
    $stg->finish;
    $subscribe_id=$job_id;                  # set up parameters for subscribe logging
    $subscribe_action="export_user";
    $implogfile="$MX_SUBSCRIBE_LOG/$group-$subscribe_id.$subscribe_action";
    # if this is data refreshment for external downloads, keep the old zipdir. (the zipname will be presumably the same)
    if ($ext_dl eq "yes" and $row[4] =~ /^(\Q$MX_EXPORT_TEMP_DIR\E\/$group_id-[0-9a-f]{5})/) {
        $zipdir=$1;
    }
    else {
        $zipdir="$MX_EXPORT_TEMP_DIR/$group_id-". substr(MD5->hexhash(rand().time()),5,5);
        system("mkdir $zipdir");
    }
    open (OUT, ">$implogfile.notok");
    print OUT "! zipdir:$zipdir;
               filter:$filter_id; multival_unfold:$multival_unfold; email_only:$email_only; column_filt:$cfilt\n";
    close OUT;
    unless (-d $zipdir) {
        push (@job_errors,"Could not create zipdir.");
        end_job();
    }
    $addfilltime=0;
    $joinformstats="";
    $selectformstats="";
    $groupformstats="";
    if ($fill_time =~ /^[0-9]+$/) {
        $joinformstats=" left join form_statistics on ui_$unique_col=form_statistics.member_id and form_statistics.form_id='$fill_time'";
        $selectformstats=",max(form_statistics.fill_time) as fill_time";
        $groupformstats=" group by ui_$unique_col";
        $addfilltime=1;
    }
    $equery="";
    if ($filter_id) {
        $stg = $dbh->prepare("select name from filter where id='$filter_id'");
        $stg->execute;
        if (@gr = $stg->fetchrow_array) {
            $addfiltname="_$gr[0]";
        }
        $stg->finish;
        undef ($filtres);
        $filter_error="filter_ok";
        open FILTER, "$MX_FILTER_ENGINE $filter_id|";
        while (<FILTER>) {
            $filtres .= $_;
        }
        close (FILTER);
        @filtarr = split /\n/,$filtres;
        if ($filtarr[0] eq "filter_ok") {
            $filter_query=$filtarr[1];
            if ($codeset ne "utf8") {
	        $filter_query = mx_iconv($filter_query,"UTF8",$codeset);
	    }
            $limitord=$filtarr[2];
            $limitnum=$filtarr[3];
            $syntax_error=$filtarr[4];
            $syntax_error_text=$filtarr[5];
        }
        else {
            $filter_error="error in filter engine: $filtarr[0]";
        }
        if ($syntax_error==1) {
            $filter_error="filter syntax error: $syntax_error_text";
        }
        if (!($filter_error eq "filter_ok")) {
            push (@job_errors,$filter_error);
            end_job();
        }
        if (length($limitord)) { 
            $limitexp=" order by $limitord limit $limitnum";
        }
        else {
            $limitexp="";
        }
        $equery="from users_$title $joinformstats where validated='yes' and robinson='no' and ($filter_query)";
    }
    @types=("id");
    @names=("id");
    %enumids=();
    %enumvals=();
    %enumcodes=();    
    @msubq=("id");
    @msubans=("id");
    @ismulti=("id");
#    if ($job_id==1991 || $job_id==2377 || $job_id==2722) {
#	    @headstr=("id");                   
#    }
#    else {
#	    @headstr=("id","email");                   
#    }
    @ufields=("users_$title.id","users_$title.ui_email");
    @headstr=("id");       
    if ($email_only) {
        @headstr=("id","email");
    }        
    if (!$email_only) {
        $cfiltpart="";
        if ($cfilt =~ /[\d,]+/) {
            $cfiltpart="and demog.id in ($cfilt)";
            $o_by="field(demog.id,$cfilt) desc";
        } else {
            $o_by=" vip_demog.ordernum";
        }
        $q="select demog.question, demog.variable_type, demog.variable_name, demog.id, demog.multiselect, demog.code
            from demog,vip_demog where demog.id=vip_demog.demog_id and vip_demog.group_id='$group_id'
            $cfiltpart order by $o_by";
        $stg = $dbh->prepare($q);
        $stg->execute;
        while (@mm = $stg->fetchrow_array) {
            push (@ufields,"users_$title.ui_$mm[2]");
            $enummulti=0;
            if ($mm[1] eq "enum" and $mm[4] eq "yes") {
                $enummulti=1;
            }
            $varname="ui_$mm[2]";
            if ( !($multival_unfold and ($mm[1] eq "matrix" or $enummulti) )) {
                push (@msubans,0);
                push (@headstr,"$mm[5] $mm[0]");
                push (@types,$mm[1]);
                push (@names,$varname); 
                push (@msubq,0);
                push (@ismulti,0);
            }
            if ($mm[1] eq "enum" or $mm[1] eq "matrix") {
                if ($sort_by_form>0) {
                    $ensql="select de.id,de.vertical,de.enum_option,de.code from 
                            demog_enumvals de inner join form_element_enumvals fev on de.id=fev.demog_enumvals_id
                            inner join form_element fe on fev.form_element_id=fe.id
                            where de.demog_id=$mm[3] and de.deleted='no' and fe.form_id=$sort_by_form order by fev.sortorder";
                }
                else {
                    $ensql="select id,vertical,enum_option,code from demog_enumvals where demog_id=$mm[3] and deleted='no'";
                }
                $r3=$dbh->prepare($ensql);
                $r3->execute;
                while (@kk=$r3->fetchrow_array) {
                    $enumids{"$kk[0]"}=$kk[0];                    
                    $enumvals{"$kk[0]"}=$kk[2];
                    $enumcodes{"$kk[0]"}=$kk[3];                    
                    if ($multival_unfold and ($enummulti or ($mm[1] eq "matrix" and $kk[1] eq "yes"))) {
                        if ($mm[1] eq "matrix" and $mm[4] eq "yes") {    # checkbox matrix
                            $ensql2="select id,enum_option,code from demog_enumvals where demog_id=$mm[3] and deleted='no' and vertical='no'";
                            $r32=$dbh->prepare($ensql2);
                            $r32->execute;
                            while (@hor=$r32->fetchrow_array) {
                                push (@msubans,$hor[0]);
                                push (@msubq,$kk[0]);
                                push (@headstr,"$mm[0] - $kk[2] = $hor[1]");
                                push (@types,$mm[1]);
                                push (@names,$varname); 
                                push (@ismulti,1);
                            }
                        }
                        else {
                            push (@msubans,0);
                            push (@msubq,$kk[0]);
                            push (@headstr,"$mm[0] - $kk[2]");
                            push (@types,$mm[1]);
                            push (@names,$varname); 
                            push (@ismulti,1) if ($mm[4] eq "yes");
                            push (@ismulti,0) if ($mm[4] ne "yes");
                        }
                    }
                }
            }
        }
        if ($validated_date) {
            push (@headstr,"Hitelesítés dátuma");
            push (@headstr,"Utolsó frissítés");
        }            
        if ($addfilltime) {
            push (@headstr,"Kitöltési idõ másodpercben");
        }
    }
    $order_by="order by validated_date";
    $chunks=1;
    if (!length($equery)) {
        $equery="from users_$title $joinformstats where robinson='no' and validated='yes'";
    }
    $equery .= $groupformstats;
    if (length($limitord)) {
        $equery .= " order by $limitord limit $limitnum";
        $count=$limitnum;
    }
    else {
        $count=0;
        $r2=$dbh->prepare("select count(*) $equery");
        $r2->execute;
        if (@k2=$r2->fetchrow_array) {
            $count=$k2[0];
        }
        $chunks=int($count/$perchunk)+1;
        if ($chunks==1) {
            $equery .=" $order_by";
        }
        $r2->finish;
    }
    $r2=$dbh->prepare("update xmlreq set progress_max='$count' where id='$job_id'");
    $r2->execute;
    $r2->finish;
    @filenames=();
    $serial="userlist_$title$addfiltname";

    $progress=0;
    for ($chunk=1;$chunk<=$chunks;$chunk++) {
        $filename="$zipdir/$serial-$chunk.csv";
        push (@filenames,$filename);
        $fpe=0;
        open (FP, ">$filename") or $fpe=1;
        if ($fpe) {
            push (@job_errors,"Could not open zipdir.");
            end_job();
        }
        $ufieldsel="users_$title.*";
        if ($#ufields>-1 and $#ufields<25) {
            $ufieldsel=join(",",@ufields) . ",validated_date,data_changed";
        }
        if ($chunks==1) {
            $cequery="select $ufieldsel$selectformstats $equery";
        }
        else {
            $cstart=($chunk-1)*$perchunk;
            $cequery="select $ufieldsel$selectformstats $equery $order_by limit $cstart,$perchunk";
        }
        if ($csv->combine(@headstr)) {
            print FP $csv->string()."\n";
        }
        else {
            push (@job_errors,"CSV internal error.");
            end_job();
        }
#print "\n$cequery\n";
        $tres3 = $dbh->prepare($cequery);
        $tres3->execute;
        while ($l=$tres3->fetchrow_hashref) {
       	@printstr=($l->{"id"});            
        if ($email_only) {
            @printstr=($l->{"id"},$l->{"ui_email"});
        }            
            for ($j=1;$j<=$#names;$j++) {
                $varname=$names[$j];
                if ($types[$j] eq "enum") {
                    $enumstr="";
                    @enumlist=split(/,/,$l->{"$varname"});
                    foreach $enumid (@enumlist) {
                        $enumid=int($enumid);
                        if ($enumid>0) {
                            if ($multival_unfold and $ismulti[$j]) {
                                if ($msubq[$j]==$enumid) {
                                    if ($exp_col eq "id") {$enumstr=$enumids{"$enumid"};}
                                    elsif ($exp_col eq "code") {$enumstr=$enumcodes{"$enumid"};}
                                    else { $enumstr="1"; }
                                }
                            }
                            else {
                                if (length($enumstr)) {
                                    $enumstr.="$delim_multi";
                                }
                                if ($exp_col eq "id") {$enumstr.=$enumids{"$enumid"};}
                                elsif ($exp_col eq "code") {$enumstr.=$enumcodes{"$enumid"};}
                                else {$enumstr.=$enumvals{"$enumid"};}    

                            }
                        }
                    }
                    if ($multival_unfold and $ismulti[$j] and $enumstr eq "" and length($l->{"$varname"})>1) {
                        $enumstr="0";
                    }
                    push (@printstr,$enumstr);
                }
                elsif ($types[$j] eq "matrix") {
                    $enumstr="";
                    @enumlist=split(/,/,$l->{"$varname"});
                    foreach $enumid (@enumlist) {
                        @msplit=split(/m/,$enumid);
                        $option_id=int($msplit[1]);
                        $subvar_id=int($msplit[0]);
                        if ($option_id>0 and $subvar_id>0) {
                            if ($multival_unfold) {
                                if ($msubq[$j]==$subvar_id and (not $ismulti[$j] or $msubans[$j]==$option_id)) {
                                    if (length($enumstr)) {
                                        $enumstr.="$delim_multi";
                                    }
                                    if ($exp_col eq "id") {$enumstr.=$enumids{"$option_id"};}
                                    elsif ($exp_col eq "code") {$enumstr.=$enumcodes{"$option_id"};}
                                    else {$enumstr.=$enumvals{"$option_id"};}                                    
                                }
                            }
                            else {
                                if (length($enumstr)) {
                                    $enumstr.="$delim_multi";
                                }
                                if ($exp_col eq "id") {$enumstr.=$enumids{$subvar_id}."=".$enumids{$option_id};}
                                elsif ($exp_col eq "code") {$enumstr.=$enumcodes{$subvar_id}."=".$enumcodes{$option_id};}
                                else {$enumstr.=$enumvals{$subvar_id}."=".$enumvals{$option_id};}
                            }
                        }
                    }
                    if ($multival_unfold and $ismulti[$j] and $enumstr eq "" and length($l->{"$varname"})>1) {
                        $enumstr="0";
                    }
                    push (@printstr,$enumstr);
                }
                else {
                    push (@printstr,$l->{"$varname"});
                }
            }
            if (!$email_only && $validated_date) {
                push (@printstr,$l->{"validated_date"});
                push (@printstr,$l->{"data_changed"});
            }
            if (!$email_only && $addfilltime) {
                push (@printstr,$l->{"fill_time"});
            }
            if ($csv->combine(@printstr)) {
                print FP $csv->string()."\n";
            }
            else {
                push (@job_errors,"CSV internal error.");
                end_job();
            }
            $progress++;
            if ($progress % 100 == 0) {
                $s4=$dbh->prepare("update xmlreq set progress=$progress where id='$job_id'");
                $s4->execute;
                $s4->finish;
            }
        }
        # DEBUG fwrite($fp,csv_line(array("$cequery")));
        close(FP);
    }
    $olddir=getcwd();
    if (length($olddir) and chdir($zipdir)) {
        $zipfile="$serial.zip";
        $command="zip -jD $zipfile";
        foreach $csvfile (@filenames) {
            $command .= " $csvfile";
        }
        $command .= " > /dev/null 2> /dev/null";
        system($command);
        foreach $csvfile (@filenames) {
            unlink("$csvfile");
        }
        chdir($olddir);
        system("chown -R www-data $zipdir");
        push (@job_output,"$zipdir/$zipfile");
    }
    else {
        push (@job_errors,"ZIP error.");
        end_job();
    }
    end_job();
}

sub end_job() {

    $jerr=$dbh->quote(join("\n",@job_errors));
    $jout=$dbh->quote(join("\n",@job_output));

    my $extadd="";
    if ($#job_errors>-1) {
        $set_status="aborted";
        $isok="notok";
    }
    else {
        if ($ext_dl eq "yes") {
            $set_status="queued";   # should be refreshed periodically.
            $extadd=",ext_dl_lastgen=now()";
        }
        else {
            $set_status="ready";
        }
        rename("$implogfile.notok", "$implogfile.ok");
        $isok="ok";
    }

    $s2=$dbh->prepare("update xmlreq set status='$set_status',job_errors=$jerr,job_output=$jout,
                       logfile='$implogfile'$extadd where id='$job_id'");
    $s2->execute;
    $s2->finish;
    system ("grep '^[^\\!]' $implogfile.$isok > $implogfile.safelog");
    exit;
}
