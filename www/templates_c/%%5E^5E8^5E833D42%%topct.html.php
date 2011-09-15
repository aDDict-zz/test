<?php /* Smarty version 2.6.6, created on 2009-12-18 15:47:27
         compiled from topct.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'topct.html', 2, false),)), $this); ?>
<?php if (count($_from = (array)$this->_tpl_vars['intervals'])):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['interval']):
?>
    <?php if (count($this->_tpl_vars['top'][$this->_tpl_vars['i']])): ?>
    <b><?php echo $this->_tpl_vars['interval']; ?>
</b><br/>
    <ul>
    <?php if (count($_from = (array)$this->_tpl_vars['top'][$this->_tpl_vars['i']])):
    foreach ($_from as $this->_tpl_vars['idx'] => $this->_tpl_vars['news']):
?>
        <li><a  class="newsitem" <?php if ($this->_tpl_vars['news']['highlighted']): ?>style="font-weight:bold;color:#bf2e1a;"<?php endif; ?> target="_blank" href="<?php echo $this->_tpl_vars['news']['url']; ?>
" <?php if ($this->_tpl_vars['news']['lead']): ?>onmouseover="attachNewsHint(this, '<?php echo $this->_tpl_vars['news']['lead']; ?>
');" <?php endif; ?>><?php echo $this->_tpl_vars['news']['title']; ?>
</a> - <i><?php echo $this->_tpl_vars['news']['rss_name']; ?>
</i></li>
    <?php endforeach; unset($_from); endif; ?>
    </ul>
    <?php endif;  endforeach; unset($_from); endif; ?>