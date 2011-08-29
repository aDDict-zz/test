Ext.define('GroupsController', {

	extend: 'Controller',
	
	getData : function(){
		var groups = new GroupsModel(this);
	}
	
});