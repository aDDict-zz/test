<?php /* Smarty version 2.6.6, created on 2009-12-19 00:05:58
         compiled from forgott_pass.html */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>H&iacute;rek.hu - Elfelejtettem a jelszavam</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="templates/main3.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div style="margin:auto;margin-top:200px;width:300px;">
	<div class="box">
		<div class="head" style="cursor:default ">			
			<h1>&nbsp;Elfelejtettem a jelszavam</h1>
		</div>
		<div class="content">
			<form name="forgott" method="post" action="login.php">
			<input type="hidden" name="action" value="forgott" />
			<table border="0" cellspacing="0" cellpadding="5" align="center">
			  <?php if ($this->_tpl_vars['error']): ?>
			  <tr>
			  	<td colspan="2">
					<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['error']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
						<span class="error"><?php echo $this->_tpl_vars['error'][$this->_sections['i']['index']]; ?>
</span><br />
					<?php endfor; endif; ?>
				</td>
			  </tr>
			  <?php endif; ?>
			  <tr>
				<td>E-mail c&iacute;m:</td>
				<td><input type="text" name="data[email]" value="<?php echo $this->_tpl_vars['datas']['email']; ?>
" /></td>
			  </tr>			  
			  <tr>
				<td colspan="2" align="center"><input type="submit" value="K&eacute;rem" /></td>
			  </tr>
			  <tr>
				<td colspan="2" align="center"><a href="login.php">Vissza a bel&eacute;p&eacute;shez</a></td>
			  </tr>
			</table>
			</form>
		</div>
	</div>
	
	</div>
</div>
</body>
</html>