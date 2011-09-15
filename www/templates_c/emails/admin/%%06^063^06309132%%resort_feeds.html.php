<?php /* Smarty version 2.6.6, created on 2010-01-19 15:01:54
         compiled from resort_feeds.html */ ?>
<script language="javascript" type="text/javascript" src="../js/common6.js"></script>
<script language="javascript" type="text/javascript" src="../js/admin/common.js"></script>
<?php echo '
<script language="javascript" type="text/javascript">
var getCategorizedFeeds = function(catID){
	var rssCategorized = document.getElementById(\'rss_categorized\');
	
	var showFeeds = function(r){		
		var xmlDoc = null;
		if(document.all){			
			xmlDoc = new ActiveXObject("Microsoft.XMLDOM")
			xmlDoc.load(r.responseXML);			
		}else{
			xmlDoc = loadXML(r.responseText);
		}
					
		var opts = xmlDoc.getElementsByTagName(\'option\');											
		populateSelect(rssCategorized, opts);
	}
	var pars = "action=get_categories_feeds&cat_id=" + catID;		
	var url = "../ajax/admin/resort_feeds.php";
	Ajax.send(url, "POST", pars, showFeeds, null);
}
var getCategories = function(){
	var rssCategories = document.getElementById(\'rss_categories\');	
	var showCategories = function(r){		
		var xmlDoc = null;
		if(document.all){			
			xmlDoc = new ActiveXObject("Microsoft.XMLDOM")
			xmlDoc.load(r.responseXML);			
		}else{
			xmlDoc = loadXML(r.responseText);
		}
					
		var opts = xmlDoc.getElementsByTagName(\'option\');											
		populateSelect(rssCategories, opts);
		getCategorizedFeeds(rssCategories.options[0].value);
	}
	var pars = "action=get_categories";		
	var url = "../ajax/admin/resort_feeds.php";
	Ajax.send(url, "POST", pars, showCategories, null);
}
var timeOut = "";

var doFeedSearch = function(obj){ //launch the search process		
	window.clearTimeout(timeOut);
	if(obj.value.length>=3) timeOut = setTimeout("searchFeeds()", 1000);
}

var searchFeeds = function(){
	var rssFinder = document.getElementById(\'rss_finder\');
	var rssUnCategorized = document.getElementById(\'rss_uncategorized\');
	
	var showFeeds = function(r){
		var xmlDoc = null;
		if(document.all){			
			xmlDoc = new ActiveXObject("Microsoft.XMLDOM")
			xmlDoc.load(r.responseXML);			
		}else{
			xmlDoc = loadXML(r.responseText);
		}		
		var opts = xmlDoc.getElementsByTagName(\'option\');											
		populateSelect(rssUnCategorized, opts);		
	}
	var pars = "action=search_feeds&q=" + encodeURIComponent(rssFinder.value);		
	var url = "../ajax/admin/resort_feeds.php";
	Ajax.send(url, "POST", pars, showFeeds, null);
}

var doSubmit = function(){
	var rssCategorized = document.getElementById(\'rss_categorized\');	
	selectAll(rssCategorized.options);
	document.resort_feeds.submit();
}

var removeFeed = function(s){
	s.remove(s.selectedIndex);
}
var addFeed = function(s){
	var rssCategorized = document.getElementById(\'rss_categorized\');
	var id = s.options[s.selectedIndex].value;

	for(var i=0;i<rssCategorized.options.length;i++){
		if(id==rssCategorized.options[i].value){
			alert(\'Ez a feed mar szerepel ebben a kategoriaban!\')
			return false;
		}
	}
	var option = document.createElement(\'option\');
	option.text = s.options[s.selectedIndex].text;
	option.value = s.options[s.selectedIndex].value;		
	if(option.value!="undefined"){
		try{
			rssCategorized.add(option, null);
		}catch(ex){
			rssCategorized.add(option);
		}
	}
}
window.onload = getCategories;

</script>
'; ?>

<form name="resort_feeds" method="post" action="index.php?id=<?php echo $this->_tpl_vars['id']; ?>
&sub_id=<?php echo $this->_tpl_vars['sub_id']; ?>
" onSubmit="return false;">
<input type="hidden" name="action" value="resort_feeds" />

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="page_title">RSS-ek besorol&aacute;sa</td>
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
				RSS kateg&oacute;ri&aacute;k:<br />
				<select name="rss_categories" id="rss_categories" onChange="getCategorizedFeeds(this.options[this.selectedIndex].value)"></select>
			</td>
			<td>
				RSS kereső:<br />
				<input type="text" name="rss_finder" id="rss_finder" onKeyUp="doFeedSearch(this);" />
			</td>
		  </tr>
		  <tr>
			<td>
				<select name="rss_cagorized[]" id="rss_categorized" multiple size="10" ondblclick="removeFeed(this);"></select>
			</td>
			<td>
				<select name="rss_uncagorized[]" id="rss_uncategorized" multiple size="10" ondblclick="addFeed(this);"></select>
			</td>
		  </tr>
		</table>
		<?php $this->_smarty_vars['capture']['resort_feeds'] = ob_get_contents(); ob_end_clean(); ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.html", 'smarty_include_vars' => array('title' => "",'content' => $this->_smarty_vars['capture']['resort_feeds'],'extra' => "width=100%")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</td>
  </tr>
  <tr>
  	<td><input type="button" name="" value="V&eacute;grehajt" class="button" onClick="doSubmit()" /></td>
  </tr>
</table>
</form>