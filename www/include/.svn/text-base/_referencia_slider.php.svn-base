<?php
$rres=mysql_query("SELECT * FROM refs WHERE r_group='1' AND r_active='1' ORDER BY r_order");
?>

<!--SLIDE RÉSZ-->
<div class="slide_holder">
		
<div class="slide">
<?php
/*
if($sajtokozlemeny=="1")
	{
echo '
<div id="sajto" style="position: absolute; z-index: 1000; background-color:#EBE9D6; width: 800px; padding: 15px; border: 2px solid black;">
<div style="float: right;text-align: left;align: left;"><a href="#" onclick="document.getElementById(\'sajto\').style.display=\'none\';">bezárás</a></div>
<b>Más nevében növelte a potenciát az Inform Média</b><br /><br />
A sértett Maximának peren kívüli egyességet ajánlott a médiavállalkozás
<br /><br />
<i>Budapest, 2010. szeptember 23. – Az Inform Média egy jól ismert direktmarketing-szolgáltató e-mail nevét és címét használta tévesen a héten két ízben, hogy potenciálnövelőt, illetve fogyasztószert
reklámozzon közel 300 ezer embernek. Az érintett fél, a Maxima direktmarketing-rendszert
üzemeltető Hírek Média már megtette a jogi lépéseket.</i>
<br /><br />
Az Inform Média technikai hibát emleget abban a mai napon küldött levélben, melynek feladójaként
hibásan Magyarország első és mindmáig meghatározó online direktmarketing-rendszere, a Maxima,
illetve annak klub szolgáltatása, a Szereteknyerni.hu szerepel.
<br /><br />
Az Inform Média Kft. a következő reklámtartalmú e-maileket küldte ki mintegy 270 ezer fős
adatbázisára Maxima Klub (info@szereteknyerni.hu) hamis feladóval:
<br /><br />
- 2010. szeptember 21.: Fedezze fel a zsírégetés új módszerét!<br />
- 2010. szeptember 22.: Add meg férfiasságodnak azt, amire vágyik!
<br /><br />
<i>"Egyelőre nem tudjuk a konkrét okot, miért használta szolgáltatásunk nevét és e-mail címét az
Inform Média Kft., de ezzel jelentős károkat okozott nekünk. A Maxima direktmarketing-rendszer
megalakulása óta mind jogilag, mind etikailag mintaként szolgál a piac számára. Elsőként szereztünk
PPOS minősítést, mely igazolja, hogy a hazai adatvédelmi jogszabályoknak megfelelően végezzünk
tevékenységünket"</i> – mondta Fialka Krisztina, a Maxima direktmarketing-rendszert üzemeltető Hírek
Média üzletágvezetője.
<br /><br />
A Maximához az elmúlt napokban folyamatosan érkeztek a panaszos levelek, így derült fény arra,
hogy valaki visszaélt a cég márkanévével és elektronikus elérhetőségeivel. A kiküldött levél – mely
időközben a népszerű vásárlói panaszfórumra, a Tékozló Homárra is felkerült – láblécében jól
látható, hogy a küldő valójában az Inform Média, hiszen a leiratkozási adatoknál a cég elérhetőségei
szerepelnek.
<br /><br />
<i>"A Maxima DM rendszer soha nem küldött ki és a jövőben sem fog kiküldeni kétes eredetű és
hatású termékeket promotáló DM üzeneteket közel 400 ezer fős adatbázisára. Az Inform Média
már megkereste cégünket és peren kívüli egyezséget ajánlott a hiba kapcsán cégünket ért
erkölcsi és anyagi kár ellentételezésére"</i> – mondta Trentin Tamás, a Maxima és Szereteknyerni.hu
szolgáltatásokat üzemeltető Hírek Média ügyvezető igazgatója.
</div>
';
}
*/

?>	
	
	
<!--CAROUSEL start-->
<div id="wrap">

  <div class=" jcarousel-skin-tango">
  <div style="position: relative; display: block;" class="jcarousel-container jcarousel-container-horizontal">
  <div style="overflow: hidden; position: relative;" class="jcarousel-clip jcarousel-clip-horizontal">
  <ul style="list-style: none; overflow: hidden; position: relative; top: 0px; left: 0; margin: 0px; padding: 0px; width: 850px;" id="mycarousel" class="jcarousel-list jcarousel-list-horizontal">
<?php while($r=mysql_fetch_array($rres)){ ?>
    <li jcarouselindex="<?php echo $i; ?>" style="float: left; list-style: none outside none;" class="jcarousel-item jcarousel-item-horizontal jcarousel-item-<?php echo $i; ?> jcarousel-item-<?php echo $i; ?>-horizontal"><a href="/referenciak/<?php echo deekezet(stripslashes($r['r_title'])); ?>"><img src="/images/references/<?php echo $r['r_bpicture']; ?>" alt="" width="123" height="158" style="border: 0;"></a></li>
	
<?php } ?>	
  </ul>
  </div>
  
  <div disabled="false" style="display: block;" class="jcarousel-prev jcarousel-prev-horizontal"></div>
  <div disabled="false" style="display: block;" class="jcarousel-next jcarousel-next-horizontal"></div>
  </div>
  </div>

</div>
<!--CAROUSEL END-->
</div>
</div>
