OM.Routers.Router = Backbone.Router.extend({
  initialize: function(options){
    this.pages = options.pages;
  },
  routes: {
    '': 'root',
    'equipo': 'team'
  },
  root: function(){
    var homeCollection = new OM.Collections.PageCollection({pageId: this.pages[0]});
    homeCollection.fetch({
      complete: function(xhr, textStatus){
        if(textStatus == 'success'){
          window.views.page_view = new OM.Views.PageView({collection: homeCollection});
        }
      },
    });
  },
  team: function(){
    var teamCollection = new OM.Collections.TeamCollection();
    teamCollection.fetch({
      complete: function(xhr, textStatus){
        if(textStatus == 'success'){
          window.views.person_view = new OM.Views.PersonView({collection: teamCollection});
        }
      }
    })
  }
});
