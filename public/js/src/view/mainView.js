Ext.define('MainView', {
  
  extend: 'View',

  render: function(data) {
    var maxima = Ext.create('Ext.container.Viewport', {
      xtype: 'viewport',
      border: 0,
      margin: 0,
      padding: 0,
      style: 'background: #EBEEF2;',
      maintainFlex: true,
      layout: {
          type: 'fit'
      },
      items : data,
      renderTo : Ext.getBody()
    });
  }
});
