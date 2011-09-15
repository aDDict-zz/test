<?php /* Smarty version 2.6.6, created on 2009-12-18 15:47:54
         compiled from search.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<body>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header_search.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menu.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div >
	<div id="search_results">			
		<h1 style="margin:10px 0 0 21px; ">Keresett kifejez&eacute;s: "<?php echo $this->_tpl_vars['q']; ?>
". Tal&aacute;latok: <?php echo $this->_tpl_vars['total_pages']; ?>
</h1>
		<ul>
			
			<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['news']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
			<li>
				<h2><a href="<?php echo $this->_tpl_vars['news'][$this->_sections['i']['index']]['news_url']; ?>
" target="_blank"><?php echo $this->_tpl_vars['news'][$this->_sections['i']['index']]['news_title']; ?>
</a></h2>
				<?php echo $this->_tpl_vars['news'][$this->_sections['i']['index']]['dadd']; ?>
 - <span><?php echo $this->_tpl_vars['news'][$this->_sections['i']['index']]['agency_name']; ?>
</span>
				<p><?php echo $this->_tpl_vars['news'][$this->_sections['i']['index']]['news_lead']; ?>
</p>
				<p></p>
			</li>
			<?php endfor; else: ?>
			<li style="border:0px; ">
				<span style="color:#FF0000">
				<?php if ($this->_tpl_vars['q'] == ""): ?>
					K&eacute;rem &iacute;rjon be legal&aacute;bb egy kifejez&eacute;st ami ut&aacute;n keresni szeretne.
				<?php else: ?>
					A keres&eacute;si krit&eacute;riumnak nem felelt meg egy h&iacute;r sem!<br />
					K&eacute;rem b&#337;v&iacute;tse a keres&eacute;st!
				<?php endif; ?>
				</span>
			</li>	
			<?php endif; ?>
		</ul>
	</div>


<table border="0" cellspacing="4" cellpadding="2" align="center" class="paging">
  <tr>		
	<td><?php if ($this->_tpl_vars['__GT']['current'] != 1 && $this->_tpl_vars['__GT']['total_pages'] != 0): ?><a href="search.php?<?php echo $this->_tpl_vars['url']; ?>
&page=1&plimit=<?php echo $this->_tpl_vars['__GT']['limit']; ?>
" target="_self">Els&#337;</a><?php endif; ?></td>
	<td><?php if ($this->_tpl_vars['__GT']['current'] != 1 && $this->_tpl_vars['__GT']['total_pages'] != 0): ?><a href="search.php?<?php echo $this->_tpl_vars['url']; ?>
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
						<a href="search.php?<?php echo $this->_tpl_vars['url']; ?>
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
	<td><?php if ($this->_tpl_vars['__GT']['current'] != $this->_tpl_vars['__GT']['total_pages'] && $this->_tpl_vars['__GT']['total_pages'] != 0): ?><a href="search.php?<?php echo $this->_tpl_vars['url']; ?>
&page=<?php echo $this->_tpl_vars['__GT']['next']['link']; ?>
&plimit=<?php echo $this->_tpl_vars['__GT']['limit']; ?>
" target="_self">K&ouml;vetkez&#337;</a><?php endif; ?></td>
	<td><?php if ($this->_tpl_vars['__GT']['current'] != $this->_tpl_vars['__GT']['total_pages'] && $this->_tpl_vars['__GT']['total_pages'] != 0): ?><a href="search.php?<?php echo $this->_tpl_vars['url']; ?>
&page=<?php echo $this->_tpl_vars['__GT']['total_pages']; ?>
&plimit=<?php echo $this->_tpl_vars['__GT']['limit']; ?>
" target="_self">Utols&oacute;</a><?php endif; ?></td>
  </tr>
  
</table>
</div>
</body>
</html>