OM.Views.FriendsView = Backbone.View.extend({
  el: 'main',
  initialize: function(){
    this.template = _.template($('#friendsTemplate').html());
    this.render();
  },
  render: function(){
    var posts = this.collection.models[0].attributes.posts;
    this.$el.html(this.template({'posts': posts}));
    return this;
  }
});

OM.Views.LabsView = Backbone.View.extend({
  el: 'section.lab',
  initialize: function(){
    this.template = _.template($('#labsTemplate').html());
    this.render();
  },
  render: function(){
    var posts = this.collection.models[0].attributes.posts;
    this.$el.html(this.template({'posts': posts}));
    return this;
  }
});

OM.Views.PageView = Backbone.View.extend({
  el: 'main',
  template: _.template($('#pageTemplate').html()),

  initialize: function(){
    this.render();
  },

  render: function() {
    //this.el is what we defined in tagName. use $el to get access to jQuery html() function
    //console.log(this.collection.models[0].attributes.posts[0]);
    this.$el.html(this.template(this.collection.models[0].attributes.posts[0]));

    return this;
  }
});

OM.Views.ProjectsView = Backbone.View.extend({
  el: 'main',
  initialize: function(){
    this.template = _.template($('#portfolioTemplate').html());

    this.render = _.wrap(this.render, function(render) {
      this.beforeRender();
      render.apply(this);
      this.afterRender();
    });

    this.render();
  },
  render: function(){
    var posts = this.collection.models[0].attributes.posts;
    this.$el.html(this.template({'posts': posts}));
    return this;
  },

  beforeRender: function () {
    console.log("Before render");
  },

  afterRender: function () {
    $(".owl-carousel").owlCarousel({
      items: 1,
      loop: true,
      nav: true,
      navText: ['&#60;','&#62;']
    });
  }
});

OM.Views.PersonsView = Backbone.View.extend({
  events: {
    'click .module-control.more': 'showMore',
    'click .module-control.less': 'showLess'
  },
  el: 'section.team .team-wrapper',
  initialize: function(){
    this.template = _.template($('#personsTemplate').html());
    this.render();
  },
  render: function(){
    var posts = this.collection.models[0].attributes.posts;
    this.$el.html(this.template({'posts': posts}));
    console.log('PersonsView rendered');
    return this;
  },
  showMore: function(e){
    $(e.currentTarget).removeClass('more').addClass('less');
    $(e.currentTarget).parent().find('.person-info').slideDown();
  },
  showLess: function(e){
    $(e.currentTarget).removeClass('less').addClass('more');
    $(e.currentTarget).parent().find('.person-info').slideUp();
  }
});

OM.Views.TeamView = Backbone.View.extend({
  events: {
    'click .team-nav li': 'chooseTeam'
  },
  el: 'main',
  initialize: function(){
    this.template = _.template($('#teamTemplate').html());
    this.render();
    this.chooseTeam();
  },
  render: function(){
    this.$el.html(this.template(this.collection.models[0].attributes.posts[0]));
    return this;
  },
  chooseTeam: function(e){
    var teamName = '';
    if(e){
      teamName = $(e.currentTarget).context.className;
    }
    else{
      teamName = 'team-core';
    }
    var newCollection = {};
    //newCollection changes according to teamName
    if(teamName == 'team-core'){
      newCollection = new OM.Collections.TeamCollection();
    }
    else if(teamName == 'team-nodes'){
      newCollection = new OM.Collections.TeamCollection();
    }
    else if(teamName == 'team-friends'){
      newCollection = new OM.Collections.FriendsCollection();
    }
    this.renderTeam(newCollection)
  },
  renderTeam: function(col){
    col.fetch({
      complete: function(xhr, textStatus){
        if(textStatus == 'success'){
          window.views.persons_view = new OM.Views.PersonsView({collection: col});
        }
      }
    });
  }
});
