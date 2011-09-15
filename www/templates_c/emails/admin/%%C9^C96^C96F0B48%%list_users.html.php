<?php /* Smarty version 2.6.6, created on 2010-03-01 10:48:53
         compiled from list_users.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'list_users.html', 39, false),)), $this); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="page_title">Felhaszn&aacute;l&oacute;k</td>
  </tr>
  <?php if ($this->_tpl_vars['error'] != ''): ?>
  <tr>
  	<td style="color:#FF0000;font-weight:bold;"><?php echo $this->_tpl_vars['error']; ?>
</td>
  </tr>
  <?php endif; ?>
  <tr>
  	<td>
		<form name="search_users" method="get" action="index.php">
		<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['id']; ?>
" />
		<input type="hidden" name="sub_id" value="<?php echo $this->_tpl_vars['sub_id']; ?>
" />		
		<?php ob_start(); ?>
			<table border="0" cellspacing="0" cellpadding="5">
			  <tr>
				<td><b>E-mail:&nbsp;</b><input type="text" name="email" value="<?php echo $this->_tpl_vars['email']; ?>
" /></td>
				<td><input type="submit" value="Keres" class="button" /></td>
			  </tr>
			</table>
		<?php $this->_smarty_vars['capture']['search_users'] = ob_get_contents(); ob_end_clean(); ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.html", 'smarty_include_vars' => array('title' => "Felhaszn&aacute;l&oacute;kkeres&eacute;se",'content' => $this->_smarty_vars['capture']['search_users'],'extra' => "width=100%")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		</form>
	</td>
  </tr>
  <tr>
  	<td class="page_title"></td>
  </tr>
  <tr>
    <td>
		<table width="100%" border="0" cellspacing="0" cellpadding="4" style="border:1px solid #BDBEBD;">
		  <tr style="height:20px;background-image:url('../i/bh.gif');background-repeat:repeat-x;">
			<td align="center" style="border-bottom:1px solid #BDBEBD;"><b>ID</b></td>
			<td style="border-bottom:1px solid #BDBEBD;"><b>Email</b></td>
			<td align="center" style="border-bottom:1px solid #BDBEBD;"><b>M&#369;veletek</b></td>
		  </tr>
		  <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['users']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
" id="a_<?php echo $this->_tpl_vars['agencies'][$this->_sections['i']['index']]['agency_id']; ?>
">
			<td align="center"><?php echo $this->_tpl_vars['users'][$this->_sections['i']['index']]['user_id']; ?>
</td>
			<td><a href="mailto:<?php echo $this->_tpl_vars['users'][$this->_sections['i']['index']]['user_email']; ?>
"><?php echo $this->_tpl_vars['users'][$this->_sections['i']['index']]['user_email']; ?>
</a></td>			
			<td align="center">[ <a href="?id=<?php echo $this->_tpl_vars['id']; ?>
&sub_id=2&uid=<?php echo $this->_tpl_vars['users'][$this->_sections['i']['index']]['user_id']; ?>
">M&oacute;dos&iacute;t</a> ] [ <a href="#" onClick="if(confirm('Biztos benne?')) document.delete_user_<?php echo $this->_tpl_vars['users'][$this->_sections['i']['index']]['user_id']; ?>
.submit();">T&ouml;r&ouml;l</a> ]
				<form name="delete_user_<?php echo $this->_tpl_vars['users'][$this->_sections['i']['index']]['user_id']; ?>
" method="post" action="index.php?id=<?php echo $this->_tpl_vars['id']; ?>
&sub_id=<?php echo $this->_tpl_vars['sub_id']; ?>
">
					<input type="hidden" name="action" value="delete_user" />
					<input type="hidden" name="user_id" value="<?php echo $this->_tpl_vars['users'][$this->_sections['i']['index']]['user_id']; ?>
" />
				</form>
			</td>				
		  </tr>
		  <?php endfor; endif; ?>		  
		</table>
	</td>
  </tr>
</table>
<table border="0" cellspacing="4" cellpadding="2" align="center" class="paging">
  <tr>		
	<td><?php if ($this->_tpl_vars['__GT']['current'] != 1 && $this->_tpl_vars['__GT']['total_pages'] != 0): ?><a href="index.php?<?php echo $this->_tpl_vars['url']; ?>
&page=1&plimit=<?php echo $this->_tpl_vars['__GT']['limit']; ?>
" target="_self">Els&#337;</a><?php endif; ?></td>
	<td><?php if ($this->_tpl_vars['__GT']['current'] != 1 && $this->_tpl_vars['__GT']['total_pages'] != 0): ?><a href="index.php?<?php echo $this->_tpl_vars['url']; ?>
&page=<?php echo $this->_tpl_vars['__GT']['prev']['link']; ?>
&plimit=<?php echo $this->_tpl_vars['__GT']['limit']; ?>
" target="_self">El&#337;z&#337;</a><?php endif; ?></td>
	<td>
		<table border="0" cellspacing="3" cellpadding="0" align="center" class="paging">
			<tr>
				<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['__GT']['pages']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
					<td align="center">								
						<?php if ($this->_tpl_vars['__GT']['pages'][$this->_sections['i']['index']]['link'] == $this->_tpl_vars['__GT']['current']): ?>
						<b><?php echo $this->_tpl_vars['__GT']['pages'][$this->_sections['i']['index']]['link']; ?>
</b>
						<?php else: ?>
						<a href="index.php?<?php echo $this->_tpl_vars['url']; ?>
&page=<?php echo $this->_tpl_vars['__GT']['pages'][$this->_sections['i']['index']]['link']; ?>
&plimit=<?php echo $this->_tpl_vars['__GT']['limit']; ?>
" target="_self"><?php echo $this->_tpl_vars['__GT']['pages'][$this->_sections['i']['index']]['link']; ?>
</a>
						<?php endif; ?>						
					</td>			
				<?php endfor; endif; ?>			
			</tr>
		</table>
	</td>				
	<td><?php if ($this->_tpl_vars['__GT']['current'] != $this->_tpl_vars['__GT']['total_pages'] && $this->_tpl_vars['__GT']['total_pages'] != 0): ?><a href="index.php?<?php echo $this->_tpl_vars['url']; ?>
&page=<?php echo $this->_tpl_vars['__GT']['next']['link']; ?>
&plimit=<?php echo $this->_tpl_vars['__GT']['limit']; ?>
" target="_self">K&ouml;vetkez&#337;</a><?php endif; ?></td>
	<td><?php if ($this->_tpl_vars['__GT']['current'] != $this->_tpl_vars['__GT']['total_pages'] && $this->_tpl_vars['__GT']['total_pages'] != 0): ?><a href="index.php?<?php echo $this->_tpl_vars['url']; ?>
&page=<?php echo $this->_tpl_vars['__GT']['total_pages']; ?>
&plimit=<?php echo $this->_tpl_vars['__GT']['limit']; ?>
" target="_self">Utols&oacute;</a><?php endif; ?></td>
  </tr>  
</table>