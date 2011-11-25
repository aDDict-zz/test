Ext.define('IddqdTranslateModel', {

  extend: 'Model',
  
  init: function() {
    
    var self = this;
    
    if(Ext.get("Iddqd") == null)
      self.getAjaxData();
      
    self.loader         = new Ext.LoadMask(Ext.getBody(), {msg:"loading"});
    
    self.itemsPerPage   = 10;
    self.language       = 'hu';
    self.cat            = 1;
    
    self.store          = Ext.create('Ext.data.Store', {
      storeId : 'translate',
      fields  : [
        'id',
        'category',
        'variable',
        'word',
        'foreign_word'
      ],
      proxy   : {
        type      : 'ajax',
        url       : ['lang?lang=',self.language,'&cat=',self.cat].join(''),
        reader    : {
          type          : 'json',
          root          : 'rows',
          totalProperty : 'results',
          }
        }      
    });
    
    self.langStore      = Ext.create('Ext.data.Store', {
      fields: ['langval', 'lang'],
      autoLoad: true,
      proxy   : {
        type      : 'ajax',
        url       : 'lang/groups',
        reader    : {
          type          : 'json',
          root          : 'rows',
          totalProperty : 'results'
        }
      },
      listeners      : {
        load      : function(store,records,options) {
          self.router.view.langCombo.setValue(self.langStore.getAt(0).data['langval']);
        }
      }
    });

    self.catStore      = Ext.create('Ext.data.Store', {
      fields: ['catval', 'cat'],
      autoLoad: true,
      value: 0,
      proxy   : {
        type      : 'ajax',
        url       : 'lang/cats',
        reader    : {
          type          : 'json',
          root          : 'rows',
          totalProperty : 'results'
          }
      },
      listeners      : {
        load      : function(store,records,options) {
          self.router.view.catCombo.setValue(self.catStore.getAt(0).data['catval']);
        }
      }
    });
    
    self.store.on('beforeload', function() {
        this.pageSize = self.itemsPerPage;
        this.limit    = self.itemsPerPage;
      });
        
    self.store.load({
      start   : 0,
      limit   : self.itemsPerPage
    });
  
    self.rowEditing = Ext.create('Ext.grid.plugin.RowEditing',{
      clicksToEdit: 1
    });
          
    self.rowEditing.on({
      scope:this,
      afteredit: function(roweditor, changes, record, rowIndex){
        self.updateRow(roweditor,changes);
      }
    });
  },
  
  updateRow: function(roweditor, scope) {
    var self = this;
    self.roweditor = roweditor;
    self.scope = scope;
    this.loader.show();
    AJAX.get(
      "lang/update",
      ['field=',roweditor.field,'&id=',roweditor.record.get('id'),'&val=',roweditor.record.get(roweditor.field)].join(''),
      function() {
        self.roweditor.record.commit();  
        self.store.load();
        self.loader.hide();
      },
      self
    );
  },
  
  addLanguage: function(newLang) {
    var self = this;
    AJAX.post(
      ['lang/addlanguage'].join(''),
      ['lang=',newLang].join(''),
      function(resp) {self.langStore.load();},
      self
    );
  },
  
  deleteLanguage: function(id) {
    var self = this;
    AJAX.get(
      ['lang/deletelanguage?id=',id].join(''),
      '',
      function(resp) {self.langStore.load();},
      self
    );
  },
  
  deleteCategory: function(id) {
    var self = this;
    AJAX.get(
      ['lang/deletecategory?id=',id].join(''),
      '',
      function(resp) {self.catStore.load();},
      self
    );
  },
  
  addCategory: function(category) {
    var self = this;
    AJAX.post(
      ['lang/addcategory'].join(''),
      ['cat=',category].join(''),
      function(resp) {self.catStore.load();},
      self
    );
  },
  
  mapper: function(data){
    var self  = this;
    self.data = data.responseText;
    self.router.ajaxCallback(self);
  },
  
  getAjaxData: function(){
    var self = this;
    AJAX.get(
      "ext-template/translate",
      "",
      this.mapper,
      self
    );
  }
  
});
