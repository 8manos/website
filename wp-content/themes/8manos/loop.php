
    <?php if ( have_posts() ) : ?>
      <?php while ( have_posts() ) : the_post(); ?>
        <article>
          <h2 class="entry-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h2>

	  <?php if(get_post_type() == 'portafolio'){ ?>
		<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumb'); ?></a>
	  <?php } ?>

          <div class="post-content">
            <?php the_content(); ?>
          </div>
        </article>
      <?php endwhile; ?>
    <?php else : ?>
    <?php endif; ?>
