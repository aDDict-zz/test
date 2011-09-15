if(!document.all){
	Document.prototype.loadXML = function (s) {      
	   var doc2 = (new DOMParser()).parseFromString(s, "text/xml");      
	   while (this.hasChildNodes())
		  this.removeChild(this.lastChild);         
	   for (var i = 0; i < doc2.childNodes.length; i++) {
		  this.appendChild(this.importNode(doc2.childNodes[i], true));
	   }
	};
	Document.prototype.__defineGetter__("xml", function () {
	   return (new XMLSerializer()).serializeToString(this);
	});
}
function loadXML(xml){	
	var xmlDoc = "";
	if (window.ActiveXObject){
		xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
		xmlDoc.async=false;
		xmlDoc.loadXML(xml);				
		return xmlDoc;
	}else if (document.implementation && document.implementation.createDocument){
		xmlDoc= document.implementation.createDocument("","",null);
		xmlDoc.loadXML(xml);								
		return xmlDoc;		
	}else{
		alert('Your browser cannot handle this script');
	}
}
var populateSelect = function(s, opts){		
	for(var i=0;i<s.options.length;i++){
		s.remove(i);
	}		
	s.options.length = 0;	
	for(var i=0;i<opts.length;i++){
		var option = document.createElement('option');
		option.text = opts[i].firstChild.nodeValue;
		option.value = opts.item(i).getAttributeNode('value').value;		
		if(option.value!="undefined"){
			try{				
				s.add(option, null);
			}catch(e){				
				s.add(option);
			}
		}			
	}	
}
var selectAll = function(s){	
	for(var i=0;i<s.length;i++){
		s[i].selected = true;
	}
}