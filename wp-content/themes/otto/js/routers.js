OM.Routers.Router = Backbone.Router.extend({
  initialize: function(options){
    this.pages = options.pages;
  },
  routes: {
    '': 'root',
    'amigos': 'friends',
    'trabajo/portafolio': 'projects',
    'equipo/nucleo': 'team_core',
    'lab': 'lab'
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
  friends: function(){
    var friendsCollection = new OM.Collections.FriendsCollection();
    friendsCollection.fetch({
      complete: function(xhr, textStatus){
        if(textStatus == 'success'){
          window.views.friends_view = new OM.Views.FriendsView({collection: friendsCollection});
        }
      }
    });
  },
  lab: function(){
    var labCollection = new OM.Collections.LabsCollection();
    labCollection.fetch({
      complete: function(xhr, textStatus){
        if(textStatus == 'success'){
          window.views.labs_view = new OM.Views.LabsView({collection: labCollection});
        }
      }
    });
  },
  projects: function(){
    var projectCollection = new OM.Collections.ProjectsCollection();
    projectCollection.fetch({
      complete: function(xhr, textStatus){
        if(textStatus == 'success'){
          window.views.projects_view = new OM.Views.ProjectsView({collection: projectCollection});
        }
      }
    });
  },
  team_core: function(){
    var teamCollection = new OM.Collections.TeamCollection();
    teamCollection.fetch({
      complete: function(xhr, textStatus){
        if(textStatus == 'success'){
          window.views.persons_view = new OM.Views.PersonsView({collection: teamCollection});
        }
      }
    })
  }
});
