<script id="labTemplate" type="text/template">
  <section class="lab main-section">
    <div class="inner-header">
      <div class="section-name">
        <div class="icon-8lab color-bg"></div>
        <h1 class="color"><%= title %></h1>
        <!--<button type="button" class="color icon-close"></button>-->
      </div>
    </div>

    <div class="inner-main section-desc">
        <%= content_display %>
        <h2>Experimentar para aprender... y compartir.</h2>
        <p>Fomentamos el uso de tecnologías libres y de código abierto.</p>
        <p>Aquí encontrarás algunos desarrollos de libre uso:</p>
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
      <button type="button" class="toggle-details more color icon-close"></button>
      <h3 class="project-title"><%= post.title %></h3>
      <h4 class="project-subtitle"><%= post.date.substr(0,4) %></h4>
      <div class="more-info">
        <div class="project-info">
          <div class="project-desc">
            <%= post.content_display %>
          </div>
        </div>
        <div class="project-link"><a href="<%= setHttp(post.ext_link) %>" target="_blank"><%= setHttp(post.ext_link) %></a></div>
      </div>
      <ul class="lab-tags">
        <% _.each(post.taxonomies.lab_type, function(tag){ %>
          <li><span class="icon-<%= tag.slug %>"></span><%= tag.name %></li>
        <% }); %>
      </ul>
    </article>
  <% }); %>
  <p class="close-search color"> <b>¿Te gusta el FOOS?</b> <br> Únete como coolaborador. :-)</p>
</script>
