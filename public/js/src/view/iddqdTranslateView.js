Ext.define('IddqdTranslateView', {
  
  extend: 'View',

  renderer: function(str) {
    return ['<b>',str,'</b>'].join('');
  },

  render: function(data) {
    
    var itemsPerPage  = 10;
    
    var store         = Ext.create('Ext.data.Store', {
      storeId   : 'translate',
      fields    : ['id', 'category', 'variable', 'word', 'foreign_word'],
      pagesize  : 10,
      
      /*listeners : {
        beforeload: function(store, options) {
          params  : {
            start   : 0,
            limit   : 10
          }
        }
      },*/
      
      proxy : {
        type        : 'ajax',
        url         : 'lang',
        extraParams :{'show':10},
        reader    : {
          limitParam    : 'limit',
          type          : 'json',
          pagesize      : 10,
          root          : 'rows',
          totalProperty : 'results',
          }
        }      
      });
      
      store.on('beforeload', function() { console.log();
        store.baseParams = {
          start   : 0,
          limit   : 10
        };
      });
      
      store.load({
        params  : {
          start   : 0,
          limit   : 10
        }
      });

      var rowEditing = Ext.create('Ext.grid.plugin.RowEditing',{
        clicksToEdit: 1
      });  
        
      rowEditing.on({
        scope:this,
        afteredit: function(roweditor, changes, record, rowIndex){
          this.scope.model.updateRow(roweditor);
        }
      }); 
      
      var Iddqd = Ext.create('Ext.grid.Panel', {
        title   : 'Translate',
        id      : "Iddqd",
        store   : store,
        columns : [
          {header : 'id'                , dataIndex: 'id'},
          {header : 'Kategória'          , dataIndex: 'category'},
          {header : 'Változó'          , dataIndex: 'variable'},
          {header : 'Kifejezés'         , dataIndex: 'word', renderer : this.renderer, editor : {xtype: 'textfield',allowBlank: false}},
          {header : 'Idegen kifejezés'  , dataIndex: 'foreign_word', renderer : this.renderer, editor : {xtype: 'textfield',allowBlank: false}}
        ],
        plugins: [rowEditing],
        height    : 400,
        width     : 700,
        dockedItems: [{
          xtype: 'pagingtoolbar',
          store: store,
          pageSize: 10,
          dock: 'top',
          displayInfo: true,
          displayMsg: 'Displaying results {0} - {1} of {2}',
          emptyMsg: "No results to display"
         }]
       });
     
//     Iddqd.getDockedComponent('pagingtoolbar')
     
//    var thisGridPanel = Iddqd.getDockedItems(true);
 
    /*var pager = Ext.create('Ext.toolbar.Toolbar', {
      dock: 'top',
      pageSize: itemsPerPage,
      displayInfo: true,
      displayMsg: 'Displaying results {0} - {1} of {2}',
      emptyMsg: "No results to display",
      items: [{text:'button text'}]
    });*/
    
//    Iddqd.addDocked(pager);
 
    /*var paging = new Ext.PagingToolbar(thisGridPanel, store, {
        pageSize: itemsPerPage,
        displayInfo: true,
//        store: store,
//        dock: 'top',
        displayMsg: 'Displaying results {0} - {1} of {2}',
        emptyMsg: "No results to display"
    });*/
     
     Globals.DEPO["viewport"].add(Iddqd);
     Globals.DEPO["viewport"].doLayout();
      
  }
});
