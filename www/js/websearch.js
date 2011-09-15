var WebSearch = {
	 
		
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
		box.className = 'box';	
		
		var show = function(r){				
				maxBoxId++;
				box.setAttribute('alt', maxBoxId);				
				box.innerHTML = r.responseText;
				if(activeDefaultTab!=null) var pageId = activeDefaultTab.id.substring(3, activeDefaultTab.id.length);	
					else var pageId = activeTab.id.substring(3, activeTab.id.length);
				new Box(box, lang['searchBox'], null, 0, 0, 3, 2, 0, 1, 1, pageId);				
		}
		
		if(activeDefaultTab==null)	var pars = "action=add_webseach" + '&page_id=' + activeTab.id.substring(3, activeTab.id.length);	
			else var pars = "action=add_webseach&default=1" + '&page_id=' + activeDefaultTab.id.substring(3, activeDefaultTab.id.length);
		
		var url = "ajax/page.php";
		Ajax.send(url, "POST", pars, show, null);
		firstCol.insertBefore(box, before);		
	},
	
	showSearchForm : function(id){
		var searchEngines = new Array('searchKurzor', 'searchYahoo', 'searchTango');
		for(var i=0;i<searchEngines.length;i++){
			document.getElementById(searchEngines[i]).style.display = 'none';
		}
		document.getElementById(id).style.display = 'block';
	}
}
