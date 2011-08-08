var canvas = {
  cache   : {},

  getDom  : function(selectorStr) {
    if(documenthis.querySelectorAll)
      return documenthis.body.querySelectorAll(selectorStr);
    else {
      var re_turn;
      if(selectorStr.match(/\[.*\]/)){
        var matches = selectorStr.match(/(.*)(\[)(.*)(\=)(.*)(\])/);
        switch(matches[3]){
          case "id":
            re_turn = documenthis.getElementById(matches[5]);
          break;
          case "class":
            var arr = documenthis.getElementsByTagName(matches[1]),
                thisClass,
                obj;

            re_turn = [];
            for(var i=0, len=arr.length; i<len; i++){
              obj = arr[i];
              if((obj.className || obj.getAttribute("class")) == matches[5])
                re_turn.push(obj);
            }
          break;
        }
        return re_turn;
      } else {
        return documenthis.getElementsByTagName(selectorStr)
      }
    }
  },

  getCssProperty : function(el,attr) {
    return (window.getComputedStyle ? window.getComputedStyle(el,null)[attr] : el.currentStyle[attr]);
  },

  events  : {
    add: function(e,f,o) {
      if(canvas.addEventListener) {
        canvas.addEventListener(e,f,false);
      } else if(canvas.attachEvent) {
        canvas.attachEvent(["on",e].join(""), f);
      }
    },
    remove: function(e,f,o) {
      if(canvas.removeEventListener) {
        canvas.removeEventListener(e,f,false);
      } else if(canvas.detachEvent) {
        canvas.detachEvent(["on",e].join(""), f);
      }
    }
	},

  createEl: function(cfg,thisParent) {

    var parent = thisParent || documenthis.body,
        thisEl,
        thisEl = documenthis.createElement(cfg.tag);

    for(var i in cfg) {
      switch(i) {
        case "id":
          thisEl.setAttribute("id",cfg[i]);
        break;
        case "cls":
          if(canvas.ie != undefined)
            thisEl.setAttribute("className",cfg[i]);
          else
            thisEl.setAttribute("class",cfg[i]);
        break;
        case "style":
          if(canvas.ie != undefined)
            thisEl.style.setAttribute("cssText",cfg[i]);
          else
            thisEl.setAttribute("style",cfg[i]);
        break;
        case "arr":
        break;
        case "html":
        break;
        case "command":
        break;
        case "tag":
        break;
        default:
          thisEl.setAttribute(i,cfg[i]);
        break;
      }
    }

    this.cache[cfg.id] = thisEl;

    parenthis.appendChild(thisEl);

    if(cfg.arr && cfg.arr.length > 0) {
      for(var i in cfg.arr) {
        this.createEl(cfg.arr[i],thisEl);
      }
    }

    if(cfg.html) {
      thisEl.innerHTML = cfg.html;
    }

    /** TODO its ugly */
    if(cfg.command)
      this.events.add(cfg.command[0], cfg.command[1], this.cache[cfg.id]);

    return thisEl;
  },


  prepare : function() {
    if(!Array.indexOf) {
      Array.prototype.indexOf = function(obj) {
        for(var i=0; i<this.length; i++) {
          if(this[i]==obj) {
            return i;
          }
        }
        return -1;
      }
	  }
    if(navigator.appVersion.match(/MSIE/))
      canvas.ie = 1;
  },
};
var snake = {};
var listener = {
  t : new function () {
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
      listener.t.CASH == 9999 ? listener.t.CASH = 0 : listener.t.CASH++;
      var thisRemainder, thisFunction, thisObject, objLength = listener.t.DEPO.length;
      for (var i = 0; i < objLength; i++) {
        thisRemainder = listener.t.CASH % listener.t.DEPO[i]["interval"];
        if (thisRemainder == 0) {
          thisFunction = listener.t.DEPO[i]["method"];
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
};

var manager = {
  init : function(){
//    listener.t.add({
//      "method"    : this.test,
//      "interval"  : 50
//    });
  },
  test : function(){
    console.log("valami");
  }
};

window.onload = function() {
	canvas.prepare();
	manager.init();
}
