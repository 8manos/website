OM.Views.MainView = Backbone.View.extend({
  el: 'body',
  events: {
    'click .menu-toggle': 'toggleMenu',
    'click .main-nav .menu-item a': 'toggleMenu',
    'click .footer-toggle': 'showFooter',
    'click .footer-close': 'hideFooter',
    'click .current-lang': 'toggleLangs',
    'change #contact-medium': 'changeInputType'
  },
  wrapHeight: 0,
  footerHeight: 0,
  initialize: function(){
    this.positionFooter();
    window.addEventListener('resize', this.resizeCallback);
    window.addEventListener('scroll', this.isContactVisible);
  },
  changeInputType: function(e){
    var type = $(e.currentTarget).val();
    console.log(type);

    if(type == 'email'){
      $('#contact-info').attr('type', 'email');
    } else{
      $('#contact-info').attr('type', 'phone');
    }
  },
  toggleMenu: function(e) {
    e.preventDefault();
    $('#header').toggleClass('is-collapsed');
  },
  showFooter: function(e) {
    e.preventDefault();
    if ((window.innerHeight + window.scrollY) < document.documentElement.scrollHeight) {
      $.scrollTo( 'max', 500 );
    } else {
      //scrolled to the bottom
      this.hideFooter();
    }
  },
  hideFooter: function() {
    var scrollOffset = document.documentElement.scrollHeight - window.innerHeight - $('.contact-footer').outerHeight();
    $.scrollTo( scrollOffset, 500 );
    return false;
  },
  toggleLangs: function() {
    $('.language-switch').toggleClass('is-closed');
  },
  resizeCallback: function() {
    clearTimeout(this.resizeTimer);
    this.resizeTimer = setTimeout(this.views.main.positionFooter, 100, this.views.main);
  },
  positionFooter: function(view) {
    if (arguments.length == 0) {
      view = this;
    }

    view.wrapHeight = $('.wrap').outerHeight();
    view.footerHeight = $('.contact-footer').outerHeight();
    $('.wrap').css('margin-bottom', (view.footerHeight));

    view.isContactVisible(view);
  },
  isContactVisible: function(view) {
    if (arguments.length == 0) {
      view = this.views.main;
    }

    view.wrapHeight = $('.wrap').outerHeight();

    if (window.scrollY + window.innerHeight > view.wrapHeight) {
      $('.contact-header').removeClass('is-fixed');
      $('.wrap').removeClass('is-contact-hidden');

      $('.footer-toggle').addClass('color');
      $('.footer-toggle').removeClass('color-bg');
    } else {
      $('.contact-header').addClass('is-fixed');
      $('.wrap').addClass('is-contact-hidden');

      $('.footer-toggle').removeClass('color');
      $('.footer-toggle').addClass('color-bg');
    }
  }
});

OM.Views.LabsView = Backbone.View.extend({
  el: '.lab-wrapper',
  events: {
    'click .toggle-details': 'clickToggle'
  },
  initialize: function(){
    this.template = _.template($('#labsTemplate').html());
    this.render();
  },
  render: function(){
    var posts = this.collection.models[0].attributes.posts;
    this.$el.html(this.template({'posts': posts}));
    return this;
  },
  clickToggle: function(e) {
    var $person = $(e.currentTarget).parent();
    $(e.currentTarget).toggleClass('more less');
    $person.find('.more-info').slideToggle();
  }
});

OM.Views.LabView = Backbone.View.extend({
  el: 'main',
  initialize: function(){
    this.template = _.template($('#labTemplate').html());
    this.render();
    this.renderLabs();
  },
  render: function(){
    this.$el.html(this.template(this.collection.models[0].attributes.posts[0]));
    return this;
  },
  renderLabs: function(col){
    labsCollection = new OM.Collections.LabsCollection();
    labsCollection.fetch({
      complete: function(xhr, textStatus){
        if(textStatus == 'success'){
          window.views.labs_view = new OM.Views.LabsView({collection: labsCollection});
        }
      }
    });
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
  el: '.works-wrapper',
  events: {
    'click .toggle-details': 'clickToggle'
  },
  initialize: function(){
    this.template = _.template($('#projectsTemplate').html());

    this.render = _.wrap(this.render, function(render) {
      render.apply(this);
    });

    this.render();
  },
  render: function(){
    var posts = this.collection.models[0].attributes.posts;
    this.$el.html(this.template({'posts': posts}));
    return this;
  },
  clickToggle: function(e) {
    var $project = $(e.currentTarget).parent();
    $project.find('.toggle-details').toggleClass('more less');
    $project.find('.more-info').slideToggle(function(){
      if($(this).is(':visible')){
        $project.find('.owl-carousel').owlCarousel({
          items: 1,
          loop: true,
          nav: true,
          navText: ['&#60;','&#62;']
        });
      }
    });
  }
});

OM.Views.PortfolioView = Backbone.View.extend({
  el: 'main',
  initialize: function(){
    this.template = _.template($('#portfolioTemplate').html());
    this.render();
    this.renderProjects();
  },
  render: function(){
    this.$el.html(this.template(this.collection.models[0].attributes.posts[0]));
    return this;
  },
  renderProjects: function(col){
    projectsCollection = new OM.Collections.ProjectsCollection();
    projectsCollection.fetch({
      complete: function(xhr, textStatus){
        if(textStatus == 'success'){
          window.views.projects_view = new OM.Views.ProjectsView({collection: projectsCollection});
        }
      }
    });
  }
});

OM.Views.PersonsView = Backbone.View.extend({
  events: {
    'click .toggle-details': 'clickToggle'
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
  clickToggle: function(e) {
    var $person = $(e.currentTarget).parent();
    $(e.currentTarget).toggleClass('more less');
    $person.find('.person-info').slideToggle();
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
      newCollection = new OM.Collections.NodesCollection();
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

OM.Views.GuideView = Backbone.View.extend({
  el: 'main',
  initialize: function(){
    this.template = _.template($('#guideTemplate').html());
    this.render();
  },
  render: function(){
    this.$el.html(this.template());
    return this;
  },
});