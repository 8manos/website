$(document).on('ready', function(){
  var menuUrls = [];
  var homeUrl = $('h1 a[rel="home"]').attr('href');
  $('#menu-main .menu-item a').each(function() {
    var linkUrl = $(this).attr('href');
    var linkUrlArray = linkUrl.split(homeUrl);
    var linkPath = linkUrlArray.length > 1 ? linkUrlArray[1] : linkUrlArray[0];
    linkPath = linkPath.replace(/(^\/+|\/+$)/g,''); //trim starting and final slash
    menuUrls.push(linkPath);
  });

  var pages = [];
  $('#menu-main .menu-item-object-page').each(function() {
    var pageClasses = $(this).attr('class');
    var pageId = pageClasses.split('page-id-')[1];
    pages.push(pageId);
  });

  window.routers.router = new OM.Routers.Router({pages: pages, urls: menuUrls});
  Backbone.history.start({
    pushState: true,
  });
});
