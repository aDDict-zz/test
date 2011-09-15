<?php /* Smarty version 2.6.6, created on 2009-12-18 20:17:11
         compiled from feed_list_empty.html */ ?>
<?php if ($this->_tpl_vars['user_id'] == -1): ?>
<div>
Saját hírforrás felvételéhez kérjük regisztráljon vagy jelentkezzen be!<br>
<a style="font-weight:bold;" href="login.php">Bejelentkez&eacute;s</a> / <a style="font-weight:bold;" href="regiseter.php">Regisztr&aacute;ci&oacute;</a>
</div>
<?php else: ?>
<div>
Még nem vett fel saját hírforrást.<br>
Saját hírforrás felviteléhez kattintson <a style="font-weight:bold;" href="#" onclick="showAddNewFeedBox();return false;">ide</a>
</div>
<?php endif; ?>