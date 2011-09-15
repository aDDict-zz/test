<?php /* Smarty version 2.6.6, created on 2009-12-18 15:47:35
         compiled from topct_rss.html */ ?>
<?php echo '<?xml'; ?>
 version="1.0" encoding="utf-8"<?php echo '?>'; ?>

<rss version="2.0">
  <channel>
    <title>Hirek.hu top</title>
    <description>Legolvasottabb cikkek</description>
    <language>hu</language>
    <link>http://www.hirek.hu/</link>
    <image>
      <title>Hirek.hu top</title>
      <url>http://www.hirek.hu/i/hirek.gif</url>
      <link>http://www.hirek.hu/</link>
    </image>
    <generator>Hirek.hu</generator>
    <?php if (count($_from = (array)$this->_tpl_vars['intervals'])):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['interval']):
?>
    <?php if (count($_from = (array)$this->_tpl_vars['top'][$this->_tpl_vars['i']])):
    foreach ($_from as $this->_tpl_vars['item']):
?>
    <item>
      <pubDate><?php echo $this->_tpl_vars['item']['pubdate']; ?>
</pubDate>
      <title><![CDATA[<?php echo $this->_tpl_vars['item']['title']; ?>
]]></title>
      <link><![CDATA[<?php echo $this->_tpl_vars['item']['url']; ?>
]]></link>
      <guid>http://www.hirek.hu/?news_id=<?php echo $this->_tpl_vars['item']['news_id']; ?>
</guid>
      <description><![CDATA[<?php echo $this->_tpl_vars['item']['lead']; ?>
]]></description>
      <category><![CDATA[<?php echo $this->_tpl_vars['item']['rss_name']; ?>
]]></category>
    </item>
    <?php endforeach; unset($_from); endif; ?>
    <?php endforeach; unset($_from); endif; ?>
 </channel>
</rss>