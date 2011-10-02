<?php
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Acceso', 'manos' ),
	) );

	$debug = true;
	global $debug;

?>
