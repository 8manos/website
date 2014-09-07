<?php get_header(); ?>

<main role="main">
  <div class="inner-header">
    <h2><span><a href="">People</a></span>Equipo</h2>
    <div class="back-link">Volver</div>
  </div>
  <div class="team-wrapper">
    <?php get_template_part( 'template', 'person' ); ?>
  </div>
</main>

<?php get_template_part( 'nav', 'sections' ); ?>

<?php get_footer(); ?>
