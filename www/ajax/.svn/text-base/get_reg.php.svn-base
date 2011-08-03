      <div class="content_right_header"><h4>Regisztráció</h4></div>
      <div class="content_right_text">
			<div class="felpalya">
				<h2></h2>
							<p>Tisztelt Látogató!<br /><br />Az adatai megadása után e-mailben elküldjük Önnek a letöltési linket.</p>
			</div>
			<div class="felpalya">
					<h2></h2>
      	<?php
						if ($errormsg) echo '<div class="errormsg">'.$errormsg.'</div>';
						
						if(!$succmsg) {
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
						}
						else echo $succmsg;
      	
      	
      	?>
      		</div>
      	</div>