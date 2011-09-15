<?php /* Smarty version 2.6.6, created on 2011-01-14 10:28:11
         compiled from add_edit_cat_colors.html */ ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="page_title">
        <?php if ($this->_tpl_vars['css']['id'] == ''): ?>
			&Uacute;j kateg&oacute;ria sz&iacute;n l&eacute;trehoz&aacute;sa
        <?php else: ?>
			Kateg&oacute;ria sz&iacute;n m&oacute;dos&iacute;t&aacute;sa
		<?php endif; ?>
	</td>
  </tr>
  <tr>
    <td style="padding-bottom:15px; padding-top:15px;">
		<?php ob_start(); ?>		
		<form name="page" method="post" action="index.php?id=<?php echo $this->_tpl_vars['id']; ?>
&sub_id=<?php echo $this->_tpl_vars['sub_id']; ?>
">
		<?php if ($this->_tpl_vars['css']['id'] != ""): ?>
			<input type="hidden" name="action" value="update_cat_color" />
			<input type="hidden" name="cat_css_id" value="<?php echo $this->_tpl_vars['css']['id']; ?>
" />
		<?php else: ?>
			<input type="hidden" name="action" value="add_cat_color" />
		<?php endif; ?>
		<table border="0" cellspacing="0" cellpadding="5">
		  <?php if ($this->_tpl_vars['message'] != ""): ?>
		  <tr>
			<td colspan="2" style="color:#FF0000;font-weight:bold;"><?php echo $this->_tpl_vars['message']; ?>
</td>
		  </tr>
		  <?php endif; ?>
		  <tr>
			<td>N&eacute;v:</td>
			<td><input type="text" name="name" value="<?php echo $this->_tpl_vars['css']['name']; ?>
" /></td>
		  </tr>
		  <tr>
			<td>URL:</td>
			<td><input type="text" name="css" value="<?php echo $this->_tpl_vars['css']['css']; ?>
" /></td>
		  </tr>
		  <tr>
			<td>Le&iacute;r&aacute;s:</td>
			<td>
				<textarea name="description" cols="15" rows="5"><?php echo $this->_tpl_vars['css']['description']; ?>
</textarea>
			</td>
		  </tr>		  
		  <tr>
		  	<td colspan="2"><input type="submit" name="" value="V&eacute;grehajt" class="button" /></td>
		  </tr>
		</table>
		</form>
		<?php $this->_smarty_vars['capture']['cat_color_update'] = ob_get_contents(); ob_end_clean(); ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.html", 'smarty_include_vars' => array('title' => "Kateg&oacute;ria sz&iacute;n  param&eacute;terek",'content' => $this->_smarty_vars['capture']['cat_color_update'],'extra' => "width=100%")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</td>
  </tr>	
</table>