<?php /* Smarty version 2.6.6, created on 2011-09-14 09:50:02
         compiled from news_rss.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'news_rss.html', 2, false),)), $this); ?>
<?php echo '<?xml'; ?>
 version="1.0" encoding="<?php echo ((is_array($_tmp=@$this->_tpl_vars['encoding'])) ? $this->_run_mod_handler('default', true, $_tmp, "utf-8") : smarty_modifier_default($_tmp, "utf-8")); ?>
" <?php echo '?>'; ?>

<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title><![CDATA[Hírek.hu<?php if ($this->_tpl_vars['page_name']): ?> - <?php echo $this->_tpl_vars['page_name'];  endif; ?>]]></title>
        <description></description>
        <link><![CDATA[<?php echo $this->_tpl_vars['var']->baseurl;  if ($this->_tpl_vars['page_id']): ?>?page_id=<?php echo $this->_tpl_vars['page_id'];  endif; ?>]]></link>
        <atom:link href="<?php echo $this->_tpl_vars['var']->baseurl; ?>
?page_id=<?php echo $this->_tpl_vars['page_id']; ?>
" rel="self" type="application/rss+xml" />
        <?php if (count($_from = (array)$this->_tpl_vars['news'])):
    foreach ($_from as $this->_tpl_vars['page_id'] => $this->_tpl_vars['page']):
?>
            <?php if (count($_from = (array)$this->_tpl_vars['page'])):
    foreach ($_from as $this->_tpl_vars['news_id'] => $this->_tpl_vars['n']):
?>
                <item>
                    <title><![CDATA[<?php echo $this->_tpl_vars['n']['title']; ?>
]]></title>
                    <link><![CDATA[<?php echo $this->_tpl_vars['var']->baseurl; ?>
?page_id=<?php echo $this->_tpl_vars['page_id']; ?>
&news_id=<?php echo $this->_tpl_vars['news_id'];  echo $this->_tpl_vars['fromparam']; ?>
]]></link>
                    <guid><?php echo $this->_tpl_vars['news']['id']; ?>
</guid>
                    <description><![CDATA[<?php echo $this->_tpl_vars['n']['description']; ?>
]]></description>
                </item>
            <?php endforeach; unset($_from); endif; ?>
        <?php endforeach; unset($_from); endif; ?>
    </channel>
</rss>