<?php /* Smarty version 2.6.6, created on 2010-03-20 22:31:36
         compiled from highlighted_feeds.html */ ?>
<script language="javascript" type="text/javascript" src="../js/common6.js"></script>
<script language="javascript" type="text/javascript" src="../js/admin/common.js"></script>
<?php echo '
<script language="javascript" type="text/javascript">
var timeOut = "";

var doFeedSearch = function(obj){ //launch the search process
	window.clearTimeout(timeOut);
	if(obj.value.length>=3) timeOut = setTimeout("searchFeeds()", 1000);
}

var searchFeeds = function(){
	var rssFinder = document.getElementById(\'rss_finder\');
	var feeds = document.getElementById(\'feeds\');
	
	var showFeeds = function(r){
		var xmlDoc = null;
		if(document.all){			
			xmlDoc = new ActiveXObject("Microsoft.XMLDOM")
			xmlDoc.load(r.responseXML);			
		}else{
			xmlDoc = loadXML(r.responseText);
		}		
		var opts = xmlDoc.getElementsByTagName(\'option\');											
		populateSelect(feeds, opts);		
	}
	var pars = "action=search_feeds&q=" + encodeURIComponent(rssFinder.value);		
	var url = "../ajax/admin/highlighted_feeds.php";
	Ajax.send(url, "POST", pars, showFeeds, null);
}

var getHighlightedFeeds = function(){
	var highlighted = document.getElementById(\'highlighted\');
	
	var showFeeds = function(r){
		var xmlDoc = null;
		if(document.all){			
			xmlDoc = new ActiveXObject("Microsoft.XMLDOM")
			xmlDoc.load(r.responseXML);			
		}else{
			xmlDoc = loadXML(r.responseText);
		}
					
		var opts = xmlDoc.getElementsByTagName(\'option\');													
		populateSelect(highlighted, opts);		
	}
	
	var pars = "action=get_feeds";		
	var url = "../ajax/admin/highlighted_feeds.php";
	Ajax.send(url, "POST", pars, showFeeds, null);
}
var removeFeed = function(s){
	s.remove(s.selectedIndex);
}
var addFeed = function(s){
	var highlighted = document.getElementById(\'highlighted\');
	var id = s.options[s.selectedIndex].value;

	for(var i=0;i<highlighted.options.length;i++){
		if(id==highlighted.options[i].value){
			alert(\'Ez a feed mar szerepel a kiemeltek kozott!\')
			return false;
		}
	}
	var option = document.createElement(\'option\');
	option.text = s.options[s.selectedIndex].text;
	option.value = s.options[s.selectedIndex].value;		
	if(option.value!="undefined"){
		try{
			highlighted.add(option, null);
		}catch(ex){
			highlighted.add(option);
		}
	}
}
window.onload = getHighlightedFeeds;
var doSubmit = function(){
	var highlighted = document.getElementById(\'highlighted\');	
	selectAll(highlighted.options);
	document.hl.submit();
}
</script>
'; ?>

<form name="hl" method="post" action="index.php?id=<?php echo $this->_tpl_vars['id']; ?>
&sub_id=<?php echo $this->_tpl_vars['sub_id']; ?>
" onsubmit="return false;">
<input type="hidden" name="action" value="highlighted_feeds" />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="page_title">Kiemelt h&iacute;rforr&aacute;sok</td>
  </tr>
  <tr>
    <td>
		<?php ob_start(); ?>
		<table border="0" cellspacing="0" cellpadding="5">
		  <?php if ($this->_tpl_vars['error'] != ''): ?>
		  <tr>
		  	<td colspan="2" style="color:#FF0000;font-weight:bold"><?php echo $this->_tpl_vars['error']; ?>
</td>
		  </tr>
		  <?php endif; ?>
		  <tr>
			<td>				
			</td>
			<td>
				RSS kereső:<br />
				<input type="text" name="rss_finder" id="rss_finder" onKeyUp="doFeedSearch(this);" />
			</td>
		  </tr>
		  <tr>
			<td>
				<select name="highlighted[]" id="highlighted" multiple size="10" ondblclick="removeFeed(this);"></select>
			</td>
			<td>
				<select name="feeds[]" id="feeds" multiple size="10" ondblclick="addFeed(this);"></select>
			</td>
		  </tr>
		</table>
		<?php $this->_smarty_vars['capture']['highlighted_feeds'] = ob_get_contents(); ob_end_clean(); ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.html", 'smarty_include_vars' => array('title' => "",'content' => $this->_smarty_vars['capture']['highlighted_feeds'],'extra' => "width=100%")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</td>
  </tr>
  <tr>
    <td><input type="button" name="" value="V&eacute;grehajt" class="button" onClick="doSubmit();" /></td>
  </tr>
</table>
</form>