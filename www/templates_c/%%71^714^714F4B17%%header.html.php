<?php /* Smarty version 2.6.6, created on 2011-07-25 15:56:05
         compiled from header.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'header.html', 4, false),array('modifier', 'escape', 'header.html', 10, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo ((is_array($_tmp=@$this->_tpl_vars['PAGE_TITLE'])) ? $this->_run_mod_handler('default', true, $_tmp, "H&iacute;rek.hu") : smarty_modifier_default($_tmp, "H&iacute;rek.hu")); ?>
</title>
<base href="<?php echo $this->_tpl_vars['var']->baseurl; ?>
">
<?php if (! $this->_tpl_vars['var']->test_site): ?>
<meta name="google-site-verification" content="tN3Wl1lXLREj8Yygc4ZnVLM8e99plIszbzceMRBESik" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<?php endif; ?>
<meta name="description" content="<?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['PAGE_DESCRIPTION'])) ? $this->_run_mod_handler('default', true, $_tmp, "") : smarty_modifier_default($_tmp, "")))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />
<meta name="keywords" content="<?php echo ((is_array($_tmp=@$this->_tpl_vars['PAGE_KEYWORDS'])) ? $this->_run_mod_handler('default', true, $_tmp, "") : smarty_modifier_default($_tmp, "")); ?>
" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<?php if (! $this->_tpl_vars['var']->test_site): ?>
<meta name="verify-v1" content="zG/8fSzPbancCnRu9eiPvRbPjXT9TK16X9uirOgTZ/o=" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<?php endif; ?>

<link href="templates/main9.css" rel="stylesheet" type="text/css" />

<script language="javascript" type="text/javascript" src="js/main25.js"></script>
<script language="javascript" type="text/javascript" src="js/common11.js"></script>
<script type="text/javascript" src="http://www.kepzesinfo.hu/js/embededflash.js"></script>
<?php if ($this->_tpl_vars['valasz']): ?>
<script language="javascript" type="text/javascript" src="js/embededflash.js"></script>
<?php endif;  if ($this->_tpl_vars['page'] == 'search'): ?>
    <?php else: ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "index_header_js.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
<script language="javascript" type="text/javascript" >var userID = '<?php echo $this->_tpl_vars['__user_id']; ?>
';</script>
<?php if (! $this->_tpl_vars['var']->test_site):  echo '
    <!--Google analytics-->
    <script type="text/javascript">

 var _gaq = _gaq || [];
_gaq.push([\'_setAccount\', \'UA-22219389-1\']);
_gaq.push([\'_addOrganic\',\'ok.hu\',\'q\']);
_gaq.push([\'_addOrganic\',\'startlap.hu\',\'q\']);
_gaq.push([\'_addOrganic\',\'startlapkereso.hu\',\'q\']);
_gaq.push([\'_addOrganic\',\'images.google.hu\',\'q\']);
_gaq.push([\'_addOrganic\',\'google.com\',\'q\']);
_gaq.push([\'_addOrganic\', \'bluu.hu\', \'kerdes\']);
_gaq.push([\'_addOrganic\', \'johu.hu\', \'q\']);
_gaq.push([\'_trackPageview\']);

(function() {
var ga = document.createElement(\'script\');
ga.type = \'text/javascript\'; ga.async = true;
ga.src = \'http://hirek.hu/js/ga.js\';
var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);
})();

    </script>
    <!--Google analytics end-->

'; ?>

    <?php if (false): ?>
    <!--webaudit-->
    <script language="javascript" type="text/javascript" src="js/webaudit.js"></script>
    <SCRIPT language="JavaScript">
    <!--
    document.write('<!-- Medián WebAudit Hírek Média Hirek.hu 1/2 --><img style="position:absolute;top:-100px;left:-100px" src="http://audit.median.hu/cgi-bin/track.cgi?uc=11731252079918&dc=1&ui='+same+'" width="1" height="1">');
    //-->
    </SCRIPT>
    <NOSCRIPT>
    <!-- Medián WebAudit Hírek Média Hirek.hu 1/2 -->
    <img style="position:absolute;top:-100px;left:-100px" src="http://audit.median.hu/cgi-bin/track.cgi?uc=11731252079918&dc=1" width="1" height="1">
    </NOSCRIPT>
    <!--webaudit end-->
    <?php endif;  endif; ?>
</head>