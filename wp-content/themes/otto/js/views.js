OM.Views.PageView = Backbone.View.extend({
  el: 'section.home',
  template: _.template($('#pageTemplate').html()),

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

OM.Views.PersonView = Backbone.View.extend({
  el: 'section.people',
  initialize: function(){
    this.template = _.template($('#personTemplate').html());
    this.render();
  },
  render: function(){
    var posts = this.collection.models[0].attributes.posts;
    this.$el.html(this.template({'posts': posts}));
    return this;
  }
});