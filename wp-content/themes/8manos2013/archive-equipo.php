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
  <div class="team-wrapper">

    <?php while ( have_posts() ) : the_post(); ?>

      <?php get_template_part('loop', 'equipo'); ?>

    <?php endwhile; ?>

  </div>
<?php endif; ?>

<?php
$subteams = get_terms( 'subteam');

foreach ($subteams as $subteam_obj):

  global $wp_query;
  $args = array_merge( $wp_query->query_vars, array( 'tax_query' => array( array('taxonomy' => 'subteam', 'field' => 'slug', 'terms' => $subteam_obj->slug) ) ) );
  $subteam_query = new WP_Query( $args );
?>

  <h1 class="main-title"><?php echo $subteam_obj->name; ?></h1>

  <?php if ( $subteam_query->have_posts() ) : ?>
    <div class="team-wrapper">

      <?php while ( $subteam_query->have_posts() ) : $subteam_query->the_post(); ?>

        <?php get_template_part('loop', 'equipo'); ?>

      <?php endwhile; ?>

    </div>
  <?php endif; ?>

<?php endforeach; ?>

<?php get_footer(); ?>
