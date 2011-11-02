Ext.define("AJAX",{statics:{ajax:function(a,f,d,e,b,c){Ext.Ajax.request({url:a,scope:(typeof b!="undefined"?b:null),form:(typeof c!="undefined"?c:null),method:f,params:d,success:e})},get:function(a,d,e,b,c){this.ajax(a,"get",d,e,b,c)},post:function(a,d,e,b,c){this.ajax(a,"post",d,e,b,c)}},constructor:function(){}});Ext.define("Message",{statics:{alert:function(b,a,c){Ext.Msg.alert(b,a,function(d){if(d=="ok"){c()}})}},constructor:function(){}});Ext.define("Globals",{statics:{DEPO:{}},constructor:function(){}});Ext.define("Controller",{model:{},view:{},data:{},nameSpace:"",fullNameSpace:"",showView:true,getNameSpace:function(){var a=this.$className.match(/(.*)(Controller)/);this.nameSpace=a[1]},getFullNameSpace:function(){var c="",b=Router.routeOrders;if(b.length>0){for(var d=0,a=b.length;d<a;d++){c+=b[d]}}else{c=this.nameSpace}this.fullNameSpace=c},constructor:function(){var self=this;self.getNameSpace();self.getFullNameSpace();self.model=eval(["new ",self.nameSpace,"Model()"].join(""));self.model.router=self;if(this.showView==true){self.view=eval(["new ",self.nameSpace,"View()"].join(""));self.view.scope=self}this.init()}});Ext.define("Model",{data:{},router:{},toJson:function(a){return Ext.decode(a)},constructor:function(){this.init()}});Ext.define("View",{scope:{},render:function(){},constructor:function(){}});Ext.define("Debug",{statics:{parse:function(b){for(var a in b){console.log(a,b)}}}});Ext.define("IEHH",{statics:{DEPO:"",init:function(){navigator.appName.match("Microsoft")!=null?this.setup():""},setup:function(){var a=document.createElement('<iframe id="thisIframe" style="display:none;" src="about:blank" />'),b=document.getElementsByTagName("body")[0];document.appendChild(a);Ext.TaskManager.start({run:IEHH.checkIframeContent,interval:1000})},changeContent:function(c){var b=document.getElementById("thisIframe"),a=b.contentWindow.document;a.open();a.write(c);a.close();IEHH.DEPO=c},checkIframeContent:function(){var c=document.getElementById("thisIframe"),d=c.contentWindow.document.body.innerHTML;if(window.location.href.match("#")&&d!=""){var b=window.location.href.split("#"),a=["#",b[1]].join("");if(a!=d){window.location.href=[b[0],d].join("")}}},constructor:function(){}}},function(){});Ext.define("Router",{statics:{frontPage:"Main",login:"Login",route:"",routeOrders:[],routeParams:{},routeCache:"",lang:"",init:function(){if(Router.ie){IEHH.setup()}Ext.TaskManager.start({run:Router.getRoute,interval:2000})},getRoute:function(){var a=Router.getOrder();if(a==""){Router.setRoute(Router.frontPage)}if(Router.routeParams.lang){Router.lang=Router.routeParams.lang}else{if(Router.lang==""){Router.lang="hu"}}if(a!=null){if(Router.route!=a){if(typeof Globals.DEPO[[a,"Controller"].join("")]=="undefined"||Globals.DEPO[[a,"Controller"].join("")]==null){try{(new Function(['Globals.DEPO["',a,'Controller"] = new ',a,"Controller();"].join("")))();if(Router.ie){IEHH.changeContent(["#",a].join(""))}if(Ext.get(Router.route)!=null){Ext.get(Router.route).hide()}Router.route=a}catch(b){console.log(b);delete Globals.DEPO[a];Message.alert("Routing error","There is no implemented class in the namespace",function(){Router.setRoute(Router.frontPage)})}}else{if(Router.route!=a){if(Ext.get(Router.route)){Ext.get(Router.route).hide()}if(Ext.get(a)){Ext.get(a).show()}Globals.DEPO[[a,"Controller"].join("")].init();Router.route=a}}}}},setRoute:function(a){window.location.href=[window.location.href.split("#")[0],"#",a].join("")},getOrder:function(){if(Router.routeCache!=window.location.href){Router.routeOrders=[];Router.routeParams={};var e=(window.location.href.match(/(.#)(.*)/)?window.location.href.match(/(.#)(.*)/):null);if(e==null){Router.setRoute(Router.frontPage)}else{route=e[2];if(route.match(/\//)){var d=route.split("/"),b;for(var c=0,a=d.length;c<a;c++){if(d[c].match(/=/)){b=d[c].split("=");Router.routeParams[b[0]]=b[1]}else{Router.routeOrders.push(d[c])}}route=Router.routeOrders[0]}Router.routeCache=window.location.href;return route}}else{return Router.routeOrders[0]}},constructor:function(){}}},function(){if(navigator.appVersion.match(/MSIE/)){Router.ie=1}Router.init()});Ext.define("GroupController",{extend:"Controller",init:function(){this.getData()},ajaxCallback:function(a){this.data=a.data;this.view.render(this.data)},getData:function(){var a=this;Globals.DEPO.GroupModel=new GroupModel(a)}});Ext.define("MainController",{extend:"Controller",init:function(){if(Ext.get("Main")==null){this.getData()}},ajaxCallback:function(a){this.view.render(a.data)},main:function(){this.view.render({})},getData:function(){}});Ext.define("IddqdController",{extend:"Controller",init:function(){try{if(typeof Globals.DEPO[[this.fullNameSpace,"Controller"].join("")]=="undefined"){(new Function(['Globals.DEPO["',this.fullNameSpace,'Controller"] = new ',this.fullNameSpace,"Controller();"].join("")))()}else{Globals.DEPO[[this.fullNameSpace,"Controller"].join("")].init()}}catch(a){console.log(a);Message.alert("Routing error","There is no implemented class in the namespace",function(){Router.setRoute(Router.frontPage)})}},ajaxCallback:function(a){this.view.render(a.data)},getData:function(){}});Ext.define("IddqdTranslateController",{extend:"Controller",init:function(){},ajaxCallback:function(a){this.view.render(a.data)},getData:function(){}});Ext.define("LoginController",{extend:"Controller",init:function(){this.getData()},auth:function(){var a=Globals.DEPO.LoginController;a.model.authentication(a)},authCallback:function(b,d){var a=Globals.DEPO.LoginController,c=a.model.toJson(b.responseText);if(c.username==null){Ext.getCmp("loginForm").getForm().setValues({username:"",password:""});Ext.Msg.alert("Login failed","Try again!")}else{Ext.getCmp("LoginForm").hide();Router.setRoute(Router.frontPage)}},ajaxCallback:function(a){Globals.DEPO.LogoutController=null;this.data=a.data;if(this.data.username){Router.setRoute(Router.frontPage)}else{if(Ext.get("LoginForm")==null){this.view.render(this.data)}}},getData:function(){if(this.data.username){Router.setRoute(Router.frontPage)}}});Ext.define("LogoutController",{extend:"Controller",showView:false,init:function(){this.getData()},ajaxCallback:function(a){Globals.DEPO.LoginController=null;Router.setRoute(Router.login)},getData:function(){if(this.data.username){Router.setRoute(Router.login)}}});Ext.define("GroupView",{extend:"View",render:function(a){Ext.create("Ext.data.Store",{storeId:"groups",fields:["title","realname"],data:{items:a},proxy:{type:"memory",reader:{type:"json",root:"items"}}});Ext.create("Ext.window.Window",{title:"Csoportok:",id:"",renderTo:Ext.getBody(),resizable:true,height:600,width:420,layout:"fit",layout:"column",items:{xtype:"grid",store:Ext.data.StoreManager.lookup("groups"),columns:[{header:"Title",dataIndex:"realname"},{header:"Realname",dataIndex:"title"}],id:"",layout:"fit",height:550,width:400}}).show()}});Ext.define("LoginView",{extend:"View",render:function(b){var a=this;Ext.create("Ext.window.Window",{title:"Login",id:"LoginForm",renderTo:Ext.getBody(),resizable:false,height:180,width:250,layout:"fit",layout:"column",items:{xtype:"form",id:"loginForm",height:145,width:237,items:b.items,buttons:[{text:"login",handler:a.scope.auth}]}}).show()}});Ext.define("IddqdView",{extend:"View",render:function(a){}});Ext.define("IddqdTranslateView",{extend:"View",renderer:function(a){return["<b>",a,"</b>"].join("")},render:function(a){console.log(a)}});Ext.define("MainView",{extend:"View",render:function(b){Globals.DEPO.viewport=Ext.create("Ext.container.Viewport",{xtype:"viewport",border:0,margin:0,padding:0,style:"background: #EBEEF2;",maintainFlex:true,renderTo:Ext.getBody(),layout:{type:"fit"}});var a=Ext.create("Ext.Container",{id:"Main",xtype:"container",layout:{type:"fit"},items:b});Globals.DEPO.viewport.add(a);Globals.DEPO.viewport.doLayout()}});Ext.define("GroupModel",{extend:"Model",init:function(){this.getAjaxData()},mapper:function(b){var a=this;a.data=a.toJson(b.responseText);a.router.ajaxCallback(a)},getAjaxData:function(){var a=this;AJAX.post("group/","",this.mapper,a)}});Ext.define("MainModel",{extend:"Model",init:function(){this.getAjaxData()},mapper:function(b){var a=this;a.data=a.toJson(b.responseText);a.router.ajaxCallback(a)},getAjaxData:function(){var a=this;AJAX.get("ext-template/","",this.mapper,a)}});Ext.define("LoginModel",{extend:"Model",init:function(){this.getAjaxData()},mapper:function(b){var a=this;a.data=a.toJson(b.responseText);a.router.ajaxCallback(a)},authentication:function(a){AJAX.post(a.data.action,Ext.getCmp("loginForm").getValues(),a.authCallback,self)},getAjaxData:function(){var a=this;AJAX.get("login/","",this.mapper,a)}});Ext.define("IddqdModel",{extend:"Model",init:function(){},mapper:function(b){var a=this;a.data=a.toJson(b.responseText);a.router.ajaxCallback(a)},getAjaxData:function(){}});Ext.define("IddqdTranslateModel",{extend:"Model",init:function(){if(Ext.get("Iddqd")==null){this.getAjaxData()}this.loader=new Ext.LoadMask(Ext.getBody(),{msg:"loading"})},updateRow:function(a,c){var b=this;b.roweditor=a;b.scope=c;this.loader.show();AJAX.get("lang/update",["field=",a.field,"&id=",a.record.get("id"),"&val=",a.record.get(a.field)].join(""),function(){b.roweditor.record.commit();Globals.DEPO.IddqdTranslateController.view.Iddqd.getView().refresh();b.loader.hide()},b)},mapper:function(b){var a=this;a.data=a.toJson(b.responseText);a.router.ajaxCallback(a)},getAjaxData:function(){var a=this;AJAX.get("ext-template/translate","",this.mapper,a)}});Ext.define("LogoutModel",{extend:"Model",init:function(){this.getAjaxData()},mapper:function(b){var a=this;this.data=Ext.JSON.decode(b.responseText);this.router.ajaxCallback(this)},getAjaxData:function(){var a=this;AJAX.get("login/logout/","",this.mapper,a)}});