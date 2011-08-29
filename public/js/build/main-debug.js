/*!
 * Maxima Javascript Engine Built on ExtJs 4.0
 */
alert("CLASSES");/**
* @author robThot, hirekmedia
*/

/*var IEHH = new function () {
  this.constructor        = null;
  this.DEPO               = "";
  this.init               = _init;
  this.setup              = _setup;
  this.changeContent      = _changeContent;
  this.checkIframeContent = _checkIframeContent;

  function _init() {
    navigator.appName.match("Microsoft") != null ? this.setup() : "";
  }
  function _setup() { alert("IEHH");
    var thisIframe = document.createElement('<iframe id="thisIframe" style="display:none;" src="about:blank" />'),
        thisBody = document.getElementsByTagName("body")[0];

    thisBody.appendChild(thisIframe);
    t.add({
      "method"    : IEHH.checkIframeContent,
      "interval"  : 50
    })
  }
  function _changeContent(urlPart) {
    var thisIframe    = document.getElementById("thisIframe"),
        thisIframeDoc = thisIframe.contentWindow.document;

    thisIframeDoc.open();
    thisIframeDoc.write(urlPart);
    thisIframeDoc.close();
    IEHH.DEPO = urlPart;
  }
  function _checkIframeContent() {
    var thisIframe        = document.getElementById("thisIframe"),
        thisIframContent  = thisIframe.contentWindow.document.body.innerHTML;

    if (window.location.href.match("#") && thisIframContent != "") {
      var thisArr = window.location.href.split("#"),
        thisUrlPart = ["#",thisArr[1]].join("");
      if (thisUrlPart != thisIframContent) {
        window.location.href = [thisArr[0],thisIframContent].join("");
      }
    }
  }
};*/

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

/*
  |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\
  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\
  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /
  |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/
*/

//Ext.application({
//  name: 'HelloExt',
//  launch: function() {
//    
//    //alert($$.title);
//    
//    $$.fokk();
//    
//    console.log($$.fokk);
//    
//    /*if(navigator.appVersion.match(/MSIE/))
//      $$.ie = 1;
//    
//    $$.init();*/
//  
//    Ext.create('Ext.container.Viewport', {
//        layout: 'fit',
//        items: [
//            {
//                title: 'Hello Ext',
//                html : 'Hello! Welcome to Ext JS.'
//            }
//        ]
//    });
//  }
//});


/*Ext.onReady = function(){ alert("sadasdsad");
  if(navigator.appVersion.match(/MSIE/))
    $$.ie = 1;
  
  $$.init();
}*/

/*window.onload = function(){
  //fuckin loader for the extJs. TODO baszott ugly
  var extLoader = function(){
    if(typeof Ext != "undefined"){
      t.del(extLoader);
      if(navigator.appVersion.match(/MSIE/))
        $$.ie = 1;
      
      $$.init();
    }
  }
  
  t.add({
    "method"    : extLoader,
    "interval"  : 10
  });
}*/
alert("CONTROLLER");alert("VIEW");alert("MODEL");