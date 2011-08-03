<?php
$r1res=mysql_query("SELECT * FROM refs WHERE r_group='1' AND r_active='1' ORDER BY r_order");
$r2res=mysql_query("SELECT * FROM refs WHERE r_group='2' AND r_active='1' ORDER BY r_order");
$r3res=mysql_query("SELECT * FROM refs WHERE r_group='3' AND r_active='1' ORDER BY r_order");

if($pagetmp[2])
	$r=mysql_fetch_array(mysql_query("SELECT * FROM refs WHERE r_url='".$pagetmp[2]."'"));
else
	$r=mysql_fetch_array(mysql_query("SELECT * FROM refs WHERE r_group='1' AND r_active='1' ORDER BY rand() LIMIT 1"));

?>

<!--SLIDE RÉSZ-->
<div class="content_holder"><!--TARTALOM START-->
  <div class="content" style="height:600px;">
    <div class="content_left">
    <div class="content_ref_header"><h4>Referenciák</h4></div>
    
    	<div id="accHolder">
		<div class="accordionButton" id="accord_id1"><img src="/images/ref_btn_01.gif" id="ref_button1" alt="" width="220" height="29" /></div>
		<div class="accordionContent">
<?php while($r1=mysql_fetch_array($r1res)){ $e++; ?>
	<div class="ref_item_holder"><a href="#" onclick="$('#ajax_ref').load('/ajax/showRef.php?rnd='+ Math.random()*99999 +'&url=<?php echo $r1['r_url']; ?>', function() {Cufon.refresh();}); return false;"><img src="/images/references/little/<?php echo $r1['r_lpicture']; ?>" alt="" title="<?php echo $r1['r_title']; ?>" width="68" height="68" id="e<?php echo $e; ?>" onmouseover="MM_swapImage('e<?php echo $e; ?>','','/images/references/little/<?php echo $r1['r_lpicture_over']; ?>',1)" onmouseout="MM_swapImgRestore()" /></a></div>
<?php } ?>
		</div>
		<div class="accordionButton" id="accord_id2"><img src="/images/ref_btn_02.gif" alt="" width="220" height="29" /></div>
		<div class="accordionContent">
<?php while($r2=mysql_fetch_array($r2res)){ $e++; ?>
	<div class="ref_item_holder"><a href="#" onclick="$('#ajax_ref').load('/ajax/showRef.php?rnd='+ Math.random()*99999 +'&url=<?php echo $r2['r_url']; ?>', function() {Cufon.refresh();}); return false;"><img src="/images/references/little/<?php echo $r2['r_lpicture']; ?>" alt="" title="<?php echo $r2['r_title']; ?>" width="68" height="68" id="e<?php echo $e; ?>" onmouseover="MM_swapImage('e<?php echo $e; ?>','','/images/references/little/<?php echo $r2['r_lpicture_over']; ?>',1)" onmouseout="MM_swapImgRestore()" /></a></div>
<?php } ?>
        </div>
		<div class="accordionButton" id="accord_id3"><img src="/images/ref_btn_03.gif" alt="" width="220" height="29" /></div>
		<div class="accordionContent">
<?php while($r3=mysql_fetch_array($r3res)){ $e++; ?>
	<div class="ref_item_holder"><a href="#" onclick="$('#ajax_ref').load('/ajax/showRef.php?rnd='+ Math.random()*99999 +'&url=<?php echo $r3['r_url']; ?>', function() {Cufon.refresh();}); return false;"><img src="/images/references/little/<?php echo $r3['r_lpicture']; ?>" alt="" title="<?php echo $r3['r_title']; ?>" width="68" height="68" id="e<?php echo $e; ?>" onmouseover="MM_swapImage('e<?php echo $e; ?>','','/images/references/little/<?php echo $r3['r_lpicture_over']; ?>',1)" onmouseout="MM_swapImgRestore()" /></a></div>
<?php } ?>
            <img src="/images/pontozott.gif" alt="" width="220" height="1" />
        </div>
	</div>
    
    <?php include($_MX_var->publicBaseDir.'/include/_ajanlatkero_doboz.php');?>
	    
    <div class="left_box" style=" padding:0 0 10px 0;">
<?php include($_MX_var->publicBaseDir.'/include/_download_box.php');?>
</div>
    </div>
    <div class="content_right" id="right_content">
    <div id="ajax_ref">	
      <div class="content_right_header"><h4><?php echo stripslashes($r['r_title']); ?></h4></div>
      <div class="content_right_ref">
      <div class="content_ref_holder">
        <center><img id="Picture" src="/images/references/<?php echo $r['r_bpicture'];?>" alt="<?php echo iconv("ISO-8859-2","UTF-8",stripslashes($r['r_title']));?>" style="display:none" /></center>
        	
        </div>
     
        </div>
        <div class="content_right_text">
        <p><?php echo nl2br(stripslashes($r['r_text'])); ?></p>
</div>
    </div>
  </div>
  </div>
  <div class="content_bottom"></div>
</div><!--TARTALOM END-->
