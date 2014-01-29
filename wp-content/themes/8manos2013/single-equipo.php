<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
  <h1 class="main-title"><?php the_title(); ?></h1>

  <div class="info">
    <?php
    $skills = get_the_terms( $post->ID, 'especialidades' );
    if ($skills) {
      echo '<ul class="tags">';
      foreach($skills as $tag) {
        echo '<li>' . $tag->name . '</li>';
      }
      echo '</ul>';
    }
    ?>
  </div>
</div>

<div class="profile-frame">
  <div class="wrap">

    <div class="member">
      <div  class="thumb-con">
        <span class="thumbnail">
          <?php the_post_thumbnail('team-thumb'); ?>
        </span>
      </div>
    </div>

    <div class="member-intro">
      <?php the_content(); ?>

      <ul class="social-menu">
        <?php
        $contact_links = simple_fields_values('link_type, link_url');

        foreach ($contact_links as $contact) {
          $href = $contact['link_type'] == 'mail' ? 'mailto:'.$contact['link_url'] : esc_url($contact['link_url']);
          printf('<li class="%1$s"><a href="%2$s">%1$s</a></li>', $contact['link_type'], $href);
        }
        ?>
      </ul>
    </div>

  </div>
</div>

<div class="wrap">

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
    <h3>Participación en proyectos</h3>

    <ul>
    <?php while ( $connected->have_posts() ) : $connected->the_post(); ?>
      <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?> <time class="date"><?php the_time('F \d\e Y'); ?></time></a></li>
    <?php endwhile; ?>
    </ul>
  </div>

  <?php
  // Prevent weirdness
  wp_reset_postdata();

  endif;
  ?>

<?php endwhile; ?>

<?php get_footer(); ?>
