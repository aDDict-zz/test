Ext.define('GroupModel', {

	extend: 'Model',
	
	init: function() {
    //this.getAjaxData();
  },
	
	mapper: function(data){
		
		var self 	= this;
		// store the data
		
		self.data 	= self.toJson(data.responseText);
		// call the callback method of the relevant controller
		self.router.ajaxCallback(self);
	},
	
	getAjaxData: function(){
		var self = this;
		AJAX.post(
			"group/",
			"", //['data=',Ext.JSON.encode(datas)].join(''),
			this.mapper,
			self
		);
	},
	
	getGroups: function(scope) {
	  //console.log(Globals.profile.model.data.user);
	  AJAX.post(
      "group/",
      "", //['data=',Ext.JSON.encode(datas)].join(''),
      scope.groupMapper,
      scope
    );
	}
	
});