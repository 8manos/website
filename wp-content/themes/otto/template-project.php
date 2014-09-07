<script id="projectTemplate" type="text/template">
  <div class="inner-header">
    <h2><span><a href="">Work</a></span>Portafolio</h2>
    <div class="back-link">Volver</div>
  </div>
  <div class="works-wrapper">
    <% _.each(posts, function(post){ %>
      <article class="project">
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
            <div class="gallery-wrapper">
              <img src="http://lorempixel.com/300/200/abstract">
              <img src="http://lorempixel.com/300/200/abstract">
            </div>
          </div>
        </div>
      </article>
    <% }); %>
  </div>
</script>