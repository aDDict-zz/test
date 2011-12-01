/**
 * static class Router
 */
Ext.define('Router', {
  
  statics: {
  	
    frontPage 	: "Main",
    login       : "Login",
    route       : "",
    routeOrders : [],
    routeParams : {},
    routeCache  : "",
    lang        : "",
    
    init      	: function() {
      
      if(Router.ie)
        IEHH.setup();
      
      Ext.TaskManager.start({
        run: Router.getRoute,
        interval: 2000
      });
    },
    
    getRoute  	: function() {
      
      var order = Router.getOrder();
      
      if(order == "")
        Router.setRoute(Router.frontPage);
      
      // setting up the language
      if(Router.routeParams["lang"])
        Router.lang = Router.routeParams["lang"];
      else {
        if(Router.lang == "")
          Router.lang = "hu";
      }
        
      if(order != null)
        if(Router.route != order)
          if(typeof Globals.DEPO[[order,"Controller"].join("")] == "undefined" || Globals.DEPO[[order,"Controller"].join("")] == null) {
            
            try {
              
              // init and store(its ref) the relevant controller class
              (new Function(['Globals.DEPO["',order,'Controller"] = new ',order,'Controller();'].join("")))();
              
              // set history for ie
              if(Router.ie)
                IEHH.changeContent(["#",order].join(""));
              
              // hiding the previous content
              if(Ext.get(Router.route) != null)
                Ext.get(Router.route).hide();
              
              Router.route = order;
            } catch(err) { console.log(err);
              delete Globals.DEPO[order];
              Message.alert('Routing error', 'There is no implemented class with this namespace', function() {
                Router.setRoute(Router.frontPage);
              });
            }
          }
        else {
          if(Router.route != order) {
            if(Ext.get(Router.route)) {
              Ext.get(Router.route).hide();
            }
            if(Ext.get(order)) {
              Ext.get(order).show();
            }
            Globals.DEPO[[order,"Controller"].join('')].init();
            Router.route = order;
          }
        }
    },
    
    setRoute    : function(route) {
      window.location.href = [window.location.href.split("#")[0],"#",route].join("");
    },
    
    getOrder    : function() {
      if(Router.routeCache != window.location.href) {
        Router.routeOrders = [];
        Router.routeParams = {};
        var matches = (window.location.href.match(/(.#)(.*)/) ? window.location.href.match(/(.#)(.*)/) : null);
        if(matches == null) {
          Router.setRoute(Router.frontPage);
        } else {
          route = matches[2];
          if(route.match(/\//)) {
            var orders = route.split('/'), arr;
            for(var i = 0, len = orders.length;i < len; i++) {
              if(orders[i].match(/=/)) {
                arr = orders[i].split("=");
                Router.routeParams[arr[0]] = arr[1];
              } else {
                Router.routeOrders.push(orders[i]);
              }
            }
            route = Router.routeOrders[0];
          }
          Router.routeCache = window.location.href;
          return route;
        }
      } else {
        return Router.routeOrders[0];
      }
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