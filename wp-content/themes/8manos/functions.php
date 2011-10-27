<?php
// Defaul support
	add_theme_support( 'post-thumbnails' );
	add_image_size('thumb-portafolio',250,230, false);
	add_image_size('thumb-equipo',134,134, false);
	add_custom_background();

// Esto es para el logo del home

	// vacio para permitir random
	define( 'HEADER_IMAGE', '' );
	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'manos_header_image_width', 960 ) );
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'manos_header_image_height', 593 ) );
	define( 'NO_HEADER_TEXT', true );
	add_theme_support( 'custom-header', array( 'random-default' => true ) );
	add_custom_image_header( 'manos_header_style', 'manos_admin_header_style');

	register_default_headers( array(
		'bicicleta' => array(
			'url' => '%s/img/headers/logo01_aureo.png',
			'thumbnail_url' => '%s/img/headers/logo01_aureo-thumb.png',
			/* translators: header image description */
			'description' => __( 'Bicicleta', 'manos' )
		)
	) );

	// para poner en el head
	function manos_header_style() {
    		?><style type="text/css">
        		.home header h1 a {
				width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
				height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
            			background: url(<?php header_image(); ?>);
        		}
    		</style><?php
	}

	// para poner en el head del admin
	function manos_admin_header_style() {
	    ?><style type="text/css">
		#headimg {
		    width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
		    height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
		    background: no-repeat;
		}
	    </style><?php
	}

// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Acceso', 'manos' ),
	) );

	$debug = false;
	global $debug;

// Para traer favicons automagicamente
	function el_estilo($url){
		global $post;
		if(is_single() && get_post_type() == "portafolio"){
			$o = "background-image:url(http://www.google.com/s2/u/0/favicons?domain=$url);";
			echo($o);
		}else{
			return false;
		}
	}
?>
