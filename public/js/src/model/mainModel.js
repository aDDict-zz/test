Ext.define('MainModel', {

  extend: 'Model',
  
  mapper: function(data){
    var self  = this;
    self.data = self.toJson(data.responseText);
    self.router.ajaxCallback(self);
  },
  
  getAjaxData: function(){
    var self = this;
    AJAX.get(
      "ext-template/",
      "",
      this.mapper,
      self
    );
  }
  
});