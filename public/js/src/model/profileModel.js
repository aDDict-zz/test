Ext.define('ProfileModel', {

  extend: 'Model',
  
  init: function() {
    
  },
  
  /*
   * @scope {Object} the relevant controller - (ProfileController)
   */
  authentication : function(scope) {
    AJAX.post(
      scope.model.data.action,
      Ext.getCmp("loginForm").getValues(),
      scope.authCallback,
      self
    );
  },
  
  mapper: function(data){
    var self  = this;
    self.data = self.toJson(data.responseText);
    self.router.ajaxCallback();
  },
  
  getAjaxData: function(){
    var self = this;
    AJAX.get(
      "login/",
      "",
      this.mapper,
      self
    );
  }
  
});