Ext.define('GroupsModel', {

	extend: 'Model',
	
	data : {},
	
	router : {},
	
	mapper: function(data){ alert("asdsad");
		//this.data
	},
	
	getAjax: function(obj){
		this.router = obj;
		AJAX.post("groups", {'elso':'ELSO','masodik':{'valami':[0,1,2,3],'masvalami':'SEMMISEM'}},this.mapper);
	}
	
});