  <footer>
    <nav id="pie" class="menuda">
      <ul>
        <li><strong>&infin;manos s.a.s</strong></li>
        <li><a href="http://8manos.com">8manos.com</a></li>
        <li><a href="mailto:info@8manos.com"></a></li>
        <li>cel: 3108077316</li>
        <li>Cll 94 #15-32 of. 301 - Bogotá</li>
        <li>NIT: 900381428-7</li>
      </ul>
    </nav>
  </footer>


  <!-- JavaScript at the bottom for fast page loading -->

  <!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
  <script>window.jQuery || document.write('<script src="<?php bloginfo('template_directory'); ?>/js/libs/jquery-1.6.4.min.js"><\/script>')</script>


  <!-- scripts concatenated and minified via build script -->
  <script defer src="<?php bloginfo('template_directory'); ?>/js/plugins.js"></script>
  <script defer src="<?php bloginfo('template_directory'); ?>/js/script.js"></script>
  <!-- end scripts -->

  <?php wp_footer(); ?>

  <!-- Asynchronous Google Analytics snippet. Change UA-XXXXX-X to be your site's ID.
       mathiasbynens.be/notes/async-analytics-snippet -->
  <script>
    var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview'],['_trackPageLoadTime']];
    (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
    g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
    s.parentNode.insertBefore(g,s)}(document,'script'));
  </script>

  <!-- Prompt IE 6 users to install Chrome Frame. Remove this if you want to support IE 6.
       chromium.org/developers/how-tos/chrome-frame-getting-started -->
  <!--[if lt IE 7 ]>
    <script defer src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
    <script defer>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
  <![endif]-->

</body>
</html>

