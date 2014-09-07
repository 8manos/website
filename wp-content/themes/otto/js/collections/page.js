var app = app || {};

app.PageCollection = Backbone.Collection.extend({
  model: app.Page,
  url: '/wp_api/v1/posts?pagename=home',
});
