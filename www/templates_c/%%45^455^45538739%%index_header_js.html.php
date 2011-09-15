<?php /* Smarty version 2.6.6, created on 2009-12-18 15:47:11
         compiled from index_header_js.html */ ?>
<script language="javascript" type="text/javascript" src="js/lang2.js"></script>
<!-- script language="javascript" type="text/javascript" src="js/prototype.js"></script -->
<script language="javascript" type="text/javascript" src="js/feed7.js"></script>
<script language="javascript" type="text/javascript" src="js/websearch2.js"></script>
<script language="javascript" type="text/javascript" src="js/webnote3.js"></script>
<script language="javascript" type="text/javascript">
var tabs = null;
var news_id = <?php if ($this->_tpl_vars['news_id']):  echo $this->_tpl_vars['news_id'];  else: ?>0<?php endif; ?>;
var newslink_from = <?php if ($this->_tpl_vars['newslink_from']): ?>'<?php echo $this->_tpl_vars['newslink_from']; ?>
'<?php else: ?>''<?php endif; ?>;
<?php echo '
window.onload = function(){	
	tabs = document.getElementById(\'tabs\');
	var pars = "action=get_tabs";
	var url = "ajax/page.php";		
    attachHints();
    parseQueryString();
	Ajax.send(url, "POST", pars, createTabs, ';  if ($this->_tpl_vars['page_id']): ?>"<?php echo $this->_tpl_vars['page_id']; ?>
"<?php else: ?>null<?php endif;  echo ');		
'; ?>

    <?php echo $this->_tpl_vars['onloadscript']; ?>

<?php echo '

}
var searchSelected = \'lnews\';
var changeSearch = function(type){
	document.getElementById(searchSelected).className = \'\';
	document.getElementById(type).className = \'selected\';
	searchSelected = type;
	var hiddenInputs = document.getElementById(\'hiddenInputs\');
	var searchText = document.getElementById(\'searchText\');
	var searchForm = document.getElementById(\'searchForm\');
    while (hiddenInputs.childNodes.length) hiddenInputs.removeChild(hiddenInputs.childNodes[0]);
	switch(type){
		case \'lnews\':
			var input = document.createElement(\'input\');
			input.type = \'hidden\';
			input.name = \'p\';
			input.value = \'Keres\';
			hiddenInputs.appendChild(input);
			
			searchText.name = \'q\';
			searchForm.method = \'GET\';
			searchForm.action= \'search.php\';
            searchForm.rweb.checked = false;
			break;
		case \'lweb\':
			var input = document.createElement(\'input\');
			input.type = \'hidden\';
			input.name = \'page\';
			input.value = \'1\';
			hiddenInputs.appendChild(input);
			
			input = document.createElement(\'input\');
			input.type = \'hidden\';
			input.name = \'page_number\';
			input.value = \'2\';
			hiddenInputs.appendChild(input);
			
			input = document.createElement(\'input\');
			input.type = \'hidden\';
			input.name = \'lap\';
			input.value = \'0\';
			hiddenInputs.appendChild(input);
			
			input = document.createElement(\'input\');
			input.type = \'hidden\';
			input.name = \'searchmode\';
			input.value = \'1\';
			hiddenInputs.appendChild(input);
			
			input = document.createElement(\'input\');
			input.type = \'hidden\';
			input.name = \'akt\';
			input.value = \'0\';
			hiddenInputs.appendChild(input);
			
			input = document.createElement(\'input\');
			input.type = \'hidden\';
			input.name = \'host\';
			input.value = \'kurzor.hu\';
			hiddenInputs.appendChild(input);
			
			searchText.name = \'word\';
			searchForm.method = \'POST\';
			searchForm.action= \'http://kurzor.hu/search.php\';
            searchForm.rnews.checked = false;
			break;
		case \'limage\':
			hiddenInputs.innerHTML = \'\';
			
			searchText.name = \'q\';
			searchForm.method = \'GET\';
			searchForm.action= \'http://kurzor.hu/kepkereso/\';
			break;
		case \'lproducts\':
			hiddenInputs.innerHTML = \'\';
			var input = document.createElement(\'input\');
			input.type = \'hidden\';
			input.name = \'pn\';
			input.value = \'search\';
			hiddenInputs.appendChild(input);
			
			input = document.createElement(\'input\');
			input.type = \'hidden\';
			input.name = \'host\';
			input.value = \'depo.hu\';
			hiddenInputs.appendChild(input);
			
			input = document.createElement(\'input\');
			input.type = \'hidden\';
			input.name = \'searchcat\';
			input.value = \'0\';
			hiddenInputs.appendChild(input);			
			
			searchText.name = \'searchstring\';
			searchForm.method = \'GET\';
			searchForm.action= \'http://www2.depo.hu/\';
			break;
	}
}
</script>
'; ?>
