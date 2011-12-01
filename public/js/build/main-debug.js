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
	  var self           = this;
	  self.getNameSpace();
	  self.getFullNameSpace();
	  self.model         = eval(['new ',self.nameSpace,'Model()'].join(''));
	  self.model.router  = self;

    if(this.showView == true) {
      self.view       = eval(['new ',self.nameSpace,'View()'].join(''));
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

	constructor	: function() {
		this.init();
	}
});

/**
 * class View
 */
Ext.define('View', {

  xtypes      : {
    'button'         : 'Ext.button.Button',
    'buttongroup'    : 'Ext.container.ButtonGroup',
    'colorpalette'   : 'Ext.picker.Color',
    'component'      : 'Ext.Component',
    'container'      : 'Ext.container.Container',
    'cycle'          : 'Ext.button.Cycle',
    'dataview'       : 'Ext.view.View',
    'datepicker'     : 'Ext.picker.Date',
    'editor'         : 'Ext.Editor',
    'editorgrid'     : 'Ext.grid.plugin.Editing',
    'grid'           : 'Ext.grid.Panel',
    'multislider'    : 'Ext.slider.Multi',
    'panel'          : 'Ext.panel.Panel',
    'progress'       : 'Ext.ProgressBar',
    'slider'         : 'Ext.slider.Single',
    'spacer'         : 'Ext.toolbar.Spacer',
    'splitbutton'    : 'Ext.button.Split',
    'tabpanel'       : 'Ext.tab.Panel',
    'treepanel'      : 'Ext.tree.Panel',
    'viewport'       : 'Ext.container.Viewport',
    'window'         : 'Ext.window.Window',
    'paging'         : 'Ext.toolbar.Paging',
    'toolbar'        : 'Ext.toolbar.Toolbar',
    'tbfill'         : 'Ext.toolbar.Fill',
    'tbitem'         : 'Ext.toolbar.Item',
    'tbseparator'    : 'Ext.toolbar.Separator',
    'tbspacer'       : 'Ext.toolbar.Spacer',
    'tbtext'         : 'Ext.toolbar.TextItem',
    'menu'           : 'Ext.menu.Menu',
    'menucheckitem'  : 'Ext.menu.CheckItem',
    'menuitem'       : 'Ext.menu.Item',
    'menuseparator'  : 'Ext.menu.Separator',
    'menutextitem'   : 'Ext.menu.Item',
    'form'           : 'Ext.form.Panel',
    'checkbox'       : 'Ext.form.field.Checkbox',
    'combo'          : 'Ext.form.field.ComboBox',
    'datefield'      : 'Ext.form.field.Date',
    'displayfield'   : 'Ext.form.field.Display',
    'field'          : 'Ext.form.field.Base',
    'fieldset'       : 'Ext.form.FieldSet',
    'hidden'         : 'Ext.form.field.Hidden',
    'htmleditor'     : 'Ext.form.field.HtmlEditor',
    'label'          : 'Ext.form.Label',
    'numberfield'    : 'Ext.form.field.Number',
    'radio'          : 'Ext.form.field.Radio',
    'radiogroup'     : 'Ext.form.RadioGroup',
    'textarea'       : 'Ext.form.field.TextArea',
    'textfield'      : 'Ext.form.field.Text',
    'timefield'      : 'Ext.form.field.Time',
    'trigger'        : 'Ext.form.field.Trigger'
  },
  
	scope       : {},
	render 		  : function() {},
	build       : function(cfg) { //console.log(cfg);
	  
	  var  self      = this,
	       rootcfg   = cfg,
	       thisItems = (cfg.items ? cfg.items : null);
	       
	  rootcfg.items = []; 
	  if(cfg.xtype == 'viewport') {
	    //Globals.DEPO["viewport"] = Ext.create('Ext.container.Viewport', cfg);
	    Globals.DEPO["viewport"] = Ext.create(self.xtypes[cfg.xtype], cfg);
	    Globals.DEPO["viewport"].add(thisItems);
	  } else {
	    if(cfg.id) {
	      Globals.DEPO[cfg.id] = Ext.create(self.xtypes[cfg.xtype], cfg);
	    }
	  }
	},
	
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
              Message.alert('Routing error', 'There is no implemented class with this namespace', function() {
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
	
});Ext.define('TestController', {

  extend: 'Controller',

  init: function() {
    /*if(Ext.get("Iddqd") == null)
      this.getData();*/
    //this.view.render({});
  },

  ajaxCallback: function(scope){
    this.view.render(scope.data);
  },

  getData : function(){
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
    } catch(err) { console.log(err);
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

});
Ext.define('IddqdTranslateController', {

  extend: 'Controller',

  init: function() {
    /*if(Ext.get("Iddqd") == null)
      this.getData();*/
    //this.view.render({});
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
Ext.define('TestView', {

  extend: 'View',
  
  render: function(data){
    
    var self  = this
        cfg   = eval("("+data+")");
    
    self.build(cfg);
    //Globals.DEPO["viewport"] = Ext.create('Ext.container.Viewport', cfg);
    
  }
  
});Ext.define('LoginView', {

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
  
  modal: function(job) {
    var self          = this;
    
    self.modalWindow  = Ext.create('Ext.window.Window', {
      title: '',
      id: 'modal',
      modal: true,
      items: [{
        xtype:'container',
        height: 100,
        width: 300,
        id: 'manager',
        layout: 'fit'
      }]
    }).show();
    
    switch(job) {
      case 'langComboAdd':
        self.addLangField = Ext.create('Ext.container.Container', {
          layout: 'fit',
          renderTo: Ext.get('manager'),
          layout: 'fit',
          margin:0,
          items: [{
            fieldLabel: 'new language',
            xtype: 'field',
            id: 'addLang',
          },{
            xtype: 'button',
            text: 'add',
            handler: function() {
              self.scope.model.addLanguage(self.addLangField.items.items[0].value);
            }
          }]
        });
      break;
      case 'langComboDel':
        self.delLangCombobox = Ext.create('Ext.form.ComboBox', {
          id: 'delLang',
          fieldLabel: 'Choose language',
          store: self.scope.model.langStore,
          queryMode: 'local',
          height: 200,
          margin:0,
          displayField: 'lang',
          valueField: 'langval',
          triggerAction : 'all',
          layout: 'fit',
          renderTo: Ext.get('manager'),
          listeners: {
            select: function() {
              self.scope.model.deleteLanguage(this.getValue());
            }
          }
        });
      break;
      case 'catComboAdd':
        self.addCatField = Ext.create('Ext.container.Container', {
          layout: 'fit',
          fieldLabel: 'Add new cat',
          renderTo: Ext.get('manager'),
          margin:0,
          items: [{
            fieldLabel: 'new category',
            xtype: 'field',
            id: 'addLang',
          },{
            xtype: 'button',
            text: 'add',
            handler: function() {
              self.scope.model.addCategory(self.addCatField.items.items[0].value);
            }
          }]
        });
      break;
      case 'catComboDel':
        self.delCatField = Ext.create('Ext.form.ComboBox', {
          id: 'delCat',
          xtype: 'combo',
          fieldLabel: 'Choose category',
          store: self.scope.model.catStore,
          queryMode: 'local',
          displayField: 'cat',
          valueField: 'catval',
          triggerAction : 'all', 
          renderTo: Ext.get('manager'),
          listeners: {
            select: function() {
              self.scope.model.deleteCategory(this.getValue());
            }
          }
        });
      break;
      case 'varComboAdd':
        self.addVarField = Ext.create('Ext.container.Container', {
          layout: 'fit',
          fieldLabel: 'Add new variable',
          renderTo: Ext.get('manager'),
          layout: 'fit',
          margin:0,
          items: [{
            fieldLabel: 'new variable',
            xtype: 'field',
            id: 'addVarnew',
          },{
            fieldLabel: 'orig expression',
            xtype: 'field',
            id: 'addVarExp',
          },{
            xtype: 'button',
            text: 'add',
            handler: function() {
              self.scope.model.addVariable(self.addVarField.items.items[0].value,self.addVarField.items.items[1].value);
            }
          }]
        });
      break;
      case 'varComboDel':
        self.delVarField = Ext.create('Ext.form.ComboBox', {
          id: 'delVar',
          xtype: 'combo',
          fieldLabel: 'Choose variable',
          store: self.scope.model.variableStore,
          queryMode: 'local',
          displayField: 'var',
          valueField: 'varval',
          triggerAction : 'all',
          renderTo: Ext.get('manager'),
          listeners: {
            select: function() {
              self.scope.model.deleteVariable(this.getValue());
            }
          }
        });
        self.scope.model.variableStore.proxy.url = ['lang/vars?cat=',self.scope.model.variableStoreCat].join('');
        self.scope.model.variableStore.load();
      break;
    }
  },
  
  renderer: function(str) {
    return ['<span style="font-weight:bold;">',str,'</span>'].join('');
  },
  
  render: function(data) {
    
    if(!Ext.get("Iddqd")) {
      
      var self          = this;
      
      // ext.apply & Ext.decode arent workin well, we need a simple eval
      Globals.DEPO["viewport"] = Ext.create('Ext.container.Viewport', eval("("+data+")"));
      
      // fuck this lookup TODO need  a spec own init method to store the referencies in a better way
      self.langCombo    = Globals.DEPO["viewport"].items.items[0].items.items[1].items.items[0].items.items[0];
      self.langComboAdd = Globals.DEPO["viewport"].items.items[0].items.items[1].items.items[0].items.items[1].items.items[0];
      self.langComboDel = Globals.DEPO["viewport"].items.items[0].items.items[1].items.items[0].items.items[1].items.items[1];
      
      self.catCombo     = Globals.DEPO["viewport"].items.items[0].items.items[1].items.items[1].items.items[0];
      self.catComboAdd  = Globals.DEPO["viewport"].items.items[0].items.items[1].items.items[1].items.items[1].items.items[0];
      self.catComboDel  = Globals.DEPO["viewport"].items.items[0].items.items[1].items.items[1].items.items[1].items.items[1];
      
      self.varCombo     = Globals.DEPO["viewport"].items.items[0].items.items[1].items.items[2].items.items[0];
      self.varComboAdd  = Globals.DEPO["viewport"].items.items[0].items.items[1].items.items[2].items.items[1].items.items[0];
      self.varComboDel  = Globals.DEPO["viewport"].items.items[0].items.items[1].items.items[2].items.items[1].items.items[1];
      
      self.varComboAdd.addListener({
        click: function() {
          self.modal('varComboAdd');
        }
      });
      self.varComboDel.addListener({
        click: function() {
          self.modal('varComboDel');
        }
      });
      
      self.langComboAdd.addListener({
        click: function() {
          self.modal('langComboAdd');
        }
      });
      self.langComboDel.addListener({
        click: function() {
          self.modal('langComboDel');
        }
      });
      self.catComboAdd.addListener({
        click: function() {
          self.modal('catComboAdd');
        }
      });
      self.catComboDel.addListener({
        click: function() {
          self.modal('catComboDel');
        }
      });
      
      self.langCombo.addListener({
        select: function() {
          self.scope.model.language         = this.getValue();
          self.scope.model.store.proxy.url  = ['lang?lang=',self.scope.model.language,'&cat=',self.scope.model.cat].join('');
          self.scope.model.store.load();
        }
      });
      self.catCombo.addListener({
        select: function() {
          self.scope.model.cat              = this.getValue();
          self.scope.model.store.proxy.url  = ['lang?lang=',self.scope.model.language,'&cat=',self.scope.model.cat].join('');
          self.scope.model.store.load();
        }
      });
      self.varCombo.addListener({
        select: function() {
          self.scope.model.variableStoreCat = this.getValue();
        }
      });
    }
  }
});
Ext.define('MainView', {
  
  extend: 'View',

  render: function(data) { //console.log(data);
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
      }
    });
    
    var main = Ext.create('Ext.Container', {
      id: 'Main',
      xtype: 'container',
      layout: {
          type: 'fit'
      },
      items: data
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
	
});Ext.define('TestModel', {

  extend: 'Model',
  
  init: function() {
    
    var self = this;
    
    if(Ext.get("Iddqd") == null)
      self.getAjaxData();
  },
  
  mapper: function(data){
    var self  = this;
    self.data = data.responseText;
    self.router.ajaxCallback(self);
  },
  
  getAjaxData: function(){
    var self = this;
    AJAX.get(
      "ext-template/test",
      "",
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
  
  reload: function() {
    var self              = this;
    self.router.view.modalWindow.destroy();
    self.cat              = self.variableStoreCat;
    self.store.proxy.url  = ['lang?lang=',self.language,'&cat=',self.cat].join('');
    self.store.load();
    self.langStore.load();
    self.catStore.load();
    self.variableStore.load();
    
    self.router.view.langCombo.setValue(self.language);
    self.router.view.catCombo.setValue(self.cat);
    self.router.view.varCombo.setValue(self.cat);
  },
  
  init: function() {
    
    var self = this;
    
    if(Ext.get("Iddqd") == null)
      self.getAjaxData();
      
    self.loader         = new Ext.LoadMask(Ext.getBody(), {msg:"loading"});
    
    self.itemsPerPage     = 10;
    self.language         = 'hu';
    self.cat              = '1';
    self.variableStoreCat = '1';
    
    self.store          = Ext.create('Ext.data.Store', {
      storeId : 'translate',
      fields  : [
        'id',
        'category',
        'variable',
        'word',
        'foreign_word'
      ],
      proxy   : {
        type      : 'ajax',
        url       : ['lang?lang=',self.language,'&cat=',self.cat].join(''),
        reader    : {
          type          : 'json',
          root          : 'rows',
          totalProperty : 'results',
          }
        }      
    });
    
    self.langStore      = Ext.create('Ext.data.Store', {
      fields: ['langval', 'lang'],
      autoLoad: true,
      proxy   : {
        type      : 'ajax',
        url       : 'lang/groups',
        reader    : {
          type          : 'json',
          root          : 'rows',
          totalProperty : 'results'
        }
      },
      listeners      : {
        load      : function(store,records,options) {
          self.router.view.langCombo.setValue(/*self.langStore.getAt(0).data['langval']*/ self.language);
        }
      }
    });

    self.catStore      = Ext.create('Ext.data.Store', {
      fields: ['catval', 'cat'],
      autoLoad: true,
      value: 0,
      proxy   : {
        type      : 'ajax',
        url       : 'lang/cats',
        reader    : {
          type          : 'json',
          root          : 'rows',
          totalProperty : 'results'
          }
      },
      listeners      : {
        load      : function(store,records,options) { //alert( self.catStore.getAt(0).data['catval'] );
          self.router.view.catCombo.setValue(/*self.catStore.getAt(0).data['catval']*/ self.cat);
          self.router.view.varCombo.setValue(/*self.catStore.getAt(0).data['catval']*/ self.cat);
        }
      }
    });
    
    self.variableStore = Ext.create('Ext.data.Store', {
      fields: ['varval', 'var'],
      autoLoad: true,
      value: 0,
      proxy   : {
        type      : 'ajax',
        url       : ['lang/vars?cat=',self.variableStoreCat].join(''),
        reader    : {
          type          : 'json',
          root          : 'rows',
          totalProperty : 'results'
          }
      },
      listeners      : {
        load      : function(store,records,options) {
          //self.router.view.catCombo.setValue(self.catStore.getAt(0).data['catval']);
        }
      }
    });
    
    self.store.on('beforeload', function() {
        this.pageSize = self.itemsPerPage;
        this.limit    = self.itemsPerPage;
      });
        
    self.store.load({
      start   : 0,
      limit   : self.itemsPerPage
    });
  
    self.rowEditing = Ext.create('Ext.grid.plugin.RowEditing',{
      clicksToEdit: 1
    });
          
    self.rowEditing.on({
      scope:this,
      afteredit: function(roweditor, changes, record, rowIndex){
        self.updateRow(roweditor,changes);
      }
    });
  },
  
  updateRow: function(roweditor, scope) {
    var self = this;
    self.roweditor = roweditor;
    self.scope = scope;
    this.loader.show();
    AJAX.post(
      "lang/update",
      ['field=',roweditor.field,'&id=',roweditor.record.get('id'),'&val=',roweditor.record.get(roweditor.field),'&lang=',self.language].join(''),
      function() {
        self.roweditor.record.commit();  
        self.store.load();
        self.loader.hide();
      },
      self
    );
  },
  
  addLanguage: function(newLang) {
    var self = this;
    AJAX.post(
      ['lang/addlanguage'].join(''),
      ['lang=',newLang].join(''),
      function(resp) {
        self.language = newLang.substring(0, 2);
        self.reload();
      },
      self
    );
  },
  
  deleteLanguage: function(id) {
    var self = this;
    AJAX.get(
      ['lang/deletelanguage?id=',id].join(''),
      '',
      function(resp) {
        self.reload();
      },
      self
    );
  },
  
  deleteCategory: function(id) {
    var self = this;
    AJAX.get(
      ['lang/deletecategory?id=',id].join(''),
      '',
      function(resp) {
        self.reload();
      },
      self
    );
  },
  
  addCategory: function(category) {
    var self = this;
    AJAX.post(
      ['lang/addcategory'].join(''),
      ['cat=',category].join(''),
      function(resp) {
        self.reload();
      },
      self
    );
  },
  
  addVariable: function(variable,expression) {
    var self = this;
    AJAX.post(
      ['lang/addvariable'].join(''),
      ['var=',variable,'&cat=',self.variableStoreCat,'&expr=',expression].join(''),
      function(resp) {
        self.reload();
      },
      self
    );
  },
  
  deleteVariable: function(id) {
    var self = this;
    AJAX.post(
      ['lang/deletevariable'].join(''),
      ['id=',id].join(''),
      function(resp) {
        self.reload();
      },
      self
    );
  }, 
  
  mapper: function(data){
    var self  = this;
    self.data = data.responseText;
    self.router.ajaxCallback(self);
  },
  
  getAjaxData: function(){
    var self = this;
    AJAX.get(
      "ext-template/translate",
      "",
      this.mapper,
      self
    );
  }
  
});
Ext.define('LogoutModel', {

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