Ext.define('IddqdTranslateView', {
  
  extend: 'View',
  
  renderer: function(str) {
    return ['<b>',str,'</b>'].join('');
  },
  
  render: function(data) { console.log(data);
    
    /*if(!Ext.get("Iddqd")) {
      var self           = this;
      self.itemsPerPage  = 10;
      
      self.store         = Ext.create('Ext.data.Store', {
        storeId : 'translate',
        fields  : ['id', 'category', 'variable', 'word', 'foreign_word'],
        proxy   : {
          type      : 'ajax',
          url       : 'lang',
          reader    : {
            type          : 'json',
            root          : 'rows',
            totalProperty : 'results',
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
          self.scope.model.updateRow(roweditor,changes);
        }
      });
      
      Globals.DEPO["viewport"] = Ext.create('Ext.container.Viewport', {
        xtype: 'viewport',
        border: 0,
        margin: 0,
        padding: 0,
        style: 'background: #EBEEF2;',
        maintainFlex: true,
        renderTo : Ext.getBody(),
        layout: {
          align: 'stretch',
          type: 'hbox'
        },
        items: [{
          id: 'translateContainer',
          xtype: 'container',
          margin: 20,
          height    : 600,
          width     : 1100,
          layout: {
            align: 'stretch',
            type: 'hbox'
          },
          items: [{
            xtype   : 'grid',
            title   : 'Translate',
            id      : "Iddqd",
            store   : self.store,
            columns : [
              {header : 'id'               , dataIndex: 'id'},
              {header : 'Kategória'        , dataIndex: 'category'},
              {header : 'Változó'          , dataIndex: 'variable'},
              {header : 'Kifejezés'        , dataIndex: 'word', renderer : this.renderer, editor : {xtype: 'textfield',allowBlank: false}},
              {header : 'Idegen kifejezés' , dataIndex: 'foreign_word', renderer : this.renderer, editor : {xtype: 'textfield',allowBlank: false}}
            ],
            plugins: [self.rowEditing],
              height    : 400,
              width     : 700,      
              layout: {
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
            
            }, {
              xtype   : 'panel',
              title   : 'Valami',
              height  : 400,
              width   : 350,
              layout: {
                align: 'stretch',
                type: 'vbox'
              },
            }]
          }]
      });*/
      
      /*self.Iddqd = Ext.create('Ext.grid.Panel', {
        title   : 'Translate',
        id      : "Iddqd",
        store   : self.store,
        //renderTo: Ext.get('translateContainer'),
        columns : [
          {header : 'id'               , dataIndex: 'id'},
          {header : 'Kategória'        , dataIndex: 'category'},
          {header : 'Változó'          , dataIndex: 'variable'},
          {header : 'Kifejezés'        , dataIndex: 'word', renderer : this.renderer, editor : {xtype: 'textfield',allowBlank: false}},
          {header : 'Idegen kifejezés' , dataIndex: 'foreign_word', renderer : this.renderer, editor : {xtype: 'textfield',allowBlank: false}}
        ],
        plugins: [self.rowEditing],
        height    : 400,
        width     : 800,
        layout: {
          //align: 'stretch',
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
      }).show();*/
       
      //Ext.get('translateContainer').add(self.Iddqd);
      //Ext.get('translateContainer').doLayout();
    //}
  }
});
