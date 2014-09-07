<?php

/**
 * WooCommerce API Manager Software API Class
 *
 * @package Update API Manager/Software API
 * @author Todd Lahman LLC
 * @copyright   Copyright (c) 2011-2013, Todd Lahman LLC
 * @since 1.1.1
 *
 * This class is partially based on WooCommerce Software Add-On by WooThemes
 *
 */

class WC_Plugin_Update_API_Manager_Software_API {

	private 	$request = array();
	public 		$debug;

	public function __construct( $request, $debug = false ) {
		global $woocommerce_plugin_update_api_manager;

		$this->debug = ( WP_DEBUG ) ? true : $debug; // always on if WP_DEBUG is on

		if ( isset( $request['request'] ) )
			$this->request = $request;
		else
			$this->error( '100', __( 'Invalid API Request', $woocommerce_plugin_update_api_manager->text_domain ) );

		// Let's get started
		if ( $this->request['request'] == 'activation' )
			$this->activation_request();
		else if ( $this->request['request'] == 'deactivation' )
			$this->deactivation_request();
		else
			$this->error( '100', __( 'Invalid API Request', $woocommerce_plugin_update_api_manager->text_domain ) );

	}

	/**
	 * activation_request Handles API key activation requests
	 * @return mixed error message or JSON
	 */
	public function activation_request() {
		global $wc_api_manager_helpers, $woocommerce_plugin_update_api_manager;

		$this->check_required( array( 'email', 'licence_key', 'product_id' ) );

		$input = $this->check_input( array( 'email', 'licence_key', 'product_id', 'version', 'platform', 'secret_key', 'instance' ) );

		// Validate email
		if ( ! is_email( $input['email'] ) )
			$this->error( '100', __( 'Activation error. The email provided is invalid', $woocommerce_plugin_update_api_manager->text_domain ), null, array( 'activated' => false ) );

		// Get the user order info
		$data = $wc_api_manager_helpers->get_order_info_by_email_with_order_key( $input['email'], $input['licence_key'] );

		if ( ! $data || $data === false )
			$this->error( '101', __( 'Activation error. No matching API license key exists', $woocommerce_plugin_update_api_manager->text_domain ), null, array( 'activated' => false ) );

		// Validate order if set
		if ( $data['order_id'] ) {
			$order_status = wp_get_post_terms( $data['order_id'], 'shop_order_status' );
			$order_status = $order_status[0]->slug;
			if ( $order_status != 'completed' )
				$this->error( '102', __( 'Activation error. The purchase matching this product is not complete', $woocommerce_plugin_update_api_manager->text_domain ), null,  array( 'activated' => false ) );
		}

		/**
		 * Prevent trial subscription orders from activating
		 */
		$sub_status = $wc_api_manager_helpers->get_subscription_status( $data['order_id'] );

		// // Get the post_meta order data
		$post_meta = $wc_api_manager_helpers->get_postmeta_data( $data['order_id'] );

		if ( $sub_status != 'active' && ! empty( $post_meta['_original_order'] ) )
			$this->error( '101', null, null, array( 'activated' => false ) );

		/**
		 * Subscription check
		 */
		if ( WC_Api_Manager_Helpers::is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ) {

			// Finds the post ID (integer) for a product even if it is a variable product
			if ( $data['is_variable_product'] == 'no' )
				$post_id 	= $data['parent_product_id'];
			else
				$post_id 	= $data['variable_product_id'];

			// Finds order ID that matches the license key. Order ID is the post_id in the post meta table
			$order_id 	= $data['order_id'];

			$user_id 	= $data['user_id'];

			// Finds the product ID, which can only be the parent ID for a product
			$product_id = $data['parent_product_id'];

			if ( isset( $user_id ) && isset( $post_id ) && isset( $order_id ) && isset( $product_id ) ) {

				if ( $wc_api_manager_helpers->get_product_checkbox_status( $post_id, '_api_is_subscription' ) === true ) {

					global $wpdb;

					// Determine the Subscriptions version
					if ( version_compare( WC_Subscriptions::$version, '1.4', '>=' ) )
						$subs_new = true;
					else
						$subs_new = false;

					// For Subscriptions > 1.4
					if ( $subs_new === true )
						$subscriptions = WC_Subscriptions_Manager::get_users_subscriptions( $user_id );

					// For Subscriptions < 1.4
					if ( $subs_new === false )
						$subscription_data = get_user_meta( $user_id, "{$wpdb->prefix}woocommerce_subscriptions", true );

				}

			}

			if ( ( ! empty( $subscriptions ) && is_array( $subscriptions ) ) || ( ! empty( $subscription_data ) && is_array( $subscription_data ) ) ) {

				/**
				 * Get the Subscription status
				 */

				// Subscriptions > 1.4
				if ( $subs_new === true && isset( $subscriptions[$order_id . '_' . $product_id]['status'] ) ) {

					// account in good standing has active status - Subscriptions > 1.4
					$status = $subscriptions[$order_id . '_' . $product_id]['status'];

					// extra check for renewal subscriptions anomaly in 1.4
					if ( $sub_status != 'active' )
						$this->error( '106', null, null, array( 'activated' => false ) );

				// For Subscriptions < 1.4
				} else if ( $subs_new === false && isset( $subscription_data[$order_id . '_' . $product_id]['status'] ) ) { // In variable product subscription orders, the product id might be the parent or the child

					// account in good standing has active status - Subscriptions < 1.4
					$status = $subscription_data[$order_id . '_' . $product_id]['status']; // Subscriptions < 1.4

				// For Subscriptions < 1.4
				} else {

					// account in good standing has active status - Subscriptions < 1.4
					$status = $subscription_data[$order_id . '_' . $post_id]['status']; // Subscriptions < 1.4

				}

			}

			if ( ! empty( $status ) && $status != 'active' )
				$this->error( '106', __( 'Could not activate API license key', $woocommerce_plugin_update_api_manager->text_domain ), null, array( 'activated' => false ) );

		} // End Subscription check

		// Check remaining activations
		$activations_remaining = $this->activations_remaining( $data, $input );

		if ( ! $activations_remaining )
			$this->error( '103', __( 'Remaining activations is equal to zero', $woocommerce_plugin_update_api_manager->text_domain ), null, array( 'activated' => false ) );

		// Activation
		$result = $this->activate_licence_key( $data, $input );

		if ( ! $result )
			$this->error( '104', __( 'Could not activate API license key', $woocommerce_plugin_update_api_manager->text_domain ), null, array( 'activated' => false ) );

		// Check remaining activations
		$activations_remaining = $this->activations_remaining( $data, $input );

		// Activations limit or 999999999 (unlimited)
		if ( $data['is_variable_product'] == 'no' && $data['_api_activations_parent'] != '' )
			$activations_limit = $data['_api_activations_parent'];
		else if ( $data['is_variable_product'] =='no' && $data['_api_activations_parent'] == '' )
			$activations_limit = 0;
		else if ( $data['is_variable_product'] == 'yes' && $data['_api_activations'] != '' )
			$activations_limit = $data['_api_activations'];
		else if ( $data['is_variable_product'] == 'yes' && $data['_api_activations'] == '' )
			$activations_limit = 0;

		if ( NULL == $activations_limit || 0 == $activations_limit || empty( $activations_limit ) ) {
			$activations_limit = 999999999;
		}

		// Activation was successful - return json
		$data['activated'] = true;
		$data['instance'] = $input['instance'];
		$data['message'] = sprintf( __( '%s out of %s activations remaining', $woocommerce_plugin_update_api_manager->text_domain ), $activations_remaining, $activations_limit );
		$data['time'] = time();

		$to_output = array( 'activated', 'instance' );
		$to_output['message'] = 'message';
		$to_output['timestamp'] = 'time';

		$json = $this->prepare_output( $to_output, $data );

		if ( ! isset( $json ) ) $this->error( '100', __( 'Invalid API Request', $woocommerce_plugin_update_api_manager->text_domain ) );

		nocache_headers();
		header( 'Content-Type: application/json' );
		die( json_encode( $json ) );

	}

	/**
	 * activate_licence_key Activates an activation for an order_key/license key
	 *
	 * Activations are contained in numerically indexed arrays that each contain identifying informaiton like
	 * order_key, instance, and domain name, so activations for a specific order_key, or domain name
	 * can be easily located in the database.
	 *
	 * @param  array $data  user_meta order info
	 * @param  array $input info sumitted in $_REQUEST from client application
	 * @return bool
	 */
	public function activate_licence_key( $data, $input ) {
		global $wpdb, $wc_api_manager_helpers;

		if ( ! is_array( $data ) || ! is_array( $input ) ) return false;

		if ( $input['licence_key'] != $data['order_key'] ) return false;

		if ( $data['_api_update_permission'] != 'yes' ) return false;

		// Check for existing activations
		$current_info = $wc_api_manager_helpers->get_users_activation_data( $data['user_id'], $data['order_key'] );

		// Information for this new activation
		$activation_info =
    		array(
    			array(
					'order_key' 		=> $input['licence_key'],
					'instance'			=> $input['instance'],
					'product_id'		=> $input['product_id'],
					'activation_time' 	=> current_time( 'mysql' ),
					'activation_active' => 1,
					'activation_domain' => $input['platform'],
					)
    			);

    	if ( ! empty( $current_info ) ) {

    		// If true is returned then the software has been activated and false is returned
			if ( $wc_api_manager_helpers->array_search_multi( $current_info, 'instance', $input['instance'] ) === true )
				return false;

    		// If true is returned then the software has been activated and false is returned
			if ( $wc_api_manager_helpers->array_search_multi( $current_info, 'activation_domain', $input['platform'] ) === true )
				return false;

    		// If other activations already exist
			$new_info = array_merge_recursive( $activation_info, $current_info );

			update_user_meta( $data['user_id'], $wpdb->get_blog_prefix() . WC_Api_Manager_Helpers::$user_meta_key_activations . $data['order_key'], $new_info );

			return true;

		} else { // if this is the first activation for this order_key

			// If this is the first activation
			update_user_meta( $data['user_id'], $wpdb->get_blog_prefix() . WC_Api_Manager_Helpers::$user_meta_key_activations . $data['order_key'], $activation_info );

			return true;

		}

		return false;

	}

	/**
	 * deactivation_request Handles API key deactivation requests
	 *
	 * @return array JSON
	 */
	public function deactivation_request() {
		global $wc_api_manager_helpers, $woocommerce_plugin_update_api_manager;

		$this->check_required( array( 'email', 'licence_key', 'product_id' ) );

		$input = $this->check_input( array( 'email', 'licence_key', 'product_id', 'version', 'platform', 'instance' ) );

		// Validate email
		if ( ! is_email( $input['email'] ) )
			$this->error( '100', __( 'Deactivation error. The email provided is invalid', $woocommerce_plugin_update_api_manager->text_domain ), null, array( 'reset' => false ) );

		// Get the user order info
		$data = $wc_api_manager_helpers->get_order_info_by_email_with_order_key( $input['email'], $input['licence_key'] );

		if ( ! $data || $data === false )
			$this->error( '101', __( 'Deactivation error. No matching license key exists', $woocommerce_plugin_update_api_manager->text_domain ), null, array( 'activated' => false ) );

		// reset number of activations
		$is_deactivated = $this->deactivate_licence_key( $data, $input );

		if ( ! $is_deactivated )
			$this->error( '104', __( 'Deactivation error. No matching instance exists', $woocommerce_plugin_update_api_manager->text_domain ), null, array( 'activated' => false ) );

		$data['reset'] = true;
		$data['timestamp'] = time();
		$to_output = array();
		$to_output['reset'] = 'reset';
		$to_output['timestamp'] = 'timestamp';
		$json = $this->prepare_output( $to_output, $data );
		return $json;

	}

	/**
	 * deactivate_licence_key Deactivates an activation for an order_key/license key
	 *
	 * A deactivation removes the array containing the data for that activation. The numerically indexed parent
	 * arrays are then reindexed.
	 *
	 * @param  array $data  user_meta order info
	 * @param  array $input info sumitted in $_REQUEST from client application
	 * @return bool
	 */
	public function deactivate_licence_key( $data, $input ) {
		global $wpdb, $wc_api_manager_helpers;

		if ( ! is_array( $data ) || ! is_array( $input ) ) return false;

		if ( $input['licence_key'] != $data['order_key'] ) return false;

		$current_info = $wc_api_manager_helpers->get_users_activation_data( $data['user_id'], $data['order_key'] );

    	if ( ! empty( $current_info ) ) {

			$active_activations = 0;

	    	foreach ( $current_info as $key => $activations ) {

	    		if ( $activations['activation_active'] == 1 && $input['licence_key'] == $activations['order_key'] ) {

					$active_activations++;

	    		}

	    	}

    		foreach ( $current_info as $key => $activation_info ) {

    			if ( $active_activations <= 1 && $activation_info['activation_active'] == 1 && $activation_info['instance'] == $input['instance'] && $activation_info['activation_domain'] == $input['platform'] ) {

					delete_user_meta( $data['user_id'], $wpdb->get_blog_prefix() . WC_Api_Manager_Helpers::$user_meta_key_activations . $data['order_key'] );

					break;

					return true;

				} else if ( $activation_info['activation_active'] == 1 && $activation_info['instance'] == $input['instance'] && $activation_info['activation_domain'] == $input['platform'] ) {

					// Delete the activation data array
	    			unset( $current_info[$key] );

		    		// Re-index the numerical array keys:
					$new_info = array_values( $current_info );

					update_user_meta( $data['user_id'], $wpdb->get_blog_prefix() . WC_Api_Manager_Helpers::$user_meta_key_activations . $data['order_key'], $new_info );

					break;

	    			return true;

				}

    		} // end foreach

		}

		return false;

	}

	/**
	 * error Handles errors sent to the client
	 * @param  integer $code          error code
	 * @param  [type]  $debug_message placeholder
	 * @param  [type]  $secret        placeholder
	 * @param  array   $addtl_data    more info
	 * @return array                  JSON
	 */
	public function error( $code = 100, $debug_message = null, $secret = null, $addtl_data = array() ) {
		global $woocommerce_plugin_update_api_manager;

		switch ( $code ) {
			case '101' :
				$error = array( 'error' => __( 'Invalid API License Key. Login to your My Account page to find a valid API License Key', $woocommerce_plugin_update_api_manager->text_domain ), 'code' => '101' );
				break;
			case '102' :
				$error = array( 'error' => __( 'Software has been deactivated', $woocommerce_plugin_update_api_manager->text_domain ), 'code' => '102' );
				break;
			case '103' :
				$error = array( 'error' => __( 'Exceeded maximum number of activations', $woocommerce_plugin_update_api_manager->text_domain ), 'code' => '103' );
				break;
			case '104' :
				$error = array( 'error' => __( 'Invalid Instance ID', $woocommerce_plugin_update_api_manager->text_domain ), 'code' => '104' );
				break;
			case '105' :
				$error = array( 'error' => __( 'Invalid security key', $woocommerce_plugin_update_api_manager->text_domain ), 'code' => '105' );
				break;
			case '106' :
				$error = array( 'error' => __( 'Subscription Not Active', $woocommerce_plugin_update_api_manager->text_domain ), 'code' => '106' );
				break;
			default :
				$error = array( 'error' => __( 'Invalid Request', $woocommerce_plugin_update_api_manager->text_domain ), 'code' => '100' );
				break;
		}

		if ( isset( $this->debug ) && $this->debug ) {
			if ( ! isset( $debug_message ) || ! $debug_message ) $debug_message = __( 'No debug information available', $woocommerce_plugin_update_api_manager->text_domain );
			$error['additional info'] = $debug_message;
		}

		if ( isset( $addtl_data['secret'] ) ) {
			$secret = $addtl_data['secret'];
			unset( $addtl_data['secret'] );
		}

		foreach ( $addtl_data as $k => $v ) {
			$error[ $k ] = $v;
		}

		$secret = ( $secret ) ? $secret : 'null';
		$error['timestamp'] = time();

		foreach ( $error as $k => $v ) {
			if ( $v === false ) $v = 'false';
			if ( $v === true ) $v = 'true';
			$sigjoined[] = "$k=$v";
		}

		$sig = implode( '&', $sigjoined );
		$sig = 'secret=' . $secret . '&' . $sig;

		if ( !$this->debug ) $sig = md5( $sig );

		$error['sig'] = $sig;
		$json = $error;

		nocache_headers();
		header( 'Content-Type: application/json' );

		die( json_encode( $json ) );
		exit;
	}

	private function check_required( $required ) {
		global $woocommerce_plugin_update_api_manager;

		$i = 0;
		$missing = '';

		foreach ( $required as $req ) {
			if ( ! isset( $this->request[ $req ] ) || $req == '' ) {
				$i++;
				if ( $i > 1 ) $missing .= ', ';
				$missing .= $req;
			}
		}

		if ( $missing != '' ) {
			$this->error( '100', __( 'The following required information is missing', $woocommerce_plugin_update_api_manager->text_domain ) . ': ' . $missing, null, array( 'activated' => false ) );
		}
	}

	private function check_input( $input ) {
		$return = array();

		foreach ( $input as $key ) {
			$return[ $key ] = ( isset( $this->request[ $key ] ) ) ? $this->request[ $key ] : '';
		}

		return $return;
	}

	private function prepare_output( $to_output = array(), $data = array() ) {
		$secret = ( isset( $data->secret_key ) ) ? $data->secret_key : 'null';
		$sig_array = array( 'secret' => $secret );

		foreach ( $to_output as $k => $v ) {
			if ( isset( $data[ $v ] ) ) {
				if ( is_string( $k ) ) {
					$output[ $k ] = $data[ $v ];
				} else {
					$output[ $v ] = $data[ $v ];
				}
			}
		}

		$sig_out = $output;
		$sig_array = array_merge( $sig_array, $sig_out );

		foreach ( $sig_array as $k => $v ) {
			if ( $v === false ) $v = 'false';
			if ( $v === true ) $v = 'true';
			$sigjoined[] = "$k=$v";
		}

		$sig = implode( '&', $sigjoined );

		$output['sig'] = $sig;
		return $output;
	}

	/**
	 * activations_remaining Calculates the number of remaining activations for an order_key/licence_key
	 * @param  array $data  user_meta order info
	 * @param  array $input info sumitted in $_REQUEST from client application
	 * @return int        Number of remaining activations
	 */
	private function activations_remaining( $data, $input ) {
		global $wc_api_manager_helpers;

		if ( ! is_array( $data ) || ! is_array( $input ) ) return 0;

		if ( $input['licence_key'] != $data['order_key'] ) return 0;

		$order_key = $input['licence_key'];

		$current_info = $wc_api_manager_helpers->get_users_activation_data( $data['user_id'], $data['order_key'] );

		if ( ! empty( $current_info ) ) {

			$active_activations = 0;

	    	foreach ( $current_info as $key => $activations ) {

	    		if ( $activations['activation_active'] == 1 && $input['licence_key'] == $activations['order_key'] ) {

					$active_activations++;

	    		}

			}

		} else {

			$active_activations = 0;

		}

		if ( isset( $data ) ) {

			if ( $data['is_variable_product'] == 'no' && $data['_api_activations_parent'] != '' )
				$activations_limit = $data['_api_activations_parent'];
			else if ( $data['is_variable_product'] =='no' && $data['_api_activations_parent'] == '' )
				$activations_limit = 0;
			else if ( $data['is_variable_product'] == 'yes' && $data['_api_activations'] != '' )
				$activations_limit = $data['_api_activations'];
			else if ( $data['is_variable_product'] == 'yes' && $data['_api_activations'] == '' )
				$activations_limit = 0;

		}

		if ( NULL == $activations_limit || 0 == $activations_limit || empty( $activations_limit ) ) {
			return 999999999;
		}

		$remaining =  $activations_limit - $active_activations;

		if ( $remaining < 0 ) $remaining = 0;

		return $remaining;
	}


} // End class
