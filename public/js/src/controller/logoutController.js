Ext.define('LogoutController', {

  extend: 'Controller',
  
  showView: false,
  
  ajaxCallback: function(scope){
    
    Globals.DEPO["LoginController"] = null;
    
    Router.setRoute(Router.login);
  },
  
  getData : function(){
    
    if(this.data.username)
      Router.setRoute(Router.login);
  }
  
});