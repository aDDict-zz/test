Ext.define('IddqdTranslateController', {

  extend: 'Controller',

  init: function() {
    this.getData();
  },

  ajaxCallback: function(scope){
    this.view.render(scope.data);
  },

  getData : function(){
    this.model.getAjaxData();
  }

});