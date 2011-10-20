Ext.define('MainController', {

  extend: 'Controller',
  
  ajaxCallback: function(scope){
    /*console.log(this);
    console.log(Globals.DEPO);*/
   
    Debug.parse(Globals);
    
    this.data = scope.data;
    //this.view.render(this.data);
  },
  
  getData : function(){
    var self = this;
    Globals.DEPO["MainModel"] = new MainModel(self);
  }
  
});