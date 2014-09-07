OM.Routers.Router = Backbone.Router.extend({
  initialize: function(options){
    this.pages = options.pages;
  },
  routes: {
    '': 'root',
    'trabajo/portafolio': 'projects',
    'equipo/nucleo': 'team_core'
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
  projects: function(){
    var projectCollection = new OM.Collections.ProjectCollection();
    projectCollection.fetch({
      complete: function(xhr, textStatus){
        if(textStatus == 'success'){
          window.views.project_view = new OM.Views.ProjectView({collection: projectCollection});
        }
      }
    });
  },
  team_core: function(){
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
