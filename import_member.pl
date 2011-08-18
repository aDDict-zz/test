#!/usr/bin/perl 

# this script is used to import users in the background.
# called by mygroups11.php indirectly through xmlreq table, uses subscribe system.
 
use DBI;
$_setchdir = $0;
$_setchdir =~ s/[^\/]*$//;
chdir($_setchdir);
require "./common.pl";
require $MX_SCRIPT_ROOT . "/sub_engine";
require $MX_SCRIPT_ROOT . "/common_functions.pl";
require Text::CSV_XS;

$dbh = DBI->connect("DBI:mysql:$DB_NAME:$DB_AUTH_HOST:$DB_AUTH_PORT",$DB_AUTH_USER,$DB_AUTH_PW) || die $DBI::errstr ;
$dbh->{RaiseError} = 1 ;
$dbh->do("set names utf8");

$sth = $dbh->prepare("select id,job_input,group_id,codeset from xmlreq 
                      where job_type='import_member' and status='queued' and id=".$ARGV[0]." order by id limit 1");
$sth->execute;
while (@row = $sth->fetchrow_array) { 
    $job_id = $row[0];          
    $codeset = $row[3];          
    $s2=$dbh->prepare("update xmlreq set status='processing',process_start=now() where id='$job_id'");
    $s2->execute;
    $s2->finish;
    @input = split(/\|/,$row[1]);           # all the needed input.
    $group_id = $row[2];          
    $csv_file = $input[0];                  # the source file
    $firstline = $input[1];         
    $delim_main = $input[2];
    my $csv = Text::CSV_XS->new({'binary' => 1, 'sep_char' => $delim_main});
    $delim_multi = $input[3];
    $delim_multi = "\\|" if ($delim_multi eq "pipe");
    $active_only="no";
    if ($input[4] eq "old_user_trusted") {  # set trusted affiliate for the sub_engine:process_newdata function
        $trusted_affiliate="yes";
    }
    elsif ($input[4] eq "old_user_notrusted") {
        $trusted_affiliate="no";
    }
    elsif ($input[4] eq "old_user_active_trusted") {  # set trusted affiliate for the sub_engine:process_newdata function
        $trusted_affiliate="yes";
        $active_only="yes";
    }
    elsif ($input[4] eq "old_user_active_notrusted") {
        $trusted_affiliate="no";
        $active_only="yes";
    }
    else {
        $trusted_affiliate="noupdate";
    }
    @idemog = split(/,/,$input[5]);         # ids of the coming demog, in this order
    $aff = $input[6];                       # the affiliate id
    $aff = 0 if (!($aff =~ /^[0-9]+$/));
    $update_by_id = $input[7];              # if 1, we only update, by user id.
    $update_by_id = 0 if ($update_by_id != 1);
    unshift(@idemog,0) if ($update_by_id == 1);
    $multi=0;                               # prepare params for the sub_get_groups function
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
    $subscribe_action="import_user";
    $implogfile="$MX_SUBSCRIBE_LOG/$group-$subscribe_id.$subscribe_action";
    open (OUT, ">$implogfile.notok");
    $csv_file =~ /\/([^\/]+)$/;
    print OUT "csv file:$1; firstline:$firstline; trusted_affiliate: $trusted_affiliate; affiliate id: $aff; update by id:$update_by_id\n";
    close OUT;
    sub_get_groups();
    sub_group_data();
    
    undef @job_errors;
    undef @job_output;
    undef %enum_demogs;                     # ids of enum demog ids whose values are retreived 
    undef %enum_demogvals;                  # the retreived values
    undef %enum_demogvalids;                # the ids of retreived values
    $progress=0;
    $lines=0;
    $csv_exists=1;
    $m_new=0;
    $m_updated=0;
    $m_total=0;
    $m_noparse=0;
    open (CSV, "<$csv_file") or $csv_exists=0;
    if ($csv_exists) {
        if ($firstline ne "fl_regular") {
            my $frcsv=<CSV>;
            if ($firstline eq "fl_demogdata") {
                if ($csv->parse($frcsv)) {
                    undef @idemog;
                    my @dfld = $csv->fields;
                    for ($fld=0;$fld<=$#dfld;$fld++) {
                        if ($fld==0) {
                            if ($update_by_id) {
                                if ($dfld[0] ne "id") {
                                    push (@job_errors,"Az elsõ oszlop az id kell hogy legyen");
                                    end_job();
                                }
                            }
                            elsif ($dfld[0] ne $unique_col) {
                                push (@job_errors,"Az elsõ oszlop a unique demog info kell hogy legyen ($unique_col)");
                                end_job();
                            }
                        }
                        if ($fld==0 and $update_by_id) {
                            $idemog[0]=0;
                        }
                        else {
                            $stg = $dbh->prepare("select d.id from demog d,vip_demog vd where vd.group_id='$group_id'
                                                  and d.variable_name=". $dbh->quote($dfld[$fld]) ." and d.id=vd.demog_id");
                            $stg->execute;
                            if (@gr = $stg->fetchrow_array) {
                                $idemog[$fld]=$gr[0];
                            }
                            else {
                                push (@job_errors,"Nemlétezõ demog: $dfld[$fld]");
                                end_job();
                            }
                        }
                    }
                }
                else {
                    push (@job_errors,"Rossz demog info definíció az elsõ sorban.");
                    end_job();
                }
            }
        }
        undef %demog_var_names;
        undef @edem;
        $dvng=join(",",@idemog);
        log_sub("$dvng\n");
        if ($dvng=~/^[0-9,]+$/) {
            #log_sub("select id,variable_name from demog where id in ($dvng)");
            my $dvngq=$dbh->prepare("select id,variable_name from demog where id in ($dvng)");
            $dvngq->execute;
            while (@dvnr=$dvngq->fetchrow_array) {
                $demog_var_names{"$dvnr[0]"}=$dvnr[1];
                push (@edem,$dbh->quote($dvnr[1]));
            }
            $dvngq->finish;
        }
        else {
            log_sub ("Invalid dvng: $dvng \n");
            exit;
        }
        # set up demog data for the sub_engine, in one step for all users rather than for each user one by one
        sub_get_var_data(\@edem);
        while (<CSV>) {
            $progress+=length($_);
            log_sub("\ncsvrow: $_");
            my $errors="";
            $m_total++;
            $rdemog="# data-charset:utf8\n";                 # raw demog data for the sub_prepare_demog() function
            if ($csv->parse($_)) {
                my @fields = $csv->fields;
                unless ($codeset eq "utf8") {
                    for ($fld=0;$fld<=$#fields;$fld++) {
                        $fields[$fld] = mx_iconv($fields[$fld],$codeset,"UTF8");
                    }
                }
                $sender="";
                if ($update_by_id) {
                    my $auth_demog_name="id";
                    if ($fields[0] =~ /^[0-9]+$/) {
                        $sender="#$fields[0]";
                    }
                }
                else {
                    my $auth_demog_name=$demog_var_names{"$idemog[0]"};
                    if ($auth_demog_name eq "email") {
                        $sender=$fields[0];
                    }
                    else {
                        if ($auth_demog_name eq "mobil") {
                            $fields[0]=imp_mobil_normal($fields[0]);
                        }
                        $sender="#$auth_demog_name#$fields[0]";
                    }
                }
                $old_user_robinson="no";
                sub_authdata();
                sub_getuserid();
                $do_update=0;
                if (length($auth_col)==0) {
                    $errors.=" invalid authenticator ";
                }
                elsif (!($trusted_affiliate eq "noupdate" and $user_id) and 
                       !($input[4] eq "old_user_active_trusted" and $old_user_robinson eq "yes") and
                       !($input[4] eq "old_user_active_notrusted" and $old_user_robinson eq "yes") ) {
                    $do_update=1;
                    for ($fld=$update_by_id;$fld<=$#fields and $fld<=$#idemog;$fld++) {
                        my $demog_id=$idemog[$fld];
                        my $demog_name=$demog_var_names{$demog_id};
                        my $demog_value=$fields[$fld];
                        $demog_value=~s/^\s+//;
                        $demog_value=~s/\s+$//;
                        if (length($demog_value)) {
                            if ($demog_var_types{$demog_name} eq "date") {
                                my @dateparts=split(/[-\/\. ]/,$demog_value);
                                my $year=$dateparts[0];
                                my $month=$dateparts[1];
                                my $day=$dateparts[2];
                                if ($year>1900 && $year<2200 && $month>0 && $month<13 && $day>0 && $day<32) {
                                    $demog_value="$year-$month-$day";
                                }
                                else {
                                    $demog_value="";
                                }
                            }
                            if ($demog_var_types{$demog_name} eq "email" 
                                and !($demog_value=~/^[\.\+_a-z0-9-]+\@([0-9a-z][0-9a-z-]*\.)+[a-z]{2,4}$/i)) {
                                $errors.=" invalid email ";
                            }
                            if ($demog_var_types{$demog_name} eq "number" and !($demog_value=~/^[0-9]+$/i)) {
                                $errors.=" invalid number ";
                            }
                            if ($demog_name eq "mobil") {
                                $demog_value=imp_mobil_normal($demog_value);
                            }
                            elsif ($demog_var_types{$demog_name} eq "phone" and !($demog_value=~/^\+?[0-9]+$/i)) {
                                $errors.=" invalid phone ";
                            }
                        }
                        if ($demog_var_types{$demog_name} eq "enum" or $demog_var_types{$demog_name} eq "matrix") {
                            if ($enum_demogs{$demog_id}!=1) {
                                imp_get_enumvals($demog_id);
                            }
                            my @evals=split(/$delim_multi/,$demog_value);
                            for (@evals) {
                                my $evl=imp_prepare_enums($_);
                                if ($demog_var_types{$demog_name} eq "enum") {
                                    if (length($enum_demogvals{"$demog_id $evl"})) {
                                        $rdemog .= "# $demog_name:". $enum_demogvals{"$demog_id $evl"} ."\n";
                                    }
                                    elsif ($evl =~ /^\d+$/ and $enum_demogvalids{"$demog_id $evl"}) {
                                        $rdemog .= "# $demog_name:". $evl ."\n";
                                    }
                                }
                                elsif ($evl =~ /=/) {
                                    my @mtxparts=split(/=/,$evl);
                                    my $mt1=$enum_demogvals{"$demog_id $mtxparts[0]"};
                                    my $mt2=$enum_demogvals{"$demog_id $mtxparts[1]"};
                                    if (length($mt1) and length($mt2)) {
                                        $rdemog .= "# $demog_name:$mt1"."m$mt2\n";
                                    }
                                }
                            }
                        }
                        else {
                            $rdemog .= "# $demog_name:$demog_value\n";
                        }
                    }
                }
            }
            else {
                $errors.=" row not parsed as csv ";
            }
            if (length($errors)) {
                log_sub("Error: $errors\n");
                $m_noparse++;
            }
            elsif ($do_update and length($rdemog)>0) {
                sub_prepare_demog(1);
                sub_doupdate(-1);
                if ($user_id) {
                    $m_updated++;
                }
                else {
                    $m_new++;
                }
            }
            $s4=$dbh->prepare("update xmlreq set progress=$progress where id='$job_id'");
            $s4->execute;
            $s4->finish;
        }
        close CSV;
        unlink($csv_file);
        push (@job_output,"Összesen: $m_total tag");
        push (@job_output,"Új: $m_new tag");
        $m_notnew = $m_total - ($m_new+$m_noparse);
        push (@job_output,"Már létezõ: $m_notnew tag");
        if ($trusted_affiliate eq "yes") {
            #push (@job_output,"Adatmódosítás: $m_updated tag");
        }
        elsif ($trusted_affiliate eq "no") {
            #push (@job_output,"Új adatok felvétele: $m_updated tag");
        }
        push (@job_output,"Hibás adatok: $m_noparse tag");
    }
    else {
        push (@job_errors,".csv file megnyitas hiba");
        #print "|||$csv_file|||\n";
    }
    end_job();
}

sub end_job() {

    $jerr=$dbh->quote(join("\n",@job_errors));
    $jout=$dbh->quote(join("\n",@job_output));

    if ($#job_errors>-1) {
        $set_status="aborted";
        $isok="notok";
    }
    else {
        $set_status="ready";
        rename("$implogfile.notok", "$implogfile.ok");
        $isok="ok";
    }

    $s2=$dbh->prepare("update xmlreq set status='$set_status',job_errors=$jerr,job_output=$jout,logfile='$implogfile' where id='$job_id'");
    $s2->execute;
    $s2->finish;
    system ("grep '^[^\\!]' $implogfile.$isok > $implogfile.safelog");
    exit;
}

sub imp_prepare_enums($) {

    my $str=shift;

    $str=~s/Õ/O/g;
    $str=~s/õ/o/g;
    $str=~s/&#337;/o/g;
    $str=~s/&#336;/O/g;
    $str=~s/&#369;/u/g;
    $str=~s/&#368;/U/g;
    $str=~s/Û/U/g;
    $str=~s/û/u/g;

    return lc($str);
}             

sub imp_get_enumvals($) {

    my $demog_id=shift;
    
    my $std = $dbh->prepare("select id,enum_option from demog_enumvals where demog_id='$demog_id'");
    $std->execute;
    while (my @rw = $std->fetchrow_array) {
        my $edw = imp_prepare_enums($rw[1]);
        $enum_demogvals{"$demog_id $edw"}=$rw[0];
        $enum_demogvalids{"$demog_id $rw[0]"}=1;
    }
}

sub imp_mobil_normal($) {

    my $mobilorig=shift;
    my $mobil=$mobilorig;
    $mobil =~ s/[^\d\+]//gi;
    unless ($mobil =~ /^\+36([237]0[\d]{7})$/) {
        if ($mobil =~ /^\+?([03]6)?([237]0[\d]{7})$/) {
            $mobil = "+36$2";
        }
        else {
            $mobil = "";
        }
    }
    if ($mobil ne $mobilorig) {
        log_sub("mobil normalized: '$mobilorig'=>'$mobil'");
    }
    return $mobil;
}

