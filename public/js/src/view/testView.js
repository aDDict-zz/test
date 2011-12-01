Ext.define('TestView', {

  extend: 'View',
  
  render: function(data){
    
    var self  = this
        cfg   = eval("("+data+")");
    
    self.build(cfg);
    //Globals.DEPO["viewport"] = Ext.create('Ext.container.Viewport', cfg);
    
  }
  
});