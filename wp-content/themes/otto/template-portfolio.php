<script id="portfolioTemplate" type="text/template">
  <section class="portfolio main-section">
    <div class="inner-header">
      <h2><%= title %></h2>
      <%= content_display %>
    </div>
    <div class="works-wrapper">
    </div>
  </section>
</script>

<script id="projectsTemplate" type="text/template">
  <%
  function setHttp(link) {
    if (link.search(/^http[s]?\:\/\//) == -1) {
        link = 'http://' + link;
    }
    return link;
  }

  function mediaGalleryUrl(sizes) {
    for (var i = 0; i < sizes.length; i++) {
      if (sizes[i].name == 'project') {
        return sizes[i].url;
      }
    };
    //if not found return full size
    return sizes[0].url;
  }
  %>

  <% _.each(posts, function(post){ %>
    <article class="project">
      <div class="toggle-details more">+</div>
      <h3><%= post.title %></h3>
      <h4><%= post.date.substr(0,7) %></h4>
      <picture>
        <source srcset="<%= post.images.project %> 1x, <%= post.images.project2x %> 2x">
        <img class="project-img" src="<%= post.images.project %>" alt="Website 8manos">
      </picture>
      <ul class="project-tags">
        <% _.each(post.taxonomies.post_tag, function(tag){ %>
          <li><span class="icon-<%= tag.slug %>"></span><%= tag.name %></li>
        <% }); %>
      </ul>
      <div class="featuring"><%= post.featuring %></div>

      <div class="more-info">
        <p class="project-link"><a href="<%= setHttp(post.ext_link) %>">Visita <%= post.title %></a></p>
        <%= post.content_display %>
        <div class="project-gallery">
          <div class="owl-carousel">
            <% _.each(post.media, function(media){ %>
              <img src="<%= mediaGalleryUrl(media.sizes) %>">
            <% }); %>
          </div>
        </div>
      </div>
    </article>
  <% }); %>
</script>
