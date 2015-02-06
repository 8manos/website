OM.Collections.FriendsCollection = Backbone.Collection.extend({
  model: OM.Models.Friend,
  url: '/wp_api/v1/posts?post_type=friend&orderby=menu_order&order=ASC',
});

OM.Collections.LabsCollection = Backbone.Collection.extend({
  model: OM.Models.Lab,
  url: '/wp_api/v1/posts?post_type=lab&orderby=menu_order&order=ASC',
});

OM.Collections.PagesCollection = Backbone.Collection.extend({
  initialize: function(options){
    this.url = '/wp_api/v1/posts?p=' + options.pageId;
  },
  model: OM.Models.Page,
});

OM.Collections.ProjectsCollection = Backbone.Collection.extend({
  model: OM.Models.Project,
  url: '/wp_api/v1/posts?post_type=portafolio',
});

OM.Collections.TeamCollection = Backbone.Collection.extend({
  model: OM.Models.Team,
  url: '/wp_api/v1/posts?post_type=equipo&orderby=menu_order&order=ASC',
});

OM.Collections.NodesCollection = Backbone.Collection.extend({
  model: OM.Models.Team,
  url: '/wp_api/v1/posts?post_type=colaboradores&orderby=menu_order&order=ASC',
});