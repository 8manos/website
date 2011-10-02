<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="es"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="es"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="es"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title><?php bloginfo('name'); ?><?php wp_title(' | '); ?></title>
  <meta name="description" content="Desarrollo web, programación, diseño, y servicios en red">
  <meta name="author" content="8manos S.A.S">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/style.css">
  <link href='http://fonts.googleapis.com/css?family=Ubuntu:400,700,400italic' rel='stylesheet' type='text/css'>
  <?php 
	wp_head(); 
  ?>
  <script src="<?php bloginfo('template_directory'); ?>/js/libs/modernizr-2.0.6.min.js"></script>
</head>

<body <?php body_class(); ?>>
  <header>
    <h1><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name');?></a></h1>
    <h2><?php bloginfo('description'); ?></h2>

    <nav id="acceso" class="clearfix">
      <?php wp_nav_menu(); ?>
    </nav>
  </header>

