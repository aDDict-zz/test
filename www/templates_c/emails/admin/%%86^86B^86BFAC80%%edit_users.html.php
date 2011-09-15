<?php /* Smarty version 2.6.6, created on 2010-03-01 10:49:08
         compiled from edit_users.html */ ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="page_title">
        Felhaszn&aacute;l&oacute; m&oacute;dos&iacute;t&aacute;sa		
	</td>
  </tr>
  <tr>
    <td style="padding-bottom:15px; padding-top:15px;">
		<?php ob_start(); ?>		
		<form name="user_update" method="post" action="index.php?id=<?php echo $this->_tpl_vars['id']; ?>
&sub_id=<?php echo $this->_tpl_vars['sub_id']; ?>
">
			<input type="hidden" name="action" value="update_user" />
			<input type="hidden" name="user[user_id]" value="<?php echo $this->_tpl_vars['user']['user_id']; ?>
" />		
		<table border="0" cellspacing="0" cellpadding="5">
		  <?php if ($this->_tpl_vars['error'] != ""): ?>
		  <tr>
			<td colspan="2" style="color:#FF0000;font-weight:bold;"><?php echo $this->_tpl_vars['error']; ?>
</td>
		  </tr>
		  <?php endif; ?>
		  <tr>
			<td>E-maiL:</td>
			<td><input type="text" name="user[user_email]" value="<?php echo $this->_tpl_vars['user']['user_email']; ?>
" /></td>
		  </tr>
		  <tr>
			<td>Jelsz&oacute;:</td>
			<td><input type="text" name="user[user_password]" value="<?php echo $this->_tpl_vars['user']['user_password']; ?>
" /></td>
		  </tr>
		  <tr>
		  	<td colspan="2"><input type="submit" name="" value="V&eacute;grehajt" class="button" /></td>
		  </tr>
		</table>
		</form>
		<?php $this->_smarty_vars['capture']['user_update'] = ob_get_contents(); ob_end_clean(); ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.html", 'smarty_include_vars' => array('title' => "Felhaszn&aacute;l&oacute; param&eacute;terek",'content' => $this->_smarty_vars['capture']['user_update'],'extra' => "width=100%")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</td>
  </tr>	
</table>