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
      ?>
    </div>

    <div class="status"><span class="disable">inactivo</span><a href="#">visitar proyecto</a></div>

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

    <div class="team">
      <h3>Equipo</h3>
      <ul>
        <li><a href="#">Caramels wypas</a></li>
        <li><a href="#">Lemon drops sesame</a></li>
        <li><a href="#">snaps tootsie roll</a></li>
        <li><a href="#">pudding pie caramels</a></li>
        <li><a href="#">Jelly bonbon cake</a></li>
        <li><a href="#">cookie cheesecake</a></li>
        <li><a href="#">Caramels wypas</a></li>
        <li><a href="#">Lemon drops sesame</a></li>
        <li><a href="#">snaps tootsie roll</a></li>
        <li><a href="#">pudding pie caramels</a></li>
        <li><a href="#">Jelly bonbon cake</a></li>
        <li><a href="#">cookie cheesecake</a></li>
      </ul>
    </div>
  </article>
<?php endwhile; ?>

<?php get_footer(); ?>
