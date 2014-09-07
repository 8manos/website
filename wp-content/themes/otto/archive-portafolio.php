<?php get_header(); ?>

<main role="main">
  <div class="inner-header">
    <h2><span><a href="">Work</a></span>Portafolio</h2>
    <div class="back-link">Volver</div>
  </div>
  <div class="works-wrapper">
    <?php get_template_part( 'template', 'project' ); ?>
  </div>
</main>

<?php get_template_part( 'nav', 'sections' ); ?>

<?php get_footer(); ?>
