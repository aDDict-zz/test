Ext.define('IddqdTranslateController', {

  extend: 'Controller',

  init: function() {
    this.view.render({});
  },

  ajaxCallback: function(scope){
    this.view.render(scope.data);
  },

  getData : function(){
  }

});