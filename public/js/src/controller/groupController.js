Ext.define('GroupController', {

	extend: 'Controller',
	
	//this time the relevant model is done with his job, all response data are stored in scope.data
	ajaxCallback: function(scope){
		var groupView = new GroupView();
		groupView.render(scope.data);
	},
	
	getData : function(){
		var self = this;
		new GroupModel(self);
	}
	
});