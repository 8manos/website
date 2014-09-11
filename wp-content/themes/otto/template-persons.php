<script id="personsTemplate" type="text/template">
  <%
  function setHttp(link) {
    if (link.search(/^http[s]?\:\/\//) == -1) {
        link = 'http://' + link;
    }
    return link;
  }
  %>

	<% _.each(posts, function(post){ %>
    <article class="person">
    	<div class="module-control more">+</div>
      <picture>
        <source media="(min-width: 40em)" srcset="http://lorempixel.com/300/300/animals 1x, http://lorempixel.com/600/600/animals 2x">
        <source srcset="http://lorempixel.com/150/150/animals 1x, http://lorempixel.com/300/300/animals 2x">
        <img src="http://lorempixel.com/150/150/animals" alt="<%= post.title %>">
      </picture>
      <h3 class="person-name"><%= post.title %></h3>
      <h4 class="person-position"><%= post.excerpt %></h4>
      <div class="person-info">
        <p><%= post.content %></p>
        <ul class="person-links">
          <% _.each(post.contact_links, function(link){ %>
            <li><a href="<%= setHttp(link.link_url) %>"><span class="icon-<%= link.link_type %>"></span><%= link.link_type %></a></li>
          <% }); %>
        </ul>
      </div>
    </article>
  <% }); %>
</script>
