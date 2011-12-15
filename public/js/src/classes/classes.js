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
  
  profileCheck: function() {
    var self = this;
    if(Globals.profile.model.data.user) {
      return true;
    } else {
      Globals.profile.view.render(Globals.profile.model.data);
      //Router.setRoute(Router.route);
    }
  },
  
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
    'hiddenfield'    : 'Ext.form.field.Hidden',
    'htmleditor'     : 'Ext.form.field.HtmlEditor',
    'label'          : 'Ext.form.Label',
    'numberfield'    : 'Ext.form.field.Number',
    'radio'          : 'Ext.form.field.Radio',
    'radiogroup'     : 'Ext.form.RadioGroup',
    'textarea'       : 'Ext.form.field.TextArea',
    'textfield'      : 'Ext.form.field.Text',
    'timefield'      : 'Ext.form.field.Time',
    'trigger'        : 'Ext.form.field.Trigger',
    'image'          : 'Ext.Img'
  },
  
	scope       : {},
	
	render 		  : function() {},
	
	// recursive iter on cfg to build components and store the component referencies
	build       : function(cfg, parent) {
	  
	  var  self      = this,
	       thisCfg   = cfg,
	       ref       = '',
	       globalId  = '',
	       hash      = (self.date.getTime()*Math.random()).toString().substr(0,2), 
	       thisItems = (cfg.items ? cfg.items : null);
	       
	  thisCfg.items = [];
	  
	  if(!Globals.DEPO['components'])
	   Globals.DEPO['components'] = {};
	  
	  if(cfg.xtype == 'viewport') {
	    globalId                             = 'viewport';
	    Globals.DEPO['components'][globalId] = Ext.create('Ext.container.Viewport', thisCfg);
	  } else {
	    ref = Ext.create(self.xtypes[cfg.xtype], thisCfg);
	    if(cfg.id) {
	      globalId = cfg.id;
	    } else {
	      globalId = [parent,hash].join('');
	    }
	    Globals.DEPO['components'][parent].add(ref);
	    Globals.DEPO['components'][globalId] = ref;
	  }

	  if(thisItems != null && thisItems.length != 0) {
	    for(var i = 0,l = thisItems.length; i < l; i++) {
	      self.build(thisItems[i],globalId);
	    }
	  }
	},
	
	constructor	: function() {
	  this.date = new Date();
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