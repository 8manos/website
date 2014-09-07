OM.Routers.Router = Backbone.Router.extend({
  /* define the route and function maps for this router */
  routes: {
    '': 'root',
  },

  root: function(){
    var homeCollection = new OM.Collections.PageCollection();
    homeCollection.fetch({
      complete: function(xhr, textStatus){
        if(textStatus == 'success'){
          window.views.page_view = new OM.Views.PageView({collection: homeCollection});
        }
      },
    });
  }
});