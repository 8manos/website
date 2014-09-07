var app = app || {};

$(function() {
  new app.Router();
  Backbone.history.start();
});