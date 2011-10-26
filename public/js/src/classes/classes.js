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
