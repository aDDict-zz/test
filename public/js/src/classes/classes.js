Ext.define('AJAX', {
	statics: {
		ajax: function(url, method, params, callback){
			Ext.Ajax.request({
			    url		: url,
			    method	: method,
			    params	: params,
			    success	: callback
			});
		},
		get : function(url, params, callback){
			this.ajax(url, "get", params, callback);
		},
		post: function(url, params, callback){
			this.ajax(url, "post", params, callback);
		}
	},
	constructor: function() {}
});

Ext.define('Controller', {
	constructor: function() {
		this.getData();
	}
});

Ext.define('Model', {
	constructor: function(reference) {
		this.getAjax(reference);
	}
});

Ext.define('View', {
	constructor: function() {}
});
