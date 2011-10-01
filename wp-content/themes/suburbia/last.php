<div class="aside"<?php echo (is_category() || is_tag() || is_page())? 'style="border-bottom: 1px solid #e0e0e0 !important; border-top: none !important; border-right: none !important;"' : ''; ?>>
	<h3>Latest posts</h3>
	<ul class="recent">
    <?php
        query_posts(array('posts_per_page' => 5));
        if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

            <li><a href="<?php the_permalink() ?>"><?php the_title(); ?></a><span><?php the_time('d M Y'); ?></span></li>

        <?php endwhile; endif; wp_reset_query();
    ?>
    </ul>
</div>
