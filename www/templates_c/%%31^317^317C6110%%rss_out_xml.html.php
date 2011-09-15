<?php /* Smarty version 2.6.6, created on 2009-12-18 16:20:01
         compiled from rss_out_xml.html */ ?>
<?php echo '<?xml'; ?>
 version="1.0" encoding="utf-8" <?php echo '?>'; ?>

<hirek_news>
<?php if (count($_from = (array)$this->_tpl_vars['news']['entries'])):
    foreach ($_from as $this->_tpl_vars['idx'] => $this->_tpl_vars['n']):
?>
<item>
    <id><?php echo $this->_tpl_vars['n']['id']; ?>
</id>
    <title><![CDATA[<?php echo $this->_tpl_vars['n']['title']; ?>
]]></title>
    <link><![CDATA[<?php echo $this->_tpl_vars['var']->baseurl; ?>
?kw=<?php echo $this->_tpl_vars['news']['kw']; ?>
&news_id=<?php echo $this->_tpl_vars['n']['id'];  echo $this->_tpl_vars['fromparam']; ?>
]]></link>
</item>
<?php endforeach; unset($_from); endif; ?>
</hirek_news>