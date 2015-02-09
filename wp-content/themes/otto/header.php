<!DOCTYPE html>
<html  <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">

	<title><?php bloginfo('name'); ?><?php wp_title(' | '); ?></title>

	<meta name="description" content="<?php bloginfo('description'); ?>">

	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Ubuntu:400,500,700,400italic,500italic,700italic">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/normalize/3.0.1/normalize.min.css">

	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<!--[if lt IE 9]><script src="<?php bloginfo('template_directory'); ?>/js/lib/html5shiv.js" media="all"></script><![endif]-->

	<?php
	wp_enqueue_style( 'otto-css', get_template_directory_uri().'/css/styles.css', '', '', 'all' );
	wp_enqueue_script( 'picturefill', get_template_directory_uri().'/js/lib/picturefill.min.js', array(), '2.2.0', false);
	wp_enqueue_script( 'otto-plugins', get_template_directory_uri().'/js/lib/plugins.js', array( 'jquery' ), '2.5.2', true );
	wp_enqueue_script( 'owl', get_template_directory_uri().'/js/lib/owl.carousel.min.js', array(), '2.0.0', true );
	wp_enqueue_script( 'scrollTo', get_template_directory_uri().'/js/lib/jquery.scrollTo.min.js', array(), '1.4.13', true );
	wp_enqueue_script( 'underscore', get_template_directory_uri().'/js/lib/underscore-min.js', array(), '1.7.0', true );
	wp_enqueue_script( 'backbone', get_template_directory_uri().'/js/lib/backbone-min.js', array('jquery', 'underscore'), '1.1.1', true );
	wp_enqueue_script('init', get_template_directory_uri().'/js/init.js', array(), '', true);
	wp_enqueue_script('app', get_template_directory_uri().'/js/app.js', array(), '', true);
	wp_enqueue_script('models', get_template_directory_uri().'/js/models.js', array(), '', true);
	wp_enqueue_script('collections', get_template_directory_uri().'/js/collections.js', array(), '', true);
	wp_enqueue_script('routers', get_template_directory_uri().'/js/routers.js', array(), '', true);
	wp_enqueue_script('views', get_template_directory_uri().'/js/views.js', array(), '', true);
	wp_enqueue_script('time', get_template_directory_uri().'/js/time.js', array(), '', true);
	wp_head();
	?>

	<link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="/apple-touch-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png">
	<link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="/favicon-194x194.png" sizes="194x194">
	<link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96">
	<link rel="icon" type="image/png" href="/android-chrome-192x192.png" sizes="192x192">
	<link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
	<link rel="manifest" href="/manifest.json">
	<meta name="msapplication-TileColor" content="#2b5797">
	<meta name="msapplication-TileImage" content="/mstile-144x144.png">
	<meta name="theme-color" content="#ffffff">
</head>
<body <?php body_class(); ?>>

	<?php
	if (! is_front_page()) {
		$header_class = 'class="is-collapsed"';
	} else {
		$header_class = '';
	}
	?>

	<header role="banner" id="header" <?php echo $header_class; ?>>
		<nav role="navigation" class="main-nav color-bg">
			<?php wp_nav_menu(array('theme_location' => 'primary', 'container' => false)); ?>
		</nav>

		<h1 class="menu-bar">
			<a href="" class="menu-toggle icon-menu color-against-black">Menú</a>
			<a href="<?php echo home_url(); ?>/" rel="home" class="logo color-against-black"><?php bloginfo('name');?></a>
			<a href="http://codigoweb.co/" target="_blank" class="blog-link color-against-black">Blog</a>
		</h1>
	</header>

	<!-- If you want to use an element as a wrapper, i.e. for styling only, then <div> is still the element to use -->
	<div class="wrap is-contact-hidden">
