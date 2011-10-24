Ext.define('MainController', {

  extend: 'Controller',
  
  ajaxCallback: function(scope){},
  
  main: function() { 
    
    //var self = this;
    
    //console.log(self.view);
    
    //Debug.parse(this.view);
    this.view.render();
  },
  
  getData : function(){
    this.main();
  }
  
});