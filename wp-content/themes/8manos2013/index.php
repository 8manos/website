<?php get_header(); ?>

	<h1><?php post_type_archive_title(); ?></h1>

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
