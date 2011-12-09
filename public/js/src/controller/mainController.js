Ext.define('MainController', {

  extend: 'Controller',
  
  init: function() {
    this.getData();
  },
  
  ajaxCallback: function(scope){
    
    var self = this;
    
    if(self.profileCheck()) {
      this.view.render(self.model.data);
      this.model.postInit(self);
    }
  },
  
  groupCallback: function() {
    var self = this;
    self.view.initGroupsGrid();
    //console.log(self.model.group.data);
  },

  /*main: function() {
    this.view.render({});
  },*/

  getData : function() {
    this.model.getAjaxData();
  }

});
