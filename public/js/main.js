var $$ = {
  orders    : ["groups","demog","trillili","trallala"],
  order     : "",
  frontPage : "groups",
  init      : function(){
    if($$.ie)
      IEHH.setup();
    
    t.add({
      "method"    : $$.getOrder,
      "interval"  : 100
    });
  },
  getOrder  : function(){
    /*var matches;
    if(matches = window.location.href.match(/(.*)(#)(.*)/))*/
    console.log(window.location.hash);
  }
}
/*
  iframe hack for ie history featureless
*/
var IEHH = new function () {
  this.constructor        = null;
  this.DEPO               = "";
  this.init               = _init;
  this.setup              = _setup;
  this.changeContent      = _changeContent;
  this.checkIframeContent = _checkIframeContent;

  function _init() {
    navigator.appName.match("Microsoft") != null ? this.setup() : "";
  }
  function _setup() {
    var thisIframe = document.createElement('<iframe id="thisIframe" style="display:none;" src="about:blank" />'),
        thisBody = document.getElementsByTagName("body")[0];

    thisBody.appendChild(thisIframe);
    T.add({
      "function": IEHH.checkIframeContent,
      "interval": 50
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
};

/*
timer
*/
var t = new function () {
    this.constructor    = null;
    // private
    this.DEPO           = [];
    this.CASH           = 0;
    this.stopListener   = _stopListener;
    this.counter        = _counter;
    this.listener       = _listener;
    this.intervalId;
    // public
    this.add            = _add;
    this.del            = _del;

    function _counter() {
      this.intervalId = setInterval(this.listener, 4)
    }
    function _listener() {
      t.CASH == 9999 ? t.CASH = 0 : t.CASH++;
      var thisRemainder, thisFunction, thisObject, objLength = t.DEPO.length;
      for (var i = 0; i < objLength; i++) {
        thisRemainder = t.CASH % t.DEPO[i]["interval"];
        if (thisRemainder == 0) {
          thisFunction = t.DEPO[i]["method"];
          thisFunction()
        }
      }
    }
    function _stopListener() {
      clearInterval(this.intervalId)
    }
    function _add(thisArray) {
      if (this.DEPO.length == 0) {
        this.counter()
      }
      var listener = 0,
        objLength = this.DEPO.length;
      for (var i = 0; i < objLength; i++) {
        if (this.DEPO[i]["method"] == thisArray["method"]) {
          listener++;
          break;
        }
      }
      if (listener == 0) {
        this.DEPO[this.DEPO.length] = {
          "method": thisArray['method'],
          "interval": thisArray['interval']
        }
      }
    }
    function _del(thisFunction) {
      var objLength = this.DEPO.length - 1;
      for (var i = objLength; i >= 0; i--) {
        if (this.DEPO[i]["function"] == thisFunction) {
            this.DEPO.splice(i, 1)
        }
      }
      if (this.DEPO.length == 0) {
        this.stopListener()
      }
    }
 }

/*
|\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\
|       0\  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\
| A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /
|/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/
*/

window.onload = function(){
  if(navigator.appVersion.match(/MSIE/))
    $$.ie = 1;
  $$.init();
};