<!DOCTYPE html>
<html  <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">

	<title><?php bloginfo('name'); ?><?php wp_title(' | '); ?></title>

	<meta name="description" content="<?php bloginfo('description'); ?>">

	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link href='http://fonts.googleapis.com/css?family=Ubuntu:400,500&text=∞manos' rel='stylesheet' type='text/css'>
	<link href="<?php bloginfo('template_directory'); ?>/css/normalize.css" rel="stylesheet" media="all">
	<link href="<?php bloginfo('template_directory'); ?>/css/styles.css" rel="stylesheet" media="all">

	<!--[if lt IE 9]><script src="<?php bloginfo('template_directory'); ?>/js/html5shiv-printshiv.js" media="all"></script><![endif]-->

	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

	<header role="banner">

		<h1 class="logo-text"><?php bloginfo('name');?></h1>

		<nav role="navigation">
			<?php wp_nav_menu(); ?>
		</nav>

	</header>

	<!-- If you want to use an element as a wrapper, i.e. for styling only, then <div> is still the element to use -->
	<div class="wrap">

		<main role="main">
