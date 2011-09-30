Ext.define('$$', {
  
  statics: {
  	
  	orders    	: ["login","logout","groups","demog"],
    order     	: "",
    frontPage 	: "groups",
    
    init      	: function(){
      if($$.ie)
        IEHH.setup();
      
      Ext.TaskManager.start({
        run: $$.getOrder,
        interval: 1000
      });
    },
  
    getOrder  	: function(){
      var matches = window.location.href.match(/(.*)(#)(.*)/);
      if(matches != null){
        if(Ext.Array.indexOf($$.orders, matches[3]) == -1){
          window.location.href = [matches[1],"#",$$.frontPage].join("");
        } else {
          if($$.order != matches[3]){
            if($$.ie)
              IEHH.changeContent(["#",matches[3]].join(""));
              
            $$.order = matches[3];
            $$.doJob();
          }
        }
      } else {
        window.location.href = [window.location.href,"#",$$.frontPage].join("");
      }
    },
  	
  	// set up the routing order
    doJob     	: function(){
      if($$.order != "")
        switch($$.order){
		  case "login":
      	 	new LoginController();
          break;
          case "groups":
      	 	new GroupController();
          break;
          case "demog":
          	new DemogController();
          break;
        }
    },
    
    constructor: function() {}
  }

},
  // initCallback
  function(){
    if(navigator.appVersion.match(/MSIE/))
      $$.ie = 1;
      
    $$.init();
  }
);