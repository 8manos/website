<?php

/**
 * Admin Menu Class
 *
 * @package Update API Manager/Admin
 * @author Todd Lahman LLC
 * @copyright   Copyright (c) 2011-2013, Todd Lahman LLC
 * @since 1.0
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WC_API_Manager_MENU {

	private $wc_api_manager_tool_tips;
	private $wc_api_manager_key;

	// Load admin menu
	public function __construct() {

		$this->wc_api_manager_tool_tips = new WC_API_Manager_Tool_Tips();
		$this->wc_api_manager_key = new WC_Api_Manager_Key();

		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_init', array( $this, 'load_settings' ) );
	}

	// Add option page menu
	public function add_menu() {
		global $woocommerce_plugin_update_api_manager;

		$page = add_options_page( __( 'WC API Manager', $woocommerce_plugin_update_api_manager->text_domain ), __( 'API Manager', $woocommerce_plugin_update_api_manager->text_domain ),
						'manage_options', 'wc_api_manager_dashboard', array( $this, 'config_page')
		);
		add_action( 'admin_print_styles-' . $page, array( $this, 'css_scripts' ) );
		add_action( "admin_print_scripts-$page", array( $this, 'javascript_scripts' ) );
	}

	// Draw option page
	public function config_page() {
		global $woocommerce_plugin_update_api_manager;

		$settings_tabs = array( 'wc_api_manager_dashboard' => __( 'Dashboard', $woocommerce_plugin_update_api_manager->text_domain ), 'wc_api_manager_deactivation' => __( 'License Deactivation', $woocommerce_plugin_update_api_manager->text_domain ) );
		$current_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'wc_api_manager_dashboard';
		$tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'wc_api_manager_dashboard';
		?>
		<div class='wrap'>
			<?php screen_icon(); ?>
			<h2><?php _e( 'WooCommerce API Manager', $woocommerce_plugin_update_api_manager->text_domain ); ?></h2>

			<script type="text/javascript">
				jQuery(function($) {
					$(".tool-tip").tipTip({maxWidth: "310px", edgeOffset: 15});
				});
			</script>

			<h2 class="nav-tab-wrapper">
			<?php
				foreach ( $settings_tabs as $tab_page => $tab_name ) {
					$active_tab = $current_tab == $tab_page ? 'nav-tab-active' : '';
					echo '<a class="nav-tab ' . $active_tab . '" href="?page=wc_api_manager_dashboard&tab=' . $tab_page . '">' . $tab_name . '</a>';
				}
			?>
			</h2>
				<form action='options.php' method='post'>
				<div class="main">
			<?php
				if( $tab == 'wc_api_manager_dashboard' ) {
						settings_fields( 'wc_api_manager' );
						do_settings_sections( 'wc_api_manager_dashboard' );
							$wc_am_save_changes = __( 'Save Changes', $woocommerce_plugin_update_api_manager->text_domain );
							submit_button( $wc_am_save_changes );
				} else {
						settings_fields( 'wc_am_deactivate_checkbox' );
						do_settings_sections( 'wc_api_manager_deactivation' );
							$wc_am_save_changes_activation = __( 'Save Changes', $woocommerce_plugin_update_api_manager->text_domain );
							submit_button( $wc_am_save_changes_activation );
				}
			?>
				</div>
					<div class="sidebar">
						<?php $this->wc_am_sidebar(); ?>
					</div>
				</form>
				</div>
			<?php
	}

	// Register settings
	public function load_settings() {
		global $woocommerce_plugin_update_api_manager;

		register_setting( 'wc_api_manager', 'wc_api_manager', array( $this, 'validate_options' ) );

		// API Key
		add_settings_section( 'api_key', __( 'License Information', $woocommerce_plugin_update_api_manager->text_domain ), array( $this, 'wc_am_api_key_text' ), 'wc_api_manager_dashboard' );
		add_settings_field( 'api_key', __( 'License Key', $woocommerce_plugin_update_api_manager->text_domain ), array( $this, 'wc_am_api_key_field' ), 'wc_api_manager_dashboard', 'api_key' );
		add_settings_field( 'api_email', __( 'License email', $woocommerce_plugin_update_api_manager->text_domain ), array( $this, 'wc_am_api_email_field' ), 'wc_api_manager_dashboard', 'api_key' );

		// Activation settings
		register_setting( 'wc_am_deactivate_checkbox', 'wc_am_deactivate_checkbox', array( $this, 'wc_am_license_key_deactivation' ) );
		add_settings_section( 'deactivate_button', __( 'Plugin License Deactivation', $woocommerce_plugin_update_api_manager->text_domain ), array( $this, 'wc_am_deactivate_text' ), 'wc_api_manager_deactivation' );
		add_settings_field( 'deactivate_button', __( 'Deactivate Plugin License', $woocommerce_plugin_update_api_manager->text_domain ), array( $this, 'wc_am_deactivate_textarea' ), 'wc_api_manager_deactivation', 'deactivate_button' );

		// Tech Support Information
		add_settings_section( 'tech_support_info', __( 'Tech Support Information', $woocommerce_plugin_update_api_manager->text_domain ), array( $this, 'tech_support_info_text' ), 'wc_api_manager_dashboard' );
		$tech_support = array(
								__( 'PHP Version', $woocommerce_plugin_update_api_manager->text_domain ),
								__( 'Database Version', $woocommerce_plugin_update_api_manager->text_domain ),
								__( 'WordPress Version', $woocommerce_plugin_update_api_manager->text_domain ),
								__( 'API Manager Version', $woocommerce_plugin_update_api_manager->text_domain ),
								__( 'Activation Status', $woocommerce_plugin_update_api_manager->text_domain )
							);
		foreach( $tech_support as $ts ) {
			add_settings_field( $ts, $ts, array( $this, 'tech_support' ), 'wc_api_manager_dashboard', 'tech_support_info', $ts );
		}

	}

	// Provides text for api key section
	public function wc_am_api_key_text() {
		//
	}

	// Outputs API License text field
	public function wc_am_api_key_field() {
		global $woocommerce_plugin_update_api_manager;

		$options = get_option( 'wc_api_manager' );
		$api_key = $options['api_key'];
		echo "<input id='api_key' name='wc_api_manager[api_key]' size='25' type='text' value='{$options['api_key']}' />";
		if ( !empty( $options['api_key'] ) ) {
			echo "<span class='icon-pos'><img src='" . $woocommerce_plugin_update_api_manager->plugin_url() . "assets/images/complete.png' title='' style='padding-bottom: 4px; vertical-align: middle; margin-right:3px;' /></span>";
		} else {
			echo "<span class='icon-pos'><img src='" . $woocommerce_plugin_update_api_manager->plugin_url() . "assets/images/warn.png' title='' style='padding-bottom: 4px; vertical-align: middle; margin-right:3px;' /></span><strong><a href='http://www.toddlahman.com/shop/woocommerce-plugin-and-theme-update-api-manager/' target='_blank'>" . __( 'Buy a License Key', $woocommerce_plugin_update_api_manager->text_domain ) . "</a></strong>";
		}
	}

	// Outputs API License email text field
	public function wc_am_api_email_field() {
		global $woocommerce_plugin_update_api_manager;

		$options = get_option( 'wc_api_manager' );
		$activation_email = $options['activation_email'];
		echo "<input id='activation_email' name='wc_api_manager[activation_email]' size='25' type='text' value='{$options['activation_email']}' />";
		if ( !empty( $options['activation_email'] ) ) {
			echo "<span class='icon-pos'><img src='" . $woocommerce_plugin_update_api_manager->plugin_url() . "assets/images/complete.png' title='' style='padding-bottom: 4px; vertical-align: middle; margin-right:3px;' /></span>";
		} else {
			echo "<span class='icon-pos'><img src='" . $woocommerce_plugin_update_api_manager->plugin_url() . "assets/images/warn.png' title='' style='padding-bottom: 4px; vertical-align: middle; margin-right:3px;' /></span><strong><a href='http://www.toddlahman.com/shop/woocommerce-plugin-and-theme-update-api-manager/' target='_blank'>" . __( 'Buy a License Key', $woocommerce_plugin_update_api_manager->text_domain ) . "</a></strong>";
		}
	}

	public function tech_support_info_text() {
		//
	}

	// Sanitizes and validates all input and output for Dashboard
	public function validate_options( $input ) {
		global $woocommerce_plugin_update_api_manager;

		// Load existing options, validate, and update with changes from input before returning
		$options = get_option( 'wc_api_manager' );

		$options['api_key'] = trim( $input['api_key'] );
		$options['activation_email'] = trim( $input['activation_email'] );

		/**
		  * Plugin Activation
		  */
		$api_email = trim( $input['activation_email'] );
		$api_key = trim( $input['api_key'] );

		$activation_status = get_option( 'wc_api_manager_activated' );
		$checkbox_status = get_option( 'wc_api_manager_deactivate_checkbox' );

		$current_api_key = $this->get_key();

		// Should match the settings_fields() value
		if ( $_REQUEST['option_page'] != 'wc_am_deactivate_checkbox' ) {

			if ( $activation_status == 'Deactivated' || $activation_status == '' || $api_key == '' || $api_email == '' || $checkbox_status == 'on' || $current_api_key != $api_key  ) {

				/**
				 * If this is a new key, and an existing key already exists in the database,
				 * deactivate the existing key before activating the new key.
				 */
				if ( $current_api_key != $api_key )
					$this->replace_license_key( $current_api_key );

				$args = array(
					'email' => $api_email,
					'licence_key' => $api_key,
					);

				$activate_results = $this->wc_api_manager_key->activate( $args );

				$activate_results = json_decode($activate_results, true);

				if ( $activate_results['activated'] == true ) {
					add_settings_error( 'activate_text', 'activate_msg', __( 'Plugin activated. ', $woocommerce_plugin_update_api_manager->text_domain ) . "{$activate_results['message']}.", 'updated' );
					update_option( 'wc_api_manager_activated', 'Activated' );
					update_option( 'wc_api_manager_deactivate_checkbox', 'off' );
				}

				if ( $activate_results == false ) {
					add_settings_error( 'api_key_check_text', 'api_key_check_error', __( 'Connection failed to the License Key API server. Try again later.', $woocommerce_plugin_update_api_manager->text_domain ), 'error' );
					$options['api_key'] = '';
					$options['activation_email'] = '';
					update_option( 'wc_api_manager_activated', 'Deactivated' );
				}

				if ( isset( $activate_results['code'] ) ) {

					switch ( $activate_results['code'] ) {
						case '100':
							add_settings_error( 'api_email_text', 'api_email_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							$options['activation_email'] = '';
							$options['api_key'] = '';
							update_option( 'wc_api_manager_activated', 'Deactivated' );
						break;
						case '101':
							add_settings_error( 'api_key_text', 'api_key_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							$options['api_key'] = '';
							$options['activation_email'] = '';
							update_option( 'wc_api_manager_activated', 'Deactivated' );
						break;
						case '102':
							add_settings_error( 'api_key_purchase_incomplete_text', 'api_key_purchase_incomplete_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							$options['api_key'] = '';
							$options['activation_email'] = '';
							update_option( 'wc_api_manager_activated', 'Deactivated' );
						break;
						case '103':
								add_settings_error( 'api_key_exceeded_text', 'api_key_exceeded_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
								$options['api_key'] = '';
								$options['activation_email'] = '';
								update_option( 'wc_api_manager_activated', 'Deactivated' );
						break;
						case '104':
								add_settings_error( 'api_key_not_activated_text', 'api_key_not_activated_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
								$options['api_key'] = '';
								$options['activation_email'] = '';
								update_option( 'wc_api_manager_activated', 'Deactivated' );
						break;
						case '105':
								add_settings_error( 'api_key_invalid_text', 'api_key_invalid_error', "{$activate_results['error']}", 'error' );
								$options['api_key'] = '';
								$options['activation_email'] = '';
								update_option( 'wc_api_manager_activated', 'Deactivated' );
						break;
						case '106':
								add_settings_error( 'sub_not_active_text', 'sub_not_active_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
								$options['api_key'] = '';
								$options['activation_email'] = '';
								update_option( 'wc_api_manager_activated', 'Deactivated' );
						break;
					}

				}

			} // End Plugin Activation

		}

		return $options;
	}

	public function get_key() {
		$wc_am_options = get_option('wc_api_manager');
		$api_key = $wc_am_options['api_key'];

		return $api_key;
	}

	// Deactivate the current license key before activating the new license key
	public function replace_license_key( $current_api_key ) {
		global $woocommerce_plugin_update_api_manager;

		$default_options = get_option( 'wc_api_manager' );

		$api_email = $default_options['activation_email'];

		$args = array(
			'email' => $api_email,
			'licence_key' => $current_api_key,
			);

		$reset = $this->wc_api_manager_key->deactivate( $args ); // reset license key activation

		if ( $reset == true )
			return true;

		return add_settings_error( 'not_deactivated_text', 'not_deactivated_error', __( 'The license could not be deactivated. Use the License Deactivation tab to manually deactivate the license before activating a new license.', $woocommerce_plugin_update_api_manager->text_domain ), 'updated' );
	}

	// Deactivates the license key to allow key to be used on another blog
	public function wc_am_license_key_deactivation( $input ) {
		global $woocommerce_plugin_update_api_manager;

		$activation_status = get_option( 'wc_api_manager_activated' );

		$default_options = get_option( 'wc_api_manager' );

		$api_email = $default_options['activation_email'];
		$api_key = $default_options['api_key'];

		$args = array(
			'email' => $api_email,
			'licence_key' => $api_key,
			);

		$options = ( $input == 'on' ? 'on' : 'off' );

		if ( $options == 'on' && $activation_status == 'Activated' && $api_key != '' && $api_email != '' ) {
			$reset = $this->wc_api_manager_key->deactivate( $args ); // reset license key activation

			if ( $reset == true ) {
				$update = array(
					'api_key' => '',
					'activation_email' => ''
					);
				$merge_options = array_merge( $default_options, $update );

				update_option( 'wc_api_manager', $merge_options );

				update_option( 'wc_api_manager_activated', 'Deactivated' );

				add_settings_error( 'wc_am_deactivate_text', 'deactivate_msg', __( 'Plugin license deactivated.', $woocommerce_plugin_update_api_manager->text_domain ), 'updated' );

				return $options;
			}

		} else {

			return $options;
		}

	}

	public function wc_am_deactivate_text() {
	}

	public function wc_am_deactivate_textarea() {
		global $woocommerce_plugin_update_api_manager;

		$activation_status = get_option( 'wc_api_manager_deactivate_checkbox' );

		?>
		<input type="checkbox" id="wc_am_deactivate_checkbox" name="wc_am_deactivate_checkbox" value="on" <?php checked( $activation_status, 'on' ); ?> />
		<?php $this->wc_api_manager_tool_tips->tips( 'deactivation' ); ?>
		<span class="description"><?php _e( 'Deactivates plugin license so it can be used on another blog.', $woocommerce_plugin_update_api_manager->text_domain ); ?></span>
		<?php
	}

	// Tech Support Information
	public function tech_support( $ts ) {
		global $wpdb, $woocommerce_plugin_update_api_manager;

		$version = get_option( 'wc_plugin_api_manager_version' );
		$activation_status = get_option( 'wc_api_manager_activated' );

		$php_version = phpversion();
		$db_version = $wpdb->db_version();
		$wordpress_version = get_bloginfo("version");

		switch ( $ts ) {
			case 'PHP Version':
				_e( $php_version, $woocommerce_plugin_update_api_manager->text_domain );
				break;
			case 'Database Version':
				_e( $db_version, $woocommerce_plugin_update_api_manager->text_domain );
				break;
			case 'WordPress Version':
				_e( $wordpress_version, $woocommerce_plugin_update_api_manager->text_domain );
				break;
			case 'API Manager Version':
				_e( $version, $woocommerce_plugin_update_api_manager->text_domain );
				break;
			case 'Activation Status':
				_e( $activation_status, $woocommerce_plugin_update_api_manager->text_domain );
				break;
		}
	}

	// Loads admin style sheets
	public function css_scripts() {
		global $woocommerce_plugin_update_api_manager;

		$curr_ver = $woocommerce_plugin_update_api_manager->version;

		wp_register_style( 'wc-am-tool-tips', $woocommerce_plugin_update_api_manager->plugin_url() . 'assets/css/tool-tips.css', array(), $curr_ver, 'all');
		wp_enqueue_style( 'wc-am-tool-tips' );
		wp_register_style( 'wc-am-admin-css', $woocommerce_plugin_update_api_manager->plugin_url() . 'assets/css/admin-settings.css', array(), $curr_ver, 'all');
		wp_enqueue_style( 'wc-am-admin-css' );
	}

	// Tooltip script
	public function javascript_scripts() {
		global $woocommerce_plugin_update_api_manager;

		$curr_ver = $woocommerce_plugin_update_api_manager->version;

		$js_path =  $woocommerce_plugin_update_api_manager->plugin_url() . 'assets/js/jquery.tipTip.minified.js';

		wp_register_script( 'tool-tip' , $js_path, null, $curr_ver );
		wp_enqueue_script( 'tool-tip', $js_path, array( 'jquery' ), $curr_ver );
	}

	// displays sidebar
	public function wc_am_sidebar() {
		global $woocommerce_plugin_update_api_manager;

		?>
		<h3><?php _e( 'Prevent Comment Spam', $woocommerce_plugin_update_api_manager->text_domain ); ?></h3>
		<ul class="celist">
			<li><a href="http://www.toddlahman.com/shop/simple-comments/" target="_blank"><?php _e( 'Simple Comments', $woocommerce_plugin_update_api_manager->text_domain ); ?></a></li>
		</ul>
		<h3><?php _e( 'Support', $woocommerce_plugin_update_api_manager->text_domain ); ?></h3>
		<p><strong><?php _e( "To get support peform the steps below in order:", $woocommerce_plugin_update_api_manager->text_domain ); ?></strong></p>
		<ol class="celist">
			<li><a href="http://www.toddlahman.com/my-account/" target="_blank"><?php _e( 'Login to toddlahman.com', $woocommerce_plugin_update_api_manager->text_domain ); ?></a></li>
			<li><a href="http://www.toddlahman.com/support/" target="_blank"><?php _e( 'Fill Out the Support Form', $woocommerce_plugin_update_api_manager->text_domain ); ?></a></li>
		</ol>
		<?php
	}

}

$wc_api_manager_menu = new WC_API_Manager_MENU();
