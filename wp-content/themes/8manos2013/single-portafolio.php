<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
  <article id="post-<?php the_ID(); ?>">
    <h1 class="main-title"><?php the_title(); ?></h1>

    <div class="info">
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

      $status = reset( get_the_terms( $post->ID, 'status' ) );
      $status_class = ( $status->name == 'inactivo' ) ? 'disable' : 'enable';
      ?>
    </div>

    <div class="status"><span class="<?php echo $status_class; ?>"><?php echo $status->name; ?></span><a href="<?php echo esc_url( simple_fields_value('url') ); ?>">visitar proyecto</a></div>

    <?php
    $gallery_args = array(
      'size' => 'medium',
      'columns' => -1,
      'link' => 'none',
      'itemtag' => 'li',
      'icontag' => 'span',
      'captiontag' => 'p'
    );
    echo gallery_shortcode( $gallery_args );
    ?>

    <div class="content">
      <?php the_content(); ?>
    </div>

    <?php
    // Find connected pages
    $connected = new WP_Query( array(
      'connected_type' => 'project_team',
      'connected_items' => get_queried_object(),
      'nopaging' => true,
    ) );

    // Display connected pages
    if ( $connected->have_posts() ) :
    ?>
    <div class="team">
      <h3>Equipo</h3>
      <ul>
      <?php while ( $connected->have_posts() ) : $connected->the_post(); ?>
        <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
      <?php endwhile; ?>
      </ul>
    </div>

    <?php
    // Prevent weirdness
    wp_reset_postdata();

    endif;
    ?>

  </article>
<?php endwhile; ?>

<?php get_footer(); ?>
