<?php /* Smarty version 2.6.6, created on 2009-12-18 18:34:36
         compiled from feed_list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'feed_list.html', 5, false),)), $this); ?>
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['feeds']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
	<li  id="myfeed_<?php echo $this->_tpl_vars['feeds'][$this->_sections['i']['index']]['id']; ?>
">
        <div <?php if ($this->_tpl_vars['feeds'][$this->_sections['i']['index']]['myfeed']): ?>onmouseover="myFeedOver(<?php echo $this->_tpl_vars['feeds'][$this->_sections['i']['index']]['id']; ?>
);" onmouseout="myFeedOver(<?php echo $this->_tpl_vars['feeds'][$this->_sections['i']['index']]['id']; ?>
,1);"<?php endif; ?> > 
            <img src="<?php if ($this->_tpl_vars['feeds'][$this->_sections['i']['index']]['type'] == 4): ?>i/in.gif<?php elseif ($this->_tpl_vars['feeds'][$this->_sections['i']['index']]['agency_favicon'] != ''):  echo $this->_tpl_vars['feeds'][$this->_sections['i']['index']]['agency_favicon'];  else: ?>i/rss.gif<?php endif; ?>" align="absmiddle" width="16" height="16" />
            <a id="myfeedlink_<?php echo $this->_tpl_vars['feeds'][$this->_sections['i']['index']]['id']; ?>
" href="#" onClick="addNewFeedBox('<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['feeds'][$this->_sections['i']['index']]['rss_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
', '<?php echo $this->_tpl_vars['feeds'][$this->_sections['i']['index']]['rss_url']; ?>
', '<?php echo $this->_tpl_vars['feeds'][$this->_sections['i']['index']]['agency_url']; ?>
', '<?php echo $this->_tpl_vars['feeds'][$this->_sections['i']['index']]['id']; ?>
', '<?php echo $this->_tpl_vars['general']; ?>
', '<?php echo $this->_tpl_vars['feeds'][$this->_sections['i']['index']]['type']; ?>
');return false;"><?php echo $this->_tpl_vars['feeds'][$this->_sections['i']['index']]['rss_name']; ?>
</a>
        </div>
    </li>
<?php endfor; endif; ?>