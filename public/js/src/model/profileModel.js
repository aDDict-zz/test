Ext.define('ProfileModel', {

  extend: 'Model',
  
  init: function() {
    
  },
  
  mapper: function(data){
    var self  = this;
    self.data = data.responseText; //self.toJson(data.responseText);
    //self.router.ajaxCallback(self);
  },
  
  getAjaxData: function(){
    var self = this;
    AJAX.get(
      "login/",
      "",
      this.mapper,
      self
    );
  }
  
});