<?php
session_start();

  if(!$_REQUEST["firstname"]) $error.="Hiányzó vezetéknév<br />";		
  if(!$_REQUEST["lastname"]) $error.="Hiányzó keresztnév<br />";		
  if(!$_REQUEST["email"]) $error.="Hiányzó e-mail cím<br />";		
  if(!$_REQUEST["company"]) $error.="Hiányzó cégnév<br />";		
  if($_REQUEST['security_code2'] != $_SESSION["security_code"]) $error .= "Hibás ellenőrző kód! <br />";

//  if(!isset($_REQUEST["hirek_ok"]) || $_REQUEST["hirek_ok"]!="1") $error.=$_lang["error_hirek_ok"]."<br />";		


//  $chk_code=mysql_num_rows(mysql_query("SELECT * FROM securityimages WHERE id='".$_REQUEST["id"]."' AND code='".$_REQUEST["code"]."'"));

//  if(!$chk_code) $error.="<br />";


  if(!$error)
  {
		$msg="
Tisztelt ".$_REQUEST["lastname"]." ".$_REQUEST["firstname"]."!\n\n
Köszönjük érdeklődését az elektronikus direkt marketing szabályozásáról írt összefoglaló tanulmányunkkal kapcsolatban.\n
Az ingyenes tanulmányt az alábbi linkre kattintva érheti el:\n
http://hirekmedia.hu/pdf/EDM_szabalyozas.pdf
\n\nüdvözlettel:\n
a Hírek Média munkatársai
";

        mail($_REQUEST["email"], "Az elektronikus direkt marketingre vonatkozó szabályozás elérhetősége", "$msg", "From: Maxima <info@maxima.hu>\n");

		$level="Név: ".$_REQUEST["lastname"]." ".$_REQUEST["firstname"]."\n";
        $level.="Vállalat: ".$_REQUEST["cegnev"]."\n";
        $level.="E-mail: ".$_REQUEST["email"]."\n\n";

		if(isset($_REQUEST["hirek_ok"]) && $_REQUEST["hirek_ok"]=="1") $level.="Hozzájárul a megkereséshez.\n\n";
		else $level.="Nem járul hozzá a megkereséshez.\n\n";

		$header="From: DM szabályozás <maxima@maxima.hu>\r\n".
				"Content-Type: text/plain; charset=utf-8\r\n";
		mail("media@hirekmedia.hu","Maxima.hu - DM szabályozás letöltése",$level,$header);
		
		
		
	if(isset($_REQUEST["hirek_ok"]) && $_REQUEST["hirek_ok"]=="1"){
        $msg= "# vezeteknev:".$_REQUEST["vezeteknev"]."\n";  //OK
        $msg.= "# keresztnev:".$_REQUEST["keresztnev"]."\n";  //OK
        $msg.= "# cegnev:".$_REQUEST["cegnev"]."\n";  //OK
        $msg.= "# email:".$_REQUEST["email"]."\n";  //OK
        $msg.= "# subscribe-id:0eaea42c81a50eaffea6f1e266517e12\n";  //OK
        $msg.= "# hirekmediauzletag:22384\n";  //OK
		$msg.= "# hm_forras:31663\n";  //OK
		$msg.= "# hm_ugyfeltipus:31660\n";  //OK
		$msg.= "# utolso_esemeny:".date("Y-m-d")."\n";  //OK
        $msg.= "##end##";
        mail("zuiol-subscribe@hirekmediaugyfelek.maxima.hu", "aff:80413", "$msg", "From: ".$_REQUEST["email"]."\n");
    }
		
		
        $sented="1";
/*	 	
         mysql_query("INSERT INTO downloads SET name='".addslashes($_REQUEST["vezeteknev"]." ".$_REQUEST["keresztnev"])."',company='".addslashes($_REQUEST["cegnev"])."',hirek_ok='".addslashes($_REQUEST["hirek_ok"])."',email='".addslashes($_REQUEST["email"])."',date=now()");
  */ 
        
    echo '<div class="content_right" id="right_content">
      		<div class="content_right_header"><h4>Regisztráció</h4></div>
      			<div class="content_right_text">Köszönjük az érdeklődést.<br />
				Az elektronikus direkt marketingre vonatkozó szabályozás című anyagunk elérhetőségét elküldtük az Ön által megadott e-mail címre.
				</div>
			</div>
		  </div>';
             
  }else{
?>
      <div class="content_right_header"><h4>Regisztráció</h4></div>
      <div class="content_right_text">
			<div class="felpalya">
				<h2></h2>
							<p>Tisztelt Látogató!<br /><br />Az adatai megadása után e-mailben elküldjük Önnek a letöltési linket.</p>
			</div>
			<div class="felpalya">
					<h2>Online kapcsolat</h2>
      	<?php
						if ($error) echo '<div class="errormsg">'.$error.'</div>';
						
							echo '
							<form method="post" action="" id="contact">
								<input type="hidden" name="submitted" value="1" />
								<table>
								<tr><td><label for="lastname">Vezetéknév</label>
								</td><td><input type="text" name="lastname" id="lastname" value="'.$_POST['lastname'].'" class="itext" /></td></tr>

								<tr><td><label for="firstname">Keresztnév</label>
								</td><td><input type="text" name="firstname" id="firstname" value="'.$_POST['firstname'].'" class="itext" /></td></tr>

								<tr><td><label for="company">Cégnév</label>
								</td><td><input type="text" name="company" id="company" value="'.$_POST['company'].'" class="itext" /></td></tr>

								<tr><td><label for="email">E-mail cím</label>
								</td><td><input type="text" name="email" id="email" value="'.$_POST['email'].'" class="itext" /></td></tr>

								<tr><td><label for="accept2" style="padding-right: 20px;"><input type="checkbox" name="accept" id="accept" value="1" class="itext"/></label>
								</td><td><div style="float: left;" id="accept2">Hozzájárulok ahhoz, hogy a Hírek Média saját üzleti ajánlataival megkeressen.</div></td></tr>
								</table>
								
								<label for="security">&nbsp;</label>
								<img id="security" src="/showcaptcha.php" style="display: block; float: left;" alt="captcha" />
                				<label for="security_code2">ide írja a kódot</label>
                				<input id="security_code2" name="security_code2" type="text" size="6" class="itext" style="width: 60px;" />

								<div style="clear: both;">
								<div class="gomb_holder">
      								<div class="gomb_left"></div>
      								<div class="gomb_mid"><a href="#" onclick="send_reg(); return false;">elküld</a></div>
      								<div class="gomb_right"></div>
    							</div>
								</div>
							</form>';
						
      	
      	
      	?>
      	</div>
    </div>

<?php } ?>