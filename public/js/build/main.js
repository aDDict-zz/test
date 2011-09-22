/*
 * Maxima Javascript Engine Built on ExtJs 4.0, @author robThot, hirekmedia
 */
Ext.define("AJAX",{statics:{ajax:function(a,e,c,d,b){Ext.Ajax.request({url:a,scope:(typeof b!="undefined"?b:null),method:e,params:c,success:d})},get:function(a,c,d,b){this.ajax(a,"get",c,d,b)},post:function(a,c,d,b){this.ajax(a,"post",c,d,b)}},constructor:function(){}});Ext.define("Controller",{model:{},view:{},constructor:function(){this.getData()}});Ext.define("Model",{data:{},router:{},constructor:function(a){this.router=a;this.getAjaxData()}});Ext.define("View",{render:function(){},constructor:function(){}});Ext.define("GroupController",{extend:"Controller",ajaxCallback:function(a){var b=new GroupView();b.render(a.data)},getData:function(){var a=this;new GroupModel(a)}});Ext.define("LoginController",{extend:"Controller",ajaxCallback:function(b){var a=new LoginView();a.render(b.data)},getData:function(){var a=this;new LoginModel(a)}});Ext.define("GroupsView",{extend:"View",render:function(a){}});Ext.define("LoginView",{extend:"View",render:function(a){Ext.create("Ext.window.Window",{title:"Login",id:"loginBody",renderTo:Ext.getBody(),height:180,width:250,layout:"fit",layout:"column",items:{xtype:"form",height:145,width:237,items:a.items,url:a.action,buttons:[{text:"login",handler:function(){var b=this.up("form").getForm();b.submit({success:function(c,d){}})}}]}}).show()}});Ext.define("GroupModel",{extend:"Model",mapper:function(b){var a=this;a.data=Ext.JSON.decode(b.responseText);a.router.ajaxCallback(a)},getAjaxData:function(){var b=this;var a={elso:"ELSO",masodik:{valami:[0,1,2,3],masvalami:"SEMMISEM"}};AJAX.post("group/",["data=",Ext.JSON.encode(a)].join(""),this.mapper,b)}});Ext.define("LoginModel",{extend:"Model",mapper:function(b){var a=this;a.data=Ext.JSON.decode(b.responseText);a.router.ajaxCallback(a)},getAjaxData:function(){var a=this;AJAX.get("login/","",this.mapper,a)}});Ext.define("IEHH",{statics:{DEPO:"",init:function(){navigator.appName.match("Microsoft")!=null?this.setup():""},setup:function(){var a=document.createElement('<iframe id="thisIframe" style="display:none;" src="about:blank" />'),b=document.getElementsByTagName("body")[0];document.appendChild(a);Ext.TaskManager.start({run:IEHH.checkIframeContent,interval:1000})},changeContent:function(c){var b=document.getElementById("thisIframe"),a=b.contentWindow.document;a.open();a.write(c);a.close();IEHH.DEPO=c},checkIframeContent:function(){var c=document.getElementById("thisIframe"),d=c.contentWindow.document.body.innerHTML;if(window.location.href.match("#")&&d!=""){var b=window.location.href.split("#"),a=["#",b[1]].join("");if(a!=d){window.location.href=[b[0],d].join("")}}},constructor:function(){}}},function(){});Ext.define("$$",{statics:{orders:["login","logout","groups","demog"],order:"",frontPage:"groups",init:function(){if($$.ie){IEHH.setup()}Ext.TaskManager.start({run:$$.getOrder,interval:1000})},getOrder:function(){var a=window.location.href.match(/(.*)(#)(.*)/);if(a!=null){if(Ext.Array.indexOf($$.orders,a[3])==-1){window.location.href=[a[1],"#",$$.frontPage].join("")}else{if($$.order!=a[3]){if($$.ie){IEHH.changeContent(["#",a[3]].join(""))}$$.order=a[3];$$.doJob()}}}else{window.location.href=[window.location.href,"#",$$.frontPage].join("")}},doJob:function(){if($$.order!=""){switch($$.order){case"login":new LoginController();break;case"groups":new GroupController();break;case"demog":new DemogController();break}}},constructor:function(){}}},function(){if(navigator.appVersion.match(/MSIE/)){$$.ie=1}$$.init()});