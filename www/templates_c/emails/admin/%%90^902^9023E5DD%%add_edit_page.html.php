<?php /* Smarty version 2.6.6, created on 2010-04-30 16:34:45
         compiled from add_edit_page.html */ ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="page_title">
        <?php if ($this->_tpl_vars['page_id'] == ''): ?>
			Új oldal létrehozása
        <?php else: ?>
			Oldal m&oacute;dos&iacute;t&aacute;sa
		<?php endif; ?>
	</td>
  </tr>
  <tr>
    <td style="padding-bottom:15px; padding-top:15px;">
		<?php ob_start(); ?>		
		<form name="page" method="post" action="index.php?id=<?php echo $this->_tpl_vars['id']; ?>
&sub_id=<?php echo $this->_tpl_vars['sub_id']; ?>
">
		<?php if ($this->_tpl_vars['page']['page_id'] != ""): ?>
			<input type="hidden" name="action" value="update_page" />
			<input type="hidden" name="page_id" value="<?php echo $this->_tpl_vars['page']['page_id']; ?>
" />
		<?php else: ?>
			<input type="hidden" name="action" value="add_page" />
		<?php endif; ?>
		<table border="0" cellspacing="0" cellpadding="5">
		  <?php if ($this->_tpl_vars['message'] != ""): ?>
		  <tr>
			<td colspan="2" style="color:#FF0000;font-weight:bold;"><?php echo $this->_tpl_vars['message']; ?>
</td>
		  </tr>
		  <?php endif; ?>
		  <tr>
			<td>Oldaln&eacute;v:</td>
			<td><input type="text" name="page_name" value="<?php echo $this->_tpl_vars['page']['page_name']; ?>
" /></td>
		  </tr>
		  <tr>
			<td>Oldal URL:</td>
			<td><input type="text" name="page_url" value="<?php echo $this->_tpl_vars['page']['page_url']; ?>
" /></td>
		  </tr>
		  <tr>
			<td>Oldalc&iacute;m:</td>
			<td><input type="text" name="page_title" value="<?php echo $this->_tpl_vars['page']['page_title']; ?>
" /></td>
		  </tr>
		  <tr>
			<td>Oldalle&iacute;r&aacute;s</td>
			<td>
				<textarea name="page_description" cols="15" rows="5"><?php echo $this->_tpl_vars['page']['page_description']; ?>
</textarea>
			</td>
		  </tr>
		  <tr>
			<td>Kulcsszavak:</td>
			<td>
				<textarea name="page_keywords" cols="15" rows="5"><?php echo $this->_tpl_vars['page']['page_keywords']; ?>
</textarea>
			</td>
		  </tr>
		  <tr style="display:none;">
			<td>Template:</td>
			<td>
				<select name="page_template" style="width:100%; ">
					<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['templates']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
						<?php if ($this->_tpl_vars['page']['page_template'] == $this->_tpl_vars['templates'][$this->_sections['i']['index']]['template_url']): ?>
						<option value="<?php echo $this->_tpl_vars['templates'][$this->_sections['i']['index']]['template_url']; ?>
" selected><?php echo $this->_tpl_vars['templates'][$this->_sections['i']['index']]['template_name']; ?>
</option>	
						<?php else: ?>
						<option value="<?php echo $this->_tpl_vars['templates'][$this->_sections['i']['index']]['template_url']; ?>
"><?php echo $this->_tpl_vars['templates'][$this->_sections['i']['index']]['template_name']; ?>
</option>
						<?php endif; ?>
					<?php endfor; endif; ?>
				</select>
			</td>
		  </tr>
		  <tr style="display:none;">
			<td>HTML:</td>
			<td><input type="text" name="page_html" value="<?php echo $this->_tpl_vars['page']['page_html']; ?>
" /></td>
		  </tr>
		  <tr>
			<td>Oldal RSS:</td>
			<td><input type="text" name="page_xml" value="<?php echo $this->_tpl_vars['page']['page_xml']; ?>
" /></td>
		  </tr>
		  <tr>
		  	<td colspan="2"><input type="submit" name="" value="V&eacute;grehajt" class="button" /></td>
		  </tr>
		</table>
		</form>
		<?php $this->_smarty_vars['capture']['domain_update'] = ob_get_contents(); ob_end_clean(); ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.html", 'smarty_include_vars' => array('title' => "Oldal param&eacute;terek",'content' => $this->_smarty_vars['capture']['domain_update'],'extra' => "width=100%")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</td>
  </tr>	
</table>