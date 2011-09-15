<?php /* Smarty version 2.6.6, created on 2011-04-14 15:54:14
         compiled from fixed_categories.html */ ?>
<script language="javascript">
var columns = new Array();
var boxes = null;
<?php if (isset($this->_foreach['m'])) unset($this->_foreach['m']);
$this->_foreach['m']['total'] = count($_from = (array)$this->_tpl_vars['fcats']);
$this->_foreach['m']['show'] = $this->_foreach['m']['total'] > 0;
if ($this->_foreach['m']['show']):
$this->_foreach['m']['iteration'] = 0;
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['m']['iteration']++;
        $this->_foreach['m']['first'] = ($this->_foreach['m']['iteration'] == 1);
        $this->_foreach['m']['last']  = ($this->_foreach['m']['iteration'] == $this->_foreach['m']['total']);
?>
	boxes = new Array();
	<?php if (isset($this->_foreach['s'])) unset($this->_foreach['s']);
$this->_foreach['s']['total'] = count($_from = (array)$this->_tpl_vars['fcats'][$this->_tpl_vars['key']]);
$this->_foreach['s']['show'] = $this->_foreach['s']['total'] > 0;
if ($this->_foreach['s']['show']):
$this->_foreach['s']['iteration'] = 0;
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['i']):
        $this->_foreach['s']['iteration']++;
        $this->_foreach['s']['first'] = ($this->_foreach['s']['iteration'] == 1);
        $this->_foreach['s']['last']  = ($this->_foreach['s']['iteration'] == $this->_foreach['s']['total']);
?>
		boxes[<?php echo $this->_tpl_vars['k']; ?>
] = '<?php echo $this->_tpl_vars['i']; ?>
';		
	<?php endforeach; unset($_from); endif; ?>
	columns[<?php echo $this->_tpl_vars['key']; ?>
] = boxes;
<?php endforeach; unset($_from); endif; ?>
</script>
<script language="javascript" type="text/javascript" src="../js/common8.js"></script>
<script language="javascript" type="text/javascript" src="../js/admin/common.js"></script>
<?php echo '
<script language="javascript" type="text/javascript">
var IE = document.all?true:false;
var moduleGhost = document.createElement("div");
moduleGhost.id = "moduleGhost";
moduleGhost.style.borderStyle = \'dashed\';
moduleGhost.style.borderWidth = \'1px\';
moduleGhost.style.borderColor = \'#c00\';

var editLink = function(o, linkID, catID){
	var ul = o.parentNode.parentNode.parentNode;
	var showEditLink = function(r){
		o.parentNode.childNodes[2].onclick = function(){removeLinkFalse()};
		
		
		var li = document.createElement(\'li\');
		o.onclick = function(){hideLinkEdit(o, li, ul, linkID, catID);}
		li.innerHTML = r.responseText;
		ul.insertBefore(li, o.parentNode.parentNode.nextSibling);				
		
		if(IE){
			var editedLink = o.parentNode.parentNode.childNodes[1];
		}else{
			var editedLink = o.parentNode.parentNode.childNodes[3];
		}		

		var linkName = li.childNodes[0].rows[0].cells[1].childNodes[0];
		var linkURL = li.childNodes[0].rows[1].cells[1].childNodes[0];
		var linkTitle = li.childNodes[0].rows[2].cells[1].childNodes[0];
		var updateButton = li.childNodes[0].rows[3].cells[0].childNodes[0];
		
		updateButton.onclick = function(){
			editedLink.href = linkURL.value;
			editedLink.innerHTML = linkName.value;
			editedLink.title = linkTitle.value;
			
			var pars = "action=update_link&link_id=" + linkID + "&link_name=" + encodeURIComponent(linkName.value) + "&link_url=" + encodeURIComponent(linkURL.value) + "&link_title=" + encodeURIComponent(linkTitle.value);
			var url = "../ajax/admin/fixed_categories.php";
			Ajax.send(url, "POST", pars, null, null);
		}
				
	}
	
	var pars = "action=get_link&link_id=" + linkID;
	var url = "../ajax/admin/fixed_categories.php";
	Ajax.send(url, "POST", pars, showEditLink, null);
}
var hideLinkEdit = function(o, li, ul, linkID, catID){
	ul.removeChild(li);
	o.onclick = function(){editLink(o, linkID, catID);}
	o.parentNode.childNodes[2].onclick = function(){removeLink(o.parentNode.childNodes[2], linkID, catID)};
}
var removeLinkFalse = function(){
	alert(\'Kerem eloszor zarja be a szerkesztesi formot!\');
}
var removeLink = function(o, linkID, catID){
	if(confirm(\'Biztos benne?\')){
		var ul = o.parentNode.parentNode.parentNode;
		ul.removeChild(o.parentNode.parentNode);
		var pars = "action=remove_link&cat_id=" + catID + "&link_id=" + linkID;
		var url = "../ajax/admin/fixed_categories.php";
		Ajax.send(url, "POST", pars, null, null);
	}
	
}

var showCategories = function(){
	var pageID = parseInt(document.getElementById(\'pageID\').value);
	var show = function(r, data){
		var box = document.createElement(\'div\');
		box.className = \'box\';
		box.innerHTML = r.responseText;
		box.title = data[\'catID\'];
		
		var column = null;
		
		if(data[\'col\']==0){
			column = document.getElementById(\'0\');
			columnOneNr++;
		}else if(data[\'col\']==1){
			column = document.getElementById(\'1\');
			columnTwoNr++;
		}
	
		if(column!=null)	column.appendChild(box);
		new Box(box, data[\'catID\']);
	}
	for (var col in columns){		
		for(var catId in columns[col]){
			var data = new Array();
			if(true || pageID!=-1)data[\'col\'] = col;
				else data[\'col\'] = 1;
			data[\'catID\'] = columns[col][catId];
			var pars = "action=get_category&cat_id=" + columns[col][catId];
			var url = "../ajax/admin/fixed_categories.php";
			Ajax.send(url, "POST", pars, show, data);
		}
	}
	/*
	for(var i=0;i<columns.length;i++){
		for(var j=0;j<columns[i].length;j++){			
			var data = new Array();
			if(pageID!=-1)data[\'col\'] = i;
				else data[\'col\'] = 1;
			data[\'catID\'] = columns[i][j];
			var pars = "action=get_category&cat_id=" + columns[i][j];
			var url = "../ajax/admin/fixed_categories.php";
			Ajax.send(url, "POST", pars, show, data);
		}
	}
	*/
}


var showFixedCategories = function(){
	var categories = document.getElementById(\'categories\');
	var showCategories = function(r){		
		var xmlDoc = null;
		if(document.all){			
			xmlDoc = new ActiveXObject("Microsoft.XMLDOM")
			xmlDoc.load(r.responseXML);			
		}else{
			xmlDoc = loadXML(r.responseText);
		}		
		var opts = xmlDoc.getElementsByTagName(\'option\');											
		populateSelect(categories, opts);
	}
	
	var pars = "action=show_fixed_categories";		
	var url = "../ajax/admin/fixed_categories.php";
	Ajax.send(url, "POST", pars, showCategories, null);
}
var addNewCat = function(){
	var newCatName = document.getElementById(\'newCatName\').value;
	var showNewCat = function(r){
		var result = r.responseText;
		var categories = document.getElementById(\'categories\');
		
		if(result==\'false\') alert(\'Ilyen nevu kategoria mar van!\');
			else{
				var option = document.createElement(\'option\');								
				option.text = newCatName;
				option.value = result;		
				if(option.value!="undefined"){
					try{
						categories.add(option, null);
					}catch(e){				
						categories.add(option);				
					}
				}
			}
	}
	if(newCatName!=\'\'){		
		var pars = "action=add_new_cat&cat_name=" + encodeURIComponent(newCatName);		
		var url = "../ajax/admin/fixed_categories.php";
		Ajax.send(url, "POST", pars, showNewCat, null);
	}else alert(\'Ures kategoria nev!\');
}
var Box = function(box, boxID){
	var categories = document.getElementById(\'categories\');
	var catOptions = categories.options;
	
	this.dragEnd = function(){		
		var pageID = parseInt(document.getElementById(\'pageID\').value);
		if(true || pageID!=-1){
			var columnOne = document.getElementById(\'0\');
			var colOneNr = new Array();
			var columnTwo = document.getElementById(\'1\');
			var colTwoNr = new Array();
            var j=0;
            for(var i=0;i<columnOne.childNodes.length;i++){
                if(columnOne.childNodes[i].tagName==\'DIV\'){
                    colOneNr[j] = columnOne.childNodes[i].title;
                    j++;
                }	
            }					
            
            columnOneNr = colOneNr.length;
            if(!colOneNr[0])	colOneNr[0]=\'\';
			j=0;
			for(var i=0;i<columnTwo.childNodes.length;i++){
				if(columnTwo.childNodes[i].tagName == \'DIV\'){
					colTwoNr[j] = columnTwo.childNodes[i].title;
					j++;
				}	
			}
			if(colOneNr.length>3){
				alert(\'A kozepso oszlopban csak harom kategoria lehet!\\nSikertelen mentes!\');
				return;
			}
			if(colTwoNr.length>5){
				alert(\'A harmadik oszlopban csak ot kategoria lehet!\\nSikertelen mentes!\');
				return;
			}
			columnTwoNr = colTwoNr.length;
		}else{
			var columnOne = document.getElementById(\'1\');
			var colOneNr = new Array();
			if(colOneNr.length>5){
				alert(\'A harmadik oszlopban csak ot kategoria lehet!\\nSikertelen mentes!\');
				return;
			}
		}
		
						
		if(true || pageID!=-1)	var pars = "action=resort_page&column_one[]=" + colOneNr + "&column_two[]=" + colTwoNr + "&page_id=" + pageID;		
			else var pars = "action=resort_page&column_one[]=" + colOneNr + "&page_id=" + pageID;		
		var url = "../ajax/admin/fixed_categories.php";
		Ajax.send(url, "POST", pars, null, null);
	}
	
	this.init = function(){
		if(IE){
			var _box = box.childNodes[0];
			var boxHead = _box.childNodes[0];			
			var boxTitle = boxHead.childNodes[1];
			var editLink = boxHead.childNodes[0].childNodes[0];
			var closeImg = boxHead.childNodes[0].childNodes[2];
			var editContent = _box.childNodes[1];
			var catName = editContent.childNodes[0].rows[0].cells[1].childNodes[0];
			var catFavicon = editContent.childNodes[0].rows[1].cells[1].childNodes[0];
			var typeSelect = editContent.childNodes[0].rows[2].cells[1].childNodes[0];
			var updateButton = editContent.childNodes[0].rows[3].cells[0].childNodes[0]
			var content = _box.childNodes[2];
		
			var rssContainer = content.childNodes[0];			
			var rssInput = rssContainer.childNodes[1];
			var rssUpdateButton = rssContainer.childNodes[3];
			var htmlContainer = content.childNodes[1];				
			var htmlContent = htmlContainer.childNodes[2];			
			var htmlContentEdit = htmlContainer.childNodes[3].childNodes[0];

            var multirssContainer = content.childNodes[4];
            var keywordContainer = content.childNodes[5];
			
			
			var linkContainer = _box.childNodes[2].childNodes[2];
			
			var links = _box.childNodes[3].childNodes[0];
			var newLink = links.childNodes[0];
			var searchLink = links.childNodes[2];
			
			var newLinks = _box.childNodes[3].childNodes[1].childNodes[0];
			var linkName = newLinks.rows[0].cells[1].childNodes[0];
			var linkURL = newLinks.rows[1].cells[1].childNodes[0];
			var linkTitle = newLinks.rows[2].cells[1].childNodes[0];
			var newLinkButton = newLinks.rows[3].cells[0].childNodes[0];
			
			var searchLinks = _box.childNodes[3].childNodes[2].childNodes[0];
			var linkKeyword = searchLinks.rows[0].cells[0].childNodes[2];
			var linkResults = searchLinks.rows[1].cells[0].childNodes[2];
			
			var linkList = _box.childNodes[2].childNodes[2].childNodes[2];
		}else{
			var _box = box.childNodes[0];
			var boxHead = _box.childNodes[1];			
			var boxTitle = boxHead.childNodes[3];
			var editLink = boxHead.childNodes[1].childNodes[0];
			var closeImg = boxHead.childNodes[1].childNodes[2];
			var editContent = _box.childNodes[3];
			var catName = editContent.childNodes[1].rows[0].cells[1].childNodes[0];
			var catFavicon = editContent.childNodes[1].rows[1].cells[1].childNodes[0];
			var typeSelect = editContent.childNodes[1].rows[2].cells[1].childNodes[1];
			var updateButton = editContent.childNodes[1].rows[3].cells[0].childNodes[0]
			var content = _box.childNodes[5];
			var rssContainer = content.childNodes[1];
			var rssInput = rssContainer.childNodes[1];
			var rssUpdateButton = rssContainer.childNodes[3];
			var htmlContainer = content.childNodes[3];
			var htmlContent = htmlContainer.childNodes[3];
			var htmlContentEdit = htmlContainer.childNodes[5].childNodes[0];
            var multirssContainer = content.childNodes[7];
            var keywordContainer = content.childNodes[9];
			
			var linkContainer = _box.childNodes[5].childNodes[5];
			
			var links = _box.childNodes[7].childNodes[1];
			var newLink = links.childNodes[0];
			var searchLink = links.childNodes[2];
			
			var newLinks = _box.childNodes[7].childNodes[3].childNodes[1];
			var linkName = newLinks.rows[0].cells[1].childNodes[0];
			var linkURL = newLinks.rows[1].cells[1].childNodes[0];
			var linkTitle = newLinks.rows[2].cells[1].childNodes[0];
			var newLinkButton = newLinks.rows[3].cells[0].childNodes[0];
			
			var searchLinks = _box.childNodes[7].childNodes[5].childNodes[1];
			var linkKeyword = searchLinks.rows[0].cells[0].childNodes[3];
			var linkResults = searchLinks.rows[1].cells[0].childNodes[3];
			
			var linkList = _box.childNodes[5].childNodes[5].childNodes[3];
		}		
        var multirssFeed = new Array();
        var multirssTitle = new Array();
        var o;
        var multirssNewsperfeed;
        var multirssUpdateButton
        for(var i in multirssContainer.childNodes) {
            o = multirssContainer.childNodes[i];
            if (o.nodeName == "INPUT") {
                if (o.getAttribute("type") == "button") {
                    multirssUpdateButton = o;
                } else if (multirssTitle.length > multirssFeed.length) {
                    multirssFeed.push(o);
                } else {
                    multirssTitle.push(o);
                }
            } else if (o.nodeName == "SELECT") {
                multirssNewsperfeed = o;
            }
        }
        var keywordInput;
        var keywordUpdateButton;
        for(var i in keywordContainer.childNodes) {
            o = keywordContainer.childNodes[i];
            if (o.nodeName == "INPUT") {
                if (o.getAttribute("type") == "button") {
                    keywordUpdateButton = o;
                } else {
                    keywordInput = o;
                }
            }
        }
        
		
		linkResults.ondblclick = function(){
			var linkId = linkResults.options[linkResults.selectedIndex].value;
			var showNewLink = function(r){				
				if(r.responseText!=\'false\'){
					var li = document.createElement(\'li\');
					li.innerHTML = r.responseText;
					linkList.appendChild(li);
				}else alert(\'Ez a link mar szerepel a kategoriaban!\');
				
			}
			var pars = "action=add_link&link_id=" + linkId + "&cat_id=" + boxID;
			var url = "../ajax/admin/fixed_categories.php";
			Ajax.send(url, "POST", pars, showNewLink, null);
		}
		
		doSearch = function(q, linkResults){
			var showResults = function(r){
				var xmlDoc = null;
				if(document.all){			
					xmlDoc = new ActiveXObject("Microsoft.XMLDOM")
					xmlDoc.load(r.responseXML);			
				}else{
					xmlDoc = loadXML(r.responseText);
				}		
				
				var opts = xmlDoc.getElementsByTagName(\'option\');											
				populateSelect(linkResults, opts);								
			}
			
			var pars = "action=search_links&q=" + encodeURIComponent(q);
			var url = "../ajax/admin/fixed_categories.php";
			Ajax.send(url, "POST", pars, showResults, null);
		}
		var timeOut = null;
		linkKeyword.onkeyup = function(){
			window.clearTimeout(timeOut);
			if(linkKeyword.value.length>=3) timeOut = window.setTimeout(function(){doSearch(linkKeyword.value, linkResults);}, 1000);
		}
		newLinkButton.onclick = function(){
			
			var showNewLink = function(r){
				var result = r.responseText;
				if(result==\'1\'){
					alert(\'Ezt a linket mar felvitte valaki es a kategoria is tartlmazza!\');
					return;
				}
				if(result.substring(0, 1)==\'2\'){
					alert(\'Ezt a linket mar felvitte valaki, besorolasa megtortent!\');					
					result = result.substring(1);					
				}
				var li = document.createElement(\'li\');
				li.innerHTML = result;
				linkList.appendChild(li);
			}
			if(linkName.value!=\'\' && linkURL.value!=\'\'){
				var pars = "action=create_link&link_name=" + encodeURIComponent(linkName.value) + "&link_url=" + encodeURIComponent(linkURL.value) + \'&link_title=\' + encodeURIComponent(linkTitle.value) + \'&cat_id=\' + boxID;
				var url = "../ajax/admin/fixed_categories.php";
				Ajax.send(url, "POST", pars, showNewLink, null);
			}else alert(\'Hianyos adatok!\');
		}
		
		newLink.onclick = function(){showNewLink();}
		
		var showNewLink = function(){						
			newLinks.parentNode.style.display = \'block\';			
			newLink.onclick = function(){hideNewLink();}
			
			searchLinks.parentNode.style.display = \'none\';			
			searchLink.onclick = function(){showSearchLinks();}
		}
		
		var hideNewLink = function(){
			newLinks.parentNode.style.display = \'none\';
			newLink.onclick = function(){showNewLink();}
		}
		
		searchLink.onclick = function(){showSearchLinks();}
		
		var showSearchLinks = function(){
			searchLinks.parentNode.style.display = \'block\';			
			searchLink.onclick = function(){hideSearchLinks();}
			
			newLinks.parentNode.style.display = \'none\';
			newLink.onclick = function(){showNewLink();}
		}
		
		var hideSearchLinks = function(){
			searchLinks.parentNode.style.display = \'none\';
			searchLink.onclick = function(){showSearchLinks();}
		}
		
		htmlContentEdit.onclick = function(){showEditHTML();}
		
		var textArea = document.createElement(\'textarea\');		
		var showEditHTML = function(){
			htmlContentEdit.innerHTML = \'Szerkeszt&eacute;s v&eacute;ge\';
			htmlContentEdit.onclick = function(){hideEditHTML();}			
									
			var height = htmlContent.offsetHeight;			
			var width = htmlContent.offsetWidth;		
			
			textArea.style.height = \'100px\';
			textArea.style.width = width + \'px\';
			textArea.value = htmlContent.innerHTML;
			htmlContent.innerHTML = \'\';
			htmlContent.appendChild(textArea);
		}
		
		var hideEditHTML = function(){
			htmlContentEdit.innerHTML = \'HTML Szerkeszt&eacute;se\';
			htmlContentEdit.onclick = function(){showEditHTML();}			
						
			htmlContent.innerHTML = textArea.value;			
            var t = textArea.value;
			textArea.value = \'\';
//			var pars = "action=update_cat_html&html=" + encodeURIComponent(htmlContent.innerHTML) + "&cat_id=" + boxID;					
			var pars = "action=update_cat_html&html=" + encodeURIComponent(t) + "&cat_id=" + boxID;					
			var url = "../ajax/admin/fixed_categories.php";
			Ajax.send(url, "POST", pars, null, null);
		}
		
		keywordUpdateButton.onclick = function(){
            keywordInput.value = keywordInput.value.replace(/^\\s+|\\s+$/, \'\');
			if(keywordInput.value!=\'\'){
				var pars = "action=update_cat_keyword&keyword=" + encodeURIComponent(keywordInput.value) + "&cat_id=" + boxID;					
				var url = "../ajax/admin/fixed_categories.php";
				Ajax.send(url, "POST", pars, null, null);
			}else alert(\'Nem adott meg kulcsszót\');
            
        }
		multirssUpdateButton.onclick = function(){
            var feedurls = \'\';
            var feedtitles = \'\';
            var empty = true;
            for (var i in multirssFeed) {
                if (feedurls != \'\') feedurls += \':|:\';
                if (feedtitles != \'\') feedtitles += \':|:\';
                feedurls += multirssFeed[i].value;
                feedtitles += multirssTitle[i].value;
                if (multirssFeed[i].value != \'\') empty = false;

            }
			if(!empty){
				var pars = "action=update_cat_multirss&rss_titles=" + encodeURIComponent(feedtitles)+ "&rss_url=" + encodeURIComponent(feedurls) + "&newsperfeed="+multirssNewsperfeed.options[multirssNewsperfeed.selectedIndex].value +"&cat_id=" + boxID;					
				var url = "../ajax/admin/fixed_categories.php";
				Ajax.send(url, "POST", pars, null, null);
			}else alert(\'Nem adott meg az RSS URL-eket!\');
		}
		rssUpdateButton.onclick = function(){
			if(rssInput.value!=\'\'){
				var pars = "action=update_cat_rss&rss_url=" + encodeURIComponent(rssInput.value) + "&cat_id=" + boxID;					
				var url = "../ajax/admin/fixed_categories.php";
				Ajax.send(url, "POST", pars, null, null);
			}else alert(\'Nem adta meg az RSS URL-jet!\');
		}
		
		closeImg.onmousedown = stopHere;
		closeImg.onclick = function(){removeBox();}
		
		var removeBox = function(){
			if(confirm(\'Biztos benne?\')){
				var col = box.parentNode.id;
				var pageID = parseInt(document.getElementById(\'pageID\').value);
				box.parentNode.removeChild(box);
				if(true || pageID!=-1){
					var columnOne = document.getElementById(\'0\');
					var colOneNr = new Array();
					var columnTwo = document.getElementById(\'1\');
					var colTwoNr = new Array();
					j=0;
					for(var i=0;i<columnTwo.childNodes.length;i++){
						if(columnTwo.childNodes[i].tagName==\'DIV\'){
							colTwoNr[j] = columnTwo.childNodes[i].title;
							j++;
						}	
					}
					
					columnTwoNr = colTwoNr.length;
				}else{
					var columnOne = document.getElementById(\'1\');
					var colOneNr = new Array();					
				}
				
								
				var j=0;
				for(var i=0;i<columnOne.childNodes.length;i++){
					if(columnOne.childNodes[i].tagName==\'DIV\'){
						colOneNr[j] = columnOne.childNodes[i].title;
						j++;
					}	
				}					
				
				columnOneNr = colOneNr.length;
				if(!colOneNr[0])	colOneNr[0]=\'\';
				if(true || pageID!=-1)	var pars = "action=resort_page&column_one=" + colOneNr[0] + "&column_two[]=" + colTwoNr + "&page_id=" + pageID;		
					else var pars = "action=resort_page&column_one[]=" + colOneNr + "&page_id=" + pageID;		
				var url = "../ajax/admin/fixed_categories.php";
				Ajax.send(url, "POST", pars, null, null);
				/*
				var col = box.parentNode.id;
				var pageID = parseInt(document.getElementById(\'pageID\').value);				
				if(true || pageID!=-1){
					if(col == 0) columnOneNr--;
						else columnTwoNr--;
				}else columnOneNr--;
					
				box.parentNode.removeChild(box);
				var pageID = document.getElementById(\'pageID\').value;
				
				var pars = "action=remove_cat&page_id=" + pageID + "&cat_id=" + boxID;		
				var url = "../ajax/admin/fixed_categories.php";
				Ajax.send(url, "POST", pars, null, null);
				*/
			}
		}
		
		editLink.onmousedown = stopHere;
		editLink.onclick = function(){showEdit();}
		
		var showEdit = function(){
			editContent.style.display = \'block\';		
			editLink.onclick = function(){hideEdit();}	
		}
		
		var hideEdit = function(){
			editContent.style.display = \'none\';			
			editLink.onclick = function(){showEdit();}
		}
		updateButton.onclick = function(){modifyCat();}
		
		var modifyCat = function(){
			if(catName.value!=\'\'){
				boxTitle.innerHTML = catName.value;
				for(var i=0;i<catOptions.length;i++){
					if(catOptions[i].value==boxID){
						catOptions[i].text = catName.value;
						break;
					}
				}
				
				switch(typeSelect.options[typeSelect.selectedIndex].value){
					case \'1\':
						rssContainer.style.display = \'block\';
						htmlContainer.style.display = \'none\';
						linkContainer.style.display = \'none\';
						links.style.display = \'none\';
                        multirssContainer.style.display = \'none\';
                        keywordContainer.style.display = \'none\';
						break;
					case \'2\':
						rssContainer.style.display = \'none\';
						htmlContainer.style.display = \'block\';
						linkContainer.style.display = \'block\';
						links.style.display = \'block\';
                        multirssContainer.style.display = \'none\';
                        keywordContainer.style.display = \'none\';
						break;	
					case \'3\':
						rssContainer.style.display = \'none\';
						htmlContainer.style.display = \'none\';
						linkContainer.style.display = \'none\';
						links.style.display = \'none\';
                        multirssContainer.style.display = \'block\';
                        keywordContainer.style.display = \'none\';
						break;	
					case \'5\':
						rssContainer.style.display = \'none\';
						htmlContainer.style.display = \'none\';
						linkContainer.style.display = \'none\';
						links.style.display = \'none\';
                        multirssContainer.style.display = \'none\';
                        keywordContainer.style.display = \'block\';
						break;	
				}
				var pars = "action=update_cat&cat_name=" + encodeURIComponent(catName.value) + "&cat_favicon=" + encodeURIComponent(catFavicon.value) + "&cat_id=" + boxID + "&type=" + typeSelect.options[typeSelect.selectedIndex].value;
				var url = "../ajax/admin/fixed_categories.php";
				Ajax.send(url, "POST", pars, null, null);
			}
		}
				
		Drag.init(boxHead, box);
		new DragDrop(box, this);
        
	}
	
	this.init();
}
var columnOneNr = 0;
var columnTwoNr = 0;

var insertCategory = function(s){
	var catID = s.options[s.selectedIndex].value;
	var catName = s.options[s.selectedIndex].text;
	var pageID = parseInt(document.getElementById(\'pageID\').value);
	
	var column = null;
	var row = null;
	
	if(true || pageID!=-1){
		if(columnOneNr==0){
			column = document.getElementById(\'0\');
			row = columnOneNr;
			columnOneNr++;
		}else if(columnTwoNr<5){
			column = document.getElementById(\'1\');
			row = columnTwoNr;
			columnTwoNr++;
		}else{
			alert(\'Nem lehet tobb fix kategoriat elhelyezni az oldalon!\');
			return;
		}
	}else{
		if(columnOneNr<3){
			column = document.getElementById(\'1\');
			row = columnOneNr;
			columnOneNr++;
		}else{
			alert(\'Nem lehet tobb fix kategoriat elhelyezni az oldalon!\');
			return;
		}		
	}
	var showNewCat = function(r){		
		var box = document.createElement(\'div\');
		box.className = \'box\';
		box.innerHTML = r.responseText;
		box.title = catID;				
		
		if(column!=null)	column.appendChild(box);
		new Box(box, catID);
	}
	

	var pars = "action=add_new_box&cat_id=" + catID + "&cat_name=" + encodeURIComponent(catName) + "&column_id=" + column.id + "&page_id=" + pageID + "&row=" + row;
	var url = "../ajax/admin/fixed_categories.php";
	Ajax.send(url, "POST", pars, showNewCat, null);
}
window.onload = function(){
	showFixedCategories();
	showCategories();
}
var timeOut = 0;
function doCatSearch(o){	
	window.clearTimeout(timeOut);
	if(o.value.length>=3) timeOut = window.setTimeout("catSearch(\'" + o.value + "\')", 1000);
}	
function catSearch(q){
	var c = document.getElementById(\'categories\');
	var showCats = function(r){
		var xmlDoc = null;
		if(document.all){			
			xmlDoc = new ActiveXObject("Microsoft.XMLDOM")
			xmlDoc.load(r.responseXML);			
		}else{
			xmlDoc = loadXML(r.responseText);
		}		
		
		var opts = xmlDoc.getElementsByTagName(\'option\');											
		populateSelect(c, opts);
	}
	var pars = "action=cat_search&q=" + encodeURIComponent(q);
	var url = "../ajax/admin/fixed_categories.php";
	Ajax.send(url, "POST", pars, showCats, null);
}
</script>
'; ?>

<input type="hidden" id="pageID" value="<?php echo $this->_tpl_vars['page']['page_id']; ?>
" />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="page_title"><?php echo $this->_tpl_vars['page']['page_name']; ?>
 r&ouml;gz&iacute;tett kateg&oacute;ri&aacute;i</td>
  </tr>
  <tr>
    <td>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td valign="top" width="15%">
				<b>&Uacute;j kateg&oacute;ria:</b><br /><br />
				<table border="0" cellspacing="0" cellpadding="4">
				  <tr>
					<td>
						Kateg&oacute;ria n&eacute;v:<br />
						<input type="text" id="newCatName" /></td>
				  </tr>
				  <tr>
					<td align="right"><input type="button" value="Felvisz" class="button" onClick="addNewCat();" /></td>
				  </tr>
				</table>
				<b>Kateg&oacute;ri&aacute;k</b><br /><br />
				<table border="0" cellspacing="0" cellpadding="4">
				  <tr>
					<td>
						Kereső:<br />
						<input type="text" onKeyUp="doCatSearch(this);" />
					</td>
				  </tr>
				  <tr>
					<td>
						<select name="" multiple size="30" id="categories" ondblclick="insertCategory(this);"></select>
					</td>
				  </tr>
				</table>
			</td>
			<td valign="top">
				<table width="100%" border="0" cellspacing="0" cellpadding="5">
				  <tr>
					<?php if (0 && $this->_tpl_vars['page']['page_id'] == -1): ?>
					<td width="50%" valign="top">
						
					</td>
					<td width="50%" valign="top" id="1">
						
					</td>
					<?php else: ?>
					<td width="50%" valign="top" id="0">
						
					</td>
					<td width="50%" valign="top" id="1">
						
					</td>
					<?php endif; ?>
				  </tr>
				</table>
			</td>
		  </tr>
		</table>
	</td>
  </tr>
</table>