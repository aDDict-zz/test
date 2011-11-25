Ext.define('IddqdTranslateView', {
  
  extend: 'View',
  
  modal: function(job) {
    var self          = this;
    
    self.modalWindow  = Ext.create('Ext.window.Window', {
      title: '',
      id: 'modal',
      modal: true,
      items: [{
        xtype:'container',
        height: 100,
        width: 300,
        id: 'manager',
        layout: 'fit'
      }]
    }).show();
    
    switch(job) {
      case 'addNewItem': alert("addNewItem!");
      break;
      case 'delNewItem': alert("delNewItem!");
      break;
      case 'langComboAdd':
        self.addLangField = Ext.create('Ext.container.Container', {
          layout: 'fit',
          renderTo: Ext.get('manager'),
          layout: 'fit',
          margin:0,
          items: [{
            fieldLabel: 'new language',
            xtype: 'field',
            id: 'addLang',
          },{
            xtype: 'button',
            text: 'add',
            handler: function() {
              self.scope.model.addLanguage(self.addLangField.items.items[0].value);
            }
          }]
        });
      break;
      case 'langComboDel':
        self.delLangCombobox = Ext.create('Ext.form.ComboBox', {
          id: 'delLang',
          fieldLabel: 'Choose language',
          store: self.scope.model.langStore,
          queryMode: 'local',
          height: 200,
          margin:0,
          displayField: 'lang',
          valueField: 'langval',
          triggerAction : 'all',
          layout: 'fit',
          renderTo: Ext.get('manager'),
          listeners: {
            select: function() {
              self.scope.model.deleteLanguage(this.getValue());
            }
          }
        });
      break;
      case 'catComboAdd':
        self.addCatField = Ext.create('Ext.container.Container', {
          layout: 'fit',
          fieldLabel: 'Add new cat',
          renderTo: Ext.get('manager'),
          layout: 'fit',
          margin:0,
          items: [{
            fieldLabel: 'new category',
            xtype: 'field',
            id: 'addLang',
          },{
            xtype: 'button',
            text: 'add',
            handler: function() {
              self.scope.model.addCategory(self.addCatField.items.items[0].value);
            }
          }]
        });
      break;
      case 'catComboDel':
        self.delCatField = Ext.create('Ext.form.ComboBox', {
          id: 'delCat',
          xtype: 'combo',
          id: 'categories',
          fieldLabel: 'Choose category',
          store: self.scope.model.catStore,
          queryMode: 'local',
          displayField: 'cat',
          valueField: 'catval',
          triggerAction : 'all', 
          renderTo: Ext.get('manager'),
          listeners: {
            select: function() {
              self.scope.model.deleteCategory(this.getValue());
            }
          }
        });
      break;
    }
  },
  
  renderer: function(str) {
    return ['<span style="font-weight:bold;">',str,'</span>'].join('');
  },
  
  render: function(data) {
    
    if(!Ext.get("Iddqd")) {
      
      var self            = this;
      
      /*self.langStore      = Ext.create('Ext.data.Store', {
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
            self.langCombo.setValue(self.langStore.getAt(0).data['langval']);
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
            self.catCombo.setValue(self.catStore.getAt(0).data['catval']);
          }
        }
      });*/
       
      /*self.store         = Ext.create('Ext.data.Store', {
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
      })*/
        
      /*self.store.on('beforeload', function() {
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
          self.scope.model.updateRow(roweditor,changes);
        }
      });*/
      
      // ext.apply & Ext.decode arent workin well, we need a simple eval
      Globals.DEPO["viewport"] = Ext.create('Ext.container.Viewport', eval("("+data+")"));
      
      // fuck this lookup TODO need  a spec own init method to store the referencies in a better way
      self.langCombo    = Globals.DEPO["viewport"].items.items[0].items.items[1].items.items[0].items.items[0];
      self.langComboAdd = Globals.DEPO["viewport"].items.items[0].items.items[1].items.items[0].items.items[1].items.items[0];
      self.langComboDel = Globals.DEPO["viewport"].items.items[0].items.items[1].items.items[0].items.items[1].items.items[1];
      
      self.catCombo     = Globals.DEPO["viewport"].items.items[0].items.items[1].items.items[1].items.items[0];
      self.catComboAdd  = Globals.DEPO["viewport"].items.items[0].items.items[1].items.items[1].items.items[1].items.items[0];
      self.catComboDel  = Globals.DEPO["viewport"].items.items[0].items.items[1].items.items[1].items.items[1].items.items[1];
      
      self.addNewItem   = Globals.DEPO["viewport"].items.items[0].items.items[0].items.items[1].items.items[0];
      self.delNewItem   = Globals.DEPO["viewport"].items.items[0].items.items[0].items.items[1].items.items[1];
      
      self.addNewItem.addListener({
        click: function() {
          self.modal('addNewItem');
        }
      });
      self.delNewItem.addListener({
        click: function() {
          self.modal('delNewItem');
        }
      });
      self.langComboAdd.addListener({
        click: function() {
          self.modal('langComboAdd');
        }
      });
      self.langComboDel.addListener({
        click: function() {
          self.modal('langComboDel');
        }
      });
      self.catComboAdd.addListener({
        click: function() {
          self.modal('catComboAdd');
        }
      });
      self.catComboDel.addListener({
        click: function() {
          self.modal('catComboDel');
        }
      });
      
      self.langCombo.addListener({
        select: function() {
          self.scope.model.language         = this.getValue().split('|')[1];
          self.scope.model.store.proxy.url  = ['lang?lang=',self.scope.model.language,'&cat=',self.scope.model.cat].join('');
          self.scope.model.store.load();
        }
      });
      self.catCombo.addListener({
        select: function() {
          self.scope.model.cat              = this.getValue();
          self.scope.model.store.proxy.url  = ['lang?lang=',self.scope.model.language,'&cat=',self.scope.model.cat].join('');
          self.scope.model.store.load();
        }
      });
      
      /*self.Iddqd = Ext.create('Ext.grid.Panel', {
        title   : 'Translate',
        id      : "Iddqd",
        store   : self.store,
        renderTo: Ext.get('translateContainer'),
        columns : [
          {header : 'id'               , dataIndex: 'id'},
          {header : 'Kategória'        , dataIndex: 'category'},
          {header : 'Változó'          , dataIndex: 'variable'},
          {header : 'Kifejezés'        , dataIndex: 'word', renderer : this.renderer, editor : {xtype: 'textfield',allowBlank: false}},
          {header : 'Idegen kifejezés' , dataIndex: 'foreign_word', renderer : this.renderer, editor : {xtype: 'textfield',allowBlank: false}}
        ],
        plugins: [self.rowEditing],
        //height    : 400,
        width     : 700,
        layout: {
          align: 'stretch',
          type: 'fit'
        },
        dockedItems: [{
          xtype       : 'pagingtoolbar',
          store       : self.store,
          dock        : 'top',
          displayInfo : true,
          displayMsg  : 'Találatok: {0} - {1} of {2}',
          emptyMsg    : "Nincs találat."
        }]
      });*/
    }
  }
});
