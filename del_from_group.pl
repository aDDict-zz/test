#!/usr/bin/perl 

# this script is used to add demog info to all users in group.

use DBI;
$_setchdir = $0;
$_setchdir =~ s/[^\/]*$//;
chdir($_setchdir);
require "./common.pl";

my $dbh = DBI->connect("DBI:mysql:$DB_NAME:$DB_AUTH_HOST:$DB_AUTH_PORT",$DB_AUTH_USER,$DB_AUTH_PW) || die $DBI::errstr ;
$dbh->{RaiseError} = 1 ;

$sth = $dbh->prepare("select id,job_input,group_id from xmlreq where 
                      job_type='del_from_group' and status='queued' and id=".$ARGV[0]." order by id limit 1");
$sth->execute;
while (@row = $sth->fetchrow_array) { 
    $job_id = $row[0];

    @input = split(/\|;\|/,$row[1]);    # all the needed input.
    $group_id = $row[2];                   
    $sql1 = $input[0];          # alter table sql
    $sql2 = $input[1];			# insert into vip_demog... sql
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
    
    $s2=$dbh->prepare($sql1);
    $s2->execute;
	$jout=$s2->{mysql_affected_rows};
	$s2->finish;

    $s2=$dbh->prepare($sql2);
    $s2->execute;
	$jout=$s2->{mysql_affected_rows};
	$s2->finish;

    end_job();
}

sub end_job() {

    $s2=$dbh->prepare("update xmlreq set status='ready' where id='$job_id'");
    $s2->execute;
    $s2->finish;
}
