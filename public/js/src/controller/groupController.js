Ext.define('GroupController', {

	extend: 'Controller',
	
	//this time the relevant model is done with his job, all response data are stored in scope.data
	ajaxCallback: function(scope){
	  this.data = scope.data;
    this.view.render(this.data);
	},
	
	getData : function(){
		var self = this;
		Globals.DEPO["GroupModel"] = new GroupModel(self);
	}
	
});