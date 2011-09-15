<?php /* Smarty version 2.6.6, created on 2009-12-19 10:34:03
         compiled from html_box.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'html_box.html', 1, false),array('modifier', 'escape', 'html_box.html', 11, false),)), $this); ?>
	<div class="blue" id="html_box_<?php echo $this->_tpl_vars['htmlbox']['user_htmlbox_id']; ?>
" alt="bid_<?php echo $this->_tpl_vars['htmlbox']['bid']; ?>
_<?php echo ((is_array($_tmp=@$this->_tpl_vars['cl'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
">
<div class="topboxcont"><div class="bottombox"><div class="leftbox"><div class="rightbox"><div class="blbox"><div class="brbox"><div class="tlbox"><div class="trbox">
		<div class="head">
			<div class="edit"><a href="#"  title="Frissítés"><img align="top" src="i/refresh.gif"></a><?php if ($this->_tpl_vars['htmlbox']['editable'] == 'yes'): ?><a href="#" title="Doboz szerkesztése">Szerkeszt</a><?php else: ?> <a href="#"></a><?php endif; ?><a> </a><a href="#" title="Doboz törlése"><img src="i/close.gif" align="top" alt="Doboz törlése"  /></a></div>
			<h1><a href="#"><img src="<?php echo ((is_array($_tmp=@$this->_tpl_vars['htmlbox']['favicon'])) ? $this->_run_mod_handler('default', true, $_tmp, 'i/in.gif') : smarty_modifier_default($_tmp, 'i/in.gif')); ?>
" style="margin-top:-5px;height:16px;width:16px;" align="absmiddle" /></a> <a><?php echo $this->_tpl_vars['htmlbox']['title']; ?>
<!-- (<?php echo $this->_tpl_vars['htmlbox']['bid']; ?>
)--></a></h1>
		</div>
		<div class="editContent">
			<table border="0" cellspacing="0" cellpadding="2">
			  <tr>
				<td>C&iacute;m:</td>
				<td><input type="text" name="title" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['htmlbox']['rss_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" /></td>
			  </tr>
			  <tr>
				<td>Sz&iacute;n:</td>
				<td>
                    <div id="c0"></div>
                    <div id="c1"></div>
                    <div id="c2"></div>
                    <div id="c3"></div>
                    <div id="c4"></div>
                    <div id="c5"></div>
                    <div id="c6"></div>
				</td>
			  </tr>
			  <tr>
				<td colspan="2"><input type="button" value="Elment" /></td>
			  </tr>
			</table>		
		</div>	
		<div class="content_2">
        <?php echo $this->_tpl_vars['htmlbox']['html']; ?>

		</div>
</div></div></div></div></div></div></div></div>
	</div>	