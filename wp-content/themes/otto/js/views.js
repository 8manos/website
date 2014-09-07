OM.Views.FriendsView = Backbone.View.extend({
  el: 'section.friends',
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

OM.Views.PrinciplesView = Backbone.View.extend({
  el: 'section.principles',
  template: _.template($('#principlesTemplate').html()),

  initialize: function(){
    this.render();
  },

  render: function() {
    //this.el is what we defined in tagName. use $el to get access to jQuery html() function
    //console.log(this.collection.models[0].attributes.posts[0]);
    this.$el.html( this.template( this.collection.models[0].attributes.posts[0] ) );

    return this;
  }
});

OM.Views.ProjectsView = Backbone.View.extend({
  el: 'section.portfolio',
  initialize: function(){
    this.template = _.template($('#projectsTemplate').html());
    this.render();
  },
  render: function(){
    var posts = this.collection.models[0].attributes.posts;
    this.$el.html(this.template({'posts': posts}));
    return this;
  }
});

OM.Views.PersonsView = Backbone.View.extend({
  el: 'section.people',
  initialize: function(){
    this.template = _.template($('#personsTemplate').html());
    this.render();
  },
  render: function(){
    var posts = this.collection.models[0].attributes.posts;
    this.$el.html(this.template({'posts': posts}));
    return this;
  }
});