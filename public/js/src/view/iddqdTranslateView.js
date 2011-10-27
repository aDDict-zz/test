Ext.define('IddqdTranslateView', {
  
  extend: 'View',

  renderer: function(str) {
    return ['<b>',str,'</b>'].join('');
  },
  
  editor: function(str) {
    console.log(str);
    return str + 'FFF';
  },

  render: function(data) { //console.log(Globals.DEPO);
    
    //console.log(this.scope.model);
    
//    var thisEditor = new Ext.ux.grid.RowEditor();
    
    var itemsPerPage  = 6;
    var store         = Ext.create('Ext.data.Store', {
      storeId   : 'translate',
      fields    : ['id', 'category', 'variable', 'word', 'foreign_word'],
      proxy : {
        type    : 'ajax',
        url     : 'lang',
        reader  : {
          type          : 'json',
          root          : 'rows',
          totalProperty : 'results',
          }
        }      
      });
      
      store.load({
        params  : {
          start   : 0,
//          page    : 0,
          limit   : itemsPerPage
        }
      });

    var rowEditing = Ext.create('Ext.grid.plugin.RowEditing',{
      clicksToEdit: 1
    });  
      
    rowEditing.on({
      scope:this,
      afteredit: function(roweditor, changes, record, rowIndex){
//        console.log(roweditor, changes, record, rowIndex);
//        console.log(roweditor.field);
//        console.log(roweditor.record.get('id'));
//        console.log(roweditor.record.get(roweditor.field));
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
      
      /*plugins: [
        Ext.create('Ext.grid.plugin.RowEditing', {
          saveText  : "My Save Button Text",
          cancelText: "My Cancel Button Text",
          clicksToEdit: 1,
          
          listeners: {
            edit: function(e) { alert("asd");
              console.log( e.record.get('id') );
            }
          },
          
//          on: function(roweditor, changes, record, rowIndex) {
//            console.log(roweditor, changes, record, rowIndex);
//          }, 
//          startEdit: function(roweditor, changes, record, rowIndex) {
//            console.log("asdasd");
//            console.log(roweditor, changes, record, rowIndex);
//          },
          
          completeEdit: function(roweditor, changes, record, rowIndex) { //obj.itemId getTargetEl
//            console.log(roweditor,changes,record,rowIndex);
//            
//            console.log(roweditor.getText());
            //console.log(obj); console.log(obj.getText());
          },
          on: function(roweditor, changes, record, rowIndex) {
            console.log(record.get('id'));
          }
        })
      ],*/
      height    : 400,
      width     : 700,
      dockedItems: [{
        xtype: 'pagingtoolbar',
        store: store,
        dock: 'top',
        displayInfo: true
       }]
     });
     
     Globals.DEPO["viewport"].add(Iddqd);
     Globals.DEPO["viewport"].doLayout();
      
  }
});
