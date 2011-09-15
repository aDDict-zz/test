<?php /* Smarty version 2.6.6, created on 2010-05-04 10:22:48
         compiled from pages.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'pages.html', 33, false),)), $this); ?>
<?php echo '
<script language="javascript">
function delPage(pageID, pageName){
	if(confirm(\'Biztos benne, hogy torli a(z) `\' + pageName +\'` oldalt?\')){
		document.forms[\'del_page_\'+pageID].submit();
	}
	return false;
}
</script>
'; ?>
 


<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="page_title">Oldalak list&aacute;ja</td>
  </tr>
  <tr>
    <td>
		<table id="pagelist" width="100%" border="0" cellspacing="0" cellpadding="4" style="border:1px solid #BDBEBD;">
		  <tr style="height:20px;background-image:url('../i/bh.gif');background-repeat:repeat-x;">
			<td align="center" style="border-bottom:1px solid #BDBEBD;"><b>ID</b></td>
			<td style="border-bottom:1px solid #BDBEBD;"><b>N&eacute;v</b></td>
			<td style="border-bottom:1px solid #BDBEBD;" colspan="2" align="center"><b>Kateg&oacute;ri&aacute;k</b></td>
						<td align="center" style="border-bottom:1px solid #BDBEBD;"><b>M&#369;veletek</b></td>
            <td>Sorrend</td>
		  </tr>
		  <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['pages']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
		  <tr id="page-<?php echo $this->_tpl_vars['pages'][$this->_sections['i']['index']]['page_id']; ?>
" bgcolor="<?php echo smarty_function_cycle(array('values' => "#EAEAEA,#F4F4F4"), $this);?>
">
			<td align="center"><?php echo $this->_tpl_vars['pages'][$this->_sections['i']['index']]['page_id']; ?>
</td>
			<td><?php echo $this->_tpl_vars['pages'][$this->_sections['i']['index']]['page_name']; ?>
</td>
			<td>[ <a href="index.php?id=<?php echo $this->_tpl_vars['id']; ?>
&sub_id=3&page_id=<?php echo $this->_tpl_vars['pages'][$this->_sections['i']['index']]['page_id']; ?>
">&Aacute;lltal&aacute;nos</a> ]</td>
			<td>[ <a href="index.php?id=<?php echo $this->_tpl_vars['id']; ?>
&sub_id=4&page_id=<?php echo $this->_tpl_vars['pages'][$this->_sections['i']['index']]['page_id']; ?>
">R&ouml;gz&iacute;tett</a> ]</td>
						<td align="center">
				[ <a href="index.php?id=<?php echo $this->_tpl_vars['id']; ?>
&sub_id=2&page_id=<?php echo $this->_tpl_vars['pages'][$this->_sections['i']['index']]['page_id']; ?>
">M&oacute;dos&iacute;t</a> ] [ <a href="#" onClick="delPage('<?php echo $this->_tpl_vars['pages'][$this->_sections['i']['index']]['page_id']; ?>
', '<?php echo $this->_tpl_vars['pages'][$this->_sections['i']['index']]['page_name']; ?>
');">T&ouml;r&ouml;l</a> ]
				<form name="del_page_<?php echo $this->_tpl_vars['pages'][$this->_sections['i']['index']]['page_id']; ?>
" method="post" action="index.php?id=<?php echo $this->_tpl_vars['id']; ?>
&sub_id=<?php echo $this->_tpl_vars['sub_id']; ?>
">
					<input type="hidden" name="action" value="del_page" />
					<input type="hidden" name="page_id" value="<?php echo $this->_tpl_vars['pages'][$this->_sections['i']['index']]['page_id']; ?>
" />
				</form>
			</td>
		  </tr>
		  <?php endfor; endif; ?>		  
		</table>
	</td>
  </tr>
</table>