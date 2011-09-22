Ext.define('LoginView', {

	extend: 'View',
	
	render: function(data){
	  
	  Ext.create('Ext.window.Window', {
      title     : 'Login',
      id        : 'loginBody',
      renderTo  : Ext.getBody(),
      height    : 180,
      width     : 250,
      layout    : 'fit',
      layout    : 'column',
      items: {  
        xtype     : 'form',
        height    : 145,
        width     : 237,
        items     : data.items,
        url       : data.action,
        buttons: [{
          text      : 'login',
          handler   : function() {
            var form = this.up('form').getForm();
            form.submit({
              success : function(form, action){
                //console.log(form, action);
              }
            });
          }
        }]
      }
    }).show();
	}
	
	/*submit: function(){
	  
	}*/
	
});

