<?php

$action = $_GET['action'];
if (empty($action)) { $action = $_POST['action']; }
$id = $_GET['id'];
if (empty($id)) { $id = $_POST['id']; }
$valueCoded = $_GET['value'];
if (empty($value)) { $value = $_POST['value']; }

$value = base64_decode($valueCoded);

session_start();

if ($action == "create") {

    /* 84, 24 */
    $char_szam = 5; //$_GET['csz'];
    $kepszel = 84; //190; //$_GET['kepszel'];
    $image2=imagecreatetruecolor($kepszel, 24/*75*/);

    $white2=imagecolorallocate($image2, 255, 255, 255);
    $grey2[1]=imagecolorallocate($image2, 153, 162, 178);
    $grey2[2]=imagecolorallocate($image2, 146, 151, 162);
    $grey2[3]=imagecolorallocate($image2, 170, 176, 190);
    $grey2[4]=imagecolorallocate($image2, 223, 229, 237);

    imagefill($image2, 1, 1, $white2);

    for ($x=0; $x < 84 * 24 / 3; $x++)
    {
        $x1 = rand(0, 84);
        $y1 = rand(0, 24);
        $x2 = rand(0, 84);
        $y2 = rand(0, 24);
        //$x1 = 0; $x2=84;
        //$y1 = rand(0, 24);
        //$y2 = rand(0, 24);
        imagesetpixel($image2, $x1, $y1, $grey2[4]);
        imagesetpixel($image2, $x2, $y2, $grey2[2]);

        //imagedashedline($image2, $x1-1, $y1, $x2-1, $y2, $grey2[4]);
    }


    for ($x=0; $x < $kepszel/4; $x++)
    {
/*
        $x12=rand(-20, $kepszel-1);
        $x22=rand(-20, 74);

        $szin2=rand(1, 2);

        imagerectangle($image2, $x12, $x22, $x12 + 20, $x22 + 20, $grey2[$szin2]);
        imagerectangle($image2, $x12 - 1, $x22 - 1, $x12 + 21, $x22 + 21, $grey2[$szin2]);
*/
        
        $y1 = 0; $y2=24;
        $x1 = rand(0, $kepszel);
        $x2 = rand(0, $kepszel);
        //$x2 = $x1 + rand($x-$kepszel/4, $x + $kepszel/4);
        imagedashedline($image2, $x1, $y1, $x2, $y2, $grey2[1]);
    }




    $textColor2=imagecolorallocate($image2, 14, 29, 80);

    //$rand="";

    //$alphanum="BDEFHJKLMNPQRTUVWXYZ236789";
    //$kuldokod="123456789";

    
    /*for ($tt=1; $tt < $char_szam+1; $tt++)
    {
        $kovbet = " ";

        while ($kovbet == " ")
            $kovbet=substr(str_shuffle($alphanum), 0, 1);

        $rand.=$kovbet;
        $alphanum=str_replace($kovbet, " ", $alphanum);
    }*/
    
    //$alphanum = str_shuffle($alphanum);
    //if (isset($_SESSION['maxima_captcha_'.$id])) {
    //    $rand = $_SESSION['maxima_captcha_'.$id];
    //} else {
    //    $rand = /*rand(10000,99999);*/substr($alphanum, 0, $char_szam);
    $_SESSION['maxima_captcha_'.$id] = /*md5*/base64_encode($value);
    //}
    //$rand=$rand."";
    
    $rand = decryptString($value);

    $font[1]='/var/www/maxima_engine/www/var/fonts/ekod.ttf';
    $font[2]='/var/www/maxima_engine/www/var/fonts/ekod2.ttf';
    $font[3]='/var/www/maxima_engine/www/var/fonts/ekod3.ttf';
    $font[4]='/var/www/maxima_engine/www/var/fonts/ekod4.ttf';

    $rand1="";
    
    for ($tt=1; $tt < $char_szam+1; $tt++)
    {
        $mit = substr($rand, $tt-1, 1);
        //$rand1.=$mit;
        $melikfont=4;//rand(1, 3);

        /*
        $hol2=$tt * 32 - 20;
        $mag2=rand(-12, 12) + 48;
        $dol2=rand(-15, 15);
        imagettftext($image2, 26, $dol2, $hol2, $mag2, $textColor2, $font[$melikfont], $mit);
        */

        $hol2=$tt * 16 - 14;
        $mag2=rand(-2, 2) + 19;
        $dol2=rand(-15, 15);
        imagettftext($image2, 15, $dol2, $hol2, $mag2, $textColor2, $font[$melikfont], $mit);

    }

    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");

    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);

    header("Pragma: no-cache");

    header('Content-type: image/jpeg');

    imagejpeg($image2);
    imagedestroy($image2);

} else if ($action=="verify") {
    
    //echo '<script type="text/javascript">alert(captchaCode);</script>';
    echo '<pre>';
    var_dump($_SESSION);
    echo '</pre>';
    exit;
    if (!empty($value) && (!empty($_SESSION['maxima_captcha_'.$id])) && /*md5*/($value) == $_SESSION['maxima_captcha_'.$id]) {
        echo "Success";
        unset($_SESSION['maxima_captcha_'.$id]);
    } else {
        echo "Fail";
        //echo " ".md5($value)." = ".$_SESSION['maxima_captcha_'.$id];
    }

}

function decryptString($code) {
    $text = "";
    $codeArray = explode(",",$code);
    foreach ($codeArray as $digitData) {
        $digitArray = explode("-", $digitData);
        $ascii = hexdec($digitArray[0]) - $digitArray[1];
        $text .= chr($ascii);
    }
    return $text;
}

?>
