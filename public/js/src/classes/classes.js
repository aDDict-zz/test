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
