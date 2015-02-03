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

		<!--demo texto especifico para pagina de principios--> 
		<p class="close-search color"> ¿Te sientes identificado? <br> <span>Unete a nuestro equipo. :-)</span></p>
		
		<!--demo texto especifico para pagina de servicios-->
		<!--
		<p class="close-search color"> ¿Eres un cliente chévere? <br> <span>Déjanos tus datos para contatarte. :-)</span></p>
		-->
		
	</section>
</script>