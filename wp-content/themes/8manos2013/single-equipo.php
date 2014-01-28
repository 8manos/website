<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
  <h1 class="main-title"><?php the_title(); ?></h1>
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
        <!-- orden alfabetico -->
        <li class="behance"><a href="">behance</a></li>
        <li class="dribbble"><a href="">dribbble</a></li>
        <li class="flickr"><a href="">flickr</a></li>
        <li class="github"><a href="">github</a></li>
        <li class="skype"><a href="">skype</a></li>
        <li class="twitter"><a href="">twitter</a></li>
        <li class="vimeo"><a href="">vimeo</a></li>
        <li class="mail"><a href="mailto:">correo electronico</a></li>
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
