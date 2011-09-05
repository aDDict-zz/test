
/**
 * dom manipulation, events, etc
 */
var $$ = {
  
  cache : {},

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
        default: console.log(cfg.id, cfg.tag, i);
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
  
  getDom  : function(selectorStr) {
    if(document.querySelector) {
      return document.body.querySelector(selectorStr);
    } else {   
      // working only with the (\[id\=)(.*)(\]) formula
      if(selectorStr.match(/\[.*\=.*\]/)){
        var matches = selectorStr.match(/(.*)(\[)(.*)(\=)(.*)(\])/);
        switch(matches[3]){
          case "id":
            return document.getElementById(matches[5]);
          break;
          case "class":
            var arr       = [],
                elements  = document.getElementsByTagName(matches[1]);
            for(var i = 0,len = elements.length; i < len;i++){
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
  
  /**
   *
   * stolen from extjs 4.0 
   *
   * Looks at this node and then at parent nodes for a match of the passed simple selector (e.g. div.some-class or span:first-child)
   * @param {String} selector The simple selector to test
   * @param {Number/Mixed} maxDepth (optional) The max depth to search as a number or element (defaults to 50 || document.body)
   * @param {Boolean} returnEl (optional) True to return a Ext.core.Element object instead of DOM node
   * @return {HTMLElement} The matching DOM node (or null if no match was found)
   */
  findParent : function(simpleSelector, maxDepth, returnEl) {
      var p = this.dom,
          b = document.body,
          depth = 0,
          stopEl;

      maxDepth = maxDepth || 50;
      if (isNaN(maxDepth)) {
          stopEl = Ext.getDom(maxDepth);
          maxDepth = Number.MAX_VALUE;
      }
      while (p && p.nodeType == 1 && depth < maxDepth && p != b && p != stopEl) {
          if (Ext.DomQuery.is(p, simpleSelector)) {
              return returnEl ? Ext.get(p) : p;
          }
          depth++;
          p = p.parentNode;
      }
      return null;
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
  }

};

var canvas  = {};
var snake   = {};
var manager = {
  
  init: function(){
    alert(timer.valami);
    //alert(timer.masvalami);
    timer.megvalami();
  }
};
var timer 	= new function(){
	//public
	this.valami = "DDDD";
	// private
	var masvalami  = "AAAAA"; 
	this.megvalami = function(){
	  alert(masvalami);
	}
	
};

window.onload = manager.init;

