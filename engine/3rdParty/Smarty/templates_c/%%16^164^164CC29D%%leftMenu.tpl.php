<?php /* Smarty version 2.6.26, created on 2009-07-06 21:26:01
         compiled from leftMenu.tpl */ ?>
<?php $_from = $this->_tpl_vars['var']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
    <a href="/<?php echo $this->_tpl_vars['key']; ?>
" id="<?php echo $this->_tpl_vars['item']; ?>
"></a>
<?php endforeach; endif; unset($_from); ?>