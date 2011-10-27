Ext.define('IddqdTranslateView', {
  
  extend: 'View',

  render: function(data) { console.log(Globals.DEPO);
    
    var itemsPerPage  = 6;
    var store         = Ext.create('Ext.data.Store', {
      storeId   : 'translate',
      fields    : ['id', 'category', 'variable', 'word', 'foreign_word'],
      //autoLoad  : {start: 0, limit: this.itemsPerPage},
      proxy : {
        type    : 'ajax',
        url     : 'lang',
        reader  : {
          type          : 'json',
          root          : 'rows',
          totalProperty : 'results',
          limit   : itemsPerPage,
          id: 'id'
          }
        }      
      });
      
      store.load({
        params  : {
          start   : 0,
          show    : itemsPerPage,
          limit   : itemsPerPage
        }
      });

    
    var Iddqd = Ext.create('Ext.grid.Panel', {
      title   : 'Translate',
      id      : "Iddqd",
      store   : store,
      columns : [
        {header : 'id'                , dataIndex: 'id'},
        {header : 'Category'          , dataIndex: 'category'},
        {header : 'Variable'          , dataIndex: 'variable'},
        {header : 'Kifejezés'         , dataIndex: 'word'},
        {header : 'Idegen kifejezés'  , dataIndex: 'foreign_word'}
      ],
      height    : 400,
      width     : 700,
      dockedItems: [{
        xtype: 'pagingtoolbar',
        store: store,
        dock: 'top',
        pageSize: itemsPerPage,
        limit   : itemsPerPage,
        displayInfo: true
       }]
     });
     
     Globals.DEPO["viewport"].add(Iddqd);
     Globals.DEPO["viewport"].doLayout();
      
  }
});