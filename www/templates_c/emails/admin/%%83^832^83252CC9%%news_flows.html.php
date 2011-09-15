<?php /* Smarty version 2.6.6, created on 2010-01-19 15:00:02
         compiled from news_flows.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'news_flows.html', 26, false),)), $this); ?>
<?php echo '
<script language="javascript">
function delFeed(feedID, feedName){
	if(confirm(\'Biztos, hogy torli a(z) `\' + feedName + \'` hrifolyamot?\')){
		document.forms[\'del_news_flow_\'+feedID].submit();
	}
	return false;
}
</script>
'; ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="page_title">H&iacute;rfolyamok</td>
  </tr>
  <tr>
    <td>
		<table width="100%" border="0" cellspacing="0" cellpadding="4" style="border:1px solid #BDBEBD;">
		  <tr style="height:20px;background-image:url('../i/bh.gif');background-repeat:repeat-x;">
			<td align="center" style="border-bottom:1px solid #BDBEBD;"><b>ID</b></td>
			<td style="border-bottom:1px solid #BDBEBD;"><b>H&iacute;rforr&aacute;s</b></td>
			<td style="border-bottom:1px solid #BDBEBD;"><b>N&eacute;v</b></td>
			<td style="border-bottom:1px solid #BDBEBD;" align="center"><b>Tipus</b></td>
			<td align="center" style="border-bottom:1px solid #BDBEBD;"><b>M&#369;veletek</b></td>
		  </tr>
		  <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['rss']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
		  <tr bgcolor="<?php echo smarty_function_cycle(array('values' => "#EAEAEA,#F4F4F4"), $this);?>
">		  	
			<td align="center"><?php echo $this->_tpl_vars['rss'][$this->_sections['i']['index']]['id']; ?>
</td>
			<td><a href="<?php echo $this->_tpl_vars['rss'][$this->_sections['i']['index']]['agency_url']; ?>
" target="_blank"><?php echo $this->_tpl_vars['rss'][$this->_sections['i']['index']]['agency_name']; ?>
</a></td>			
			<td><a href="<?php echo $this->_tpl_vars['rss'][$this->_sections['i']['index']]['rss_url']; ?>
" target="_blank"><?php echo $this->_tpl_vars['rss'][$this->_sections['i']['index']]['rss_name']; ?>
</a></td>			
			<td align="center">
				<?php if ($this->_tpl_vars['rss'][$this->_sections['i']['index']]['feed_type'] == 1): ?>
					RSS
				<?php else: ?>
					HTML
				<?php endif; ?>
			</td>
			<td align="center">[ <a href="index.php?id=<?php echo $this->_tpl_vars['id']; ?>
&sub_id=4&rss_id=<?php echo $this->_tpl_vars['rss'][$this->_sections['i']['index']]['id']; ?>
">M&oacute;dos&iacute;t</a> ] [ <a href="#" onClick="delFeed('<?php echo $this->_tpl_vars['rss'][$this->_sections['i']['index']]['id']; ?>
', '<?php echo $this->_tpl_vars['rss'][$this->_sections['i']['index']]['rss_name']; ?>
')">T&ouml;r&ouml;l</a> ]
				<form name="del_news_flow_<?php echo $this->_tpl_vars['rss'][$this->_sections['i']['index']]['id']; ?>
" method="post" action="index.php?id=<?php echo $this->_tpl_vars['id']; ?>
&sub_id=<?php echo $this->_tpl_vars['sub_id']; ?>
">
					<input type="hidden" name="action" value="del_news_flow" />
					<input type="hidden" name="rss_id" value="<?php echo $this->_tpl_vars['rss'][$this->_sections['i']['index']]['id']; ?>
" />
				</form>
			</td>
		  </tr>
		  <?php endfor; endif; ?>		  
		</table>
	</td>
  </tr>
</table>