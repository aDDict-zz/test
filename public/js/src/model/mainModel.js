Ext.define('MainModel', {

  extend: 'Model',
  
  mapper: function(data){
    var self  = this;
    self.data = {};
    self.router.ajaxCallback(self);
  },
  
  authentication : function(scope) {
    /*AJAX.post(
      scope.data.action,
      Ext.getCmp("loginForm").getValues(),
      scope.authCallback,
      self
    );*/
  },
  
  getAjaxData: function(){
    
    this.mapper();
    
    /*var self = this;
     
    AJAX.get(
      "login/",
      "",
      this.mapper,
      self
    );*/
  }
  
});