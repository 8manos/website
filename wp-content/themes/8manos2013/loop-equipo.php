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
