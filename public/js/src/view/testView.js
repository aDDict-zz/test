Ext.define('TestView', {

  extend: 'View',
  
  render: function(data){ //console.log(data);
    
    var self  = this;
    
    self.cfg = eval("("+data+")");
    self.build(self.cfg);
    Globals.DEPO["viewport"] = Ext.create('Ext.container.Viewport', cfg);
    
  }
  
});