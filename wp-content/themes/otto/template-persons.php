<script id="personsTemplate" type="text/template">
  <%
  function setHttp(link) {
    if (link) {
      if (link.search(/\S+@\S+\.\S+/) > -1 && link.search(/^mailto\:/) == -1) {
        link = 'mailto:' + link;
      }
      else if (link.search(/^http[s]?\:\/\//) == -1) {
        link = 'http://' + link;
      }
    }
    return link;
  }
  %>

	<% _.each(posts, function(post){ %>
    <article class="person cerrado">
      <figure>
        <% if(!post.images.thumb){ %>
          <div class="placeholder icon-equipo color"></div>
        <% } else { %>
          <img class="color" src="<%= post.images.thumb %>" alt="<%= post.title %>">
        <% } %>
      </figure>
      <button type="button" class="toggle-details more color icon-close"></button>
      <h3 class="person-name"><%= post.title %></h3>
      <h4 class="person-position"><%= post.excerpt %></h4>
      <div class="person-info more-info">
        <p><%= post.content_display %></p>
        <ul class="person-links color">
          <% _.each(post.contact_links, function(link){ %>
            <li><a class="icon-<%= link.link_type %>" href="<%= setHttp(link.link_url) %>" target="_blank"><%= link.link_type %></a></li>
          <% }); %>
        </ul>
      </div>
    </article>
  <% }); %>
</script>