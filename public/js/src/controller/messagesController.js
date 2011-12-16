Ext.define('MessagesController', {

  extend: 'Controller',
  
  init: function() { alert( 'its inited' );
    var self = this;
    
    //self.getData();
  },
  
  ajaxCallback: function(scope){
  },

  /*main: function() {
    this.view.render({});
  },*/

  getData : function() {
    this.model.getAjaxData();
  }

});
