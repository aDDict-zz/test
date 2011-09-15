<?php /* Smarty version 2.6.6, created on 2011-03-25 20:04:51
         compiled from login.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'login.html', 5, false),array('modifier', 'escape', 'login.html', 6, false),)), $this); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<base href="<?php echo $this->_tpl_vars['var']->baseurl; ?>
">
<title><?php echo ((is_array($_tmp=@$this->_tpl_vars['PAGE_TITLE'])) ? $this->_run_mod_handler('default', true, $_tmp, "H&iacute;rek.hu") : smarty_modifier_default($_tmp, "H&iacute;rek.hu")); ?>
</title>
<meta name="description" content="<?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['PAGE_DESCRIPTION'])) ? $this->_run_mod_handler('default', true, $_tmp, "") : smarty_modifier_default($_tmp, "")))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />
<meta name="keywords" content="<?php echo ((is_array($_tmp=@$this->_tpl_vars['PAGE_KEYWORDS'])) ? $this->_run_mod_handler('default', true, $_tmp, "") : smarty_modifier_default($_tmp, "")); ?>
" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link href="templates/main9.css" rel="stylesheet" type="text/css" />
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

    <!--webaudit-->
    <script language="javascript" type="text/javascript" src="js/webaudit.js"></script>
    <SCRIPT language="JavaScript">
    <!--
    document.write(\'<!-- Medián WebAudit Hírek Média Hirek.hu 1/2 --><img style="position:absolute;top:-100px;left:-100px" src="http://audit.median.hu/cgi-bin/track.cgi?uc=11731252079918&dc=1&ui=\'+same+\'" width="1" height="1">\');
    //-->
    </SCRIPT>
    <NOSCRIPT>
    <!-- Medián WebAudit Hírek Média Hirek.hu 1/2 -->
    <img style="position:absolute;top:-100px;left:-100px" src="http://audit.median.hu/cgi-bin/track.cgi?uc=11731252079918&dc=1" width="1" height="1">
    </NOSCRIPT>
    <!--webaudit end-->
'; ?>

<?php endif; ?>
</head>

<body>
<div style="margin-top:150px;">
<div style="width:100%;text-align:center;margin-bottom:10px;"><a href=""><img src="i/hirek.gif" /></a></div>
<div class="box register">
    <div class="topboxcont" style="width:300px;margin:auto;"><div class="bottombox"><div class="leftbox"><div class="rightbox"><div class="blbox"><div class="brbox"><div class="tlbox"><div class="trbox">
		<div class="head" style="cursor:default;padding-top:10px; ">			
			<h1>&nbsp;Bejelentkez&eacute;s</h1>
		</div>
		<div class="content" style="margin-right:5px;margin-top:23px;">
			<form name="login" method="post" action="login.php">
			<input type="hidden" name="action" value="login" />
			<table border="0" cellspacing="0" cellpadding="5" align="center" >
			  <?php if ($this->_tpl_vars['error_login']): ?>
			  <tr>
			  	<td colspan="2">
					<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['error_login']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
						<span class="error"><?php echo $this->_tpl_vars['error_login'][$this->_sections['i']['index']]; ?>
</span><br />
					<?php endfor; endif; ?>
				</td>
			  </tr>
			  <?php endif; ?>
			  <tr>
				<td><b>E-mail c&iacute;m:</b></td>
				<td><input type="text" name="data[email]" value="<?php echo $this->_tpl_vars['datas_login']['email']; ?>
" /></td>
			  </tr>
			  <tr>
				<td><b>Jelsz&oacute;:</b></td>
				<td><input type="password" name="data[password]" value="<?php echo $this->_tpl_vars['datas_login']['password']; ?>
" /></td>
			  </tr>
			  <tr>
				<td colspan="2" align="center"><input type="checkbox" name="remind_me" id="remind_me" value="1" /> <label for="remind_me">bejelentkez&eacute;sem megjegyz&eacute;se</label></td>
			  </tr>
			  <tr>
				<td colspan="2" align="center"><img src="i/login_button.gif" alt="Bejelentkezem" class="ql" onclick="document.login.submit();" /></td>
			  </tr>
			  <tr>
				<td colspan="2" align="center">
                    <a href="login.php?id=1">Elfelejtettem a jelszavam</a>&nbsp;&nbsp;&nbsp;&nbsp;
				    <a href="<?php if ($this->_tpl_vars['var']->rewrite_engine):  echo $this->_tpl_vars['var']->rewrite_baseurl;  echo $this->_tpl_vars['var']->page_url_register;  else: ?>register.php<?php endif; ?>">Regisztr&aacute;ci&oacute;</a>
                </td>
			  </tr>
			</table>
			</form>
		</div>
    </div></div></div></div></div></div></div></div>
</div>
</div>
</div>
</body>
</html>