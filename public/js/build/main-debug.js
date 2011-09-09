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
/*Ext.define('FormBuilder', {
	
	statics     : {
		render 		: function(parent, data, scope) {
		  var form  = data.name,
          cfg   = data.elements;
      
		  Ext.core.DomHelper.append(parent, cfg);
		  console.log(Ext.cache["ext-document"]);
		}
	},
	
	constructor	: function() {
	}
});*/Ext.define('GroupController', {

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
	  
		var cfg   = {
		  extend    : Ext.Window,
		  title     : data.title,
      renderTo  : Ext.getBody(),
      items     : data.items,
      url       : data.action,
      buttons: [{
          text: 'login',
          handler: function() {
            var form = this.up('form').getForm();
            form.submit({
              success : function(form, action){
                //console.log(form, action);
              }
            });
          }
      }]
		};
		
		Ext.create('Ext.form.Panel', cfg);
		
		/*Ext.create('Ext.form.Panel', {
        title: 'Basic Form',
        renderTo: Ext.getBody(),
        bodyPadding: 5,
        width: 350,
    
        // Any configuration items here will be automatically passed along to
        // the Ext.form.Basic instance when it gets created.
    
        // The form will submit an AJAX request to this URL when submitted
        url: 'save-form.php',
    
        items: [{
            fieldLabel: 'Field',
            name: 'theField'
        },{
            fieldLabel: 'Field',
            name: 'theField'
        },{
            fieldLabel: 'Field',
            name: 'theField'
        }],
    
        buttons: [{
            text: 'Submit',
            handler: function() {
                // The getForm() method returns the Ext.form.Basic instance:
                var form = this.up('form').getForm();
                if (form.isValid()) {
                    // Submit the Ajax request and handle the response
                    form.submit({
                        success: function(form, action) {
                           Ext.Msg.alert('Success', action.result.msg);
                        },
                        failure: function(form, action) {
                            Ext.Msg.alert('Failed', action.result.msg);
                        }
                    });
                }
            }
        }]
    });*/
		
		
		
	}
	
	/*submit: function(){
	  
	}*/
	
});

Ext.define('GroupModel', {

	extend: 'Model',
	
	mapper: function(data){
		
		var self 	= this;
		// store the data
		self.data 	= Ext.JSON.decode(data.responseText);
		// call the callback method of the relevant controller
		self.router.ajaxCallback(self);
	},
	
	getAjaxData: function(){
		
		var self = this;
		
		var datas = {
			'elso' : 'ELSO',
			'masodik' : {
				'valami' 	: [0,1,2,3],
				'masvalami' : 'SEMMISEM'
			}
		};
		
		AJAX.post(
			"group/",
			['data=',Ext.JSON.encode(datas)].join(''),
			this.mapper,
			self
		);
	}
	
});Ext.define('LoginModel', {

	extend: 'Model',
	
	mapper: function(data){
		
		var self 	= this;
		// store the data
		self.data = Ext.JSON.decode(data.responseText);
		// run the callback method of the relevant controller
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