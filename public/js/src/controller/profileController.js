Ext.define('ProfileController', {

  extend: 'Controller',
  
  init: function() {
    if(typeof this.session == 'undefined')
      this.getData();
  },
  
  ajaxCallback: function(scope){
    
  },

  getData : function(){
    this.model.getAjaxData();
  }

});