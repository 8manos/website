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
  <div id="mosaic">

  <?php while ( have_posts() ) : the_post(); ?>

    <article id="post-<?php the_ID(); ?>">
      <div class="content">
        <h1><b>ProjectName=</b> <i><?php the_title(); ?></i></h1>

        <?php the_content(); ?>

        <a href="<?php echo esc_url( get_post_meta( $post->ID, '_url', true ) ); ?>" target="_blank">
          <div class="info">
            <h2><?php the_title(); ?></h2>
            <h4><?php the_time('F \d\e Y'); ?></h4>
          </div>
        </a>

        <?php
        $types = get_the_terms( $post->ID, 'lab_type' );
        if ($types) {
          echo '<ul class="tags">';
          foreach($types as $type) {
            echo '<li>' . $type->name . '</li>';
          }
          echo '</ul>';
        }
        ?>
      </div>
    </article>

  <?php endwhile; ?>

  </div>
<?php endif; ?>

<?php get_footer(); ?>
