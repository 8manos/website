OM.Routers.Router = Backbone.Router.extend({
  initialize: function(options){
    this.bind('route', this.trackPageview);
    this.pages = options.pages;
    this.route('/', 'root');
    this.route(options.urls[0], 'principles');
    this.route(options.urls[1], 'team');
    this.route(options.urls[2], 'services');
    this.route(options.urls[3], 'portfolio');
    this.route(options.urls[4], 'lab');
    this.route(options.urls[6], 'style_guide');
  },
  trackPageview: function () {
    var url = Backbone.history.getFragment();

    //prepend slash
    if (!/^\//.test(url) && url != "") {
      url = "/" + url;
    }

    ga('send', 'pageview', url);
  },
  root: function(){
    console.log('Main menu');
  },
  principles: function(){
    var principlesCollection = new OM.Collections.PagesCollection({pageId: this.pages[0]});
    principlesCollection.fetch({
      complete: function(xhr, textStatus){
        if(textStatus == 'success'){
          window.views.principles_view = new OM.Views.PageView({collection: principlesCollection});
          document.title = '∞manos | '+principlesCollection.models[0].attributes.posts[0].title;
        }
      },
    });
  },
  team: function(){
    var teamCollection = new OM.Collections.PagesCollection({pageId: this.pages[1]});
    teamCollection.fetch({
      complete: function(xhr, textStatus){
        if(textStatus == 'success'){
          window.views.team_view = new OM.Views.TeamView({collection: teamCollection});
          document.title = '∞manos | '+teamCollection.models[0].attributes.posts[0].title;
        }
      }
    });
  },
  services: function(){
    var servicesCollection = new OM.Collections.PagesCollection({pageId: this.pages[2]});
    servicesCollection.fetch({
      complete: function(xhr, textStatus){
        if(textStatus == 'success'){
          window.views.services_view = new OM.Views.PageView({collection: servicesCollection});
          document.title = '∞manos | '+servicesCollection.models[0].attributes.posts[0].title;
        }
      },
    });
  },
  portfolio: function(){
    var portfolioCollection = new OM.Collections.PagesCollection({pageId: this.pages[3]});
    portfolioCollection.fetch({
      complete: function(xhr, textStatus){
        if(textStatus == 'success'){
          window.views.portfolio_view = new OM.Views.PortfolioView({collection: portfolioCollection});
          document.title = '∞manos | '+portfolioCollection.models[0].attributes.posts[0].title;
        }
      }
    });
  },
  lab: function(){
    var labCollection = new OM.Collections.PagesCollection({pageId: this.pages[4]});
    labCollection.fetch({
      complete: function(xhr, textStatus){
        if(textStatus == 'success'){
          window.views.labs_view = new OM.Views.LabView({collection: labCollection});
          document.title = '∞manos | '+labCollection.models[0].attributes.posts[0].title;
        }
      }
    });
  },
  style_guide: function(){
    window.views.guide_view = new OM.Views.GuideView();
  }
});