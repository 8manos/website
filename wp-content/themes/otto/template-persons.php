<script id="personsTemplate" type="text/template">
  <div class="inner-header">
    <h2><span><a href="">People</a></span>Equipo</h2>
    <div class="back-link">Volver</div>
  </div>
  <div class="team-wrapper">
    <% _.each(posts, function(post){ %>
      <article class="person">
        <h3><%= post.title %></h3>
        <h4>Líder de agilidad</h4>
        <picture>
          <source media="(min-width: 40em)" srcset="http://lorempixel.com/300/300/animals 1x, http://lorempixel.com/600/600/animals 2x">
          <source srcset="http://lorempixel.com/150/150/animals 1x, http://lorempixel.com/300/300/animals 2x">
          <img src="http://lorempixel.com/150/150/animals" alt="Otto Manotas">
        </picture>
        <div class="more-info">
          <p><%= post.content %></p>
          <ul class="person-links">
            <li class="icon-twitter"><a href="">Twitter</a></li>
            <li class="icon-github"><a href="">Github</a></li>
          </ul>
        </div>
      </article>
    <% }); %>
  </div>
</script>