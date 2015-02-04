<script id="portfolioTemplate" type="text/template">
  <section class="portfolio main-section">
    <div class="inner-header">
      <div class="section-name">
        <div class="icon-trabajo color-bg"></div>
        <h1 class="color"><%= title %></h1>
        <!--<button type="button" class="color icon-close"></button>-->
      </div>
    </div>

      <div class="inner-main section-desc">
        <%= content_display %>
        <h2>Amor por la tecnología + habilidades técnicas</h2>
        <p>Explora nuestro portafolio de trabajos. Si no te gustan, presiona (Cmd + W).</p>
        <div class="sect-conventions color">
          <h3>Convenciones:</h3>
          <ul class="conventions">
            <li class="convention icon-target">Identidad</li>
            <li class="convention icon-tools">Diseño UX/UI</li>
            <li class="convention icon-gear">Desarrollo</li>
          </ul>
        </div>
      </div>

    <div class="works-wrapper">
    </div>
  </section>
</script>

<script id="projectsTemplate" type="text/template">
  <%
  function setHttp(link) {
    if (link && link.search(/^http[s]?\:\/\//) == -1) {
      link = 'http://' + link;
    }
    return link;
  }
  %>

  <% _.each(posts, function(post){ %>
    <article class="project off">
      <button type="button" class="toggle-details more color icon-close"></button>
      <h3 class="project-title"><%= post.title %></h3>
      <ul class="project-tags">
        <% _.each(post.taxonomies.post_tag, function(tag){ %>
          <li class="icon-<%= tag.slug %>"></li>
        <% }); %>
      </ul>
      <figure>
        <img src="<%= post.images.project %>" srcset="<%= post.images.projectth %> 300w, <%= post.images.projectth2x %> 600w, <%= post.images.projectth3x %> 900w" sizes="90vw, (min-width: 45em) 45vw, (min-width: 90em) 30vw" alt="<%= post.title %>">
      </figure>

      <div class="more-info">
        <div class="project-info">
          <p class="data">Cliente: <strong><%= post.title %></strong></p>
          <p class="data">Co-workers: <strong><%= post.featuring %></strong></p>
          <p class="data">Año: <strong><%= post.date.substr(0,4) %></strong></p>
          <div class="project-desc">
            <%= post.content_display %>
          </div>
        </div>
        <h4 class="project-gallery-title">Galería de imágenes</h4>
        <div class="project-gallery">
          <div id="carousel-<%= post.name %>" class="owl-carousel">
            <% _.each(post.gallery, function(slide){ %>
              <img class="owl-lazy" data-src="<%= slide.medium[0] %>">
            <% }); %>
          </div>
        </div>
        <p class="project-link"><a href="<%= setHttp(post.ext_link) %>" target="_blank">Visita <%= post.title %></a></p>
      </div>
    </article>
  <% }); %>
  <p class="close-search color"> <b>¿Te gusta lo que ves?</b> <br> <span>Invitanos a conocer tu proyecto contactanos :-)</span></p>
</script>