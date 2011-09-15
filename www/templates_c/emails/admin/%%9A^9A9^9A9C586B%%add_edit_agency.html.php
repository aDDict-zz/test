<?php /* Smarty version 2.6.6, created on 2010-01-19 15:00:17
         compiled from add_edit_agency.html */ ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="page_title">
        <?php if ($this->_tpl_vars['agency']['agency_id'] == ''): ?>
			Új h&iacute;rforr&aacute;s 
        <?php else: ?>
			H&iacute;rforr&aacute;s m&oacute;dos&iacute;t&aacute;sa
		<?php endif; ?>
	</td>
  </tr>
  <tr>
    <td style="padding-bottom:15px; padding-top:15px;">
		<?php ob_start(); ?>		
		<form name="page" method="post" action="index.php?id=<?php echo $this->_tpl_vars['id']; ?>
&sub_id=<?php echo $this->_tpl_vars['sub_id']; ?>
">
		<?php if ($this->_tpl_vars['agency']['agency_id'] != ""): ?>
			<input type="hidden" name="action" value="update_agency" />
			<input type="hidden" name="agency_id" value="<?php echo $this->_tpl_vars['agency']['agency_id']; ?>
" />
		<?php else: ?>
			<input type="hidden" name="action" value="add_agency" />
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
			<td><input type="text" name="agency_name" value="<?php echo $this->_tpl_vars['agency']['agency_name']; ?>
" /></td>
		  </tr>
		  <tr>
			<td>H&iacute;rforr&aacute;s URL:<br />(http:// kötelező)</td>
			<td><input type="text" name="agency_url" value="<?php echo $this->_tpl_vars['agency']['agency_url']; ?>
" /></td>
		  </tr>
		  <tr>
			<td>Favicon URL:<br />(http:// kötelező)</td>
			<td><input type="text" name="agency_favicon" value="<?php echo $this->_tpl_vars['agency']['agency_favicon']; ?>
" /></td>
		  </tr>
		  <tr>
		  	<td>Le&iacute;r&aacute;s:</td>
			<td><textarea name="description" rows="10"><?php echo $this->_tpl_vars['agency']['agency_description']; ?>
</textarea></td>
		  </tr>		  		  
		  <tr>
		  	<td>Tipus:</td>
			<td>
				<select name="agency_type">
					<?php if ($this->_tpl_vars['agency']['agency_type'] == 1 || $this->_tpl_vars['agency']['agency_type'] == ''): ?>
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
		  	<td colspan="2"><input type="submit" name="" value="V&eacute;grehajt" class="button" /></td>
		  </tr>
		</table>
		</form>
		<?php $this->_smarty_vars['capture']['agency'] = ob_get_contents(); ob_end_clean(); ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.html", 'smarty_include_vars' => array('title' => "H&iacute;rforr&aacute;s",'content' => $this->_smarty_vars['capture']['agency'],'extra' => "width=100%")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</td>
  </tr>	
</table>