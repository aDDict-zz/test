<?php /* Smarty version 2.6.6, created on 2011-04-29 15:00:18
         compiled from page.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'page.html', 34, false),array('modifier', 'escape', 'page.html', 41, false),array('modifier', 'count', 'page.html', 82, false),array('function', 'counter', 'page.html', 62, false),)), $this); ?>
<script>
var fCat = new Array();
var fCatFeedTypes = new Array();
<?php if ($this->_tpl_vars['fixed_categories'][0][0]['cat']['cat_type'] == 1 || $this->_tpl_vars['fixed_categories'][0][0]['cat']['cat_type'] == 3): ?>
	fCat['<?php echo $this->_tpl_vars['fixed_categories'][0][0]['id']; ?>
'] = '<?php echo $this->_tpl_vars['fixed_categories'][0][0]['cat']['cat_rss']; ?>
';
<?php endif;  if ($this->_tpl_vars['fixed_categories'][0][0]['cat']['feed_type'] == 4): ?>
    fCatFeedTypes['<?php echo $this->_tpl_vars['fixed_categories'][0][0]['id']; ?>
'] = '<?php echo $this->_tpl_vars['fixed_categories'][0][0]['cat']['feed_type']; ?>
';
<?php endif;  if ($this->_tpl_vars['fixed_categories'][0][1]['cat']['cat_type'] == 1 || $this->_tpl_vars['fixed_categories'][0][1]['cat']['cat_type'] == 3): ?>
	fCat['<?php echo $this->_tpl_vars['fixed_categories'][0][1]['id']; ?>
'] = '<?php echo $this->_tpl_vars['fixed_categories'][0][1]['cat']['cat_rss']; ?>
';
<?php endif;  if ($this->_tpl_vars['fixed_categories'][0][2]['cat']['cat_type'] == 1 || $this->_tpl_vars['fixed_categories'][0][2]['cat']['cat_type'] == 3): ?>
	fCat['<?php echo $this->_tpl_vars['fixed_categories'][0][2]['id']; ?>
'] = '<?php echo $this->_tpl_vars['fixed_categories'][0][2]['cat']['cat_rss']; ?>
';
<?php endif;  if ($this->_tpl_vars['fixed_categories'][1][0]['cat']['cat_type'] == 1 || $this->_tpl_vars['fixed_categories'][1][0]['cat']['cat_type'] == 3): ?>
	fCat['<?php echo $this->_tpl_vars['fixed_categories'][1][0]['id']; ?>
'] = '<?php echo $this->_tpl_vars['fixed_categories'][1][0]['cat']['cat_rss']; ?>
';
<?php endif; ?>

<?php if ($this->_tpl_vars['fixed_categories'][1][1]['cat']['cat_type'] == 1 || $this->_tpl_vars['fixed_categories'][1][1]['cat']['cat_type'] == 3): ?>
	fCat['<?php echo $this->_tpl_vars['fixed_categories'][1][1]['id']; ?>
'] = '<?php echo $this->_tpl_vars['fixed_categories'][1][1]['cat']['cat_rss']; ?>
';
<?php endif;  if ($this->_tpl_vars['fixed_categories'][1][2]['cat']['cat_type'] == 1 || $this->_tpl_vars['fixed_categories'][1][2]['cat']['cat_type'] == 3): ?>
	fCat['<?php echo $this->_tpl_vars['fixed_categories'][1][2]['id']; ?>
'] = '<?php echo $this->_tpl_vars['fixed_categories'][1][2]['cat']['cat_rss']; ?>
';
<?php endif;  if ($this->_tpl_vars['fixed_categories'][1][3]['cat']['cat_type'] == 1 || $this->_tpl_vars['fixed_categories'][1][3]['cat']['cat_type'] == 3): ?>
	fCat['<?php echo $this->_tpl_vars['fixed_categories'][1][3]['id']; ?>
'] = '<?php echo $this->_tpl_vars['fixed_categories'][1][3]['cat']['cat_rss']; ?>
';
<?php endif;  if ($this->_tpl_vars['fixed_categories'][1][4]['cat']['cat_type'] == 1 || $this->_tpl_vars['fixed_categories'][1][4]['cat']['cat_type'] == 3): ?>
	fCat['<?php echo $this->_tpl_vars['fixed_categories'][1][4]['id']; ?>
'] = '<?php echo $this->_tpl_vars['fixed_categories'][1][4]['cat']['cat_rss']; ?>
';
<?php endif; ?>


var maxBoxID = <?php echo ((is_array($_tmp=@$this->_tpl_vars['max_box_id'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
;
var components = new Array();
<?php if (count($_from = (array)$this->_tpl_vars['struc'])):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['col']):
?>
	var box = new Array();
    <?php if (count($_from = (array)$this->_tpl_vars['col'])):
    foreach ($_from as $this->_tpl_vars['j'] => $this->_tpl_vars['cell']):
?>
		box[<?php echo $this->_tpl_vars['j']; ?>
] = <?php echo '{'; ?>

			'component' : 'comp_<?php echo $this->_tpl_vars['page_prefix'];  echo $this->_tpl_vars['page_id']; ?>
_<?php echo $this->_tpl_vars['i']; ?>
_<?php echo $this->_tpl_vars['j']; ?>
',
			'title'     : '<?php echo ((is_array($_tmp=$this->_tpl_vars['cell']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
', 
			'feed'      : '<?php echo $this->_tpl_vars['cell']['feed_encoded']; ?>
',
            'feed_type' : '<?php echo $this->_tpl_vars['cell']['feed_type']; ?>
',
			'keywords'      : '<?php echo $this->_tpl_vars['cell']['keywords_encoded']; ?>
',
			'closed'    : '<?php echo $this->_tpl_vars['cell']['closed']; ?>
', 
			'items_nr'  : '<?php echo $this->_tpl_vars['cell']['items_nr']; ?>
', 
			'color'  : '<?php echo $this->_tpl_vars['cell']['color']; ?>
', 
			'type'  : '<?php if ($this->_tpl_vars['cell']['feed_type'] > 3):  echo $this->_tpl_vars['cell']['feed_type'];  else:  echo $this->_tpl_vars['cell']['type'];  endif; ?>',
			'editable' : '<?php echo $this->_tpl_vars['cell']['editable']; ?>
',
			'moveable' : '<?php echo $this->_tpl_vars['cell']['moveable']; ?>
', 
			'closeable' : '<?php echo $this->_tpl_vars['cell']['closeable']; ?>
',
            'owner' : '<?php echo $this->_tpl_vars['cell']['owner']; ?>
'
		<?php echo '}'; ?>
;
		
	<?php endforeach; unset($_from); endif; ?>		
	components[<?php echo $this->_tpl_vars['i']; ?>
] = box;
<?php endforeach; unset($_from); endif; ?>
</script>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" id="main_content" style="margin:5px 0 0 0;padding:0 0 5px 0;">
		  <tr>
			<?php echo smarty_function_counter(array('start' => -1,'skip' => 1,'print' => false,'assing' => $this->_tpl_vars['boxnr']), $this);?>

			<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['struc']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
				<?php if ($this->_sections['i']['index'] == 0): ?>		
					<td class="container" valign="top" width="33%">
				<?php elseif ($this->_sections['i']['index'] == 1): ?>
					<td class="container" valign="top" width="33%">
				<?php else: ?>
					<td class="container" valign="top" width="33%">
				<?php endif; ?>
						 <?php if ($this->_sections['i']['index'] == 1): ?>
						 	<?php if ($this->_tpl_vars['fixed_categories'][0][0] != ''): ?>
								<div class="box">
									<div class="blue">															
    <div class="topboxcont"><div class="bottombox"><div class="leftbox"><div class="rightbox"><div class="blbox"><div class="brbox"><div class="tlbox"><div class="trbox">
										<div class="head">											
											<h3><img src="<?php if ($this->_tpl_vars['fixed_categories'][0][0]['cat']['cat_favicon']):  echo $this->_tpl_vars['fixed_categories'][0][0]['cat']['cat_favicon'];  else: ?>i/nfi.gif<?php endif; ?>" style="margin-top:-5px;width:16px; height:16px;" align="absmiddle" /> <?php echo $this->_tpl_vars['fixed_categories'][0][0]['cat']['cat_name']; ?>
</h3>
										</div>										
										<div class="content_2" id="fc_<?php echo $this->_tpl_vars['page_prefix'];  echo $this->_tpl_vars['page_id']; ?>
_<?php echo $this->_tpl_vars['fixed_categories'][0][0]['id']; ?>
">
											<?php if ($this->_tpl_vars['fixed_categories'][0][0]['cat']['cat_type'] == 2): ?>
												<div style="padding:3px;"><?php echo $this->_tpl_vars['fixed_categories'][0][0]['cat']['cat_html']; ?>
</div>												
												<?php if ($this->_tpl_vars['fixed_categories'][0][0]['cat']['cat_links'] != '' && count($this->_tpl_vars['fixed_categories'][0][0]['cat']['cat_links'])): ?>
													<ul>
														<?php unset($this->_sections['k']);
$this->_sections['k']['name'] = 'k';
$this->_sections['k']['loop'] = is_array($_loop=$this->_tpl_vars['fixed_categories'][0][0]['cat']['cat_links']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['k']['show'] = true;
$this->_sections['k']['max'] = $this->_sections['k']['loop'];
$this->_sections['k']['step'] = 1;
$this->_sections['k']['start'] = $this->_sections['k']['step'] > 0 ? 0 : $this->_sections['k']['loop']-1;
if ($this->_sections['k']['show']) {
    $this->_sections['k']['total'] = $this->_sections['k']['loop'];
    if ($this->_sections['k']['total'] == 0)
        $this->_sections['k']['show'] = false;
} else
    $this->_sections['k']['total'] = 0;
if ($this->_sections['k']['show']):

            for ($this->_sections['k']['index'] = $this->_sections['k']['start'], $this->_sections['k']['iteration'] = 1;
                 $this->_sections['k']['iteration'] <= $this->_sections['k']['total'];
                 $this->_sections['k']['index'] += $this->_sections['k']['step'], $this->_sections['k']['iteration']++):
$this->_sections['k']['rownum'] = $this->_sections['k']['iteration'];
$this->_sections['k']['index_prev'] = $this->_sections['k']['index'] - $this->_sections['k']['step'];
$this->_sections['k']['index_next'] = $this->_sections['k']['index'] + $this->_sections['k']['step'];
$this->_sections['k']['first']      = ($this->_sections['k']['iteration'] == 1);
$this->_sections['k']['last']       = ($this->_sections['k']['iteration'] == $this->_sections['k']['total']);
?>
															<li><a href="http://<?php echo $this->_tpl_vars['fixed_categories'][0][0]['cat']['cat_links'][$this->_sections['k']['index']]['link_url']; ?>
" title="<?php echo $this->_tpl_vars['fixed_categories'][0][0]['cat']['cat_links'][$this->_sections['k']['index']]['link_title']; ?>
"><?php echo $this->_tpl_vars['fixed_categories'][0][0]['cat']['cat_links'][$this->_sections['k']['index']]['link_name']; ?>
</a></li>
														<?php endfor; endif; ?>
													</ul>
												<?php endif; ?>
											<?php elseif ($this->_tpl_vars['fixed_categories'][0][0]['cat']['cat_type'] == 1 || $this->_tpl_vars['fixed_categories'][0][0]['cat']['cat_type'] == 3): ?>	
												Bet&ouml;lt&eacute;s alatt...
											<?php endif; ?>
											
										</div>
    </div></div></div></div></div></div></div></div>
									</div>
								</div>
							<?php endif; ?>
						 	<?php if ($this->_tpl_vars['fixed_categories'][0][1] != ''): ?>
								<div class="box">
									<div class="blue">															
    <div class="topboxcont"><div class="bottombox"><div class="leftbox"><div class="rightbox"><div class="blbox"><div class="brbox"><div class="tlbox"><div class="trbox">
										<div class="head">											
											<h3><img src="<?php if ($this->_tpl_vars['fixed_categories'][0][1]['cat']['cat_favicon']):  echo $this->_tpl_vars['fixed_categories'][0][1]['cat']['cat_favicon'];  else: ?>i/nfi.gif<?php endif; ?>" style="margin-top:-5px;width:16px; height:16px;" align="absmiddle" /> <?php echo $this->_tpl_vars['fixed_categories'][0][1]['cat']['cat_name']; ?>
</h3>
										</div>										
										<div class="content_2" id="fc_<?php echo $this->_tpl_vars['page_prefix'];  echo $this->_tpl_vars['page_id']; ?>
_<?php echo $this->_tpl_vars['fixed_categories'][0][1]['id']; ?>
">
											<?php if ($this->_tpl_vars['fixed_categories'][0][1]['cat']['cat_type'] == 2): ?>
												<div style="padding:3px;"><?php echo $this->_tpl_vars['fixed_categories'][0][1]['cat']['cat_html']; ?>
</div>												
												<?php if ($this->_tpl_vars['fixed_categories'][0][1]['cat']['cat_links'] != '' && count($this->_tpl_vars['fixed_categories'][0][1]['cat']['cat_links'])): ?>
													<ul>
														<?php unset($this->_sections['k']);
$this->_sections['k']['name'] = 'k';
$this->_sections['k']['loop'] = is_array($_loop=$this->_tpl_vars['fixed_categories'][0][1]['cat']['cat_links']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['k']['show'] = true;
$this->_sections['k']['max'] = $this->_sections['k']['loop'];
$this->_sections['k']['step'] = 1;
$this->_sections['k']['start'] = $this->_sections['k']['step'] > 0 ? 0 : $this->_sections['k']['loop']-1;
if ($this->_sections['k']['show']) {
    $this->_sections['k']['total'] = $this->_sections['k']['loop'];
    if ($this->_sections['k']['total'] == 0)
        $this->_sections['k']['show'] = false;
} else
    $this->_sections['k']['total'] = 0;
if ($this->_sections['k']['show']):

            for ($this->_sections['k']['index'] = $this->_sections['k']['start'], $this->_sections['k']['iteration'] = 1;
                 $this->_sections['k']['iteration'] <= $this->_sections['k']['total'];
                 $this->_sections['k']['index'] += $this->_sections['k']['step'], $this->_sections['k']['iteration']++):
$this->_sections['k']['rownum'] = $this->_sections['k']['iteration'];
$this->_sections['k']['index_prev'] = $this->_sections['k']['index'] - $this->_sections['k']['step'];
$this->_sections['k']['index_next'] = $this->_sections['k']['index'] + $this->_sections['k']['step'];
$this->_sections['k']['first']      = ($this->_sections['k']['iteration'] == 1);
$this->_sections['k']['last']       = ($this->_sections['k']['iteration'] == $this->_sections['k']['total']);
?>
															<li><a href="http://<?php echo $this->_tpl_vars['fixed_categories'][0][1]['cat']['cat_links'][$this->_sections['k']['index']]['link_url']; ?>
" title="<?php echo $this->_tpl_vars['fixed_categories'][0][1]['cat']['cat_links'][$this->_sections['k']['index']]['link_title']; ?>
"><?php echo $this->_tpl_vars['fixed_categories'][0][1]['cat']['cat_links'][$this->_sections['k']['index']]['link_name']; ?>
</a></li>
														<?php endfor; endif; ?>
													</ul>
												<?php endif; ?>
											<?php elseif ($this->_tpl_vars['fixed_categories'][0][1]['cat']['cat_type'] == 1 || $this->_tpl_vars['fixed_categories'][0][1]['cat']['cat_type'] == 3): ?>	
												Bet&ouml;lt&eacute;s alatt...
											<?php endif; ?>
											
										</div>
    </div></div></div></div></div></div></div></div>
									</div>
								</div>
							<?php endif; ?>
						 	<?php if ($this->_tpl_vars['fixed_categories'][0][2] != ''): ?>
								<div class="box">
									<div class="blue">															
    <div class="topboxcont"><div class="bottombox"><div class="leftbox"><div class="rightbox"><div class="blbox"><div class="brbox"><div class="tlbox"><div class="trbox">
										<div class="head">											
											<h3><img src="<?php if ($this->_tpl_vars['fixed_categories'][0][2]['cat']['cat_favicon']):  echo $this->_tpl_vars['fixed_categories'][0][2]['cat']['cat_favicon'];  else: ?>i/nfi.gif<?php endif; ?>" style="margin-top:-5px;width:16px; height:16px;" align="absmiddle" /> <?php echo $this->_tpl_vars['fixed_categories'][0][2]['cat']['cat_name']; ?>
</h3>
										</div>										
										<div class="content_2" id="fc_<?php echo $this->_tpl_vars['page_prefix'];  echo $this->_tpl_vars['page_id']; ?>
_<?php echo $this->_tpl_vars['fixed_categories'][0][2]['id']; ?>
">
											<?php if ($this->_tpl_vars['fixed_categories'][0][2]['cat']['cat_type'] == 2): ?>
												<div style="padding:3px;"><?php echo $this->_tpl_vars['fixed_categories'][0][2]['cat']['cat_html']; ?>
</div>												
												<?php if ($this->_tpl_vars['fixed_categories'][0][2]['cat']['cat_links'] != '' && count($this->_tpl_vars['fixed_categories'][0][2]['cat']['cat_links'])): ?>
													<ul>
														<?php unset($this->_sections['k']);
$this->_sections['k']['name'] = 'k';
$this->_sections['k']['loop'] = is_array($_loop=$this->_tpl_vars['fixed_categories'][0][2]['cat']['cat_links']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['k']['show'] = true;
$this->_sections['k']['max'] = $this->_sections['k']['loop'];
$this->_sections['k']['step'] = 1;
$this->_sections['k']['start'] = $this->_sections['k']['step'] > 0 ? 0 : $this->_sections['k']['loop']-1;
if ($this->_sections['k']['show']) {
    $this->_sections['k']['total'] = $this->_sections['k']['loop'];
    if ($this->_sections['k']['total'] == 0)
        $this->_sections['k']['show'] = false;
} else
    $this->_sections['k']['total'] = 0;
if ($this->_sections['k']['show']):

            for ($this->_sections['k']['index'] = $this->_sections['k']['start'], $this->_sections['k']['iteration'] = 1;
                 $this->_sections['k']['iteration'] <= $this->_sections['k']['total'];
                 $this->_sections['k']['index'] += $this->_sections['k']['step'], $this->_sections['k']['iteration']++):
$this->_sections['k']['rownum'] = $this->_sections['k']['iteration'];
$this->_sections['k']['index_prev'] = $this->_sections['k']['index'] - $this->_sections['k']['step'];
$this->_sections['k']['index_next'] = $this->_sections['k']['index'] + $this->_sections['k']['step'];
$this->_sections['k']['first']      = ($this->_sections['k']['iteration'] == 1);
$this->_sections['k']['last']       = ($this->_sections['k']['iteration'] == $this->_sections['k']['total']);
?>
															<li><a href="http://<?php echo $this->_tpl_vars['fixed_categories'][0][2]['cat']['cat_links'][$this->_sections['k']['index']]['link_url']; ?>
" title="<?php echo $this->_tpl_vars['fixed_categories'][0][2]['cat']['cat_links'][$this->_sections['k']['index']]['link_title']; ?>
"><?php echo $this->_tpl_vars['fixed_categories'][0][2]['cat']['cat_links'][$this->_sections['k']['index']]['link_name']; ?>
</a></li>
														<?php endfor; endif; ?>
													</ul>
												<?php endif; ?>
											<?php elseif ($this->_tpl_vars['fixed_categories'][0][2]['cat']['cat_type'] == 1 || $this->_tpl_vars['fixed_categories'][0][2]['cat']['cat_type'] == 3): ?>	
												Bet&ouml;lt&eacute;s alatt...
											<?php endif; ?>
											
										</div>
    </div></div></div></div></div></div></div></div>
									</div>
								</div>
							<?php endif; ?>
						 <?php elseif ($this->_sections['i']['index'] == 2): ?>
						 	<?php if ($this->_tpl_vars['fixed_categories'][1][0] != ''): ?>
								<div class="box">
									<div class="blue">															
    <div class="topboxcont"><div class="bottombox"><div class="leftbox"><div class="rightbox"><div class="blbox"><div class="brbox"><div class="tlbox"><div class="trbox">
										<div class="head">											
											<h3><img src="<?php if ($this->_tpl_vars['fixed_categories'][1][0]['cat']['cat_favicon']):  echo $this->_tpl_vars['fixed_categories'][1][0]['cat']['cat_favicon'];  else: ?>i/nfi.gif<?php endif; ?>" style="margin-top:-5px;width:16px; height:16px;" align="absmiddle" /> <?php echo $this->_tpl_vars['fixed_categories'][1][0]['cat']['cat_name']; ?>
</h3>
										</div>										
										<div class="content_2" id="fc_<?php echo $this->_tpl_vars['page_prefix'];  echo $this->_tpl_vars['page_id']; ?>
_<?php echo $this->_tpl_vars['fixed_categories'][1][0]['id']; ?>
">
											<?php if ($this->_tpl_vars['fixed_categories'][1][0]['cat']['cat_type'] == 2): ?>
												<div style="padding:3px;"><?php echo $this->_tpl_vars['fixed_categories'][1][0]['cat']['cat_html']; ?>
</div>											
												<?php if ($this->_tpl_vars['fixed_categories'][1][0]['cat']['cat_links'] != '' && count($this->_tpl_vars['fixed_categories'][1][0]['cat']['cat_links'])): ?>
													<ul>
														<?php unset($this->_sections['k']);
$this->_sections['k']['name'] = 'k';
$this->_sections['k']['loop'] = is_array($_loop=$this->_tpl_vars['fixed_categories'][1][0]['cat']['cat_links']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['k']['show'] = true;
$this->_sections['k']['max'] = $this->_sections['k']['loop'];
$this->_sections['k']['step'] = 1;
$this->_sections['k']['start'] = $this->_sections['k']['step'] > 0 ? 0 : $this->_sections['k']['loop']-1;
if ($this->_sections['k']['show']) {
    $this->_sections['k']['total'] = $this->_sections['k']['loop'];
    if ($this->_sections['k']['total'] == 0)
        $this->_sections['k']['show'] = false;
} else
    $this->_sections['k']['total'] = 0;
if ($this->_sections['k']['show']):

            for ($this->_sections['k']['index'] = $this->_sections['k']['start'], $this->_sections['k']['iteration'] = 1;
                 $this->_sections['k']['iteration'] <= $this->_sections['k']['total'];
                 $this->_sections['k']['index'] += $this->_sections['k']['step'], $this->_sections['k']['iteration']++):
$this->_sections['k']['rownum'] = $this->_sections['k']['iteration'];
$this->_sections['k']['index_prev'] = $this->_sections['k']['index'] - $this->_sections['k']['step'];
$this->_sections['k']['index_next'] = $this->_sections['k']['index'] + $this->_sections['k']['step'];
$this->_sections['k']['first']      = ($this->_sections['k']['iteration'] == 1);
$this->_sections['k']['last']       = ($this->_sections['k']['iteration'] == $this->_sections['k']['total']);
?>
															<li><a href="http://<?php echo $this->_tpl_vars['fixed_categories'][1][0]['cat']['cat_links'][$this->_sections['k']['index']]['link_url']; ?>
" title="<?php echo $this->_tpl_vars['fixed_categories'][1][0]['cat']['cat_links'][$this->_sections['k']['index']]['link_title']; ?>
"><?php echo $this->_tpl_vars['fixed_categories'][1][0]['cat']['cat_links'][$this->_sections['k']['index']]['link_name']; ?>
</a></li>
														<?php endfor; endif; ?>
													</ul>
												<?php endif; ?>
											<?php elseif ($this->_tpl_vars['fixed_categories'][1][0]['cat']['cat_type'] == 1 || $this->_tpl_vars['fixed_categories'][1][0]['cat']['cat_type'] == 3): ?>	
												Bet&ouml;lt&eacute;s alatt...	
											<?php endif; ?>
										</div>
    </div></div></div></div></div></div></div></div>
									</div>
								</div>
							<?php endif; ?>
							
							<?php if ($this->_tpl_vars['fixed_categories'][1][1]['cat']['cat_name'] != ''): ?>								
								<div class="box">
									<div class="blue">															
    <div class="topboxcont"><div class="bottombox"><div class="leftbox"><div class="rightbox"><div class="blbox"><div class="brbox"><div class="tlbox"><div class="trbox">
										<div class="head">											
											<h3><img src="<?php if ($this->_tpl_vars['fixed_categories'][1][1]['cat']['cat_favicon']):  echo $this->_tpl_vars['fixed_categories'][1][1]['cat']['cat_favicon'];  else: ?>i/nfi.gif<?php endif; ?>" style="margin-top:-5px;width:16px; height:16px;" align="absmiddle" /> <?php echo $this->_tpl_vars['fixed_categories'][1][1]['cat']['cat_name']; ?>
</h3>
										</div>										
										<div class="content_2" id="fc_<?php echo $this->_tpl_vars['page_prefix'];  echo $this->_tpl_vars['page_id']; ?>
_<?php echo $this->_tpl_vars['fixed_categories'][1][1]['id']; ?>
">
											<?php if ($this->_tpl_vars['fixed_categories'][1][1]['cat']['cat_type'] == 2): ?>
												<div style="padding:3px;"><?php echo $this->_tpl_vars['fixed_categories'][1][1]['cat']['cat_html']; ?>
</div>												
												<?php if ($this->_tpl_vars['fixed_categories'][1][1]['cat']['cat_links'] != '' && count($this->_tpl_vars['fixed_categories'][1][1]['cat']['cat_links'])): ?>
													<ul>
														<?php unset($this->_sections['k']);
$this->_sections['k']['name'] = 'k';
$this->_sections['k']['loop'] = is_array($_loop=$this->_tpl_vars['fixed_categories'][1][1]['cat']['cat_links']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['k']['show'] = true;
$this->_sections['k']['max'] = $this->_sections['k']['loop'];
$this->_sections['k']['step'] = 1;
$this->_sections['k']['start'] = $this->_sections['k']['step'] > 0 ? 0 : $this->_sections['k']['loop']-1;
if ($this->_sections['k']['show']) {
    $this->_sections['k']['total'] = $this->_sections['k']['loop'];
    if ($this->_sections['k']['total'] == 0)
        $this->_sections['k']['show'] = false;
} else
    $this->_sections['k']['total'] = 0;
if ($this->_sections['k']['show']):

            for ($this->_sections['k']['index'] = $this->_sections['k']['start'], $this->_sections['k']['iteration'] = 1;
                 $this->_sections['k']['iteration'] <= $this->_sections['k']['total'];
                 $this->_sections['k']['index'] += $this->_sections['k']['step'], $this->_sections['k']['iteration']++):
$this->_sections['k']['rownum'] = $this->_sections['k']['iteration'];
$this->_sections['k']['index_prev'] = $this->_sections['k']['index'] - $this->_sections['k']['step'];
$this->_sections['k']['index_next'] = $this->_sections['k']['index'] + $this->_sections['k']['step'];
$this->_sections['k']['first']      = ($this->_sections['k']['iteration'] == 1);
$this->_sections['k']['last']       = ($this->_sections['k']['iteration'] == $this->_sections['k']['total']);
?>
															<li><a href="http://<?php echo $this->_tpl_vars['fixed_categories'][1][1]['cat']['cat_links'][$this->_sections['k']['index']]['link_url']; ?>
" title="<?php echo $this->_tpl_vars['fixed_categories'][1][1]['cat']['cat_links'][$this->_sections['k']['index']]['link_title']; ?>
"><?php echo $this->_tpl_vars['fixed_categories'][1][1]['cat']['cat_links'][$this->_sections['k']['index']]['link_name']; ?>
</a></li>
														<?php endfor; endif; ?>
													</ul>
												<?php endif; ?>
											<?php elseif ($this->_tpl_vars['fixed_categories'][1][1]['cat']['cat_type'] == 1 || $this->_tpl_vars['fixed_categories'][1][1]['cat']['cat_type'] == 3): ?>	
												Bet&ouml;lt&eacute;s alatt...	
											<?php endif; ?>											
										</div>
    </div></div></div></div></div></div></div></div>
									</div>
								</div>
							<?php endif; ?>
							<?php if ($this->_tpl_vars['fixed_categories'][1][2]['cat']['cat_name'] != ''): ?>								
								<div class="box">
									<div class="blue">															
    <div class="topboxcont"><div class="bottombox"><div class="leftbox"><div class="rightbox"><div class="blbox"><div class="brbox"><div class="tlbox"><div class="trbox">
										<div class="head">											
											<h3><img src="<?php if ($this->_tpl_vars['fixed_categories'][1][2]['cat']['cat_favicon']):  echo $this->_tpl_vars['fixed_categories'][1][2]['cat']['cat_favicon'];  else: ?>i/nfi.gif<?php endif; ?>" style="margin-top:-5px;width:16px; height:16px;" align="absmiddle" /> <?php echo $this->_tpl_vars['fixed_categories'][1][2]['cat']['cat_name']; ?>
</h3>
										</div>										
										<div class="content_2" id="fc_<?php echo $this->_tpl_vars['page_prefix'];  echo $this->_tpl_vars['page_id']; ?>
_<?php echo $this->_tpl_vars['fixed_categories'][1][2]['id']; ?>
">
											<?php if ($this->_tpl_vars['fixed_categories'][1][2]['cat']['cat_type'] == 2): ?>
												<div style="padding:3px;"><?php echo $this->_tpl_vars['fixed_categories'][1][2]['cat']['cat_html']; ?>
</div>												
												<?php if ($this->_tpl_vars['fixed_categories'][1][2]['cat']['cat_links'] != '' && count($this->_tpl_vars['fixed_categories'][1][2]['cat']['cat_links'])): ?>
													<ul>
														<?php unset($this->_sections['k']);
$this->_sections['k']['name'] = 'k';
$this->_sections['k']['loop'] = is_array($_loop=$this->_tpl_vars['fixed_categories'][1][2]['cat']['cat_links']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['k']['show'] = true;
$this->_sections['k']['max'] = $this->_sections['k']['loop'];
$this->_sections['k']['step'] = 1;
$this->_sections['k']['start'] = $this->_sections['k']['step'] > 0 ? 0 : $this->_sections['k']['loop']-1;
if ($this->_sections['k']['show']) {
    $this->_sections['k']['total'] = $this->_sections['k']['loop'];
    if ($this->_sections['k']['total'] == 0)
        $this->_sections['k']['show'] = false;
} else
    $this->_sections['k']['total'] = 0;
if ($this->_sections['k']['show']):

            for ($this->_sections['k']['index'] = $this->_sections['k']['start'], $this->_sections['k']['iteration'] = 1;
                 $this->_sections['k']['iteration'] <= $this->_sections['k']['total'];
                 $this->_sections['k']['index'] += $this->_sections['k']['step'], $this->_sections['k']['iteration']++):
$this->_sections['k']['rownum'] = $this->_sections['k']['iteration'];
$this->_sections['k']['index_prev'] = $this->_sections['k']['index'] - $this->_sections['k']['step'];
$this->_sections['k']['index_next'] = $this->_sections['k']['index'] + $this->_sections['k']['step'];
$this->_sections['k']['first']      = ($this->_sections['k']['iteration'] == 1);
$this->_sections['k']['last']       = ($this->_sections['k']['iteration'] == $this->_sections['k']['total']);
?>
															<li><a href="http://<?php echo $this->_tpl_vars['fixed_categories'][1][2]['cat']['cat_links'][$this->_sections['k']['index']]['link_url']; ?>
" title="<?php echo $this->_tpl_vars['fixed_categories'][1][2]['cat']['cat_links'][$this->_sections['k']['index']]['link_title']; ?>
"><?php echo $this->_tpl_vars['fixed_categories'][1][2]['cat']['cat_links'][$this->_sections['k']['index']]['link_name']; ?>
</a></li>
														<?php endfor; endif; ?>
													</ul>
												<?php endif; ?>
											<?php elseif ($this->_tpl_vars['fixed_categories'][1][2]['cat']['cat_type'] == 1 || $this->_tpl_vars['fixed_categories'][1][2]['cat']['cat_type'] == 3): ?>	
												Bet&ouml;lt&eacute;s alatt...	
											<?php endif; ?>											
										</div>
    </div></div></div></div></div></div></div></div>
									</div>
								</div>
							<?php endif; ?>
							<?php if ($this->_tpl_vars['fixed_categories'][1][3]['cat']['cat_name'] != ''): ?>								
								<div class="box">
									<div class="blue">															
    <div class="topboxcont"><div class="bottombox"><div class="leftbox"><div class="rightbox"><div class="blbox"><div class="brbox"><div class="tlbox"><div class="trbox">
										<div class="head">											
											<h3><img src="<?php if ($this->_tpl_vars['fixed_categories'][1][3]['cat']['cat_favicon']):  echo $this->_tpl_vars['fixed_categories'][1][3]['cat']['cat_favicon'];  else: ?>i/nfi.gif<?php endif; ?>" style="margin-top:-5px;width:16px; height:16px;" align="absmiddle" /> <?php echo $this->_tpl_vars['fixed_categories'][1][3]['cat']['cat_name']; ?>
</h3>
										</div>										
										<div class="content_2" id="fc_<?php echo $this->_tpl_vars['page_prefix'];  echo $this->_tpl_vars['page_id']; ?>
_<?php echo $this->_tpl_vars['fixed_categories'][1][3]['id']; ?>
">
											<?php if ($this->_tpl_vars['fixed_categories'][1][3]['cat']['cat_type'] == 2): ?>
												<div style="padding:3px;"><?php echo $this->_tpl_vars['fixed_categories'][1][3]['cat']['cat_html']; ?>
</div>												
												<?php if ($this->_tpl_vars['fixed_categories'][1][3]['cat']['cat_links'] != '' && count($this->_tpl_vars['fixed_categories'][1][3]['cat']['cat_links'])): ?>
													<ul>
														<?php unset($this->_sections['k']);
$this->_sections['k']['name'] = 'k';
$this->_sections['k']['loop'] = is_array($_loop=$this->_tpl_vars['fixed_categories'][1][3]['cat']['cat_links']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['k']['show'] = true;
$this->_sections['k']['max'] = $this->_sections['k']['loop'];
$this->_sections['k']['step'] = 1;
$this->_sections['k']['start'] = $this->_sections['k']['step'] > 0 ? 0 : $this->_sections['k']['loop']-1;
if ($this->_sections['k']['show']) {
    $this->_sections['k']['total'] = $this->_sections['k']['loop'];
    if ($this->_sections['k']['total'] == 0)
        $this->_sections['k']['show'] = false;
} else
    $this->_sections['k']['total'] = 0;
if ($this->_sections['k']['show']):

            for ($this->_sections['k']['index'] = $this->_sections['k']['start'], $this->_sections['k']['iteration'] = 1;
                 $this->_sections['k']['iteration'] <= $this->_sections['k']['total'];
                 $this->_sections['k']['index'] += $this->_sections['k']['step'], $this->_sections['k']['iteration']++):
$this->_sections['k']['rownum'] = $this->_sections['k']['iteration'];
$this->_sections['k']['index_prev'] = $this->_sections['k']['index'] - $this->_sections['k']['step'];
$this->_sections['k']['index_next'] = $this->_sections['k']['index'] + $this->_sections['k']['step'];
$this->_sections['k']['first']      = ($this->_sections['k']['iteration'] == 1);
$this->_sections['k']['last']       = ($this->_sections['k']['iteration'] == $this->_sections['k']['total']);
?>
															<li><a href="http://<?php echo $this->_tpl_vars['fixed_categories'][1][3]['cat']['cat_links'][$this->_sections['k']['index']]['link_url']; ?>
" title="<?php echo $this->_tpl_vars['fixed_categories'][1][3]['cat']['cat_links'][$this->_sections['k']['index']]['link_title']; ?>
"><?php echo $this->_tpl_vars['fixed_categories'][1][3]['cat']['cat_links'][$this->_sections['k']['index']]['link_name']; ?>
</a></li>
														<?php endfor; endif; ?>
													</ul>
												<?php endif; ?>
											<?php elseif ($this->_tpl_vars['fixed_categories'][1][3]['cat']['cat_type'] == 1 || $this->_tpl_vars['fixed_categories'][1][3]['cat']['cat_type'] == 3): ?>	
												Bet&ouml;lt&eacute;s alatt...	
											<?php endif; ?>											
										</div>
    </div></div></div></div></div></div></div></div>
									</div>
								</div>
							<?php endif; ?>
							<?php if ($this->_tpl_vars['fixed_categories'][1][4]['cat']['cat_name'] != ''): ?>								
								<div class="box">
									<div class="blue">															
    <div class="topboxcont"><div class="bottombox"><div class="leftbox"><div class="rightbox"><div class="blbox"><div class="brbox"><div class="tlbox"><div class="trbox">
										<div class="head">											
											<h3><img src="<?php if ($this->_tpl_vars['fixed_categories'][1][4]['cat']['cat_favicon']):  echo $this->_tpl_vars['fixed_categories'][1][4]['cat']['cat_favicon'];  else: ?>i/nfi.gif<?php endif; ?>" style="margin-top:-5px;width:16px; height:16px;" align="absmiddle" /> <?php echo $this->_tpl_vars['fixed_categories'][1][4]['cat']['cat_name']; ?>
</h3>
										</div>										
										<div class="content_2" id="fc_<?php echo $this->_tpl_vars['page_prefix'];  echo $this->_tpl_vars['page_id']; ?>
_<?php echo $this->_tpl_vars['fixed_categories'][1][4]['id']; ?>
">
											<?php if ($this->_tpl_vars['fixed_categories'][1][4]['cat']['cat_type'] == 2): ?>
												<div style="padding:3px;"><?php echo $this->_tpl_vars['fixed_categories'][1][4]['cat']['cat_html']; ?>
</div>												
												<?php if ($this->_tpl_vars['fixed_categories'][1][4]['cat']['cat_links'] != '' && count($this->_tpl_vars['fixed_categories'][1][4]['cat']['cat_links'])): ?>
													<ul>
														<?php unset($this->_sections['k']);
$this->_sections['k']['name'] = 'k';
$this->_sections['k']['loop'] = is_array($_loop=$this->_tpl_vars['fixed_categories'][1][4]['cat']['cat_links']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['k']['show'] = true;
$this->_sections['k']['max'] = $this->_sections['k']['loop'];
$this->_sections['k']['step'] = 1;
$this->_sections['k']['start'] = $this->_sections['k']['step'] > 0 ? 0 : $this->_sections['k']['loop']-1;
if ($this->_sections['k']['show']) {
    $this->_sections['k']['total'] = $this->_sections['k']['loop'];
    if ($this->_sections['k']['total'] == 0)
        $this->_sections['k']['show'] = false;
} else
    $this->_sections['k']['total'] = 0;
if ($this->_sections['k']['show']):

            for ($this->_sections['k']['index'] = $this->_sections['k']['start'], $this->_sections['k']['iteration'] = 1;
                 $this->_sections['k']['iteration'] <= $this->_sections['k']['total'];
                 $this->_sections['k']['index'] += $this->_sections['k']['step'], $this->_sections['k']['iteration']++):
$this->_sections['k']['rownum'] = $this->_sections['k']['iteration'];
$this->_sections['k']['index_prev'] = $this->_sections['k']['index'] - $this->_sections['k']['step'];
$this->_sections['k']['index_next'] = $this->_sections['k']['index'] + $this->_sections['k']['step'];
$this->_sections['k']['first']      = ($this->_sections['k']['iteration'] == 1);
$this->_sections['k']['last']       = ($this->_sections['k']['iteration'] == $this->_sections['k']['total']);
?>
															<li><a href="http://<?php echo $this->_tpl_vars['fixed_categories'][1][4]['cat']['cat_links'][$this->_sections['k']['index']]['link_url']; ?>
" title="<?php echo $this->_tpl_vars['fixed_categories'][1][4]['cat']['cat_links'][$this->_sections['k']['index']]['link_title']; ?>
"><?php echo $this->_tpl_vars['fixed_categories'][1][4]['cat']['cat_links'][$this->_sections['k']['index']]['link_name']; ?>
</a></li>
														<?php endfor; endif; ?>
													</ul>
												<?php endif; ?>
											<?php elseif ($this->_tpl_vars['fixed_categories'][1][4]['cat']['cat_type'] == 1 || $this->_tpl_vars['fixed_categories'][1][4]['cat']['cat_type'] == 3): ?>	
												Bet&ouml;lt&eacute;s alatt...	
											<?php endif; ?>											
										</div>
    </div></div></div></div></div></div></div></div>
									</div>
								</div>
							<?php endif; ?>
						<?php endif; ?>
					<div id="<?php echo $this->_tpl_vars['page_prefix'];  echo $this->_tpl_vars['page_id']; ?>
_<?php echo smarty_function_counter(array(), $this);?>
">						
                    <?php if (count($_from = (array)$this->_tpl_vars['struc'][$this->_sections['i']['index']])):
    foreach ($_from as $this->_tpl_vars['j'] => $this->_tpl_vars['cell']):
?>						
						<?php if ($this->_tpl_vars['cell']['type'] == 1 || $this->_tpl_vars['cell']['type'] == 5): ?>
						<div class="box" id="comp_<?php echo $this->_tpl_vars['page_prefix'];  echo $this->_tpl_vars['page_id']; ?>
_<?php echo $this->_sections['i']['index']; ?>
_<?php echo $this->_tpl_vars['j']; ?>
" alt="<?php echo $this->_tpl_vars['cell']['bid']; ?>
">
							<div class="blue" id="rss_box_<?php echo $this->_tpl_vars['cell']['title']; ?>
">							
    <div class="topboxcont_<?php echo $this->_tpl_vars['cell']['color']; ?>
"><div class="bottombox_<?php echo $this->_tpl_vars['cell']['color']; ?>
"><div class="leftbox_<?php echo $this->_tpl_vars['cell']['color']; ?>
"><div class="rightbox_<?php echo $this->_tpl_vars['cell']['color']; ?>
"><div class="blbox_<?php echo $this->_tpl_vars['cell']['color']; ?>
"><div class="brbox_<?php echo $this->_tpl_vars['cell']['color']; ?>
"><div class="tlbox_<?php echo $this->_tpl_vars['cell']['color']; ?>
"><div class="trbox_<?php echo $this->_tpl_vars['cell']['color']; ?>
">
								<div class="head">
									<div class="edit"><a href="#" title="Hírek frissítése"><img align="top" src="i/refresh.gif" alt="Hírek frissítése"></a><?php if ($this->_tpl_vars['cell']['editable'] == 1): ?> <a title="Hírdoboz szerkesztése" href="#">Szerkeszt</a><?php else: ?> <a href="#"></a><?php endif;  if ($this->_tpl_vars['cell']['closeable'] == 1): ?><a href="#" title="H&iacute;rdoboz bez&aacute;r&aacute;sa"><img src="i/var.gif" align="top" alt="H&iacute;rdoboz bez&aacute;r&aacute;sa" /></a><a href="#" title="Hírdoboz törlése"><img src="i/close.gif" align="top" alt="Hírdoboz törlése"/></a><?php else:  endif; ?></div>
									<h3 ><a href="#"><img src="<?php if ($this->_tpl_vars['cell']['feed_type'] == 4): ?>i/in.gif<?php elseif ($this->_tpl_vars['cell']['feed_favicon'] != ''):  echo $this->_tpl_vars['cell']['feed_favicon'];  else: ?>i/rss.gif<?php endif; ?>" style="margin-top:-5px;width:16px; height:16px;" align="absmiddle" /></a> <a href="<?php echo $this->_tpl_vars['cell']['agency_url']; ?>
" target="_blank"><?php echo $this->_tpl_vars['cell']['title']; ?>
<!-- (<?php echo $this->_tpl_vars['cell']['bid']; ?>
)--></a></h3>
								</div>
								<div class="editContent">
									<table border="0" cellspacing="0" cellpadding="2">
									  <tr>
										<td>C&iacute;m:</td>
										<td><input type="text" name="title" <?php if ($this->_tpl_vars['cell']['owner'] == 1): ?>disabled<?php endif; ?> value="<?php echo ((is_array($_tmp=$this->_tpl_vars['cell']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
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
                                      <?php if ($this->_tpl_vars['cell']['feed_type'] == 4): ?>
										<td>Kulcssz&oacute;:</td>
										<td><input type="text" name="feed" <?php if ($this->_tpl_vars['cell']['owner'] == 1): ?>disabled<?php endif; ?> value="<?php echo $this->_tpl_vars['cell']['keywords']; ?>
" /></td>
                                      <?php else: ?>
										<td>Forr&aacute;s:</td>
										<td><input type="text" name="feed" <?php if ($this->_tpl_vars['cell']['owner'] == 1): ?>disabled<?php endif; ?> value="<?php echo $this->_tpl_vars['cell']['feed']; ?>
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
                                                <?php echo $this->_tpl_vars['items'][$this->_sections['l']['index']]; ?>

													<?php if ($this->_tpl_vars['items'][$this->_sections['l']['index']] == $this->_tpl_vars['cell']['items_nr']): ?>
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
								<?php if ($this->_tpl_vars['cell']['closed'] == '1'): ?>
								<div class="content_<?php echo $this->_tpl_vars['cell']['color']; ?>
" style="display:none; ">
								<?php else: ?>
								<div class="content_<?php echo $this->_tpl_vars['cell']['color']; ?>
">
								<?php endif; ?>
                                <?php if ($this->_tpl_vars['cell']['content']): ?>
                                    <?php echo $this->_tpl_vars['cell']['content']; ?>

                                <?php else: ?>
									Bet&ouml;lt&eacute;s alatt...
                                <?php endif; ?>
								</div>
    </div></div></div></div></div></div></div></div>
							</div>
						</div>
						<?php elseif ($this->_tpl_vars['cell']['type'] == 2): ?>						
						<div class="box" id="comp_<?php echo $this->_tpl_vars['page_prefix'];  echo $this->_tpl_vars['page_id']; ?>
_<?php echo $this->_sections['i']['index']; ?>
_<?php echo $this->_tpl_vars['j']; ?>
" alt="<?php echo $this->_tpl_vars['cell']['bid']; ?>
">
							<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "webnote.html", 'smarty_include_vars' => array('wn_id' => $this->_tpl_vars['cell']['rid'],'wn_bid' => $this->_tpl_vars['cell']['bid'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
						</div>
						<?php elseif ($this->_tpl_vars['cell']['type'] == 3): ?>
						<div class="box" id="comp_<?php echo $this->_tpl_vars['page_prefix'];  echo $this->_tpl_vars['page_id']; ?>
_<?php echo $this->_sections['i']['index']; ?>
_<?php echo $this->_tpl_vars['j']; ?>
" alt="<?php echo $this->_tpl_vars['cell']['bid']; ?>
">
							<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "search_box.html", 'smarty_include_vars' => array('sb_bid' => $this->_tpl_vars['cell']['bid'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
						</div>			
                        <?php endif; ?>
					<?php endforeach; unset($_from); endif; ?>
					</div>
					</td>
			<?php endfor; else: ?>			
				<td class="container" valign="top" width="33%"><div id="<?php echo $this->_tpl_vars['page_prefix'];  echo $this->_tpl_vars['page_id']; ?>
_<?php echo smarty_function_counter(array(), $this);?>
"></div></td>
				<td class="container" valign="top" width="33%"><div id="<?php echo $this->_tpl_vars['page_prefix'];  echo $this->_tpl_vars['page_id']; ?>
_<?php echo smarty_function_counter(array(), $this);?>
"></div></td>
				<td class="container" valign="top" width="33%"><div id="<?php echo $this->_tpl_vars['page_prefix'];  echo $this->_tpl_vars['page_id']; ?>
_<?php echo smarty_function_counter(array(), $this);?>
"></div></td>	
			<?php endif; ?>
			<?php if ($this->_sections['i']['max'] == 1): ?>
				<td class="container" valign="top" width="33%"><div id="<?php echo $this->_tpl_vars['page_prefix'];  echo $this->_tpl_vars['page_id']; ?>
_<?php echo smarty_function_counter(array(), $this);?>
">-</div></td>
				<td class="container" valign="top" width="33%"><div id="<?php echo $this->_tpl_vars['page_prefix'];  echo $this->_tpl_vars['page_id']; ?>
_<?php echo smarty_function_counter(array(), $this);?>
">-</div></td>	
			<?php elseif ($this->_sections['i']['max'] == 2): ?>
				<td class="container" valign="top" width="33%"><div id="<?php echo $this->_tpl_vars['page_prefix'];  echo $this->_tpl_vars['page_id']; ?>
_<?php echo smarty_function_counter(array(), $this);?>
"></div></td>
			<?php endif; ?>
	</tr>
</table>