<script id="pageTemplate" type="text/template">
	<section class="<%= name %> main-section">
		<div class="inner-header">
			<div class="section-name">
	        	<div class="icon-<%= name %> color-bg"></div>
	        	<h1 class="color"><%= title %></h1>
	        	<!--<button type="button" class="color icon-close"></button>-->
	      	</div>
		</div>
		<div class="inner-main">
			<%= content_display %>
		</div>
	</section>
</script>