<?php /* Smarty version 2.6.6, created on 2010-05-05 09:30:52
         compiled from index.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<body> 
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header_search.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menu.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php if ($this->_tpl_vars['valasz']): ?>
<div id="igaze" style="display:none;position:absolute;top:100px;left:400px;z-index:1000;">
<script type="text/javascript">
    embededflash('igaz-e','<?php echo $this->_tpl_vars['var']->baseurl; ?>
fixbox/igaze/flash/igaz-e.swf','400','320','#555555','transparent','tipus=<?php echo $this->_tpl_vars['valasz']; ?>
&katt=javascript:bezar();', '<?php echo $this->_tpl_vars['var']->baseurl; ?>
fixbox/igaze/');
    function valaszFlash() <?php echo '{'; ?>

        var l = (document.documentElement.clientWidth ? document.documentElement.clientWidth : document.body.clientWidth)/2-400/2;
        var t = (document.documentElement.clientHeight ? document.documentElement.clientHeight : document.body.clientHeight)/2-320/2;
        var o = document.getElementById('igaze');
        <?php echo '
            o.style.left=l+\'px\';
            o.style.top = t+\'px\';
            o.style.display=\'block\';
            igazeToTop(1000);
        '; ?>

    <?php echo '}'; ?>

    <?php echo '
    function bezar() {document.getElementById("igaze").style.display=\'none\';}
    function igazeToTop(i) {
        var zi = 2000-i;
        var o = document.getElementById(\'igaze\');
        if (o) {
            o.style.zIndex = zi;
            if (i) {
                i--;
                setTimeout(\'igazeToTop(\'+i+\')\', 200);
            }
        }
    }
    '; ?>

</script>
</div>
<?php endif; ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td id="manager" style="display:none;" valign="top">
        <div style="display:block;top:157px;left:195px;z-index:10000;position:absolute;"><img src="i/close.gif" style="cursor:pointer;" onClick="hideManager();" /></div>
        <div style="width:220px;">
            <div class="tabs" style="height:27px;">&nbsp;</div>
            <div style="padding:5px;position:relative;top:-31px;z-index:200;">
    <div class="topboxcont11"><div class="bottombox11"><div class="leftbox11"><div class="rightbox11"><div class="blbox11"><div class="brbox11"><div class="tlbox11"><div class="trbox11">
                    <div>
                        <ul>
                            <li><a id="uj_hirforras_felvetele" href="#" onClick="showAddNewFeedBox(this);return false;"><b>&Uacute;j h&iacute;rforr&aacute;s felv&eacute;tele</b></a></li>
                            <li><img src="i/ha.gif" align="texttop"> <a id="show_my_feeds" href="#" onclick="showMyFeeds(this);return false;"><b>H&iacute;rforr&aacute;saim</b></a></li>
                            <li><img src="i/ha.gif" align="texttop"> <A id="kiemelt_hirforrasok" href="#" onclick="showFeaturedFeeds(this);return false;"><b>Kiemelt h&iacute;rforr&aacute;sok</b></A></li>
                            <li><img src="i/ha.gif" align="texttop"> <A id="tematikus_hirforrasok" href="#" onClick="getFeedCategories(this);return false;"><b>Tematikus h&iacute;rforr&aacute;sok</b></A></li>
                            <li style="display:none;"><ul style="margin-left:10px;" id="feedDirectory"><li>&nbsp;</li></ul></li>
                            <li><img src="i/ha.gif" align="texttop"> <A id="kulcsszavas_hirforrasok" href="#" onClick="showAddNewFeedBox(this, 1);return false;"><b>Kulcsszavas hírforrások</b></A></li>
                            <li><img src="i/search.gif" align="texttop"> <A id="kereso" href="#" onClick="WebSearch.show();return false;"><b>Keres&#337;</b></A></li>
                            <?php if ($this->_tpl_vars['__user_id'] != -1): ?>
                            <li><img src="i/note.gif" align="texttop"> <A id="jegyzet" href="#" onClick="WebNote.show();return false;"><b>Jegyzet</b></A></li>
                            <?php endif; ?>
                            <li><img src="i/helpmovie.gif" width="16" height="16" align="texttop"> <A id="utmutato" href="#" onClick="addNewHtmlBox('Súgó - képekben', '', '', 1, 1);return false;"><b>S&uacute;g&oacute; k&eacute;pekben</b></A></li>
                        </ul>
                    </div>
                    <div id="feed_actions" style="position:absolute;display:none;z-index:1000;" onmouseover="myFeedActionOver(0);" onmouseout="myFeedActionOut();"><img src="i/ha.gif" ></div>
                    <div id="feed_action_links" class="feedActionList" style="position:absolute;display:none;z-index:1000;" onmouseover="myFeedActionOver(1);" onmouseout="myFeedActionOut();">
                        <table>
                        <tr>
                            <td valign="top"><a class="ql" onclick="showFeedRename();" >átnevezés</a><br>
                                <a class="ql" onclick="showFeedDelete();">törlés</a>
                            </td>
                            <td>
                                <div id="feed_action_rename" style="display:none;width:260px;" >
                                    <input size="30" id="renameFeedField" value="" />&nbsp;<input type="button" onclick="renameFeed(document.getElementById('renameFeedField').value);" value="OK">
                                </div>
                                <div id="feed_action_delete" style="display:none;width:260px;" >biztos törlöd a hirforrást?&nbsp;<input type="button" value="Igen" onclick="deleteFeed();">&nbsp;<input type="button" value="Mégsem" onclick="myFeedActionOut();" ></div>
                                <div id="feed_action_response" style="display:none;"></div>
                            </td>
                        </tr>
                        </table>
                    </div>
                </div></div></div></div></div></div></div></div>
            </div>
        </div>
	</td>
    <td valign="top" style="width:100%;">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="tabs">
			<tr>
				<td style="padding-left:5px;" align="left" class="nwr">
					<div id="reg"><img src="i/in.gif" align="absmiddle" />
                    <?php if ($this->_tpl_vars['__user_email'] == '' || $this->_tpl_vars['__user_id'] == -1): ?>
                        <a style="font-weight:bold;" id="login" href="<?php if ($this->_tpl_vars['var']->rewrite_engine):  echo $this->_tpl_vars['var']->rewrite_baseurl;  echo $this->_tpl_vars['var']->page_url_login;  else: ?>login.php<?php endif; ?>">Bejelentkez&eacute;s</a> / <a style="font-weight:bold;" id="register" href="<?php if ($this->_tpl_vars['var']->rewrite_engine):  echo $this->_tpl_vars['var']->rewrite_baseurl;  echo $this->_tpl_vars['var']->page_url_register;  else: ?>register.php<?php endif; ?>">Regisztr&aacute;ci&oacute;</a>
                    <?php else: ?>
                        <a style="font-weight:bold;" href="logout.php">Kijelentkez&eacute;s <?php echo $this->_tpl_vars['__user_email']; ?>
</a>
                    <?php endif; ?>
                    </div>
				</td>
				<td width="100%">
					<div id="tabs">Saj&aacute;t oldal</div>
					<div style="padding:4px;white-space:nowrap;float:left;">&nbsp; <a href="#" id="uj_oldal" onclick="<?php if ($this->_tpl_vars['__user_id'] != -1): ?>createNewTab();<?php endif; ?>return false;">&Uacute;j oldal</a></div>
				</td>
				<td class="nwr" ><div id="beallitas"><img align="absmiddle" src="i/in2.gif" />&nbsp;<script>mx_hp('http://www.hirek.hu/', 'Legyen a kezdőoldalam!', 'Beállítás kezdőoldalnak: húzza ezt a linket a Kezdőoldal ikonra');</script></div></td>
                <td class="nwr"><div id="sugo"><a href="#" onClick="showHelp();return false;"><img src="i/help.png" align="absmiddle" />&nbsp;S&uacute;g&oacute; - r&eacute;szletesen</a></div></td>
			</tr>
		</table>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="margin:5px 0 0 5px;">
	    <tr>
            <td style="padding-left:5px;"><div id="mainContent"></div></td>	
        </tr>
        </table>
    </td>
  </tr>
</table>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php if ($this->_tpl_vars['endscript']): ?>
<script type="text/javascript" language="javascript"><?php echo $this->_tpl_vars['endscript']; ?>
</script>
<?php endif; ?>
<?php echo $this->_tpl_vars['maxi_hirekson']; ?>

</body>
</html>