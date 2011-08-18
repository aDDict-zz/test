#!/usr/bin/perl 

# this script is used to import users in the background.
# called by mygroups11.php indirectly through xmlreq table.
 
use DBI;
$_setchdir = $0;
$_setchdir =~ s/[^\/]*$//;
chdir($_setchdir);
require "./common.pl";
require Text::CSV_XS;
my $csv = Text::CSV_XS->new({'binary' => 1, 'sep_char' => ';'});

my $dbh = DBI->connect("DBI:mysql:$DB_NAME:$DB_AUTH_HOST:$DB_AUTH_PORT",$DB_AUTH_USER,$DB_AUTH_PW) || die $DBI::errstr ;
$dbh->{RaiseError} = 1 ;

$sth = $dbh->prepare("select id,job_input,group_id from xmlreq where 
                      job_type='import_subgroup' and status='queued' and id=".$ARGV[0]." order by id limit 1");
$sth->execute;
while (@row = $sth->fetchrow_array) { 
    $job_id = $row[0];                   
    @input = split(/\|/,$row[1]);    # all the needed input.
    $group_id = $row[2];                   
    $csv_file = $input[0];          # the source file
    $user_group_name = $input[1];
    $authenticator = $input[2];
    $stg = $dbh->prepare("select title from groups where id='$group_id'");
    $stg->execute;
    if (@gr = $stg->fetchrow_array) {
        $title=$gr[0];
    }
    else {
        push (@job_errors,"nemletezo csoport: $group_id");
        end_job();
    }
    
    undef @job_errors;
    undef @job_output;
    $progress=0;
    
    $s2=$dbh->prepare("update xmlreq set status='processing',process_start=now() where id='$job_id'");
    $s2->execute;
    $s2->finish;
    
    $s2=$dbh->prepare("select id from user_group where name='$user_group_name' and group_id='$group_id'");
    $s2->execute;
    if (@r2 = $s2->fetchrow_array) {
        $user_group_id=$r2[0];
    }
    else {
        $s3=$dbh->prepare("insert into user_group (group_id,name,tstamp) values ('$group_id','$user_group_name',now())");
        $s3->execute;
        $user_group_id=$s3->{mysql_insertid};
        $s3->finish;
    }
    $s2->finish;
    $uglist_exists=0;
    $sg = $dbh->prepare("desc users_$title");
    $sg->execute;
    while (@rg = $sg->fetchrow_array) {
        $uglist_exists=1 if ($rg[0] eq "uglist");
    }
    $sg->finish;
    $lines=0;
    $csv_exists=1;
    $m_notmember=0;
    $m_already=0;
    $m_added=0;
    open (CSV, "<$csv_file") or $csv_exists=0;
    if ($csv_exists) {
        while (<CSV>) {
            $progress+=length($_);
            if ($csv->parse($_)) {
                my @fields = $csv->fields;
                $uid=$fields[0];
                $uid=~s/^\s+//;
                $uid=~s/\s+$//;
                if ($authenticator eq "id") {
                    if ($uid =~ /^[0-9]+$/) {
                        $wpart="where id=$uid";
                    }
                    else {
                        $wpart="where 0";
                    }
                }
                else {
                    $suid=$dbh->quote($uid);
                    if ($suid =~ /\*/) {
                        $suid =~ s/\*/%/g;
                        $wpart="where ui_$authenticator like $suid";
                    }
                    else {
                        $wpart="where ui_$authenticator=$suid";
                    }
                }
#print("select id from users_$title $wpart\n");
                $s2=$dbh->prepare("select id from users_$title $wpart");
                $s2->execute;
                $wfound=0;
                while (@r2 = $s2->fetchrow_array) {
                    $ug_uid = $r2[0];
                    $s3=$dbh->prepare("select id from user_group_members where user_id='$ug_uid' 
                                       and user_group_id='$user_group_id'");
                    $s3->execute;
                    if (@r3 = $s3->fetchrow_array) {
                        $m_already++;
                    }
                    else {
                        $s4=$dbh->prepare("insert into user_group_members (user_group_id,user_id,tstamp) 
                                           values ('$user_group_id','$ug_uid',now())");
                        $s4->execute;
                        $s4->finish;
                        if ($uglist_exists) {
                            $s4=$dbh->prepare("update users_$title set uglist=concat(uglist,',$user_group_id,') 
                                               where id='$ug_uid'");
                            $s4->execute;
                            $s4->finish;
                        }
                        $m_added++;
                    }
                    $s3->finish;
                    $wfound++;
                }
                unless ($wfound) {
                    $m_notmember++;
                }
                $s2->finish;
                $lines++;
                if ($lines%50==0) {
                    $s4=$dbh->prepare("update xmlreq set progress=$progress where id='$job_id'");
                    $s4->execute;
                    $s4->finish;
                }
            }
        }
        close CSV;
        unlink($csv_file);
        push (@job_output,"$m_added tag importja sikeres volt.") if ($m_added);
        push (@job_output,"$m_already mar tagja volt a tagok csoportjanak") if ($m_already);
        push (@job_output,"$m_notmember nem tagja a csoportnak") if ($m_notmember);
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
    }
    else {
        $set_status="ready";
    }

    $s2=$dbh->prepare("update xmlreq set status='$set_status',job_errors=$jerr,job_output=$jout where id='$job_id'");
    $s2->execute;
    $s2->finish;
}
