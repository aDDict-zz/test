<?php /* Smarty version 2.6.6, created on 2009-12-21 13:31:02
         compiled from page_categories.html */ ?>
<?php echo '
<style>
.box{
	width:298px;		
	margin-bottom:10px;
}
.module{			
	border:1px solid #D5DEE1;					
	border-bottom:0px;
}
.mHeader{
	background-image:url(../i/bh.gif);
	background-repeat:repeat-x;
	border-bottom:1px solid #7D8E97;
	color:#00368F;	
	height:20px;
	cursor:move;
	overflow: hidden;
	z-index:1;
}
.mFooter{
	background-image:url(../i/bh.gif);
	background-repeat:repeat-x;
	border:1px solid #D5DEE1;
	border-top:0px;
	color:#00368F;	
	height:12px;
	text-align:right;	
	padding:4px;
}

div.boxName{
	float:left;
	padding:2px 0px 0px 10px;
}
.editDIV{
	background-color:#EFF5FF;
	color:#00368F;	
	display:block;	
}
a.boxName:link{font-weight:bold;text-decoration:none;color:#102E71;}
a.boxName:visited{font-weight:bold;text-decoration:none;color:#102E71;}
a.boxName:hover{font-weight:bold;text-decoration:underline;color:#FF6600;}
a.boxName:active{font-weight:bold;text-decoration:none;color:#102E71;}
.boxControls{
	float:right;
	display:none;
	padding:5px;	
}
.divEdit{
	float:left;
	margin-right:4px;	
	padding-bottom:3px;
}
.showBoxControls{
	float:right;
	display:block;
	padding:2px;
}
.boxContent{
	padding:3px;
	border:1px solid #D5DEE1;	
	border-top:0px;
}
#moduleGhost{	
	border:1px dashed #FF0000;		
	margin-bottom:10px;
	padding:0;
}
.container{
	vertical-align:top;	
}
ul.feed{
	margin:2px;
	padding:0px;
}
ul.feed li{
	margin:2px;
	padding:0px;
	border: none;		
	list-style-type:none;
}
.menuContainer{
	border:2px solid #EEEEEE;
	padding:3px;
	width:100%;
}
.menuContent{
	background-color:#EFF5FF;
	color:#000000;		
	font-weight:bold;
	padding:5px;	
}
.menuContentClose{	
	float:right;	
	width:13px;
	height:15px;
}
a.menuContent:link{font-weight:bold;text-decoration:none;color:#000000;}
a.menuContent:visited{font-weight:bold;text-decoration:none;color:#000000;}
a.menuContent:hover{font-weight:bold;text-decoration:underline;color:#FF6600;}
a.menuContent:active{font-weight:bold;text-decoration:none;color:#000000;}
.addNewFeedC{
	width:120px;
}
.close{
	cursor:pointer;
}
ul.edit{
	list-style-type:none;
	margin:0px;
	padding:5px;
}
ul.edit li input{
	width:250px;
}
ul.edit li form{
	margin:0;
}
div#newCatContent{
	display:none;
}
div#searchCatContent{
	display:none;
}
div#searchHtmlboxContent{
	display:none;
}
</style>

<!-- script language="javascript" type="text/javascript" src="prototype-1.4.0/dist/prototype.js"></script -->
<script language="javascript" type="text/javascript">
var catCSS = Array();

function decodeText(text){
	if(text!="" || text!=null || text!="undefined"){
		text = text.replace(/&#xxx;/gi, ":");
		text = text.replace(/&#yyy;/gi, "|");
	}
	return text;
}

function stopHere(evt){
  if (!evt) 
    var evt = window.event;

  evt.cancelBubble = true
  if (evt.stopPropagation)
    evt.stopPropagation()
}

var moduleGhost = document.createElement("div");
moduleGhost.id = "moduleGhost";
var IE = document.all?true:false;
function createElement(name, cl){
	var element = document.createElement(name);
	element.className = cl;
	return element;
}
function findPosX(obj) {
	var curleft = 0;
	if (obj.offsetParent) {
		while (obj.offsetParent) {
			curleft += obj.offsetLeft;
			obj = obj.offsetParent;
		}
	} else if (obj.x) curleft += obj.x;
	return curleft;
}
function findPosY(obj) {
	var curtop = 0;
	if (obj.offsetParent!=null) {
		while (obj.offsetParent) {
			curtop += obj.offsetTop;
			obj = obj.offsetParent;
		}
	} else if (obj.y) curtop += obj.y;
	return curtop;

}

var mouseXY = Array();
function getContent(request, data){
	var answer = request.responseText;			
	data[0].innerHTML = answer;
}
var Box = function(boxTitle, boxTitleURL, boxColumn, boxTop, boxNewsNr, boxID, boxType, boxCssID, boxCSS, boxHTML, boxSQL){				
	var obj = null;			
	this.build = function(){										
		var column = document.getElementById(boxColumn);		
		var box = document.createElement(\'div\');
		box.className = "box";
		box.id = \'box_\' + boxID;
		
		var module = document.createElement(\'div\');
		module.className = "module";
		box.appendChild(module);
		
		var boxHeader = document.createElement(\'div\');
		boxHeader.className = "mHeader";
		module.appendChild(boxHeader);
		
		var boxName = document.createElement(\'div\');
		boxName.className = "boxName";
		boxName.innerHTML = \'<a href="\' + boxTitleURL + \'" class="boxName" target="_blank">\' + boxTitle + \'</a>\';
		if(IE)
		  boxName.attachEvent("onmousedown", stopHere);
		else
		  boxName.onmousedown = stopHere;
		boxHeader.appendChild(boxName);
		
		var content = document.createElement(\'div\');
		content.className = "boxContent";
		content.innerHTML = "Bet&ouml;lt&eacute;s alatt...";
		box.appendChild(content);				
		
		var boxControls = document.createElement(\'div\');
		boxControls.className = "boxControls";		
		var divEdit = document.createElement(\'div\');
		divEdit.className = "divEdit";
		boxControls.appendChild(divEdit);
		var linkEdit = document.createElement("a");
		linkEdit.innerHTML = "Szerkeszt";
		linkEdit.href = "#";
		if(IE)
		  linkEdit.attachEvent("onmousedown", stopHere);
		else
		  linkEdit.onmousedown = stopHere;
		linkEdit.onclick = function(){obj.showEditRSSBox(this);	}
        if (boxType == 5) linkEdit.style.display = \'none\';
		divEdit.appendChild(linkEdit);		
		var closeImg = document.createElement(\'img\');
		closeImg.className = "close";
		closeImg.src = \'../i/closeMod.gif\';
		if(IE)
		  closeImg.attachEvent("onmousedown", stopHere);
		else
		  closeImg.onmousedown = stopHere;
		closeImg.onclick = function(){
			obj.removeRSSBox(boxID);
		}
		boxControls.appendChild(closeImg);		
		boxHeader.appendChild(boxControls);
		if(column!=null)
			column.appendChild(box);
		var data = Array();
		data[0] = content;
		data[1] = box;		
		
		Ajax.send("ajax.php", "POST", "action=get_rss" + "&id=" + boxID, getContent, data);				
		boxHeader.onmouseover = function()	{
			boxControls.style.display = "block";
		}
		boxHeader.onmouseout = function()	{
			boxControls.style.display = "none";
		}
		
		var footer = document.createElement(\'div\');
		footer.className = "mFooter";
		box.appendChild(footer);
		
		footer.innerHTML = "<div style=\\"float:right;\\"><a href=\\"#\\" onclick=\\"openAddNewRSS(this, \'" +  boxID + "\');\\">&Uacute;j RSS</a></div>"
        if (boxType == 5) footer.style.visibility = \'hidden\';
		
		obj = box;		
		Drag.init(boxHeader, obj);	
										
		obj.removeRSSBox = function(id){		
			if(confirm(\'Biztos benne?\')){
				var pars = "action=remove_rss_box&cat_id=" + id + "&page_id=" + document.getElementById(\'page_id\').value;
				var url = "ajax.php";
				Ajax.send(url, "POST", pars, null, null);	
				closeBox(\'box_\' + id);				
			}else return false;
		}
		
		obj.closeEditRSSBox = function(editLink){
			boxHeader.onmouseover = function()	{
				boxControls.style.display = "block";
			}
			boxHeader.onmouseout = function()	{
				boxControls.style.display = "none";
			}
			boxControls.style.display = "none";
			editLink.onclick = function(){obj.showEditRSSBox(this);};
			editLink.innerHTML = "Szerkeszt"
			closeBox(\'editDiv_\' + boxID);
		}
		
		obj.changeCatType = function(type){
			if(type.options[type.selectedIndex].value == 1){
				document.getElementById(\'cat_content_\' + boxID).style.display = "none";
				document.getElementById(\'cat_content_\' + boxID).style.visibility = "hidden";
			}else{
				document.getElementById(\'cat_content_\' + boxID).style.display = "block";
				document.getElementById(\'cat_content_\' + boxID).style.visibility = "visible";
			}
		}
		
		obj.showEditRSSBox = function(editLink){			
			boxHeader.onmouseover = "";
			boxHeader.onmouseout = "";
			boxControls.style.display = "block";
			editLink.onclick = function(){obj.closeEditRSSBox(this);};
			editLink.innerHTML = "Szerkeszt&eacute;s v&eacute;ge"
			
			if(document.getElementById("editDiv_" + boxID)!=null){				
				var editDIV = document.getElementById("editDiv_" + boxID);
				editDIV.style.display = "block";
			}else{
				var editDIV = createElement("div", "editDIV");
				
				editDIV.id = "editDiv_" + boxID;
				module.appendChild(editDIV);
				var ul = createElement("ul", "edit");
				editDIV.appendChild(ul);
				
				var li = createElement("li", "");
				li.innerHTML = "C&iacute;m:";
				ul.appendChild(li);
				
				var inputTitle = createElement("input", "");
				inputTitle.type = "text";
				inputTitle.value = boxTitle;				
				li = createElement("li", "");
				li.appendChild(inputTitle);
				ul.appendChild(li);
				
				li = createElement("li", "");				
				li.innerHTML = "Kateg&oacute;ria URL:";
				ul.appendChild(li);
				
				var categoryURL = createElement("input", "");
				categoryURL.type = "text";
				categoryURL.value = boxTitleURL;				
				li = createElement("li", "");
				li.appendChild(categoryURL);
				ul.appendChild(li);
				
				li = createElement("li", "");
				li.innerHTML = "H&iacute;rek sz&aacute;ma:";
				ul.appendChild(li);
				li = createElement("li", "");
				
				var newsNr = createElement("select", "");
				newsNr.style.width = "auto";
				
				li.appendChild(newsNr);
				ul.appendChild(li);
				for(var i=1;i<20;i++){
					var option = createElement(\'option\', "");
					option.text = i;
					option.value = i;
					
					if(document.all && !window.XMLHttpRequest) {
                        if(parseInt(i)==(parseInt(boxNewsNr)+1)) option.selected = true;
                    } else {
                        if(i==boxNewsNr) option.selected = true;
                    }					
						
					try{
						newsNr.add(option, null);
					}catch(e){
						newsNr.add(option);
					}
				}
				if(boxNewsNr==19) newsNr.options[18].selected = true;
				/*
				li = createElement("li", "");
				li.innerHTML = "Kiemelt szin:";
				ul.appendChild(li);
				li = createElement("li", "");
				var catCSSSelect = createElement("select", "");
				catCSSSelect.style.width = "auto";				
				var option = createElement(\'option\', "");
				option.innerHTML = "Nincs";
				option.value = "0";
				try{
					catCSSSelect.add(option, null);
				}catch(e){
					catCSSSelect.add(option);
				}
				for(var i=0;i<catCSS.length;i++){
					if(catCSS[i]!=""){
						var option = createElement(\'option\', "");
						var temp = catCSS[i].split("|");
						option.text = decodeText(temp[1]);
						option.value = decodeText(temp[0]);
						if(boxCssID==temp[0]) option.selected = true;
						try{
							catCSSSelect.add(option, null);
						}catch(e){
							catCSSSelect.add(option);
						}
					}
				}
				li.appendChild(catCSSSelect);
				ul.appendChild(li);
				*/
				
				li = createElement("li", "");
				li.innerHTML = "Tipus:";
				ul.appendChild(li);
				li = createElement("li", "");
				var type = createElement("select", "");
				type.style.width = "auto";				
				type.onchange = function(){obj.changeCatType(this);}
				var option = createElement(\'option\', "");				
				option.innerHTML = "Hir kategoria";
				option.value = "1";
				if(boxType == 1)  option.selected = "true";
				type.appendChild(option);
				/*option = createElement(\'option\', "");
				option.innerHTML = "HTML";
				option.value = "2";
				if(boxType == 2)  option.selected = "true";
				type.appendChild(option);
				option = createElement(\'option\', "");
				option.innerHTML = "SQL";
				option.value = "3";
				if(boxType == 3)  option.selected = "true";
				type.appendChild(option);*/				
				li.appendChild(type);
				ul.appendChild(li);
				
				li = createElement("li", "");
				var span = createElement("span", "");
				span.id = "cat_content_" + boxID; 
				if(boxType == 1){
					span.style.display = "none";
					span.style.visibility = "hidden";
				}else{
					span.style.display = "block";
					span.style.visibility = "visible";
				}					
				li.appendChild(span);
				ul.appendChild(li);
				
				var ul2 = createElement("ul", "edit");
				
				
				var li2 = createElement("li", "");
				li2.innerHTML = "Tartalom:";
				ul2.appendChild(li2);
				
				li2 = createElement("li", "");
				var textArea = createElement("textarea", "");
				textArea.style.width = "100%";
				textArea.style.height = "80px";
				if(boxType == 2) textArea.value = boxHTML;
					else if(boxType == 3) textArea.value = boxSQL;
				li2.appendChild(textArea);
				ul2.appendChild(li2);
				
				span.appendChild(ul2);
				
				li = createElement("li", "");
				var button = createElement("input", "");
				button.type = "button";
				button.value = "Módosit";
				button.style.width = "auto";
				button.onclick = function(){
					//obj.updateBox(inputTitle, newsNr, categoryURL, catCSSSelect, type, textArea);
					obj.updateBox(inputTitle, newsNr, categoryURL, type, textArea);
				}
				li.appendChild(button);
				ul.appendChild(li);
				ul.style.display = \'block\';

			}									
		}
		
		obj.updateBox = function(title, newsNr, categoryURL, type, content){
			var nr = newsNr.options[newsNr.selectedIndex].value;
			boxNewsNr = nr;
			boxTitle = title.value;
			boxTitleURL = categoryURL.value; 
			boxType = type.options[type.selectedIndex].value;
			boxCssID = 0;
			boxCSS = \'\';
			if(boxType == 1){
				boxHTML = "";
				boxSQL = "";
			}else if(boxType == 2){
				boxHTML = content.value;
				boxSQL = "";
			}else if(boxType == 3){
				boxHTML = "";
				boxSQL = content.value;
			}

			boxName.innerHTML = \'<a href="\' + boxTitleURL + \'" class="boxName" target="_blank">\' + boxTitle + \'</a>\';
			if(IE)
			  boxName.attachEvent("onmousedown", stopHere);
			else
			  boxName.onmousedown = stopHere;				
			Ajax.send("ajax.php", "POST", "action=update_category&id=" + boxID + "&title=" + boxTitle + "&url=" + encodeURIComponent(boxTitleURL) + "&news_nr=" + boxNewsNr + "&page_id=" + document.getElementById(\'page_id\').value + "&cat_type=" + boxType + "&cat_css_id=" + boxCssID + "&cat_css_name=" + encodeURIComponent(boxCSS) + "&cat_html=" + encodeURIComponent(boxHTML) + "&cat_sql=" + encodeURIComponent(boxSQL), null, null);			
		}
		
		obj.onDragStart = function(x, y){			
			var top = findPosY(this);
			var left = findPosX(this);
			var w = this.offsetWidth;
			this.style.width = w + "px";
			this.style.position = "absolute";			
			if(!IE){
				this.style.left = (left+1)+"px";
				this.style.top = (top+1)+"px";						
			}else{
				this.style.left = (left+11)+"px";
				this.style.top = (top+17)+"px";						
			}
			this.parentNode.insertBefore(moduleGhost, this);
			moduleGhost.style.height = (this.offsetHeight+2) +"px";	
			moduleGhost.style.width = (this.offsetWidth+2) +"px";	
			
		}
		obj.onDrag = function(nx, ny){
			var col = null;
			if ((nx+this.offsetWidth/2)>=findPosX(document.getElementById(0))) col=document.getElementById(0);
			if ((nx+this.offsetWidth/2)>=findPosX(document.getElementById(1))) col=document.getElementById(1);
			if ((nx+this.offsetWidth/2)>=findPosX(document.getElementById(2))) col=document.getElementById(2);
	
			if(col!=null){
				if(moduleGhost.parentNode!=col){
					var pN = moduleGhost.parentNode;				
					pN.removeChild(moduleGhost);
					col.appendChild(moduleGhost);				
				}
				var myPos = 0;							
				
				var elems = null;
				elems = Array();
				for(var i=0;i<col.childNodes.length;i++){
					if(col.childNodes[i].tagName == "DIV" && col.childNodes[i]!=this){			
						elems.push(col.childNodes[i]);
					}
				}
				for(var i=0;i<elems.length;i++){
					if(elems[i] == moduleGhost){
						myPos = i;
						break;
					}
				}		

				if (myPos!=0 && ny<=findPosY(elems[myPos-1])) {	
					col.removeChild(moduleGhost);	
					col.insertBefore(moduleGhost, elems[myPos-1]);	
				}			
				
				if (myPos!=(elems.length-1) && ny>=findPosY(elems[myPos+1])) {			
					if (elems[myPos+2]) {
						col.insertBefore(moduleGhost, elems[myPos+2]);
					} else {						
						col.appendChild(moduleGhost);
					}	
				}
				
			}		
		}
		obj.onDragEnd = function(x,y){
			this.style.position=  "static"
			moduleGhost.parentNode.insertBefore(this, moduleGhost);		
			moduleGhost.parentNode.removeChild(moduleGhost);		
			var cols = Array();
			for(var i=0;i<3;i++){
				var parent = document.getElementById(i);
				cols[i] = \'\';
				for(var j=0;j<parent.childNodes.length;j++){
					if(parent.childNodes[j].tagName=="DIV")
						cols[i] += parent.childNodes[j].id + ";";
				}
			}
			cols[0] = "&first=" + cols[0];
			cols[1] = "&second=" + cols[1];
			cols[2] = "&third=" + cols[2];	
			var page_id = "&page_id=" + document.getElementById(\'page_id\').value;
			Ajax.send("ajax.php", "POST", "action=resort" + cols[0] + cols[1] + cols[2] + page_id, null, null);
		}		
	}
	this.build();
	
}
function test(r){
	alert(r.responseText);
}
var Drag = {	
	obj : null,

    init : function(o, oRoot, minX, maxX, minY, maxY, bSwapHorzRef, bSwapVertRef, fXMapper, fYMapper)
    {
        o.onmousedown    = Drag.start;

        o.hmode            = bSwapHorzRef ? false : true ;
        o.vmode            = bSwapVertRef ? false : true ;

        o.root = oRoot && oRoot != null ? oRoot : o ;

        if (o.hmode  && isNaN(parseInt(o.root.style.left  ))) o.root.style.left   = "0px";
        if (o.vmode  && isNaN(parseInt(o.root.style.top   ))) o.root.style.top    = "0px";
        if (!o.hmode && isNaN(parseInt(o.root.style.right ))) o.root.style.right  = "0px";
        if (!o.vmode && isNaN(parseInt(o.root.style.bottom))) o.root.style.bottom = "0px";

        o.minX    = typeof minX != \'undefined\' ? minX : null;
        o.minY    = typeof minY != \'undefined\' ? minY : null;
        o.maxX    = typeof maxX != \'undefined\' ? maxX : null;
        o.maxY    = typeof maxY != \'undefined\' ? maxY : null;

        o.xMapper = fXMapper ? fXMapper : null;
        o.yMapper = fYMapper ? fYMapper : null;				
		
        o.root.onDragStart    = new Function();
        o.root.onDragEnd    = new Function();
        o.root.onDrag        = new Function();  
    },

    start : function(e)
    {
        var o = Drag.obj = this;
		
		
        e = Drag.fixE(e);		
        var y = parseInt(o.vmode ? o.root.style.top  : o.root.style.bottom);
        var x = parseInt(o.hmode ? o.root.style.left : o.root.style.right );
        o.root.onDragStart(x, y);

        o.lastMouseX    = e.clientX;
        o.lastMouseY    = e.clientY;

        if (o.hmode) {
            if (o.minX != null)    o.minMouseX    = e.clientX - x + o.minX;
            if (o.maxX != null)    o.maxMouseX    = o.minMouseX + o.maxX - o.minX;
        } else {
            if (o.minX != null) o.maxMouseX = -o.minX + e.clientX + x;
            if (o.maxX != null) o.minMouseX = -o.maxX + e.clientX + x;
        }

        if (o.vmode) {
            if (o.minY != null)    o.minMouseY    = e.clientY - y + o.minY;
            if (o.maxY != null)    o.maxMouseY    = o.minMouseY + o.maxY - o.minY;
        } else {
            if (o.minY != null) o.maxMouseY = -o.minY + e.clientY + y;
            if (o.maxY != null) o.minMouseY = -o.maxY + e.clientY + y;
        }
		Drag.drag(e);
        document.onmousemove    = Drag.drag;
        document.onmouseup        = Drag.end;
			
        return false;
    },

    drag : function(e)
    {
		moduleGhost.style.display = "block";
        e = Drag.fixE(e);
        var o = Drag.obj;
        var ey    = e.clientY;
        var ex    = e.clientX;       
		var y = parseInt(o.root.style.top);
        var x = parseInt(o.root.style.left);
        var nx, ny;

		nx = x + ex - o.lastMouseX;
        ny = y + ey - o.lastMouseY;

        Drag.obj.root.style[o.hmode ? "left" : "right"] = nx + "px";
        Drag.obj.root.style[o.vmode ? "top" : "bottom"] = ny + "px";
        Drag.obj.lastMouseX    = ex;
        Drag.obj.lastMouseY    = ey;

        Drag.obj.root.onDrag(nx, ny);
		
        return false;
    },

    end : function()
    {
        
		document.onmousemove = null;
        document.onmouseup   = null;
        Drag.obj.root.onDragEnd(    parseInt(Drag.obj.root.style[Drag.obj.hmode ? "left" : "right"]), 
                                    parseInt(Drag.obj.root.style[Drag.obj.vmode ? "top" : "bottom"]));		
		
        Drag.obj = null;
    },

    fixE : function(e)
    {
        if (typeof e == \'undefined\') e = window.event;
        if (typeof e.layerX == \'undefined\') e.layerX = e.offsetX;
        if (typeof e.layerY == \'undefined\') e.layerY = e.offsetY;
        return e;
    }
}
var Ajax = new Object();
Ajax.send = function(url, method, parameters, callBack, data){	
	var HttpRequest = "";
	try{
		HttpRequest = new ActiveXObject("Msxml2.XMLHTTP");
	}catch(e){
		try{
			HttpRequest = new ActiveXObject("Microsoft.XMLHTTP");
		}catch(e){
			try{
				HttpRequest = new XMLHttpRequest();
			}catch(e){
				alert("XMLHttpRequest not suported!");
				return false;
			}
		}
	} 
	HttpRequest.onreadystatechange = function() {
		if (HttpRequest.readyState == 4) {// only if req shows "loaded"
			if (HttpRequest.status < 400) {// only if "OK"				
				if(callBack!=null){
					if(data==null)	callBack(HttpRequest);
						else callBack(HttpRequest, data);
				}
			} else {
				alert("There was a problem loading data :\\n" + HttpRequest.status+ "/" + HttpRequest.statusText);
			}
		}

	}
	if (method=="POST") {
		HttpRequest.open("POST", url, true);
		HttpRequest.setRequestHeader(\'Content-Type\', \'application/x-www-form-urlencoded\');
		HttpRequest.send(parameters);
	} else {		
		HttpRequest.open("GET", url + "?" + parameters, true);
		HttpRequest.send(null);

	}
}

function showResponse(request){	
	var answer = request.responseText.split(":");			
	
	var temp = null;
	for(var i=0;i<answer.length;i++){				
		temp = answer[i].split("|");		
		if(temp.length>1){			
			new Box(decodeText(temp[0]), decodeText(temp[4]), decodeText(temp[1]), decodeText(temp[2]), decodeText(temp[5]), decodeText(temp[3]), decodeText(temp[8]), decodeText(temp[6]), decodeText(temp[7]), decodeText(temp[9]), decodeText(temp[10]));
		}
	}
}
function showNewRSSModule(obj){
	var parent = document.getElementById(\'menuTD\');
	parent.rowSpan = 2;
	parent.removeChild(obj)
	
	var container = document.createElement("div");
	container.className = "menuContainer";	
	container.id = "menuContainer";
	parent.appendChild(container);	
	
	var content = document.createElement("div");
	content.className = "menuContent";
	
	//
	var contentClose = document.createElement("div");
	contentClose.className = "menuContentClose";
	contentClose.innerHTML = \'<img src="i/closeMod.gif" onclick="closeNewRSSModule()" class="close" />\';
	content.appendChild(contentClose);
	
	var addNewFeedC = document.createElement("div");
	addNewFeedC.className = "addNewFeedC";
	content.appendChild(addNewFeedC);
	addNewFeedC.innerHTML = \'<a href="#" class="menuContent" onClick="showBox(\\\'addNewRSSContainer\\\');">&Uacute;j RSS</a>\';
	
	container.appendChild(content);
}
function closeNewRSSModule(){		
	closeBox(\'menuContainer\');
	var parent = document.getElementById(\'menuTD\');
	parent.rowSpan = 0;			
	parent.innerHTML = \'<a href="#" onClick="showNewRSSModule(this);">&Uacute;j RSS felvitele</a>\';	
	alert(parent.rowSpan);
}

function showmenuContent(){
	
}
function closeBox(id){
	try{		
		var parent = document.getElementById(id).parentNode;
		parent.removeChild(document.getElementById(id));
	}catch(e){
		document.getElementById(id).style.display = "none";
	}	
	return false;
}
function showBox(id){
	document.getElementById(id).style.display = "block";
	return false;
}
function addNewRSSBOXProcess(request,data){	
	if(request.responseText!=-1){
		var answer = request.responseText.split("|");				
		new Box(decodeText(answer[0]), decodeText(answer[1]), decodeText(data[0]), 0, 0, 7, 0, 1, decodeText(answer[3]));		
	}else alert(\'Ismeretlen RSS forras!\');
}
function addNewRSSBOX(){	
	var rss = document.getElementById(\'newRSSFeed\').value;	
	var pars = "action=add_new_rss&rss=" + encodeURIComponent(rss);
	var url = "ajax.php";
	var data = Array();
	data[0] = rss;
	Ajax.send(url, "POST", pars, addNewRSSBOXProcess, data);	
}

function init(){ 		
		var url = \'ajax.php\';
		var pars = \'action=get_page_categories&page_id=\' + document.getElementById(\'page_id\').value;		
		Ajax.send(url, "POST", pars, showResponse, null);	
		url = \'ajax.php\';
		pars = \'action=get_css\';		
		Ajax.send(url, "POST", pars, getCSS, null);	
}
function getCSS(request){
	var answer = request.responseText.split(":");
	for(var i=0;i<answer.length;i++){		
		catCSS[i] = answer[i];
	}
}
function addNewCategoryProcess(request){
	var answer = request.responseText.split("|");			
	var boxHTML = "";
	var boxSQL = "";
	if(answer[0]==-1){
		alert(\'Hiba ilyen nevü kategória már létezik és az oldalba is be van sorolva!\');
		return false;
	}else if(answer[0]==-2){
		alert(\'Hiba ilyen nevü kategória már létezik és az oldalba a besorolása megtörtént!\');				
		new Box(decodeText(answer[1]), decodeText(answer[3]), 0, 1, 10, decodeText(answer[2]), decodeText(answer[4]), decodeText(answer[5]), decodeText(answer[6]), decodeText(answer[7]), decodeText(answer[8]));				
		return false;
	}else{
		alert("Sikeres felvitel!");
		if(answer[4]==2){
			boxHTML = decodeText(answer[7]);
			boxSQL = "";
		}else if(answer[4]==3){
			boxHTML = "";
			boxSQL = decodeText(answer[7]);
		}
		new Box(decodeText(answer[1]), decodeText(answer[3]), 0, 1, 10, decodeText(answer[2]), decodeText(answer[4]), decodeText(answer[5]), decodeText(answer[6]), boxHTML, boxSQL);
		return false;
	}	
}
function addNewCategory(){
	var catName = document.getElementById(\'newCategory\').value;
	var catURL = encodeURIComponent(document.getElementById(\'newCategoryURL\').value);
		
	var catType = document.getElementById(\'newCatType\').options[document.getElementById(\'newCatType\').selectedIndex].value;
	var catContent = encodeURIComponent(document.getElementById(\'catContent\').value);
	var catCssID = 0;
	var catCssName = \'\';
	if(catCssID==0) catCssName = \'\';
	
	if(catName!=""){
		var pars = "action=add_new_category&page_id=" + document.getElementById(\'page_id\').value + "&cat_name=" + catName + "&cat_url=" + catURL + "&cat_type=" + catType + "&cat_content=" + catContent + "&cat_css_id=" + catCssID + "&cat_css=" + catCssName;				
		var url = "ajax.php";
		var data = Array();	
		Ajax.send(url, "POST", pars, addNewCategoryProcess, null);
	}else alert(\'Kérem adjon meg egy kategória nevet!\');
}
var timeOut = "";
var timeOutHtmlbox = "";

function doHtmlboxSearch(obj){ //launch the search process
	window.clearTimeout(timeOutHtmlbox);
	if(obj.value.length>=3) timeOutHtmlbox = setTimeout("searchHtmlbox()", 1000);
}
function searchHtmlboxProcess(request){
	var answer = request.responseText;
	var obj = document.getElementById(\'htmlboxResults\');
	var htmlboxResponse = new Array();
	htmlboxResponse = answer.split(":");				

	for(var i=0;i<obj.options.length;i++){
		obj.remove(i);
	}
	obj.options.length = 0;
	for(var i=0;i<htmlboxResponse.length;i++){
		if(htmlboxResponse[i]!=""){
			var temp = htmlboxResponse[i].split("|");		
			var option = document.createElement(\'option\');
			option.text = decodeText(temp[1]);
			option.value = decodeText(temp[0]);
			if(option.value!="undefined"){
				try{
					obj.add(option, null);
				}catch(ex){
					obj.add(option);
				}
			}
		}							
	}

}
function searchHtmlbox(){
	var keyword = document.getElementById(\'htmlboxKeyword\').value;
	var pars = "action=search_htmlbox&keyword=" + keyword;
	var url = "ajax.php";	
	Ajax.send(url, "POST", pars, searchHtmlboxProcess, null);
}
function addFoundedHtmlboxProcess(request){
	var answer = request.responseText;	
	if(answer==-1){
		alert(\'Ez a html doboz már be van sorolva ebbe az oldalba!\');
		return false;
	}else{ 	
		var temp = answer.split("|");	
        //legy okos domokos!
		new Box(decodeText(temp[0]), decodeText(temp[4]), 0, 1, 10, decodeText(temp[3]), decodeText(temp[8]), decodeText(temp[6]), decodeText(temp[7]), decodeText(temp[9]), decodeText(temp[10]));	
	}	
}
function addFoundedHtmlbox(obj){
	var htmlboxId = obj.options[obj.selectedIndex].value;	
	var pageId = document.getElementById(\'page_id\').value;
	var pars = "action=add_founded_htmlbox&page_id=" + pageId + "&htmlbox_id=" + htmlboxId;
	var url = "ajax.php";
	Ajax.send(url, "POST", pars, addFoundedHtmlboxProcess, null);
	
}




function doCategorySearch(obj){ //launch the search process
	window.clearTimeout(timeOut);
	if(obj.value.length>=3) timeOut = setTimeout("searchCategory()", 1000);
}
function searchCategoryProcess(request){
	var answer = request.responseText;
	var obj = document.getElementById(\'categoryResults\');
	var categoryResponse = new Array();
	categoryResponse = answer.split(":");				

	for(var i=0;i<obj.options.length;i++){
		obj.remove(i);
	}
	obj.options.length = 0;
	for(var i=0;i<categoryResponse.length;i++){
		if(categoryResponse[i]!=""){
			var temp = categoryResponse[i].split("|");		
			var option = document.createElement(\'option\');
			option.text = decodeText(temp[1]);
			option.value = decodeText(temp[0]);
			if(option.value!="undefined"){
				try{
					obj.add(option, null);
				}catch(ex){
					obj.add(option);
				}
			}
		}							
	}

}
function searchCategory(){
	var keyword = document.getElementById(\'categoryKeyword\').value;
	var pars = "action=search_category&keyword=" + keyword;
	var url = "ajax.php";	
	Ajax.send(url, "POST", pars, searchCategoryProcess, null);
}
function addFoundedCategoryProcess(request){
	var answer = request.responseText;	
	if(answer==-1){
		alert(\'Ez a kategória már be van sorolva ebbe az oldalba!\');
		return false;
	}else{ 	
		var temp = answer.split("|");	
		new Box(decodeText(temp[0]), decodeText(temp[4]), 0, 1, 10, decodeText(temp[3]), decodeText(temp[8]), decodeText(temp[6]), decodeText(temp[7]), decodeText(temp[9]), decodeText(temp[10]));	
	}	
}
function addFoundedCategory(obj){
	var catId = obj.options[obj.selectedIndex].value;	
	var pageId = document.getElementById(\'page_id\').value;
	var pars = "action=add_founded_category&page_id=" + pageId + "&cat_id=" + catId;
	var url = "ajax.php";
	Ajax.send(url, "POST", pars, addFoundedCategoryProcess, null);
	
}
function changeNewCatType(object){
	var textArea = document.getElementById(\'catContentSpan\');
	if(object.options[object.selectedIndex].value==1){
		textArea.style.visibility = "hidden";
		textArea.style.display = "none";
	}else{
		textArea.style.visibility = "visible";
		textArea.style.display = "block";
	}
}
function closeNewCatContent(obj){	
	var parent = obj.parentNode;
	parent.removeChild(obj);
	parent.innerHTML = \'<a href="#" onClick="openNewCatContent(this)">Kinyit</a>\';
	var newCatContent = document.getElementById(\'newCatContent\');
	newCatContent.style.display = "none";
}

function openNewCatContent(obj){	
	var parent = obj.parentNode;
	parent.removeChild(obj);
	parent.innerHTML = \'<a href="#" onClick="closeNewCatContent(this)">Bezár</a>\';
	var newCatContent = document.getElementById(\'newCatContent\');
	var catType = document.getElementById(\'newCatType\');
	var textArea = document.getElementById(\'catContentSpan\');
	if(catType.options[catType.selectedIndex].value==1){
		textArea.style.visibility = "hidden";
		textArea.style.display = "none";
	}else{
		textArea.style.visibility = "visible";
		textArea.style.display = "block";
	}
	newCatContent.style.display = "block";
}
function closeSearchCatContent(obj){
	var parent = obj.parentNode;
	parent.removeChild(obj);
	parent.innerHTML = \'<a href="#" onClick="openSearchCatContent(this)">Kinyit</a>\';	
	var searchCatContent = document.getElementById(\'searchCatContent\');
	searchCatContent.style.display = "none";
}

function openSearchCatContent(obj){
	var parent = obj.parentNode;
	parent.removeChild(obj);
	parent.innerHTML = \'<a href="#" onClick="closeSearchCatContent(this)">Bezár</a>\';
	var searchCatContent = document.getElementById(\'searchCatContent\');
	searchCatContent.style.display = "block";
}
function closeSearchHtmlboxContent(obj){
	var parent = obj.parentNode;
	parent.removeChild(obj);
	parent.innerHTML = \'<a href="#" onClick="openSearchHtmlboxContent(this)">Kinyit</a>\';	
	var searchHtmlboxContent = document.getElementById(\'searchHtmlboxContent\');
	searchHtmlboxContent.style.display = "none";
}
function openSearchHtmlboxContent(obj){
	var parent = obj.parentNode;
	parent.removeChild(obj);
	parent.innerHTML = \'<a href="#" onClick="closeSearchHtmlboxContent(this)">Bezár</a>\';
	var searchHtmlboxContent = document.getElementById(\'searchHtmlboxContent\');
	searchHtmlboxContent.style.display = "block";
}
function closeEditRSS(obj, rssID, boxID){
	var parent = obj.parentNode;
	parent.removeChild(obj);
	parent.innerHTML = \'<a href="#" onclick="openEditRSS(this, \\\'\' + rssID + \'\\\', \\\'\' + boxID + \'\\\');">Szerkeszt</a>&nbsp;<img src="../i/closeMod.gif" style="cursor:pointer;" onclick="delRSSFromCategory(\\\'\' + boxID + \'\\\', \\\'\' + rssID + \'\\\');" />\';
	var li = parent.parentNode;
	li.removeChild(li.childNodes[2]);	
	return false;
}
function showRSSEditForm(request, data){
	var answer = request.responseText;
	data[0].innerHTML = answer;
}
function openEditRSS(obj, rssID, boxID){
	var parent = obj.parentNode;
	parent.removeChild(obj);
	parent.innerHTML = \'<a href="#" onclick="closeEditRSS(this, \\\'\' + rssID + \'\\\', \\\'\' + boxID + \'\\\');">Bez&aacute;r</a>&nbsp;<img src="../i/closeMod.gif" style="cursor:pointer;" onclick="delRSSFromCategory(\\\'\' + boxID + \'\\\', \\\'\' + rssID + \'\\\');" />\';		
	
	var li = parent.parentNode;	
	var editContent = document.createElement(\'div\');
	li.appendChild(editContent);
	
	var pars = "action=get_rss_info&rss_id=" + rssID + "&cat_id=" + boxID ;
	var url = "ajax.php";	
	var data = Array();
	data[0] = editContent;
	Ajax.send(url, "POST", pars, showRSSEditForm, data);				
	return false;
}
function updateRSSProcess(request, data){
	var rssLink = document.getElementById(\'rss_link_\' + data[1] + \'_\' + data[0]);
	rssLink.href = data[2];
	rssLink.innerHTML = data[3];
}
function updateRSS(boxID, rssID){
	var ul = document.getElementById(\'edit_rss_\' + boxID + \'_\' + rssID);	
	var inputs = ul.getElementsByTagName(\'input\');
	var selects = ul.getElementsByTagName(\'select\');
	var agency = 0;
	var type = 0;
	var rssName = "";
	var rssURL = "";
	var pattern = "";
	var auxURL =  "";
	var matchLink = "";
	var matchTitle = "";
	var matchLead = "";
	var period = 0;
    var news_order = \'desc\';
	var status = 0;
	for(var i=0;i<selects.length;i++){
		switch(selects[i].name){
			case "agencies": agency = selects[i].options[selects[i].selectedIndex].value;break;
			case "type": type = selects[i].options[selects[i].selectedIndex].value;break;
			case "news_order": news_order = selects[i].options[selects[i].selectedIndex].value;break;
			case "status": status = selects[i].options[selects[i].selectedIndex].value;break;
		}		
	}
	for(var i=0;i<inputs.length;i++){	
		switch(inputs[i].name){
			case "rss_name": rssName = inputs[i].value;break;
			case "rss_url": rssURL = inputs[i].value;break;
			case "pattern": pattern = inputs[i].value;break;
			case "aux_url": auxURL = inputs[i].value;break;
			case "match_link": matchLink = inputs[i].value;break;
			case "match_title": matchTitle = inputs[i].value;break;
			case "match_lead": matchLead = inputs[i].value;break;
			case "period": period = inputs[i].value;break;
		}
	}
	
	var pars = "action=update_rss&rss_id=" + rssID + "&box_id=" + boxID + "&rss_name=" + rssName + "&rss_url=" + encodeURIComponent(rssURL) + "&agencies=" + agency + "&type=" + type + "&status=" + status + "&news_order="+news_order+"&pattern=" + encodeURIComponent(pattern)  + "&aux_url=" + encodeURIComponent(auxURL) + "&match_link=" + matchLink + "&match_title=" + matchTitle + "&match_lead=" + matchLead + "&period=" + period ;
	var url = "ajax.php";	
	var data = Array();
	data[0] = boxID;
	data[1] = rssID;
	data[2] = rssURL;
	data[3] = rssName;
	Ajax.send(url, "POST", pars, updateRSSProcess, data);
	
}
function  closeAddNewLink(obj, boxID){
}

function  openAddNewLink(obj, boxID){
}
function  closeAddNewRSS(obj, boxID){
	var parent = obj.parentNode;
	parent.removeChild(obj);
	parent.innerHTML = \'<a href="#" onclick="openAddNewRSS(this, \\\'\' + boxID + \'\\\');">&Uacute;j RSS</a>\';
	parent.parentNode.parentNode.removeChild(parent.parentNode.parentNode.childNodes[3]);
	return false;
}
function  openAddNewRSS(obj, boxID){
	var parent = obj.parentNode;
	parent.removeChild(obj);
	parent.innerHTML = \'<a href="#" onclick="closeAddNewRSS(this, \\\'\' + boxID + \'\\\');">Bez&aacute;r</a>\';

	var addNewRSS = document.createElement("div");
	addNewRSS.style.padding = "5px 10px 5px 10px";
	addNewRSS.innerHTML = "";
	addNewRSS.innerHTML += \'<b>RSS keres&#337;:</b><br />\';
	addNewRSS.innerHTML += \'<input type="text" id="rss_name_\' + boxID + \'" onkeyup="doRSSSearch(this, \\\'\' + boxID + \'\\\')" /><br />\';
	addNewRSS.innerHTML += \'<b>RSS tal&aacute;latok:</b><br />\';
	addNewRSS.innerHTML += \'<select id="rss_results_\' + boxID + \'" multiple size="10" ondblclick="addNewRSS(this, \\\'\' + boxID + \'\\\');"></select><br />\';	
	addNewRSS.className = "editDIV";
	
	parent.parentNode.parentNode.appendChild(addNewRSS);
	return false;
}
function doRSSSearch(obj, boxID){
	window.clearTimeout(timeOut);	
	if(obj.value.length>=3) timeOut = setTimeout("searchRSS(\'" + obj.value+ "\', \'" + boxID + "\')", 1000);
}
function searchRSS(keyword, boxID){			
	var pars = "action=search_rss&keyword=" + keyword ;
	var url = "ajax.php";	
	var data = Array();
	data[0] = boxID;
	Ajax.send(url, "POST", pars, searchRSSProcess, data);	
}
function searchRSSProcess(request, data){
	var answer = request.responseText;
	var obj = document.getElementById(\'rss_results_\' + data[0]);
	var categoryResponse = new Array();
	categoryResponse = answer.split(":");				

	for(var i=0;i<obj.options.length;i++){
		obj.remove(i);
	}
	obj.options.length = 0;
	for(var i=0;i<categoryResponse.length;i++){
		if(categoryResponse[i]!=""){
			var temp = categoryResponse[i].split("|");		
			var option = document.createElement(\'option\');
			option.text = decodeText(temp[1]);
			option.value = decodeText(temp[0]);
			if(option.value!="undefined"){
				try{
					obj.add(option, null);
				}catch(ex){
					obj.add(option);
				}
			}
		}							
	}
}
function addNewRSS(results, boxID){
	var pars = "action=add_new_rss&rss_id=" + results.options[results.selectedIndex].value + "&cat_id=" + boxID;
	var url = "ajax.php";	
	var data = Array();
	data[0] = boxID;
	Ajax.send(url, "POST", pars, addNewRSSProcess, data);	
}
function addNewRSSProcess(request, data){	
	if(request.responseText!="-1"){
		var answer = request.responseText.split("|");		
		var ul = document.getElementById(\'box_rss_\' + data[0]);
		var li = createElement("li", "");		
		li.id = \'rss_\' + decodeText(answer[0]);
		li.innerHTML = \'<div style="float:right;"><a href="#" onclick="openEditRSS(this, \\\'\' + decodeText(answer[0]) + \'\\\', \\\'\' + data[0] + \'\\\');">Szerkeszt</a>&nbsp;<img src="../i/closeMod.gif" style="cursor:pointer;" onclick="delRSSFromCategory(\\\'\' + data[0] + \'\\\', \\\'\' + decodeText(answer[0]) + \'\\\');" /></div><a href="\' + decodeText(answer[2]) + \'" id="rss_link_\' + decodeText(answer[0]) + \'_\' + data[0] + \'">\' + decodeText(answer[1]) + \'</a>\';
		ul.appendChild(li);
	}else alert(\'Ez a hírfolyam már szerepel ebben a kategóriában!\');
}
function delRSSFromCategory(boxID, rssID){
	if(confirm(\'Biztosan benne?\')){
		var pars = "action=del_rss_from_category&rss_id=" + rssID + "&cat_id=" + boxID;
		var url = "ajax.php";			
		Ajax.send(url, "POST", pars, null, null);
		var ul = document.getElementById(\'box_rss_\' + boxID);
		var li = document.getElementById(\'rss_\' + rssID);
		ul.removeChild(li);
	}
}
function changeType(obj, boxID, rssID){
	var ul = document.getElementById(\'rss_type_\' + boxID + \'_\' + rssID);
	if(obj.options[obj.selectedIndex].value==1){
		ul.style.display = "none";
		ul.style.visibility = "hidden";
	}else if(obj.options[obj.selectedIndex].value==2){
		ul.style.display = "block";
		ul.style.visibility = "visible";
	}
}
</script>
'; ?>

<input type="hidden" id="page_id" value="<?php echo $this->_tpl_vars['page_id']; ?>
" />
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">  
  <tr>
  	<td colspan="3" class="page_title"><?php echo $this->_tpl_vars['page']['page_name']; ?>
 - oldal rendez&eacute;se</td>
  </tr>
  <tr>
  	<td colspan="3">
		<table border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td style="padding-right:8px;" valign="top">
				<div class="box">
					<div class="module">
						<div class="mHeader">
							<div class="boxName"><b>&Uacute;j kateg&oacute;ria</b></div>
							<div class="boxControls" style="display:block;"><a href="#" onClick="openNewCatContent(this)">Kinyit</a></div>
						</div>
						<div class="boxContent" id="newCatContent">
							<b>Kateg&oacute;ria n&eacute;v:</b><br />
							<input type="text" name="newCategory" id="newCategory" value="" /><br />
							<b>Kateg&oacute;ria URL:</b><br />
							<input type="text" name="newCategoryURL" id="newCategoryURL" value="" /><br />
														<b>Tipus:</b><br />
							<select name="newCatType" id="newCatType" onChange="changeNewCatType(this);">
								<option value="1" selected>H&iacute;r kateg&oacute;ria</option>
															</select><br />
							<span id="catContentSpan" style="visibility:hidden;display:none;">
								<b>Tartalom:</b><br />
								<textarea id="catContent" rows="10"></textarea>
							</span>
							<input type="button" name="" value="Hozz&aacute;ad" onclick="addNewCategory();"  class="button" /><br />
						</div>
					</div>
				</div>
			</td>
			<td valign="top">
				<div class="box">
					<div class="module">
						<div class="mHeader">
							<div class="boxName"><b>Kateg&oacute;ria keres&#337;</b></div>
							<div class="boxControls" style="display:block;"><a href="#" onClick="openSearchCatContent(this)">Kinyit</a></div>
						</div>
						<div class="boxContent" id="searchCatContent">
							<b>Kateg&oacute;ria n&eacute;v:</b><br />
							<input type="text" name="categoryKeyword" id="categoryKeyword" value="" onKeyUp="doCategorySearch(this);" />
							<select name="categoryResults" id="categoryResults" size="10" ondblclick="addFoundedCategory(this)">
								
							</select>
						</div>
					</div>
				</div>
			</td>
			<td valign="top">
				<div class="box">
					<div class="module">
						<div class="mHeader">
							<div class="boxName"><b>HTML doboz keres&#337;</b></div>
							<div class="boxControls" style="display:block;"><a href="#" onClick="openSearchHtmlboxContent(this)">Kinyit</a></div>
						</div>
						<div class="boxContent" id="searchHtmlboxContent">
							<b>HTML doboz n&eacute;v:</b><br />
							<input type="text" name="htmlboxKeyword" id="htmlboxKeyword" value="" onKeyUp="doHtmlboxSearch(this);" />
							<select name="htmlboxResults" id="htmlboxResults" size="10" ondblclick="addFoundedHtmlbox(this)">
								
							</select>
						</div>
					</div>
				</div>
			</td>
			<td>&nbsp;</td>
		  </tr>
		</table>
	</td>
  </tr>
  <tr>
  	<td colspan="3" height="20"></td>
  </tr>
  <tr>
    <td class="container" width="33%" id="0" style="padding-right:8px; "></td>
    <td class="container" width="34%" id="1"></td>
    <td class="container" width="33%" id="2"></td>
  </tr>
</table>
<script language="javascript" type="text/javascript">	
	init();	
</script>