OM.Routers.Router = Backbone.Router.extend({
  initialize: function(options){
    this.pages = options.pages;
    this.route(options.urls[0], 'root');
    this.route(options.urls[1], 'friends');
    this.route(options.urls[2], 'projects');
    this.route(options.urls[3], 'team');
    this.route(options.urls[4], 'lab');
  },
  root: function(){
    var principlesCollection = new OM.Collections.PagesCollection({pageId: this.pages[0]});
    principlesCollection.fetch({
      complete: function(xhr, textStatus){
        if(textStatus == 'success'){
          window.views.principles_view = new OM.Views.PrinciplesView({collection: principlesCollection});
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
  team: function(){
    console.log(this.pages);
    var teamCollection = new OM.Collections.PagesCollection({pageId: this.pages[1]});
    var personsCollection = new OM.Collections.TeamCollection();
    teamCollection.fetch({
      complete: function(xhr, textStatus){
        if(textStatus == 'success'){
          window.views.team_view = new OM.Views.TeamView({collection: teamCollection});
        }
      }
    });
    personsCollection.fetch({
      complete: function(xhr, textStatus){
        if(textStatus == 'success'){
          window.views.persons_view = new OM.Views.PersonsView({collection: personsCollection});
        }
      }
    });
  }
});
