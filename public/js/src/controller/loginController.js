Ext.define('LoginController', {

	extend: 'Controller',
	
	ajaxCallback: function(scope){
		var loginView = new LoginView();
		loginView.render(scope.data);
	},
	
	getData : function(){
		var self = this;
		new LoginModel(self);
	}
	
});