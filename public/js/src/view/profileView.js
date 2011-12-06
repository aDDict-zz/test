Ext.define('ProfileView', {
  
  extend: 'View',

  render: function(data) { //console.log( data );
    var self = this;
    //self.cfg = eval("("+ data +")");
    
    Ext.create('Ext.window.Window', {
      title     : 'Login',
      id        : 'LoginForm',
      renderTo  : Ext.getBody(),
      resizable : false,
      height    : 180,
      width     : 250,
      layout    : 'fit',
      layout    : 'column',
      items: {  
        xtype     : 'form',
        id        : 'loginForm',
        height    : 145,
        width     : 237,
        items     : data.items,
        url       : data.action,
        buttons: [{
          text      : 'login',
          handler   : self.scope.auth
        }]
      }
    }).show();
    
  }
});
