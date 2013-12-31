<?php

/**
 * WooCommerce API Manager Update API Class
 *
 * Plugin and Theme Update API responding to update requests
 *
 * @package Update API Manager/API
 * @author Todd Lahman LLC
 * @copyright   Copyright (c) 2011-2013, Todd Lahman LLC
 * @since 1.0.0
 *
 */

class WC_Plugin_Update_API_Manager_API {

	private $request = array();
	private $plugin_name;
	private $version;
	private $product_id;
	private $api_key;
	private $activation_email;
	private $instance;
	private $domain;
	private $software_version;

	public function __construct( $request ) {

		/**
		 * For example a $request['plugininformation'] might look like:
		 * Array
		 *	(
		 *	    [wc-api] => upgrade-api
		 *	    [request] => plugininformation
		 *	    [plugin_name] => simple-comments/simple-comments.php
		 *	    [version] => 1.9.4
		 *	    [product_id] => Simple Comments
		 *	    [api_key] => f66226254772
		 *	    [activation_email] => todd@testing.com
		 *	)
		 */

		if ( isset( $request['request'] ) ) {
			$this->request 			= $request['request'];
			$this->plugin_name 		= $request['plugin_name']; // same as plugin slug
			$this->version 			= $request['version'];
			$this->product_id 		= $request['product_id'];
			$this->api_key			= $request['api_key'];
			$this->activation_email	= $request['activation_email'];
			$this->instance			= ( empty( $request['instance'] ) ) ? '' : $request['instance'];
			$this->domain			= ( empty( $request['domain'] ) ) ? '' : $request['domain'];
			$this->software_version = ( empty( $request['software_version'] ) ) ? '' : $request['software_version'];

			// Let's get started
			$this->update_check();

		} else {

			$errors = array( 'no_key' => 'no_key' );

			self::send_error_api_data( $this->request, $errors );
		}

	}

	/**
	 * Checks account information and for dependencies before getting API information.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function update_check() {
		global $woocommerce_plugin_update_api_manager, $wc_api_manager_helpers;

		if ( ! empty( $this->request ) || ! empty( $this->plugin_name ) || ! empty( $this->version ) || ! empty( $this->product_id ) || ! empty( $this->api_key ) || ! empty( $this->activation_email ) ) {

			$is_order_key = false;

			// If the remote plugin or theme has nothing entered into the license key and license email fields
			if ( $this->api_key == '' || $this->activation_email == '' ) {

				$errors = array( 'no_key' => 'no_key' );

				self::send_error_api_data( $this->request, $errors );
			}

			// returns $user->ID
			$user = get_user_by( 'email', $this->activation_email );

			// If the remote plugin or theme has nothing entered into the license key and license email fields
			if ( ! is_object( $user ) ) {

				$errors = array( 'no_key' => 'no_key' );

				self::send_error_api_data( $this->request, $errors );
			}

			// Check if this is an order_key
			if ( stristr( $this->api_key, 'order_') ) {

				$is_order_key = true;

				$user_orders = WC_Api_Manager_Helpers::get_users_data( $user->ID );

				if ( isset( $user_orders ) ) {

					$order_info = $user_orders[$this->api_key];

				} else {

					$errors = array( 'no_key' => 'no_key' );

					self::send_error_api_data( $this->request, $errors );

				}

				// Get activation info
				$current_info = $wc_api_manager_helpers->get_users_activation_data( $user->ID, $order_info['order_key'] );

				// Check if this software has been activated
				if ( is_array( $current_info ) && ! empty( $current_info ) ) {

					// If false is returned then the software has not yet been activated and an error is returned
					if ( $wc_api_manager_helpers->array_search_multi( $current_info, 'order_key', $this->api_key ) === false ) {

						$errors = array( 'no_activation' => 'no_activation' );

						self::send_error_api_data( $this->request, $errors );

					}

					// If false is returned then the software has not yet been activated and an error is returned
					if ( ! empty( $this->instance ) && $wc_api_manager_helpers->array_search_multi( $current_info, 'instance', $this->instance ) === false ) {

						$errors = array( 'no_activation' => 'no_activation' );

						self::send_error_api_data( $this->request, $errors );

					}

					// If false is returned then the software has not yet been activated and an error is returned
					if ( ! empty( $this->domain ) && $wc_api_manager_helpers->array_search_multi( $current_info, 'activation_domain', $this->domain ) === false ) {

						$errors = array( 'no_activation' => 'no_activation' );

						self::send_error_api_data( $this->request, $errors );

					}


				} else { // Send an error if this software has not been activated

					$errors = array( 'no_activation' => 'no_activation' );

					self::send_error_api_data( $this->request, $errors );

				}

			}

			// If this is a Software Add-on license
			if ( $is_order_key === false && WC_Api_Manager_Helpers::is_plugin_active( 'woocommerce-software-add-on/woocommerce-software.php' ) ) {

				// Finds the post ID (integer) for a product even if it is a variable product
				$post_id 	= $wc_api_manager_helpers->get_post_id( $this->api_key, $this->activation_email );

				// Finds order ID that matches the license key. Order ID is the post_id in the post meta table
				$order_id 	= $wc_api_manager_helpers->get_order_id( $this->api_key, $this->activation_email );

				// Finds the product ID, which can only be the parent ID for a product
				$product_id = $wc_api_manager_helpers->get_post_id_by( '_api_software_title_parent', $this->product_id );

				// Finds the order_key for the product purchased
				$order_key 	= get_post_meta( $order_id, '_order_key', true );

			} else { // If this is an order_key

				// Finds the post ID (integer) for a product even if it is a variable product
				if ( $order_info['is_variable_product'] == 'no' )
					$post_id 	= $order_info['parent_product_id'];
				else
					$post_id 	= $order_info['variable_product_id'];

				// Finds order ID that matches the license key. Order ID is the post_id in the post meta table
				$order_id 	= $order_info['order_id'];

				// Finds the product ID, which can only be the parent ID for a product
				$product_id = $order_info['parent_product_id'];

				// Finds the order_key for the product purchased
				$order_key 	= $order_info['order_key'];

				// Does this order_key have Permission to get updates from the API?
				if ( $order_info['_api_update_permission'] != 'yes' ) {

					$errors = array( 'download_revoked' => 'download_revoked' );

					self::send_error_api_data( $this->request, $errors );
				}

			}

			if ( isset( $user ) && isset( $post_id ) && isset( $order_id ) && isset( $product_id ) && isset( $order_key ) ) {

				// Verifies license key exists. Returns true or false.
				if ( $is_order_key === false ) {

					// If this is a Software Add-on license
					$key_exists = $wc_api_manager_helpers->verify_license_key( $this->api_key, $this->activation_email );

				} else if ( $this->api_key == $order_info['order_key'] ) {

					// If this is an order_key
					$key_exists = true;

				} else {

					$key_exists = false;

				}

				// Send a renew license key message to the customer
				if ( isset( $key_exists ) && $key_exists === false ) {

					$errors = array( 'exp_license' => 'exp_license' );

					self::send_error_api_data( $this->request, $errors );

				} else if ( WC_Api_Manager_Helpers::is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) && $wc_api_manager_helpers->get_product_checkbox_status( $post_id, '_api_is_subscription' ) === true ) {

					global $wpdb;

					// Determine the Subscriptions version
					if ( version_compare( WC_Subscriptions::$version, '1.4', '>=' ) )
						$subs_new = true;
					else
						$subs_new = false;

					// For Subscriptions > 1.4
					if ( $subs_new === true )
						$subscriptions = WC_Subscriptions_Manager::get_users_subscriptions( $user->ID );

					// For Subscriptions < 1.4
					if ( $subs_new === false )
						$subscription_data = get_user_meta( $user->ID, "{$wpdb->prefix}woocommerce_subscriptions", true );


					// Send an error if no subscription is found and if the API key doesn't match what was sent by the software
					if ( $subs_new === true && empty( $subscriptions ) ) {

						if ( $key_exists ) { // Matched the API Key, send the update data

								self::send_api_data( $this->request, $this->plugin_name, $this->version, $order_id, $this->api_key, $this->activation_email, $post_id, $order_key );

							} else { // No API Key match, send an error

								$errors = array( 'no_subscription' => 'no_subscription', 'no_key' => 'no_key' );

								self::send_error_api_data( $this->request, $errors );

							}

					} else if ( $subs_new === false && empty( $subscription_data ) ) {

						if ( $key_exists ) { // Matched the API Key, send the update data

								self::send_api_data( $this->request, $this->plugin_name, $this->version, $order_id, $this->api_key, $this->activation_email, $post_id, $order_key );

							} else { // No API Key match, send an error

								$errors = array( 'no_subscription' => 'no_subscription', 'no_key' => 'no_key' );

								self::send_error_api_data( $this->request, $errors );

							}

					} else if ( is_array( $subscriptions ) || is_array( $subscription_data ) ) {

						/**
						 * Get the Subscription status
						 */

						// Subscriptions > 1.4
						if ( $subs_new === true && isset( $subscriptions[$order_id . '_' . $product_id]['status'] ) ) {

							// account in good standing has active status - Subscriptions > 1.4
							$status = $subscriptions[$order_id . '_' . $product_id]['status'];

							$sub_status = $wc_api_manager_helpers->get_subscription_status( $order_id );

							// extra check for renewal subscriptions anomaly in 1.4
							if ( $sub_status != 'active' ) {

								$errors = array( 'no_subscription' => 'no_subscription', 'no_key' => 'no_key' );

								self::send_error_api_data( $this->request, $errors );

							}

						// For Subscriptions < 1.4
						} else if ( $subs_new === false && isset( $subscription_data[$order_id . '_' . $product_id]['status'] ) ) { // In variable product subscription orders, the product id might be the parent or the child

							// account in good standing has active status - Subscriptions < 1.4
							$status = $subscription_data[$order_id . '_' . $product_id]['status']; // Subscriptions < 1.4

						// For Subscriptions < 1.4
						} else {

							// account in good standing has active status - Subscriptions < 1.4
							$status = $subscription_data[$order_id . '_' . $post_id]['status']; // Subscriptions < 1.4
						}


						// Just in case there are no results at all
						if ( empty( $status ) ) {

							if ( $key_exists ) {

								self::send_api_data( $this->request, $this->plugin_name, $this->version, $order_id, $this->api_key, $this->activation_email, $post_id, $order_key );

							} else {

								$errors = array( 'no_subscription' => 'no_subscription', 'no_key' => 'no_key' );

								self::send_error_api_data( $this->request, $errors );

							}

						} else if ( isset( $status ) ) {

							if ( $status == 'active' ) {

								self::send_api_data( $this->request, $this->plugin_name, $this->version, $order_id, $this->api_key, $this->activation_email, $post_id, $order_key );

							} else if ( $status == 'on-hold' ) {

								$errors = array( 'hold_subscription' => 'hold_subscription' );

								self::send_error_api_data( $this->request, $errors );

							} else if ( $status == 'cancelled' ) {

								$errors = array( 'cancelled_subscription' => 'cancelled_subscription' );

								self::send_error_api_data( $this->request, $errors );

							} else if ( $status == 'expired' ) {

								$errors = array( 'exp_subscription' => 'exp_subscription' );

								self::send_error_api_data( $this->request, $errors );

							} else if ( $status == 'switched' ) {

								$errors = array( 'switched_subscription' => 'switched_subscription' );

								self::send_error_api_data( $this->request, $errors );

							} else if ( $status == 'suspended' ) {

								$errors = array( 'suspended_subscription' => 'suspended_subscription' );

								self::send_error_api_data( $this->request, $errors );

							} else if ( $status == 'pending' ) {

								$errors = array( 'pending_subscription' => 'pending_subscription' );

								self::send_error_api_data( $this->request, $errors );

							} else if ( $status == 'trash' ) {

								$errors = array( 'trash_subscription' => 'trash_subscription' );

								self::send_error_api_data( $this->request, $errors );

							} else if ( $key_exists ) {

								self::send_api_data( $this->request, $this->plugin_name, $this->version, $order_id, $this->api_key, $this->activation_email, $post_id, $order_key );

							} else {

								$errors = array( 'no_subscription' => 'no_subscription', 'no_key' => 'no_key' );

								self::send_error_api_data( $this->request, $errors );
							}

						} // end isset $status

					} // end is_array $subscription_data

				} else if ( $key_exists ) {

						self::send_api_data( $this->request, $this->plugin_name, $this->version, $order_id, $this->api_key, $this->activation_email, $post_id, $order_key );

				} // end if subscriptions installed

			} // end if isset data variables

		} else {

			$errors = array( 'no_key' => 'no_key' );

			self::send_error_api_data( $this->request, $errors );
		}

	}

	/**
	 * Plugin and Theme Update API method.
	 *
	 * @access public
	 * @since  1.0.0
	 * @param  varies
	 * @return object $response
	 */
	public function send_api_data( $request, $plugin_name, $version, $order_id, $api_key, $activation_email, $post_id, $order_key ) {
		global $wc_api_manager_helpers;

		$download_count = $wc_api_manager_helpers->get_download_count( $order_id, $order_key );

		if ( $download_count !== false ) {

			// Get the API data in an array
			$api_data = get_post_custom( $post_id );

			// The download ID is needed for the order specific download URL
			$download_id = $wc_api_manager_helpers->get_download_id( $post_id );

			// Build the order specific download URL
			$download_link = $wc_api_manager_helpers->create_url( $api_key, $activation_email, $post_id, $download_id );

			/**
			 * Prepare pages for display in upgrade "View version details" screen
			 */
			$desc_obj 		= get_post( $api_data['_api_description'][0] );
			$install_obj 	= get_post( $api_data['_api_installation'][0] );
			$faq_obj 		= get_post( $api_data['_api_faq'][0] );
			$screen_obj 	= get_post( $api_data['_api_screenshots'][0] );
			$change_obj 	= get_post( $api_data['_api_changelog'][0] );
			$notes_obj 		= get_post( $api_data['_api_other_notes'][0] );

			// Instantiate $response object
			$response = new stdClass();

			switch( $request ) {

				/**
				 * new_version here is compared with the current version in plugin
				 * Provides info for plugin row and dashboard -> updates page
				 */
				case 'pluginupdatecheck':
					$response->slug 					= $plugin_name;
					$response->new_version 				= $api_data['_api_new_version'][0];
					$response->url 						= $api_data['_api_plugin_url'][0];
					$response->package 					= $download_link;
					break;
				/**
				 * Request for detailed information for view details page
				 * more plugin info:
				 * wp-admin/includes/plugin-install.php
				 * Display plugin information in dialog box form.
				 * function install_plugin_information()
				 *
				 */
				case 'plugininformation':
					$response->version 					= $api_data['_api_new_version'][0];
					$response->slug 					= $plugin_name;
					$response->author 					= $api_data['_api_author'][0];
					$response->homepage 				= $api_data['_api_plugin_url'][0];
					$response->requires 				= $api_data['_api_version_required'][0];
					$response->tested 					= $api_data['_api_tested_up_to'][0];
					$response->downloaded 				= $download_count;
					$response->last_updated 			= $api_data['_api_last_updated'][0];
					$response->download_link 			= $download_link;
					$response->sections = array(
										'description' 	=> $wc_api_manager_helpers->get_page_content( $desc_obj ),
										'installation' 	=> $wc_api_manager_helpers->get_page_content( $install_obj ),
										'faq' 			=> $wc_api_manager_helpers->get_page_content( $faq_obj ),
										'screenshots' 	=> $wc_api_manager_helpers->get_page_content( $screen_obj ),
										'changelog' 	=> $wc_api_manager_helpers->get_page_content( $change_obj ),
										'other_notes' 	=> $wc_api_manager_helpers->get_page_content( $notes_obj )
										);
					break;
				/**
				 * more theme info
				 * wp-admin/includes/theme-install.php
				 * WordPress Theme Install Administration API
				 * $theme_field_defaults
				 *
				 * wp-admin/includes/theme.php
				 * function themes_api()
				 */

			}

			nocache_headers();

			die( serialize( $response ) );

			exit;

		} else {

			$errors = array( 'download_revoked' => 'download_revoked' );

			self::send_error_api_data( $this->request, $errors );
		}

	}

	/**
	 * Plugin and Theme Update API error method.
	 *
	 * @access public
	 * @since  1.0.0
	 * @param  varies
	 * @return object $response->errors
	 */
	public function send_error_api_data( $request, $errors ) {

		$response = new stdClass();

		switch( $request ) {

			case 'pluginupdatecheck':
				$response->slug 					= '';
				$response->new_version 				= '';
				$response->url 						= '';
				$response->package 					= '';
				$response->errors 					= $errors;
				break;

			case 'plugininformation':
				$response->version 					= '';
				$response->slug 					= '';
				$response->author 					= '';
				$response->homepage 				= '';
				$response->requires 				= '';
				$response->tested 					= '';
				$response->downloaded 				= '';
				$response->last_updated 			= '';
				$response->download_link 			= '';
				$response->sections = array(
									'description' 	=> '',
									'installation' 	=> '',
									'faq' 			=> '',
									'screenshots' 	=> '',
									'changelog' 	=> '',
									'other_notes' 	=> ''
									);
				$response->errors 					= $errors;
				break;

		}

		nocache_headers();

		die( serialize( $response ) );

		exit;
	}

}

// Instantiated in woocommerce-api-manager.php
