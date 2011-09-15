<?php /* Smarty version 2.6.6, created on 2010-12-14 16:00:01
         compiled from news_xml.html */ ?>
<?php echo '<?xml'; ?>
 version="1.0" encoding="utf-8" <?php echo '?>'; ?>

<hirek_news>
<?php if (count($_from = (array)$this->_tpl_vars['news'])):
    foreach ($_from as $this->_tpl_vars['page_id'] => $this->_tpl_vars['page']):
?>
    <?php if (count($_from = (array)$this->_tpl_vars['page'])):
    foreach ($_from as $this->_tpl_vars['news_id'] => $this->_tpl_vars['n']):
?>
    <item>
        <id><?php echo $this->_tpl_vars['news_id']; ?>
</id>
        <title><![CDATA[<?php echo $this->_tpl_vars['n']['title']; ?>
]]></title>
        <link><![CDATA[<?php echo $this->_tpl_vars['var']->baseurl; ?>
?page_id=<?php echo $this->_tpl_vars['page_id']; ?>
&news_id=<?php echo $this->_tpl_vars['news_id'];  echo $this->_tpl_vars['fromparam']; ?>
]]></link>
        <agency><![CDATA[<?php echo $this->_tpl_vars['n']['agency_name']; ?>
]]></agency>
    </item>
    <?php endforeach; unset($_from); endif;  endforeach; unset($_from); endif; ?>
</hirek_news>