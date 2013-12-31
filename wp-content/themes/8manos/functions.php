<?php

// Desactivar el reporte de version de WordPress para evitar ataques automáticos
function sin_generators()
{
return '';
}
add_filter('the_generator','sin_generators');

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

// Revisa si el prefijo http existe, lo agrega si no existe.
function httpify($link, $append = 'http://', $allowed = array('http://', 'https://')){
  $found = false;

  foreach($allowed as $protocol){
    	if(strpos($link, $protocol) != 0){
      		$found = true;
	}
  }

  if($found){
	return $link;
  }else{
	return $append.$link;
  }
}


//Woocommerce
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );

add_filter( 'woocommerce_currencies', 'add_col_pesos' );
function add_col_pesos( $currencies ) {
	$currencies['COP'] = __( 'Pesos Colombianos', 'woocommerce' );
	return $currencies;
}

add_filter('woocommerce_currency_symbol', 'add_col_pesos_symbol', 10, 2);
function add_col_pesos_symbol( $currency_symbol, $currency ) {
	switch( $currency ) {
		case 'COP': $currency_symbol = '$'; break;
	}
	return $currency_symbol;
}
?>
