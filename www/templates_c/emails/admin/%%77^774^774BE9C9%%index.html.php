<?php /* Smarty version 2.6.6, created on 2011-05-09 09:51:48
         compiled from index.html */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>H&iacute;rek.hu - Adminisztr&aacute;ci&oacute;s fel&uuml;let</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../templates/admin/main.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="../js/main25.js"></script>
<script language="javascript" type="text/javascript" src="../js/admin/jquery-1.4.2.min.js"></script>
<script language="javascript" type="text/javascript" src="../js/admin/jquery.tablednd_0_5.js"></script>

<?php if ($this->_tpl_vars['sub_id'] == 1):  echo '
<script>
$(document).ready(function() {

    $("#pagelist").tableDnD({
        onDrop: function(table, row) {
            var rows = table.tBodies[0].rows;
            var pagesort = \'\';
            for (var i=0; i<rows.length; i++) {
                pagesort += rows[i].id.replace(\'page-\', \'\')+\'|\';
            }
            $.ajax({ url: \'../ajax/admin/pages.php?pagesort=\'+pagesort});
        },
    });
});
</script>
'; ?>

<?php endif; ?>

</head>

<body>
<?php if ($this->_tpl_vars['__user_name'] != ""): ?>
<div id="content">	
	<div id="row">&Uuml;dv&ouml;zl&uuml;nk <?php echo $this->_tpl_vars['__user_name']; ?>
&nbsp;| <a href="index.php?id=-1" class="sub_menu_link">Kil&eacute;p&eacute;s</a>&nbsp;</div>
	<div id="header"></div>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menu.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php if ($this->_tpl_vars['id'] == 1): ?>
		<?php if ($this->_tpl_vars['sub_id'] == 1): ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "pages.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php elseif ($this->_tpl_vars['sub_id'] == 2): ?>	
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "add_edit_page.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php elseif ($this->_tpl_vars['sub_id'] == 3): ?>	
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page_categories.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php elseif ($this->_tpl_vars['sub_id'] == 4): ?>	
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "fixed_categories.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>	
		<?php endif; ?>	
	<?php elseif ($this->_tpl_vars['id'] == 2): ?>	
		<?php if ($this->_tpl_vars['sub_id'] == 1): ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "cat_colors.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php elseif ($this->_tpl_vars['sub_id'] == 2): ?>			
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "add_edit_cat_colors.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php endif; ?>	
	<?php elseif ($this->_tpl_vars['id'] == 3): ?>
		<?php if ($this->_tpl_vars['sub_id'] == 1): ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "agencies.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php elseif ($this->_tpl_vars['sub_id'] == 2): ?>	
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "add_edit_agency.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php elseif ($this->_tpl_vars['sub_id'] == 3): ?>	
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "news_flows.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>	
		<?php elseif ($this->_tpl_vars['sub_id'] == 4): ?>	
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "add_edit_news_flow.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>		
		<?php endif; ?>
	<?php elseif ($this->_tpl_vars['id'] == 5): ?>		
		<?php if ($this->_tpl_vars['sub_id'] == 1): ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "list_users.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php elseif ($this->_tpl_vars['sub_id'] == 2): ?>	
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "edit_users.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php endif; ?>
	<?php elseif ($this->_tpl_vars['id'] == 6): ?>	
		<?php if ($this->_tpl_vars['sub_id'] == 1): ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "list_rss_categories.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php elseif ($this->_tpl_vars['sub_id'] == 2): ?>	
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "add_edit_rss_categories.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php elseif ($this->_tpl_vars['sub_id'] == 3): ?>	
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "resort_feeds.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php elseif ($this->_tpl_vars['sub_id'] == 4): ?>	
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "highlighted_feeds.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>	
		<?php endif; ?>
	<?php elseif ($this->_tpl_vars['id'] == 7): ?>	
		<?php if ($this->_tpl_vars['sub_id'] == 1): ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "stat.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>	
        <?php endif; ?>
	<?php endif; ?>
</div>
<?php else: ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "login.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
</body>
</html>