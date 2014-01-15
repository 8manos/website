<?php get_header(); ?>

<h1 class="main-title"><?php post_type_archive_title(); ?></h1>

<p class="intro">
  <?php
  global $wp_query;
  $post_type = get_post_type_object( $wp_query->query['post_type'] );
  echo $post_type->description;
  ?>
</p>

<?php if ( have_posts() ) : ?>

  <?php while ( have_posts() ) : the_post(); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class('article-portafolio'); ?>>
      <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark">
        <?php the_post_thumbnail('medium'); ?>
        <div class="info">
          <h2><?php the_title(); ?></h2>
          <h4><?php the_time('F \d\e Y'); ?></h4>

          <?php
          $posttags = get_the_tags();
          if ($posttags) {
            echo '<ul class="tags">';
            foreach($posttags as $tag) {
              echo '<li>' . $tag->name . '</li>';
            }
            echo '</ul>';
          }
          ?>
        </div>
      </a>

    </article>

  <?php endwhile; ?>

<?php endif; ?>

<?php get_footer(); ?>
