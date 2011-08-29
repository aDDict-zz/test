Ext.define('GroupsModel', {

	extend: 'Model',
	
	mapper: function(data){
		var self 	= this;
		self.data 	= Ext.JSON.decode(data.responseText);
		self.router.ajaxCallback(self);
	},
	
	getAjaxData: function(){
		var self = this;
		
		var datas = {
			'elso' : 'ELSO',
			'masodik' : {
				'valami' 	: [0,1,2,3],
				'masvalami' : 'SEMMISEM'
			}
		};
		
		AJAX.post(
			"group/",
			['data=',Ext.JSON.encode(datas)].join(''),
			this.mapper,
			self
		);
	}
	
});