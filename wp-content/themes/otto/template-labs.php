<script id="labsTemplate" type="text/template">
  <section class="lab main-section">
    <div class="lab-wrapper">
      <% _.each(posts, function(post){ %>
        <article class="project">
          <h3><%= post.title %></h3>
          <h4>septiembre de 2014</h4>
          <div class="lab-url"><a href="">URL del proyecto</a></div>

          <div class="more-info">
            <p><%= post.content %></p>
            <ul class="lab-tags">
              <li class="icon-plugin">Plugin</li>
              <li class="icon-app">App</li>
            </ul>
          </div>
        </article>
      <% }); %>
    </div>
  </section>
</script>