<?php /* Smarty version 2.6.6, created on 2009-12-21 13:30:48
         compiled from login.html */ ?>
<table width="100" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td class="page_title">Bejelentkez&eacute;s</td>
  </tr>
  <tr>
  	<td>
		<?php ob_start(); ?>		
		<form name="login" method="post" action="index.php?id=4">
		<input type="hidden" name="action" value="login" />
		<table border="0" cellspacing="0" cellpadding="5">
		  <?php if ($this->_tpl_vars['message'] != ""): ?>
		  <tr>
			<td colspan="2" style="color:#FF0000;font-weight:bold;">
				<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['message']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
					<?php echo $this->_tpl_vars['message'][$this->_sections['i']['index']]; ?>
<br />
				<?php endfor; endif; ?>
			</td>
		  </tr>
		  <?php endif; ?>
		  <tr>
			<td>Felhaszn&aacute;l&oacute;n&eacute;v:</td>
			<td><input type="text" name="login_name" value="<?php echo $this->_tpl_vars['login_name']; ?>
" /></td>
		  </tr>
		  <tr>
			<td>Jelsz&oacute;:</td>
			<td><input type="password" name="password" value="<?php echo $this->_tpl_vars['password']; ?>
" /></td>
		  </tr>
		  <tr>
			<td colspan="2" align="center"><input type="submit" name="" value="Bejelentkez&eacute;s" class="button" /></td>
		  </tr>
		</table>
		</form>
		<?php $this->_smarty_vars['capture']['login'] = ob_get_contents(); ob_end_clean(); ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.html", 'smarty_include_vars' => array('title' => "Bejelentkez&eacute;s",'content' => $this->_smarty_vars['capture']['login'],'extra' => "width=100 align=center")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</td>
  </tr>	
</table>