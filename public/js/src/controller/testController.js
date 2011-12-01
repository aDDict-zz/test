Ext.define('TestController', {

  extend: 'Controller',

  init: function() {
    /*if(Ext.get("Iddqd") == null)
      this.getData();*/
    //this.view.render({});
  },

  ajaxCallback: function(scope){
    this.view.render(scope.data);
  },

  getData : function(){
  }

});