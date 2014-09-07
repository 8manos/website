$(document).on('ready', function(){
  window.routers.router = new OM.Routers.Router();
  Backbone.history.start();
});