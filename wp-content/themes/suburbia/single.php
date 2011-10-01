<?php get_header(); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<?php include (TEMPLATEPATH . "/meta.php"); ?>        
    <div id="single">
		<h1><?php the_title(); ?></h1>   
		<?php the_content(); ?>


    <div id="comments">
        <?php comments_template(); ?>
    </div>

    </div>



	<?php endwhile; ?>
	<?php else : ?>
	<?php endif; ?>  

    <?php include (TEMPLATEPATH . "/related.php"); ?>
              
<?php get_footer(); ?>
