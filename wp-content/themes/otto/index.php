<?php get_header(); ?>

<main role="main">
  <?php
    get_template_part('section', 'principles');
    get_template_part('section', 'team');
    get_template_part('section', 'about');
    get_template_part('section', 'work');
    get_template_part('section', 'lab');
    get_template_part('section', 'contact');
  ?>
</main>
<?php get_footer(); ?>