Ext.define('IddqdTranslateController', {

  extend: 'Controller',

  init: function() {
    
  },

  ajaxCallback: function(scope){
    this.view.render(scope.data);
  },

  /*main: function() {
    this.view.render({});
  },*/

  getData : function(){
  }

});