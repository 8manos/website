<?php

/**
 * WooCommerce API Manager API Key Class
 *
 * @package Update API Manager/Key Handler
 * @author Todd Lahman LLC
 * @copyright   Copyright (c) 2011-2013, Todd Lahman LLC
 * @since 1.1.1
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WC_Api_Manager_Key {

	// API Key URL
	public function create_software_api_url( $args ) {
		global $woocommerce_plugin_update_api_manager;

		$api_url = add_query_arg( 'wc-api', 'am-software-api', $woocommerce_plugin_update_api_manager->upgrade_url );

		return $api_url . '&' . http_build_query( $args );
	}

	public function activate( $args ) {

		$product_id = get_option( 'wc_api_manager_product_id' );
		$instance = get_option( 'wc_api_manager_instance' );
		$platform = site_url();

		$defaults = array(
			'request' => 'activation',
			'product_id' => $product_id,
			'instance' => $instance,
			'platform' => $platform
			);

		$args = wp_parse_args( $defaults, $args );

		$target_url = self::create_software_api_url( $args );

		$request = wp_remote_get( $target_url );

		if( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
		// Request failed
			return false;
		}

		$response = wp_remote_retrieve_body( $request );

		return $response;
	}

	public function deactivate( $args ) {

		// instance required
		$product_id = get_option( 'wc_api_manager_product_id' );
		$instance = get_option( 'wc_api_manager_instance' );
		$platform = site_url();

		$defaults = array(
			'request' => 'deactivation',
			'product_id' => $product_id,
			'instance' => $instance,
			'platform' => $platform
			);

		$args = wp_parse_args( $defaults, $args );

		$target_url = self::create_software_api_url( $args );

		$request = wp_remote_get( $target_url );

		if( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
		// Request failed
			return false;
		}

		$response = wp_remote_retrieve_body( $request );

		return $response;
	}

	public function check( $args ) {

		$product_id = get_option( 'wc_api_manager_product_id' );

		$defaults = array(
			'request'     => 'check',
			'product_id' => $product_id,
			);

		$args = wp_parse_args( $defaults, $args );

		$target_url = self::create_software_api_url( $args );

		$request = wp_remote_get( $target_url );

		if( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
		// Request failed
			return false;
		}

		$response = wp_remote_retrieve_body( $request );

		return $response;
	}

}

// Class is instantiated as an object by other classes on-demand
