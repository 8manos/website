<?php
class Manos {
	const version = '2014';

	function setup() {
		add_action( 'init', array( __CLASS__, 'manos_post_types' ) );
		add_action( 'init', array( __CLASS__, 'manos_taxonomies' ) );
		add_filter( 'kc_post_settings', array( __CLASS__, 'manos_custom_fields' ) );
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
}

add_action( 'plugins_loaded', array('Manos', 'setup') );
