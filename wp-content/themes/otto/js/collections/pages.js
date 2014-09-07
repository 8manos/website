OM.Collections.PageCollection = Backbone.Collection.extend({
  model: OM.Models.Page,
  url: '/wp_api/v1/posts?pagename=hola',
});
