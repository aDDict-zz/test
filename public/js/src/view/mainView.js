Ext.define('MainView', {
  
  extend: 'View',

  render: function(data) {
    
    Globals.DEPO["viewport"] = Ext.create('Ext.container.Viewport', {
      xtype: 'viewport',
      border: 0,
      margin: 0,
      padding: 0,
      style: 'background: #EBEEF2;',
      maintainFlex: true,
      renderTo : Ext.getBody(),
      layout: {
          type: 'fit'
      },
      items : [{
        id: 'Main',
        xtype: 'container',
        layout: {
            type: 'fit'
        },
        items : data
      }]
    });
  }
});
