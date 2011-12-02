Ext.define('MainModel', {

  extend: 'Model',
  
  language: 'hu',
  
  init: function() {
    this.getAjaxData();
  },
  
  mapper: function(data){
    var self  = this;
    self.data = data.responseText; //self.toJson(data.responseText);
    self.router.ajaxCallback(self);
  },
  
  getAjaxData: function(){
    var self = this;
    AJAX.get(
      ["ext-template?lang=",self.language].join(''),
      "",
      this.mapper,
      self
    );
  }
  
});