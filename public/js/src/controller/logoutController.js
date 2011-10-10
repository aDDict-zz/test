Ext.define('LogoutController', {

  extend: 'Controller',
  
  showView: false,
  
  ajaxCallback: function(scope){
    
    Globals.DEPO["LoginController"] = null;
    
    this.data = scope.data;
    
    // "redirect" if everything is fine
    /*if(this.data.username) {
      Router.setRoute(Router.frontPage);
    } else {
      // display the loginform
      //var loginView = new LoginView();
      //loginView.render(scope.data);
    }*/
  },
  
  getData : function(){
    
    
    if(this.data.username)
      Router.setRoute(Router.login);
    else {
      var self = this;
      new LogoutModel(self); 
    }
  }
  
});