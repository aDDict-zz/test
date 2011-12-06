Ext.define('MainController', {

  extend: 'Controller',
  
  init: function() {
    this.getData();
  },
  
  ajaxCallback: function(scope){
    
    var self = this;
    
    //self.profileCheck();
    
    if(self.profileCheck())
      this.view.render(scope.data);
  },

  /*main: function() {
    this.view.render({});
  },*/

  getData : function(){
    this.model.getAjaxData();
  }

});
