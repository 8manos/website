	</div>

	<footer role="contentinfo">
		<small>&copy; <?php bloginfo('name'); ?></small>
	</footer>

	<?php
		get_template_part('template', 'principles');
		get_template_part('template', 'team');
		get_template_part('template', 'persons');
		get_template_part('template', 'portfolio');
		get_template_part('template', 'labs');
		get_template_part('template', 'friends');
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
	<script src="<?php bloginfo('template_directory'); ?>/js/colors.js"></script>
	<script src="<?php bloginfo('template_directory'); ?>/js/contrast.js"></script>
</body>
</html>
