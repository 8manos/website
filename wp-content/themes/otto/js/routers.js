OM.Routers.Router = Backbone.Router.extend({
  initialize: function(options){
    this.pages = options.pages;
    this.route('/', 'root');
    this.route(options.urls[0], 'principles');
    this.route(options.urls[1], 'team');
    this.route(options.urls[2], 'services');
    this.route(options.urls[3], 'portfolio');
    this.route(options.urls[4], 'lab');
    this.route(options.urls[5], 'contact');
  },
  root: function(){
    console.log('Main menu');
  },
  principles: function(){
    var principlesCollection = new OM.Collections.PagesCollection({pageId: this.pages[0]});
    principlesCollection.fetch({
      complete: function(xhr, textStatus){
        if(textStatus == 'success'){
          window.views.principles_view = new OM.Views.PrinciplesView({collection: principlesCollection});
        }
      },
    });
  },
  team: function(){
    var teamCollection = new OM.Collections.PagesCollection({pageId: this.pages[1]});
    teamCollection.fetch({
      complete: function(xhr, textStatus){
        if(textStatus == 'success'){
          window.views.team_view = new OM.Views.TeamView({collection: teamCollection});
        }
      }
    });
  },
  /*friends: function(){
    var friendsCollection = new OM.Collections.FriendsCollection();
    friendsCollection.fetch({
      complete: function(xhr, textStatus){
        if(textStatus == 'success'){
          window.views.friends_view = new OM.Views.FriendsView({collection: friendsCollection});
        }
      }
    });
  },*/
  portfolio: function(){
    var projectCollection = new OM.Collections.ProjectsCollection();
    projectCollection.fetch({
      complete: function(xhr, textStatus){
        if(textStatus == 'success'){
          window.views.projects_view = new OM.Views.ProjectsView({collection: projectCollection});
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
  }
});
