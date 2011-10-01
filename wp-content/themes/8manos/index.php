<?php get_header(); ?>

  <div role="main" id="contenidos">
    <?php if ( have_posts() ) : ?>
      <?php while ( have_posts() ) : the_post(); ?>
        <article>
          <h2 class="entry-title">
            <?php the_title(); ?>
          </h2>

          <div class="post-content">
            <?php the_content(); ?>
          </div>
        </article>
      <?php endwhile; ?>
    <?php else : ?>
    <?php endif; ?>
  </div>

<?php get_footer(); ?>

