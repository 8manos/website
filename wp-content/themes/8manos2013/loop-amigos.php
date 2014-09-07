<h1 class="main-title">Amigos</h1>

<?php
global $wp_query;

$args = array(
  'post_type' => 'friend',
  'posts_per_page' => -1,
  'orderby' => 'menu_order',
  'order' => 'ASC'
);
$friends_query = new WP_Query( $args );
?>

<?php if ( $friends_query->have_posts() ) : ?>
  <div class="team-wrapper">

    <?php while ( $friends_query->have_posts() ) : $friends_query->the_post(); ?>
      <div class="friend-wrapper">
        <article class="member friend">
          <div  class="thumb-con">
            <a class="thumbnail" href="<?php echo esc_url( $post->post_excerpt ); ?>">
              <?php the_post_thumbnail('team-thumb'); ?>
            </a>
          </div>
        </article>

        <h2 class="friend-title"><?php the_title(); ?></h2>
      </div>

    <?php endwhile; ?>

  </div>
<?php endif; ?>
