<?php

if($_POST["submitted"]) {
	if (empty($_POST['fullname']) || $_POST['fullname'] == "NULL")
		$errormsg .= "Kérjük adja meg a teljes nevét! <br />";
	elseif (ereg("[0-9]", $_POST['fullname']))
		$errormsg .= "A név számokat nem tartalmazhat! <br />";
	elseif (str_word_count($_POST['fullname']) < 2)
		$errormsg .= "A teljes név minimum két szóból kell, hogy álljon! <br />";
	if (empty($_POST['tel']) || $_POST['tel'] == "NULL")
		$errormsg .= "Kérjük adja meg telefonszámát! <br />";
	if (empty($_REQUEST['security_code2']) || $_REQUEST['security_code2'] != $_SESSION["security_code"])
		$errormsg .= "Hibás ellenőrző kód! <br />";

	if (empty($_POST['company']))
		$_POST['company'] == "NULL";

	//e-mail cím ellenőrzése
	if (empty($_POST['email'])) $errormsg .= "Kérjük adja meg az e-mail címét! <br />";
	elseif (!ereg("^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $_POST['email'])) $errormsg .= "Nem megfelelő e-mail cím! <br />";

	$check_array = array_keys($_POST);
	for($i=0; $i <= count($check_array); $i++){
		$_POST[$check_array[$i]] = 	stripslashes($_POST[$check_array[$i]]);
		$_POST[$check_array[$i]] = 	htmlspecialchars($_POST[$check_array[$i]]);
		$_POST[$check_array[$i]] = 	ereg_replace(";", "", $_POST[$check_array[$i]]);
	}


	if(!$errormsg) {

		$body = "A kapcsolatfelvételt kérő adatai:\n\n";
		$body .= "Név: ".$_POST['fullname']."\n";
		$body .= "Cégnév: ".$_POST['company']."\n";
		$body .= "Telefonszám: ".$_POST['tel']."\n";
		$body .= "E-mail: ".$_POST['email']."\n";
		$body .= "Megjegyzés: ".$_POST['note']."\n";
		$body .= "Regisztráció dátuma: ".date("Y-m-d H:i:s")."\n";

		$subject = "Új kapcsolatfelvétel (Maxima)!";

        //$to = "info@lightmail.hu";
             // $to = "mandark@hirekmedia.hu";

		$headers = "From: support@maxima.hu\r\n" .
				   "Content-Type: text/plain; charset=utf-8\r\n";

		//mail($to, $subject, $body, $headers);
		mail("fialka.krisztina@hirekmedia.hu", $subject, $body, $headers);
		mail("magyar.mark@hirekmedia.hu", $subject, $body, $headers);
		mail("mozes.aniko@hirekmedia.hu", $subject, $body, $headers);
		mail("trentin.tamas@hirekmedia.hu", $subject, $body, $headers);
		
		$succmsg = "<br /><b>Köszönjük érdeklődését!</b><br />Munkatársunk hamarosan felveszi Önnel a kapcsolatot.<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />";
	}
}
?>


<div class="content_holder"><!--TARTALOM START-->
  <div class="content" style="height:950px;">
    <div class="content_left">
    
    <?php include($_homedir.'/include/_ajanlatkero_doboz.php');?>

    <div class="left_box">

<?php include($_homedir.'/include/_download_box.php');?>    
</div>
    </div>
    <div class="content_right" id="right_content">
      <div class="content_right_header"><h4>Kapcsolat</h4></div>
      <div class="content_right_text">
			<div class="felpalya">
						<h2>Elérhetőségeink</h2>

						<p>
							<p><b>HÍREK MÉDIA<br />ÉS INTERNET TECHNOLÓGIA KFT.</b></p>
							<p>1024 Budapest,<br />Margit körút 5/b. II. em. 3.<br />
							Tel.: +36 (1) 373-0953<br />
							Fax: +36 (1) 373-0954</p>
			</div>
			<div class="felpalya">
					<h2>Online kapcsolat</h2>
      	<?php
      		if(!$_POST) echo '<p>Kérjük, adja meg adatait, hogy munkatársunk felvehesse Önnel a kapcsolatot.</p>';
						else if ($errormsg) echo '<div class="errormsg">'.$errormsg.'</div>';
						
						if(!$succmsg) {
							echo '
							<form method="post" action="/kapcsolat" id="contact">
								<input type="hidden" name="submitted" value="1" />
								<label for="fullname">Teljes név</label>
								<input type="text" name="fullname" id="fullname" value="'.$_POST['fullname'].'" class="itext" />

								<label for="company">Cégnév</label>
								<input type="text" name="company" id="company" value="'.$_POST['company'].'" class="itext" />

								<label for="tel">Telefonszám</label>
								<input type="text" name="tel" id="tel" value="'.$_POST['tel'].'" class="itext" />

								<label for="email">E-mail cím</label>
								<input type="text" name="email" id="email" value="'.$_POST['email'].'" class="itext" />

								<label for="note">Megjegyzés</label>
								<textarea name="note" id="note">'.$_POST['note'].'</textarea>

								<label for="security">&nbsp;</label>
								<img id="security" src="/showcaptcha.php" style="display: block; float: left;" alt="captcha" />
                				<label for="security_code2">ide írja a kódot</label>
                				<input id="security_code2" name="security_code2" type="text" size="6" class="itext" style="width: 60px;" />

								<div style="clear: both;"></div>
								<div class="gomb_holder">
      								<div class="gomb_left"></div>
      								<div class="gomb_mid"><a href="#" onclick="document.getElementById(\'contact\').submit(); return false;">elküld</a></div>
      								<div class="gomb_right"></div>
    							</div>
									
								</div>
							</form>';
						}
						else echo $succmsg;
      	
      	
      	?>
      	</div>
    </div>
  </div>
  <div class="content_bottom"></div>
</div><!--TARTALOM END-->