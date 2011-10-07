/**
 * static class Router
 */
Ext.define('Router', {
  
  statics: {
  	
    frontPage 	: "Group",
    route       : "", 
    
    init      	: function() {
      
      if(Router.ie)
        IEHH.setup();
      
      Ext.TaskManager.start({
        run: Router.getRoute,
        interval: 2000
      });
    },
  
    getRoute  	: function() {
      // TODO needs refact, set up the hashmark order at the url 
      var matches = window.location.href.match(/(.#)(.*)/)[2];
          
      if(matches != null)
        if(Router.route != matches)
          if(typeof Globals.DEPO[matches] == "undefined" && matches != "") {
            try {
              // init and store(its ref) the relevant controller class
              (new Function(['Globals.DEPO["',matches,'"] = new ',matches,'Controller();'].join("")))();
              
              //set history for ie
              if(Router.ie)
                IEHH.changeContent(["#",matches].join(""));
              
              Router.route = matches;
            } catch(err) { console.log(matches);
              delete Globals.DEPO[matches];
              Router.setRoute(Router.frontPage);
            }
          }
        //else
          //TODO needs refact, we dont need the controller, only the view this time 
          //Globals.DEPO[matches].getData();
    },
    
    setRoute    : function(route) {
      window.location.href = [window.location.href.split("#")[0],"#",route].join("");
    },
  	
    constructor: function() {}
  }

},
  function(){
    if(navigator.appVersion.match(/MSIE/))
      Router.ie = 1;
      
    Router.init();
  }
);