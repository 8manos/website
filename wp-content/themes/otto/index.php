<?php get_header(); ?>
<main role="main">
  <noscript>
    <?php while ( have_posts() ) : the_post(); ?>

      <section class="<?php echo $post->post_name; ?> main-section">
        <div class="inner-header">
          <div class="section-name">
            <div class="icon-<?php echo $post->post_name; ?> color-bg"></div>
            <h1 class="color"><?php the_title(); ?></h1>
          </div>
        </div>
        <div class="inner-main">
          <?php the_content(); ?>
        </div>

        <?php if ($post->post_name == 'servicios') { ?>
          <p class="close-search color"> ¿Eres un cliente chévere? <br> <span>Déjanos tus datos para contatarte. :-)</span></p>
        <?php } elseif ($post->post_name == 'principios')  { ?>
          <p class="close-search color"> ¿Te sientes identificado? <br> <span>Unete a nuestro equipo. :-)</span></p>
        <?php } ?>
      </section>

    <?php endwhile; ?>
  </noscript>
</main>
<?php get_footer(); ?>