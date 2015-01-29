		<div class="section-name contact-header is-fixed color-bg">
			<h1 class="footer-toggle">¡Yo Quiero!</h1>
			<button type="button" class="footer-close icon-close"></button>
		</div>
	</div>

	<div class="ugly-filler"></div>

	<footer role="contentinfo" class="contact-footer">
	
	<div class="inner-footer">
		<div class="footer-form">
			<h3>Aca va un texto introductorio que se administra como una página en wordpress. O podemos poner un texto relacionado con la sección en la que uno se encuentra?</h3>
			<p>Prometemos responder en el menor tiempo posible.</p>
			<form>
				<div class="name">
					<label for="name">Nombre:</label>
					<input type="text" placeholder="¿Cómo te gusta que te llamen?" name="name" id="name" tabindex="1">
				</div>
				<div class="medium">
					<label>¿Cómo te contactamos?</label>
					<div class="means-wrapper">
						<ul id="contact-means" class="select" tabindex="2">
							<li class="selected"><i class="icon-correo"></i></li>
							<div class="options-wrapper">
								<li class="option"><i class="icon-correo"></i></li>
								<li class="option"><i class="icon-telefono"></i></li>
							</div>
						</ul><input type="email" name="contact-field" id="contact-field" placeholder="Escribe tu correo electrónico" tabindex="3">
					</div>
				</div>
				<p>
					<label for="message">Mensaje:</label>
					<textarea name="message" id="message" tabindex="4"></textarea>
				</p>
				<p class="submit">
					<input class="color-bg" type="submit" Value="Enviar">
				</p>
			</form>
		</div>
		<div class="footer-block">
			<div class="contact-block">
				<h4>8manos en Bogotá</h4>
				<p>Calle 94 # 15-32 Oficina 301<br>Tel: (57-1) 6057039</p>
			</div>
			<div class="contact-block social">
				<h4>8manos en la red</h4>
				<ul class="contact-links">
					<li><a href="" class="icon-twitter">Twitter</a></li>
					<li><a href="" class="icon-github">Github</a></li>
					<li><a href="" class="icon-tumblr">Tumblr</a></li>
				</ul>
			</div>
		</div>		
	</div>
	</footer>

	<?php
		get_template_part('template', 'pages');
		get_template_part('template', 'team');
		get_template_part('template', 'persons');
		get_template_part('template', 'portfolio');
		get_template_part('template', 'labs');
		get_template_part('template', 'friends');
		get_template_part('template', 'guide');
	?>

	<?php wp_footer(); ?>

	<!-- Google Analytics - Optimized version by Mathias Bynens -->
	<!-- See: http://mathiasbynens.be/notes/async-analytics-snippet -->
	<!-- Change the UA-XXXX-XX string to your site's ID -->
	<script>
		var _gaq=[['_setAccount','UA-XXXX-XX'],['_trackPageview']];(function(a,b){var c=a.createElement(b),d=a.getElementsByTagName(b)[0];c.src=("https:"==location.protocol?"//ssl":"//www")+".google-analytics.com/ga.js";d.parentNode.insertBefore(c,d)})(document,"script");
	</script>
	<script src="<?php bloginfo('template_directory'); ?>/js/init.js"></script>
	<script src="<?php bloginfo('template_directory'); ?>/js/app.js"></script>
	<script src="<?php bloginfo('template_directory'); ?>/js/models.js"></script>
	<script src="<?php bloginfo('template_directory'); ?>/js/collections.js"></script>
	<script src="<?php bloginfo('template_directory'); ?>/js/routers.js"></script>
	<script src="<?php bloginfo('template_directory'); ?>/js/views.js"></script>
	<script src="<?php bloginfo('template_directory'); ?>/js/time.js"></script>
	<script src="<?php bloginfo('template_directory'); ?>/js/contrast.js"></script>
</body>
</html>
