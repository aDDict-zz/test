#!/usr/bin/perl 

%spooldirs=("sender3"=>"/var/www/maxima_engine/wwws3/spool","sender2"=>"/usr/local/maximas2/spool","ssd"=>"/usr/local/maximas2/ssd/spool");

# the exim spool is turned off for now, there is no need for it any more, 
# the messages are expected to be sent asap anyway
# and if there is only one 'other' spool it is easier to balance sendings.
@exim_groups=();
#@exim_groups=("mainap","horoszkop","sport","napigazdasag");
@test_groups=("proba","kliensek","levelezoteszt");
@ssd_groups=("zigor2","egeszseg","erdekeshonlapok","horoszkop","hihetetlen","receptek","kreativpercek","vicc","mainap","szerelem","jog","mozi","program","holgyklub","tudomany","lakas","auto","befektetes","utazas","allatbarat","babamama","unionjack","sport","sztarvilag","hogyanmukodik","krisztina","divat","konyvajanlo","trendikutyuk","cegvezetes","erotika","mediamarketing","gamer","ferfizona","nyeremenyjatek","proba","kliensek","levelezoteszt");

# maxima  is www.maxima.hu (193.28.86.36) exim
# sender1 is sender.hirekmedia.hu (193.28.86.37) qmail
# sender2 is sender2.maxima.hu (193.28.86.22) qmail
# sender3 is sender3.maxima.hu 193.28.86.26 exim
# sender4 is sender4.maxima.hu 193.28.86.24 exim larger outside bandwidth
# sender6 is sender6.maxima.hu 193.28.86.25 qmail larger outside bandwidth

# this function decides into which spool(=machine) will go the messages.
# NOTE that this does not automatically mean that those machines' tlbs will send those messages,
sub mx_spool($,$) {

    my $group=shift;
    my $test=shift;

	return "sender2";

    # only this spool will be used, and tlb's from sender* machines will read parts of these spools.
	#if ($group eq "zigor2") {
	#return "sender3";
		#}
		#if (grep(/^$group$/,@ssd_groups)) {
		return "ssd";
		#}
		#return "sender2";
}

sub mx_main_spooldir($) {

    my $spool=shift;
    
    my $main_spooldir=$spooldirs{"$spool"};
    my $templatedir="$main_spooldir/templates";
    my $templatedir_contents="$templatedir/contents";
    my $templatedir_banners="$templatedir/banners";

    return ($main_spooldir,$templatedir,$templatedir_contents,$templatedir_banners);
}

# this function decides which spool dir to use PER MESSAGE, WITHIN the already chosen main spool dir.

sub mx_spooldir($,$,$,$,$) {

    my $email=shift;
    my $cnt=shift;
    my $spool=shift;
    my $test=shift;
    my $mailnum=shift;
    
    $email =~ /^(.*)\@(.*)$/; #there should be valid email addresses in the database.
    $domain = $2;
    my @nums = (0..9,'a'..'f');
    my %nums = map { $nums[$_] => $_ } 0..$#nums;
    my $msub = 0;
    for (lc(substr(MD5->hexhash($email),0,4)) =~ /./g ) {
        $msub *= 16;
        $msub += $nums{$_};
    }
    $msub = $msub % 3;
    # test spool: this one gets only a few messages, these are supposed to be very fast. [maxima] will send these.
	if (0 and $group_name eq "zigor2") {
		$spooldir="exim";
	}
    elsif ($cloudmark{$email} or grep(/^$group_name$/,@test_groups) or $test eq "yes" or $mailnum>0 and $mailnum<25) {
        $spooldir="test";
    }
    # freemail spool: freemail servers may need special settings, [sender1,sender2]
	#elsif (0 && $domain eq "freemail.hu") {
    elsif ($msub == 0) {
        $spooldir="freemail";
    }  
    # important spool: for groups marked as 'important', [sender*]
    #elsif ($important eq "yes" or ($memberlist_send and $memberlist_send_ids ne "delete")) {
	elsif ($msub == 1) { 
        $spooldir="important";
    }
    # other spool: everything else, [sender*]
    else {
        $spooldir="other";
    }
    if ($cnt) {
		$spool_counts{"$spooldir"}++;
		if ($cnt==2) {
			$mails_to_queue{"$spooldir"}++;
			$mails_queued{"$spooldir"}=0;
		}
    }
    return $spooldir;
}

