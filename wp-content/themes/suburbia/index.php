<?php get_header(); ?>

	<?php include (TEMPLATEPATH . "/sidebar.php"); ?>

	<?php include (TEMPLATEPATH . "/navigation.php"); ?>        

    <!-- LOOP1 -->
    <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
    <div class="post one"<?php echo (is_category() || is_tag())? 'style="border-bottom: 1px solid #e0e0e0 !important; border-top: none !important;"' : ''; ?>>
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
    <!-- #LOOP1 -->
    
    <div class="aside"<?php echo (is_category() || is_tag())? 'style="border-bottom: 1px solid #e0e0e0 !important; border-top: none !important;"' : ''; ?>>
        <h3>Navigation</h3>
        <p>Please use the navigation to move within this section.</p>
        <p><?php next_posts_link('Next'); ?>
        <?php previous_posts_link('Prev'); ?>
        </p>
    </div>
   
<?php get_footer(); ?>
