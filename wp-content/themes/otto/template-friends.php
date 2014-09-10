<script id="friendsTemplate" type="text/template">
  <section class="friends main-section">
    <% _.each(posts, function(post){ %>
      <article class="friend">
        <h3><%= post.title %></h3>
        <h4>septiembre de 2014</h4>
        <div class="friend-url"><a href="">URL del proyecto</a></div>

        <div class="more-info">
          <p><%= post.content %></p>
          <ul class="friend-tags">
            <li class="icon-plugin">Plugin</li>
            <li class="icon-app">App</li>
          </ul>
        </div>
      </article>
    <% }); %>
  </section>
</script>