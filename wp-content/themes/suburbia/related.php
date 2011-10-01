<div class="meta">
    <?php        		
        ob_start();
        ob_implicit_flush(0);
        echo get_option('suburbia_apropos'); 
        $my_logo = ob_get_contents();
        ob_end_clean();
        if (
        $my_logo == ''
        ): ?>
        <a href="<?php bloginfo("url"); ?>/">
        <img src="<?php bloginfo('template_url'); ?>/images/logo2.gif" alt="<?php bloginfo('name'); ?>" width="155" height="155" class="dem" /></a>
        <?php else: ?>
        <a href="<?php bloginfo("url"); ?>/"><img src="<?php echo get_option('suburbia_apropos'); ?>" width="155" height="155" class="dem" /></a>       		
    <?php endif; ?>
    
    <h3>Recent articles</h3>
    <ul class="recent">
    <?php
        query_posts(array('posts_per_page' => 5));
        if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

            <li><a href="<?php the_permalink() ?>"><?php the_title(); ?></a><span><?php the_time('d M Y'); ?></span></li>

        <?php endwhile; endif; wp_reset_query();
    ?>
    </ul>
    
    <h3>Recent comments</h3>
    <ul class="recent">
    <?php
        $comms = get_comments('number=5');
        foreach($comms as $comm) :
            $post = get_post($comm->comment_post_ID); ?>
            <li class="recentcomments"><a href="<?php echo $comm->comment_author_url; ?>" class="url"><?php echo $comm->comment_author; ?></a> on <a href="<?php echo $post->guid; ?>"><?php echo $post->post_title; ?></a><span><?php if (function_exists('excerpt_comment')) custom_excerpt($comm->comment_content, 20); ?></span></li>
        <?php endforeach;
        wp_reset_query();
    ?>
    </ul>
</div>
