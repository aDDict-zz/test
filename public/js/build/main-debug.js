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
		 * @param {string} 			    url
		 * @param {string} 			    method
		 * @param {string} (JSON) 	params
		 * @param {reference} 		  callback
		 * @param {reference} 		  form
		 * @param {reference}       scope
		 */
		ajax: function(url, method, params, callback, scope, form){
			Ext.Ajax.request({
			    url		: url,
			    scope 	: (typeof scope != "undefined" ? scope : null),
			    form    : (typeof form != "undefined" ? form : null),
			    method	: method,
			    params	: params,
			    success	: callback
			});
		},
		/**
		 * @method get
		 * ajax get method
		 * @param {string} 			  url
		 * @param {JSON}			    params
		 * @param {reference} 		callback
		 * @param {reference} 		scope
		 * @param {reference}     form
		 */
		get : function(url, params, callback, scope, form){
			this.ajax(url, "get", params, callback, scope, form);
		},
		/**
		 * @method post
		 * ajax post method
		 * @param {string} 			  url
		 * @param {JSON}		 	    params
		 * @param {reference} 		callback
		 * @param {reference} 		scope
		 * @param {reference}     form
		 */
		post: function(url, params, callback, scope, form){
			this.ajax(url, "post", params, callback, scope, form);
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

	model      : {},
	view		   : {},
	data       : {},
	nameSpace  : "",
	showView   : true,

	getNameSpace: function() {
	  var matches    = this.$className.match(/(.*)(Controller)/);
	  this.nameSpace = matches[1];
	},

	constructor	: function() {
	  var self = this;
	  self.getNameSpace();
	  self.model  = eval(['new ',self.nameSpace,'Model()'].join(''));
	  self.model.router = self;

    if(this.showView == true) {
      self.view = eval(['new ',self.nameSpace,'View()'].join(''));
      self.view.scope = self;
    }
		this.getData();
	}
});

/**
 * class Model
 */
Ext.define('Model', {

	data 		  : {},
	router 		: {},
	toJson      : function(str) {
	  return Ext.decode(str);
	},

	constructor	: function(reference) {
		this.getAjaxData();
	}
});

/**
 * class View
 */
Ext.define('View', {

	scope       : {},
	render 		  : function() {},
	constructor	: function() {
	}
});

/**
 * class Debug
 */
Ext.define('Debug', {
  statics: {
    parse      : function(obj) {
      for(var i in obj) {
        console.log(i, obj);
      }
    }
  }
});
// iframe hack for the ie history featureless
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
  	
    frontPage 	: "Main",
    login       : "Login",
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
      
      // TODO needs refact, set up the hashmark order at the url 
      if(window.location.href.match(/(.#)(.*)/))
        var match = window.location.href.match(/(.#)(.*)/)[2];
      else
        Router.setRoute(Router.frontPage);
      
      if(match == "")
        Router.setRoute(Router.frontPage);
        
      if(match != null)
        if(Router.route != match)
          if(typeof Globals.DEPO[[match,"Controller"].join("")] == "undefined" || Globals.DEPO[[match,"Controller"].join("")] == null) {
            try {
              
              // init and store(its ref) the relevant controller class
              (new Function(['Globals.DEPO["',match,'Controller"] = new ',match,'Controller();'].join("")))();
              
              //set history for ie
              if(Router.ie)
                IEHH.changeContent(["#",match].join(""));
              
              Router.route = match;
            } catch(err) { console.log(err);
              delete Globals.DEPO[match];
              Router.setRoute(Router.frontPage);
            }
          }
        else {
          //TODO needs refact, we dont need the controller, only the view this time, need the rendered view, displayed or not or sthing like this
          //Globals.DEPO[match].getData();
          //console.log( Globals.DEPO[[match,"Controller"].join("")].data );
          //Globals.DEPO[[match,"View"].join("")].render(Globals.DEPO[[match,"Controller"].join("")].data);
          //Globals.DEPO[[match,"Controller"].join("")].getData();
        }
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
	  this.data = scope.data;
    this.view.render(this.data);
	},
	
	getData : function(){
		var self = this;
		Globals.DEPO["GroupModel"] = new GroupModel(self);
	}
	
});Ext.define('MainController', {

  extend: 'Controller',

  ajaxCallback: function(scope){
    this.view.render(scope.data);
  },

  main: function() {
    this.view.render({});
  },

  getData : function(){
    //this.main();
  }

});
Ext.define('LoginController', {

	extend: 'Controller',
	
	auth: function() {
    var self = Globals.DEPO["LoginController"];
    self.model.authentication(self);
	},
	
	authCallback : function(response, req) {
    var self      = Globals.DEPO["LoginController"],
        res       = self.model.toJson(response.responseText);
	  
	  if(res.username == null) {
	    Ext.getCmp('loginForm').getForm().setValues({
        username: "", 
        password: "" 
      })
      Ext.Msg.alert('Login failed', 'Try again!');
	  } else {
	    Ext.getCmp("LoginForm").hide();
	    Router.setRoute(Router.frontPage);
	  }
	},
	
	ajaxCallback: function(scope){
	  
	  Globals.DEPO["LogoutController"] = null;
	  
	  this.data = scope.data;
	  
	  if(this.data.username) {
	    Router.setRoute(Router.frontPage);
	  } else {
	    if(Ext.get("LoginForm") == null) {
	     this.view.render(this.data);
      }
	  }
	},
	
	getData : function(){
	  if(this.data.username)
      Router.setRoute(Router.frontPage);
	}
	
});Ext.define('LogoutController', {

  extend: 'Controller',
  
  showView: false,
  
  ajaxCallback: function(scope){
    
    Globals.DEPO["LoginController"] = null;
    
    Router.setRoute(Router.login);
  },
  
  getData : function(){
    
    if(this.data.username)
      Router.setRoute(Router.login);
  }
  
});Ext.define('GroupView', {

	extend: 'View',
	
	render: function(data){
	  
	  Ext.create('Ext.data.Store', {
      storeId:'groups',
      fields:['title', 'realname'],
      data:{'items': data},
      proxy: {
        type: 'memory',
        reader: {
          type: 'json',
          root: 'items'
        }
      }
    });
	  
	  Ext.create('Ext.window.Window', {
      title     : 'Csoportok:',
      id        : '',
      renderTo  : Ext.getBody(),
      resizable : true,
      height    : 600,
      width     : 420,
      layout    : 'fit',
      layout    : 'column',
      items: {
        xtype     : 'grid',
        store: Ext.data.StoreManager.lookup('groups'),
        columns: [
          {header: 'Title',  dataIndex: 'realname'},
          {header: 'Realname', dataIndex: 'title'}
        ],
        id        : '',
        layout    : 'fit',
        //layout    : 'column',
        height    : 550,
        width     : 400
      }
    }).show();
    
	  
		/*Ext.create('Ext.grid.Panel', {
      title: 'Csoportok: ',
      store: Ext.data.StoreManager.lookup('groups'),
      columns: [
        {header: 'Title',  dataIndex: 'realname'},
        {header: 'Realname', dataIndex: 'title'}
      ],
      layout    : 'fit',
      layout    : 'column',
      height    : 400,
      width     : 400,
      renderTo: Ext.getBody() //Ext.get("groupBody")
    });*/
	}
	
});
Ext.define('LoginView', {

	extend: 'View',
	
	render: function(data){
	  
	  var self = this;
	  
	  Ext.create('Ext.window.Window', {
      title     : 'Login',
      id        : 'LoginForm',
      renderTo  : Ext.getBody(),
      resizable : false,
      height    : 180,
      width     : 250,
      layout    : 'fit',
      layout    : 'column',
      items: {  
        xtype     : 'form',
        id        : 'loginForm',
        height    : 145,
        width     : 237,
        items     : data.items,
        //url       : data.action,
        buttons: [{
          text      : 'login',
          handler   : self.scope.auth
        }]
      }
    }).show();
	}
	/*submit: function(){
	  
	}*/
	
});

Ext.define('MainView', {
  
  extend: 'View',

  render: function(data) { //console.log(data); Ext.window.Window  //Ext.Component  Ext.container.Viewport
  
    var maxima = Ext.create('Ext.container.Viewport', {  //Ext.container.Viewport
      xtype: 'viewport',
      border: 0,
      margin: 0,
      padding: 0,
      style: 'background: #EBEEF2;',
      maintainFlex: true,
      layout: {
          type: 'fit'
      },
      items : data,
      renderTo : Ext.getBody()
      /*initComponent: function() {
          //this.items = data;
          //this.superclass.initComponent.call(this, arguments);
      }*/
    });
    
    //Ext.apply(maxima, data);
    //maxima.show();
    
    //Ext.apply(maxima, data);
    
    //console.log(maxima);
  
    /*var maxima = Ext.create('Ext.Component', {
      
      renderTo  : Ext.getBody(),
      items : data
      
    });
    
    Ext.apply(maxima, data);
    
    maxima.show();
    
    console.log(maxima);*/
  
  
  
    //var Maxima = Ext.create('Maxima', data);

    //Ext.Loader.setConfig({enabled:true});
    
    /*var MaximaViewport = Ext.create('ext-template', {
        renderTo: Ext.getBody()
    });*/
    
    //Ext.apply(MaximaViewport, data);
    
    //MaximaViewport.show();
    
    //console.log(MaximaViewport);



    /*// viewport
    Ext.create('Ext.container.Viewport', {
      border: 0,
      margin: 0,
      padding: 0,
      style: 'background: #EBEEF2;',
      maintainFlex: true,
      title    : "",
      layout   : {
          type  : 'fit'
      },
      renderTo : Ext.getBody()
    }).show();

    // wrapper container
    Ext.create('Ext.container.Container', {
      renderTo  : Ext.getBody(),
      id        : "maximaContainer",
      margin    : 0,
      minHeight : 300,
      minWidth  : 1200,
      layout    : {
        align : 'stretch',
        type  : 'hbox'
      }
    });

    // wrapper left container
    Ext.create('Ext.container.Container', {
      renderTo  : Ext.get("maximaContainer"),
      id        : "maximaLeftContainer",
      margin: 10,
      layout: {
        align : 'stretch',
        type  : 'vbox'
      },
      flex: 1
    });

    // wrapper right container
    Ext.create('Ext.container.Container', {
      renderTo  : Ext.get("maximaContainer"),
      id        : "maximaRightContainer",
      autoShow  : true,
      margin    : '10 10 10 0',
      width     : 200,
      layout    : {
        align     : 'stretch',
        type      : 'hbox'
      }
    });

    // menu wrapper container
    Ext.create('Ext.container.Container', {
      renderTo  : Ext.get("maximaLeftContainer"),
      id        : "maximaMenuWrapper",
      height    : 100,
      layout    : {
        type      : 'fit'
      }
    });

    // toolbar container
    Ext.create('Ext.toolbar.Toolbar', {
      height    : 90,
      layout    : {
        align     : 'stretch',
        type      : 'hbox'
      },
      renderTo  : Ext.get("maximaMenuWrapper"),
      id        : "maximaToolbar",

      items     : [{
        xtype   : "buttongroup",
        text    : 'Kérdőívek',
        id      : "maximaKerdoivCont",
        layout  : "fit",
        buttons : [{
          text    : 'Kérdőívek',
          id      : "kerdoivBtn",
          columns   : 1,
          flex      : 1,
          handler : ""
        }]
      },{
        xtype   : "buttongroup",
        text    : 'Tagok',
        id      : "maximaTagokCont",
        buttons: [{
          text      : 'Tagok listája',
          id        : "memberlist",
          columns   : 3,
          flex      : 2,
          disabled  : true,
          handler   : ""
        }]
      }]
    });*/

//    // toolbar kerdoivek
//    Ext.create('Ext.container.ButtonGroup', {
//      height    : 90,
//      style     : 'background: #C9DDF6;',
//      title     : 'kérdőívek',
//      flex      : 1,
//      rowspan   : 1,
//      columns   : 1,
//      renderTo  : Ext.get("maximaToolbar"),
//      id        : "maximaKerdoivCont",
//      buttons: [{
//        text    : 'Kérdőívek',
//        id      : "kerdoivBtn",
//        handler : ""
//      }]
//    });

//    // toolbar tagok
//    Ext.create('Ext.container.ButtonGroup', {
//      height    : 90,
//      style     : 'background: #C9DDF6;',
//      title     : 'Tagok',
//      flex      : 2,
//      rowspan   : 1,
//      columns   : 3,
//      renderTo  : Ext.get("maximaToolbar"),
//      id        : "maximaTagokCont",
//      buttons: [{
//        text      : 'Tagok listája',
//        id        : "memberlist",
//        disabled  : true,
//        handler   : ""
//      }]
//    });


  }


});
Ext.define('GroupModel', {

	extend: 'Model',
	
	mapper: function(data){
		
		var self 	= this;
		// store the data
		
		self.data 	= self.toJson(data.responseText);
		// call the callback method of the relevant controller
		self.router.ajaxCallback(self);
	},
	
	getAjaxData: function(){
		
		var self = this;
		
		/*var datas = {
			'elso' : 'ELSO',
			'masodik' : {
				'valami' 	: [0,1,2,3],
				'masvalami' : 'SEMMISEM'
			}
		};*/
		
		AJAX.post(
			"group/",
			"", //['data=',Ext.JSON.encode(datas)].join(''),
			this.mapper,
			self
		);
	}
	
});Ext.define('MainModel', {

  extend: 'Model',
  
  mapper: function(data){
    var self  = this;
    self.data = self.toJson(data.responseText);
    self.router.ajaxCallback(self);
  },
  
  getAjaxData: function(){
    var self = this;
    AJAX.get(
      "ext-template/",
      "",
      this.mapper,
      self
    );
  }
  
});Ext.define('LoginModel', {

	extend: 'Model',
	
	mapper: function(data){
		var self 	= this;
		self.data = self.toJson(data.responseText);
		self.router.ajaxCallback(self);
	},
	
	authentication : function(scope) {
	  AJAX.post(
      scope.data.action,
      Ext.getCmp("loginForm").getValues(),
      scope.authCallback,
      self
    );
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
	
});Ext.define('LogoutModel', {

  extend: 'Model',
  
  mapper: function(data){
    
    var self  = this;
    // store the data
    this.data = Ext.JSON.decode(data.responseText);
    // run the callback method of the relevant controller
    this.router.ajaxCallback(this);
  },
  
  getAjaxData: function(){
    
    var self = this;
    
    AJAX.get(
      "login/logout/",
      "",
      this.mapper,
      self
    );
  }
  
});