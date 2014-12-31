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
      <picture>
        <source media="(min-width: 40em)" srcset="<%= post.images.thumb2x %> 1x, <%= post.images.thumb4x %> 2x">
        <source srcset="<%= post.images.thumb %> 1x, <%= post.images.thumb2x %> 2x">
        <img class="color" src="<%= post.images.thumb %>" alt="<%= post.title %>">
      </picture>
      <button type="button" class="toggle-details more color icon-close"></button>
      <h3 class="person-name"><%= post.title %></h3>
      <h4 class="person-position"><%= post.excerpt %></h4>
      <div class="person-info more-info">
        <p><%= post.content %></p>
        <ul class="person-links color">
          <% _.each(post.contact_links, function(link){ %>
            <li><a class="icon-<%= link.link_type %>" href="<%= setHttp(link.link_url) %>"><%= link.link_type %></a></li>
          <% }); %>
        </ul>
      </div>
    </article>
  <% }); %>
</script>