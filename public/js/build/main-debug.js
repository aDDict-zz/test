/*!
 * Maxima Javascript Engine Built on ExtJs 4.0, @author robThot, hirekmedia
 */
Ext.define('AJAX', {
	statics: {
		ajax: function(url, method, params, callback){
			Ext.Ajax.request({
			    url		: url,
			    method	: method,
			    params	: params,
			    success	: callback
			});
		},
		get : function(url, params, callback){
			this.ajax(url, "get", params, callback);
		},
		post: function(url, params, callback){
			this.ajax(url, "post", params, callback);
		}
	},
	constructor: function() {}
});

Ext.define('Controller', {
	constructor: function() {
		this.getData();
	}
});

Ext.define('Model', {
	constructor: function(reference) {
		this.getAjax(reference);
	}
});

Ext.define('View', {
	constructor: function() {}
});
Ext.define('GroupsController', {

	extend: 'Controller',
	
	getData : function(){
		var groups = new GroupsModel(this);
	}
	
});Ext.define('GroupsView', {

	extend: 'View'
	
});Ext.define('GroupsModel', {

	extend: 'Model',
	
	data : {},
	
	router : {},
	
	mapper: function(data){ alert("asdsad");
		//this.data
	},
	
	getAjax: function(obj){
		this.router = obj;
		AJAX.get("groups", {'elso':'ELSO','masodik':{'valami':[0,1,2,3],'masvalami':'SEMMISEM'}},this.mapper);
	}
	
});// iframe hack for the ie history featureless
Ext.define('IEHH', {

	statics: {

		DEPO: "",
		
		init: function(){
			navigator.appName.match("Microsoft") != null ? this.setup() : "";
		},
  
		setup: function(){
  
			var thisIframe  = document.createElement('<iframe id="thisIframe" style="display:none;" src="about:blank" />'),
				  thisBody  = document.getElementsByTagName("body")[0];
        
			document.appendChild(thisIframe);
    
			Ext.TaskManager.start({
				run: IEHH.checkIframeContent,
				interval: 1000
			});
		},
  
		changeContent: function(urlPart){
			var thisIframe    = document.getElementById("thisIframe"),
					thisIframeDoc = thisIframe.contentWindow.document;

			thisIframeDoc.open();
			thisIframeDoc.write(urlPart);
			thisIframeDoc.close();
			IEHH.DEPO = urlPart;
		},
  
		checkIframeContent: function(){
			var thisIframe        = document.getElementById("thisIframe"),
					thisIframContent  = thisIframe.contentWindow.document.body.innerHTML;

			if (window.location.href.match("#") && thisIframContent != "") {
				var thisArr = window.location.href.split("#"),
					thisUrlPart = ["#",thisArr[1]].join("");
				if (thisUrlPart != thisIframContent) {
					window.location.href = [thisArr[0],thisIframContent].join("");
				}
			}
		},
    
    constructor: function() {}
	}
},
  function(){}
);

Ext.define('$$', {
  
  statics: {
  
    orders    : ["groups","demog","trillili","trallala"],
    order     : "",
    frontPage : "groups",
    
    init      : function(){
      if($$.ie)
        IEHH.setup();
      
      Ext.TaskManager.start({
        run: $$.getOrder,
        interval: 1000
      });
    },
  
    getOrder  : function(){
      var matches = window.location.href.match(/(.*)(#)(.*)/);
      if(matches != null){
        if(Ext.Array.indexOf($$.orders, matches[3]) == -1){
          window.location.href = [matches[1],"#",$$.frontPage].join("");
        } else {
          if($$.order != matches[3]){
            if($$.ie)
              IEHH.changeContent(["#",matches[3]].join(""));
              
            $$.order = matches[3];
            $$.doJob();
          }
        }
      } else {
        window.location.href = [window.location.href,"#",$$.frontPage].join("");
      }
    },
  
    doJob     : function(){
      if($$.order != "")
        switch($$.order){
          case "groups":
          	var groups = new GroupsController();
          break;
          case "demog":
          	var groups = new DemogController();
          break;
        }
    },
    
    constructor: function() {}
  }

},
  // initCallback
  function(){
    if(navigator.appVersion.match(/MSIE/))
      $$.ie = 1;
      
    $$.init();
  }
);