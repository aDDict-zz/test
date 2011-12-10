/*
  @author    robThot
  @Copyright (c) robThot, ROZITIV
*/
var o = {

  cache   : {},

  colours : {
    def     : {
      bg      : "#333333",
      lightbg : "#EDF4F5",
      front   : "#669966",
      hover   : "#FFCC00",
      white   : "#fff",
      extra   : "#993366"
    },
    scheme35  : {
      bg      : "#400D12",
      lightbg : "#CDFFFF",
      front   : "#FF0000",
      hover   : "#4FD5D6",
      white   : "#CDFFFF",
      extra   : "#993366"
    },
    scheme121  : {
      bg      : "#8C8984",
      lightbg : "#005B9A",
      front   : "#0191C8",
      hover   : "#74C2E1",
      white   : "#CDFFFF",
      extra   : "#993366"
    }
  },

  menu    : {
    hu      : {
      rozitiv     : "ROZITÍV",
      collection  : "KOLLEKCIÓ",
      order       : "RENDELÉS",
      contact     : "KONTAKT"
    },
    en      : {
      rozitiv     : "ROZITIV",
      collection  : "COLLECTION",
      order       : "ORDER",
      contact     : "CONTACT"
    }
  },

  getDom  : function(selectorStr) {
    if(document.querySelector) {
      return document.body.querySelector(selectorStr);
    } else {   
      // working only with the (\[id\=)(.*)(\]) formula
      if(selectorStr.match(/\[.*\=.*\]/)) {
        var matches = selectorStr.match(/(.*)(\[)(.*)(\=)(.*)(\])/);
        switch(matches[3]) {
          case "id":
            return document.getElementById(matches[5]);
          break;
          case "class":
            var arr       = [],
                elements  = document.getElementsByTagName(matches[1]);
            for(var i = 0,len = elements.length; i < len;i++) {
              if( (elements[i].className ? elements[i].className : elements[i].getAttribute("class") ) == matches[5])
                arr.push(elements[i]);
            }
            return arr;
          break;
        }
      }
      // in this case hopefully its a simple tag
      else {
        return document.getElementsByTagName(selectorStr);
      }
    }
  },
  
  getCssProperty : function(el,attr) {
    return (window.getComputedStyle ? window.getComputedStyle(el,null)[attr] : el.currentStyle[attr]);
  },

  events  : {
    add: function(e,f,o) {
      if(o.addEventListener) {
        o.addEventListener(e,f,false);
      } else if(o.attachEvent) {
        o.attachEvent(["on",e].join(""), f);
      }
    },
    remove: function(e,f,o) {
      if(o.removeEventListener) {
        o.removeEventListener(e,f,false);
      } else if(o.detachEvent) {
        o.detachEvent(["on",e].join(""), f);
      }
    }
  },

  createEl: function(cfg,thisParent) {

    var parent = thisParent || document.body,
        thisEl,
        thisEl = document.createElement(cfg.tag);

    for(var i in cfg) {
      switch(i) {
        case "id":
          thisEl.setAttribute("id",cfg[i]);
        break;
        case "cls":
          if(o.ie != undefined)
            thisEl.setAttribute("className",cfg[i]);
          else
            thisEl.setAttribute("class",cfg[i]);
        break;
        case "style":
          if(o.ie != undefined)
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
        default: //console.log(cfg.id, cfg.tag, i);
          thisEl.setAttribute(i,cfg[i]);
        break;
      }
    }

    this.cache[cfg.id] = thisEl;

    parent.appendChild(thisEl);

    if(cfg.arr) {
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

  getColorButtons : function() {
    var res     = [],
        colors  = this.colours;

    for(var i in colors) {
      res.push({
        tag     : "div",
        id      : i,
        command : ["click", function(e, options) {
          e = e || window.event;
          var target = e.target || e.srcElement; 
              target = target.parentNode;
          o.render(target.getAttribute("id"));
        }],
        style   : ["height:20px;width:60px;float:left;display:block;margin-left:10px;margin-top:15px;"].join(""),
        arr     : [{
          tag     : "div",
          style   : ["float:left;height:20px;width:15px;border-radius:5px 0px 0px 5px;background:",colors[i].bg].join("")
        },{
          tag     : "div",
          style   : ["float:left;height:20px;width:15px;background:",colors[i].lightbg].join("")
        },{
          tag     : "div",
          style   : ["float:left;height:20px;width:15px;background:",colors[i].hover].join("")
        },{
          tag     : "div",
          style   : ["float:left;height:20px;width:15px;border-radius:0px 5px 5px 0px;background:",colors[i].front].join("")
        }]

      });
    }
    return [{
      tag   : "div",
      id    : "colorButtonsWrapper",
      style : "width:100%;height:30px;text-align:center;display:none;",
      arr   : res
    }];
  },

  prepare : function() {
  	 
    if(o.ie)
      IEHH.setup();

    T.add({
      "function" : o.getOrder,
      "interval" : 100
    });

    // gallery init
    var images = [];
    for(var i=1;i<32;i++) {
      images.push(["thumb/",i,".jpg"].join(""));
    }
    Gallery.init(images);

    document.onmousemove = function(e) {
      e = e || window.event;
      var thisY;
      typeof e.pageY == 'number' ? thisY = e.pageY : thisY = e.clientY;
      var thisScrollTop = parseInt(document.body.scrollTop);
      var thisHeight    = (thisScrollTop+o.windowHeight);
      o.getDom("div[id=BottomWrapper]").style.top = [(thisHeight-70),"px"].join("");
      if(thisY > (thisHeight-70)) {
        if(o.layerLock == false) {
          o.layerLock = true;
          o.anim([
            "ColorChooser",
            "height:70px;margin-top:0px;",
            {duration: 300},
            function() {
              o.layerLock = false;
              o.cache.colorButtonsWrapper.style.display = "block";
            }
          ]);
        }
      }
      if(thisY < (thisHeight-70))
        if(o.layerLock == false) {
          o.layerLock                               = true;
          o.cache.colorButtonsWrapper.style.display = "none";
          o.anim([
            "ColorChooser",
            "height:0px;margin-top:70px;",
            {duration: 300},
            function() {
              o.layerLock = false;
            }
          ]);
        }
    };
  },

  render  : function(thisPref) {
  
    var prefix      = thisPref    || "def",
        language    = o.language  || "hu";

    if(o.cache.Container) {
      this.cache.Container.className ? this.cache.Container.className = [prefix,"Container"].join("") : this.cache.Container.setAttribute("class", [prefix,"Container"].join(""));
      return;
    }

    this.windowHeight = document.body.clientHeight;
    this.colors       = this.colours[prefix];
    this.uri          = (window.location.href.match(/#/) ? window.location.href.split("#")[0] : window.location.href);
    this.prefix       = prefix;

    var thisEl = this.createEl({
      tag     : "div",
      id      : "Container",
      cls     : [prefix,"Container"].join(""),
      style   : ["height:",(this.windowHeight+5),"px;"].join(""),
      arr     : [{
        tag       : "div",
        id        : "Left",
        cls       : "left",
        style     : ["height:",this.windowHeight,"px;float:left;"].join(""),
        arr       : [{
          tag       : "div",
          id        : "c",
          cls       : "c",
          style     : ["margin-top:",Math.floor(this.windowHeight/9),"px"].join(""),
          arr       : [{
            tag       : "div",
            id        : "pages",
            cls       : "pages",
            arr       : [{
              tag       : "div",
              id        : "rozitiv_page",
              cls       : "rozitiv_page"
            }, {
              tag       : "div",
              id        : "collection_page",
              cls       : "collection_page"
            }, {
              tag       : "div",
              id        : "order_page",
              cls       : "order_page"
            }, {
              tag       : "div",
              id        : "contact_page",
              cls       : "contact_page"
            }]
          }]
        }]
      },{
        tag       : "div",
        id        : "Right",
        cls       : "right",
        style     : ["height:",this.windowHeight,"px;float:left;"].join(""),
        arr       : [{
          tag       : "div",
          id        : "logoWrapper",
          cls       : "logoWrapper",
          style     : ["margin-top:",Math.floor(this.windowHeight/9),"px"].join(""),
          arr       : [{
            tag       : "div",
            id        : "rozitiv-R",
            html      : "r"
          },{
            tag       : "div",
            id        : "rozitiv-plus",
            html      : "+"
          }]
        }, {
          tag       : "div",
          id        : "MenuHolder",
          cls       : "menuHolder",
          arr       : this.getMenuItems(this.menu[language])
        }]
      }]
    });

    this.createEl({
      tag     : "div",
      id      : "BottomWrapper",
      style   : ["position:absolute;top:",(this.windowHeight-70),"px;width:100%;height:70px;margin:0 auto;padding:0px;left:0px;"].join(""),
      arr     : [{
        tag     : "div",
        id      : "ColorChooser",
        style   : ["width:100%;height:0px;margin-top:70px;background:",this.colors.bg,";border-radius: 5px 5px 0px 0px;display:block;"].join(""),
        arr     : this.getColorButtons()
      }]
    });

    o.layerLock = false;

    this.events.add("mouseover",function(e,options) {
      var target      = e.target || e.srcElement,
          targetClass = target.className || target.getAttribute("class");
      if(targetClass == "menuItem")
        target.className ? target.className = "menuItemHover" : target.setAttribute("class", "menuItemHover");
    },this.cache.MenuHolder);

    this.events.add("mouseout",function(e,options) {
      var target      = e.target || e.srcElement,
          targetClass = target.className || target.getAttribute("class");
      if(targetClass == "menuItemHover" && o.menuCache != target.getAttribute("rel")) {
        target.className ? target.className = "menuItem" : target.setAttribute("class", "menuItem");
      }
    },this.cache.MenuHolder);

    this.events.add("mouseup",function(e,options) {
      var target      = e.target || e.srcElement,
          targetClass = target.className || target.getAttribute("class");
      if(targetClass == "menuItemHover") {
        window.location.href = [o.uri,"#_", target.getAttribute("rel")].join("");
        if(o.ie)
          IEHH.changeContent(["#_", target.getAttribute("rel")].join(""));

        o.getOrder();
      }
    },this.cache.MenuHolder);

    this.getOrder();
    this.prepare();
  },

  getMenuItems  : function(menu) {
    var arr = [];
    for(var i in menu) {
      arr.push({
        tag     : "div",
        id      : ["menuItem",i].join(""),
        cls     : "menuItem box",
        rel     : i,
        html    : menu[i]
      });
    }
    return arr;
  },

  doJob         : function(order) {
    var menu          = ["rozitiv","collection","order","contact"],
        pageIndex     = menu.indexOf(order),
        thisHeight    = 0,
        current       = this.getDom(["div[id=",menu[pageIndex],"_page]"].join("")),
        currentHeight = parseInt(this.getCssProperty(current, "height"),10),
        el;
    
    for(var i = 0, len = menu.length; i < len; i++) {
      if(i < pageIndex) {
        el = this.getDom(["div[id=",menu[i],"_page]"].join(""));
        thisHeight += parseInt(this.getCssProperty(el, "height"),10); 
      } else {
        break;
      }
    }
    
    this.anim([
      "pages",
      ["top:",-thisHeight,"px"].join(""),
      {duration: 400},
      function() {
        /*o.anim([
          "c",
          ["height:",currentHeight,"px"].join(""),
          {duration: 400},
          function() {}
        ]);*/
      }
    ]);
    
    this.anim([
      "c",
      ["height:",currentHeight,"px"].join(""),
      {duration: 400},
      function() {}
    ]);
  },

  getOrder      : function() {
    if(window.location.href.match(/(#)/)) {
      var order     = window.location.href.match(/(#_)(.*)/)[2],
          menuitems = o.cache.MenuHolder.childNodes,
          target;

      if(o.menuCache != order) {
        for(var i in menuitems) {
          if(typeof menuitems[i] == "object") {
            if(menuitems[i].getAttribute("rel") == order)
              if(menuitems[i].className)
                menuitems[i].className = "menuItemHover";
              else
                menuitems[i].setAttribute("class", "menuItemHover");
            else
              if(menuitems[i].className)
                menuitems[i].className = "menuItem";
              else
                menuitems[i].setAttribute("class", "menuItem");
          }
        }
        document.title  = order;
        o.menuCache     = order;
        o.doJob(order);
      }
    } else {
      var order             = "rozitiv";
      window.location.href  = [o.uri,"#_",order].join("");
    }
  },

  /*
    simple wrapper for the css effect library of Thomas Fuchs's emile
  */
  anim        : function(cfg) {
    var el, css, dur, callback;
    this.cache[cfg[0]] ? el = this.cache[cfg[0]] : el = o.getDom(["div[id=",cfg[0],"]"].join(""));
    css = cfg[1];
    cfg[2].duration ? dur = cfg[2] : dur = {duration: 800};
    typeof cfg[3] != "undefined" ? callback = cfg[3] : callback = "";
    emile(el, css, dur, callback);
  },
  
  getParent: function(el,type,prop,name,stopCondition) {
  
    var self 					= this,
        stopCondition = stopCondition || 10,
        current 			= el;
    
    if(typeof self.getAttrib == 'undefined')
    	self.getAttrib = function(el, prop) {
    		if(prop == 'class')
  				return (el.className ? el.className : el.getAttribute(prop))
  			else
  				return el.getAttribute(prop)
    	};
    
    if(!type) return el.parentNode;
    
    for(var i = 0; i < stopCondition; i++) {
      if(current.tagName != 'HTML') {
	      if(!prop && type == current.tagName) {
	      	return current
		  	} else if(typeof self.getAttrib(current,prop) != 'undefined') {
	        if(self.getAttrib(current,prop) == name) {
	      	  return current
      	 	}
  	  	}
  	  } else {
  	  	return null;
  	  }
  	  var current = current.parentNode;
    }
    return null;
  }

};

var Gallery = {
  config  : {
    imgWidth : 150
  },
  cache   : {},
  loader  : function() {
    if(typeof this.counter != "undefined")
      if(this.counter < this.len) {
        this.counter++;
      } else {
        this.loadComplete();
      }
    else
      this.counter = 2;
  },
  
  loadComplete : function() {
    
    o.createEl({
      tag   : "div",
      id    : "galleryCont",
      cls   : "galleryCont",
      style : "width:200px;height:200px;background:red;"
    },o.cache.collection_page);
    
    for(var i=0;i<this.len;i++) {
      o.createEl({
        tag     : "div",
        id      : "",
        rel     : i,
        cls     : "imageWrapper",
        style   : "",
        arr     : [{
          tag   : "img",
          cls   : "",
          src   : this.images[i],
          width : this.config.imgWidth,
          style : ""
        }],
        command : ["click", function(e) {
          e = e || window.event;
          var target = e.target || e.srcElement; //console.log(target);
          var currentTarget = o.getParent(target, 'DIV', 'class', 'imageWrapper');
          if(currentTarget != null) {
          	console.log(currentTarget.getAttribute('rel'));
          }
        }]
      },o.cache.galleryCont);  
    }
  },
  
  init    : function(images) {
      this.images           = images;
      this.len              = images.length;
      this.cache.container  = o.createEl({
      tag   : "div",
      style : "display:none"
    });
    for(var i=0;i<this.len;i++) { //console.log(images[i]);
      this.cache[["img",i].join("")] = o.createEl({
        tag       : "img",
        src       : images[i],
        command   : ["load", function(e, options) {
          Gallery.loader(); 
        }]
      },this.cache.container);
    }
  }
};

/*
  |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\   |\_____|\
  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\  |       0\
  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /  | A____  /
  |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/   |/|/ |/\/
*/

/*
  emile.js (c) 2009 Thomas Fuchs
  Licensed under the terms of the MIT license.
*/

(function(emile, container) {
  var parseEl = document.createElement('div'),
    props = ('backgroundColor borderBottomColor borderBottomWidth borderLeftColor borderLeftWidth '+
    'borderRightColor borderRightWidth borderSpacing borderTopColor borderTopWidth bottom color fontSize '+
    'fontWeight height left letterSpacing lineHeight marginBottom margelement.onmousemoveinLeft marginRight marginTop maxHeight '+
    'maxWidth minHeight minWidth opacity outlineColor outlineOffset outlineWidth paddingBottom paddingLeft '+
    'paddingRight paddingTop right textIndent top width wordSpacing zIndex').split(' ');

  function interpolate(source,target,pos) { return (source+(target-source)*pos).toFixed(3); }
  function s(str, p, c) { return str.substr(p,c||1); }
  function color(source,target,pos) {
    var i = 2, j, c, tmp, v = [], r = [];
    while(j=3,c=arguments[i-1],i--)
      if(s(c,0)=='r') { c = c.match(/\d+/g); while(j--) v.push(~~c[j]); } else {
        if(c.length==4) c='#'+s(c,1)+s(c,1)+s(c,2)+s(c,2)+s(c,3)+s(c,3);
        while(j--) v.push(parseInt(s(c,1+j*2,2), 16)); }
    while(j--) { tmp = ~~(v[j+3]+(v[j]-v[j+3])*pos); r.push(tmp<0?0:tmelement.onmousemovep>255?255:tmp); }
    return 'rgb('+r.join(',')+')';
  }

  function parse(prop) {
    var p = parseFloat(prop), q = prop.replace(/^[\-\d\.]+/,'');
    return isNaN(p) ? { v: q, f: color, u: ''} : { v: p, f: interpolate, u: q };
  }

  function normalize(style) {
    var css, rules = {}, i = props.length, v;
    parseEl.innerHTML = '<div style="'+style+'"></div>';
    css = parseEl.childNodes[0].style;
    while(i--) if(v = css[props[i]]) rules[props[i]] = parse(v);
    return rules;
  }

  container[emile] = function(el, style, opts, after) {
    el = typeof el == 'string' ? document.getElementById(el) : el;
    opts = opts || {};
    var target = normalize(style), comp = el.currentStyle ? el.currentStyle : getComputedStyle(el, null),
      prop, current = {}, start = +new Date, dur = opts.duration||200, finish = start+dur, interval,
      easing = opts.easing || function(pos) { return (-Math.cos(pos*Math.PI)/2) + 0.5; };
    for(prop in target) current[prop] = parse(comp[prop]);
    interval = setInterval(function() {
      var time = +new Date, pos = time>finish ? 1 : (time-start)/dur;
      for(prop in target)
        el.style[prop] = target[prop].f(current[prop].v,target[prop].v,easing(pos)) + target[prop].u;
      if(time>finish) { clearInterval(interval); opts.after && opts.after(); after && setTimeout(after,1); }
    },10);
  }
})('emile', this);

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
var T = new function () {
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
    T.intervalId = setInterval(this.listener, 4)
  }
  function _listener() {
    T.CASH == 9999 ? T.CASH = 0 : T.CASH++;
    var thisRemainder, thisFunction, thisObject, objLength = T.DEPO.length;
    for (var i = 0; i < objLength; i++) {
      thisRemainder = T.CASH % T.DEPO[i]["interval"];
      if (thisRemainder == 0) {
        thisFunction = T.DEPO[i]["function"];
        thisFunction()
      }
    }
  }
  function _stopListener() {
    clearInterval(T.intervalId)
  }
  function _add(thisArray) {
    if (T.DEPO.length == 0) {
      this.counter()
    }
    var listener = 0,
      objLength = T.DEPO.length;
    for (var i = 0; i < objLength; i++) {
      if (T.DEPO[i]["function"] == thisArray["function"]) {
        listener++;
        break;
      }
    }
    if (listener == 0) {
      this.DEPO[this.DEPO.length] = {
        "function": thisArray['function'],
        "interval": thisArray['interval']
      }
    }
  }
  function _del(thisFunction) {
    var objLength = T.DEPO.length - 1;
    for (var i = objLength; i >= 0; i--) {
      if (T.DEPO[i]["function"] == thisFunction) {
          T.DEPO.splice(i, 1)
      }
    }
    if (T.DEPO.length == 0) {
      T.stopListener()
    }
  }
};

window.onload = function() {
  if(navigator.appVersion.match(/MSIE/))
    o.ie = 1;

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
  document.title = "rozitiv";
  o.render(/*"scheme35"*/);
};




