<?php /* Smarty version 2.6.6, created on 2011-01-14 10:28:58
         compiled from add_edit_rss_categories.html */ ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="page_title">
		<?php if ($this->_tpl_vars['category']['cat_id'] == ''): ?>
			&Uacute;j RSS  kateg&oacute;ria
		<?php else: ?>
			RSS kateg&oacute;ria m&oacute;dos&iacute;t&aacute;sa
		<?php endif; ?>
	</td>
  </tr>
  <tr>
    <td>
		<?php ob_start(); ?>	
		<form name="add_edit_rss_cat" method="post" action="index.php?id=<?php echo $this->_tpl_vars['id']; ?>
&sub_id=<?php echo $this->_tpl_vars['sub_id']; ?>
">
		<?php if ($this->_tpl_vars['category']['cat_id'] == ''): ?>
		<input type="hidden" name="action" value="add_new_cat" />
		<?php else: ?>
		<input type="hidden" name="action" value="update_cat" />
		<input type="hidden" name="category[cat_id]" value="<?php echo $this->_tpl_vars['category']['cat_id']; ?>
" />
		<?php endif; ?>
		<table border="0" cellspacing="0" cellpadding="4">
		  <?php if ($this->_tpl_vars['error'] != ''): ?>
		  <tr>
		  	<td colspan="2" style="color:#FF0000;font-weight:bold"><?php echo $this->_tpl_vars['error']; ?>
</td>
		  </tr>
		  <?php endif; ?>
		  <tr>
			<td>N&eacute;v:</td>
			<td><input type="text" name="category[cat_name]" value="<?php echo $this->_tpl_vars['category']['cat_name']; ?>
" /></td>
		  </tr>
		  <tr>
			<td>C&iacute;m:</td>
			<td><input type="text" name="category[cat_title]" value="<?php echo $this->_tpl_vars['category']['cat_title']; ?>
" /></td>
		  </tr>
		  <tr>
			<td colspan="2">
				<input type="submit" value="V&eacute;grehajt" class="button" />
			</td>
		  </tr>
		</table>
		</form>
		<?php $this->_smarty_vars['capture']['category'] = ob_get_contents(); ob_end_clean(); ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.html", 'smarty_include_vars' => array('title' => "Kateg&oacute;ria",'content' => $this->_smarty_vars['capture']['category'],'extra' => "width=100%")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</td>
  </tr> 
</table>