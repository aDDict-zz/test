/**
* @author robThot, hirekmedia
*/


/*
  iframe hack for ie history featureless
*/
Ext.define('IEHH', {

	statics: {

		DEPO: "",
		
		init: function(){
			navigator.appName.match("Microsoft") != null ? this.setup() : "";
		},
  
		setup: function(){
  
			var thisIframe  = document.createElement('<iframe id="thisIframe" style="display:none;" src="about:blank" />'),
				  thisBody    = document.getElementsByTagName("body")[0];
        
			document.appendChild(thisIframe);
    
			Ext.TaskManager.start({
				run: IEHH.checkIframeContent,
				interval: 1000
			});
		},
  
		changeContent: function(urlPart){
			var thisIframe    = document.getElementById("thisIframe"),
					thisIframeDoc = thisIframe.contentWindow.document;

			thisIframeDoc.open();
			thisIframeDoc.write(urlPart);
			thisIframeDoc.close();
			IEHH.DEPO = urlPart;
		},
  
		checkIframeContent: function(){
			var thisIframe        = document.getElementById("thisIframe"),
					thisIframContent  = thisIframe.contentWindow.document.body.innerHTML;

			if (window.location.href.match("#") && thisIframContent != "") {
				var thisArr = window.location.href.split("#"),
					thisUrlPart = ["#",thisArr[1]].join("");
				if (thisUrlPart != thisIframContent) {
					window.location.href = [thisArr[0],thisIframContent].join("");
				}
			}
		},
    
    constructor: function() {}
	}
},
  function(){}
);

Ext.define('$$', {
  
  statics: {
  
    orders    : ["groups","demog","trillili","trallala"],
    order     : "",
    frontPage : "groups",
    
    init      : function(){ console.log(Ext.Array);
      if($$.ie)
        IEHH.setup();
      
      Ext.TaskManager.start({
        run: $$.getOrder,
        interval: 1000
      });
    },
  
    getOrder  : function(){ console.log( Ext.Array.indexOf($$.orders, "trillili") );
      var matches = window.location.href.match(/(.*)(#)(.*)/);
      if(matches != null){
        if(Ext.Array.indexOf($$.orders, matches[3]) == -1){ alert("frontPage");
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
  
    doJob     : function(){
      if($$.order != "")
        switch($$.order){
          case "groups": alert("groups");
          break;
          case "demog": alert("demog");
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