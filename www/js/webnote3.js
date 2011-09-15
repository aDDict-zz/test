var WebNote = {
	textArea : null, 
	
	writeWebnote : function(webnote){
		
		if(!webnote.childNodes[0] || webnote.childNodes[0].tagName!='TEXTAREA'){
			textArea = document.createElement('textarea');			
            textArea.value = webnote.defaultContent ? '' : webnote.innerHTML.br2nl();			
            webnote.defaultContent = 0;
			
			textArea.style.width = "100%";
			textArea.style.height = webnote.offsetHeight + 'px';
			textArea.style.border = "0px";
			webnote.innerHTML = '';
			webnote.appendChild(textArea);
			textArea.focus();
			
		}else var textArea = webnote.childNodes[0];
		
		textArea.onblur = function(){
			webnote.removeChild(textArea);		
			webnote.innerHTML = textArea.value.nl2br();			
			if(textArea.value.trim()=='') webnote.style.height = '120px';
				else webnote.style.height = 'auto';
			
			var wnID = webnote.id.substring(8, webnote.id.length);
			
			var pars = "action=update_webnote" + '&id=' + wnID + '&content=' + encodeURIComponent(textArea.value.trim().nl2br());	
			var url = "ajax/webnote.php";
			Ajax.send(url, "POST", pars, null, null);				
		}		
	}, 
	
	show : function(){
		if(activeDefaultTab!=null)	var mainContent = document.getElementById('dfp_' + activeDefaultTab.id.substring(3, activeDefaultTab.id.length));		
			else var mainContent = document.getElementById('usp_s' + activeTab.id.substring(3, activeTab.id.length));		

		if(IE){
			mainContent = mainContent.childNodes[0];
			var firstCol = mainContent.rows[0].cells[0].childNodes[0];			
			if(firstCol.childNodes[0])	var before = firstCol.childNodes[0];
				else 	var before = null;
		}else{
			mainContent = mainContent.childNodes[1];
			if(mainContent.rows[0].cells[0].childNodes.length==1){
				var firstCol = mainContent.rows[0].cells[0].childNodes[0];						
				var before = null;
			}else{
				
				var firstCol = mainContent.rows[0].cells[0].childNodes[1];
				var before = firstCol.childNodes[1];
			}
		}

		var box = document.createElement('div');
		
		var show = function(r){
			maxBoxId++;

            var bid = maxBoxId;
            if (r.responseText.match(/alt="bid_(\d+)"/m)) {
                bid = RegExp.$1;
                //alert('bid: ' + bid);
            }
			box.innerHTML = r.responseText;			
			box.setAttribute('alt', bid);
            box.className = 'box';
			if(activeDefaultTab!=null) {
                var pageId = activeDefaultTab.id.substring(3, activeDefaultTab.id.length);	
            } else {
                var pageId = 's' + activeTab.id.substring(3, activeTab.id.length);	
            }
			new Box(box, lang['webNote'], null, 0, 0, 2, 2, 0, 1, 1, pageId);						
		}
		
		if(activeDefaultTab==null)	var pars = "action=add_webnote" + '&page_id=' + activeTab.id.substring(3, activeTab.id.length);
			else var pars = "action=add_webnote&default=1" + '&page_id=' + activeDefaultTab.id.substring(3, activeDefaultTab.id.length);	
		var url = "ajax/page.php";
		Ajax.send(url, "POST", pars, show, null);	
		firstCol.insertBefore(box, before);
		
	}
}
