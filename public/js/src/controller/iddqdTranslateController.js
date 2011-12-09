Ext.define('IddqdTranslateController', {

  extend: 'Controller',

  init: function() {
    this.getData();
  },

  ajaxCallback: function(scope){
    
    var self = this;
    
    if(self.profileCheck()) {
      self.model.setup();
      self.view.render(self.model.data);
    }
    
    //this.view.render(scope.data);
  },

  getData : function(){
    this.model.getAjaxData();
  }

});