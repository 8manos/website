<?php
	add_theme_support( 'post-thumbnails' );
	add_image_size('thumb-portafolio',250,230, false);
	add_image_size('thumb-equipo',134,134, false);

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Acceso', 'manos' ),
	) );

	$debug = false;
	global $debug;

	function el_estilo($url)
		{
		global $post;
		if(is_single() && get_post_type() == "portafolio"){
			$o = "background-image:url(http://www.google.com/s2/u/0/favicons?domain=$url);";
			echo($o);
		}else{
			return false;
		}
	}

?>
