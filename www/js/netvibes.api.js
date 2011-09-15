App.Modules.Api = function(obj)
{

	var data = obj.dataObj.data;
	
	var moduleTitle		 = obj.elm_title;
	var moduleContent	 = obj.elm_moduleContent;
	var editContent		 = obj.elm_editContent;
  
  var NV_XML_REQUEST_URL = NV_PATH + 'xmlProxy.php';
  var NV_API_REQUEST_URL = NV_PATH + 'apiProxy.php';
  var NV_AJAX_REQUEST_URL = NV_PATH + 'ajaxProxy.php';
  
  var NV_KB_ENTER = false;
  var NV_KB_ACTION = false;
  
  var NV_MODULE = obj;
  var NV_MODULE_ID = obj.dataObj.id;

  var configureContent = false;

	this.edit = function()
	{
	
		editContent.style.padding = "0";

    if(configureContent) {

      new Insertion.Bottom(editContent, configureContent);
      
      var form = editContent.getElementsByTagName('form')[0];

      var formElements = Form.getElements(form);
      for (var j = 0; j < formElements.length; j++) {
        if(formElements[j].type != "submit") {
			    n = formElements[j].name||formElements[j].id;
			    if (!n) {
				    continue;
			    }
          var inputValue = data[n];
          if(inputValue && inputValue != 'undefined' && inputValue != 'NULL') {
            switch(formElements[j].type) {
              case 'checkbox':
                if(inputValue == 'on') formElements[j].checked = true;
                break;
              case 'radio':
                if(inputValue == formElements[j].value) formElements[j].checked = true;
                break;
              default :
                formElements[j].value = inputValue;
                break;
            }
          }
        }
      }

      form.onsubmit = function() 
      {

        var formElements = Form.getElements(this);
        for (var j=0; j<formElements.length; j++) {
          if(formElements[j].type != "submit") {
            switch(formElements[j].type) {
              case 'checkbox':
                if($F(formElements[j]) == 'on')
                  data[formElements[j].name] = 'on';
                else
                  data[formElements[j].name] = 'off';
                break;
              case 'radio':
                if(formElements[j].checked)
                  data[formElements[j].name] = formElements[j].value;
                break;
              default :
                data[formElements[j].name] = formElements[j].value;
                break;
            }
          }
        }
        
        obj.save();

        function endEdit()
        {
          fetchModule();
          obj.endEditMode();
        }

        window.setTimeout(endEdit, 500);

        return false;
        
      }

    } else {

      var editUrlContent = document.createElement("div");

      editUrlContent.innerHTML =
        '<div class="optionContent">' +
        '<form><table width="100%" cellpadding="0" cellspacing="0" class="formTable">'+
          '<tr>' +
            '<td><b>URL :</b></td>' + 
            '<td><input name="url" type="text" class="inputClean" maxlength="255" value="' + ((data.moduleUrl) ? data.moduleUrl : '') + '" style="width:75%" /> <input style="vertical-align:20%" type="button" class="buttonClean" value="Go" onclick="" /></td>' +
          '</tr>' +
        '</table></form>'+
        '</div>';
      
      editContent.appendChild(editUrlContent);

      var form = editContent.getElementsByTagName('form')[0];
      var inputs = editContent.getElementsByTagName('input');
      var submitInput = inputs[1];
      var urlInput = inputs[0];

      editFormSubmit = function()
      {
        data.moduleUrl = urlInput.value;
        obj.save();
        fetchModule();
        return false;
      }

      form.onsubmit = editFormSubmit;
      submitInput.onclick = editFormSubmit;

    }

	}

	function fetchModule()
	{
		
		if(obj.dataObj.id) { var params = "moduleId=" + obj.dataObj.id; }
		if(data.moduleUrl) {
      params += '&moduleUrl=' + escape(data.moduleUrl);
      params += '&nocache='+ Math.random();
    }
    
		//moduleContent.style.height= moduleContent.offsetHeight + 'px';
		//moduleContent.innerHTML = 'Loading ...';

		new Ajax.Request(
			NV_API_REQUEST_URL,
			{ method: 'get', parameters: params, onSuccess: displayModule, onFailure: ajaxFailure }
		);

	}

  this.refresh = function()
  {
    window.setTimeout(NV_REFRESH, 10);
  }

  var NV_REFRESH = function()
  {
    fetchModule();
  }

	function displayModule(xhr)
	{
	
		var html = xhr.responseXML;

	    var body = html.getElementsByTagName('body')[0];
    
	    if (!body) {
			var errmsg = "Problem while parsing the module. Not valid XML.";
			if (Browser.isIE) { 
				var xmldoc;
				try { xmldoc = new ActiveXObject("MSXML2.XmlDom"); } catch (e) {}
				if (!xmldoc) try { xmldoc = new ActiveXObject("Microsoft.XmlDom"); } catch (e) {}
				if (!xmldoc) try { xmldoc = new ActiveXObject("MSXML.XmlDom"); } catch (e) {}
				if (!xmldoc) try { xmldoc = new ActiveXObject("MSXML3.XmlDom"); } catch (e) {}
				xmldoc.validateOnParse = false;
				if (xmldoc.loadXML(xhr.responseText)) { // recovery seems possible
					body = xmldoc.getElementsByTagName('body')[0];
				} else {
					errmsg = "An error occurred in the XML:  " + xmldoc.parseError.reason + "(" + xmldoc.parseError.line + ":" + xmldoc.parseError.linepos + ")<br /><tt>" + xmldoc.parseError.srcText.replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/\n/g, "<br />") + "</tt>";
				}
				
			}
		}

	    if (!body) {
			moduleContent.innerHTML = '<div class="apiContent"><p>' + errmsg + '</p></div>';
			return false;
		}
    
    // title handling
		
    var title = html.getElementsByTagName('title')[0];
    moduleTitle.innerHTML = title.firstChild.nodeValue;
    
    obj.dataObj.title = title.firstChild.nodeValue;

    // content handling
		
		if(body.innerHTML) { 
		  var content = body.innerHTML.stripScripts(); // mozilla
		} else {
		  var content = body.xml; // ie (must wrap the body container)
		}
		moduleContent.setAttribute('id', 'm' + obj.dataObj.id);
		moduleContent.style.height= 'auto';
		moduleContent.style.background= 'none';
		moduleContent.innerHTML = '<div class="apiContent">' + content + '</div>';
    
    // bindings
    var NV_CONTENT = moduleContent.firstChild;
    var NV_TITLE = moduleTitle;
	
    // favicon handling
		var links = html.getElementsByTagName('link');
		for(var i=0;i<links.length;i++) {
			if(links[i].getAttribute('rel') == 'icon') {
				var favicon = links[i].getAttribute('href');
			}
		}
		if(favicon) {
			var buildHref = buildUrl(data.moduleUrl, favicon);
			if(buildHref) { favicon = buildHref }
			obj.elm_ico.innerHTML = '<img width="16" height="16" src="'+favicon+'" />';
		} else {
			obj.elm_ico.innerHTML = '<img width="16" height="16" src="http://www.netvibes.com/img/netvibes.gif" />';
		}
		
		// links handling
		var links = moduleContent.getElementsByTagName('a');
		for (var i=0; i<links.length; i++) {
		  if(!links[i].onclick) {
        links[i].onclick = function()
        {
          var linkHref = this.href.replace('http://' + NV_HOST + NV_PATH, '');
          var buildHref = buildUrl(data.moduleUrl, linkHref);
          if(buildHref) { // internal link
            data.moduleUrl = buildHref;
            obj.save();
            fetchModule();
          } else { // external link
            window.open(this.href);
          }
          return false;
        }
		  }
		}

    // image handling
		var images = moduleContent.getElementsByTagName('img');
		for (var i=0; i<images.length; i++) {
      var buildHref = buildUrl(data.moduleUrl, images[i].getAttribute('src'));
      if(buildHref) { images[i].setAttribute('src', buildHref); }
    }

		// form handling
		var forms = moduleContent.getElementsByTagName('form');
		for (var i=0; i<forms.length; i++) {
	        var formElements = Form.getElements(forms[i]);
			for (var j = 0; j < formElements.length; j++) {
				if(formElements[j].type != "button" && formElements[j].type != "submit" && formElements[j].type != "hidden") {
					if (formElements[j].onfocus) formElements[j].oldOnfocus = formElements[j].onfocus;
					formElements[j].onfocus = function(e) {
						App.keyboardFocus = "module.edit";
						if (this.oldOnfocus) this.oldOnfocus(e);
					};
					if (formElements[j].onblur) formElements[j].oldOnblur = formElements[j].onblur;
					formElements[j].onblur  = function(e) {
						App.keyboardFocus = "module";
						if (this.oldOnblur) this.oldOnblur(e);
					};
				}
			}


      // new behavior
      if(forms[i].className == 'configuration') {
        
        configureContent =
          '<div class="optionContent configureContent"><form>' +
            forms[i].innerHTML +
          '</form></div>';
        
        // forms[i].parentNode.removeChild(forms[i]);
        // removeChild doesn't work and produce a javascript error so ...
        forms[i].style.display = 'none';

      // old behavior
      } else if(forms[i].className == 'configure') {

        var formElements = Form.getElements(forms[i]);
				for (var j = 0; j < formElements.length; j++) {
					if(formElements[j].type != "submit") {
						n = formElements[j].name||formElements[j].id;
						if (!n) {
							continue;
						}
						var inputValue = data[n];
						if(inputValue && inputValue != 'undefined')
							formElements[j].value = inputValue;
					}
				}

				forms[i].onsubmit = function()
				{
          var formElements = Form.getElements(this);
					for (var j=0; j<formElements.length; j++) {
						if(formElements[j].type != "submit")
							n = formElements[j].name||formElements[j].id;
							if (!n) {
								continue;
							}
							data[n] = formElements[j].value;
					}
					var buildHref = buildUrl(data.moduleUrl, this.getAttribute('action'));
					if(buildHref) {
						data.moduleUrl = buildHref;
            window.setTimeout(fetchModule, 500);
					}
          obj.save();
          return false;
				}
      
      // classic form
			} else {

        forms[i].setAttribute('target', "_blank"); // to open classic forms in new window
				
        if(!forms[i].onsubmit) {
          forms[i].onsubmit = function()
          {
            var params = Form.serialize(this);
            if(obj.dataObj.id) { params += "&moduleId="+obj.dataObj.id; } 
            if(this.getAttribute('method')) {
              var method = this.getAttribute('method');
            } else {
              var method = 'get';
            }
            if(this.getAttribute('action')) {
              var buildHref = buildUrl(data.moduleUrl, this.getAttribute('action'));
              if(buildHref) {
                params += "&moduleUrl="+buildHref;
                new Ajax.Request(
                  NV_API_REQUEST_URL,
                  {method: method, parameters: params, onSuccess: displayModule, onFailure: ajaxFailure}
                );
                return false;
              }
            }
          }
        }

			} // end if form.className != configure

		} // for forms
    
    obj.refreshMode = true; // activing refresh into module
    
    // CSS handling
    var cssId = 'css' + obj.dataObj.id;
    var style = html.getElementsByTagName('style');
    var cssContent = '';
    for (var i=0; i<style.length; i++) {
		for(var j=0; j<style[i].childNodes.length; j++){
	      cssContent += style[i].childNodes[j].nodeValue;
		}
    }
    if(!$(cssId)) {
      var head = document.getElementsByTagName('head').item(0);
      var css = document.createElement("style");
      css.setAttribute('id', cssId);
      css.setAttribute('type','text/css');
      head.appendChild(css);
    }
    if(cssContent.length > 0) {
      var namespace = '#m' + obj.dataObj.id;
      cssContent = cssContent.replace(/#moduleContent/g, '');
      cssContent = cssContent.replace(/\n\s*([a-zA-z0-9\.\-, :#]*)\s*([{|,])/g, "\n" + namespace + " $1$2");
		if($(cssId).styleSheet){// IE
			$(cssId).styleSheet.cssText = cssContent;
		} else {// w3c
			$(cssId).appendChild(document.createTextNode(cssContent));
		}      
    }

    // Javascript scripts handling
    NV_ONLOAD = null;
    var scripts = html.getElementsByTagName('script');
    for (var i=0; i<scripts.length; i++) {
      var src = scripts[i].getAttribute('src');
      if(!scripts[i].getAttribute('src')) { // if internal script (src not defined)
        eval(scripts[i].firstChild.nodeValue);
      }
    }
    if(NV_ONLOAD) {
      window.setTimeout(NV_ONLOAD, 100);  // not too fast !
    }

    obj.save();

	}
	function checkPath() {
		if (arguments[1].substr(0, 7) == "http://" || arguments[1].substr(0, 8) == "https://") {
			if (arguments[1].toLowerCase().substr(-13, -1) != "netvibes.com") {
				return arguments[0];
			}
			all = arguments[0];
			path = arguments[4];
			ext = arguments[5];
		} else {
			all = arguments[0];
			path = arguments[2];
			ext = arguments[3];
		}
		switch(ext) {
			case "gif": case "png": case "jpg": // allow images
			case "css": // allow css
				return all;
		}
		if (ext == "php") { // only allow these php files
			switch (path) {
				case "xmlProxy":
				case "apiProxy":
				case "feedProxy":
					return all;
			}
		} else if (ext == "js") {
			switch (path) {
				case "api/0.3/behavior":
				case "api/0.3/emulation":
					return all;
			}
		}
		return "http://www.netvibes.com/empty.txt";
	}
	function setValue(name, value)
	{
    data[name] = value;
		obj.save();
		return value;
	}
	
	function saveValue(name, value)
	{
		return setValue(name, value);
	}

	function getValue(name)
	{
    return data[name];
	}

  function setToolTip(element, text, width)
	{
    new App.toolTip(element, text, width, "left");
	}

  this.onKeyboardEnter = function()
  {
    if(NV_KB_ENTER) {
      NV_KB_ENTER()
    }
    App.keyboardFocus = "module.action";
	}
  
  this.onKeyboardAction = function(keyCode)
  {
    if(NV_KB_ACTION) {
      NV_KB_ACTION(keyCode);
    } 
	}

	function ajaxFailure(xhr)
	{
		moduleTitle.innerHTML = _("Mini API module") + ' : Error';
		moduleContent.innerHTML = 'Error while retrieving the module.<br />' . xhr.responseText;
	}
	
	function buildUrl(moduleUrl, linkHref)
	{
		var first_split = moduleUrl.split("://");
		var scheme =  first_split[0]
		var without_resource = first_split[1];
		var second_split = without_resource.split("/");
		var domain = second_split[0];
		var path = '';
		for (i=1;i<second_split.length-1;i++) {
			path += '/' + second_split[i];
		}
		
		if(linkHref.split("://").length>1) {
			var type = 'complete';
			return false;
		} else if(linkHref.substring(0, 1) == '/') {
			var type = 'absolute';
			return scheme +'://' + domain + linkHref;
		} else {
			var type = 'relative';
			return scheme +'://' + domain + path + '/' + linkHref;
		}
	}

  function ajaxRequest(url, parameters)
  {
    var myAjax = new Ajax.Request( NV_PATH + 'ajaxProxy.php?url=' + escape(url), parameters);
  }
	
	if (data.moduleUrl) {
    fetchModule();
  } else {
		moduleTitle.innerHTML = _("Mini API module");
		moduleContent.innerHTML =
			'<table><tr><td>' +
				_("Configure this module by editing and filling the URL of a third party module created with the Netvibes Mini API.") + "<br /><br />" +
				_("Netvibes can not be held responsible for content, functionality, availability or performance of this module. If you have any concerns please report to the Netvibes ecosystem.") +
			'</td></tr></table>';
	}

	obj.onLoadModule();

}
var ApiAjax = {
	Request: function(url, options) {
		if (/^(http:\/\/(www\.)?netvibes\.com)?\/?(xml|ajax|feed)Proxy/.test(url)) {
			return new Ajax.Request(url, options);
		}
	}
}