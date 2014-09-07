<?php get_header(); ?>

  <?php while ( have_posts() ) : the_post(); ?>

    <article>
      <h1 class="main-title"><?php the_title(); ?></h1>

      <div class="content">
      	<?php the_content(); ?>
      </div>
    </article>

  <?php endwhile; ?>

<?php get_footer(); ?>
