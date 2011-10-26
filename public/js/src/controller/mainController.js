Ext.define('MainController', {

  extend: 'Controller',
  
  init: function() { //console.log(Ext.get("Main"));
    if(Ext.get("Main") == null)
      this.getData();
  },
  
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
