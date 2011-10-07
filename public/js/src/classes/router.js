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
      var match = window.location.href.match(/(.#)(.*)/)[2];
      
      if(match != null)
        if(Router.route != match)
          if(typeof Globals.DEPO[[match,"Controller"].join("")] == "undefined" && match != "") {
            try {
              // init and store(its ref) the relevant controller class
              (new Function(['Globals.DEPO["',match,'Controller"] = new ',match,'Controller();'].join("")))();
              
              // init and store(its ref) the relevant view class
              (new Function(['Globals.DEPO["',match,'View"] = new ',match,'View();'].join("")))();
              
              //set history for ie
              if(Router.ie)
                IEHH.changeContent(["#",match].join(""));
              
              Router.route = match;
            } catch(err) { console.log(match);
              delete Globals.DEPO[match];
              Router.setRoute(Router.frontPage);
            }
          }
        else {
          //TODO needs refact, we dont need the controller, only the view this time, needs a value of the view state, displayed or not or sthing like this
          //Globals.DEPO[match].getData();
          console.log( Globals.DEPO[[match,"Controller"].join("")].data );
          //Globals.DEPO[[match,"View"].join("")].render(Globals.DEPO[[match,"Controller"].join("")].data);
        }
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