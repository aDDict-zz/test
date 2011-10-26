Ext.define('IddqdTranslateModel', {

  extend: 'Model',
  
  init: function() {
    this.getAjaxData();
  },
  
  mapper: function(data){
    var self  = this;
    self.data = self.toJson(data.responseText);
    self.router.ajaxCallback(self);
  },
  
  getAjaxData: function(){
    var self = this;
    AJAX.get(
      "lang/",
      "",
      this.mapper,
      self
    );
  }
  
});