<?php /* Smarty version 2.6.6, created on 2009-12-18 19:38:43
         compiled from search_box.html */ ?>
	<div class="blue" alt="bid_<?php echo $this->_tpl_vars['sb_bid']; ?>
">
<div class="topboxcont"><div class="bottombox"><div class="leftbox"><div class="rightbox"><div class="blbox"><div class="brbox"><div class="tlbox"><div class="trbox">
		<div class="head">
			<div class="edit"><a href="#"></a> <a href="#"></a><a href="#" title="Doboz törlése"><img src="i/close.gif" align="absmiddle" alt="Doboz törlése"/></a></div>	
			<h1><a href="#"><img src="i/search.gif" style="margin-top:-5px;width:16px; height:16px;" align="absmiddle" /></a> <a href="">Keres&#337;<!-- (<?php echo $this->_tpl_vars['sb_bid']; ?>
)--></a></h1>
		</div>
		<div class="editContent">

		</div>	
		<div class="content" >
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td style="padding:5px 5px 0 5px;">
					<table border="0" cellspacing="0" cellpadding="0" >
					  <tr>
						<td style="font-weight:bold;padding:5px;border-width:1px;border-style:solid; border-color:#6d6e71;" class="ql" onClick="WebSearch.showSearchForm('searchKurzor')">Kurzor</td>
						<td style="font-weight:bold;padding:5px;border-width:1px 1px 1px 0;border-style: solid;border-color: #6d6e71;" class="ql" onClick="WebSearch.showSearchForm('searchYahoo')">Yahoo</td>			
						<td style="font-weight:bold;padding:5px;border-width:1px 1px 1px 0; border-style: solid; border-color : #6d6e71;" class="ql" onClick="WebSearch.showSearchForm('searchTango')">Tang&oacute;</td>
					  </tr>
					</table>
				</td>
			  </tr>
			  <tr>
				<td style="padding:5px 5px 0 5px;">
					<table width="100%" border="0" cellspacing="0" cellpadding="5" id="searchKurzor" >
					  <tr>
						<td>
							<form name="kurzor" method="get" target="_blank" action="http://kurzor.hu/talalat/" accept-charset="iso-8859-2">
								<img src="i/kurzor.gif" alt="" align="left" />&nbsp;
								<input type="text" name="q" value="" />&nbsp;
								<input type="submit" value="Keres" />
							</form>
						</td>
					  </tr>
					</table>
					<table width="100%" border="0" cellspacing="0" cellpadding="5" id="searchYahoo"  style="display:none; ">
					  <tr>
						<td>
							<form name="yahoo" method="get" target="_blank" action="http://search.yahoo.com/search">
								<img src="i/yahoo.gif" alt="" align="left" />&nbsp;
								<input type="text" name="p" value="" />&nbsp;
								<input type="submit" value="Keres" />
							</form>
						</td>
					  </tr>
					</table>					
					<table width="100%" border="0" cellspacing="0" cellpadding="5" id="searchTango"  style="display:none; ">
					  <tr>
						<td>
							<form name="tango" method="get" target="_blank" action="http://tango.hu/search.php" accept-charset="iso-8859-2">
								<img src="i/tango.gif" alt="" align="left" />&nbsp;
								<input type="text" name="q" value="" />&nbsp;
								<input type="submit" value="Keres" />
							</form>
						</td>
					  </tr>
					</table>
				</td>
			  </tr>
			  <tr>
			  	<td>&nbsp;</td>
			  </tr>
			</table>

		</div>
</div></div></div></div></div></div></div></div>
	</div>



		