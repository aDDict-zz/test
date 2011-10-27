Ext.define('IddqdTranslateModel', {

  extend: 'Model',
  
  init: function() {
  },
  
  updateRow: function(roweditor) {
    AJAX.get(
      "lang/update",
      ['field=',roweditor.field,'&id=',roweditor.record.get('id'),'&val=',roweditor.record.get(roweditor.field)].join(''),
      this.mapper,
      self
    );
  },
  
  mapper: function(data){
  },
  
  getAjaxData: function(){
  }
  
});
