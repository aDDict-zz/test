<?php /* Smarty version 2.6.6, created on 2010-01-12 13:24:51
         compiled from list_rss_categories.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'list_rss_categories.html', 15, false),)), $this); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="page_title">RSS kateg&oacute;ri&aacute;k</td>
  </tr>
  <tr>
    <td>
		<table width="100%" border="0" cellspacing="0" cellpadding="4" style="border:1px solid #BDBEBD;">
		  <tr style="height:20px;background-image:url('../i/bh.gif');background-repeat:repeat-x;">
			<td align="center" style="border-bottom:1px solid #BDBEBD;"><b>ID</b></td>
			<td style="border-bottom:1px solid #BDBEBD;"><b>N&eacute;v</b></td>
			<td style="border-bottom:1px solid #BDBEBD;"><b>Sorrend</b></td>			
			<td align="center" style="border-bottom:1px solid #BDBEBD;"><b>M&#369;veletek</b></td>
		  </tr>
		  <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['categories']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
			<td align="center"><?php echo $this->_tpl_vars['categories'][$this->_sections['i']['index']]['cat_id']; ?>
</td>
			<td><?php echo $this->_tpl_vars['categories'][$this->_sections['i']['index']]['cat_name']; ?>
</td>
			<td align="center">
				<input type="text" name="position_<?php echo $this->_tpl_vars['categories'][$this->_sections['i']['index']]['cat_id']; ?>
" value="<?php echo $this->_tpl_vars['categories'][$this->_sections['i']['index']]['order_by']; ?>
" size="3" style="width:auto;" dir="rtl" />
			</td>			
			<td align="center">
				[ <a href="index.php?id=<?php echo $this->_tpl_vars['id']; ?>
&sub_id=2&cat_id=<?php echo $this->_tpl_vars['categories'][$this->_sections['i']['index']]['cat_id']; ?>
">M&oacute;dos&iacute;t</a> ] [ <a href="#" onClick="if(confirm('Biztos benne?')) document.del_cat_<?php echo $this->_tpl_vars['categories'][$this->_sections['i']['index']]['cat_id']; ?>
.submit();return false;">T&ouml;r&ouml;l</a> ]
				<form name="del_cat_<?php echo $this->_tpl_vars['categories'][$this->_sections['i']['index']]['cat_id']; ?>
" method="post" action="index.php?id=<?php echo $this->_tpl_vars['id']; ?>
&sub_id=<?php echo $this->_tpl_vars['sub_id']; ?>
">
					<input type="hidden" name="action" value="del_cat" />
					<input type="hidden" name="cat_id" value="<?php echo $this->_tpl_vars['categories'][$this->_sections['i']['index']]['cat_id']; ?>
" />
				</form>				
			</td>
		  </tr>
		  <?php endfor; endif; ?>		  
		</table>
	</td>
  </tr>
  <tr>
  	<td style="padding:10px 0px 10px 0px; "><input type="button" value="V&eacute;grehajt" class="button" onclick="document.getElementById('update_pages').submit();return false;"/></td>
  </tr>
</table>