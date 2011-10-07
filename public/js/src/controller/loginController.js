Ext.define('LoginController', {

	extend: 'Controller',
	
	ajaxCallback: function(scope){
	  
	  // "redirect" if everything is fine
	  if(scope.data.username) {
	    Router.setRoute(Router.frontPage);
	  } else {
	    // show the loginform
	    var loginView = new LoginView();
      loginView.render(scope.data);
	  }
	},
	
	getData : function(){
		var self = this;
		new LoginModel(self);
	}
	
});