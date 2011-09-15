<?php /* Smarty version 2.6.6, created on 2009-12-21 13:37:31
         compiled from stat.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_select_date', 'stat.html', 23, false),array('function', 'cycle', 'stat.html', 62, false),array('modifier', 'count', 'stat.html', 50, false),array('modifier', 'default', 'stat.html', 63, false),)), $this); ?>
<?php echo '
<style>
    #statperiod select, input {width : auto;}
</style>
'; ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td class="page_title">Cikkre kattint&aacute;sok</td>
</tr>
<tr>
    <td>
        <form name="statperiod" action="index.php?id=<?php echo $this->_tpl_vars['id']; ?>
&sub_id=<?php echo $this->_tpl_vars['sub_id']; ?>
" method="post">
        <div id="statperiod">
        <input type="hidden" name="action" value="ct_news" />
        <select name="period" onchange="if (this.options[this.selectedIndex].value != '') document.statperiod.submit();">
            <option value="">-- id&#337;szak --</option>
            <option value="today" <?php if ($this->_tpl_vars['period'] == 'today'): ?>selected<?php endif; ?>>Ma</option>
            <option value="yesterday" <?php if ($this->_tpl_vars['period'] == 'yesterday'): ?>selected<?php endif; ?>>Tegnap</option>
            <option value="week" <?php if ($this->_tpl_vars['period'] == 'week'): ?>selected<?php endif; ?>>Legut&oacute;bbi h&eacute;t</option>
            <option value="month" <?php if ($this->_tpl_vars['period'] == 'month'): ?>selected<?php endif; ?>>Legut&oacute;bbi 30 nap</option>
        </select>
        &nbsp;
        <?php echo smarty_function_html_select_date(array('prefix' => 'from_','start_year' => '2007','field_order' => 'YMD','time' => ($this->_tpl_vars['from_date'])), $this);?>
&nbsp;-&nbsp;<?php echo smarty_function_html_select_date(array('prefix' => 'to_','start_year' => '2007','field_order' => 'YMD','time' => ($this->_tpl_vars['to_date'])), $this);?>

        <input type="button" name="go" value="mehet" onclick="document.statperiod.period.value='';document.statperiod.submit();">
        </div>
        <div id="statperiod">
        <p>a kiválasztott hírforrásra - hírfolyamra:</p>
        <select name="agency_id" onchange="document.statperiod.rss_id.value=0;document.statperiod.submit();">
            <option value="0">-- összes hírforrás --</option>
            <?php if (count($_from = (array)$this->_tpl_vars['agencies'])):
    foreach ($_from as $this->_tpl_vars['idx'] => $this->_tpl_vars['a']):
?>
            <option value="<?php echo $this->_tpl_vars['a']['agency_id']; ?>
" <?php if ($this->_tpl_vars['a']['selected']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['a']['agency_name']; ?>
</option>
            <?php endforeach; unset($_from); endif; ?>
        </select>
        <select name="rss_id" onchange="document.statperiod.submit();">
            <option value="0">-- összes hírfolyam --</option>
            <?php if (count($_from = (array)$this->_tpl_vars['rss_feeds'])):
    foreach ($_from as $this->_tpl_vars['idx'] => $this->_tpl_vars['a']):
?>
            <option value="<?php echo $this->_tpl_vars['a']['id']; ?>
" <?php if ($this->_tpl_vars['a']['selected']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['a']['rss_name']; ?>
</option>
            <?php endforeach; unset($_from); endif; ?>
        </select>
        </div>
        </form>
    </td>
</tr>
</table>
    <p>&Ouml;sszes kattint&aacute;s: <?php echo $this->_tpl_vars['ct_logged_in']['2']; ?>
</p>
<?php if ($this->_tpl_vars['ct_logged_in']['2']): ?>
    <p>Bejelentkezett felhaszn&aacute;l&oacute;k: <?php echo $this->_tpl_vars['ct_logged_in']['1']; ?>
</p>
    <p>Be nem jelentkezett felhaszn&aacute;l&oacute;k: <?php echo $this->_tpl_vars['ct_logged_in']['0']; ?>
</p>
<?php endif;  if (count($this->_tpl_vars['ct_url'])): ?>
    <div style="width:100%;margin 20px 10px; ">
    <p class="page_subtitle">Legt&ouml;bbet l&aacute;togatott cikkek</p>
    <table width="100%" cellspacing="0" cellpadding="5" style="border:2px #f3f3f3 solid;">
    <tr>
        <td style="width:50%;font-weight:bold;">Hírforrás</td>
        <td style="width:50%;font-weight:bold;">Hírfolyam</td>
        <td style="font-weight:bold;">Megjelenés</td>
        <td style="font-weight:bold;">Naponta</td>
        <td style="font-weight:bold;">Összesen<br>(bejel.)</td>
    </tr>
    <?php if (count($_from = (array)$this->_tpl_vars['ct_url'])):
    foreach ($_from as $this->_tpl_vars['url'] => $this->_tpl_vars['u']):
?>
    <tr style="background-color:<?php echo smarty_function_cycle(array('values' => "#f3f3f3,#fff"), $this);?>
">
        <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['u']['agency'])) ? $this->_run_mod_handler('default', true, $_tmp, "-") : smarty_modifier_default($_tmp, "-")); ?>
</td>
        <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['u']['rss'])) ? $this->_run_mod_handler('default', true, $_tmp, "-") : smarty_modifier_default($_tmp, "-")); ?>
</td>
        <td><?php echo $this->_tpl_vars['u']['date_from']; ?>
-<?php echo $this->_tpl_vars['u']['date_to']; ?>
</td>
        <td align="right"><?php echo $this->_tpl_vars['u']['ctperday']; ?>
</td>
        <td align="right"><?php echo $this->_tpl_vars['u']['c']; ?>
&nbsp;(<?php echo ((is_array($_tmp=@$this->_tpl_vars['u']['1'])) ? $this->_run_mod_handler('default', true, $_tmp, "-") : smarty_modifier_default($_tmp, "-")); ?>
)</td>
    </tr>
    <tr style="background-color:<?php echo smarty_function_cycle(array('values' => "#f3f3f3,#fff"), $this);?>
">
        <td colspan="5"><a style="text-decoration:underline;" target="_blank" href="<?php echo $this->_tpl_vars['url']; ?>
"><?php echo $this->_tpl_vars['u']['title']; ?>
</a></td>
    </tr>
    <?php endforeach; unset($_from); endif; ?>
    </table>
    </div>
<?php endif;  if (count($this->_tpl_vars['ct_days'])): ?>
    <div style="width:100%;margin 20px 10px; ">
    <p class="page_subtitle">Napi kattint&aacute;sok</p>
    <table width="100%" cellspacing="0" cellpadding="5" style="border:2px #f3f3f3 solid;">
    <tr>
        <td style="width:100%;font-weight:bold;">Nap</td>
        <td><div style="width:120px;font-weight:bold;">Kattint&aacute;s<br>(bejel.)</div></td>
    </tr>
    <?php if (count($_from = (array)$this->_tpl_vars['ct_days'])):
    foreach ($_from as $this->_tpl_vars['day'] => $this->_tpl_vars['d']):
?>
    <tr style="background-color:<?php echo smarty_function_cycle(array('values' => "#f3f3f3,#fff"), $this);?>
">
        <td><?php echo $this->_tpl_vars['day']; ?>
</td>
        <td style="text-align:right;"><?php echo $this->_tpl_vars['d']['c']; ?>
&nbsp;(<?php echo ((is_array($_tmp=@$this->_tpl_vars['d']['1'])) ? $this->_run_mod_handler('default', true, $_tmp, "-") : smarty_modifier_default($_tmp, "-")); ?>
)</td>
    </tr>
    <?php endforeach; unset($_from); endif; ?>
    </table>
    </div>
<?php endif; ?>