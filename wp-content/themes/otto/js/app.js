$(document).on('ready', function(){
  var firstPageClasses = $('#menu-main .menu-item-object-page').eq(0).attr('class');
  var firstPageId = firstPageClasses.split('page-id-')[1];
  var pages = []
  pages[0] = firstPageId;

  window.routers.router = new OM.Routers.Router({pages: pages});
  Backbone.history.start({
  	root: '/',
  	//pushState: true
  });
});