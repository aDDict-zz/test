<?php
	session_start();

	if (empty($_GET['fullname']) || $_GET['fullname'] == "NULL")
		$errormsg .= "Kérjük adja meg a teljes nevét! <br />";
	elseif (ereg("[0-9]", $_GET['fullname']))
		$errormsg .= "A név számokat nem tartalmazhat! <br />";
	elseif (str_word_count($_GET['fullname']) < 2)
		$errormsg .= "A teljes név minimum két szóból kell, hogy álljon! <br />";
	if (empty($_GET['tel']) || $_GET['tel'] == "NULL")
		$errormsg .= "Kérjük adja meg telefonszámát! <br />";
	if (empty($_GET['security_code2']) || $_GET['security_code2'] != $_SESSION["security_code"])
		$errormsg .= "Hibás ellenőrző kód! <br />";

	if (empty($_GET['company']))
		$_GET['company'] == "NULL";

	//e-mail cím ellenőrzése
	if (empty($_GET['email'])) $errormsg .= "Kérjük adja meg az e-mail címét! <br />";
	elseif (!ereg("^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $_GET['email'])) $errormsg .= "Nem megfelelő e-mail cím! <br />";

	$check_array = array_keys($_GET);
	for($i=0; $i <= count($check_array); $i++){
		$_GET[$check_array[$i]] = 	stripslashes($_GET[$check_array[$i]]);
		$_GET[$check_array[$i]] = 	htmlspecialchars($_GET[$check_array[$i]]);
		$_GET[$check_array[$i]] = 	ereg_replace(";", "", $_GET[$check_array[$i]]);
	}


	if(!$errormsg) {
		$cel[1]='eladások növelése';
		$cel[2]='termék/szolgáltatás ismertségének növelése';
		$cel[3]='termék/szolgáltatás bevezetése a piacra';
		$cel[4]='termék/szolgáltatás imidzsének a növelése';
		$cel[5]='látogatók számának növelése a weboldalon';
		$cel[6]='regisztrált tagok növelése';
		$cel[7]='egyéb';

		$camps[1]='E-mail marketing';
		$camps[2]='Adatbázis-építés';
		$camps[3]='Ügyfél adatbazisának kezelése';
		$camps[4]='Telesales adatbérlés';
		$camps[5]='SMS marketing';
		$camps[6]='Hírlevél-üzemeltetés';
		$camps[7]='Offline direkt marketing';
		$camps[8]='Online nyereményjátékok';
		$camps[9]='Egyéb';

		$body = "A kapcsolatfelvételt kérő adatai:\n\n";
		$body .= "Név: ".$_GET['fullname']."\n";
		$body .= "Cégnév: ".$_GET['company']."\n";
		$body .= "Telefonszám: ".$_GET['tel']."\n";
		$body .= "E-mail: ".$_GET['email']."\n\n";

		$body .= "Kampány célja: ".$cel[$_GET['mission']]."\n";

		if(isset($_GET['campaign']))
		{
			$tmp=explode("|",$_GET['campaign']);
			foreach($tmp as $x)
				$camp.="- ".$camps[$x]."\n";		
		}

		if(isset($camp))
		$body .= "\nMilyen kampányt tervez?\n".$camp."\n";

		$body .= "\nA kampányban szereplő termék/ szolgáltatás leírása:\n".$_GET['note']."\n";
		$body .= "\nA termék/ szolgáltatás célcsoportjának leírása:\n".$_GET['target']."\n";

		$subject = "Új kapcsolatfelvétel (Maxima)!";

//             $to = "info@maxima.hu";
//              $to = "mandark@hirekmedia.hu";

		$headers = "From: maxima@maxima.hu\r\n" .
				   "Content-Type: text/plain; charset=utf-8\r\n";

		mail("fialka.krisztina@hirekmedia.hu", $subject, $body, $headers);
		mail("magyar.mark@hirekmedia.hu", $subject, $body, $headers);
		mail("mozes.aniko@hirekmedia.hu", $subject, $body, $headers);
		mail("trentin.tamas@hirekmedia.hu", $subject, $body, $headers);

		$succmsg = "<br /><b>Köszönjük érdeklődését!</b><br />Munkatársunk hamarosan felveszi Önnel a kapcsolatot.<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />";
	}
?>
      <div class="content_right_header"><h4>Ajánlatkérés</h4></div>
      <div class="content_right_text">
			<div class="felpalya">
					<h2>Online kapcsolat</h2>
      	<?php
			if ($errormsg) echo '<div class="errormsg">'.$errormsg.'</div>';
						
						if(!$succmsg) {
							echo '
							<form method="post" action="" id="contact">
								<input type="hidden" name="submitted" value="1" />
								<label for="fullname">Teljes név</label>
								<input type="text" name="fullname" id="fullname" value="'.$_GET['fullname'].'" class="itext" />

								<label for="company">Cégnév</label>
								<input type="text" name="company" id="company" value="'.$_GET['company'].'" class="itext" />

								<label for="tel">Telefonszám</label>
								<input type="text" name="tel" id="tel" value="'.$_GET['tel'].'" class="itext" />

								<label for="email">E-mail cím</label>
								<input type="text" name="email" id="email" value="'.$_GET['email'].'" class="itext" />

								<label for="camp">Milyen kampányt tervez?</label>
								<table id="camp" cellspacing="0" border="0" style="padding-top: 10px; width: 200px;">
								<tr><td  style="width: 25px;"><input type="checkbox" name="campaign" value="1" style="width: 20px;" /></td><td>E-mail marketing</td></tr>
								<tr><td><input type="checkbox" name="campaign" value="2" style="width: 20px;" /></td><td>Adatbázis-építés</td></tr>
								<tr><td><input type="checkbox" name="campaign" value="3" style="width: 20px;" /></td><td>Ügyfél adatbazisának kezelése</td></tr>
								<tr><td><input type="checkbox" name="campaign" value="4" style="width: 20px;" /></td><td>Telesales adatbérlés</td></tr>
								<tr><td><input type="checkbox" name="campaign" value="5" style="width: 20px;" /></td><td>SMS marketing</td></tr>
								<tr><td><input type="checkbox" name="campaign" value="6" style="width: 20px;" /></td><td>Hírlevél-üzemeltetés</td></tr>
								<tr><td><input type="checkbox" name="campaign" value="7" style="width: 20px;" /></td><td>Offline direkt marketing</td></tr>
								<tr><td><input type="checkbox" name="campaign" value="8" style="width: 20px;" /></td><td>Online nyereményjátékok</td></tr>
								</table>


								<table id="camp" cellspacing="0" border="0" style="padding-top: 10px; width: 300px;">
								<tr><td align="center"><b>A kampányban szereplő termék/ szolgáltatás leírása</b></td></tr>
								<tr><td align="center"><textarea name="note" id="note" style="padding-top: 10px; width: 300px;">'.$_POST['note'].'</textarea></td></tr>
								</table>
								

								<table id="camp" cellspacing="0" border="0" style="padding-top: 10px; width: 300px;">
								<tr><td align="center"><b>A termék/ szolgáltatás célcsoportjának leírása (pl.: 25-50 éves nő, akinek van gyereke)</b></td></tr>
								<tr><td align="center"><textarea name="target" id="target" style="padding-top: 10px; width: 300px;">'.$_POST['target'].'</textarea></td></tr>
								</table>

								<label for="mission">Kampány célja</label>
								<select name="mission" id="mission" style="margin-top: 15px; width: 210px;">
									<option value="1">eladások növelése</option>
									<option value="2">termék/szolgáltatás ismertségének növelése</option>
									<option value="3">termék/szolgáltatás bevezetése a piacra</option>
									<option value="4">termék/szolgáltatás imidzsének a növelése</option>
									<option value="5">látogatók számának növelése a weboldalon</option>
									<option value="6">regisztrált tagok növelése</option>
								</select>

								<table id="camp" cellspacing="0" border="0" style="padding-top: 10px; width: 300px;">
								<tr><td valign="bottom"><b>írja be a kódot</b></td><td align="center"><img src="/showcaptcha.php" style="display: block; float: left;" alt="captcha" /><br /><input id="security_code2" name="security_code2" type="text" size="6" class="itext" style="width: 120px;" /></td></tr>
								</table>
                												
								<div style="clear: both;">
								<div class="gomb_holder" style="padding-top: 20px;">
      								<div class="gomb_left"></div>
      								<div class="gomb_mid"><a href="#" onclick="send_offer(); return false;">elküld</a></div>
      								<div class="gomb_right"></div>
    							</div>
								</div>
							</form>';
						}
						else echo $succmsg;      	
      	?>
      	<br /><br />
      	</div>
			<div class="felpalya">
				<div style="padding-left: 40px;">
						<h2>Elérhetőségeink</h2>

						<p>
							<p><b>HÍREK MÉDIA<br />ÉS INTERNET TECHNOLÓGIA KFT.</b></p>
							<p>1024 Budapest,<br />Margit körút 5/b. II. em. 3.<br />
							Tel.: +36 (1) 373-0953<br />
							Fax: +36 (1) 373-0954</p>
				</div>
			</div>      	
    </div>