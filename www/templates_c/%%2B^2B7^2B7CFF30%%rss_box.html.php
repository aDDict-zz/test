<?php /* Smarty version 2.6.6, created on 2010-05-05 21:09:29
         compiled from rss_box.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'rss_box.html', 1, false),array('modifier', 'escape', 'rss_box.html', 11, false),)), $this); ?>
	<div class="blue" id="rss_box_<?php echo $this->_tpl_vars['feed']['user_feed_id']; ?>
" alt="bid_<?php echo $this->_tpl_vars['max_box_id']; ?>
_<?php echo ((is_array($_tmp=@$this->_tpl_vars['cl'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
">
<div class="topboxcont"><div class="bottombox"><div class="leftbox"><div class="rightbox"><div class="blbox"><div class="brbox"><div class="tlbox"><div class="trbox">
		<div class="head">
			<div class="edit"><a href="#"  title="Hírek frissítése"><img align="top" src="i/refresh.gif"></a><?php if ($this->_tpl_vars['feed']['editable'] == 1): ?> <a href="#" title="Hírdoboz szerkesztése">Szerkeszt</a><?php else: ?> <a href="#"></a><?php endif; ?><a href="#" title="H&iacute;rdoboz bez&aacute;r&aacute;sa"><img src="i/var.gif" align="top" alt="H&iacute;rdoboz bez&aacute;r&aacute;sa" /></a><a href="#" title="Hírdoboz törlése"><img src="i/close.gif" align="top" alt="Hírdoboz törlése"  /></a></div>
			<h3><a href="#"><img src="<?php if ($this->_tpl_vars['feed']['type'] == 4): ?>i/in.gif<?php elseif ($this->_tpl_vars['feed']['agency_favicon'] != ''):  echo $this->_tpl_vars['feed']['agency_favicon'];  else: ?>i/rss.gif<?php endif; ?>" style="margin-top:-5px;height:16px;width:16px;" align="absmiddle" /></a> <a href="<?php echo $this->_tpl_vars['feed']['agency_url']; ?>
" target="_blank"><?php echo $this->_tpl_vars['feed']['rss_name']; ?>
<!-- (<?php echo $this->_tpl_vars['max_box_id']; ?>
-<?php echo ((is_array($_tmp=@$this->_tpl_vars['cl'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
)--></a></h3>
		</div>
		<div class="editContent">
			<table border="0" cellspacing="0" cellpadding="2">
			  <tr>
				<td>C&iacute;m:</td>
				<td><input type="text" name="title" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['feed']['rss_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
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
                <?php if ($this->_tpl_vars['feed']['type'] == 4): ?>
				<td>Kulcssz&oacute;:</td>
				<td><input type="text" name="feed" value="<?php echo $this->_tpl_vars['feed']['keywords']; ?>
" /></td>
                <?php else: ?>
				<td>Forr&aacute;s:</td>
				<td><input type="text" name="feed" value="<?php echo $this->_tpl_vars['feed']['rss_url']; ?>
" /></td>
                <?php endif; ?>
			  </tr>
			  <tr>
				<td>Elemek:</td>
				<td>
					<select name="items">
						<?php unset($this->_sections['l']);
$this->_sections['l']['name'] = 'l';
$this->_sections['l']['loop'] = is_array($_loop=$this->_tpl_vars['items']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['l']['show'] = true;
$this->_sections['l']['max'] = $this->_sections['l']['loop'];
$this->_sections['l']['step'] = 1;
$this->_sections['l']['start'] = $this->_sections['l']['step'] > 0 ? 0 : $this->_sections['l']['loop']-1;
if ($this->_sections['l']['show']) {
    $this->_sections['l']['total'] = $this->_sections['l']['loop'];
    if ($this->_sections['l']['total'] == 0)
        $this->_sections['l']['show'] = false;
} else
    $this->_sections['l']['total'] = 0;
if ($this->_sections['l']['show']):

            for ($this->_sections['l']['index'] = $this->_sections['l']['start'], $this->_sections['l']['iteration'] = 1;
                 $this->_sections['l']['iteration'] <= $this->_sections['l']['total'];
                 $this->_sections['l']['index'] += $this->_sections['l']['step'], $this->_sections['l']['iteration']++):
$this->_sections['l']['rownum'] = $this->_sections['l']['iteration'];
$this->_sections['l']['index_prev'] = $this->_sections['l']['index'] - $this->_sections['l']['step'];
$this->_sections['l']['index_next'] = $this->_sections['l']['index'] + $this->_sections['l']['step'];
$this->_sections['l']['first']      = ($this->_sections['l']['iteration'] == 1);
$this->_sections['l']['last']       = ($this->_sections['l']['iteration'] == $this->_sections['l']['total']);
?>
							<?php if ($this->_tpl_vars['items'][$this->_sections['l']['index']] == 10): ?>
								<option value="<?php echo $this->_tpl_vars['items'][$this->_sections['l']['index']]; ?>
" selected><?php echo $this->_tpl_vars['items'][$this->_sections['l']['index']]; ?>
</option>
							<?php else: ?>
								<option value="<?php echo $this->_tpl_vars['items'][$this->_sections['l']['index']]; ?>
"><?php echo $this->_tpl_vars['items'][$this->_sections['l']['index']]; ?>
</option>
							<?php endif; ?>
							
						<?php endfor; endif; ?>
					</select>
				</td>
			  </tr>
			  			  <tr>
				<td colspan="2"><input type="button" value="Elment" /></td>
			  </tr>
			</table>		
		</div>	
		<div class="content_2">
			Bet&ouml;lt&eacute;s alatt...
		</div>
</div></div></div></div></div></div></div></div>
	</div>	