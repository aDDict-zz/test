<?php /* Smarty version 2.6.6, created on 2009-12-22 16:31:06
         compiled from rss_out_olimpia.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'rss_out_olimpia.html', 4, false),)), $this); ?>
<ul id="hirek_keywords"><?php if (count($_from = (array)$this->_tpl_vars['news']['entries'])):foreach ($_from as $this->_tpl_vars['idx'] => $this->_tpl_vars['n']):?><li><a target="_blank" href="<?php echo $this->_tpl_vars['var']->baseurl; ?>click.php?link=<?php echo ((is_array($_tmp=$this->_tpl_vars['n']['link'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>&title=<?php echo $this->_tpl_vars['n']['enctitle']; ?>&rss=<?php echo $this->_tpl_vars['n']['rss_id']; ?>"><?php echo $this->_tpl_vars['n']['title'];  echo $this->_tpl_vars['n']['title_tail']; ?></a></li><?php endforeach; unset($_from); endif; ?></ul>