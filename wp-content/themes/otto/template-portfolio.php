<script id="portfolioTemplate" type="text/template">
  <section class="portfolio main-section">
    <div class="inner-header">
      <h2><%= title %></h2>
      <%= content %>
    </div>
    <div class="works-wrapper">
    </div>
  </section>
</script>

<script id="projectsTemplate" type="text/template">
  <% _.each(posts, function(post){ %>
    <article class="project">
      <div class="toggle-details more">+</div>
      <h3><%= post.title %></h3>
      <h4>septiembre de 2014</h4>
      <picture>
        <source srcset="http://lorempixel.com/300/200/abstract 1x, http://lorempixel.com/600/400/abstract 2x">
        <img src="http://lorempixel.com/300/200/abstract" alt="Website 8manos">
      </picture>
      <ul class="project-tags">
        <li class="icon-dev">Desarrollo</li>
        <li class="icon-design">Diseño</li>
      </ul>
      <div class="featuring">Otto</div>

      <div class="more-info">
        <p><%= post.content %></p>
        <div class="project-gallery">
          <div class="owl-carousel">
            <img src="http://lorempixel.com/300/200/abstract">
            <img src="http://lorempixel.com/300/200/abstract">
          </div>
        </div>
      </div>
    </article>
  <% }); %>
</script>
