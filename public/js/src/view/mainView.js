Ext.define('MainView', {
  
  extend: 'View',

  /*langChooser: function() {
    //alert('FOKKK');
  },*/

  render: function(data) { 
    
    var self = this;
    self.cfg = eval("("+data+")");
    
    self.build(self.cfg);
    
    self.languageChooser = Globals.DEPO['languages']; //console.log(languageChooser);
    
    self.languageChooser.addListener({
      arrowclick: function() { console.log(this.getState()); alert('asdad');
        //self.scope.model.language         = this.getValue();
        //self.scope.model.store.proxy.url  = ['lang?lang=',self.scope.model.language,'&cat=',self.scope.model.cat].join('');
        //self.scope.model.store.load();
      }
    });
  }
});
