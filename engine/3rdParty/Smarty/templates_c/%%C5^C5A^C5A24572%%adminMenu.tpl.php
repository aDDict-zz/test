<?php /* Smarty version 2.6.26, created on 2009-10-29 01:46:40
         compiled from adminMenu.tpl */ ?>
<div id="adminMenuCont">
	<ul id="adminMenu"> 
		<?php $_from = $this->_tpl_vars['var']['menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
			<?php if ($this->_tpl_vars['k'] == $this->_tpl_vars['var']['thisUrlPart']): ?>
			<li class="activ"><a href="/admin/<?php echo $this->_tpl_vars['k']; ?>
"><?php echo $this->_tpl_vars['v']['name']; ?>
</a></li>
			<?php else: ?>
			<li class="inactiv"><a href="/admin/<?php echo $this->_tpl_vars['k']; ?>
"><?php echo $this->_tpl_vars['v']['name']; ?>
</a></li>
			<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
	</ul>
	<div style="clear: both;"/>
</div>