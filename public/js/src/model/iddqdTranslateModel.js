Ext.define('IddqdTranslateModel', {

  extend: 'Model',
  
  init: function() {
    this.loader = new Ext.LoadMask(Ext.getBody(), {msg:"loading"});
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
        //self.roweditor.record.set(self.roweditor.field,"somwValue");
        self.roweditor.record.commit();
        Globals.DEPO["IddqdTranslateController"].view.Iddqd.getView().refresh();
        self.loader.hide();
      },
      self
    );
  },
  
  mapper: function(data){
  },
  
  getAjaxData: function(){
  }
  
});
