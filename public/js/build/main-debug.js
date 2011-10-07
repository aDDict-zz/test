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
 * class Globals
 */
Ext.define('Globals', {
  statics: {
    DEPO   : {}    
  },
  constructor : function() {}
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
/**
 * static class Router
 */
Ext.define('Router', {
  
  statics: {
  	
    frontPage 	: "Group",
    route       : "", 
    
    init      	: function() {
      
      if(Router.ie)
        IEHH.setup();
      
      Ext.TaskManager.start({
        run: Router.getRoute,
        interval: 2000
      });
    },
  
    getRoute  	: function() {
      var matches = window.location.href.match(/(.#)(.*)/)[2];
          
      if(matches != null)
        if(Router.route != matches)
          if(typeof Globals.DEPO[matches] == "undefined" && matches != "") {
            try {
              // init and store(its ref) the relevant controller class
              (new Function(['Globals.DEPO["',matches,'"] = new ',matches,'Controller();'].join("")))();
              
              //set history for ie
              if(Router.ie)
                IEHH.changeContent(["#",matches].join(""));
              
              Router.route = matches;
            } catch(err) { console.log(matches);
              delete Globals.DEPO[matches];
              Router.setRoute(Router.frontPage);
            }
          }
        //else
          //TODO needs refact, we dont need the controller, only the view this time 
          //Globals.DEPO[matches].getData();
    },
    
    setRoute    : function(route) {
      window.location.href = [window.location.href.split("#")[0],"#",route].join("");
    },
  	
    constructor: function() {}
  }

},
  function(){
    if(navigator.appVersion.match(/MSIE/))
      Router.ie = 1;
      
    Router.init();
  }
);Ext.define('GroupController', {

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
	  
	  // "redirect" if everything is fine
	  if(scope.data.username) {
	    Router.setRoute(Router.frontPage);
	  } else {
	    // show the loginform
	    var loginView = new LoginView();
      loginView.render(scope.data);
	  }
	},
	
	getData : function(){
		var self = this;
		new LoginModel(self);
	}
	
});Ext.define('GroupsView', {

	extend: 'View',
	
	render: function(data){ //console.log("sadsad");
		//console.log(data);
	}
	
});Ext.define('LoginView', {

	extend: 'View',
	
	render: function(data){
	  
	  Ext.create('Ext.window.Window', {
      title     : 'Login',
      id        : 'loginBody',
      renderTo  : Ext.getBody(),
      height    : 180,
      width     : 250,
      layout    : 'fit',
      layout    : 'column',
      items: {  
        xtype     : 'form',
        height    : 145,
        width     : 237,
        items     : data.items,
        url       : data.action,
        buttons: [{
          text      : 'login',
          handler   : function() {
            var form = this.up('form').getForm();
            form.submit({
              success : function(form, action){
                //console.log(form, action);
              }
            });
          }
        }]
      }
    }).show();
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
	
});