Ext.define('MainView', {
  
  extend: 'View',

  render: function(data) { //console.log(data); Ext.window.Window  //Ext.Component  Ext.container.Viewport
  
    var maxima = Ext.create('Ext.container.Viewport', data); 
    
    //maxima.show();
    
    //Ext.apply(maxima, data);
    
    console.log(maxima);
  
    /*var maxima = Ext.create('Ext.Component', {
      
      renderTo  : Ext.getBody(),
      items : data
      
    });
    
    Ext.apply(maxima, data);
    
    maxima.show();
    
    console.log(maxima);*/
  
  
  
    //var Maxima = Ext.create('Maxima', data);

    //Ext.Loader.setConfig({enabled:true});
    
    /*var MaximaViewport = Ext.create('ext-template', {
        renderTo: Ext.getBody()
    });*/
    
    //Ext.apply(MaximaViewport, data);
    
    //MaximaViewport.show();
    
    //console.log(MaximaViewport);



    /*// viewport
    Ext.create('Ext.container.Viewport', {
      border: 0,
      margin: 0,
      padding: 0,
      style: 'background: #EBEEF2;',
      maintainFlex: true,
      title    : "",
      layout   : {
          type  : 'fit'
      },
      renderTo : Ext.getBody()
    }).show();

    // wrapper container
    Ext.create('Ext.container.Container', {
      renderTo  : Ext.getBody(),
      id        : "maximaContainer",
      margin    : 0,
      minHeight : 300,
      minWidth  : 1200,
      layout    : {
        align : 'stretch',
        type  : 'hbox'
      }
    });

    // wrapper left container
    Ext.create('Ext.container.Container', {
      renderTo  : Ext.get("maximaContainer"),
      id        : "maximaLeftContainer",
      margin: 10,
      layout: {
        align : 'stretch',
        type  : 'vbox'
      },
      flex: 1
    });

    // wrapper right container
    Ext.create('Ext.container.Container', {
      renderTo  : Ext.get("maximaContainer"),
      id        : "maximaRightContainer",
      autoShow  : true,
      margin    : '10 10 10 0',
      width     : 200,
      layout    : {
        align     : 'stretch',
        type      : 'hbox'
      }
    });

    // menu wrapper container
    Ext.create('Ext.container.Container', {
      renderTo  : Ext.get("maximaLeftContainer"),
      id        : "maximaMenuWrapper",
      height    : 100,
      layout    : {
        type      : 'fit'
      }
    });

    // toolbar container
    Ext.create('Ext.toolbar.Toolbar', {
      height    : 90,
      layout    : {
        align     : 'stretch',
        type      : 'hbox'
      },
      renderTo  : Ext.get("maximaMenuWrapper"),
      id        : "maximaToolbar",

      items     : [{
        xtype   : "buttongroup",
        text    : 'Kérdőívek',
        id      : "maximaKerdoivCont",
        layout  : "fit",
        buttons : [{
          text    : 'Kérdőívek',
          id      : "kerdoivBtn",
          columns   : 1,
          flex      : 1,
          handler : ""
        }]
      },{
        xtype   : "buttongroup",
        text    : 'Tagok',
        id      : "maximaTagokCont",
        buttons: [{
          text      : 'Tagok listája',
          id        : "memberlist",
          columns   : 3,
          flex      : 2,
          disabled  : true,
          handler   : ""
        }]
      }]
    });*/

//    // toolbar kerdoivek
//    Ext.create('Ext.container.ButtonGroup', {
//      height    : 90,
//      style     : 'background: #C9DDF6;',
//      title     : 'kérdőívek',
//      flex      : 1,
//      rowspan   : 1,
//      columns   : 1,
//      renderTo  : Ext.get("maximaToolbar"),
//      id        : "maximaKerdoivCont",
//      buttons: [{
//        text    : 'Kérdőívek',
//        id      : "kerdoivBtn",
//        handler : ""
//      }]
//    });

//    // toolbar tagok
//    Ext.create('Ext.container.ButtonGroup', {
//      height    : 90,
//      style     : 'background: #C9DDF6;',
//      title     : 'Tagok',
//      flex      : 2,
//      rowspan   : 1,
//      columns   : 3,
//      renderTo  : Ext.get("maximaToolbar"),
//      id        : "maximaTagokCont",
//      buttons: [{
//        text      : 'Tagok listája',
//        id        : "memberlist",
//        disabled  : true,
//        handler   : ""
//      }]
//    });


  }


});
