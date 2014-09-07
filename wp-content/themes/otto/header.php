<!DOCTYPE html>
<html  <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">

	<title><?php bloginfo('name'); ?><?php wp_title(' | '); ?></title>

	<meta name="description" content="<?php bloginfo('description'); ?>">

	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/normalize/3.0.1/normalize.min.css">
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/styles.css">

	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<!--[if lt IE 9]><script src="<?php bloginfo('template_directory'); ?>/js/lib/html5shiv.js" media="all"></script><![endif]-->

	<?php
	wp_enqueue_script( 'otto-modernizr', get_template_directory_uri() . '/js/lib/modernizr.js', array(), '2.5.2', false );
	wp_enqueue_script( 'otto-plugins', get_template_directory_uri() . '/js/lib/plugins.js', array( 'jquery' ), '2.5.2', true );
	wp_enqueue_script( 'underscore', get_bloginfo('template_directory').'/js/lib/underscore-min.js', array(), '1.7.0', true );
	wp_enqueue_script( 'backbone', get_bloginfo('template_directory').'/js/lib/backbone-min.js', array('jquery', 'underscore'), '1.1.1', true );
	wp_head();
	?>
</head>
<body <?php body_class(); ?>>

	<header class="color-bg" role="banner">
		<a href="" class="menu-toggle">Menú</a>
		<nav role="navigation">
			<?php wp_nav_menu(array('theme_location' => 'primary')); ?>
		</nav>

		<h1 class="ochomanos"><a href="<?php echo home_url(); ?>/" rel="home"><?php bloginfo('name');?></a></h1>
	</header>

	<!-- If you want to use an element as a wrapper, i.e. for styling only, then <div> is still the element to use -->
	<div class="wrap">
