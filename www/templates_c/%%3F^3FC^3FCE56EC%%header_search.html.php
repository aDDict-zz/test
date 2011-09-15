<?php /* Smarty version 2.6.6, created on 2009-12-18 15:47:11
         compiled from header_search.html */ ?>
<form name="search2" method="get" action="search.php" style="margin:0;" <?php if (! $this->_tpl_vars['searchpage'] && false): ?>target="_blank"<?php endif; ?> id="searchForm">
<div id="hiddenInputs"><input type="hidden" name="p" value="Keres" /></div>
<div style="position:absolute;top:5px;width:100%;text-align:right;height:95px;">
    <div style="margin-right:30px;" class="search">
        <table border="0" cellspacing="2" cellpadding="0" align="right" width="<?php if ($this->_tpl_vars['searchpage']): ?>380<?php else: ?>250<?php endif; ?>">
          <tr>
            <td align="left" width="100%"><input type="text" name="q" value="<?php echo $this->_tpl_vars['q']; ?>
" class="text" id="searchText" /></td>
            <td class="button" onclick="document.search2.submit();">Keres&eacute;s</td>
          </tr>
          <?php if (! $this->_tpl_vars['searchpage']): ?>
          <tr>
            <td id="search_where" class="radio" colspan="2" align="left">
                <label for="rnews" id="lnews" class="selected" onclick="changeSearch('lnews')">h&iacute;rek</label><input type="checkbox" name="rnews" id="rnews" checked onclick="changeSearch('lnews')" />  
                <label for="rweb" id="lweb" onclick="changeSearch('lweb')">web</label><input class="cb" type="checkbox" name="rweb" id="rweb" onclick="changeSearch('lweb')" /> 
            </td>
          </tr>
        <?php else: ?>
        <tr>
            <td id="search_lead" colspan="2" class="radio" align="left"><input type="checkbox" name="lead" value="1"<?php if ($this->_tpl_vars['lead'] == 1): ?> checked<?php endif; ?> style="border:0px;" onclick="if (document.search2 && document.search2.lead) document.search2.lead.value = this.checked ? 1 : 0;"/> keres&eacute;s a bevezet&#337;ben is</td>
        </tr>
        <tr>
            <td id="search_filter" colspan="2" class="radio" align="left">
                &nbsp;Id&#337;szak: 
                <select name="period" onchange="if (document.search2 && document.search2.period) document.search2.period.value = this.options[this.selectedIndex].value;">
                <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['periods']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                <?php if ($this->_tpl_vars['period'] == $this->_sections['i']['index']): ?>
                    <option value="<?php echo $this->_sections['i']['index']; ?>
" selected><?php echo $this->_tpl_vars['periods'][$this->_sections['i']['index']]; ?>
</option>
                <?php else: ?>
                    <option value="<?php echo $this->_sections['i']['index']; ?>
"><?php echo $this->_tpl_vars['periods'][$this->_sections['i']['index']]; ?>
</option>
                <?php endif; ?>
                <?php endfor; endif; ?>
                </select> 
                Kateg&oacute;ria: 
                <select name="category" onchange="if (document.search2 && document.search2.category) document.search2.category.value = this.options[this.selectedIndex].value;">
                    <option value="">Mindegy</option>
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
                    <?php if ($this->_tpl_vars['selected_category'] == $this->_tpl_vars['categories'][$this->_sections['i']['index']]['cat_id']): ?>
                    <option value="<?php echo $this->_tpl_vars['categories'][$this->_sections['i']['index']]['cat_id']; ?>
" selected><?php echo $this->_tpl_vars['categories'][$this->_sections['i']['index']]['cat_name']; ?>
</option>
                   <?php else: ?>
                    <option value="<?php echo $this->_tpl_vars['categories'][$this->_sections['i']['index']]['cat_id']; ?>
"><?php echo $this->_tpl_vars['categories'][$this->_sections['i']['index']]['cat_name']; ?>
</option>
                    <?php endif; ?>
                    <?php endfor; endif; ?>
                </select>					
                <script>
                    if (document.search2 && document.search2.q) document.search2.q.value = '<?php echo $this->_tpl_vars['q']; ?>
';
                    if (document.search2 && document.search2.lead) document.search2.lead.value = '<?php echo $this->_tpl_vars['lead']; ?>
';
                    if (document.search2 && document.search2.agency) document.search2.agency.value = '<?php echo $this->_tpl_vars['agency']; ?>
';
                    if (document.search2 && document.search2.period) document.search2.period.value = '<?php echo $this->_tpl_vars['period']; ?>
';
                    if (document.search2 && document.search2.category) document.search2.category.value = '<?php echo $this->_tpl_vars['selected_category']; ?>
';
                    var o; if (o = document.getElementById('search_radio')) o.style.display = 'none';
                </script>
            </td>
        </tr>
        <?php endif; ?>
        </table>
    </div>
</div>
</form>