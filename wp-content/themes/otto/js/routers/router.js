var app = app || {};

app.Router = Backbone.Router.extend({
  /* define the route and function maps for this router */
  routes: {
    '': 'root',
  },

  root: function(){
    var homeCollection = new app.PageCollection();
    homeCollection.fetch({
      complete: function(xhr, textStatus){
        if(textStatus == 'success'){
          new app.PageView({collection: homeCollection});
        }
      },
    });
  }
});
