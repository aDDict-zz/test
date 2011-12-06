Ext.define('ProfileController', {

  extend: 'Controller',
  
  auth: function() {
    var self = Globals.profile;
    self.model.authentication(self);
  },
  
  authCallback : function(response, req) {
    var self      = Globals.profile,
        res       = self.model.toJson(response.responseText);
    
    if(res.username == null) {
      Ext.getCmp('loginForm').getForm().setValues({
        username: "", 
        password: "" 
      })
      Ext.Msg.alert('Login failed', 'Try again!');
    } else {
      Ext.getCmp("LoginForm").hide();
      Router.setRoute(Router.frontPage);
    }
  },
  
  init: function() {
    if(typeof this.session == 'undefined')
      this.getData();
  },
  
  ajaxCallback: function(){
    //console.log( this );
  },

  getData : function(){
    this.model.getAjaxData();
  }

});