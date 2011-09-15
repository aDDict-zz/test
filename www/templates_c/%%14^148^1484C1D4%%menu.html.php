<?php /* Smarty version 2.6.6, created on 2010-05-04 16:49:59
         compiled from menu.html */ ?>
<div id="hintContainer" style="position:absolute; width:280px; padding:5px;border:1px solid #336699; background:#FFFFFF;z-index:100; display:none;"></div>
<h1 id="header" ><a href="<?php echo $this->_tpl_vars['var']->baseurl; ?>
" ><?php echo $this->_tpl_vars['var']->page_slogan; ?>
</a></h1>
<div id="pages">
<table width="100%" cellspacing="0" cellpadding="0" border="0" id="menutable">
<tr>
    <?php if ($this->_tpl_vars['page'] == 'index' || $this->_tpl_vars['page'] == ""): ?>
    <td width="90" height="41" style="padding-left:5px;" align="left">
        <table style="width:105px;" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td><img style="cursor:pointer;" onClick="showManager()" src="i/add2.gif" border="0" /></td>
            <td><div id="addContent" onClick="showManager()" style="text-align:left;">&Uacute;j tartalom</div></td>
        </tr>
        </table>
    </td>
    <?php endif; ?>
    <?php if (count($_from = (array)$this->_tpl_vars['pages'])):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['p']):
?>
    <?php if ($this->_tpl_vars['id'] > 0): ?>
    <td width="7%" height="41">
    <!--[if IE]>
    <div style="height:15px;">
    <![endif]-->
    <div id="menudiv">
        <?php if ($this->_tpl_vars['id'] == $this->_tpl_vars['page_id']): ?><h2><?php endif; ?><a id="dp_<?php echo $this->_tpl_vars['id']; ?>
" class="menuitem" href="<?php if ($this->_tpl_vars['var']->rewrite_engine):  echo $this->_tpl_vars['var']->rewrite_baseurl;  echo $this->_tpl_vars['p']['page_url'];  else: ?>index.php?page_id=<?php echo $this->_tpl_vars['id'];  endif; ?>"><?php echo $this->_tpl_vars['p']['page_name']; ?>
</a><?php if ($this->_tpl_vars['id'] == $this->_tpl_vars['page_id']): ?></h2><?php endif; ?>
    </div>
    <!--[if IE]>
    </div>
    <![endif]-->
    </td>
    <td>
    <?php if (! $this->_tpl_vars['p']['last']): ?><b>|</b><?php endif; ?>
    </td>
    <?php endif; ?>
    <?php endforeach; unset($_from); endif; ?>

</tr>
</table>
</div>
