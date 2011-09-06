/*!
 * Maxima Javascript Engine Built on ExtJs 4.0, @author robThot, hirekmedia
 */
/**
 * static class AJAX
 */
Ext.define('AJAX', {
	statics: {
		/**
		 * @method ajax
		 * simple wrapper for the Ext.Ajax.request
		 * @param {string} 			url
		 * @param {string} 			method
		 * @param {string} (JSON) 	params
		 * @param {reference} 		callback
		 * @param {reference} 		scope
		 */
		ajax: function(url, method, params, callback, scope){
			Ext.Ajax.request({
			    url		: url,
			    scope 	: (typeof scope != "undefined" ? scope : null),
			    method	: method,
			    params	: params,
			    success	: callback
			});
		},
		/**
		 * @method get
		 * ajax get method
		 * @param {string} 			url
		 * @param {JSON}			params
		 * @param {reference} 		callback
		 * @param {reference} 		scope
		 */
		get : function(url, params, callback, scope){
			this.ajax(url, "get", params, callback, scope);
		},
		/**
		 * @method post
		 * ajax post method
		 * @param {string} 			url
		 * @param {JSON}		 	params
		 * @param {reference} 		callback
		 * @param {reference} 		scope
		 */
		post: function(url, params, callback, scope){
			this.ajax(url, "post", params, callback, scope);
		}
	},
	constructor: function() {}
});
/**
 * class Controller
 */
Ext.define('Controller', {
	
	model		: {},
	view		: {},
	
	constructor	: function() {
		this.getData();
	}
});
/**
 * class Model
 */
Ext.define('Model', {
	
	data 		: {},
	router 		: {},
	
	constructor	: function(reference) {
		// storing the relevant controller instance reference for the ajax callback
		this.router = reference;
		this.getAjaxData();
	}
});
/**
 * class View
 */
Ext.define('View', {
	
	render 		: function() {},
	constructor	: function() {}
});
/**
 * class FormBuilder
 */
Ext.define('FormBuilder', {
	
	statics     : {
		// its a wrapper for Ext.domHelper.append
		render 		: function(parent, data) {
			for(var i = 0,len = data.elements.length; i < len; i++){
				console.log(data.elements[i]);
			}
		}
	},
	
	constructor	: function() {
	}
});Ext.define('GroupController', {

	extend: 'Controller',
	
	//this time the relevant model is done with his job, all response data are stored in scope.data
	ajaxCallback: function(scope){
		var groupView = new GroupView();
		groupView.render(scope.data);
	},
	
	getData : function(){
		var self = this;
		new GroupModel(self);
	}
	
});Ext.define('LoginController', {

	extend: 'Controller',
	
	ajaxCallback: function(scope){
		var loginView = new LoginView();
		loginView.render(scope.data);
	},
	
	getData : function(){
		var self = this;
		new LoginModel(self);
	}
	
});Ext.define('LoginView', {

	extend: 'View',
	
	render: function(data){
		var thisView = FormBuilder.render(document.body,data);
	}
	
});Ext.define('LoginModel', {

	extend: 'Model',
	
	mapper: function(data){
		
		var self 	= this;
		// store the data
		self.data = Ext.JSON.decode(data.responseText);
		// call the callback method of the relevant controller
		self.router.ajaxCallback(self);
	},
	
	getAjaxData: function(){
		
		var self = this;
		
		AJAX.get(
			"login/",
			"",
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
  	
  	orders    	: ["login","logout","groups","demog"],
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
  	
  	// set up the routing order
    doJob     	: function(){
      if($$.order != "")
        switch($$.order){
		  case "login":
      	 	new LoginController();
          break;
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