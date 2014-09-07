OM.Views.PageView = Backbone.View.extend({
  el: 'section.home',
  template: _.template( $( '#pageTemplate' ).html() ),

  initialize: function() {
    this.render();
  },

  render: function() {
    //this.el is what we defined in tagName. use $el to get access to jQuery html() function
    console.log(this.collection.models[0].attributes.posts[0]);
    this.$el.html( this.template( this.collection.models[0].attributes.posts[0] ) );

    return this;
  }
});