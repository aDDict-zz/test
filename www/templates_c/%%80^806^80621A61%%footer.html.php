<?php /* Smarty version 2.6.6, created on 2010-05-04 16:49:59
         compiled from footer.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'footer.html', 13, false),)), $this); ?>
<div id="footer">
    <?php if (count($_from = (array)$this->_tpl_vars['pages'])):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['p']):
?>
    <?php if ($this->_tpl_vars['id'] > 0): ?>
    <a href="<?php if ($this->_tpl_vars['var']->rewrite_engine):  echo $this->_tpl_vars['var']->rewrite_baseurl;  echo $this->_tpl_vars['p']['page_url'];  else: ?>index.php?page_id=<?php echo $this->_tpl_vars['id'];  endif; ?>"><?php echo $this->_tpl_vars['p']['page_name']; ?>
</a><?php if (! $this->_tpl_vars['p']['last']): ?> | <?php endif; ?>
    <?php endif; ?>
    <?php endforeach; unset($_from); endif; ?>
    <br>
	<a href="http://www.hirekmedia.hu/portfolio/hirek" target="_blank">Impresszum</a> | 
    <a href="http://www.hirekmedia.hu/" target="_blank">Adatkezel&eacute;si elvek</a> | 
    <a href="http://www.hirekmedia.hu/portfolio/hirek" target="_blank">M&eacute;diaaj&aacute;nlat</a> |
	<a href="#" onClick="showHelp();return false;">S&uacute;g&oacute;</a>
    <br />
	Copyright &copy; 2006-<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y") : smarty_modifier_date_format($_tmp, "%Y")); ?>
 Hírek.hu - Minden jog fenntartva
</div>