var testResponse = function(r){
	alert(r.responseText);
}
if (!IE) document.captureEvents(Event.MOUSEMOVE)

document.onmousemove = getMouseXY;

var tempX = 0
var tempY = 0

var mouseXY = Array();

function getMaxX() {
    return document.documentElement.clientWidth ? document.documentElement.clientWidth : document.body.clientWidth;
}
function getMaxY() {
    return document.documentElement.clientHeight ? document.documentElement.clientHeight : document.body.clientHeight;
}


function getMouseXY(e) {  	
  if (!e) 
		var e = window.event;
  if (IE) { // grab the x-y pos.s if browser is IE
    tempY = event.clientY + parseInt(document.documentElement.scrollTop);
    tempX = event.clientX + parseInt(document.documentElement.scrollLeft);
  } else {  // grab the x-y pos.s if browser is NS
    tempX = e.pageX
    tempY = e.pageY
  }  
  // catch possible negative values in NS4
  if (tempX < 0){tempX = 0}
  if (tempY < 0){tempY = 0}  
  // show the position values in the form named Show
  // in the text fields named MouseX and MouseY
  
  mouseXY[0] = tempX;
  mouseXY[1] = tempY;  
  
  return true;
}


function findPosX(obj) {
	var curleft = 0;
	if (obj.offsetParent) {
		while (obj.offsetParent) {
			curleft += obj.offsetLeft;
			obj = obj.offsetParent;
		}
	} else if (obj.x) {
        curleft += obj.x;
    }
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
function setLeft(o,oLeft) {
    o.style.left = oLeft + "px"
}

function setTop(o,oTop) {
    o.style.top = oTop + "px"
}

function setPosition(o,oLeft,oTop) {
    setLeft(o,oLeft)
    setTop(o,oTop)
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
				//alert("There was a problem loading data :\n" + HttpRequest.status+ "/" + HttpRequest.statusText);
			}
		}

	}
	if (method=="POST") {
		HttpRequest.open("POST", url);
		HttpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		HttpRequest.send(parameters);
	} else {		
		HttpRequest.open("GET", url + "?" + parameters, true);
		HttpRequest.send(null);

	}
}
/*
Ajax.send = function(url, meth, pars, callBack, data){		
	new Ajax.Updater('',url+ '?' + pars,{ method:meth , onComplete: callBack, onFailure: function(){alert('Failure!');}});
}
*/
function stopHere(evt){
	if (!evt) 
		var evt = window.event;
	
	evt.cancelBubble = true
	if (evt.stopPropagation)
		evt.stopPropagation()
}

String.prototype.nl2br = function(){
  return this.replace(/([^>])\n|\r|\r\n/g, '<br>');
}

String.prototype.br2nl = function(){	
	return this.replace(/<br>|<BR>/g, '\n');		
}

String.prototype.trim = function(){	
	return this.replace(/^\s*|\s*$/g,"");		
}

var DragTab = function(obj, box){	
	obj.onDragStart = function(x, y){			
			var top = findPosY(this);
			var left = findPosX(this);
			if(IE) var w = this.offsetWidth - 7;
				else var w = this.clientWidth;
			
			this.style.width = parseInt(w)+"px";
			this.style.margin = 0;
			
			this.style.position = "absolute";
			
			//this.style.top = (top)+"px";		
			if(IE){
				this.style.top = "0px";		
				this.style.left = (left-1)+"px";
			}else{
				this.style.top = "146px";		
				this.style.left = (left+16)+"px";
			}
			this.parentNode.insertBefore(moduleGhostTab, this);
			
			moduleGhostTab.style.height = this.offsetHeight +"px";	
			moduleGhostTab.style.width = this.offsetWidth +"px";										
		},
	obj.onDrag = function(nx, ny){
			var col = document.getElementById('tabs');
			//this.style.top = top;
			if(col!=null){
				if(moduleGhostTab.parentNode!=col){
					var pN = moduleGhostTab.parentNode;				
					pN.removeChild(moduleGhostTab);
					col.appendChild(moduleGhostTab);				
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
					if(elems[i] == moduleGhostTab){
						myPos = i;
						break;
					}
				}		

				if (myPos!=0 && nx<=findPosX(elems[myPos-1])) {	
					col.removeChild(moduleGhostTab);	
					col.insertBefore(moduleGhostTab, elems[myPos-1]);						
				}							
				if (myPos!=(elems.length-1) && nx>=findPosX(elems[myPos+1])) {			
					if (elems[myPos+2]) {
						col.insertBefore(moduleGhostTab, elems[myPos+2]);
					} else {						
						col.appendChild(moduleGhostTab);
					}	
				}
				
			}		
		},
	obj.onDragEnd = function(x,y){			
			if (!IE) document.captureEvents(Event.MOUSEMOVE)
			document.onmousemove = getMouseXY;
			this.style.position = "static"			
			moduleGhostTab.parentNode.insertBefore(this, moduleGhostTab);		
			moduleGhostTab.parentNode.removeChild(moduleGhostTab);
			this.style.width = "";
			this.style.margin = '0 0 0 1px';
			if(box!=null)	{
                box.dragEnd();
            }
	}
}
var DragDrop = function(obj, box, pageId){
	obj.onDragStart = function(x, y){						
			var top = findPosY(this);
			if(IE) {
                var w = this.offsetWidth - 7;
                var left = findPosX(this.offsetParent != null ? this.offsetParent : this);
            } else {
                var w = this.clientWidth;
                var left = findPosX(this);
            }
			
			this.style.width = parseInt(w)+"px";
			this.style.margin = 0;
			this.style.left = (left)+"px";

			this.style.top = (top)+"px";
			this.parentNode.insertBefore(moduleGhost, this);			
			moduleGhost.style.height = this.offsetHeight +"px";	
			moduleGhost.style.width = this.offsetWidth +"px";	
			this.style.position = "absolute";			
            
		},
	obj.onDrag = function(nx, ny){
			var col = null;
			
			if(document.getElementById(pageId + '_' + 0))	if ((nx+this.offsetWidth/2)>=findPosX(document.getElementById(pageId + '_' + 0))) col=document.getElementById(pageId + '_' + 0);
			if(document.getElementById(pageId + '_' + 1))	if ((nx+this.offsetWidth/2)>=findPosX(document.getElementById(pageId + '_' + 1))) col=document.getElementById(pageId + '_' + 1);
			if(document.getElementById(pageId + '_' + 2))	if ((nx+this.offsetWidth/2)>=findPosX(document.getElementById(pageId + '_' + 2))) col=document.getElementById(pageId + '_' + 2);
            //manufaktura - adminban is mukodjon
            if (col == null) {
                if(document.getElementById("0"))	if ((nx+this.offsetWidth/2)>=findPosX(document.getElementById("0"))) col=document.getElementById("0");
                if(document.getElementById("1"))	if ((nx+this.offsetWidth/2)>=findPosX(document.getElementById("1"))) col=document.getElementById("1");
                if(document.getElementById("2"))	if ((nx+this.offsetWidth/2)>=findPosX(document.getElementById("2"))) col=document.getElementById("2");
            }
	
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
		},
	obj.onDragEnd = function(x,y){
			if (!IE) document.captureEvents(Event.MOUSEMOVE)
			document.onmousemove = getMouseXY;
			this.style.position=  "static"			
			moduleGhost.parentNode.insertBefore(this, moduleGhost);		
			moduleGhost.parentNode.removeChild(moduleGhost);
			this.style.width = "";
			this.style.margin = '0px 5px 10px 5px';
			if(box!=null)	{
                box.dragEnd(pageId);		
            }
	}
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

        o.minX    = typeof minX != 'undefined' ? minX : null;
        o.minY    = typeof minY != 'undefined' ? minY : null;
        o.maxX    = typeof maxX != 'undefined' ? maxX : null;
        o.maxY    = typeof maxY != 'undefined' ? maxY : null;

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
        if (typeof e == 'undefined') e = window.event;
        if (typeof e.layerX == 'undefined') e.layerX = e.offsetX;
        if (typeof e.layerY == 'undefined') e.layerY = e.offsetY;
        return e;
    }
}
function mx_hp(hp,qu,mozqu) {
    if (document.all) {  
        document.write('<a href="#" onclick="this.style.behavior=\'url(#default#homepage)\';this.setHomePage(\''+hp+'\');" >'+qu+'</a>'); 
    } else if (document.getElementById) { 
        document.write('<div class="comment"  ><a class="mxt" style="border:0px;" href="'+hp+'" onclick="return false;">'+mozqu+'</a></div>');
    }
}
function attachHints() {
    var applyHints = function(r) {
        eval(r.responseText);
        for (var id in pageHints) {
            var o;
            if (o = document.getElementById(id)) {
                var hint = new Hint(escape(pageHints[id]));
                if (IE) {
                    o.attachEvent("onmouseover", hint.show);
                    o.attachEvent("onmouseout", hint.hide);
                } else {
                    o.addEventListener("mouseover", hint.show,false);
                    o.addEventListener("mouseout", hint.hide,false);
                }
            }
        }
    }
    var pars="action=get_hints";
    var url = "ajax/page.php";
    Ajax.send(url, "GET", pars, applyHints, null);
    var hint;
}
function clearParentByClassName(obj, cl,ask) {
    var o = obj;
    while (o && o.className != cl) {
        o = o.parentNode;
    }
    if (o && o.className == cl) {
        var p = o.parentNode;
        if (!ask || confirm('Biztos benne?')) {
            p.removeChild(o);
        }
    }
}
/**
 * parse the query string sent to this window into a global array of key = value pairs
  * this function should only be called once
   */
function parseQueryString() {
    queryParams = {};
    var s=window.location.search;
    if (s!='') {
        s=s.substring( 1 );
        var p=s.split('&');
        for (var i=0;i<p.length;i++) {
            var q=p[i].split('=');
            queryParams[q[0]]=q[1];
        }
    }
}
function evalScriptFromHtml(html, div) {
        var script = '';
        var rmax = 1000;
        var r = 0;
        var m;
        var i=0;
        var mark = new Array();
        var re = new RegExp("(<script([^<]*?)>(.*?)</script>)", "im");
        while ((r < rmax) && (m = re.exec(html))) {
            if (m[2].indexOf('src=') != -1) {
                html = html.replace(re, '%mark'+i+'%');
                mark[i] = m[1];
                i++;
            } else {
                script += m[3];
                html = html.replace(re, '');
            }
            r++;
        }
        if (script != '') {
            var s =  document.createElement('script');
            div.appendChild(s);	
            s.nodeValue = eval(script);
        }

}
function insertMonddMeg() {
	var div = document.getElementById('monddmeg_doboz');
	if (div) {
		var e = document.createElement('script');
		e.src = 'http://www.monddmeg.hu/api/mm.js?site_id=5&category=20&w=300&id=monddmeg_doboz';
		e.type="text/javascript"; 
		document.getElementsByTagName("head")[0].appendChild(e); 
	}
}


