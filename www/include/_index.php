<?php
$f=mysql_fetch_array(mysql_query("SELECT * FROM maindata"));
?>
<div class="content_holder">
  <div class="content">
    <div class="content_left">
    
     <?php include($_MX_var->publicBaseDir.'/include/_ajanlatkero_doboz.php');?>
    
    <div class="left_box_login" <?php if(isset($_GET["loginerror"])){ ?>style="height: 240px;"<?php } ?>>
    <div class="left_header_holder">
  <div class="left_header"><h3 style="color:#fff;">Bejelentkez&eacute;s | &Uuml;gyf&eacute;lkapu</h3></div>
  <div class="left_header_right"></div>
</div>
    <div class="left_box_login_td">
    <form action="<?php echo $_MX_var->baseUrl?>/login.php" name="menuloginform" method="post">
<fieldset>
    <table id="gyorsreg" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><label for="username">E-mail c&iacute;m:</label></td>
  </tr>
  <tr>
    <td><input type="text" name="username" id="username" <?php if(isset($_GET["loginerror"])){ ?>class="input_error"<?php } ?>  onkeypress="if(window.event.keyCode == 13) document.menuloginform.submit();" /></td>
  </tr>
  <tr>
    <td><label for="password">Jelsz&oacute;</label></td>
  </tr>
  <tr>
    <td><input type="password" id="password" name="password" <?php if(isset($_GET["loginerror"])){ ?>class="input_error"<?php } ?>  onkeypress="if(window.event.keyCode == 13) document.menuloginform.submit();" /><input type="hidden" name="public_login_source" value="/" /></td>
  </tr>
  <tr>
    <td style="padding:5px 0 5px 0"><div class="gomb_holder">
      <div class="gomb_left"></div>
      <div class="gomb_mid"><a href="#" onclick="document.menuloginform.submit();">Bel&eacute;p&eacute;s</a></div>
      <div class="gomb_right"></div>
    </div></td>
  </tr>
  <tr>
    <td style=" line-height:20px"><a href="<?php echo $_MX_var->publicBaseUrl; ?>/forget.php" id="forgotten" onclick="return false;">Elfelejtette a jelszav&aacute;t?</a></td>
  </tr>
</table>
    <?php if(isset($_GET["loginerror"])): ?>
        <div style="color:#FFA84A; font-weight:bold; padding:0px 0px 5px 0px;">
    <?php echo $_GET["loginerror"]; ?>
        </div>	
    <?php endif; ?>

</fieldset>
</form>
</div>
    </div>

<script type="text/javascript">
$(document).ready(function() {

	$("#forgotten").fancybox({
				'width'				: 300,
				'height'			: 250,
				'autoScale'			: false,
				'transitionIn'		: 'none',
				'transitionOut'		: 'none',
				'type'				: 'iframe'
			});
	
});
</script>

<div class="left_box">
    <?php include($_MX_var->publicBaseDir.'/include/_download_box.php');?>
</div>
    
    <div class="content_left_bottom"><img src="images/content_left_bg_bottom.png" alt="" width="235" height="60" /></div>
    </div>
    <div class="content_right" id="right_content">
      <div class="content_right_header">
        <h4>Mit ny&uacute;jt a Maxima?</h4></div>
      <div class="content_right_text"><p><strong>Mit nyújt a Maxima direktmarketing-rendszer?</strong></p>
      <ul>
<li><strong>Teljes körű DM-szolgáltatás:</strong> E-mail, SMS és postai direkt marketing, adatbázis-építés, ügyféloldali adatbázis-kezelés, hírlevél-üzemeltetés, telesales adatbérlés.</li>
<li><strong>420 ezer fős adatbázis: </strong> Rendszeres adatbázis-építés és -frissítés - garantált élő kontaktszám.</li>
<li><strong>Kifinomult targetálás:</strong> 50 főfilter, összesen több mint 100 szűrési szempont.</li> 
<li><strong>Interaktív megoldások:</strong> Az e-mail direkt marketing üzenetekben flash animációk és űrlapok is elhelyezhetők.</li>
<li><strong>Magas válaszadási arány:</strong> E-DM kampányoknál átlagosan 5-15% közötti válaszarány.</li>
<li><strong>Mérhetőség:</strong> Valós idejű statisztikák, finomhangolás lehetősége a kampány ideje alatt.  </li>
<li><strong>Gyorsaság:</strong> Direkt marketing kampány lebonyolítása akár 1-2 nap alatt.</li>
<li><strong>Jogkövető megoldás:</strong> A hazánkban érvényes szabályozásnál is szigorúbb double opt-in alapú adatbázis.</li>
</ul>

      
      </div>
      <div class="kiemeltadatok_holder">
        <div class="kiemeltadatok_header"><h3>Kiemelt adatok</h3></div>
        <div class="kiemeltadatok_right"></div>
        <div class="kiemelt_adat_nev_box">
<h5 style="color:#ff8b0e;">
  <div class="kiemelt_adat_nev">Panel m&eacute;rete</div><div class="kiemelt_adat"><span style="color:#545454;"><?php echo $f['panel_size'];?> tag</span></div>
  <div class="kiemelt_adat_nev">Utols&oacute; adatb&aacute;zis friss&iacute;t&eacute;se</div><div class="kiemelt_adat"><span style="color:#545454;"><?php echo $f['last_update'];?></span></div>
  <div class="kiemelt_adat_nev">K&ouml;vetkező adatb&aacute;zis-friss&iacute;t&eacute;s</div><div class="kiemelt_adat"><span style="color:#545454;"><?php echo $f['next_update'];?></span></div></h5></div>

      </div>
    </div>
  </div>
  <div class="content_bottom"></div>
</div>

