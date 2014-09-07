<?php

/**
 * WooCommerce Admin Settings Class
 *
 * @package WooCommerce/Update API Manager/Settings Admin
 * @author Todd Lahman LLC
 * @copyright   Copyright (c) 2011-2013, Todd Lahman LLC
 * @since 1.0
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WC_API_Manager_Settings {

	public $settings;
	private $license_check;

	// Load admin menu
	function __construct() {
		global $woocommerce_plugin_update_api_manager;

		//$this->license_check 	= ( WC_Api_Manager_Helpers::is_plugin_active( 'woocommerce-software-add-on/woocommerce-software.php' ) ) ? __('The Software Add-On license keys replace the Update API Manager license keys for all products.', $woocommerce_plugin_update_api_manager->text_domain ) : __('ATTENTION! <a href="http://www.woothemes.com/products/software-add-on/" target="_blank">WooCommerce Software Add-on</a> is required to be installed and activated to use this option.', $woocommerce_plugin_update_api_manager->text_domain );

		// Settings init
		$this->settings = array(
			array( 'name' => __( 'Update API Manager', $woocommerce_plugin_update_api_manager->text_domain ), 'type' => 'title', 'desc' => '', 'id' => 'api_manager' ),
			array(
				'name' 		=> __('Software Add-On License Keys', $woocommerce_plugin_update_api_manager->text_domain ),
				//'desc' 		=> $this->license_check,
				'desc' 		=> __('The Software Add-On license keys replace the Update API Manager license keys for all products.', $woocommerce_plugin_update_api_manager->text_domain ),
				'id' 		=> 'wc_api_manager_software_add_on_license_key',
				'type' 		=> 'checkbox',
			),
			array( 'type' => 'sectionend', 'id' => 'api_manager'),
		);

		// Settings hooks
		add_action( 'woocommerce_settings_general_options_after', array( $this, 'admin_settings' ) );
		add_action( 'woocommerce_update_options_general', array( $this, 'save_admin_settings' ) );

	}

	/**
	 * admin_settings function.
	 *
	 * @access public
	 * @return void
	 */
	public function admin_settings() {
		woocommerce_admin_fields( $this->settings );
	}

	/**
	 * save_admin_settings function.
	 *
	 * @access public
	 * @return void
	 */
	public function save_admin_settings() {
		woocommerce_update_options( $this->settings );
	}

} // end of class

new WC_API_Manager_Settings();
