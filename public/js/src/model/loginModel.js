Ext.define('LoginModel', {

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
	
});