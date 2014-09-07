	</div>

	<pre id="orientation">
		x: <span id="data-x">0</span>
		y: <span id="data-y">0</span>
		z: <span id="data-z">0</span>

		r: <span id="data-r">0</span>
		g: <span id="data-g">0</span>
		b: <span id="data-b">0</span>

		h: <span id="data-h">0</span>
		s: <span id="data-s">0</span>
		l: <span id="data-l">0</span>
	</pre>

	<footer role="contentinfo">
		<small>&copy; <?php bloginfo('name'); ?></small>
	</footer>

	<?php
		get_template_part('template', 'page');
	?>

	<?php wp_footer(); ?>

	<!-- Google Analytics - Optimized version by Mathias Bynens -->
	<!-- See: http://mathiasbynens.be/notes/async-analytics-snippet -->
	<!-- Change the UA-XXXX-XX string to your site's ID -->
	<script>
		var _gaq=[['_setAccount','UA-XXXX-XX'],['_trackPageview']];(function(a,b){var c=a.createElement(b),d=a.getElementsByTagName(b)[0];c.src=("https:"==location.protocol?"//ssl":"//www")+".google-analytics.com/ga.js";d.parentNode.insertBefore(c,d)})(document,"script");
	</script>

</body>
</html>
