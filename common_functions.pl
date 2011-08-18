#!/usr/bin/perl 

use Encode;

# rfc2047 B-encodes a string in the given codeset.
sub mx_encode_header_tags($,$) {

    my $string=shift;
    my $codeset=shift;
    $codeset="cp1250" unless ($codeset);
    $codeset=~s/utf8/utf-8/i;
    $string = decode('utf-8', $string) if ($codeset eq "utf-8");
    my $len=length($string);
    my @eparts = ();
    my $chunks=$len/32;
    for (my $i=0;$i<$chunks;$i++) {
        my $chunk=substr($string,$i*32,32);
        $chunk=encode('utf-8',$chunk) if ($codeset eq "utf-8");
        $chunk=encode_base64($chunk);
        $chunk =~ s/[\r\n]+$//;
        push(@eparts,"=?$codeset?B?$chunk?=");
    }
    return join("\n\t",@eparts);
}

# convert character sets, for now it's simply iconv, we may need this more sophisticated later
sub mx_iconv($;$;$) {

    my $text=shift;
    my $from=shift;
    my $to=shift;
    my $converter = Text::Iconv->new($from, $to);
    return $converter->convert($text);
}

# this function is used to detect the incoming charset of the data.
# the result are charsets that mysql is able to convert to the demog variables if needed.
# the encodings below should be enough. CP1250 is the deault, that one was used before
sub mx_get_supported_charset($) {

    my $charset=shift;

    if ($charset =~ /iso.?8859.?2/i) {
        return "latin2";
    }
    elsif ($charset =~ /iso.?8859.?1/i or $charset =~ /(cp|windows).?1252/i) {
        return "latin1";
    }
    elsif ($charset =~ /utf.?8/i) {
        return "utf8";
    }
    return "cp1250";
}

# used from robin and maxima subscribe and hidden subscribe to get the codeset of the subscribe data
# from the email header if it is not set already in the rdemog.
sub mx_get_subscribe_codeset(\$header,\$rdemog,\$buffer) {

    my $header=shift;
    my $rdemog=shift;
    my $buffer=shift;
    my $charset="";

    if ($$header =~ /quoted-printable/i) {
        $$rdemog = decode_qp($$rdemog);
        $$buffer = decode_qp($$buffer);
    }
    if ($$header =~ /Content-Type:.*charset\s*=\s*['"]?([^\s'"]+)['"]?/is) {
        $charset=$1;
        unless ($$rdemog =~ /# data-charset/) {
            $$rdemog="# data-charset:$charset\n$$rdemog";
        }
    }
}

1;
