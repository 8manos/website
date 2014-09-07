<?php get_header(); ?>

<main role="main">
  <?php
    get_template_part( 'section', 'home' );
    get_template_part( 'section', 'about' );
    get_template_part( 'section', 'people' );
  ?>
</main>

<?php get_template_part( 'nav', 'sections' ); ?>

<?php get_footer(); ?>
