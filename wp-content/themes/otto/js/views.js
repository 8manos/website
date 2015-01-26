OM.Views.MainView = Backbone.View.extend({
  el: 'body',
  events: {
    'click .menu-toggle': 'toggleMenu',
    'click .main-nav .menu-item a': 'hideMenu',
    'click .main-nav .menu-item:nth-child(6) a': 'showFooter',
    'click .footer-toggle': 'showFooter',
    'click .footer-close': 'hideFooter',
    'click .current-lang': 'toggleLangs',
    //'change #contact-medium': 'changeInputType',
    'click ul.select': 'showOptions',
    'click ul.select .option': 'selectOption',
    'customSelectChange #contact-means': 'changeInputType',
    'blur ul.select': 'customSelectBlur'
  },
  footerHeight: 0,
  initialize: function(){
    this.collapseMenu();
    this.positionFooter();
    window.addEventListener('resize', this.resizeCallback);
    window.addEventListener('scroll', this.isContactVisible);
    window.addEventListener('scroll', this.isHeaderHidden);
    $.event.trigger({
      type: 'customSelectChange'
    });
  },
  changeInputType: function(e){
    var type = $(e.currentTarget).find('.selected').html();

    if(type.indexOf('correo') > 0){
      $('#contact-field').attr({
        placeholder: 'Tu correo electrónico',
        type: 'email'
      });
    }
    else{
      $('#contact-field').attr({
        placeholder: 'Tu número celular',
        type: 'tel'
      });
    }
  },
  customSelectBlur: function(e){
    var $select = $(e.currentTarget);
    var $options = $select.find('.options-wrapper');
    $options.fadeOut();
  },
  selectOption: function(e){
    var $option = $(e.currentTarget);
    var $select = $option.parent().parent();
    var $selected = $select.find('.selected');
    $selected.html($option.html());
    $select.trigger('customSelectChange');
  },
  showOptions: function(e){
    var $select = $(e.currentTarget);
    var $options = $select.find('.options-wrapper');
    $options.fadeToggle();
  },
  toggleMenu: function(e) {
    e.preventDefault();
    if (window.scrollY) {
      $.scrollTo( 0, 500 );
    } else {
      this.hideMenu(true);
    }
  },
  hideMenu: function(animate) {
    var menuHeight = window.innerHeight - $('.menu-bar').outerHeight();

    if (typeof animate == 'object' || animate) { //if it's an event, also animate
      $.scrollTo( menuHeight, 500 );
    } else {
      $.scrollTo( menuHeight, 0 );
    }
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
  collapseMenu: function() {
    if ( $('#header').hasClass('is-collapsed') ) {
      this.hideMenu(false);
    }
  },
  positionFooter: function(view) {
    if (arguments.length == 0) {
      view = this;
    }

    //view.footerHeight = $('.contact-footer').outerHeight();
    //$('.wrap').css('margin-bottom', (view.footerHeight));

    view.isContactVisible(view);
  },
  isContactVisible: function(view) {
    if (arguments.length == 0) {
      view = this.views.main;
    }

    view.footerHeight = $('.contact-footer').outerHeight();
    var isContactHeaderFixed = $('.contact-header').hasClass('is-fixed');
    var isBottomReached = document.documentElement.scrollHeight - view.footerHeight < window.scrollY + window.innerHeight;

    if (isBottomReached && isContactHeaderFixed) {
      $('.contact-header').removeClass('is-fixed');
      $('.wrap').removeClass('is-contact-hidden');

      $('.footer-toggle').addClass('color');
      $('.footer-toggle').removeClass('color-bg');
    } else if ( ! isBottomReached && ! isContactHeaderFixed ) {
      $('.contact-header').addClass('is-fixed');
      $('.wrap').addClass('is-contact-hidden');

      $('.footer-toggle').removeClass('color');
      $('.footer-toggle').addClass('color-bg');
    }
  },
  isHeaderHidden: function(view) {
    if (arguments.length == 0) {
      view = this.views.main;
    }

    var menuBarHeight = $('.menu-bar').outerHeight();
    var isMenuFixed = $('#header').hasClass('is-collapsed');
    var isHeaderHidden = window.innerHeight - menuBarHeight <= window.scrollY;

    if (isHeaderHidden && ! isMenuFixed) {
      $('#header').addClass('is-collapsed');
    } else if ( ! isHeaderHidden && isMenuFixed ) {
      $('#header').removeClass('is-collapsed');
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
    $project.toggleClass('on off');
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
  initialize: function(){
    this.template = _.template($('#personsTemplate').html());
  },
  render: function(){
    var posts = this.collection.models[0].attributes.posts;
    this.$el.html(this.template({'posts': posts}));
    console.log('PersonsView rendered');
    return this;
  },
  clickToggle: function(e) {
    var $person = $(e.currentTarget).parent();
    $person.toggleClass('abierto cerrado');
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
      teamName = $(e.currentTarget).context.classList[0];
    }
    else{
      teamName = 'team-core';
    }

    $('.active-team').removeClass('active-team color-bg').css('background-color', '');
    $('.'+teamName).addClass('active-team color-bg');

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
  renderTeam: function(collection){
    collection.fetch({
      complete: function(xhr, textStatus){
        if(textStatus == 'success'){
          var persons_view = new OM.Views.PersonsView({collection: collection});
          $('section.team .team-wrapper').html(persons_view.render().el);
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