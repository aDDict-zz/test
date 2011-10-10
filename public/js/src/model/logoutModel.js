Ext.define('LogoutModel', {

  extend: 'Model',
  
  mapper: function(data){
    
    var self  = this;
    // store the data
    self.data = Ext.JSON.decode(data.responseText);
    // run the callback method of the relevant controller
    self.router.ajaxCallback(self);
  },
  
  getAjaxData: function(){
    
    var self = this;
    
    AJAX.get(
      "login/logout/",
      "",
      this.mapper,
      self
    );
  }
  
});