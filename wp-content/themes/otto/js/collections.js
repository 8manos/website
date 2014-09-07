OM.Collections.PageCollection = Backbone.Collection.extend({
  model: OM.Models.Page,
  url: '/wp_api/v1/posts?pagename=hola',
});

OM.Collections.TeamCollection = Backbone.Collection.extend({
  model: OM.Models.Team,
  url: '/wp_api/v1/posts?post_type=equipo',
});