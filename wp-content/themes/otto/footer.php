		<div class="section-name contact-header is-fixed">
			<div class="icon-contacto color-bg"></div>
			<h1 class="footer-toggle color-bg">¡Yo Quiero!</h1>
			<button type="button" class="color icon-close footer-close"></button>
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
					<input type="text" placeholder="como te gusta que te llamen" name="name" id="name">
				</div>
				<div class="medium">
					<label for="contact-medium">Como te contactamos:</label>
					<select id="contact-medium" name="contact-medium">
						<option selected value="email">&#x2709;</option>
						<option value="phone">&#x260e;</option>
					</select>
					<input type="email" name="contact-info" id="contact-info">
				</div>
				<p>
					<label for="message">Mensaje:</label>
					<textarea name="message" id="message"></textarea>
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
