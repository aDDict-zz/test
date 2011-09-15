<?php /* Smarty version 2.6.6, created on 2010-05-05 12:55:22
         compiled from rss.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'rss.html', 6, false),array('modifier', 'date_format', 'rss.html', 10, false),)), $this); ?>
<?php echo '<?xml'; ?>
 version="1.0"<?php echo '?>'; ?>

<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title><?php echo ((is_array($_tmp=$this->_tpl_vars['title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</title>
        <link>http://www.hirek.hu</link>
        <description></description>
        <language>hu-HU</language>
        <copyright>Hirek.hu <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y") : smarty_modifier_date_format($_tmp, "%Y")); ?>
</copyright>
        <managingEditor>szerkeszto@hirek.hu (Szerkesztő)</managingEditor>
        <webMaster>webmester@hirek.hu (Webmester)</webMaster>      
        <pubDate><?php echo $this->_tpl_vars['pubDate']; ?>
</pubDate>
        <lastBuildDate><?php echo $this->_tpl_vars['lastBuildDate']; ?>
</lastBuildDate>
        <category><?php echo ((is_array($_tmp=$this->_tpl_vars['category'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</category>
        <generator>Hirek.hu</generator>
        <docs>http://blogs.law.harvard.edu/tech/rss</docs>
        <ttl>60</ttl>
	<atom:link href="http://www.hirek.hu/rss.php?kw=<?php echo ((is_array($_tmp=$this->_tpl_vars['kw'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  if ($this->_tpl_vars['category']): ?>&amp;category=<?php echo ((is_array($_tmp=$this->_tpl_vars['category'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  endif; ?>" rel="self" type="application/rss+xml" />
        <?php if (count($_from = (array)$this->_tpl_vars['results'])):
    foreach ($_from as $this->_tpl_vars['result']):
?>
        <item>
            <title><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  echo ((is_array($_tmp=$this->_tpl_vars['result']['rss_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</title>
            <description><![CDATA[<?php echo $this->_tpl_vars['result']['description']; ?>
]]></description>
            <link>
            <?php if ($this->_tpl_vars['type'] == 'ct'): ?>
                http://www.hirek.hu/click.php?from=rsskw&amp;link=<?php echo ((is_array($_tmp=$this->_tpl_vars['result']['link'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&amp;news_id=<?php echo $this->_tpl_vars['result']['id']; ?>

            <?php else: ?>
                http://www.hirek.hu/?from=rsskw&amp;kw=<?php echo $this->_tpl_vars['kw']; ?>
&amp;news_id=<?php echo $this->_tpl_vars['result']['id']; ?>

            <?php endif; ?>
            </link>         
	    <guid><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['link'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</guid>
        </item>
        <?php endforeach; unset($_from); endif; ?>
    </channel>
</rss>