var IE = document.all?true:false;
var opera = false;//navigator.userAgent.match(/opera/i) ? true : false;
var alerted = false;
var moduleGhost = document.createElement("div");
moduleGhost.id = "moduleGhost";
var moduleGhostTab = document.createElement("div");
moduleGhostTab.id = "moduleGhostTab";

//to get html from url for fixed categories html boxes
var fileContents = new Array();
function getFileContents(file, cat_id, page_id, idx) {
    var id = 'fixed_content_'+page_id + '_' + cat_id + '_' + idx;
    if (typeof(fileContents[page_id]) == 'undefined') {
        fileContents[page_id] = new Array();    
    }
    fileContents[page_id].push([id, file]);
}



var boxes = new Array();

var Hint = function(alt){
	hintContainer : null,	
	
	this.show = function(){		
		this.timeOut = window.setTimeout('this.showHint("' + alt + '")', 500);
	},
	
	this.hide = function(){
		window.clearTimeout(this.timeOut);
		hideHint();		
				
	}, 
	
	showHint = function(alt){
		if(!this.hintContainer) this.hintContainer = document.getElementById('hintContainer')
		this.hintContainer.innerHTML = unescape(alt);
		this.hintContainer.style.display = 'block';
        var maxx =  getMaxX();
        var hcWidth = this.hintContainer.offsetWidth;
        if(mouseXY[0] + hcWidth + 10 < maxx ) {
          this.hintContainer.style.left = (mouseXY[0] + 10) + 'px';
        } else {
          this.hintContainer.style.left = (maxx -hcWidth -10) + 'px';
        }
        var maxy =  getMaxY();
        var hcHeight = this.hintContainer.offsetHeight;
        var scrolly = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop;
        if(mouseXY[1]-scrolly + hcHeight + 10 < maxy ) {
          this.hintContainer.style.top = (mouseXY[1] + 10) + 'px';
        } else {
          this.hintContainer.style.top = (mouseXY[1] - 10 - hcHeight) + 'px';
        }

//		this.hintContainer.style.top = (mouseXY[1] + 10) + 'px';
		//this.hintContainer.style.left = (mouseXY[0] + 10) + 'px';
        this.hintContainer.style.zIndex = 300;
	},
	
	hideHint = function(){
		if(!this.hintContainer) this.hintContainer = document.getElementById('hintContainer');
    this.hintContainer.style.display = 'none';		
	}	
}

var mouseXY = Array();
var Box = function(obj, title, feed, contentClosed, itemsNr, type, color, editable, moveable, closeable, pageId){				
    this.title = title;
	this.dragEnd = function(pageId){
		var columns = new Array();
		for(var i=0;i<3;i++){
			var column = document.getElementById(pageId + '_' + i);
			
			var childs = column.childNodes;
			var temp = Array();
			var l = 0;
			for(var j=0;j<childs.length;j++){
				if(childs[j].tagName=='DIV'){
					temp[l] = childs[j].getAttribute('alt');					
					l++;
				}
			}			
			columns[i] = temp;
		}			
//        alert('Page id: '+pageId+'\nactivetab.id:'+activeTab.id.substring(3, activeTab.id.length));
		if(activeDefaultTab==null)	{
            var pars = "action=update_strucure&page_id=" + activeTab.id.substring(3, activeTab.id.length) + '&firstCol=' + encodeURIComponent(columns[0])  + '&secondCol=' + encodeURIComponent(columns[1]) + '&thirdCol=' + encodeURIComponent(columns[2]);
        } else {
            var pars = "action=update_strucure&default=1&page_id=" + activeDefaultTab.id.substring(3, activeDefaultTab.id.length) + '&firstCol=' + encodeURIComponent(columns[0])  + '&secondCol=' + encodeURIComponent(columns[1]) + '&thirdCol=' + encodeURIComponent(columns[2]);
        }
				
		var url = "ajax/page.php";
		Ajax.send(url, "POST", pars, null, null);	

		/*
		favIcon.src = favIconSrc;
		editDiv.style.display = 'none';		
		*/
	}
	
	this.build = function(){				
		var newsList = null;
		if(IE && (getInternetExplorerVersion() < 9)){
            try {
    			var _box = obj.childNodes[0];
            } catch (e) {
                return false;
            }
            
            this.topboxcont = _box.childNodes[0];
            this.bottombox = this.topboxcont.childNodes[0];
            this.leftbox = this.bottombox.childNodes[0];
            this.rightbox = this.leftbox.childNodes[0];
            this.blbox = this.rightbox.childNodes[0];
            this.brbox = this.blbox.childNodes[0];
            this.tlbox = this.brbox.childNodes[0];
            this.trbox = this.tlbox.childNodes[0];

			
			var boxHead = this.trbox.childNodes[0];
			var h1 = boxHead.childNodes[1];
			
			var favIcon = boxHead.childNodes[1].childNodes[0].childNodes[0];
			var titleLink = boxHead.childNodes[1].childNodes[2];
			var editDiv = boxHead.childNodes[0];
			if(editable==1)	{
                var editLink;
                if (!(editLink = editDiv.childNodes[2])) editable = 0;
            }
			if(closeable==1){				
				switch(parseInt(type)){
					case 2:
					case 3:
                        var contentCloseLink = editDiv.childNodes[1];
                        var closeLink = editDiv.childNodes[2];
                        break;
					case 1:
					case 4:
					case 5:
                        var contentCloseLink = editDiv.childNodes[3];
                        var closeLink = editDiv.childNodes[4];
                        break;
				}	
                if (!closeLink) closeable = 0;
                var contentCloseIcon = contentCloseLink ? contentCloseLink.childNodes[0] : false;
			}
			
			var content = this.trbox.childNodes[2];
            this.content = content;
			if(type == 2) var webNoteContent = content.childNodes[0];

			if((type==1) || (type==4) || (type==5)){							
				var refreshLink = editDiv.childNodes[0];							
				var editContent = this.trbox.childNodes[1];							
				var inputTitle = editContent.childNodes[0].childNodes[0].childNodes[0].childNodes[1].childNodes[0];			
				var c0 = editContent.childNodes[0].childNodes[0].childNodes[1].childNodes[1].childNodes[0];			
				var c1 = editContent.childNodes[0].childNodes[0].childNodes[1].childNodes[1].childNodes[1];
				var c2 = editContent.childNodes[0].childNodes[0].childNodes[1].childNodes[1].childNodes[2];
				var c3 = editContent.childNodes[0].childNodes[0].childNodes[1].childNodes[1].childNodes[3];
				var c4 = editContent.childNodes[0].childNodes[0].childNodes[1].childNodes[1].childNodes[4];
				var c5 = editContent.childNodes[0].childNodes[0].childNodes[1].childNodes[1].childNodes[5];
				var c6 = editContent.childNodes[0].childNodes[0].childNodes[1].childNodes[1].childNodes[6];
                if (editable) {
    				var inputFeed = editContent.childNodes[0].childNodes[0].childNodes[2].childNodes[1].childNodes[0];
	    			var itemsNrSelect = editContent.childNodes[0].childNodes[0].childNodes[3].childNodes[1].childNodes[0];
		    		var updateButton = editContent.childNodes[0].childNodes[0].childNodes[4].childNodes[0].childNodes[0];				
                }
			}
		} else {

            try {
    			var _box = obj.childNodes[1];
            } catch (e) {
                return false;
            }
            
            this.topboxcont = _box.childNodes[1];
            this.bottombox = this.topboxcont.childNodes[0];
            this.leftbox = this.bottombox.childNodes[0];
            this.rightbox = this.leftbox.childNodes[0];
            this.blbox = this.rightbox.childNodes[0];
            this.brbox = this.blbox.childNodes[0];
            this.tlbox = this.brbox.childNodes[0];
            this.trbox = this.tlbox.childNodes[0];
			
			var boxHead = this.trbox.childNodes[1];				
			var h1 = boxHead.childNodes[3];
			
			var favIcon = boxHead.childNodes[3].childNodes[0].childNodes[0];
			var titleLink = boxHead.childNodes[3].childNodes[2];
			var editDiv = boxHead.childNodes[1];
			if(editable==1)	{
                var editLink;
                if (!(editLink = editDiv.childNodes[2])) editable = 0;
            }
			if(closeable==1){				
				switch(parseInt(type)){
					case 2:
					case 3:
                        var contentCloseLink = editDiv.childNodes[2];
                        var closeLink = editDiv.childNodes[3];
                        break;
					case 1:
					case 4:
					case 5:
                        var contentCloseLink = editDiv.childNodes[3];
                        var closeLink = editDiv.childNodes[4];
                        break;
				}	
                if (!closeLink) closeable = 0;
                var contentCloseIcon = contentCloseLink ? contentCloseLink.childNodes[0] : false;
            }
			var content = this.trbox.childNodes[5];
            this.content = content;

			if(type == 2) var webNoteContent = content.childNodes[1];
			
			if((type==1) || (type==4) || (type==5)){							
				var refreshLink = editDiv.childNodes[0];				
				var editContent = this.trbox.childNodes[3];				
                if (opera) {
                    var inputTitle = editContent.childNodes[1].childNodes[0].childNodes[0].childNodes[1].childNodes[0];
                    var c0 = editContent.childNodes[1].childNodes[0].childNodes[1].childNodes[1].childNodes[1];
                    var c1 = editContent.childNodes[1].childNodes[0].childNodes[1].childNodes[1].childNodes[3];
                    var c2 = editContent.childNodes[1].childNodes[0].childNodes[1].childNodes[1].childNodes[5];
                    var c3 = editContent.childNodes[1].childNodes[0].childNodes[1].childNodes[1].childNodes[7];
                    var c4 = editContent.childNodes[1].childNodes[0].childNodes[1].childNodes[1].childNodes[9];
                    var c5 = editContent.childNodes[1].childNodes[0].childNodes[1].childNodes[1].childNodes[11];			
                    var c6 = editContent.childNodes[1].childNodes[0].childNodes[1].childNodes[1].childNodes[13];			
                    if (editable) {
                        var inputFeed = editContent.childNodes[1].childNodes[0].childNodes[2].childNodes[1].childNodes[0];
                        var itemsNrSelect = editContent.childNodes[1].childNodes[0].childNodes[3].childNodes[1].childNodes[1];
                        var updateButton = editContent.childNodes[1].childNodes[0].childNodes[4].childNodes[0].childNodes[0];											
                    }
                } else {
                    var inputTitle = editContent.childNodes[1].childNodes[1].childNodes[0].childNodes[3].childNodes[0];
                    var c0 = editContent.childNodes[1].childNodes[1].childNodes[2].childNodes[3].childNodes[1];
                    var c1 = editContent.childNodes[1].childNodes[1].childNodes[2].childNodes[3].childNodes[3];
                    var c2 = editContent.childNodes[1].childNodes[1].childNodes[2].childNodes[3].childNodes[5];
                    var c3 = editContent.childNodes[1].childNodes[1].childNodes[2].childNodes[3].childNodes[7];
                    var c4 = editContent.childNodes[1].childNodes[1].childNodes[2].childNodes[3].childNodes[9];
                    var c5 = editContent.childNodes[1].childNodes[1].childNodes[2].childNodes[3].childNodes[11];			
                    var c6 = editContent.childNodes[1].childNodes[1].childNodes[2].childNodes[3].childNodes[13];			
                    if (editable) {
                        var inputFeed = editContent.childNodes[1].childNodes[1].childNodes[4].childNodes[3].childNodes[0];
                        var itemsNrSelect = editContent.childNodes[1].childNodes[1].childNodes[6].childNodes[3].childNodes[1];
                        var updateButton = editContent.childNodes[1].childNodes[1].childNodes[8].childNodes[1].childNodes[0];											
                    }
                }
			}
		}
        
        if (contentCloseLink) {
            contentCloseLink.onmousedown = stopHere;
        }
		
        if((type==1) || (type==4) || (type==5)){
            var thisbox = this;
			c0.onclick = function() {color = 0;thisbox.setColor(0);};
			c1.onclick = function() {color = 1;thisbox.setColor(1);};
			c2.onclick = function() {color = 2;thisbox.setColor(2);};
			c3.onclick = function() {color = 3;thisbox.setColor(3);};
			c4.onclick = function() {color = 4;thisbox.setColor(4);};
			c5.onclick = function() {color = 5;thisbox.setColor(5);};
			c6.onclick = function() {color = 6;thisbox.setColor(6);};
			
			refreshLink.onmousedown = stopHere 
			refreshLink.onclick = function(){
				titleLink.innerHTML = lang['updating'];
				getRSS(true, 1);
                return false;
			};
			
			
			editContent.onmousedown = stopHere;		
		    if (itemsNrSelect) {	
                if(IE) itemsNrSelect.attachEvent('onchange', onItemsNrChange);
                    else  itemsNrSelect.addEventListener('change', onItemsNrChange, false);
            }
			
            if (updateButton) {
                updateButton.onclick = function(){			
                    title = inputTitle.value;
                    titleLink.innerHTML = inputTitle.value;
                    feed = encodeURIComponent(inputFeed.value);
                    if (type == 4) {
    //                    feed = feed.replace(/%20/g, '||');
                    }
                    titleLink.innerHTML = lang['updating'];
                    
                    itemsNr = itemsNrSelect.options.length ? itemsNrSelect.options[itemsNrSelect.selectedIndex].value : 0;				

                    if(activeDefaultTab==null)	var pars = "action=update_box&page_id=" + activeTab.id.substring(3, activeTab.id.length) + "&box_id=" + obj.getAttribute('alt') + "&box_title=" + encodeURIComponent(inputTitle.value) + '&box_feed=' + feed + "&box_color=" + color + "&box_items_nr=" + itemsNr;
                        else var pars = "action=update_box&default=1&page_id=" + activeDefaultTab.id.substring(3, activeDefaultTab.id.length) + "&box_id=" + obj.getAttribute('alt') + "&box_title=" + encodeURIComponent(inputTitle.value) + '&box_feed=' + feed + "&box_color=" + color + "&box_items_nr=" + itemsNr;
                    var url = "ajax/page.php";
                    Ajax.send(url, "POST", pars, null, null);
                    
                    getRSS(true, 1);
                    closeEdit();
                    boxHeadOut();
                }
            }
			
		}
		
		if(editable==1){			
			editLink.onmousedown = stopHere 
			editLink.onclick = function(){showEdit();return false;}
		}
		
		if(closeable==1){
			closeLink.onmousedown = stopHere 
			closeLink.onclick = function(){				
				if(confirm(lang['onBoxDelete'])){				
					if(activeDefaultTab==null)	var pars = "action=remove_box&page_id=" + activeTab.id.substring(3, activeTab.id.length) + '&box_id=' + obj.getAttribute('alt') + '&type=' + type;		
						else var pars = "action=remove_box&general=1&page_id=" + activeDefaultTab.id.substring(3, activeDefaultTab.id.length) + '&box_id=' + obj.getAttribute('alt') + '&type=' + type;		
					var url = "ajax/page.php";
					Ajax.send(url, "POST", pars, null, null);
					obj.parentNode.removeChild(obj);
				}
                return false;
			};
		}
		
		titleLink.onmousedown = stopHere;
		var hideContent = function(){
			content.style.display = "none";
			//favIcon.onclick = showContent;
            contentCloseLink.onclick = showContent;
            contentCloseIcon.src = 'i/har.gif'; 
			contentClosed = true;
		}
		
		var showContent = function(){
			content.style.display = "block";
			//favIcon.onclick = hideContent;
            contentCloseLink.onclick = hideContent;
            contentCloseIcon.src = 'i/var.gif'; 
			contentClosed = false;
		}
		
		favIcon.onmousedown = stopHere;
		if(contentClosed=="0")	{
        //    favIcon.onclick = hideContent;
            contentCloseLink.onclick = hideContent;
        } else {
        //    favIcon.onclick = showContent;
            contentCloseLink.onclick = showContent;
        }
		
		var favIconSrc = favIcon.src;
		
		var boxHeadOver = function(){
            /*
			if(contentClosed=="0")	{
                favIcon.src = 'i/va.gif';
            } else {
                favIcon.src = 'i/ha.gif';
            }
            */
			if(editDiv)	editDiv.style.display = 'block';
		}
		
		var boxHeadOut = function(){
			//favIcon.src = favIconSrc;
			if(editDiv)	editDiv.style.display = 'none';
		}
		
		boxHead.onmouseover =  boxHeadOver;
		boxHead.onmouseout = boxHeadOut;
				
		
		
		var showEdit = function(){			
			editContent.style.display = "block";
			editLink.innerHTML = lang['closeEdit'];
			boxHead.onmouseout = null;
			editLink.onclick = closeEdit;
            return false;
		}
		
		var closeEdit = function(){
            try {
                editContent.style.display = "none";
                editLink.innerHTML = lang['edit'];
                boxHead.onmouseout = boxHeadOut;
                editLink.onclick = showEdit;
            } catch (e) {};
            return false;
		}
		function parseHTML(r, onRefresh){						
			content.innerHTML = r.responseText;			
			if(onRefresh) titleLink.innerHTML = title;
        }
		
		function parseRSS(r, onRefresh){						
			content.innerHTML = r.responseText;			
			newsList = content.childNodes[0];
			if(onRefresh) titleLink.innerHTML = title;
            var newsCount = 0;
            if (newsList && newsList.childNodes && newsList.childNodes.length) {
                for(var i=0;i<newsList.childNodes.length;i++){
                    if(newsList.childNodes[i].tagName=='LI'){					
                        newsCount ++;
                        if(newsList.childNodes[i].childNodes[0].getAttribute('alt')){
                            var hint = new Hint(escape(newsList.childNodes[i].childNodes[0].getAttribute('alt')));
                            if(IE){
                                newsList.childNodes[i].childNodes[0].attachEvent("onmouseover", hint.show);
                                newsList.childNodes[i].childNodes[0].attachEvent("onmouseout", hint.hide);
                            }else{						
                                newsList.childNodes[i].childNodes[0].addEventListener('mouseover', hint.show, false);
                                newsList.childNodes[i].childNodes[0].addEventListener('mouseout', hint.hide, false);
                            }
                        }
                    }
                }
            }
            var ol = itemsNrSelect.length;
            var osel = ol ? itemsNrSelect.selectedIndex : -1;
            if (newsCount > 20) newsCount = 20;
            for (var ii = itemsNrSelect.length+1; ii<=newsCount; ii++) {
                var o = new Option;
                o.value = ii;
                o.text = ii;
                itemsNrSelect.options[itemsNrSelect.options.length] = o;

            }
            itemsNrSelect.length = newsCount;
            if (newsCount && (!ol || ((ol < newsCount) && (osel == ol-1)) || (osel > (newsCount-1)))) {
                itemsNrSelect.selectedIndex = itemsNrSelect.length-1;
                updateButton.onclick();
            }
            onItemsNrChange();

            var o;
            if (news_id && (o = document.getElementById('news_'+news_id))) {
                while (o && (!o.id || !o.id.match(/^rss_box_/))) o = o.parentNode;
                if (o) {
                    scrollTo(findPosX(o), findPosY(o)-10);
                }
            }
			
		}
        this.getRSS = function() {
            getRSS(true,1);
        }
		function getRSS(onRefresh, force){
			var url = "ajax/read_rss.php";
            if (type == 5) {
    			var pars = 'action=read_htmlbox&force=' + force + '&htmlbox_id='+feed; 
                Ajax.send(url, "GET", pars, parseHTML, onRefresh);

            } else {
    			var pars = "action=read_rss&feed=" + feed + "&items_nr=" + itemsNr + '&type=' + type + '&force=' + force + '&news_id='+news_id+'&newslink_from='+newslink_from; 
                Ajax.send(url, "GET", pars, parseRSS, onRefresh);
            }
		}
		
		function onItemsNrChange(){						
            if ((itemsNrSelect.selectedIndex) != -1 && (itemsNrSelect.options.length > 0)) {
    			itemsNr = itemsNrSelect.options[itemsNrSelect.selectedIndex].value;
            } else {
                itemsNr = 0;
            }
			var j = 0;
			
			for(var i=0;i<newsList.childNodes.length;i++){
				if(newsList.childNodes[i].tagName!=null && newsList.childNodes[i].tagName=='LI'){
					if(j>=itemsNr) newsList.childNodes[i].className = "hidden";
						else newsList.childNodes[i].className = "visible";
					j++;
				}
			}				
		}		

		
		
		function getWebNoteContent(){
			var showContent = function(r){
                var a = r.responseText.split('_::_');
				webNoteContent.innerHTML = a[0];
                webNoteContent.defaultContent = a.length > 1 ? parseInt(a[1]) : 0;
				if(r.responseText.trim()=='') webNoteContent.style.height = '120px';				
			}
			var wnID = webNoteContent.id.substring(8, webNoteContent.id.length);
			var pars = "action=get_content&id=" + wnID;			
			var url = "ajax/webnote.php";
			Ajax.send(url, "POST", pars, showContent, null);	
		}
						
		if(moveable==1){
			Drag.init(boxHead, obj);
			new DragDrop(obj, this, pageId);			
		}

		switch(type){
			case '1':getRSS(false, 0);break;
			case 1:	getRSS(false, 0);break;
			case '2': getWebNoteContent();break;
			case 2: getWebNoteContent();break;
			case '4':getRSS(false, 0);break;
			case 4:	getRSS(false, 0);break;
			case '5':getRSS(false, 0);break;
			case 5:	getRSS(false, 0);break;
		}
				
			
	}
    
	this.build();
}
Box.prototype.setColor = function(idx) {
        this.topboxcont.className = 'topboxcont_'+idx;
        this.bottombox.className = 'bottombox_'+idx;
        this.leftbox.className = 'leftbox_'+idx;
        this.rightbox.className = 'rightbox_'+idx;
        this.blbox.className = 'blbox_'+idx;
        this.brbox.className = 'brbox_'+idx;
        this.tlbox.className = 'tlbox_'+idx;
        this.trbox.className = 'trbox_'+idx;
        this.content.className = 'content_'+idx;
}

var elements = null;
var maxTabId = 0;
var maxBoxId = null;
function createPage(r, div){			
	//var mainContent = document.getElementById('mainContent');	
	var mainContent = div;	
    var a = div.id.split('_');
    var pageId;
    if (false && a.length == 2) {
        pageId = (a[0] == 'usp' ? 's' : '') + a[1];
    } else {
    	pageId = div.id.substring(4, div.id.length);
    }

	

 	var script =  document.createElement('script');
	mainContent.appendChild(script);	
	var scriptText = r.responseText.substring(r.responseText.indexOf('<script>')+8, r.responseText.indexOf('</script>'));	
	script.nodeValue = eval(scriptText);		
   evalScriptFromHtml(r.responseText, div);
	
	/*Fixed Categories RSS Reader */
	var showFixedRSS = function(r, catID){								
		var c = document.getElementById('fc_' + pageId + '_' + catID);
		c.innerHTML = r.responseText;					
	}
	var fixedCategories = new Array();
	fixedCategories = fCat;
	
	if(fixedCategories!=null){
		for (var catID in fixedCategories){		
			
			var pars = "action=read_rss&feed=" + encodeURIComponent(fixedCategories[catID]) + '&news_id='+news_id+'&newslink_from='+newslink_from;			
            if (fCatFeedTypes[catID] != undefined) {
                pars += '&type='+fCatFeedTypes[catID];
                if (queryParams['count'] != undefined) pars += '&items_nr='+queryParams['count'];
            }
			var url = "ajax/read_rss.php";
			Ajax.send(url, "GET", pars, showFixedRSS, catID);
		}
	}
	/*Fixed Categories RSS Reader */
	maxBoxId = maxBoxID;
	mainContent.innerHTML = r.responseText.substring(r.responseText.indexOf('</script>')+9, r.responseText.length);		
	elements = components;	
    var first = false;
	for(var i=0;i<components.length;i++){		
        if (typeof(components[i]) != 'undefined') {
            for(var j=0;j<components[i].length;j++){			
                var obj = document.getElementById('comp_' + pageId + '_' + i + '_' + j);
                if (obj != null) {
                    var xx = components[i][j]['type'];
                    try {
                        boxes[components[i][j]['title']] = new Box(obj, components[i][j]['title'], components[i][j]['feed'], components[i][j]['closed'], components[i][j]['items_nr'], components[i][j]['type'], components[i][j]['color'], components[i][j]['editable'], components[i][j]['moveable'], components[i][j]['closeable'], pageId);
                        if (first) {
                            var dd = new DragDrop(obj, boxes[components[i][j]['title']], pageId);
                            obj.onDragStart(0,0);
                            obj.onDrag(0,0);
                            obj.onDragEnd();
                            first = false;
                        }
                    } catch(e) {
    //                    alert(i + ', '+ j + ': ' + components[i][j]['title']);
                    }
                }
            }
        }
	}		
	var showFixedHtml= function(r, key){								
		var c = document.getElementById(key);
        if (c) {
        		c.innerHTML = r.responseText;					
        }
	}
    var o;
    if (typeof(fileContents[pageId]) != 'undefined') {
        for (var ij=0;ij<fileContents[pageId].length;ij++) {
            var key = fileContents[pageId][ij][0];
            var file = fileContents[pageId][ij][1];
            var url = "ajax/read_filecontent.php";
            if (o = document.getElementById(key)) {
                var pars = 'file='+encodeURIComponent(file);
                Ajax.send(url, "GET", pars, showFixedHtml, key);
            }
        }
    }
insertMonddMeg();
	
}
var activeDefaultTab = null;

function showDefaultPage(pageID, tab) {

	maxBoxId = 0;	

	if(activeTab){
		var oldPageId = activeTab.id.substring(3, activeTab.id.length);
		if(document.getElementById('usp_s' + oldPageId)) document.getElementById('usp_s' + oldPageId).style.display = 'none';
		if(IE){
			activeTab.removeChild(activeTab.childNodes[2]);
		}else{				
			if(activeTab.childNodes[2].tagName=='IMG') activeTab.removeChild(activeTab.childNodes[2]);	
				else activeTab.removeChild(activeTab.childNodes[3]);	
		}
		activeTab.className = 'tabinactive';
		activeTab = null;
	}
	if(activeDefaultTab){
		activeDefaultTab.style.color = "#fff";
		var oldPageId = activeDefaultTab.id.substring(3, activeDefaultTab.id.length);
		if(document.getElementById('dfp_' + oldPageId)) document.getElementById('dfp_' + oldPageId).style.display = 'none';
		
	}
	if(tab==null) tab = document.getElementById('dp_' + pageID);
	activeDefaultTab = tab;
	tab.style.color = "#F67E0E";
	if(!document.getElementById('dfp_' + pageID)){
        if (typeof(urchinTracker) != 'undefined') {
            var u = (typeof('userID') != 'undefined') && userID != '-1' && userID != '' ? '_user' : '';
            urchinTracker(pageID + '_'+tab.innerHTML+u);
        }
		var div = document.createElement('div');
		div.id = 'dfp_' + pageID;
		var mainContent = document.getElementById('mainContent');
		mainContent.appendChild(div);
		var pars = "action=get_default_page_structure&page_id=" + pageID;
        if (queryParams['kw'] != undefined) pars += '&kw='+queryParams['kw'];
        if (queryParams['from'] != undefined) pars += '&from='+queryParams['from'];
        if (queryParams['news_id'] != undefined) pars += '&news_id='+queryParams['news_id'];
		var url = "ajax/page.php";
		Ajax.send(url, "POST", pars, createPage, div);			
	}else{
		document.getElementById('dfp_' + pageID).style.display = 'block';
	}
	
}

var getAllBoxes = function(){
	var mainContent = document.getElementById('main_content');
	var boxes = new Array();
	for(var i=0;i<mainContent.rows[0].childNodes.length;i++){
		if(mainContent.rows[0].childNodes[i].tagName=='TD'){
			var td = mainContent.rows[0].childNodes[i];
			for(var j=0;j<td.childNodes.length;j++){
				if(td.childNodes[j].tagName=='DIV'){
					var col = td.childNodes[j];
					for(var l=0;l<col.childNodes.length;l++){
						if(col.childNodes[l].tagName=='DIV')
							boxes.push(col.childNodes[l]);						
					}
				}
			}			
		}		
	}	
	return boxes;
}

var tabItems = Array();
var activeTab = null;

var createTabs = function(r, defaultPage){

    if (defaultPage == 'undefined') defaultPage=1;
    var objDP = document.getElementById('dp_'+defaultPage);
    if (!objDP) {
        defaultPage = 1;
        objDP = document.getElementById('dp_'+defaultPage);
    }
	tabs.innerHTML = r.responseText;
	var first = true;
	for(var i=0;i<tabs.childNodes.length;i++){
		if(tabs.childNodes[i].tagName=='DIV'){			
			if(IE)	new Tabs(tabs.childNodes[i], tabs.childNodes[i].childNodes[0]);
				else new Tabs(tabs.childNodes[i], tabs.childNodes[i].childNodes[1]);
				
			tabItems.push(tabs.childNodes[i]);
			
			var id = parseInt(tabs.childNodes[i].id.substring(3, tabs.childNodes[i].id.length));			
			if(id>maxTabId)	maxTabId = id;
			if(first){				
				activeTab = tabs.childNodes[i];
				/*
				var pars = "action=get_page_structure&page_id=" + id;				
				var url = "ajax/page.php";
				Ajax.send(url, "POST", pars, createPage, null);	
				*/
				first = false;
			}
		}		
	}
    if (objDP) {
    	showDefaultPage(defaultPage, objDP);
    }
}
var updateTabs = function(onDelete, tabID){
	var ids = new Array();
	var titles = new Array();
	
	
	for(var i=0;i<tabs.childNodes.length;i++){
		if(tabs.childNodes[i].tagName=='DIV'){
			ids.push(tabs.childNodes[i].id.substring(3, tabs.childNodes[i].id.length));
			
			if(IE){			
				if(tabs.childNodes[i].childNodes[0] && tabs.childNodes[i].childNodes[0].tagName=='SPAN')	titles.push(tabs.childNodes[i].childNodes[0].innerHTML);
					else titles.push(tabs.childNodes[i].childNodes[0].value);
			}else{				
				if(tabs.childNodes[i].childNodes.length==2){					
					if(tabs.childNodes[i].childNodes[0] && tabs.childNodes[i].childNodes[0].tagName=='SPAN')	titles.push(tabs.childNodes[i].childNodes[0].innerHTML);
						else titles.push(tabs.childNodes[i].childNodes[0].value);	
				}else if(tabs.childNodes[i].childNodes.length==3){					
					if(tabs.childNodes[i].childNodes[1] && tabs.childNodes[i].childNodes[1].tagName=='SPAN')	titles.push(tabs.childNodes[i].childNodes[1].innerHTML);
						else titles.push(tabs.childNodes[i].childNodes[0].innerHTML);					
				}else{										
					if(tabs.childNodes[i].childNodes[1] && tabs.childNodes[i].childNodes[1].tagName=='SPAN')	titles.push(tabs.childNodes[i].childNodes[1].innerHTML);
						else titles.push(tabs.childNodes[i].childNodes[1].value);					
				}	
			}
			
		}			
	}
	if(onDelete==true)	var pars = "action=resort_tabs&ids=" + encodeURIComponent(ids) + '&titles=' + encodeURIComponent(titles) + "&on_delete=" + onDelete + "&tab_id=" + tabID;
		else var pars = "action=resort_tabs&ids=" + encodeURIComponent(ids) + '&titles=' + encodeURIComponent(titles);
	var url = "ajax/page.php";
	Ajax.send(url, "POST", pars, null, null);	
}
var closeTab = function(tab){
	if(confirm(lang['onTabDelete'])){
		var tabId = tab.id.substring(3, tab.id.length);
		
		if(tabItems.length==1){
			alert(lang['lastTabDelete']);
			return;
		}
		
		var active = 0;
		for(var i=0;i<tabItems.length;i++){
			if(tab==tabItems[i]) active = i;
		}
		
		if(active<tabItems.length-1){
			for(var i=active+1;i<tabItems.length;i++){
				tabItems[i-1] = tabItems[i];
			}
			activeTab = tabItems[active];		
		}else activeTab = tabItems[active-1];
		
		tabItems.length--;
		var mainContent = document.getElementById('mainContent');		
		mainContent.removeChild(document.getElementById('usp_s' + tab.id.substring(3, tab.id.length)));
		
		var img = document.createElement('img');
        var o = document.getElementById('close2_gif');
        if (o) {
        //    img.src = o.src;
        } else {
            img.src = 'i/close2.gif';	
        }
		img.onmousedown = stopHere;
		img.onclick = function(){closeTab(activeTab)};
		img.style.cursor = 'pointer';
		img.align = 'top';	
		
		activeTab.appendChild(img);
		activeTab.className = 'tabactive';	
		tab.parentNode.removeChild(tab);
		updateTabs(true, tabId);
		if(!document.getElementById('usp_s' + activeTab.id.substring(3, activeTab.id.length))){
			var div = document.createElement('div');
			div.id = 'usp_s' + activeTab.id.substring(3, activeTab.id.length);
			mainContent.appendChild(div);
			
			var pars = "action=get_page_structure&page_id=" + activeTab.id.substring(3, activeTab.id.length);
			var url = "ajax/page.php";
			Ajax.send(url, "POST", pars, createPage, div);	
		}else document.getElementById('usp_s' + activeTab.id.substring(3, activeTab.id.length)).style.display = 'block';
	}
}

var Tabs = function(tab, title){
	var input = document.createElement('input');

	this.dragEnd = function(){		
		if(input.value!='')
			title.innerHTML = input.value;
		title.onclick = editTab;	
		updateTabs(null, null);		
	}
	
	var closeImg = null;
	
	if(IE){
		if(tab.childNodes[2])	closeImg = tab.childNodes[2];
	}else{
		if(tab.childNodes[3])	closeImg = tab.childNodes[3];
	}
	if(closeImg){
		closeImg.onmousedown = stopHere;
		closeImg.onclick = function(){closeTab(tab)};
	}
	
	tab.onclick = function(){				
		if(tab.className=='tabinactive'){										
			if(activeDefaultTab){
				var oldPageId = activeDefaultTab.id.substring(3, activeDefaultTab.id.length);
				if(document.getElementById('dfp_' + oldPageId)) document.getElementById('dfp_' + oldPageId).style.display = 'none';
				activeDefaultTab.style.color = "#FFF";
				activeDefaultTab = null;				
			}
			if(activeTab){				
				var oldPageId = activeTab.id.substring(3, activeTab.id.length);
				if(document.getElementById('usp_s' + oldPageId)) document.getElementById('usp_s' + oldPageId).style.display = 'none';
				if(IE){
					activeTab.removeChild(activeTab.childNodes[2]);
				}else{				
					if(activeTab.childNodes[2].tagName=='IMG') activeTab.removeChild(activeTab.childNodes[2]);	
						else activeTab.removeChild(activeTab.childNodes[3]);	
				}				
				activeTab.className = 'tabinactive';
				
			}
			
			activeTab = tab;									
			tab.className = 'tabactive';
			var img = document.createElement('img');
            var o = document.getElementById('close2_gif');
            if (o) {
//                img.src = o.src;
            } else {
                img.src = 'i/close2.gif';	
            }
			img.onmousedown = stopHere;
			img.onclick = function(){closeTab(tab)};
			img.style.cursor = 'pointer';
			img.align = 'top';
			
			tab.appendChild(img);
			if(!document.getElementById('usp_s' + tab.id.substring(3, tab.id.length))){
				var div = document.createElement('div');
				div.id = 'usp_s' + tab.id.substring(3, tab.id.length);
				var mainContent = document.getElementById('mainContent');
				mainContent.appendChild(div);
				
				var pars = "action=get_page_structure&page_id=" + tab.id.substring(3, tab.id.length);
				var url = "ajax/page.php";
				Ajax.send(url, "POST", pars, createPage, div);	
			}else	document.getElementById('usp_s' + tab.id.substring(3, tab.id.length)).style.display = 'block';
			
			//Ajax.send(url, "POST", pars, testResponse, null);	
			
		}
	}
	
	var editTab = function(){
		var t = title.innerHTML;		
		
		if(tab.className=="tabactive"){
				input.setAttribute('type', 'text');
				input.setAttribute('value', t);				
				title.innerHTML = '';
				title.appendChild(input);
				title.onclick = stopHere;
				
				input.focus();				
				input.onblur = function(){							
					try{
						title.removeChild(input);
						//title.innerHTML = ''
						if(input.value.trim()!='')	title.innerHTML = input.value;								
							else title.innerHTML = 'Név nélkül!';
						
						updateTabs(null, null);	
					}catch(e){
					}
					title.onclick = editTab;										
				}
							
		}
		
	}
	title.onmousedown = stopHere;
	title.onclick = editTab;
		
	Drag.init(tab, tab);
	new DragTab(tab, this);
}

var createNewTab = function(){	
	
	var tab = document.createElement('div');
	tabs.appendChild(tab);	
	var title = document.createElement('span');	
	title.style.cursor = 'pointer';
	tab.appendChild(title);	
	var text = document.createTextNode(' ');
	tab.appendChild(text);
	
	tab.className = 'tabinactive';
	maxTabId++;
	tab.id = 'tab' + maxTabId;		
	title.innerHTML = lang['newTab'] + ' ' + maxTabId;
	tabItems.push(tab);
	
	new Tabs(tab, title);
	
	var pars = "action=add_new_tab&tab_id=" + maxTabId + '&tab_name=' + encodeURIComponent(lang['newTab'] + ' ' + maxTabId);
	var url = "ajax/page.php";
	Ajax.send(url, "POST", pars, null, null);	
	
}

//manufaktura

function popwindow(page,same, width, height) {
    if (!width) width=600;
    if (!height) height=400;
    winopts = "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width="+width+",height="+height;
    return window.open(baseurl+page, 'pw'+same, winopts); 
}
function popwindowdirect(page,same, width, height) {
    if (!width) width=600;
    if (!height) height=400;
    winopts = "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width="+width+",height="+height;
    return window.open(page, 'pw'+same, winopts); 
}
function showHelp() {
    var hw = popwindowdirect('help.html', 'help');
    hw.focus();
}
function attachNewsHint(obj, lead) {
    var hint = new Hint(escape(lead));
    obj.onmouseover = "";
    if(IE){
        obj.attachEvent("onmouseover", hint.show);
        obj.attachEvent("onmouseout", hint.hide);
    }else{						
        obj.addEventListener('mouseover', hint.show, false);
        obj.addEventListener('mouseout', hint.hide, false);
    }
    hint.show();
    
}
