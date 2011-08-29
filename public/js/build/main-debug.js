/*!
 * Maxima Javascript Engine Built on ExtJs 4.0, @author robThot, hirekmedia
 */
/**
 * simple wrapper for ext ajax 
 */
Ext.define('AJAX', {
	statics: {
		ajax: function(url, method, params, callback, scope){
			Ext.Ajax.request({
			    url		: url,
			    scope 	: (typeof scope != "undefined" ? scope : null),
			    method	: method,
			    params	: params,
			    success	: callback
			});
		},
		get : function(url, params, callback, scope){
			this.ajax(url, "get", params, callback, scope);
		},
		post: function(url, params, callback, scope){
			this.ajax(url, "post", params, callback, scope);
		}
	},
	constructor: function() {}
});
/**
 * controller
 */
Ext.define('Controller', {
	model		: {},
	view		: {},
	constructor	: function() {
		this.getData();
	}
});
/**
 * model
 */
Ext.define('Model', {
	
	data 		: {},
	router 		: {},
	
	constructor	: function(reference) {
		this.router = reference;
		this.getAjaxData();
	}
});
/**
 * view
 */
Ext.define('View', {
	render 		: function() {},
	constructor	: function() {}
});
Ext.define('GroupController', {

	extend: 'Controller',
	
	//this time the relevant model is done with his job, all response data are stored in scope.data
	ajaxCallback: function(scope){
		var groupView = new GroupsView();
		groupView.render(scope.data);
	},
	
	getData : function(){
		var self = this;
		new GroupsModel(self);
	}
	
});Ext.define('GroupsView', {

	extend: 'View',
	
	render: function(data){ console.log("sadsad");
		console.log(data);
	}
	
});Ext.define('GroupsModel', {

	extend: 'Model',
	
	mapper: function(data){
		var self 	= this;
		self.data 	= Ext.JSON.decode(data.responseText);
		self.router.ajaxCallback(self);
	},
	
	getAjaxData: function(){
		var self = this;
		AJAX.post(
			"group/",
			{'elso':'ELSO','masodik':{'valami':[0,1,2,3],'masvalami':'SEMMISEM'}},
			this.mapper,
			self
		);
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
  	
  	orders    	: ["groups","demog","trillili","trallala"],
    order     	: "",
    frontPage 	: "groups",
    
    init      	: function(){
      if($$.ie)
        IEHH.setup();
      
      Ext.TaskManager.start({
        run: $$.getOrder,
        interval: 1000
      });
    },
  
    getOrder  	: function(){
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
  
    doJob     	: function(){
      if($$.order != "")
        switch($$.order){
          case "groups":
      	 	new GroupController();
          break;
          case "demog":
          	new DemogController();
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