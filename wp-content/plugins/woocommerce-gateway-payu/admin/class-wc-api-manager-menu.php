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

class API_Manager_Example_MENU {

	private $api_manager_example_key;

	// Load admin menu
	public function __construct() {

		$this->api_manager_example_key = new Api_Manager_Example_Key();

		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_init', array( $this, 'load_settings' ) );
	}

	// Add option page menu
	public function add_menu() {
		global $api_manager_example;

		$page = add_options_page( __( 'WC Gateway PayU Latam License', $api_manager_example->text_domain ), __( 'WC Gateway PayU Latam License', $api_manager_example->text_domain ),
						'manage_options', 'api_manager_example_dashboard', array( $this, 'config_page')
		);
		add_action( 'admin_print_styles-' . $page, array( $this, 'css_scripts' ) );
	}

	// Draw option page
	public function config_page() {
		global $api_manager_example;

		$settings_tabs = array( 'api_manager_example_dashboard' => __( 'Dashboard', $api_manager_example->text_domain ), 'api_manager_example_deactivation' => __( 'License Deactivation', $api_manager_example->text_domain ) );
		$current_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'api_manager_example_dashboard';
		$tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'api_manager_example_dashboard';
		?>
		<div class='wrap'>
			<?php screen_icon(); ?>
			<h2><?php _e( 'WC Gateway PayU Latam License', $api_manager_example->text_domain ); ?></h2>

			<h2 class="nav-tab-wrapper">
			<?php
				foreach ( $settings_tabs as $tab_page => $tab_name ) {
					$active_tab = $current_tab == $tab_page ? 'nav-tab-active' : '';
					echo '<a class="nav-tab ' . $active_tab . '" href="?page=api_manager_example_dashboard&tab=' . $tab_page . '">' . $tab_name . '</a>';
				}
			?>
			</h2>
				<form action='options.php' method='post'>
				<div class="main">
			<?php
				if( $tab == 'api_manager_example_dashboard' ) {
						settings_fields( 'api_manager_example' );
						do_settings_sections( 'api_manager_example_dashboard' );
							$wc_am_save_changes = __( 'Save Changes', $api_manager_example->text_domain );
							submit_button( $wc_am_save_changes );
				} else {
						settings_fields( 'am_deactivate_example_checkbox' );
						do_settings_sections( 'api_manager_example_deactivation' );
							$wc_am_save_changes_activation = __( 'Save Changes', $api_manager_example->text_domain );
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
		global $api_manager_example;

		register_setting( 'api_manager_example', 'api_manager_example', array( $this, 'validate_options' ) );

		// API Key
		add_settings_section( 'api_key', __( 'License Information', $api_manager_example->text_domain ), array( $this, 'wc_am_api_key_text' ), 'api_manager_example_dashboard' );
		add_settings_field( 'api_key', __( 'License Key', $api_manager_example->text_domain ), array( $this, 'wc_am_api_key_field' ), 'api_manager_example_dashboard', 'api_key' );
		add_settings_field( 'api_email', __( 'License email', $api_manager_example->text_domain ), array( $this, 'wc_am_api_email_field' ), 'api_manager_example_dashboard', 'api_key' );

		// Activation settings
		register_setting( 'am_deactivate_example_checkbox', 'am_deactivate_example_checkbox', array( $this, 'wc_am_license_key_deactivation' ) );
		add_settings_section( 'deactivate_button', __( 'Plugin License Deactivation', $api_manager_example->text_domain ), array( $this, 'wc_am_deactivate_text' ), 'api_manager_example_deactivation' );
		add_settings_field( 'deactivate_button', __( 'Deactivate Plugin License', $api_manager_example->text_domain ), array( $this, 'wc_am_deactivate_textarea' ), 'api_manager_example_deactivation', 'deactivate_button' );

	}

	// Provides text for api key section
	public function wc_am_api_key_text() {
		//
	}

	// Outputs API License text field
	public function wc_am_api_key_field() {
		global $api_manager_example;

		$options = get_option( 'api_manager_example' );
		$api_key = $options['api_key'];
		echo "<input id='api_key' name='api_manager_example[api_key]' size='25' type='text' value='{$options['api_key']}' />";
		if ( !empty( $options['api_key'] ) ) {
			echo "<span class='icon-pos'><img src='" . $api_manager_example->plugin_url() . "assets/images/complete.png' title='' style='padding-bottom: 4px; vertical-align: middle; margin-right:3px;' /></span>";
		} else {
			echo "<span class='icon-pos'><img src='" . $api_manager_example->plugin_url() . "assets/images/warn.png' title='' style='padding-bottom: 4px; vertical-align: middle; margin-right:3px;' /></span>";
		}
	}

	// Outputs API License email text field
	public function wc_am_api_email_field() {
		global $api_manager_example;

		$options = get_option( 'api_manager_example' );
		$activation_email = $options['activation_email'];
		echo "<input id='activation_email' name='api_manager_example[activation_email]' size='25' type='text' value='{$options['activation_email']}' />";
		if ( !empty( $options['activation_email'] ) ) {
			echo "<span class='icon-pos'><img src='" . $api_manager_example->plugin_url() . "assets/images/complete.png' title='' style='padding-bottom: 4px; vertical-align: middle; margin-right:3px;' /></span>";
		} else {
			echo "<span class='icon-pos'><img src='" . $api_manager_example->plugin_url() . "assets/images/warn.png' title='' style='padding-bottom: 4px; vertical-align: middle; margin-right:3px;' /></span>";
		}
	}

	// Sanitizes and validates all input and output for Dashboard
	public function validate_options( $input ) {
		global $api_manager_example;

		// Load existing options, validate, and update with changes from input before returning
		$options = get_option( 'api_manager_example' );

		$options['api_key'] = trim( $input['api_key'] );
		$options['activation_email'] = trim( $input['activation_email'] );

		/**
		  * Plugin Activation
		  */
		$api_email = trim( $input['activation_email'] );
		$api_key = trim( $input['api_key'] );

		$activation_status = get_option( 'api_manager_example_activated' );
		$checkbox_status = get_option( 'am_deactivate_example_checkbox' );

		$current_api_key = $this->get_key();

		// Should match the settings_fields() value
		if ( $_REQUEST['option_page'] != 'am_deactivate_example_checkbox' ) {

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

				$activate_results = $this->api_manager_example_key->activate( $args );

				$activate_results = json_decode($activate_results, true);

				if ( $activate_results['activated'] == true ) {
					add_settings_error( 'activate_text', 'activate_msg', __( 'Plugin activated. ', $api_manager_example->text_domain ) . "{$activate_results['message']}.", 'updated' );
					update_option( 'api_manager_example_activated', 'Activated' );
					update_option( 'am_deactivate_example_checkbox', 'off' );
				}

				if ( $activate_results == false ) {
					add_settings_error( 'api_key_check_text', 'api_key_check_error', __( 'Connection failed to the License Key API server. Try again later.', $api_manager_example->text_domain ), 'error' );
					$options['api_key'] = '';
					$options['activation_email'] = '';
					update_option( 'api_manager_example_activated', 'Deactivated' );
				}

				if ( isset( $activate_results['code'] ) ) {

					switch ( $activate_results['code'] ) {
						case '100':
							add_settings_error( 'api_email_text', 'api_email_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							$options['activation_email'] = '';
							$options['api_key'] = '';
							update_option( 'api_manager_example_activated', 'Deactivated' );
						break;
						case '101':
							add_settings_error( 'api_key_text', 'api_key_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							$options['api_key'] = '';
							$options['activation_email'] = '';
							update_option( 'api_manager_example_activated', 'Deactivated' );
						break;
						case '102':
							add_settings_error( 'api_key_purchase_incomplete_text', 'api_key_purchase_incomplete_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
							$options['api_key'] = '';
							$options['activation_email'] = '';
							update_option( 'api_manager_example_activated', 'Deactivated' );
						break;
						case '103':
								add_settings_error( 'api_key_exceeded_text', 'api_key_exceeded_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
								$options['api_key'] = '';
								$options['activation_email'] = '';
								update_option( 'api_manager_example_activated', 'Deactivated' );
						break;
						case '104':
								add_settings_error( 'api_key_not_activated_text', 'api_key_not_activated_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
								$options['api_key'] = '';
								$options['activation_email'] = '';
								update_option( 'api_manager_example_activated', 'Deactivated' );
						break;
						case '105':
								add_settings_error( 'api_key_invalid_text', 'api_key_invalid_error', "{$activate_results['error']}", 'error' );
								$options['api_key'] = '';
								$options['activation_email'] = '';
								update_option( 'api_manager_example_activated', 'Deactivated' );
						break;
						case '106':
								add_settings_error( 'sub_not_active_text', 'sub_not_active_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
								$options['api_key'] = '';
								$options['activation_email'] = '';
								update_option( 'api_manager_example_activated', 'Deactivated' );
						break;
					}

				}

			} // End Plugin Activation

		}

		return $options;
	}

	public function get_key() {
		$wc_am_options = get_option('api_manager_example');
		$api_key = $wc_am_options['api_key'];

		return $api_key;
	}

	// Deactivate the current license key before activating the new license key
	public function replace_license_key( $current_api_key ) {
		global $api_manager_example;

		$default_options = get_option( 'api_manager_example' );

		$api_email = $default_options['activation_email'];

		$args = array(
			'email' => $api_email,
			'licence_key' => $current_api_key,
			);

		$reset = $this->api_manager_example_key->deactivate( $args ); // reset license key activation

		if ( $reset == true )
			return true;

		return add_settings_error( 'not_deactivated_text', 'not_deactivated_error', __( 'The license could not be deactivated. Use the License Deactivation tab to manually deactivate the license before activating a new license.', $api_manager_example->text_domain ), 'updated' );
	}

	// Deactivates the license key to allow key to be used on another blog
	public function wc_am_license_key_deactivation( $input ) {
		global $api_manager_example;

		$activation_status = get_option( 'api_manager_example_activated' );

		$default_options = get_option( 'api_manager_example' );

		$api_email = $default_options['activation_email'];
		$api_key = $default_options['api_key'];

		$args = array(
			'email' => $api_email,
			'licence_key' => $api_key,
			);

		$options = ( $input == 'on' ? 'on' : 'off' );

		if ( $options == 'on' && $activation_status == 'Activated' && $api_key != '' && $api_email != '' ) {
			$reset = $this->api_manager_example_key->deactivate( $args ); // reset license key activation

			if ( $reset == true ) {
				$update = array(
					'api_key' => '',
					'activation_email' => ''
					);
				$merge_options = array_merge( $default_options, $update );

				update_option( 'api_manager_example', $merge_options );

				update_option( 'api_manager_example_activated', 'Deactivated' );

				add_settings_error( 'wc_am_deactivate_text', 'deactivate_msg', __( 'Plugin license deactivated.', $api_manager_example->text_domain ), 'updated' );

				return $options;
			}

		} else {

			return $options;
		}

	}

	public function wc_am_deactivate_text() {
	}

	public function wc_am_deactivate_textarea() {
		global $api_manager_example;

		$activation_status = get_option( 'am_deactivate_example_checkbox' );

		?>
		<input type="checkbox" id="am_deactivate_example_checkbox" name="am_deactivate_example_checkbox" value="on" <?php checked( $activation_status, 'on' ); ?> />
		<span class="description"><?php _e( 'Deactivates plugin license so it can be used on another blog.', $api_manager_example->text_domain ); ?></span>
		<?php
	}

	// Loads admin style sheets
	public function css_scripts() {
		global $api_manager_example;

		$curr_ver = $api_manager_example->version;

		wp_register_style( 'am-admin-example-css', $api_manager_example->plugin_url() . 'assets/css/admin-settings.css', array(), $curr_ver, 'all');
		wp_enqueue_style( 'am-admin-example-css' );
	}

	// displays sidebar
	public function wc_am_sidebar() {
		?>
		<h3><?php _e( 'Prevent Comment Spam', $api_manager_example->text_domain ); ?></h3>
		<ul class="celist">
			<li><a href="http://www.toddlahman.com/shop/simple-comments/" target="_blank"><?php _e( 'Simple Comments', $api_manager_example->text_domain ); ?></a></li>
		</ul>
		<?php
	}

}

$api_manager_example_menu = new API_Manager_Example_MENU();
