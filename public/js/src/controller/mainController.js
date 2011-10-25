Ext.define('MainController', {

  extend: 'Controller',

  ajaxCallback: function(scope){
    this.view.render(scope.data);
  },

  main: function() {
    this.view.render({});
  },

  getData : function(){
    //this.main();
  }

});
