Ext.define('MainModel', {

  extend: 'Model',
  
  language: 'hu',
  
  languages: {
    'english'  : 'hu',
    'magyar'   : 'en'
  },
  
  languagesInv: {
    'hu'  : 'english',
    'en'  : 'magyar'
  },
  
  init: function() {
    //this.getAjaxData();
  },
  
  mapper: function(data){
    var self  = this;
    self.data = data.responseText;
    self.router.ajaxCallback(self);
  },
  
  getAjaxData: function(){
    var self = this;
    AJAX.get(
      ["ext-template?lang=",self.language].join(''),
      "",
      self.mapper,
      self
    );
  },
  
  groupMapper: function(response) {
    var self          = Globals.DEPO['MainController'].model;
    self.group.data   = self.toJson(response.responseText);
    self.group.groupsStore  = Ext.create('Ext.data.Store', {
      storeId:'groups',
      fields:['group_id','title', 'realname', 'membership','group'],
      data:{'items': self.group.data},
      proxy: {
        type: 'memory',
        reader: {
          type: 'json',
          root: 'items'
        }
      }
    });
    
    Globals.DEPO['MainController'].groupCallback();
  },
  
  postInit: function() {
    var self   = this;
    self.group = new GroupModel();
    self.group.getGroups(self);
  }
  
});