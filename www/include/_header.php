<?php
   if(!isset($_COOKIE["ie6w"]) && (strstr($_SERVER["HTTP_USER_AGENT"],"MSIE 6.0") || strstr($_SERVER["HTTP_USER_AGENT"],"MSIE 5."))){ 
		setcookie("ie6w","1",time()+604800,"/");	
   }
  
   $mur=substr($_SERVER['REQUEST_URI'],1,strlen($_SERVER['REQUEST_URI']));
    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php if(isset($meta['title'][$mur])) echo $meta['title'][$mur]; else echo "Maxima.hu"; ?></title>
<meta name='keywords' content='<?php if(isset($meta['keywords'][$mur])) echo $meta['title'][$mur]; else echo "Maxima.hu"; ?>' />
<meta name='description' content='<?php if(isset($meta['description'][$mur])) echo $meta['title'][$mur]; else echo "Maxima.hu"; ?>' />
<meta name="google-site-verification" content="pQTJJDO1h7eQeA4MhyaEho68De2VlCmzfjZPoSFdyoQ" />
<link rel="shortcut icon" href="http://images.szereteknyerni.hu/maxima_favicon.ico" /> 
<link href="<?php echo $_MX_var->publicBaseUrl; ?>/maxima_style.css" rel="stylesheet" type="text/css" />
<!--
  jCarousel skin stylesheet
-->
<link rel="stylesheet" type="text/css" href="<?php echo $_MX_var->publicBaseUrl; ?>/skin.css">

<!--
  jQuery library
-->
<meta http-equiv=refresh content="1740; URL=<?=$_SERVER['REQUEST_URI']?>">
<meta name="ROBOTS" content="FOLLOW, INDEX" />
<meta name="revisit-after" content="1" />
 
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $_MX_var->publicBaseUrl; ?>/js/javascript.js"></script>

<script type="text/javascript" src="<?php echo $_MX_var->publicBaseUrl; ?>/js/jquery-1.js"></script>
<!--
  jCarousel library
-->
<script type="text/javascript" src="<?php echo $_MX_var->publicBaseUrl; ?>/js/jquery.js"></script>

<script type="text/javascript" src="<?php echo $_MX_var->publicBaseUrl; ?>/fancybox/jquery.fancybox-1.3.1.pack.js"></script>
<link rel="stylesheet" href="<?php echo $_MX_var->publicBaseUrl; ?>/fancybox/jquery.fancybox-1.3.1.css" type="text/css" media="screen" />
<script type="text/javascript">
$(document).ready(function() {

	$("#ugyfelkapu").fancybox({
				'width'				: 300,
				'height'			: 250,
				'autoScale'			: false,
				'transitionIn'		: 'none',
				'transitionOut'		: 'none',
				'type'				: 'iframe'
			});
	
});
</script>

<script type="text/javascript">

function mycarousel_initCallback(carousel)
{
    // Disable autoscrolling if the user clicks the prev or next button.
    carousel.buttonNext.bind('click', function() {
        carousel.startAuto(0);
    });

    carousel.buttonPrev.bind('click', function() {
        carousel.startAuto(0);
    });

    // Pause autoscrolling if the user moves with the cursor over the clip.
    carousel.clip.hover(function() {
        carousel.stopAuto();
    }, function() {
        carousel.startAuto();
    });
};

jQuery(document).ready(function() {
    jQuery('#mycarousel').jcarousel({
        auto: 2,
        wrap: 'last',
        initCallback: mycarousel_initCallback
    });
});

</script>
<script src="<?php echo $_MX_var->publicBaseUrl; ?>/js/cufon-yui.js" type="text/javascript"></script>	
        	
        <script src="<?php echo $_MX_var->publicBaseUrl; ?>/fonts/ITCAvantGardeGothicCondCEMed_400.font.js" type="text/javascript"></script>
        <script src="<?php echo $_MX_var->publicBaseUrl; ?>/fonts/ITCAvantGardeGothicCondCE_400.font.js" type="text/javascript"></script>

        <script type="text/javascript">
			Cufon.set('fontFamily', 'ITCAvantGardeGothicCondCEMed').replace('h4')('h5');
			Cufon.set('fontFamily', 'ITCAvantGardeGothicCondCE').replace('h3')('h1')('h2');
		</script>
        
        <script type="text/javascript">

/***********************************************
* Bookmark site script- C Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

/* Modified to support Opera */
function bookmarksite(title,url){
if (window.sidebar) // firefox
	window.sidebar.addPanel(title, url, "");
else if(window.opera && window.print){ // opera
	var elem = document.createElement('a');
	elem.setAttribute('href',url);
	elem.setAttribute('title',title);
	elem.setAttribute('rel','sidebar');
	elem.click();
} 
else if(document.all)// ie
	window.external.AddFavorite(url, title);
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

$(document).ajaxSuccess(function() {
	Cufon.refresh();
});
</script>

<script type="text/javascript" src="<?php echo $_MX_var->publicBaseUrl; ?>/js/ajax.js"></script>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-22220737-1']);
  _gaq.push(['_addOrganic','ok.hu','q']);
  _gaq.push(['_addOrganic','startlap.hu','q']);
  _gaq.push(['_addOrganic','startlapkereso.hu','q']);
  _gaq.push(['_addOrganic','images.google.hu','q']);
  _gaq.push(['_addOrganic','google.com','q']);
  _gaq.push(['_addOrganic', 'bluu.hu', 'kerdes']);
  _gaq.push(['_addOrganic', 'johu.hu', 'q']);
  _gaq.push(['_trackPageview']);
  _gaq.push(['_trackPageLoadTime']);

  (function() {
    var ga = document.createElement('script');
    ga.type = 'text/javascript'; ga.async = true;
    ga.src =  '<?php echo $_MX_var->publicBaseUrl; ?>/js/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

</head>

<body>
<? if(!isset($_COOKIE["ie6w"]) && (strstr($_SERVER["HTTP_USER_AGENT"],"MSIE 6.0") || strstr($_SERVER["HTTP_USER_AGENT"],"MSIE 5."))){ ?>
	<div id="iewarning" style="width: 100%; position: absolute; top: 0px; text-align:center; z-index: 200;"><img src="<?php echo $_MX_var->publicBaseUrl; ?>/images/ie6.gif" alt="" onclick="document.getElementById('iewarning').style.display='none';" style="cursor: pointer;" /></div>
<? } ?>	
	
<div id="wrapper"><!--WRAPPER START-->

<div class="top_holder"><!--TOP HOLDER START-->

	<div class="top_logo_menu"><!--TOP LOGO MENU START-->
		<div class="logo"><a href="<?php echo $_MX_var->publicBaseUrl; ?>"><img src="<?php echo $_MX_var->publicBaseUrl; ?>/images/logo_maxima.gif" width="151" height="61" alt="logo" /></a></div>

			<div class="menu"><!--MENÜ START--> 
   			  <h3>
       			<a href="<?php echo $_MX_var->publicBaseUrl; ?>/szolgaltatasaink">Szolg&aacute;ltat&aacute;saink</a> |
                <a href="<?php echo $_MX_var->publicBaseUrl; ?>/ugyfeleink">Ügyfeleink</a> |
                <a href="<?php echo $_MX_var->publicBaseUrl; ?>/referenciak">Referenci&aacute;k</a> |
       			<a href="<?php echo $_MX_var->publicBaseUrl; ?>/kapcsolat">Kapcsolat</a> |
       			<a href="<?php echo $_MX_var->publicBaseUrl; ?>/adatkezeles">Adatkezel&eacute;s</a> |
				<a href="<?php echo $_MX_var->publicBaseUrl; ?>/szotar">Sz&oacute;t&aacute;r</a>
   			  </h3>
      
			</div><!--MENÜ END-->  

	</div><!--TOP LOGO MENU END-->

<!--MOTTO RÉSZ-->

<div class="motto_login_holder">
<div class="motto_login_content">
<div class="motto"><h1>Gyors &eacute;s hat&eacute;kony direkt marketing megold&aacute;s!</h1></div>
<div class="login">
  <h2><a href="<?php echo $_MX_var->publicBaseUrl; ?>/login.php" id="ugyfelkapu">Bejelentkez&eacute;s | &Uuml;gyf&eacute;lkapu</a></h2></div>
</div>
</div>
<!--TARTALOM START-->
