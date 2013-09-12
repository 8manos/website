<?php get_header(); ?>

	<h1><?php post_type_archive_title(); ?></h1>

	<h2>
		<?php
		global $wp_query;
		$post_type = get_post_type_object( $wp_query->query['post_type'] );
		echo $post_type->description;
		?>
	</h2>

	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark">
					<?php the_post_thumbnail('medium'); ?>
					<h2><?php the_title(); ?></h2>
					<h4><?php the_time('F \d\e Y'); ?></h4>
				</a>

			</article>

		<?php endwhile; ?>

	<?php endif; ?>

<?php get_footer(); ?>
