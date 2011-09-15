<?php /* Smarty version 2.6.6, created on 2009-12-21 13:32:23
         compiled from fixed_categories_box.html */ ?>
<div>
	<div>		
		<div style="float:right"><a href="#">Szerk</a> <img src="../i/close.gif" align="absmiddle" /></div>
		<h2><?php echo $this->_tpl_vars['cat_name']; ?>
</h2>
	</div>
	<div class="edit">
		<table border="0" cellspacing="0" cellpadding="4">
		  <tr>
			<td><b>N&eacute;v:</b></td>
			<td><input type="text" value="<?php echo $this->_tpl_vars['cat_name']; ?>
" /></td>
		  </tr>
		  <tr>
			<td><b>Favicon:</b></td>
			<td><input type="text" value="<?php echo $this->_tpl_vars['cat_favicon']; ?>
" /></td>
		  </tr>
		  <tr>
			<td><b>T&iacute;pus:</b></td>
			<td>
				<select>
					<?php if ($this->_tpl_vars['cat_type'] == 1): ?>
					<option value="1" selected>RSS</option>
					<option value="3">több RSS</option>
					<option value="2">HTML + Link</option>
					<option value="5">Kulcsszó</option>
					<?php elseif ($this->_tpl_vars['cat_type'] == 2): ?>
					<option value="1">RSS</option>
					<option value="3">több RSS</option>
					<option value="2" selected>HTML + Link</option>	
					<option value="5">Kulcsszó</option>
					<?php elseif ($this->_tpl_vars['cat_type'] == 3): ?>
					<option value="1">RSS</option>
					<option value="3" selected>több RSS</option>
					<option value="2">HTML + Link</option>	
					<option value="5">Kulcsszó</option>
					<?php elseif ($this->_tpl_vars['cat_type'] == 5): ?>
					<option value="1">RSS</option>
					<option value="3">több RSS</option>
					<option value="2">HTML + Link</option>	
					<option value="5" selected>Kulcsszó</option>
					<?php endif; ?>
				</select>
			</td>
		  </tr>
		  <tr>
			<td colspan="2"><input type="button" value="V&eacute;grehajt" style="width:auto; " /></td>
		  </tr>
		</table>
	</div>
	<div class="content">		
		<div class="rss"<?php if ($this->_tpl_vars['cat_type'] == 1): ?> style="display:block"<?php endif; ?>>RSS: <input type="text" value="<?php echo $this->_tpl_vars['cat_rss']; ?>
" /> <input type="button" value="Elment" class="button"  style="width:auto;" /></div>
		<div class="htmllink"<?php if ($this->_tpl_vars['cat_type'] == 2): ?> style="display:block;"<?php endif; ?>>
			HTML:<br />
			<div style="border:1px solid #CCCCCC;padding:2px;"><?php echo $this->_tpl_vars['cat_html']; ?>
</div>
			<div style="float:right"><a href="#">HTML Szerkeszt&eacute;se</a></div>
		</div>
		<div class="link_list"<?php if ($this->_tpl_vars['cat_type'] == 2): ?> style="display:block;"<?php endif; ?>>
			Linkek:<br />
			<ul>
				<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['links']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
					<div style="float:right"><a href="#" onClick="editLink(this, '<?php echo $this->_tpl_vars['links'][$this->_sections['i']['index']]['link_id']; ?>
', '<?php echo $this->_tpl_vars['cat_id']; ?>
');return false;">Szerk</a> <img src="../i/close.gif" align="absmiddle" onClick="removeLink(this, '<?php echo $this->_tpl_vars['links'][$this->_sections['i']['index']]['link_id']; ?>
', '<?php echo $this->_tpl_vars['cat_id']; ?>
');" /></div>
					<a href="<?php echo $this->_tpl_vars['links'][$this->_sections['i']['index']]['link_url']; ?>
" title="<?php echo $this->_tpl_vars['links'][$this->_sections['i']['index']]['link_title']; ?>
"><?php echo $this->_tpl_vars['links'][$this->_sections['i']['index']]['link_name']; ?>
</a>										
				</li>
				<?php endfor; endif; ?>
			</ul>
		</div>
        <div class="rss"<?php if ($this->_tpl_vars['cat_type'] == 3): ?> style="display:block"<?php endif; ?>>
            Hírek száma hírfolyamonként: 
            <select style="width:50px;">
                <?php if (count($_from = (array)$this->_tpl_vars['newsc'])):
    foreach ($_from as $this->_tpl_vars['c']):
?> 
                <option value="<?php echo $this->_tpl_vars['c']; ?>
" <?php if ($this->_tpl_vars['c'] == $this->_tpl_vars['cat_newsperfeed']): ?>selected<?php endif; ?> ><?php echo $this->_tpl_vars['c']; ?>
</option>
                <?php endforeach; unset($_from); endif; ?>
            </select>
            <br>Hírfolyamok:
            <?php if (count($_from = (array)$this->_tpl_vars['multi_rss'])):
    foreach ($_from as $this->_tpl_vars['idx'] => $this->_tpl_vars['rss']):
?>
            <br>Cím: <input type="text" value="<?php echo $this->_tpl_vars['multi_titles'][$this->_tpl_vars['idx']]; ?>
" /> 
            <br>RSS: <input style="width:260px;" type="text" value="<?php echo $this->_tpl_vars['multi_rss'][$this->_tpl_vars['idx']]; ?>
" /> 
            <?php endforeach; unset($_from); endif; ?>
            <br><input type="button" value="Elment" class="button"  style="width:auto;" />
        </div>
        <div class="rss"<?php if ($this->_tpl_vars['cat_type'] == 5): ?> style="display:block"<?php endif; ?>>
			Kulcsszó:
            <br><input style="width:200px;" type="text" value="<?php echo $this->_tpl_vars['cat_keyword']; ?>
" /> 
            <br><input type="button" value="Elment" class="button"  style="width:auto;" />
        </div>

		<div style="clear:both"></div>		
	</div>
	<div class="links">
		<h2<?php if ($this->_tpl_vars['cat_type'] == 2): ?> style="display:block;"<?php endif; ?>><a href="#">&Uacute;j link</a> | <a href="#">Link kereso</a></h2>
		<div class="newlink">
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "add_edit_external_link.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		</div>
		<div class="searchlinks">
			<table border="0" cellspacing="0" cellpadding="4">
			  <tr>
				<td>
					N&eacute;v:<br />
					<input type="text" />
				</td>
			  </tr>
			  <tr>
				<td>
					Tal&aacute;latok:<br />
					<select name="" multiple size="10"></select>
				</td>
			  </tr>			  
			</table>
		</div>
	</div>
</div>		