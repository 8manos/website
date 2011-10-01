<?php get_header(); ?>

	<?php include (TEMPLATEPATH . "/sidebar.php"); ?>        

    <!-- LOOP1 -->
    <?php if (have_posts()) : ?>
    <?php query_posts('showposts=2'); ?>
    <?php while (have_posts()) : the_post(); ?>
    <div class="two">
		<div class="sepia">
        	<?php if (get_post_meta($post->ID, 'cover', true)) { ?>
    		<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><img src="http://www.moodyguy.net/images/cover/<?php echo get_post_meta($post->ID, 'cover', true) ?>" alt="<?php the_title(); ?>" /></a>
    		<?php } else { ?>
            <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/none.png" alt="<?php the_title(); ?>" /></a>
    		<?php } ?>
        </div><!-- .sepia -->
        <h2><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
        <?php the_excerpt(); ?>   
        <div class="time"><?php the_time('M, d'); ?> &middot; in <?php the_category(','); ?></div>
	</div>
   	<?php endwhile; ?>
   	<?php else : ?>
	<?php endif; ?> 
    <!-- #LOOP1 --> 
    
   	<!-- LOOP2 -->
    <?php if (have_posts()) : ?>
    <?php query_posts('showposts=5&offset=2'); ?>
    <?php while (have_posts()) : the_post(); ?>
    <div class="one">
		<div class="sepia">
        	<?php if (get_post_meta($post->ID, 'cover', true)) { ?>
    		<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><img src="http://www.moodyguy.net/images/cover/<?php echo get_post_meta($post->ID, 'cover', true) ?>"  alt="<?php the_title(); ?>" /></a>
    		<?php } else { ?>
            <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/none.png" alt="<?php the_title(); ?>" /></a>
    		<?php } ?>
        </div><!-- .sepia -->
        <h2><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
        <?php the_excerpt(); ?> 
        <div class="time"><?php the_time('M, d'); ?></div>  
	</div>
   	<?php endwhile; ?>
   	<?php else : ?>
	<?php endif; ?> 
    <!-- #LOOP2 --> 
    
    <?php include (TEMPLATEPATH . "/bottom.php"); ?>
             
<?php get_footer(); ?>
