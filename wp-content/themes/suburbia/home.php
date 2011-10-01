<?php get_header(); ?>

	<?php include (TEMPLATEPATH . "/sidebar.php"); ?>        
    <?php
        if ( $paged == 0 ) {
            $offset1 = 0;
            $offset2 = 2;
        } else {
            $off = $paged - 1;
            $offset1 = $off * 7;
            $offset2 = $off * 7 + 2;
        }
    ?>
    <!-- LOOP1 -->
    <?php if (have_posts()) : ?>
    <?php query_posts('posts_per_page=2&offset='.$offset1); ?>
    <?php while (have_posts()) : the_post(); ?>
    <div class="post two">
		<div class="sepia">

			<?php
			if ( has_post_thumbnail() ) { ?>
                    	<?php 
                    	$imgsrcparam = array(
						'alt'	=> trim(strip_tags( $post->post_excerpt )),
						'title'	=> trim(strip_tags( $post->post_title )),
						);
                    	$thumbID = get_the_post_thumbnail( $post->ID, 'two', $imgsrcparam ); ?>
                        <div class="preview"><a href="<?php the_permalink() ?>"><?php echo "$thumbID"; ?></a></div>

                    
                    <?php } else {?>
                        <div class="preview"><a href="<?php the_permalink() ?>"><img src="<?php bloginfo('template_url'); ?>/images/default-thumbnail.jpg" alt="<?php the_title(); ?>" /></a></div>
                    <?php } ?>

        </div><!-- .sepia -->
        <h2><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
        <?php the_excerpt(); ?>   
        <div class="time"><?php the_time('M, d'); ?> &middot; in <?php the_category(','); ?></div>
	</div>
   	<?php endwhile; ?>
   	<?php else : ?>
	<?php endif; ?> 
    <?php wp_reset_query(); ?>
    <!-- #LOOP1 --> 
    
   	<!-- LOOP2 -->
    <?php if (have_posts()) : ?>
    <?php query_posts('posts_per_page=5&offset='.$offset2); ?>
    <?php while (have_posts()) : the_post(); ?>
    <div class="post one"<?php echo (is_home())? 'style="border-bottom: none !important;"' : ''; ?>>
		<div class="sepia">

			<?php
			if ( has_post_thumbnail() ) { ?>
                    	<?php 
                    	$imgsrcparam = array(
						'alt'	=> trim(strip_tags( $post->post_excerpt )),
						'title'	=> trim(strip_tags( $post->post_title )),
						);
                    	$thumbID = get_the_post_thumbnail( $post->ID, 'one', $imgsrcparam ); ?>
                        <div class="preview"><a href="<?php the_permalink() ?>"><?php echo "$thumbID"; ?></a></div>

                    
                    <?php } else {?>
                        <div class="preview"><a href="<?php the_permalink() ?>"><img src="<?php bloginfo('template_url'); ?>/images/default-thumbnail.jpg" alt="<?php the_title(); ?>" /></a></div>
                    <?php } ?>

        </div><!-- .sepia -->
        <h2><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
        <?php the_excerpt(); ?> 
        <div class="time"><?php the_time('M, d'); ?></div>  
	</div>
   	<?php endwhile; ?>
   	<?php else : ?>
	<?php endif; ?> 
    <?php wp_reset_query(); ?>
    <!-- #LOOP2 --> 
    <div style="clear: both;"></div>
    <?php include (TEMPLATEPATH . "/bottom.php"); ?>
             
<?php get_footer(); ?>
