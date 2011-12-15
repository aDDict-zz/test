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
  },

  /*main: function() {
    this.view.render({});
  },*/
 
  logout: function() {
    var self = this;
    AJAX.get(
      'login/logout',
      "",
      Router.reload,
      self
    );
  },

  getData : function() {
    this.model.getAjaxData();
  }

});
