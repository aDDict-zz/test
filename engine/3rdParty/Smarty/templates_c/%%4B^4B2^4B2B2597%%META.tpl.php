<?php /* Smarty version 2.6.26, created on 2009-07-06 02:33:46
         compiled from META.tpl */ ?>
<?php $_from = $this->_tpl_vars['var']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
    <?php if ($this->_tpl_vars['item'] == "content-type"): ?>
        <meta http-equiv="<?php echo $this->_tpl_vars['key']; ?>
" content="charset=<?php echo $this->_tpl_vars['item']; ?>
" />
    <?php else: ?>
        <meta name="<?php echo $this->_tpl_vars['key']; ?>
" content="<?php echo $this->_tpl_vars['item']; ?>
" />
    <?php endif; ?>
<?php endforeach; endif; unset($_from); ?>