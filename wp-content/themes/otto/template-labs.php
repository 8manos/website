<script id="labTemplate" type="text/template">
  <section class="lab main-section">
    <div class="inner-header">
      <h1 class="icon-8lab"><%= title %></h1>
      <%= content_display %>
    </div>
    <div class="lab-wrapper">
    </div>
  </section>
</script>

<script id="labsTemplate" type="text/template">
  <%
  function setHttp(link) {
    if (link && link.search(/^http[s]?\:\/\//) == -1) {
      link = 'http://' + link;
    }
    return link;
  }
  %>
  <% _.each(posts, function(post){ %>
    <article class="project">
      <div class="toggle-details more">+</div>
      <h3><%= post.title %></h3>
      <h4><%= post.date.substr(0,7) %></h4>
      <ul class="lab-tags">
        <% _.each(post.taxonomies.lab_type, function(tag){ %>
          <li><span class="icon-<%= tag.slug %>"></span><%= tag.name %></li>
        <% }); %>
      </ul>

      <div class="more-info">
        <div class="lab-url"><a href="<%= setHttp(post.ext_link) %>"><%= setHttp(post.ext_link) %></a></div>
        <%= post.content_display %>
      </div>
    </article>
  <% }); %>
</script>
