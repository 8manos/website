$(document).on('ready', function(){

  var menuUrls = [];
  var pages = [];
  var homeUrl = $('h1 a[rel="home"]').attr('href');

  function urlPath(link) {
    //get relative url
    var linkUrlArray = link.split(homeUrl);
    var linkPath = linkUrlArray.length > 1 ? linkUrlArray[1] : linkUrlArray[0];
    return cleanUrl(linkPath);
  }

  function cleanUrl(url) {
    url = url.replace(/(^\/+|\/+$)/g,''); //trim starting and trailing slash
    if (url.length > 0) {//if not home, add trailing slash
      url += '/';
    }
    return url;
  }

  $('.main-nav .menu-item a').each(function() {
    var href = $(this).attr('href');
    var linkPath = urlPath(href);
    menuUrls.push(linkPath);
  });

  $('.main-nav .menu-item-object-page').each(function() {
    var pageClasses = $(this).attr('class');
    var pageId = pageClasses.split('page-id-')[1];
    pages.push(pageId);
  });

  $('.main-nav .menu-item a').on('click', function(e) {
    e.preventDefault();
    var href = $(this).attr('href');
    var linkPath = urlPath(href);
    Backbone.history.navigate(linkPath, {trigger: true});
  });

  window.views.main = new OM.Views.MainView();
  window.routers.router = new OM.Routers.Router({pages: pages, urls: menuUrls});
  Backbone.history.start({
    pushState: true,
  });
  //console.log(menuUrls);
  //console.log(pages);
  //console.log(homeUrl);
});