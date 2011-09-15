<?php /* Smarty version 2.6.6, created on 2009-12-24 09:24:27
         compiled from new_feed_box.html */ ?>
<div class="topboxcont"><div class="bottombox"><div class="leftbox"><div class="rightbox"><div class="blbox"><div class="brbox"><div class="tlbox"><div class="trbox">
			<div class="edit" style="display:block; "><a href="#" onClick="removeNewFeedBox();return false;"><img src="i/close.gif" align="absmiddle" /></a></div>	
			<div style="padding:10px;">
                <?php if (! $this->_tpl_vars['keywords']): ?>
                <p><b>H&iacute;rforr&aacute;s URL:</b></p>
                <input type="text" size="40" id="newFeedURL"/> <input type="button" value="Felvisz" onClick="addNewFeedBoxByFeed(document.getElementById('newFeedURL').value);" class="button" />
                <?php else: ?>
                <p><b>H&iacute;rforr&aacute;s kulcssz&oacute; alapj&aacute;n:</b></p>
                <p>H&iacute;rforr&aacute;s megnevez&eacute;se: <input type="text" size="20" id="newFeedKeywordsTitle"/></p>
                <p>Kulcssz&oacute;: <input type="text" id="newFeedKeywords"/> <input type="button" value="Felvisz" onClick="addNewFeedBoxByKeywords(document.getElementById('newFeedKeywordsTitle').value, document.getElementById('newFeedKeywords').value);" class="button" /></p>
                <?php endif; ?>
                <p id="feedVerifyProgress"></p>
			</div>
</div></div></div></div></div></div></div></div>