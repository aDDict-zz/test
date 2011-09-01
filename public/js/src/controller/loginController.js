Ext.define('LoginController', {

	extend: 'Controller',
	
	ajaxCallback: function(scope){ alert("sadasds");
		var loginView = new GroupsView();
		loginView.render(scope.data);
	},
	
	getData : function(){
		var self = this;
		new LoginModel(self);
	}
	
});