<?php /* Smarty version 2.6.6, created on 2011-04-30 03:19:47
         compiled from register.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'register.html', 5, false),array('modifier', 'escape', 'register.html', 6, false),)), $this); ?>
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
<script language="javascript" type="text/javascript" src="js/main25.js"></script>
<script language="javascript" type="text/javascript" src="js/common8.js"></script>
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
			<h1>&nbsp;Regisztr&aacute;ci&oacute;</h1>
		</div>
		<div class="content" style="margin-right:5px;">
		  <p style="margin:10px 10px 10px 0;">A regisztrációt követően lehetősége nyílik arra, hogy tetszés szerinti hírcsoportokkal saját elképzeléséhez igazítsa a hirek.hu szerkezetét és tartalmát.</p>
			<form name="register" method="post" action="register.php">
			<input type="hidden" name="action" value="register" />
			<table border="0" cellspacing="0" cellpadding="5" align="center" style="width:100%"> 
			  <?php if ($this->_tpl_vars['error_register']): ?>
    		  <tr>
			  	<td colspan="2">
					<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['error_register']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
						<span class="error"><?php echo $this->_tpl_vars['error_register'][$this->_sections['i']['index']]; ?>
</span><br />
					<?php endfor; endif; ?>
				</td>
			  </tr>
			  <?php endif; ?>
			  <tr>
				<td><div style="width:100px;"><b>E-mail c&iacute;m:</b></div></td>
				<td colspan="2" align="right"><input type="text" name="data[email]" size="25" value="<?php echo $this->_tpl_vars['datas_register']['email']; ?>
" /></td>
			  </tr>
			  <tr>
				<td ><b>Jelsz&oacute;:</b></td>
                <td >&nbsp;</td>
				<td align="right" width="20"><input type="password" name="data[password]" value="<?php echo $this->_tpl_vars['datas_register']['password']; ?>
" /></td>
			  </tr>
			  <tr>
				<td colspan="2" ><b>Jelsz&oacute; m&eacute;gegyszer:</b></td>
				<td align="right"><input type="password" name="data[password_again]" value="<?php echo $this->_tpl_vars['datas_register']['password_again']; ?>
" /></td>
			  </tr>
			  <tr>
				<td colspan="3" align="center"><img alt="Regisztrálok" src="i/reg_button.gif" class="ql" onclick="document.register.submit();" /></td>
			  </tr>
			  <tr>
				<td colspan="3" align="center"><script>mx_hp('http://www.hirek.hu/', 'Legyen a kezdőoldalam!', 'Beállítás kezdőoldalnak: húzza ezt a linket a Kezdőoldal ikonra');</script></td>
			  </tr>
			</table>
			</form>
		</div>
	</div>
    </div></div></div></div></div></div></div></div>
</div>
</div>
</div>
</body>
</html>