Ext.define('LoginController', {

	extend: 'Controller',
	
	auth: function() {
    var self = Globals.DEPO["LoginController"];
    Globals.DEPO["LoginController"].model.authentication(self);
	},
	
	authCallback : function(response, req) {
    var self   = Globals.DEPO["LoginController"];
    var res    = self.model.toJson(response.responseText);
	  
	  if(res.username == null) {
	    Ext.getCmp('loginForm').getForm().setValues({
        username: "", 
        password: "" 
      })
      Ext.Msg.alert('Login failed', 'Try again!');
	  } else {
	    Ext.getCmp("LoginBody").hide();
	    Router.setRoute(Router.frontPage);
	  }
	},
	
	ajaxCallback: function(scope){
	  
	  Globals.DEPO["LogoutController"] = null;
	  
	  this.data = scope.data;
	  // "redirect" if everything is fine
	  if(this.data.username) {
	    Router.setRoute(Router.frontPage);
	  } else {
	    this.view.render(scope.data);
	  }
	},
	
	getData : function(){
    if(this.data.username)
      Router.setRoute(Router.frontPage);
    else {
      var self = this;
      self.model = new LoginModel(self); 
    }
	}
	
});