<?php
class Manos {
	const version = '2014';

	function setup() {
		add_action( 'init', array( __CLASS__, 'manos_post_types' ) );
		add_action( 'init', array( __CLASS__, 'manos_taxonomies' ) );
		add_filter( 'kc_post_settings', array( __CLASS__, 'manos_custom_fields' ) );
		add_filter( 'thermal_post_entity', array(__CLASS__, 'api_return_fields') , 10, 3);

		add_action( 'p2p_init', array( __CLASS__, 'my_connection_types' ) );
	}

	function manos_post_types() {
		register_post_type('equipo', array(
			'label' => 'Equipo',
			'labels' => array (
				'name' => 'Equipo',
				'add_new' => 'Agregar nuevo',
				'add_new_item' => 'Agregar nuevo equipo',
				'edit_item' => 'Editar equipo',
				'new_item' => 'Nuevo equipo',
				'view_item' => 'Ver equipo',
				'parent' => 'Equipo padre',
				),
			'public' => true,
			'map_meta_cap' => true,
			'hierarchical' => true,
			'has_archive' => true,
			'supports' => array('title','editor','excerpt','revisions','thumbnail','page-attributes')
			)
		);

		register_post_type('colaboradores', array(
			'label' => 'Colaboradores',
			'labels' => array (
				'name' => 'Colaboradores',
				'add_new' => 'Agregar nuevo',
				'add_new_item' => 'Agregar nuevo colaborador',
				'edit_item' => 'Editar colaborador',
				'new_item' => 'Nuevo colaborador',
				'view_item' => 'Ver colaborador',
				'parent' => 'Colaborador padre',
				),
			'public' => true,
			'map_meta_cap' => true,
			'hierarchical' => true,
			'has_archive' => true,
			'supports' => array('title','editor','excerpt','revisions','thumbnail','page-attributes')
			)
		);

		register_post_type('portafolio', array(
			'label' => 'Portafolio',
			'labels' => array (
				'name' => 'Portafolio',
				'add_new' => 'Agregar nuevo',
				'add_new_item' => 'Agregar nuevo portafolio',
				'edit_item' => 'Editar portafolio',
				'new_item' => 'Nuevo portafolio',
				'view_item' => 'Ver portafolio',
				),
			'description' => 'Nuestro portafolio es la mejor muestra de lo que hacemos. Acá encontrarás algunos de los proyectos que hemos realizado junto a nuestros colaboradores y aliados.',
			'public' => true,
			'map_meta_cap' => true,
			'has_archive' => true,
			'supports' => array('title','editor','revisions','thumbnail'),
			'taxonomies' => array('post_tag')
			)
		);

		register_post_type('lab', array(
			'label' => 'Lab',
			'labels' => array (
				'name' => 'Lab',
				'add_new' => 'Agregar nuevo',
				'add_new_item' => 'Agregar nuevo lab',
				'edit_item' => 'Editar lab',
				'new_item' => 'Nuevo lab',
				'view_item' => 'Ver lab',
				),
			'description' => 'En 8manos tenemos espacio para la experimentación, para desarrollos propios y proyectos de código abierto.',
			'public' => true,
			'map_meta_cap' => true,
			'hierarchical' => true,
			'has_archive' => true,
			'supports' => array('title','editor','revisions','thumbnail','page-attributes')
			)
		);

		register_post_type('friend', array(
			'label' => 'Amigos',
			'labels' => array (
				'name' => 'Amigos',
				'add_new' => 'Agregar nuevo',
				'add_new_item' => 'Agregar nuevo amigo',
				'edit_item' => 'Editar amigo',
				'new_item' => 'Nuevo amigo',
				'view_item' => 'Ver amigo',
				),
			'public' => true,
			'map_meta_cap' => true,
			'hierarchical' => true,
			'supports' => array('title','excerpt','thumbnail','page-attributes')
			)
		);
	}

	function manos_taxonomies() {
		register_taxonomy( 'especialidades', array ( 'portafolio', 'equipo' ), array(
			'label' => 'Especialidades'
			)
		);

		register_taxonomy( 'status', 'portafolio', array(
			'label' => 'Estados',
			'show_admin_column' => true
			)
		);

		register_taxonomy( 'lab_type', 'lab', array(
			'label' => 'Tipo de Proyecto'
			)
		);
	}

	function manos_custom_fields($groups) {
		$groups[] = array(
			'portafolio' => array(
				array(
					'id' => 'extra-data',
					'title' => 'Extra data',
					'fields' => array(
						array(
							'id' => 'url',
							'title' => 'URL',
							'type' => 'text'
						),
						array(
							'id' => 'featuring',
							'title' => 'Featuring',
							'type' => 'text'
						)
					)
				)
			),
			'equipo' => array(
				array(
					'id' => 'contact',
					'title' => 'Contacto',
					'fields' => array(
						array(
							'id' => 'contact_link',
							'title' => 'Enlaces',
							'type' => 'multiinput',
							'subfields' => array(
								array(
									'id' => 'link_type',
									'title' => 'Tipo de enlace',
									'desc' => 'Los tipos de enlace son: dribbble, behance, skype, flickr, vimeo, github, twitter o mail',
									'type' => 'text'
								),array(
									'id' => 'link_url',
									'title' => 'Enlace',
									'type' => 'text'
								)
							)
						)
					)
				)
			),
			'lab' => array(
				array(
					'id' => 'extra-data',
					'title' => 'Extra data',
					'fields' => array(
						array(
							'id' => 'url',
							'title' => 'URL',
							'type' => 'text'
						)
					)
				)
			),
		);

		return $groups;
	}

	function my_connection_types() {
		p2p_register_connection_type( array(
			'name' => 'project_team',
			'from' => 'portafolio',
			'to' => 'equipo'
		) );
	}

	function api_return_fields( $data, &$post, $state ) {
		if( $state === 'read' ){

			// Build images
			$featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
			// $today_thumbnail = wp_get_attachment_image_src( get_post_meta( $post->ID, '_today_thumbnail', true ) , 'full' );
			// $copy_image = wp_get_attachment_image_src( get_post_meta( $post->ID, '_today_copy', true ) , 'full' );

			// $data->meta->subtitle = get_post_meta( $post->ID, '_subtitle', true );
			// $data->meta->copy_image = $copy_image;
			// $data->meta->bg_color = get_post_meta( $post->ID, '_today_bg_color', true );
			// $data->meta->featured_image_url = $featured_image;
			// $data->meta->today_thumbnail_url = $today_thumbnail;

			// prev and next tests
			$this_post = new WP_Query( array( 'p' => $post->ID ) );
			$this_post->the_post();
			$data->meta->previous_post_link = get_previous_post_link( '%link', '%title', true );
			$data->meta->next_post_link = get_next_post_link( '%link', '%title', true );
			wp_reset_postdata();

			return $data;
		}
	}
}

add_action( 'plugins_loaded', array('Manos', 'setup') );
