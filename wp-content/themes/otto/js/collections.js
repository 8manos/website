OM.Collections.PageCollection = Backbone.Collection.extend({
  initialize: function(options){
    this.url = '/wp_api/v1/posts?p='+options.pageId;
  },
  model: OM.Models.Page,
});

OM.Collections.ProjectCollection = Backbone.Collection.extend({
  model: OM.Models.Project,
  url: '/wp_api/v1/posts?post_type=portafolio',
});

OM.Collections.TeamCollection = Backbone.Collection.extend({
  model: OM.Models.Team,
  url: '/wp_api/v1/posts?post_type=equipo',
});