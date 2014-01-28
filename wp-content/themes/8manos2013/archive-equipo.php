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

    <article class="member">
      <div  class="thumb-con">
        <a class="thumbnail" href="<?php the_permalink(); ?>">
          <?php the_post_thumbnail('team-thumb'); ?>
          <div class="name">
            <?php
              $full_name = get_the_title();
              $split_point = ' ';
              $separator_pos = strrpos($full_name, $split_point);
              $first_name = substr($full_name, 0, $separator_pos);
              $last_name = substr($full_name, $separator_pos+1);
            ?>
            <h2><?php echo $first_name; ?></h2>
            <h2><?php echo $last_name; ?></h2>
          </div>
        </a>
      </div>
    </article>

  <?php endwhile; ?>

</div>
<?php endif; ?>

<?php get_footer(); ?>
