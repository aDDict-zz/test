<?php /* Smarty version 2.6.26, created on 2009-12-09 21:04:27
         compiled from dependencies.tpl */ ?>
<?php $_from = $this->_tpl_vars['var']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
    <?php if ($this->_tpl_vars['item'] == 'css'): ?>
        <link rel="stylesheet" type="text/css" href="/css/<?php echo $this->_tpl_vars['key']; ?>
.css" media="all" />
    <?php elseif ($this->_tpl_vars['item'] == 'js'): ?>
        <script type="text/javascript" src="/js/<?php echo $this->_tpl_vars['key']; ?>
.js"></script>
	<?php elseif ($this->_tpl_vars['item'] == 'tmc'): ?>
        <script type="text/javascript" src="/js/tinymce/<?php echo $this->_tpl_vars['key']; ?>
.js"></script>
    <?php endif; ?>
<?php endforeach; endif; unset($_from); ?>