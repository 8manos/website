<?php
/**
 * Plugin Name: WooCommerce API Manager
 * Plugin URI: http://www.toddlahman.com/shop/woocommerce-plugin-and-theme-update-api-manager
 * Description: Provides and manages APIs for software API Key activation/deactivation and software updates.
 * Version: 1.2.1
 * Author: Todd Lahman LLC
 * Author URI: http://www.toddlahman.com
 * License: Copyright Todd Lahman LLC
 *
 *	Intellectual Property rights, and copyright, reserved by Todd Lahman, LLC as allowed by law incude,
 *	but are not limited to, the working concept, function, and behavior of this plugin,
 *	the logical code structure and expression as written.
 *
 *
 * @package     WooCommerce API Manager
 * @author      Todd Lahman LLC
 * @category    Plugin
 * @copyright   Copyright (c) 2011-2013, Todd Lahman LLC
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Required functions
 */
if ( ! function_exists( 'woothemes_queue_update' ) )
	require_once( 'woo-includes/woo-functions.php' );

/**
 * Plugin updates
 */
woothemes_queue_update( plugin_basename( __FILE__ ), 'f7cdcfb7de76afa0889f07bcb92bf12e', '260110' );

/**
 * If WooCommerce is not active disable WooCommerce API Manager.
 *
 * @since 1.0
 */
if ( ! is_woocommerce_active() ) {
	add_action( 'admin_notices', 'WooCommerce_Plugin_Update_API_Manager::woocommerce_inactive_notice' );
	return;
}

class WooCommerce_Plugin_Update_API_Manager {

	/**
	 * @var string
	 */
	public $wc_am_self_upgrade = false; // Should this product query the remote upgrade API for upgrades of itself?

	/**
	 * @var string
	 */
	public $version = '1.2.1';

	/**
	 * @var string
	 */
	public $wc_api_manager_version_name = 'wc_plugin_api_manager_version';

	/**
	 * @var string
	 */
	public $api_upgrade_url;

	/**
	 * @var string
	 */
	public $api_url_software;

	/**
	 * @var string
	 */
	public $plugin_url;

	/**
	 * @var string
	 */
	public $plugins_dir_url;

	/**
	 * @var string
	 */
	public $plugin_path;

	/**
	 * @var string
	 */
	public $plugins_basename;

	/**
	 * @var string
	 */
	public $wc_version;

	/**
	 * @var string
	 */
	public $text_domain = 'wc-api-manager';

	/**
	 * Self Upgrade Values
	 */
	// Base URL to the remote upgrade API server
	public $upgrade_url = 'http://www.toddlahman.com/';

	// URL to customer dashboard
	public $renew_license_url = 'https://www.toddlahman.com/my-account';

	// Is this a plugin or a theme?
	public $plugin_or_theme = 'plugin';

	/**
	 * Gets things started by adding an action to initialize this plugin once
	 * WooCommerce is known to be active and initialized
	 */
	public function __construct() {

		// Define WooCommerce Version
		$this->wc_version = get_option( 'woocommerce_version' );

		// Installation
		register_activation_hook( __FILE__, array( $this, 'activation' ) );

		require_once( $this->plugin_path() . '/classes/class-wc-api-helpers.php' );

		add_action( 'plugins_loaded', array( $this, 'remove_actions' ) );

		// Include required files
		$this->includes();

		// Installation
		if ( is_admin() && ! defined( 'DOING_AJAX' ) )
			$this->install();

		/**
		 * Upgrade API URL
		 * http://docs.woothemes.com/document/wc_api-the-woocommerce-api-callback/
		 */
		$this->api_upgrade_url = add_query_arg( 'wc-api', 'upgrade-api', site_url() );

		// Upgrade API hook
		add_action( 'woocommerce_api_upgrade-api', array( $this, 'handle_upgrade_api_request' ) );

		/**
		 * Software API URL
		 */
		$this->api_url_software = add_query_arg( 'wc-api', 'am-software-api', site_url() );

		// Software API hook
		add_action( 'woocommerce_api_am-software-api', array( $this, 'handle_software_api_request' ) );

		// Ready for translation
		load_plugin_textdomain( $this->text_domain, false, dirname( $this->plugins_basename() ) . '/i18n/languages' );

		add_action( 'woocommerce_admin_css', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );

		// AJAX
		add_action( 'wp_ajax_woocommerce_lost_api_key', array( $this, 'lost_api_key_ajax' ) );
		add_action( 'wp_ajax_nopriv_woocommerce_lost_api_key', array( $this, 'lost_api_key_ajax' ) );

		// Shortcodes
		add_shortcode( 'woocommerce_api_manager_lost_api_key', array( $this, 'lost_api_key_page' ) );

		// Display Update API Manager data on a User's account page
		add_action( 'woocommerce_before_my_account', array( $this, 'get_my_api_manger_account_template' ) );

		// Delete the user order array when the order is deleted
		//add_action( 'woocommerce_before_delete_order_item', array( $this, 'delete_user_order_info' ) );

		add_action( 'before_delete_post', array( $this, 'woocommerce_delete_order_items' ) );

		// Delete the user order array before the order is deleted
		add_action( 'woocommerce_before_delete_order_items', array( $this, 'delete_user_order_info' ) );

		/**
		 * Deletes all data if plugin deactivated
		 */
		if ( $this->wc_am_self_upgrade )
			register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );

	}

	/** Helper functions ******************************************************/

	/**
	 * Get the plugin's url.
	 *
	 * @access public
	 * @return string
	 */
	public function plugin_url() {
		if ( isset( $this->plugin_url ) ) return $this->plugin_url;
		return $this->plugin_url = plugins_url( '/', __FILE__ );
	}

	/**
	 * Get the plugin directory url.
	 *
	 * @access public
	 * @return string
	 */
	public function plugins_dir_url() {
		if ( isset( $this->plugins_dir_url ) ) return $this->plugins_dir_url;
		return $this->plugins_dir_url = plugin_dir_url( '/', __FILE__ );
	}

	/**
	 * Get the plugin path.
	 *
	 * @access public
	 * @return string
	 */
	public function plugin_path() {
		if ( isset( $this->plugin_path ) ) return $this->plugin_path;

		return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Get the plugin basename.
	 *
	 * @access public
	 * @return string
	 */
	public function plugins_basename() {
		if ( isset( $this->plugins_basename ) ) return $this->plugins_basename;

		return $this->plugins_basename = untrailingslashit( plugin_basename( __FILE__ ) );
	}

	/**
	 * Get Ajax URL.
	 *
	 * @return string
	 */
	public function ajax_url() {
		return admin_url( 'admin-ajax.php', 'relative' );
	}

	/**
	 * admin_scripts function.
	 */
	public function admin_styles() {
		wp_enqueue_style( 'woocommerce_api_manager_admin_styles', $this->plugin_url() . 'assets/css/admin.css', true );
	}

	public function enqueue_styles_scripts() {

		//$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		//wp_enqueue_script( 'woocommerce_api_manager_admin_scripts', $this->plugin_url() . 'assets/js/admin.js', array( 'jquery' ), filemtime( $this->plugin_path() . '/assets/js/admin.js' ), true  );

	}

	/**
	 * Register/queue frontend scripts.
	 *
	 * @access public
	 * @return void
	 */
	public function frontend_scripts() {

		// $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// wp_enqueue_script( 'woocommerce_api_manager', $this->plugin_url() . 'assets/js/frontend/' . 'api-manager' . $suffix . '.js', array( 'jquery' ), filemtime( $this->plugin_path() . '/assets/js/frontend/'  . 'api-manager' . $suffix . '.js' ), true  );

	}

	/**
	 * handle_upgrade_api_request function.
	 *
	 * @access public
	 * @return void
	 */
	public function handle_upgrade_api_request() {
		// Responds to plugin update requests
		require_once( $this->plugin_path() . '/classes/class-wc-upgrade-api.php' );
		new WC_Plugin_Update_API_Manager_API( $_REQUEST );
		die();
	}

	/**
	 * handle_software_api_request function.
	 *
	 * @access public
	 * @return void
	 */
	public function handle_software_api_request() {
		// Responds to plugin update requests
		require_once( $this->plugin_path() . '/classes/class-wc-upgrade-api-software.php' );
		new WC_Plugin_Update_API_Manager_Software_API( $_REQUEST );
		die();
	}

	/**
	 * remove_actions function.
	 *
	 * Emails new key template without license key activations number if Software Add-On is active,
	 * because it would otherwise be incorrect due to timing of database entries.
	 * See includes()
	 *
	 * @access public
	 * @return void
	 */
	public function remove_actions() {

		/**
		 * Had to remove before_delete_post hook then recreate hook in this file to allow Update API Manager
		 * to delete order data when an order is deleted by WooCommerce
		 */
		//if ( $this->wc_version < '2.1' )
		remove_action( 'before_delete_post', 'woocommerce_delete_order_items' );

		// For WooCommerce > 2.1
		// if ( $this->wc_version >= '2.1' )
		// 	remove_action( 'before_delete_post', array( 'WC_Admin_CPT_Shop_Order', 'delete_order_items' ) );

		// Disables the Software Add-on license key emails to avoid conflict with Upate API Manager key emails
		if ( WC_Api_Manager_Helpers::is_plugin_active( 'woocommerce-software-add-on/woocommerce-software.php' ) )
			remove_action( 'woocommerce_email_before_order_table', array( $GLOBALS['wc_software'], 'email_keys' ) );

	}

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @access public
	 * @return void
	 */
	public function includes() {

		if ( get_option( 'wc_api_manager_software_add_on_license_key' ) == 'yes' && WC_Api_Manager_Helpers::is_plugin_active( 'woocommerce-software-add-on/woocommerce-software.php' ) ) {

			/**
			 * saves user_meta data, and prepares email data for new key template that does not include license key
			 * activations number, because it would otherwise be incorrect due to timing of database entries between
			 * the Software Add-on and API Manager. Uses a later priority to insure correct data is saved in the license
			 * activation table.
			 */
			add_action( 'woocommerce_order_status_completed', array( 'WC_Api_Manager_Helpers', 'order_complete' ), 20 );

		} else {

			// order_complete function saves user_meta data to be used by the email template and the API Manager
			add_action( 'woocommerce_order_status_completed', array( 'WC_Api_Manager_Helpers', 'order_complete' ) );

		}

		add_action( 'woocommerce_email_before_order_table', array( $this, 'email_license_keys' ) );

		/**
		 * Initial orders control the subscription, not renewal orders, so a new license key does not need to be generated for subscription renewals
		 */
		// if ( WC_Api_Manager_Helpers::is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) && get_option( 'wc_api_manager_software_add_on_license_key' ) == 'yes' && WC_Api_Manager_Helpers::is_plugin_active( 'woocommerce-software-add-on/woocommerce-software.php' ) ) {
		// 	// adds a license key to an order if a subscription is renewed
		// 	add_action( 'woocommerce_order_status_completed', array( 'WC_Api_Manager_Helpers', 'renew_subscription_order_complete' ) );
		// }

		// plugin update download API hook
		add_action('init', array( 'WC_Api_Manager_Helpers', 'download_product' ) );

		// Handle customer request to delete a domain name from My Account
		add_action('init', array( 'WC_Api_Manager_Helpers', 'delete_my_account_url' ) );

		if ( is_admin() ) {

			// WooCommerce product pages
			require_once( $this->plugin_path() . '/classes/class-wc-api-manager-product-admin.php' );

			require_once( $this->plugin_path() . '/classes/class-wc-api-manager-order-admin.php' );

			// Classes only loaded if self upgrade set to true
			if ( $this->wc_am_self_upgrade ) {

				// License key verification for udpates
				require_once( $this->plugin_path() . '/classes/class-wc-key-api.php' );

				// Update check
				require_once( $this->plugin_path() . '/classes/class-wc-plugin-update.php' );

				// Settings admin page
				require_once( $this->plugin_path() . '/classes/class-wc-tool-tips.php' );
				require_once( $this->plugin_path() . '/admin/class-wc-api-manager-menu.php' );

				// Load update class to update $this plugin from for example toddlahman.com
				$this->load_plugin_self_updater();
			}

			// Load WooCommerce Dashboard Settings
			// if ( WC_Api_Manager_Helpers::is_plugin_active( 'woocommerce-software-add-on/woocommerce-software.php' ) )
			// 	require_once( $this->plugin_path() . '/admin/class-wc-api-manager-settings.php' );

		}
	}

	/**
	 * Check for $this plugin updates from for example toddlahman.com
	 */
	private function load_plugin_self_updater() {
		$options = get_option( 'wc_api_manager' );

		$upgrade_url = $this->upgrade_url;
		$plugin_name = $this->plugins_basename(); // same as plugin slug. if a theme use a theme name like 'twentyeleven'
		$product_id = get_option( 'wc_api_manager_product_id' );
		$api_key = $options['api_key'];
		$activation_email = $options['activation_email'];
		$renew_license_url = 'https://www.toddlahman.com/my-account';
		$instance = get_option( 'wc_api_manager_instance' );
		$domain = site_url();
		$software_version = get_option( 'wc_plugin_api_manager_version' );
		$plugin_or_theme = 'plugin'; // 'theme' or 'plugin'
		// $this->text_domain is used to defined localization for translation

		new WC_API_Manager_Plugin_Update_API_Check( $upgrade_url, $plugin_name, $product_id, $api_key, $activation_email, $renew_license_url, $instance, $domain, $software_version, $plugin_or_theme, $this->text_domain );
	}

	/**
	 * email_license_keys function. Emails Sofware Add-on license key or the order_key if the license free option is set.
	 *
	 * @access public
	 * @return void
	 */
	public function email_license_keys( $order ) {
		global $wpdb;

		// Emails new key template without license key activations number, because it would otherwise be incorrect due to timing of database entries.
		if ( get_option( 'wc_api_manager_software_add_on_license_key' ) == 'yes' && WC_Api_Manager_Helpers::is_plugin_active( 'woocommerce-software-add-on/woocommerce-software.php' ) ) {

			$licence_keys = $wpdb->get_results( "
				SELECT * FROM {$wpdb->prefix}woocommerce_software_licences
				WHERE order_id = {$order->id}
			" );

			woocommerce_get_template( 'email-license-keys.php', array(
				'keys'	=> $licence_keys
			), 'woocommerce-software', $this->plugin_path() . '/templates/' );

		} else { // Emails order_key instead of license key provided by Software Add-on

			$current_info = WC_Api_Manager_Helpers::get_users_data( $order->user_id );

			woocommerce_get_template( 'email-order-keys.php', array(
				'order_key' => $order->order_key,
				'keys'		=> $current_info
			), 'woocommerce-api-manager', $this->plugin_path() . '/templates/' );

		}

	}

	/** AJAX *****************************************************************/

	public function lost_api_key_ajax() {
		global $woocommerce;

		check_ajax_referer( 'wc-lost-api-key', 'security' );

		$email = esc_attr( trim( $_POST['email'] ) );

		if ( ! is_email( $email ) )
			die( json_encode( array(
				'success' 	=> false,
				'message'	=> __( 'Invalid Email Address', $this->text_domain )
			) ) );

		// returns $user->ID
		$user = get_user_by( 'email', $email );

		if ( is_object( $user ) ) {

			// Get the orders for this customer
			$user_orders = WC_Api_Manager_Helpers::get_users_data( $user->ID );

			if ( isset( $user_orders ) ) {

				foreach ( $user_orders as $order_key => $data ) {

					// Find the API keys that are part of the same data array as the email address
					if ( $data['license_email'] == $email ) {

						$api_keys[] = $data['order_key'];

					}
				}

				// Populate an order data array that only matches the API keys
				foreach ( $api_keys as $key => $api_key ) {

					$order_info[] = $user_orders[$api_key];

				}

			}
		}

		if ( count( $order_info ) > 0 ) {

			ob_start();

			$mailer = $woocommerce->mailer();

			woocommerce_get_template( 'email-lost-api-keys.php', array(
				'keys'	=> $order_info,
				'email_heading' => __( 'Your API keys', $this->text_domain )
			), 'woocommerce-api-manager', $this->plugin_path() . '/templates/' );

			$message = ob_get_clean();

			woocommerce_mail( $email, __( 'Your API keys', $this->text_domain ), $message );

			die( json_encode( array(
				'success' 	=> true,
				'message'	=> __( 'Your API keys have been emailed', $this->text_domain )
			) ) );

		} else {

			die( json_encode( array(
				'success' 	=> false,
				'message'	=> __( 'No API keys were found for your email address', $this->text_domain )
			) ) );

		}

	}

	/** Templates ************************************************************/

	/**
	 * lost_api_key_page function.
	 *
	 * @access public
	 */
	public function lost_api_key_page() {

		woocommerce_get_template( 'lost-api-key.php', '', 'woocommerce-api-manager', $this->plugin_path() . '/templates/' );

	}

	/**
	 * Loads the my-api-manager.php template on the My Account page.
	 * @return [type] [description]
	 */
	public function get_my_api_manger_account_template() {

		$user_id = get_current_user_id();

		woocommerce_get_template( 'my-api-manager.php', array( 'user_id' => $user_id ), '', $this->plugin_path() . '/templates/' );

	}

	/** Core WooCommerce Function ********************************************/

	/**
	 * This is a core WooCommerce function
	 *
	 * Remove item meta on permanent deletion
	 *
	 * @access public
	 * @return void
	 **/
	public function woocommerce_delete_order_items( $postid ) {
		global $wpdb;

		// Hook added for Upgrade API Manager to be able to delete user order data before WC order deletion
		do_action( 'woocommerce_before_delete_order_items', $postid );

		if ( get_post_type( $postid ) == 'shop_order' ) {
			$wpdb->query( "
				DELETE {$wpdb->prefix}woocommerce_order_items, {$wpdb->prefix}woocommerce_order_itemmeta
				FROM {$wpdb->prefix}woocommerce_order_items
				JOIN {$wpdb->prefix}woocommerce_order_itemmeta ON {$wpdb->prefix}woocommerce_order_items.order_item_id = {$wpdb->prefix}woocommerce_order_itemmeta.order_item_id
				WHERE {$wpdb->prefix}woocommerce_order_items.order_id = '{$postid}';
				" );
		}

		do_action( 'woocommerce_after_delete_order_items', $postid );

	}

	/**
	 * delete_user_order_info Deletes user order and activations data when the corresponding order is deleted
	 *
	 * @since 1.1.1
	 * @param  int $post_id
	 * @return void
	 */
	public function delete_user_order_info( $post_id ) {
		global $wpdb;

		/**
		 * Was using woocommerce_before_delete_order_item hook with $item_id arg, but didn't work
		 * because woocommerce_delete_order_items() already deleted the item from
		 * {$wpdb->prefix}woocommerce_order_items
		 */

		// $item_id = absint( $item_id );

		// if ( ! $item_id )
		// 	return false;

		// $order_id = $wpdb->get_var( $wpdb->prepare( "
		// 	SELECT order_id FROM {$wpdb->prefix}woocommerce_order_items
		// 	WHERE order_item_id = %d
		// 	LIMIT 1
		// ", $item_id ) );

		$order_meta = get_post_meta( $post_id );

		if ( isset( $order_meta ) && ! empty( $order_meta ) && is_array( $order_meta ) ) {

			$order_key 	= get_post_meta( $post_id, '_order_key', true );
			$user_id 	= get_post_meta( $post_id, '_customer_user', true );

		}

		/** Delete the Activation data ********************************************/

		delete_user_meta( $user_id, $wpdb->get_blog_prefix() . WC_Api_Manager_Helpers::$user_meta_key_activations . $order_key );

		/** Delete the Order data ********************************************/

		// All user orders in a multidimensional array each indexed by order_key
		$user_orders = WC_Api_Manager_Helpers::get_users_data( $user_id );

		if ( isset( $user_orders ) && is_array( $user_orders ) && ! empty( $user_orders ) ) {

			$active_orders = count( $user_orders );

			if ( $active_orders <= 1 ) {

				delete_user_meta( $user_id, $wpdb->get_blog_prefix() . WC_Api_Manager_Helpers::$user_meta_key_orders );

			} else {

				// Delete the activation data array
    			unset( $user_orders[$order_key] );

				update_user_meta( $user_id, $wpdb->get_blog_prefix() . WC_Api_Manager_Helpers::$user_meta_key_orders, $user_orders );

			}

		}

	}

	private function install() {

		$curr_ver = get_option( $this->wc_api_manager_version_name );

		// checks if the current plugin version is lower than the version being installed
		if ( version_compare( $this->version, $curr_ver, '>' ) ) {
			// update the version
			update_option( $this->wc_api_manager_version_name, $this->version );
		}
	}

	/**
	 * Displays an inactive notice when WooCommerce is inactive.
	 *
	 * @since 1.0
	 */
	public static function woocommerce_inactive_notice() { ?>
		<div id="message" class="error">
			<p><?php printf( __( '%sWooCommerce Plugin and Theme Update API Manager is inactive.%s The %sWooCommerce%s plugin must be active for the WooCommerce Plugin and Theme Update API Manager to work. Please activate WooCommerce on the %splugin page%s once it is installed.', $this->text_domain ), '<strong>', '</strong>', '<a href="http://wordpress.org/extend/plugins/woocommerce/" target="_blank">', '</a>', '<a href="' . admin_url( 'plugins.php' ) . '">', '</a>' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Handles tasks when plugin is activated
	 */
	public function activation() {
		global $wpdb;

		if ( $this->wc_am_self_upgrade ) {

			$global_options = array(
				'api_key' 			=> '',
				'activation_email' 	=> '',
						);

			update_option( 'wc_api_manager', $global_options );

			require_once( plugin_dir_path( __FILE__ ) . 'classes/class-wc-api-manager-passwords.php' );

			$wc_api_manager_password_management = new WC_API_Manager_Password_Management();

			// Generate a unique installation $instance id
			$instance = $wc_api_manager_password_management->generate_password( 12, false );

			$single_options = array(
				'wc_api_manager_product_id' 			=> 'WooCommerce Upgrade API Manager',
				'wc_api_manager_instance' 				=> $instance,
				'wc_api_manager_deactivate_checkbox' 	=> 'on',
				'wc_api_manager_activated' 				=> 'Deactivated',
				);

			foreach ( $single_options as $key => $value ) {
				update_option( $key, $value );
			}

		}

		// Create the lost API key page
		$lost_api_key_page_id = get_option( 'woocommerce_lost_license_page_id' );

		// Creates the lost API key page with the right shortcode in it
		$slug = 'lost-api-key';
		$found = $wpdb->get_var( "SELECT ID FROM " . $wpdb->posts . " WHERE post_name = '$slug' LIMIT 1;" );

		if ( empty( $lost_api_key_page_id ) || ! $found ) {
			$lost_api_key_page = array(
				'post_title' 	=> _x( 'Lost API Key', 'Title of a page', $this->text_domain ),
				'post_content' 	=> '[woocommerce_api_manager_lost_api_key]',
				'post_status' 	=> 'publish',
				'post_type' 	=> 'page',
				'post_name' 	=> $slug,
			);
			$lost_api_key_page_id = (int) wp_insert_post( $lost_api_key_page );
			update_option( 'woocommerce_lost_license_page_id', $lost_api_key_page_id );
		}

	}

	/**
	 * Deletes all data if plugin deactivated
	 * @return void
	 */
	public function deactivation() {
		global $wpdb, $blog_id;

		$this->license_key_deactivation();

		// Remove options
		if ( is_multisite() ) {

			switch_to_blog( $blog_id );

			foreach ( array(
					'wc_api_manager',
					'wc_api_manager_product_id',
					'wc_api_manager_instance',
					'wc_api_manager_deactivate_checkbox',
					'wc_api_manager_activated',
					'wc_plugin_api_manager_version',
					) as $option) {

					delete_option( $option );

					}

			restore_current_blog();

		} else {

			foreach ( array(
					'wc_api_manager',
					'wc_api_manager_product_id',
					'wc_api_manager_instance',
					'wc_api_manager_deactivate_checkbox',
					'wc_api_manager_activated',
					'wc_plugin_api_manager_version',
					) as $option) {

					delete_option( $option );

					}

		}

	}

	/**
	 * Deactivates the license on the API server
	 * @return void
	 */
	public function license_key_deactivation() {

		$wc_api_manager_key = new WC_Api_Manager_Key();

		$activation_status = get_option( 'wc_api_manager_activated' );

		$default_options = get_option( 'wc_api_manager' );

		$api_email = $default_options['activation_email'];
		$api_key = $default_options['api_key'];

		$args = array(
			'email' => $api_email,
			'licence_key' => $api_key,
			);

		if ( $activation_status == 'Activated' && $api_key != '' && $api_email != '' ) {
			$wc_api_manager_key->deactivate( $args ); // reset license key activation
		}
	}


} // End class

$GLOBALS['woocommerce_plugin_update_api_manager'] = new WooCommerce_Plugin_Update_API_Manager();
