Ext.define('MainView', {
  
  extend: 'View',

  getLang: function(options,e) {
    var self      = Globals.DEPO['MainController'].view,
        thisLang  = self.scope.model.languages[this.getText()];
        
    // TODO need refact
    if(thisLang == 'hu') {
      self.setLang('magyar', this);
    } else {
      self.setLang('english', this);
    }
  },
  
  setLang: function(lang, scope) {
    var self                          = this;
    self.scope.model.language         = self.scope.model.languages[lang];
    
    Globals.DEPO['components']['viewport'].destroy();
        
    self.scope.model.getAjaxData();
  },

  render: function(data) {
    
    var self = this;
    
    try {
      Globals.DEPO['components']['viewport'].destroy();
      Globals.DEPO['components'] = {};
    } catch(err) {}
    
    self.cfg = eval("("+data+")");
    self.build(self.cfg);
    Globals.DEPO['components']['languages'].setText(self.scope.model.languagesInv[self.scope.model.language]);
  }
});
