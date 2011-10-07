Ext.define('LoginController', {

	extend: 'Controller',
	
	ajaxCallback: function(scope){
	  
	  this.data = scope.data;
	  // "redirect" if everything is fine
	  if(this.data.username) {
	    Router.setRoute(Router.frontPage);
	  } else {
	    // display the loginform
	    //var loginView = new LoginView();
      //loginView.render(scope.data);
	  }
	},
	
	getData : function(){
		var self = this;
		new LoginModel(self);
	}
	
});