OM.Views.MainView = Backbone.View.extend({
  el: 'body',
  events: {
    'click .menu-toggle': 'toggleMenu',
    'click .main-nav .menu-item a': 'hideMenu',
    'click main a': 'hideMenu',
    'click .main-nav .menu-item:nth-child(6) a': 'contactClick',
    'click .footer-toggle': 'footerToggle',
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
    if ($(window).scrollTop() > 0) {
      $.scrollTo( 0, 500 );
      ga('send', 'event', 'button', 'click', 'menu', 'open');
    } else {
      ga('send', 'event', 'button', 'click', 'menu', 'close');
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
  contactClick: function(e) {
    e.preventDefault();
    ga('send', 'event', 'button', 'click', 'contacto');
    this.showFooter();
  },
  footerToggle: function(e) {
    e.preventDefault();
    if ((window.innerHeight + $(window).scrollTop()) < document.documentElement.scrollHeight) {
      ga('send', 'event', 'button', 'click', 'yo quiero', 'open');
      this.showFooter();
    } else {
      //scrolled to the bottom
      ga('send', 'event', 'button', 'click', 'yo quiero', 'close');
      this.hideFooter();
    }
  },
  showFooter: function() {
    $.scrollTo( 'max', 500 );
    return false;
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

    view.footerHeight = $('.contact-footer').outerHeight();
    $('.wrap').css('margin-bottom', (view.footerHeight));

    view.isContactVisible(view);
  },
  isContactVisible: function(view) {
    if (arguments.length == 0) {
      view = this.views.main;
    }

    view.footerHeight = $('.contact-footer').outerHeight();
    var isContactHeaderFixed = $('.contact-header').hasClass('is-fixed');
    var isBottomReached = document.documentElement.scrollHeight - view.footerHeight < $(window).scrollTop() + window.innerHeight;

    if (isBottomReached && isContactHeaderFixed) {
      $('.contact-header').removeClass('is-fixed');
      $('.wrap').removeClass('is-contact-hidden');

    } else if ( ! isBottomReached && ! isContactHeaderFixed ) {
      $('.contact-header').addClass('is-fixed');
      $('.wrap').addClass('is-contact-hidden');
    }
  },
  isHeaderHidden: function(view) {
    if (arguments.length == 0) {
      view = this.views.main;
    }

    var menuBarHeight = $('.menu-bar').outerHeight();
    var isMenuFixed = $('#header').hasClass('is-collapsed');
    var isHeaderHidden = window.innerHeight - menuBarHeight <= $(window).scrollTop();

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
    'click .toggle-details, .project-title': 'clickToggle'
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
    var $project = $(e.currentTarget).parent();
    $project.find('.toggle-details').toggleClass('more less');
    var $moreInfo = $project.find('.more-info');
    if ($moreInfo.is(':hidden')) {
      var title = $project.find('.project-title').text();
      ga('send', 'event', 'button', 'click', 'lab-more', title);
    }
    $moreInfo.slideToggle();
    $.scrollTo( $project, 500, {offset: {top:-50}} );
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
    'click .toggle-details, .project-title': 'clickToggle'
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

    var $moreInfo = $project.find('.more-info');
    if ($moreInfo.is(':hidden')) {
      var title = $project.find('.project-title').text();
      ga('send', 'event', 'button', 'click', 'work-more', title);
    }

    $moreInfo.slideToggle(function(){
      if($(this).is(':visible')){
        var $carousel = $project.find('.owl-carousel');
        $carousel.owlCarousel({
          items: 1,
          loop: true,
          lazyLoad:true,
          dots: false,
          nav: true,
          //navText: ['&#60;','&#62;'],
          onLoadedLazy: function(){
            $items = $carousel.find('.owl-item');
            $items.css('height', 'auto');
            var highest = 0;
            $items.each(function(){
              //console.log(this);
              highest = Math.max(highest, $(this).innerHeight());
            });
            //console.log(highest);
            $items.css('height', highest);
          }
        });
      }
    });
    $.scrollTo( $project, 500, {offset: {top:-50}} );
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
    'click .toggle-details, figure, .person-name': 'clickToggle'
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
    var $button = $person.find('.toggle-details');
    $person.toggleClass('abierto cerrado');
    $button.toggleClass('more less');

    var $personInfo = $person.find('.person-info');
    if ($personInfo.is(':hidden')) {
      var title = $person.find('.person-name').text();
      ga('send', 'event', 'button', 'click', 'team-more', title);
    }

    $personInfo.slideToggle();
    $.scrollTo( $person, 500, {offset: {top:-100}} );
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
    if(!e){
      newCollection = new OM.Collections.TeamCollection();
      $('.active-team').removeClass('active-team color-bg').css('background-color', '');
      $('.team-core').addClass('active-team color-bg');
    }
    else{
      var newCollection = {};
      //newCollection changes according to teamName
      if($(e.currentTarget).hasClass('team-core')){
        newCollection = new OM.Collections.TeamCollection();
        $('.active-team').removeClass('active-team color-bg').css('background-color', '');
        $('.team-core').addClass('active-team color-bg');
        ga('send', 'event', 'button', 'click', 'team-tab', 'nucleo');
      }
      else if($(e.currentTarget).hasClass('team-nodes')){
        newCollection = new OM.Collections.NodesCollection();
        $('.active-team').removeClass('active-team color-bg').css('background-color', '');
        $('.team-nodes').addClass('active-team color-bg');
        ga('send', 'event', 'button', 'click', 'team-tab', 'nodos');
      }
      else if($(e.currentTarget).hasClass('team-friends')){
        newCollection = new OM.Collections.FriendsCollection();
        $('.active-team').removeClass('active-team color-bg').css('background-color', '');
        $('.team-friends').addClass('active-team color-bg');
        ga('send', 'event', 'button', 'click', 'team-tab', 'amigos');
      }
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