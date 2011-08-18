#!/usr/bin/perl 
 
use DBI;
use Time::Local;
$_setchdir = $0;
$_setchdir =~ s/[^\/]*$//;
chdir($_setchdir);
require "./common.pl";

$debug=0;

$dbh = DBI->connect("DBI:mysql:$DB_NAME:$DB_AUTH_HOST:$DB_AUTH_PORT",$DB_AUTH_USER,$DB_AUTH_PW) || die $DBI::errstr ;
$dbh->{RaiseError} = 1 ;

# groups grouped by multigroups forming one spool in which at most one job should be running
$sth = $dbh->prepare("select count(*), m.id, m.title from multi m,multigroup mg where m.index_grouping='yes' and m.id=mg.multiid group by m.id");
$sth->execute;
while (@row = $sth->fetchrow_array) {
	push(@mid,$row[1]);
    $spool = $row[2];
	$msth = $dbh->prepare("select x.id,x.group_id,x.job_type, UNIX_TIMESTAMP(x.process_start),x.status,x.check_error 
                          from (groups g left join multigroup mg on g.id=mg.groupid),xmlreq x 
                          where g.id=x.group_id and mg.multiid=".@row[1]. " and (x.status='processing' or x.status='queued') order by x.status desc,date");
	$msth->execute;
	$running=0; # this will be set to 1 if there is a process still running in one of the multi's groups
	while (@mrow = $msth->fetchrow_array) {
        check_job();
    }
} 

# the rest of the groups in one spool
$multi_ids=join(",",@mid);
$spool="others";
$sth = $dbh->prepare("select x.id,x.group_id,x.job_type,UNIX_TIMESTAMP(x.process_start),x.status,x.check_error 
                      from (groups g left join multigroup mg on g.id=mg.groupid and mg.multiid in($multi_ids)),xmlreq x 
                      where mg.multiid is null and g.id=x.group_id and (x.status='processing' or x.status='queued') order by x.status desc,date");
$sth->execute;
$running=0;
while (@mrow = $sth->fetchrow_array) {
    check_job();
} 

sub check_job() {

    if ($mrow[4] eq "processing") {
        $running_for = time - $mrow[3];
        if ($running_for > 900) {	# send warning mail when process runs for more than 15 minutes and skip to the next jobs
            $message = "Job $mrow[0] in spool $spool is running for too long: $running_for";
            if ($debug) {
                print "$message\n";
            }
            elsif ($mrow[5] ne 'yes') {
                if (!open(SENDMAIL, "|/usr/lib/sendmail -oi -t -odb")) {
                    warn "error invoking sendmail: $!\n";
                } 
                else {
                    $dbh->do("update xmlreq set check_error='yes' where id=".$mrow[0]);
                    print SENDMAIL "From: MAXIMA Error Report <kolbasz\@maxima.hu>\nTo: tbjanos\@manufaktura.rs\nSubject: 'Tul sokaig futo offline job'\nContent-Type: text/plain;\n\tcharset=\"iso-8859-2\"\n\n$message\n";
                    close SENDMAIL;
                }
            }
        }
        else {  # this jobs is running for less than 15 minutes, don't start the rest yet
            $running=1;
            if ($debug) {
                print "Job $mrow[0] in spool $spool is still running, $running_for not starting next jobs\n";
            }
        }
    }
    elsif (not $running) {
        if ($debug) {
            print("$MX_SCRIPT_ROOT/$mrow[2].pl $mrow[0] $spool\n");
        }
        else {
            system("$MX_SCRIPT_ROOT/$mrow[2].pl $mrow[0]");
        }
        $running=1;
    }
    elsif ($debug) {
        print "Job $mrow[0] in spool $spool not started because of previous jobs\n";
    }
}
