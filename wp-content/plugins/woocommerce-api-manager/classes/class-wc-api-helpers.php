<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * WooCommerce API Manager Helpers Class
 *
 * @package Update API Manager/Helpers
 * @author Todd Lahman LLC
 * @copyright   Copyright (c) 2011-2013, Todd Lahman LLC
 * @since 1.0.0
 *
 */

class WC_Api_Manager_Helpers {

	public static $user_meta_key_orders 		= 'wc_am_orders';
	public static $user_meta_key_activations 	= 'wc_am_activations_';

	// For troubleshooting
	//exit( var_dump( $wpdb->last_query ) );

	/**
	 * order_complete function.
	 *
	 * Order is complete - update activation_limit if Software Add-on activated, and saves user_meta data
	 *
	 * Updates the number of activations per license key according to the API Manager settings
	 * Saves array of data for each order generated for a customer
	 *
	 */
	public static function order_complete( $order_id ) {
		global $wpdb;

		$order = new WC_Order( $order_id );

		if ( count( $order->get_items() ) > 0 ) {

			foreach ( $order->get_items() as $item ) {

				$item_product_id = ( isset( $item['product_id'] ) ) ? $item['product_id'] : $item['id'];

				$item_variation_id = ( isset( $item['variation_id'] ) ) ? $item['variation_id'] : $item['id'];

				// verify this is a variable product
				if ( $item_variation_id > 0 ) {

					$meta = get_post_custom( $item_product_id );

					$meta_var = get_post_custom( $item_variation_id );

					if ( $meta['_downloadable'][0] == 'yes' || $meta_var['_downloadable'][0] == 'yes' ) {

						$quantity = isset( $item['item_meta']['_qty'][0] ) ? absint( $item['item_meta']['_qty'][0] ) : 1;

						/**
						 * If the parent product has a tile else use the variable product title
						 */
						if ( isset( $meta_var['_api_software_title_parent'][0] ) && $meta_var['_api_software_title_parent'][0] != '' && ! empty( $meta_var['_api_software_title_parent'][0] ) ) {

							$software_title_parent = $meta_var['_api_software_title_parent'][0];
							$software_title = $meta_var['_api_software_title_parent'][0];

						} else if ( isset( $meta_var['_api_software_title_var'][0] ) && $meta_var['_api_software_title_var'][0] != '' && ! empty( $meta_var['_api_software_title_var'][0] ) ) {

							$software_title_var = $meta_var['_api_software_title_var'][0];
							$software_title = $meta_var['_api_software_title_var'][0];

						} else {

							$software_title = '';
						}

						if ( get_option( 'wc_api_manager_software_add_on_license_key' ) == 'yes' && self::is_plugin_active( 'woocommerce-software-add-on/woocommerce-software.php' ) ) {
							// Get the order_id
							$lic_order_id = $wpdb->get_var( $wpdb->prepare( "
								SELECT order_id FROM {$wpdb->prefix}woocommerce_software_licences
								WHERE order_id = %d
								LIMIT 1
							", $order_id ) );

							// verify the license has been created before continuing
							if ( $lic_order_id ) {

								for ( $i = 0; $i < $quantity; $i++ ) {

					                $data = array(
										'order_id' 				=> $order_id,
										'activations_limit'		=> empty( $meta_var['_api_activations'][0] ) ? '' : (int) $meta_var['_api_activations'][0],
										'software_title' 		=> empty( $software_title ) ? '' : (string) $software_title,
										'current_version'		=> empty( $meta_var['_api_new_version'][0] ) ? '' : (string) $meta_var['_api_new_version'][0],
							        );

									self::update_licence_key( $data );

									// Saves meta data for this order to be used by the API Manager if Software Add-on license is required
					                $meta_data =
					                		array( $order->order_key =>
					                			array(
								                	'user_id'						=> $order->user_id,
													'order_id' 						=> $order_id,
													'order_key' 					=> $order->order_key,
													'license_email' 				=> $order->billing_email,
													'_api_software_title_parent' 	=> empty( $software_title_parent ) ? '' : (string) $software_title_parent,
													'_api_software_title_var' 		=> empty( $software_title_var ) ? '' : (string) $software_title_var,
													'software_title' 				=> empty( $software_title ) ? '' : (string) $software_title,
													'parent_product_id'				=> empty( $item_product_id ) ? '' : (int) $item_product_id,
													'variable_product_id'			=> empty( $item_variation_id ) ? '' : (int) $item_variation_id,
													'current_version'				=> empty( $meta_var['_api_new_version'][0] ) ? '' : (string) $meta_var['_api_new_version'][0],
													'_api_activations'				=> empty( $meta_var['_api_activations'][0] ) ? '' : (int) $meta_var['_api_activations'][0],
													'_api_activations_parent'		=> '',
													'_api_update_permission'		=> 'yes',
													'is_variable_product'			=> 'yes',
													'license_type'					=> '',
													'expires'						=> '',
													)
					                			);

									self::save_order_complete_user_meta( $meta_data, $order->order_key );

								}
							}

						} else { // end if Software Add-on is activated

							for ( $i = 0; $i < $quantity; $i++ ) {

								// Saves meta data for this order to be used by the API Manager if order_key is required
				                $meta_data =
				                		array( $order->order_key =>
				                			array(
							                	'user_id'						=> $order->user_id,
												'order_id' 						=> $order_id,
												'order_key' 					=> $order->order_key,
												'license_email' 				=> $order->billing_email,
												'_api_software_title_parent' 	=> empty( $software_title_parent ) ? '' : (string) $software_title_parent,
												'_api_software_title_var' 		=> empty( $software_title_var ) ? '' : (string) $software_title_var,
												'software_title' 				=> empty( $software_title ) ? '' : (string) $software_title,
												'parent_product_id'				=> empty( $item_product_id ) ? '' : (int) $item_product_id,
												'variable_product_id'			=> empty( $item_variation_id ) ? '' : (int) $item_variation_id,
												'current_version'				=> empty( $meta_var['_api_new_version'][0] ) ? '' : (string) $meta_var['_api_new_version'][0],
												'_api_activations'				=> empty( $meta_var['_api_activations'][0] ) ? '' : (int) $meta_var['_api_activations'][0],
												'_api_activations_parent'		=> '',
												'_api_update_permission'		=> 'yes',
												'is_variable_product'			=> 'yes',
												'license_type'					=> '',
												'expires'						=> '',
												)
				                			);

								self::save_order_complete_user_meta( $meta_data, $order->order_key );

							}

						}

					} // end if is_software

				} else { // end if variable product

					// Start if simple product

					$meta = get_post_custom( $item_product_id );

					$meta_var = get_post_custom( $item_variation_id );

					if ( $meta['_downloadable'][0] == 'yes' || $meta_var['_downloadable'][0] == 'yes' ) {

						$quantity = isset( $item['item_meta']['_qty'][0] ) ? absint( $item['item_meta']['_qty'][0] ) : 1;

						/**
						 * If the parent product has a tile else use the variable product title
						 */
						if ( isset( $meta['_api_software_title_parent'][0] ) && $meta['_api_software_title_parent'][0] != '' && ! empty( $meta['_api_software_title_parent'][0] ) ) {

							$software_title_parent = $meta['_api_software_title_parent'][0];
							$software_title = $meta['_api_software_title_parent'][0];

						} else if ( isset( $meta_var['_api_software_title_var'][0] ) && $meta_var['_api_software_title_var'][0] != '' && ! empty( $meta_var['_api_software_title_var'][0] ) ) {

							$software_title_var = $meta_var['_api_software_title_var'][0];
							$software_title = $meta_var['_api_software_title_var'][0];

						} else {

							$software_title = '';
						}

						if ( get_option( 'wc_api_manager_software_add_on_license_key' ) == 'yes' && self::is_plugin_active( 'woocommerce-software-add-on/woocommerce-software.php' ) ) {
							// Get the order_id
							$lic_order_id = $wpdb->get_var( $wpdb->prepare( "
								SELECT order_id FROM {$wpdb->prefix}woocommerce_software_licences
								WHERE order_id = %d
								LIMIT 1
							", $order_id ) );

							// verify the license has been created before continuing
							if ( $lic_order_id ) {

								for ( $i = 0; $i < $quantity; $i++ ) {

					                $data = array(
										'order_id' 				=> $order_id,
										'activations_limit'		=> empty( $meta['_api_activations_parent'][0] ) ? '' : (int) $meta['_api_activations_parent'][0],
										'software_title' 		=> empty( $software_title ) ? '' : (string) $software_title,
										'current_version'		=> empty( $meta['_api_new_version'][0] ) ? '' : (string) $meta['_api_new_version'][0],
							        );

									self::update_licence_key( $data );

									// Saves meta data for this order to be used by the API Manager if Software Add-on license is required
					                $meta_data =
					                		array( $order->order_key =>
					                			array(
								                	'user_id'						=> $order->user_id,
													'order_id' 						=> $order_id,
													'order_key' 					=> $order->order_key,
													'license_email' 				=> $order->billing_email,
													'_api_software_title_parent' 	=> empty( $software_title_parent ) ? '' : (string) $software_title_parent,
													'_api_software_title_var' 		=> empty( $software_title_var ) ? '' : (string) $software_title_var,
													'software_title' 				=> empty( $software_title ) ? '' : (string) $software_title,
													'parent_product_id'				=> empty( $item_product_id ) ? '' : (int) $item_product_id,
													'variable_product_id'			=> empty( $item_variation_id ) ? '' : (int) $item_variation_id,
													'current_version'				=> empty( $meta['_api_new_version'][0] ) ? '' : (string) $meta['_api_new_version'][0],
													'_api_activations'				=> '',
													'_api_activations_parent'		=> empty( $meta['_api_activations_parent'][0] ) ? '' : (int) $meta['_api_activations_parent'][0],
													'_api_update_permission'		=> 'yes',
													'is_variable_product'			=> 'no',
													'license_type'					=> '',
													'expires'						=> '',
													)
					                			);

									self::save_order_complete_user_meta( $meta_data, $order->order_key );

								}
							}

						} else { // end if Software Add-on is activated

							for ( $i = 0; $i < $quantity; $i++ ) {

								// Saves meta data for this order to be used by the API Manager if order_key is required
				                $meta_data =
				                		array( $order->order_key =>
				                			array(
							                	'user_id'						=> $order->user_id,
												'order_id' 						=> $order_id,
												'order_key' 					=> $order->order_key,
												'license_email' 				=> $order->billing_email,
												'_api_software_title_parent' 	=> empty( $software_title_parent ) ? '' : (string) $software_title_parent,
												'_api_software_title_var' 		=> empty( $software_title_var ) ? '' : (string) $software_title_var,
												'software_title' 				=> empty( $software_title ) ? '' : (string) $software_title,
												'parent_product_id'				=> empty( $item_product_id ) ? '' : (int) $item_product_id,
												'variable_product_id'			=> empty( $item_variation_id ) ? '' : (int) $item_variation_id,
												'current_version'				=> empty( $meta['_api_new_version'][0] ) ? '' : (string) $meta['_api_new_version'][0],
												'_api_activations'				=> '',
												'_api_activations_parent'		=> empty( $meta['_api_activations_parent'][0] ) ? '' : (int) $meta['_api_activations_parent'][0],
												'_api_update_permission'		=> 'yes',
												'is_variable_product'			=> 'no',
												'license_type'					=> '',
												'expires'						=> '',
												)
				                			);

								self::save_order_complete_user_meta( $meta_data, $order->order_key );

							}

						}

					} // end if is_software

				} // end if is not a variable product

			}

		}

	}

	/**
	 * update_licence_key function.
	 *
	 * Updates the number of activations per license key according to the API Manager settings
	 *
	 * @access public
	 * @return void
	 */
	public static function update_licence_key( $data ) {
		global $wpdb;

		$order_id = $data['order_id'];
		$activations = $data['activations_limit'];
		$product_id = $data['software_title'];
		$software_version = $data['current_version'];

		$sql =
			"
			UPDATE {$wpdb->prefix}woocommerce_software_licences
			SET activations_limit = %d,
			software_product_id = %s,
			software_version = %s
			WHERE order_id = %d
			LIMIT 1
		";

		$wpdb->query( $wpdb->prepare( $sql, $activations, $product_id, $software_version, $order_id ) );
	}

	/**
	 * update_licence_key function.
	 *
	 * Adds user_meta info for download permissions query to the database
	 *
	 * @access public
	 * @return void
	 */
	public static function save_order_complete_user_meta( $data, $order_key ) {
		global $wpdb;

		$current_info = self::get_users_data( $data[$order_key]['user_id'] );

		$new_info = self::array_merge_recursive_associative( $data, $current_info );

		update_user_meta( $data[$order_key]['user_id'], $wpdb->get_blog_prefix() . self::$user_meta_key_orders, $new_info );

	}

	/**
	 * Gets the user order info stored in an array for the $user_id, aka $object_id
	 *
	 * @since 1.1
	 * @param $user_id int
	 * @return array
	 */
	public static function get_users_data( $user_id = 0 ) {
		global $wpdb;

		if ( $user_id === 0 ) {
			$data = array();
			return $data;
		}

		$data = get_metadata( 'user', $user_id, $wpdb->get_blog_prefix() . self::$user_meta_key_orders, true );

		if( empty( $data ) )
			$data = array();

		return $data;
	}

	/**
	 * Gets the order info from the postmeta table with the $post_id, aka $object_id
	 *
	 * @since 1.1
	 * @param $user_id int
	 * @return array
	 */
	public function get_postmeta_data( $post_id = 0 ) {
		global $wpdb;

		if ( $post_id === 0 ) {
			$data = array();
			return $data;
		}

		$data = get_metadata( 'post', $post_id, '', true );

		if( empty( $data ) )
			$data = array();

		return $data;
	}

	/**
	 * Gets the user order info stored in an array for the $user_id, aka $object_id
	 *
	 * @since 1.1
	 * @param $user_id int
	 * @return array
	 */
	public function get_users_activation_data( $user_id = 0, $order_key = 0 ) {
		global $wpdb;

		if ( $user_id === 0 ) {
			$data = array();
			return $data;
		}

		if ( $order_key === 0 ) {
			$data = array();
			return $data;
		}

		$data = get_metadata( 'user', $user_id, $wpdb->get_blog_prefix() . self::$user_meta_key_activations . $order_key, true );

		if( empty( $data ) )
			$data = array();

		return $data;
	}

	/**
	 * renew_subscription_order_complete function.
	 *
	 * Creates license key for renewed subscription orders.
	 *
	 * @access public
	 * @return void
	 */
	public static function renew_subscription_order_complete( $order_id ) {
		global $wpdb;

		$order = new WC_Order( $order_id );

		if ( count( $order->get_items() ) > 0 ) {

			foreach ( $order->get_items() as $item ) {

				$item_product_id = ( isset( $item['product_id'] ) ) ? $item['product_id'] : $item['id'];

				if ( $item_product_id > 0 ) {

					$meta = get_post_custom( $item_product_id );

					$quantity = isset( $item['item_meta']['_qty'][0] ) ? absint( $item['item_meta']['_qty'][0] ) : 1;

					for ( $i = 0; $i < $quantity; $i++ ) {
		                $data = array(
							'order_id' 				=> $order_id,
							'activation_email'		=> $order->billing_email,
							'prefix'				=> empty( $meta['_software_license_key_prefix'][0] ) ? '' : $meta['_software_license_key_prefix'][0],
							'software_product_id'	=> empty( $meta['_software_product_id'][0] ) ? '' : $meta['_software_product_id'][0],
							'software_version'		=> empty( $meta['_software_version'][0] ) ? '' : $meta['_software_version'][0],
							'activations_limit'		=> empty( $meta['_software_activations'][0] ) ? '' : (int) $meta['_software_activations'][0],
				        );

						$key_id = self::save_licence_key( $data );
					}

				}

			}

		}

		update_post_meta( $order_id,  'software_processed', 1);

	}

	/**
	 * save_licence_key function.
	 *
	 * @access public
	 * @return void
	 */
	function save_licence_key( $data ) {
		global $wpdb;

		$defaults = array(
			'order_id' 				=> '',
			'activation_email' 		=> '',
			'prefix'				=> '',
			'licence_key' 			=> self::generate_licence_key(),
			'software_product_id' 	=> '',
			'software_version'		=> '',
			'activations_limit'		=> '',
			'created'				=> current_time( 'mysql' )
		);

		$data = wp_parse_args( $data, $defaults  );

		$insert = array(
			'order_id' 				=> $data['order_id'],
			'activation_email'		=> $data['activation_email'],
			'licence_key'			=> $data['prefix'] . $data['licence_key'],
			'software_product_id'	=> $data['software_product_id'],
			'software_version'		=> $data['software_version'],
			'activations_limit'		=> $data['activations_limit'],
			'created'				=> $data['created']
        );

        $format = array(
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s'
        );

        $wpdb->insert( $wpdb->prefix . 'woocommerce_software_licences',
            $insert,
            $format
        );

		return $wpdb->insert_id;
	}

	/**
	 * generates a unique id that is used as the license code
	 *
	 * @since 1.0
	 * @return string the unique ID
	 */
	function generate_licence_key() {

		return sprintf(
			'%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
			mt_rand( 0, 0x0fff ) | 0x4000,
			mt_rand( 0, 0x3fff ) | 0x8000,
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
		);
	}

	/**
	 * Find post_id that matches api_key and activation_email
	 *
	 * @access public
	 * @param mixed license_key
	 * @param mixed activation_email
	 * @return integer $post_id which is the same as the product_id
	 */
	public function get_post_id( $api_key, $activation_email ) {
		global $wpdb;

		$order_id = $wpdb->get_var( $wpdb->prepare( "
			SELECT order_id FROM {$wpdb->prefix}woocommerce_software_licences
			WHERE licence_key = %s
			AND activation_email = %s LIMIT 1
		", $api_key, $activation_email ) );

		$post_id = $wpdb->get_var( $wpdb->prepare( "
			SELECT product_id FROM {$wpdb->prefix}woocommerce_downloadable_product_permissions
			WHERE order_id = %s
			LIMIT 1
		", $order_id ) );

		if ( ! $post_id ) return false;

		return $post_id;
	}

	/**
	 * Find order_id that matches api_key and activation_email
	 *
	 * @access public
	 * @param mixed license_key
	 * @param mixed activation_email
	 * @return integer $order_id
	 */
	public function get_order_id( $api_key, $activation_email ) {
		global $wpdb;

		$order_id = $wpdb->get_var( $wpdb->prepare( "
			SELECT order_id FROM {$wpdb->prefix}woocommerce_software_licences
			WHERE licence_key = %s
			AND activation_email = %s LIMIT 1
		", $api_key, $activation_email ) );

		if ( ! $order_id ) return false;

		return $order_id;
	}

	/**
	 * Find post_id which can match a purchase ID or a product ID
	 *
	 * @access public
	 * @param mixed meta_key
	 * @param mixed meta_value
	 * @return integer $post_id which is the same as the product_id
	 */
	public function get_post_id_by( $meta_key, $meta_value ) {
		global $wpdb;

		$post_id = $wpdb->get_var( $wpdb->prepare( "
			SELECT post_id FROM {$wpdb->postmeta}
			WHERE meta_key = %s
			AND meta_value = %s LIMIT 1
		", $meta_key, $meta_value ) );

		if ( ! $post_id ) return false;

		return $post_id;
	}

	/**
	 * Find file download id (am_key)
	 *
	 * @access public
	 * @param $post_id (integer)
	 * @return array $download_id (limited to first value in array)
	 */
	public function get_download_id( $post_id ) {

		$file_path = get_post_meta( $post_id, '_file_paths', true );

		if ( is_array( $file_path ) ) {
			foreach ( $file_path as $key => $value ) {
				$path[] = $key;
			}
		}

		if ( ! $path[0] ) return false;

		return $path[0];
	}

	/**
	 * Find file download path, or download link, usually the same value
	 *
	 * @access public
	 * @param mixed $software_product_id, i.e. Simple Comments, or  $post->ID if Software add-on not installed
	 * @return array $file_path (limited to first value in array)
	 */
	public function get_file_path( $software_product_id, $software_add_on = true ) {

		if ( $software_add_on ) { // use the product id, i.e. Simple Comments
			$product_id = self::get_post_id( $software_product_id );
			if ( ! $product_id ) return false;

			$file_path = get_post_meta( $product_id, '_file_paths', true );
		} else { // use post->ID
			$file_path = get_post_meta( $software_product_id, '_file_paths', true );
		}

		if ( is_array( $file_path ) ) {
			foreach ( $file_path as $key => $value ) {
				$path[] = $value;
			}
		}

		if ( ! $path[0] ) return false;

		return $path[0];
	}

	/**
	 * verify_licence_key function.
	 *
	 * @access public
	 * @param mixed $api_key
	 * @param mixed $activation_email
	 * @return bool
	 */
	public function verify_license_key( $api_key, $activation_email ) {
    	global $wpdb;

		$sql =
			"
			SELECT licence_key FROM {$wpdb->prefix}woocommerce_software_licences
			WHERE licence_key = %s
			AND activation_email = %s
			LIMIT 1
			";

		$key = $wpdb->get_var( $wpdb->prepare( $sql, $api_key, $activation_email ) );

		if ( $key == $api_key )
			return true;

		return false;
	}

	/**
	 * get_order_number function.
	 *
	 * @access public
	 * @param mixed $api_key
	 * @param mixed $activation_email
	 * @return string
	 */
	public function get_order_number( $api_key, $activation_email ) {
    	global $wpdb;

		$order_id = $wpdb->get_var( $wpdb->prepare( "
			SELECT order_id FROM {$wpdb->prefix}woocommerce_software_licences
			WHERE licence_key = %s
			AND activation_email = %s
			LIMIT 1
		", $api_key, $activation_email ) );

		$order_number = $wpdb->get_var( $wpdb->prepare( "
			SELECT meta_value FROM {$wpdb->postmeta}
			WHERE post_id = %s
			AND meta_key = '_order_key'
			LIMIT 1
		", $order_id ) );

		return $order_number;
	}

	/**
	 * download_count function.
	 *
	 * @access public
	 * @param mixed $order_id
	 * @param mixed $order_key
	 * @return integer or boolean
	 */
	public function get_download_count( $order_id, $order_key ) {
    	global $wpdb;

		$download_count = $wpdb->get_var( $wpdb->prepare( "
			SELECT download_count FROM {$wpdb->prefix}woocommerce_downloadable_product_permissions
			WHERE order_id = %s
			AND order_key = %s
			LIMIT 1
		", $order_id, $order_key ) );

		if ( isset( $download_count ) )
			return $download_count;

		return false;
	}

	/**
	 * create_url function to properly format download url
	 *
	 * @access public
	 * @param mixed $api_key
	 * @param mixed $activation_email
	 * @param mixed $product_id
	 * @return url + query string
	 */
	public function create_url( $api_key, $activation_email, $product_id, $download_id = '' ) {
		global $wpdb;

		if ( stristr( $api_key, 'order_') ) {

			$order_key = $api_key;

		} else if ( self::is_plugin_active( 'woocommerce-software-add-on/woocommerce-software.php' ) ) {

			$order_key = self::get_order_number( $api_key, $activation_email );

		}

		$sql = "
			SELECT product_id,order_id,downloads_remaining,user_id,download_count,access_expires,download_id
			FROM {$wpdb->prefix}woocommerce_downloadable_product_permissions
			WHERE user_email = %s
			AND order_key = %s
			AND product_id = %s";

		$args = array(
			$activation_email,
			$order_key,
			$product_id
		);

		if ( $download_id ) {
			// backwards compatibility for existing download URLs
			$sql .= " AND download_id = %s";
			$args[] = $download_id;
		}

		//$result = $wpdb->get_row( $wpdb->prepare( $sql, $args ), ARRAY_A );
		$result = $wpdb->get_row( $wpdb->prepare( $sql, $args ) );

		//$result = get_object_vars( $download_result );

		/**
		 * Since adding the ARRAY_A flag an associative array is returned rather than an object
		 *
		 * 	$download_result returns:
		 * stdClass Object
		 *	(
		 *		[product_id] =>
		 *		[order_id] =>
		 *		[downloads_remaining] =>
		 *		[user_id] =>
		 *		[download_count] =>
		 *		[access_expires] =>
		 *		[download_id] =>
		 *	)
		 *	get_object_vars() puts oject into an array
		 */

		$url_args = array(
			'am_download_file' 	=> $result->product_id,
			'am_order' 			=> $order_key,
			'am_email' 			=> $activation_email,
		);

		if ( $result->download_id != '' ) {
			$url_args['am_key'] = $result->download_id;
		}

		return site_url() . '/?' . http_build_query( $url_args, '', '&' );
	}

	/**
	 * Checks if a plugin is activated
	 *
	 * @since 1.1
	 */
	public static function is_plugin_active( $slug ) {
		$active_plugins = (array) get_option( 'active_plugins', array() );

		if ( is_multisite() )
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );

		return in_array( $slug, $active_plugins ) || array_key_exists( $slug, $active_plugins );
	}

	/**
	 * Checks if page content exists
	 *
	 * @since 1.1
	 */
	public function get_page_content( $page_obj ) {

		if ( isset( $page_obj  ) && is_object( $page_obj ) ) {

			if ( ! empty( $page_obj->post_content ) ) {

				return $page_obj->post_content;

			} else {

				return '';

			}

		} else {

			return '';

		}

	}

	/**
	 * Merges arrays recursively with an associative key
	 * To merge arrays that are numerically indexed use the PHP array_merge_recursive() function
	 *
	 * @since 1.1
	 * @param $array1 Array
	 * @param $array2 Array
	 * @return array
	 */
	public static function array_merge_recursive_associative( $array1, $array2 ) {

		$merged_arrays = $array1;

		if ( is_array( $array2 ) ) {
			foreach ( $array2 as $key => $val ) {
				if ( is_array( $array2[$key] ) ) {
					$merged_arrays[$key] = ( isset( $merged_arrays[$key] ) && is_array( $merged_arrays[$key] ) ) ? self::array_merge_recursive_associative( $merged_arrays[$key], $array2[$key] ) : $array2[$key];
				} else {
					$merged_arrays[$key] = $val;
				}
			}
		}

		return $merged_arrays;
	}

	/**
	 * array_merge_recursive does indeed merge arrays, but it converts values with duplicate
	 * keys to arrays rather than overwriting the value in the first array with the duplicate
	 * value in the second array, as array_merge does. I.e., with array_merge_recursive,
	 * this happens (documented behavior):
	 *
	 * array_merge_recursive(array('key' => 'org value'), array('key' => 'new value'));
	 *     => array('key' => array('org value', 'new value'));
	 *
	 * array_merge_recursive_distinct does not change the datatypes of the values in the arrays.
	 * Matching keys' values in the second array overwrite those in the first array, as is the
	 * case with array_merge, i.e.:
	 *
	 * array_merge_recursive_distinct(array('key' => 'org value'), array('key' => 'new value'));
	 *     => array('key' => array('new value'));
	 *
	 * Parameters are passed by reference, though only for performance reasons. They're not
	 * altered by this function.
	 *
	 * @param array $array1
	 * @param array $array2
	 * @return array
	 * @author Daniel <daniel (at) danielsmedegaardbuus (dot) dk>
	 * @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com>
	 * @since 1.1.1
	 */
	public function array_merge_recursive_distinct ( array &$array1, array &$array2 ) {
		$merged = $array1;

		foreach ( $array2 as $key => &$value ) {
			if ( is_array ( $value ) && isset ( $merged [$key] ) && is_array ( $merged [$key] ) ) {
				$merged [$key] = array_merge_recursive_distinct ( $merged [$key], $value );
			} else {
				$merged [$key] = $value;
			}
		}

		return $merged;
	}

	/**
	 * Experimental
	 * @since 1.1.1
	 * @param  [type]  $needle   [description]
	 * @param  [type]  $haystack [description]
	 * @param  boolean $strict   [description]
	 * @return [type]            [description]
	 */
	public function in_array_recursive( $needle, $haystack, $strict = false ) {

		foreach ( $haystack as $item ) {

			if ( ( $strict ? $item === $needle : $item == $needle ) || ( is_array( $item ) && self::in_array_recursive( $needle, $item, $strict ) ) ) {

				return true;

			}

		}

		return false;
	}

	/**
	 * Removes element from array based on key
	 * @since 1.1
	 * @return array New array minus removed elements
	 *
	 * For example:
	 *
	 * $fruit_inventory = array(
	 *	  'apples' => 52,
	 *	  'bananas' => 78,
	 *	  'peaches' => 'out of season',
	 *	  'pears' => 'out of season',
	 *	  'oranges' => 'no longer sold',
	 *	  'carrots' => 15,
	 *	  'beets' => 15,
	 *	);
	 *
	 * $fruit_inventory = array_remove_by_key($fruit_inventory,
     *                              "beets",
     *                              "carrots");
	 */
	public function array_remove_by_key() {

		$args  = func_get_args();

		return array_diff_key( $args[0], array_flip( array_slice( $args, 1 ) ) );

	}

	/**
	 * Removes element from array based on value
	 * @since 1.1
	 * @return array New array minus removed elements
	 * For example:
	 *
	 * $fruit_inventory = array(
	 *	  'apples' => 52,
	 *	  'bananas' => 78,
	 *	  'peaches' => 'out of season',
	 *	  'pears' => 'out of season',
	 *	  'oranges' => 'no longer sold',
	 *	  'carrots' => 15,
	 *	  'beets' => 15,
	 *	);
	 *
	 * $fruit_inventory = array_remove_by_value($fruit_inventory,
     *                                 "out of season",
     *                                 "no longer sold");
	 */
	public function array_remove_by_value() {

		$args = func_get_args();

		return array_diff( $args[0], array_slice( $args, 1 ) );

	}

	/**
	 * array_search_multi Finds if a value matched with a needle exists in a multidimensional array
	 * @param  array $array  multidimensional array (for simple array use array_search)
	 * @param  mixed $value  value to search for
	 * @param  mixed $needle needle that needs to match value in array
	 * @since 1.1.1
	 * @return boolean
	 */
	public function array_search_multi( $array, $value, $needle ) {

		foreach( $array as $index_key => $value_key ) {

			if ( $value_key[$value] === $needle ) return true;

		}

		return false;
	}

	/**
	 * get_array_search_multi Finds a key for a value matched by a needle in a multidimensional array
	 * @param  array $array  multidimensional array (for simple array use array_search)
	 * @param  mixed $value  value to search for
	 * @param  mixed $needle needle that needs to match value in array
	 * @since 1.1.1
	 * @return mixed
	 */
	public function get_key_array_search_multi( $array, $value, $needle ) {

		foreach( $array as $index_key => $value_key ) {

			if ( $value_key[$value] === $needle ) return $value_key;

		}

		return false;
	}

	/**
	 * Gets the status of a checkbox
	 *
	 * @since 1.1
	 * @param $product_id int
	 * @param $meta_key string
	 * @return boolean
	 */
	public function get_product_checkbox_status( $post_id, $meta_key ) {

		$result = get_post_meta( $post_id, $meta_key, true );

		if ( $result == 'yes' )
			return true;
		else
			return false;

	}

	/**
	 * get_order_info_by_email_with_order_key Gets the user order info
	 * @param  string $activation_email license email
	 * @param  string $order_key        order key
	 * @return array                    array populated with user purchase info
	 */
	public function get_order_info_by_email_with_order_key( $activation_email, $order_key ) {

		if ( isset( $activation_email ) )
			$user = get_user_by( 'email', $activation_email ); // returns $user->ID
		else
			return false;

		if ( ! is_object( $user ) ) return false;

		// Check if this is an order_key
		if ( isset( $order_key ) && stristr( $order_key, 'order_') ) {

			$user_orders = self::get_users_data( $user->ID );

			if ( isset( $user_orders ) && ! empty( $user_orders ) )
				return $user_orders[$order_key]; // returns a single order info array identified by order_key
			else
				return false;

		}

		return false;

	}

	/**
	 * Determine subscription status using order_id = post_id
	 *
	 * For Suscriptions > 1.4
	 *
	 * @access public
	 * @param int order_id
	 * @since 1.1.1
	 * @return string subscription status
	 */
	public function get_subscription_status( $order_id ) {
		global $wpdb;

		$order_item_id = $wpdb->get_var( $wpdb->prepare( "
			SELECT order_item_id FROM {$wpdb->prefix}woocommerce_order_items
			WHERE order_id = %d
			LIMIT 1
		", $order_id ) );

		$status = $wpdb->get_var( $wpdb->prepare( "
			SELECT meta_value FROM {$wpdb->prefix}woocommerce_order_itemmeta
			WHERE order_item_id = %d
			AND meta_key = %s LIMIT 1
		", $order_item_id, '_subscription_status' ) );

		if ( isset( $status ) && ! empty( $status ) )
			return $status;
		else
			return false;

	}

	/**
	 * Nonce URL
	 * @param  mixed $args string or array
	 * @see http://codex.wordpress.org/Function_Reference/add_query_arg
	 * @return string
	 * @since 1.2.1
	 */
	public function nonce_url( $args ) {

		$action_url = wp_nonce_url( add_query_arg( $args ) );

		return $action_url;

	}

	/**
	 * Finds the default order_ prefix or a unique prefix that was created
	 * by the woocommerce_generate_order_key filter
	 *
	 * example $uniqid = $this->get_uniqid_prefix( $product_post_meta['_order_key'][0], '_' );
	 *
	 * @access public
	 * @param  mixed $haystack
	 * @param  mixed $needle
	 * @return mixed
	 */
	public function get_uniqid_prefix( $haystack, $needle ) {

		$pos = stripos( $haystack, $needle ) + 1;

		return trim( substr( $haystack, 0, $pos ) );

	}

	/**
	 * Allows the customer to delete a domain name activated for an order on their My Account dashbaord
	 * @return void or error message
	 * @since 1.2.1
	 */
	public static function delete_my_account_url() {
		global $woocommerce, $wpdb, $wc_api_manager_helpers, $woocommerce_plugin_update_api_manager;

		if ( isset( $_GET['domain'] ) && isset( $_GET['instance'] ) && isset( $_GET['order_key'] ) && isset( $_GET['user_id'] ) && isset( $_GET['_wpnonce'] ) ) {

			if ( wp_verify_nonce( $_GET['_wpnonce'] ) === false )
				$woocommerce->add_error( __( 'The domain name could not be deleted.', $woocommerce_plugin_update_api_manager->text_domain ) );

			$domain 	= sanitize_text_field( $_GET['domain'] );
			$instance 	= sanitize_text_field( $_GET['instance'] );
			$order_key 	= sanitize_text_field( $_GET['order_key'] );
			$user_id 	= intval( $_GET['user_id'] );

			$current_info = self::get_users_activation_data( $user_id, $order_key );

	    	if ( ! empty( $current_info ) ) {

				$active_activations = 0;

		    	foreach ( $current_info as $key => $activations ) {

		    		if ( $activations['activation_active'] == 1 && $order_key == $activations['order_key'] ) {

						$active_activations++;

		    		}

		    	}

	    		foreach ( $current_info as $key => $activation_info ) {

	    			if ( $active_activations <= 1 && $activation_info['activation_active'] == 1 && $activation_info['instance'] == $instance && $activation_info['activation_domain'] == $domain ) {

						delete_user_meta( $user_id, $wpdb->get_blog_prefix() . self::$user_meta_key_activations . $order_key );

						wp_safe_redirect( get_permalink( woocommerce_get_page_id( 'myaccount' ) ) );

						break;

						exit();

					} else if ( $activation_info['activation_active'] == 1 && $activation_info['activation_active'] == 1 && $activation_info['instance'] == $instance && $activation_info['activation_domain'] == $domain ) {

						// Delete the activation data array
		    			unset( $current_info[$key] );

			    		// Re-index the numerical array keys:
						$new_info = array_values( $current_info );

						update_user_meta( $user_id, $wpdb->get_blog_prefix() . self::$user_meta_key_activations . $order_key, $new_info );

						wp_safe_redirect( get_permalink( woocommerce_get_page_id( 'myaccount' ) ) );

						break;

						exit();

					}

	    		} // end foreach

			}

		}

	}

	/**
	 * Download a file - hook into init function.
	 * variation of woocommerce_download_product() function
	 *
	 * No login required for Plugin Updates since authentication is handled by the APIs
	 * Download restrictions controlled by WooCommerce order screen
	 *
	 * @access public
	 * @return void
	 */
	public static function download_product() {
		global $woocommerce_plugin_update_api_manager;

		if ( isset( $_GET['am_download_file'] ) && isset( $_GET['am_order'] ) && isset( $_GET['am_email'] ) ) {

			global $wpdb, $is_IE;

			$product_id           = (int) urldecode($_GET['am_download_file']);
			$order_key            = urldecode( $_GET['am_order'] );
			$email                = sanitize_email( str_replace( ' ', '+', urldecode( $_GET['am_email'] ) ) );
			$download_id          = isset( $_GET['am_key'] ) ? preg_replace( '/\s+/', ' ', urldecode( $_GET['am_key'] ) ) : '';
			$_product             = get_product( $product_id );
			$file_download_method = apply_filters( 'woocommerce_file_download_method', get_option( 'woocommerce_file_download_method' ), $product_id );

			if ( ! is_email( $email) )
				wp_die( __( 'Invalid email address.', $woocommerce_plugin_update_api_manager->text_domain ) . ' <a href="' . home_url() . '">' . __( 'Go to homepage &rarr;', $woocommerce_plugin_update_api_manager->text_domain ) . '</a>' );

			$query = "
				SELECT order_id,downloads_remaining,user_id,download_count,access_expires,download_id
				FROM " . $wpdb->prefix . "woocommerce_downloadable_product_permissions
				WHERE user_email = %s
				AND order_key = %s
				AND product_id = %s";
			$args = array(
				$email,
				$order_key,
				$product_id
			);

			if ( $download_id ) {
				// backwards compatibility for existing download URLs
				$query .= " AND download_id = %s";
				$args[] = $download_id;
			}

			$download_result = $wpdb->get_row( $wpdb->prepare( $query, $args ) );

			if ( ! $download_result )
				wp_die( __( 'Invalid download.', $woocommerce_plugin_update_api_manager->text_domain ) . ' <a href="'.home_url().'">' . __( 'Go to homepage &rarr;', $woocommerce_plugin_update_api_manager->text_domain ) . '</a>' );

			$download_id 			= $download_result->download_id;
			$order_id 				= $download_result->order_id;
			$downloads_remaining 	= $download_result->downloads_remaining;
			$download_count 		= $download_result->download_count;
			$user_id 				= $download_result->user_id;
			$access_expires 		= $download_result->access_expires;

			// if ( $user_id && get_option( 'woocommerce_downloads_require_login' ) == 'yes' ) {

			// 	if ( ! is_user_logged_in() )
			// 		wp_die( __( 'You must be logged in to download files.', $woocommerce_plugin_update_api_manager->text_domain ) . ' <a href="' . wp_login_url( get_permalink( woocommerce_get_page_id( 'myaccount' ) ) ) . '">' . __( 'Login &rarr;', $woocommerce_plugin_update_api_manager->text_domain ) . '</a>' );

			// 	elseif ( $user_id != get_current_user_id() )
			// 		wp_die( __( 'This is not your download link.', $woocommerce_plugin_update_api_manager->text_domain ) );

			// }

			if ( ! get_post( $product_id ) )
				wp_die( __( 'Product no longer exists.', $woocommerce_plugin_update_api_manager->text_domain ) . ' <a href="' . home_url() . '">' . __( 'Go to homepage &rarr;', $woocommerce_plugin_update_api_manager->text_domain ) . '</a>' );

			if ( $order_id ) {
				$order = new WC_Order( $order_id );

				if ( ! $order->is_download_permitted() || $order->post_status != 'publish' )
					wp_die( __( 'Invalid order.', $woocommerce_plugin_update_api_manager->text_domain ) . ' <a href="' . home_url() . '">' . __( 'Go to homepage &rarr;', $woocommerce_plugin_update_api_manager->text_domain ) . '</a>' );
			}

			if ( $downloads_remaining == '0' )
				wp_die( __( 'Sorry, you have reached your download limit for this file', $woocommerce_plugin_update_api_manager->text_domain ) . ' <a href="'.home_url().'">' . __( 'Go to homepage &rarr;', $woocommerce_plugin_update_api_manager->text_domain ) . '</a>' );

			if ( $access_expires > 0 && strtotime( $access_expires) < current_time( 'timestamp' ) )
				wp_die( __( 'Sorry, this download has expired', $woocommerce_plugin_update_api_manager->text_domain ) . ' <a href="' . home_url() . '">' . __( 'Go to homepage &rarr;', $woocommerce_plugin_update_api_manager->text_domain ) . '</a>' );

			if ( $downloads_remaining > 0 ) {
				$wpdb->update( $wpdb->prefix . "woocommerce_downloadable_product_permissions", array(
					'downloads_remaining' => $downloads_remaining - 1,
				), array(
					'user_email' 	=> $email,
					'order_key' 	=> $order_key,
					'product_id' 	=> $product_id,
					'download_id' 	=> $download_id
				), array( '%d' ), array( '%s', '%s', '%d', '%s' ) );
			}

			// Count the download
			$wpdb->update( $wpdb->prefix . "woocommerce_downloadable_product_permissions", array(
				'download_count' => $download_count + 1,
			), array(
				'user_email' 	=> $email,
				'order_key' 	=> $order_key,
				'product_id' 	=> $product_id,
				'download_id' 	=> $download_id
			), array( '%d' ), array( '%s', '%s', '%d', '%s' ) );

			// Trigger action
			do_action( 'download_product', $email, $order_key, $product_id, $user_id, $download_id, $order_id );

			// Get the download URL and try to replace the url with a path
			$file_path = $_product->get_file_download_path( $download_id );

			if ( ! $file_path )
				wp_die( __( 'No file defined', $woocommerce_plugin_update_api_manager->text_domain ) . ' <a href="'.home_url().'">' . __( 'Go to homepage &rarr;', $woocommerce_plugin_update_api_manager->text_domain ) . '</a>' );

			// Redirect to the file...
			if ( $file_download_method == "redirect" ) {
				header( 'Location: ' . $file_path );
				exit;
			}

			// ...or serve it
			if ( ! is_multisite() ) {

				/*
				 * Download file may be either http or https.
				 * site_url() depends on whether the page containing the download (ie; My Account) is served via SSL because WC
				 * modifies site_url() via a filter to force_ssl.
				 * So blindly doing a str_replace is incorrect because it will fail when schemes are mismatched. This code
				 * handles the various permutations.
				 */
				$scheme = parse_url( $file_path, PHP_URL_SCHEME );

				if ( $scheme ) {
					$site_url = set_url_scheme( site_url( '' ), $scheme );
				} else {
					$site_url = is_ssl() ? str_replace( 'https:', 'http:', site_url() ) : site_url();
				}

				$file_path   = str_replace( trailingslashit( $site_url ), ABSPATH, $file_path );

			} else {

				$network_url = is_ssl() ? str_replace( 'https:', 'http:', network_admin_url() ) : network_admin_url();
				$upload_dir  = wp_upload_dir();

				// Try to replace network url
				$file_path   = str_replace( trailingslashit( $network_url ), ABSPATH, $file_path );

				// Now try to replace upload URL
				$file_path   = str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $file_path );
			}

			// See if its local or remote
			if ( strstr( $file_path, 'http:' ) || strstr( $file_path, 'https:' ) || strstr( $file_path, 'ftp:' ) ) {
				$remote_file = true;
			} else {
				$remote_file = false;

				// Remove Query String
				if ( strstr( $file_path, '?' ) )
					$file_path = current( explode( '?', $file_path ) );

				$file_path   = realpath( $file_path );
			}

			$file_extension  = strtolower( substr( strrchr( $file_path, "." ), 1 ) );
			$ctype           = "application/force-download";

			foreach ( get_allowed_mime_types() as $mime => $type ) {
				$mimes = explode( '|', $mime );
				if ( in_array( $file_extension, $mimes ) ) {
					$ctype = $type;
					break;
				}
			}

			// Start setting headers
			if ( ! ini_get('safe_mode') )
				@set_time_limit(0);

			if ( function_exists( 'get_magic_quotes_runtime' ) && get_magic_quotes_runtime() )
				@set_magic_quotes_runtime(0);

			if( function_exists( 'apache_setenv' ) )
				@apache_setenv( 'no-gzip', 1 );

			@session_write_close();
			@ini_set( 'zlib.output_compression', 'Off' );
			@ob_end_clean();

			if ( ob_get_level() )
				@ob_end_clean(); // Zip corruption fix

			if ( $is_IE && is_ssl() ) {
				// IE bug prevents download via SSL when Cache Control and Pragma no-cache headers set.
				header( 'Expires: Wed, 11 Jan 1984 05:00:00 GMT' );
				header( 'Cache-Control: private' );
			} else {
				nocache_headers();
			}

			$file_name = basename( $file_path );

			if ( strstr( $file_name, '?' ) )
				$file_name = current( explode( '?', $file_name ) );

			header( "Robots: none" );
			header( "Content-Type: " . $ctype );
			header( "Content-Description: File Transfer" );
			header( "Content-Disposition: attachment; filename=\"" . $file_name . "\";" );
			header( "Content-Transfer-Encoding: binary" );

	        if ( $size = @filesize( $file_path ) )
	        	header( "Content-Length: " . $size );

			if ( $file_download_method == 'xsendfile' ) {

				// Path fix - kudos to Jason Judge
	         	if ( getcwd() )
	         		$file_path = trim( preg_replace( '`^' . getcwd() . '`' , '', $file_path ), '/' );

	            header( "Content-Disposition: attachment; filename=\"" . $file_name . "\";" );

	            if ( function_exists( 'apache_get_modules' ) && in_array( 'mod_xsendfile', apache_get_modules() ) ) {

	            	header("X-Sendfile: $file_path");
	            	exit;

	            } elseif ( stristr( getenv( 'SERVER_SOFTWARE' ), 'lighttpd' ) ) {

	            	header( "X-Lighttpd-Sendfile: $file_path" );
	            	exit;

	            } elseif ( stristr( getenv( 'SERVER_SOFTWARE' ), 'nginx' ) || stristr( getenv( 'SERVER_SOFTWARE' ), 'cherokee' ) ) {

	            	header( "X-Accel-Redirect: /$file_path" );
	            	exit;

	            }
	        }

	        if ( $remote_file )
	        	@woocommerce_readfile_chunked( $file_path ) or header( 'Location: ' . $file_path );
	        else
	        	@woocommerce_readfile_chunked( $file_path ) or wp_die( __( 'File not found', $woocommerce_plugin_update_api_manager->text_domain ) . ' <a href="' . home_url() . '">' . __( 'Go to homepage &rarr;', $woocommerce_plugin_update_api_manager->text_domain ) . '</a>' );

	        exit;
		}
	}

}

$GLOBALS['wc_api_manager_helpers'] = new WC_Api_Manager_Helpers();
