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
 * class Message
 */
Ext.define('Message', {
  statics: {
    alert: function(head, body, callback) {
      Ext.Msg.alert(head, body, function(btn){
        if (btn == 'ok') {
          callback();
        }
      });
    }
  },
  constructor : function() {}
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

	model          : {},
	view		       : {},
	data           : {},
	nameSpace      : "",
	fullNameSpace  : "",
	showView       : true,

	getNameSpace: function() {
	  var matches    = this.$className.match(/(.*)(Controller)/);
	  this.nameSpace = matches[1];
	},
	
	getFullNameSpace: function() {
	  var nameSpace  = "",
	      arr        = Router.routeOrders;
	  
	  if(arr.length > 0) 
      for(var i = 0, len = arr.length; i < len; i++) {
        nameSpace += arr[i];
      }
    else
      nameSpace = this.nameSpace;
	  
	  this.fullNameSpace = nameSpace;
	},

	constructor	: function() {
	  var self = this;
	  self.getNameSpace();
	  self.getFullNameSpace();
	  self.model  = eval(['new ',self.nameSpace,'Model()'].join(''));
	  self.model.router = self;

    if(this.showView == true) {
      self.view = eval(['new ',self.nameSpace,'View()'].join(''));
      self.view.scope = self;
    }
		this.init();
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
		this.init();
	}
});

/**
 * class View
 */
Ext.define('View', {

	scope       : {},
	render 		  : function() {},
	constructor	: function() {
	  if(typeof Globals.DEPO["viewport"] == 'undefined' || Globals.DEPO["viewport"] == null)
  	  Globals.DEPO["viewport"] = Ext.create('Ext.container.Viewport', {
        xtype: 'viewport',
        border: 0,
        margin: 0,
        padding: 0,
        style: 'background: #EBEEF2;',
        maintainFlex: true,
        renderTo : Ext.getBody(),
        layout: {
            type: 'fit'
        },
        items : [/*{
          id: 'Main',
          xtype: 'container',
          layout: {
              type: 'fit'
          },
          items : data
        }*/]
      });
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
    routeOrders : [],
    routeParams : {},
    routeCache  : "",
    lang        : "",
    
    init      	: function() {
      
      if(Router.ie)
        IEHH.setup();
      
      Ext.TaskManager.start({
        run: Router.getRoute,
        interval: 2000
      });
    },
    
    getRoute  	: function() {
      
      var order = Router.getOrder();
      
      if(order == "")
        Router.setRoute(Router.frontPage);
      
      // setting up the language
      if(Router.routeParams["lang"])
        Router.lang = Router.routeParams["lang"];
      else {
        if(Router.lang == "")
          Router.lang = "hu";
      }
        
      if(order != null)
        if(Router.route != order)
          if(typeof Globals.DEPO[[order,"Controller"].join("")] == "undefined" || Globals.DEPO[[order,"Controller"].join("")] == null) {
            try {
              
              // init and store(its ref) the relevant controller class
              (new Function(['Globals.DEPO["',order,'Controller"] = new ',order,'Controller();'].join("")))();
              
              // set history for ie
              if(Router.ie)
                IEHH.changeContent(["#",order].join(""));
              
              // hiding the previous content
              if(Ext.get(Router.route) != null)
                Ext.get(Router.route).hide();
              
              Router.route = order;
            } catch(err) { console.log(err);
              delete Globals.DEPO[order];
              Message.alert('Routing error', 'There is no implemented class in the namespace', function() {
                Router.setRoute(Router.frontPage);
              });
            }
          }
        else {
          if(Router.route != order) {
            if(Ext.get(Router.route)) {
              Ext.get(Router.route).hide();
            }
            if(Ext.get(order)) {
              Ext.get(order).show();
            }
            Globals.DEPO[[order,"Controller"].join('')].init();
            Router.route = order;
          }
        }
    },
    
    setRoute    : function(route) {
      window.location.href = [window.location.href.split("#")[0],"#",route].join("");
    },
    
    getOrder    : function() {
      if(Router.routeCache != window.location.href) {
        Router.routeOrders = [];
        Router.routeParams = {};
        var matches = (window.location.href.match(/(.#)(.*)/) ? window.location.href.match(/(.#)(.*)/) : null);
        if(matches == null) {
          Router.setRoute(Router.frontPage);
        } else {
          route = matches[2];
          if(route.match(/\//)) {
            var orders = route.split('/'), arr;
            for(var i = 0, len = orders.length;i < len; i++) {
              if(orders[i].match(/=/)) {
                arr = orders[i].split("=");
                Router.routeParams[arr[0]] = arr[1];
              } else {
                Router.routeOrders.push(orders[i]);
              }
            }
            route = Router.routeOrders[0];
          }
          Router.routeCache = window.location.href;
          return route;
        }
      } else {
        return Router.routeOrders[0];
      }
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
	
	init: function() {
    this.getData();
  },
	
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
  
  init: function() { //console.log(Ext.get("Main"));
    if(Ext.get("Main") == null)
      this.getData();
  },
  
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
Ext.define('IddqdController', {

  extend: 'Controller',

  init: function() {
    try {
      if(typeof Globals.DEPO[[this.fullNameSpace,"Controller"].join("")] == "undefined")
        (new Function(['Globals.DEPO["',this.fullNameSpace,'Controller"] = new ',this.fullNameSpace,'Controller();'].join("")))();
      else
        Globals.DEPO[[this.fullNameSpace,"Controller"].join("")].init();
    } catch(err) {
      Message.alert('Routing error', 'There is no implemented class in the namespace', function() {
        Router.setRoute(Router.frontPage);
      });
      
    }
  },

  ajaxCallback: function(scope){
    this.view.render(scope.data);
  },

  /*main: function() {
    this.view.render({});
  },*/

  getData : function(){
  }

});Ext.define('IddqdTranslateController', {

  extend: 'Controller',

  init: function() {
    this.view.render({});
  },

  ajaxCallback: function(scope){
    this.view.render(scope.data);
  },

  getData : function(){
  }

});Ext.define('LoginController', {

	extend: 'Controller',
	
	init: function() {
	  this.getData();
	},
	
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
  
  init: function() {
    this.getData();
  },
  
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

Ext.define('IddqdView', {
  
  extend: 'View',

  render: function(data) { //console.log(data);
  }
});Ext.define('IddqdTranslateView', {
  
  extend: 'View',

  render: function(data) { console.log(Globals.DEPO);
    
    var itemsPerPage  = 6;
    var store         = Ext.create('Ext.data.Store', {
      storeId   : 'translate',
      fields    : ['id', 'category', 'variable', 'word', 'foreign_word'],
      //autoLoad  : {start: 0, limit: this.itemsPerPage},
      proxy : {
        type    : 'ajax',
        url     : 'lang',
        reader  : {
          type          : 'json',
          root          : 'rows',
          totalProperty : 'results',
          limit   : itemsPerPage,
          id: 'id'
          }
        }      
      });
      
      store.load({
        params  : {
          start   : 0,
          show    : itemsPerPage,
          limit   : itemsPerPage
        }
      });

    
    var Iddqd = Ext.create('Ext.grid.Panel', {
      title   : 'Translate',
      id      : "Iddqd",
      store   : store,
      columns : [
        {header : 'id'                , dataIndex: 'id'},
        {header : 'Category'          , dataIndex: 'category'},
        {header : 'Variable'          , dataIndex: 'variable'},
        {header : 'Kifejezés'         , dataIndex: 'word'},
        {header : 'Idegen kifejezés'  , dataIndex: 'foreign_word'}
      ],
      height    : 400,
      width     : 700,
      dockedItems: [{
        xtype: 'pagingtoolbar',
        store: store,
        dock: 'top',
        pageSize: itemsPerPage,
        limit   : itemsPerPage,
        displayInfo: true
       }]
     });
     
     Globals.DEPO["viewport"].add(Iddqd);
     Globals.DEPO["viewport"].doLayout();
      
  }
});Ext.define('MainView', {
  
  extend: 'View',

  render: function(data) { console.log(Globals.DEPO);
    //Globals.DEPO["viewport"].remove();
    
    //delete Globals.DEPO["viewport"];
    /*Globals.DEPO["viewport"] = Ext.create('Ext.container.Viewport', {
      xtype: 'viewport',
      border: 0,
      margin: 0,
      padding: 0,
      style: 'background: #EBEEF2;',
      maintainFlex: true,
      renderTo : Ext.getBody(),
      layout: {
          type: 'fit'
      },
      items : [{
        id: 'Main',
        xtype: 'container',
        layout: {
            type: 'fit'
        },
        items : data
      }]
    });*/
    
    var main = Ext.create('Ext.Container', {
      id: 'Main',
      xtype: 'container',
      layout: {
          type: 'fit'
      },
      items : data
    });
    
    Globals.DEPO["viewport"].add(main);
    Globals.DEPO["viewport"].doLayout();
    
  }
});
Ext.define('GroupModel', {

	extend: 'Model',
	
	init: function() {
    this.getAjaxData();
  },
	
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
  
  init: function() {
    this.getAjaxData();
  },
  
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
	
	init: function() {
	  this.getAjaxData();
	},
	
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
	
});Ext.define('IddqdModel', {

  extend: 'Model',
  
  init: function() {
  },
  
  mapper: function(data){
    var self  = this;
    self.data = self.toJson(data.responseText);
    self.router.ajaxCallback(self);
  },
  
  getAjaxData: function(){
    /*var self = this;
    AJAX.get(
      "lang/",
      "",
      this.mapper,
      self
    );*/
  }
  
});Ext.define('IddqdTranslateModel', {

  extend: 'Model',
  
  init: function() {
  },
  
  mapper: function(data){
  },
  
  getAjaxData: function(){
    /*var self = this,
        querystr = ['?lang=',Router.lang,'&show=',this.itemsPerPage].join('');
        
    AJAX.get(
      "lang/",
      querystr,
      this.mapper,
      self
    );*/
  }
  
});Ext.define('LogoutModel', {

  extend: 'Model',
  
  init: function() {
    this.getAjaxData();
  },
  
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