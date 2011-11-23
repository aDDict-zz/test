Ext.define('IddqdTranslateView', {
  
  extend: 'View',
  
  renderer: function(str) {
    return ['<span style="font-weight:bold;">',str,'</span>'].join('');
  },
  
  render: function(data) {
    
    
    if(!Ext.get("Iddqd")) {
      
      var self            = this;
      self.itemsPerPage   = 10;
      self.language       = 'hu';
      self.cat            = 1;
      
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
      });
       
      self.store         = Ext.create('Ext.data.Store', {
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
        })
        
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
          self.scope.model.updateRow(roweditor,changes);
        }
      });
      
      // ext.apply & Ext.decode arent workin well, we need a simple eval
      Globals.DEPO["viewport"] = Ext.create('Ext.container.Viewport', eval("("+data+")"));
      
      // fuck this lookup
      self.langCombo  = Globals.DEPO["viewport"].items.items[0].items.items[1].items.items[0].items.items[0];
      self.catCombo   = Globals.DEPO["viewport"].items.items[0].items.items[1].items.items[1].items.items[0];
      
      self.langCombo.addListener({
          select: function() {
            self.language         = this.getValue().split('|')[1];
            self.store.proxy.url  = ['lang?lang=',self.language,'&cat=',self.cat].join('');
            self.store.load();
          }
      });
      self.catCombo.addListener({
          select: function() {
            self.cat = this.getValue();
            self.store.proxy.url  = ['lang?lang=',self.language,'&cat=',self.cat].join('');
            self.store.load();
          }
      });
      
      self.Iddqd = Ext.create('Ext.grid.Panel', {
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
      });
    }
  }
});
