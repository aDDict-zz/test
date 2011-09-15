<?php /* Smarty version 2.6.6, created on 2009-12-18 15:47:17
         compiled from rss_list_multi.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'rss_list_multi.html', 2, false),array('modifier', 'escape', 'rss_list_multi.html', 6, false),)), $this); ?>
<?php if (count($_from = (array)$this->_tpl_vars['results'])):
    foreach ($_from as $this->_tpl_vars['idx'] => $this->_tpl_vars['result']):
?>
<?php if (count($this->_tpl_vars['result']['entries'])): ?>
<?php if ($this->_tpl_vars['titles'][$this->_tpl_vars['idx']]): ?><b><?php echo $this->_tpl_vars['titles'][$this->_tpl_vars['idx']]; ?>
</b><?php endif; ?>
<ul>
	<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['result']['entries']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
			<li <?php if ($this->_sections['i']['index'] >= $this->_tpl_vars['result']['entries_nr'] && $this->_tpl_vars['result']['entries_nr'] != ''): ?>class="hidden"<?php endif; ?>><a class="newsitem" href="click.php?link=<?php echo ((is_array($_tmp=$this->_tpl_vars['result']['entries'][$this->_sections['i']['index']]['link'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&title=<?php echo ((is_array($_tmp=$this->_tpl_vars['result']['entries'][$this->_sections['i']['index']]['title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  echo $this->_tpl_vars['rss_link_tail']; ?>
" alt="<?php echo $this->_tpl_vars['result']['entries'][$this->_sections['i']['index']]['description']; ?>
" target="_blank" >
			<?php if ($this->_tpl_vars['result']['entries'][$this->_sections['i']['index']]['title'] == ""): ?> [...]
            <?php else: ?><span <?php if ($this->_tpl_vars['result']['entries'][$this->_sections['i']['index']]['marked']): ?>id="news_<?php echo $this->_tpl_vars['result']['entries'][$this->_sections['i']['index']]['news_id']; ?>
" style="font-weight:bold;color:#bf2e1a;"<?php endif; ?>><?php echo $this->_tpl_vars['result']['entries'][$this->_sections['i']['index']]['title'];  echo $this->_tpl_vars['result']['entries'][$this->_sections['i']['index']]['title_tail']; ?>
</span>
			<?php endif; ?>	
			</a></li>
	<?php endfor; endif; ?>
</ul>
<?php endif; ?>
<?php endforeach; unset($_from); endif; ?>