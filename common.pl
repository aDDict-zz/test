#!/usr/bin/perl

$maxima_implementation="MAXIMA"; # to identify implementation, possible values are MAXIMA, ROBIN, INVITEL

# Database ---------------------------------------------------------------------------------------
$DB_AUTH_HOST="localhost";
$DB_AUTH_PORT="3306";
$DB_AUTH_USER="root";
$DB_AUTH_PW="v";
$DB_NAME="maxima";

# Script paths -----------------------------------------------------------------------------------
$MX_SCRIPT_ROOT = "/home/vvv/sites/maxima_engine";
$MX_FILTER_ENGINE = $MX_SCRIPT_ROOT . "/filter_engine";
$MX_SPOOL_ENGINE = $MX_SCRIPT_ROOT . "/maxima_engine";

# Logging paths ----------------------------------------------------------------------------------
$MX_LOG_ROOT = "/home/vvv/sites/maxima_engine/www/var/log";
$MX_SEND_VERIFY_DIR = $MX_LOG_ROOT . "/verify";
$MX_SENDREQ_VERIFY_DIR = $MX_LOG_ROOT . "/verify/mail";
$MX_DUPLICATE_VERIFY_DIR = $MX_LOG_ROOT . "/verify_sender";
$MX_SUBSCRIBE_LOG = "/var/spool/subscribe/maxima1.1";  # logging dir for subscribe,validate,hidden-subscribe,import
$MX_UNSUBSCRIBE_LOG = $MX_LOG_ROOT . "/unsublog";      # unsub_ppos logs here
$MX_SEND_ERROR_LOG = $MX_LOG_ROOT . "/errorlog";       # cron/maxima_check logs here
$MX_SEND_SMS_VERIFY_DIR = $MX_LOG_ROOT . "/smsverify"; # sms0, the sms bulk sender writes here, similar style as the email sender
$MX_SEND_SMS_GATEWAY_LOG = $MX_LOG_ROOT . "/smsdebug"; # sms_seeme writes here data about messages forwarded to the gateway
$MX_BAD_INCOMING_SMS_DIR = $MX_LOG_ROOT . "/badsmsin"; # dsmsreply logs here data about malformed incoming sms messages coming through the email gateway

# Spool dirs -------------------------------------------------------------------------------------
$MX_SPOOL_ROOT = "/var/www/maxima_engine/www/var/spool";
$MX_SMS_SEND_SPOOL = $MX_SPOOL_ROOT . "/dqueue";    # dimoco engine spool dir; sms_seeme reads this; sms0, sms_automat and maxima_check writes there
$MX_SMS_INCOMING_SPOOL = $MX_SPOOL_ROOT . "/smsin"; # dsmsreply writes here and sms_automat reads this
$MX_BOUNCE_SPOOL = "/var/maximas/bounce";           # save_bounce writes here first few significant lines of bounced emails; files are handled offline for now
# mail spools are handled by spools.pl

# Misc settings ----------------------------------------------------------------------------------
$MX_EXPORT_TEMP_DIR = "/var/www/maxima_engine/www/var/export_csv";
$local_hostname="maxima.hu";
$release_version="1.1";
$unsubscribe_mail_prefix="leir";
$MX_SENDER_ATTACHMENTS_URL="http://www.maxima.hu/upload";

# For other dirs/settings check also _variables.php of the web application

# Settings specific to the mail assembling engine ------------------------------------------------

# Settings specific to the mail spool engine and sms spool engine --------------------------------
# if just_log_addresses=1, script logs addressees to $just_log_filename, and does not write to $SPOOL dir, but it does update tables if nosql is 0.
$just_log_addresses = 0; # 0 or 1
$just_log_addresses_root = $MX_LOG_ROOT . "/addresses";
# if nosql=1, script does not update tables (track,tracko,users_*,feedback), but it DOES write to $SPOOL dir if just_log_addresses is 0.
$nosql = 0; # 0 or 1
# write debug data to this file if $VERIFY is set. This usually must be turned on because logs in .notok files are used by send_again.
$VERIFY = 1; # 1 or maybe 0, but again, send_again WILL NOT work correctly without this!
$BANNERADMIN_LINK = "http://ad.hirekmedia.hu/outs/lnk.php?msm=";
# this page returns data about available banners for a specific adslot.
$banner_query_addr = "http://ad.hirekmedia.hu";
$banner_query_page = "/outs/getall.php?a=";
# call this page to rport AV for banneradmin, and use spice string for md5 checksum, same used in banner_avreport_page
$banner_avreport_addr = "http://ad.hirekmedia.hu";
$banner_avreport_page = "/outs/m0ad.php?a=";
$banner_avreport_spice = "2241-qqww-?-soleil";
# same things as above, for ctnetwork
$ctnet_query_page = "http://av.ctnetwork.hu/?getall=1&site_id=";
$ctnet_avreport_page = "http://ctnetwork.hu/partner/pump_av.php?a=";
$ctnet_avreport_spice = " akarmi 49739";
# external images, not base64 encoded
$MX_EXTERNAL_IMAGES_DIR = "/usr/local/storage/maxima.hu/mail-images";
@MX_EXTERNAL_IMAGES_URLS = ("http://a.images.maxima.hu","http://b.images.maxima.hu","http://c.images.maxima.hu");

# Used by the subscribe engine -------------------------------------------------------------------
sub log_sub($) {
    $ls_string=shift;
    open (OUT, ">>$MX_SUBSCRIBE_LOG/$group-$subscribe_id.$subscribe_action.notok");
    print OUT $ls_string;
    close OUT;
}
