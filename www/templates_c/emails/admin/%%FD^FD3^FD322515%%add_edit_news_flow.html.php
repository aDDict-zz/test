<?php /* Smarty version 2.6.6, created on 2010-01-19 15:01:03
         compiled from add_edit_news_flow.html */ ?>
<?php echo '
<script language="javascript" type="text/javascript">
function changeType(obj){
	if(obj.options[obj.selectedIndex].value=="1"){ //RSS
		document.getElementById(\'html\').style.display = "none";
		document.getElementById(\'html\').style.visibility = "hidden";
	}else{ //HTML
		document.getElementById(\'html\').style.display = "block";
		document.getElementById(\'html\').style.visibility = "visible";
	}
}
</script>
'; ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="page_title">
        <?php if ($this->_tpl_vars['rss']['id'] == ''): ?>
			Új h&iacute;rfolyam
        <?php else: ?>
			H&iacute;rfolyam m&oacute;dos&iacute;t&aacute;sa
		<?php endif; ?>
	</td>
  </tr>
  <tr>
    <td style="padding-bottom:15px; padding-top:15px;">
		<?php ob_start(); ?>		
		<form name="page" method="post" action="index.php?id=<?php echo $this->_tpl_vars['id']; ?>
&sub_id=<?php echo $this->_tpl_vars['sub_id']; ?>
">
		<?php if ($this->_tpl_vars['rss']['id'] != ""): ?>
			<input type="hidden" name="action" value="update_rss" />
			<input type="hidden" name="rss_id" value="<?php echo $this->_tpl_vars['rss']['id']; ?>
" />
		<?php else: ?>
			<input type="hidden" name="action" value="add_rss" />
		<?php endif; ?>
		<table border="0" cellspacing="0" cellpadding="5">
		  <?php if ($this->_tpl_vars['message'] != ""): ?>
		  <tr>
			<td colspan="2" style="color:#FF0000;font-weight:bold;"><?php echo $this->_tpl_vars['message']; ?>
</td>
		  </tr>
		  <?php endif; ?>
		  <tr>
			<td>H&iacute;rforr&aacute;s:</td>
			<td>
				<select name="agencies">
					<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['agencies']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
						<?php if ($this->_tpl_vars['rss']['agency_id'] == $this->_tpl_vars['agencies'][$this->_sections['i']['index']]['agency_id']): ?>
							<option value="<?php echo $this->_tpl_vars['agencies'][$this->_sections['i']['index']]['agency_id']; ?>
" selected><?php echo $this->_tpl_vars['agencies'][$this->_sections['i']['index']]['agency_name']; ?>
</option>
						<?php else: ?>
							<option value="<?php echo $this->_tpl_vars['agencies'][$this->_sections['i']['index']]['agency_id']; ?>
"><?php echo $this->_tpl_vars['agencies'][$this->_sections['i']['index']]['agency_name']; ?>
</option>
						<?php endif; ?>
					<?php endfor; endif; ?>
				</select>
			</td>
		  </tr>
		  <tr>
		  	<td>Tipus:</td>
			<td>
				<select name="type" onChange="changeType(this);">
					<?php if ($this->_tpl_vars['rss']['feed_type'] == 1 || $this->_tpl_vars['rss']['feed_type'] == ""): ?>
					<option value="1" selected>RSS</option>
					<option value="2">HTML</option>
					<?php else: ?>
					<option value="1">RSS</option>
					<option value="2" selected>HTML</option>	
					<?php endif; ?>
				</select>
			</td>
		  </tr>		  
		  <tr>
			<td>H&iacute;rfolyam:</td>
			<td><input type="text" name="rss_name" value="<?php echo $this->_tpl_vars['rss']['rss_name']; ?>
" /></td>
		  </tr>
		  <tr>
			<td>H&iacute;rfolyam URL:</td>
			<td><input type="text" name="rss_url" value="<?php echo $this->_tpl_vars['rss']['rss_url']; ?>
" /></td>
		  </tr>
		  <tr>
		  	<td>Le&iacute;r&aacute;s:</td>
			<td><textarea name="description" rows="10"><?php echo $this->_tpl_vars['rss']['rss_description']; ?>
</textarea></td>
		  </tr>
		  <tr>
		  	<td colspan="2">
				<table width="100%" border="0" cellspacing="0" cellpadding="5" id="html" <?php if ($this->_tpl_vars['rss']['feed_type'] == 1 || $this->_tpl_vars['rss']['feed_type'] == ""): ?>style="display:none; visibility:hidden;"<?php endif; ?>>
				  <tr>
					<td>Regul&aacute;ris kifejez&eacute;s:</td>
					<td><input type="text" name="pattern" value="<?php echo $this->_tpl_vars['rss']['pattern']; ?>
" /></td>
				  </tr>
				  <tr>
					<td>Kieg&eacute;sz&iacute;t&#337; URL:</td>			
					<td><input type="text" name="aux_url" value="<?php echo $this->_tpl_vars['rss']['aux_url']; ?>
" /></td>
				  </tr>
				  <tr>
					<td>H&iacute;r URL tal&aacute;lat:</td>			
					<td><input type="text" name="match_link" value="<?php echo $this->_tpl_vars['rss']['match_link']; ?>
" /></td>
				  </tr>
				  <tr>
					<td>H&iacute;r c&iacute;m tal&aacute;lat:</td>			
					<td><input type="text" name="match_title" value="<?php echo $this->_tpl_vars['rss']['match_title']; ?>
" /></td>
				  </tr>
				  <tr>
					<td>H&iacute;r bevezet&#337; tal&aacute;lat:</td>			
					<td><input type="text" name="match_lead" value="<?php echo $this->_tpl_vars['rss']['match_lead']; ?>
" /></td>
				  </tr>
				</table>
			</td>
		  </tr>		  
		  <tr>
			<td>Ellen&#337;rz&eacute;si peri&oacute;dus:</td>
			<td><input type="text" name="period" value="<?php echo $this->_tpl_vars['rss']['period']; ?>
" dir="rtl" /> (perc)</td>
		  </tr>		  
		  <tr>
			<td>Hírek időrendi sorrendje:</td>
			<td>
				<select name="news_order">
                    <option value="desc">Csökkenő</option>
                    <option value="asc" <?php if ($this->_tpl_vars['rss']['news_order'] == 'asc'): ?>selected<?php endif; ?> >Növekvő</option>
				</select>
			</td>
		  </tr>		  
		  <tr>
			<td>St&aacute;tusz:</td>
			<td>
				<select name="status">
					<?php if ($this->_tpl_vars['rss']['status'] == 1 || $this->_tpl_vars['rss']['status'] == ""): ?>
						<option value="1" selected>Akt&iacute;v</option>
						<option value="0">Inakt&iacute;v</option>
					<?php else: ?>
						<option value="1">Akt&iacute;v</option>
						<option value="0" selected>Inakt&iacute;v</option>
					<?php endif; ?>
				</select>
			</td>
		  </tr>		  
		  <tr>
		  	<td colspan="2"><input type="submit" name="" value="V&eacute;grehajt" class="button" /></td>
		  </tr>
		</table>
		</form>
		<?php $this->_smarty_vars['capture']['rss'] = ob_get_contents(); ob_end_clean(); ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.html", 'smarty_include_vars' => array('title' => "H&iacute;rfolyam",'content' => $this->_smarty_vars['capture']['rss'],'extra' => "width=100%")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</td>
  </tr>	
</table>