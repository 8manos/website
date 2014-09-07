<?php
/*
Plugin Name: WooCommerce Gateway PayU Latam
Plugin URI: http://8manos.com/
Description: Extends WooCommerce. Provides a PayU Latam (http://www.payulatam.com/) payment gateway for WooCommerce.
Version: 1.0.1
Author: 8manos S.A.S
Author URI: http://8manos.com/

Copyright: © 2013 8manos S.A.S (email: plugins@8manos.com)
*/

// Run the activation function
register_activation_hook( __FILE__, 'activation' );

/**
 * Deletes all data if plugin deactivated
 */
register_deactivation_hook( __FILE__, 'uninstall' );

/**
 * Generate the default data arrays
 */
function activation() {
	global $wpdb;

	$global_options = array(
		'api_key' 			=> '',
		'activation_email' 	=> '',
				);

	update_option( 'api_manager_example', $global_options );

	require_once( plugin_dir_path( __FILE__ ) . 'classes/class-wc-api-manager-passwords.php' );

	$API_Manager_Example_Password_Management = new API_Manager_Example_Password_Management();

	// Generate a unique installation $instance id
	$instance = $API_Manager_Example_Password_Management->generate_password( 12, false );

	$single_options = array(
		'api_manager_example_product_id' 			=> 'WC Gateway PayU Latam License',
		'api_manager_example_instance' 				=> $instance,
		'api_manager_example_deactivate_checkbox' 	=> 'on',
		'api_manager_example_activated' 			=> 'Deactivated',
		);

	foreach ( $single_options as $key => $value ) {
		update_option( $key, $value );
	}
}

/**
 * Deletes all data if plugin deactivated
 * @return void
 */
function uninstall() {
	global $wpdb, $blog_id;

	license_key_deactivation();

	// Remove options
	if ( is_multisite() ) {

		switch_to_blog( $blog_id );

		foreach ( array(
				'api_manager_example',
				'api_manager_example_product_id',
				'api_manager_example_instance',
				'api_manager_example_deactivate_checkbox',
				'api_manager_example_activated',
				'bf_version'
				) as $option) {

				delete_option( $option );

				}

		restore_current_blog();

	} else {

		foreach ( array(
				'api_manager_example',
				'api_manager_example_product_id',
				'api_manager_example_instance',
				'api_manager_example_deactivate_checkbox',
				'api_manager_example_activated'
				) as $option) {

				delete_option( $option );

				}

	}

}

/**
 * Deactivates the license on the API server
 * @return void
 */
function license_key_deactivation() {

	$api_manager_example_key = new Api_Manager_Example_Key();

	$activation_status = get_option( 'api_manager_example_activated' );

	$default_options = get_option( 'api_manager_example' );

	$api_email = $default_options['activation_email'];
	$api_key = $default_options['api_key'];

	$args = array(
		'email' => $api_email,
		'licence_key' => $api_key,
		);

	if ( $activation_status == 'Activated' && $api_key != '' && $api_email != '' ) {
		$api_manager_example_key->deactivate( $args ); // reset license key activation
	}
}


/*
 * Plugin loaded after WooCommerce
 */
add_action('plugins_loaded', 'woocommerce_gateway_payu_init', 0);

function woocommerce_gateway_payu_init() {

	if ( !class_exists( 'WC_Payment_Gateway' ) ) return;

	/**
	 * Localization
	 */
	load_plugin_textdomain('wc-gateway-payu', false, dirname( plugin_basename( __FILE__ ) ) . '/languages');

	/**
	 * Gateway class
	 */
	class WC_Gateway_Payu extends WC_Payment_Gateway {

		/**
		 * WC API Manager vars
		 */
		// Base URL to the remote upgrade API server
		public $upgrade_url = 'http://8manos.com/';
		public $version = '1.0.1';
		public $api_manager_example_version_name = 'woocommerce_gateway_payu_latam_1_0';
		public $plugin_url;
		public $text_domain = 'wc-gateway-payu';

		var $notify_url;

		var $pol_codes = array(
			1 => 'Transacción aprobada',
			2 => 'Pago cancelado por el usuario',
			3 => 'Pago cancelado por el usuario durante validación',
			4 => 'Transacción rechazada por la entidad',
			5 => 'Transacción declinada por la entidad',
			6 => 'Fondos insuficientes',
			7 => 'Tarjeta invalida',
			8 => 'Es necesario contactar a la entidad',
			9 => 'Tarjeta vencida',
			10 => 'Tarjeta restringida',
			11 => 'Discrecional POL',
			12 => 'Fecha de expiracióno o campo seg. Inválidos',
			13 => 'Repita transacción',
			14 => 'Transacción inválida',
			15 => 'Transacción en proceso de validación',
			16 => 'Combinación usuario-contraseña inválidos',
			17 => 'Monto excede máximo permitido por entidad',
			18 => 'Documento de identificación inválido',
			19 => 'Transacción abandonada capturando datos TC',
			20 => 'Transacción abandonada',
			21 => 'Imposible reversar transacción',
			22 => 'Tarjeta no autorizada para realizar compras por internet.',
			23 => 'Transacción rechazada por el Modulo Antifraude',
			24 => 'Transacción parcial aprobada',
			25 => 'Rechazada por no confirmación',
			26 => 'Comprobante generado, esperando pago en banco',
			50 => 'Transacción Expirada, antes de ser enviada a la red del medio de pago',
			51 => 'Ocurrió un error en el procesamiento por parte de la Red del Medio de Pago',
			52 => 'El medio de Pago no se encuentra Activo. No se envía la solicitud a la red del mismo',
			53 => 'Banco no disponible',
			54 => 'El proveedor del Medio de Pago notifica que no fue aceptada la transacción',
			55 => 'Error convirtiendo el monto de la transacción',
			56 => 'Error convirtiendo montos del deposito',
			9994 => 'Transacción pendiente por confirmar',
			9995 => 'Certificado digital no encontrado',
			9996 => 'Entidad no responde',
			9997 => 'Error de mensajería con la entidad financiera',
			9998 => 'Error en la entidad financiera',
			9999 => 'Error no especificado',
			10000 => 'Ajustado Automáticamente',
			10001 => 'Ajuste Automático y Reversión Exitosa',
			10002 => 'Ajuste Automático y Reversión Fallida',
			10003 => 'Ajuste automático no soportado',
			10004 => 'Error en el Ajuste',
			10005 => 'Error en el ajuste y reversión'
		);

		function __construct() {
			global $woocommerce;

			if ( is_admin() ) {

				// Performs activations and deactivations of API License Keys
				require_once( plugin_dir_path( __FILE__ ) . 'classes/class-wc-key-api.php' );

				// Checks for software updatess
				require_once( plugin_dir_path( __FILE__ ) . 'classes/class-wc-plugin-update.php' );

				// Admin menu with the license key and license email form
				require_once( plugin_dir_path( __FILE__ ) . 'admin/class-wc-api-manager-menu.php' );

				// Load update class to update $this plugin from for example toddlahman.com
				$this->load_plugin_self_updater();

			}

			$activation_status = get_option( 'api_manager_example_activated' );

			$default_options = get_option( 'api_manager_example' );
			$api_email = $default_options['activation_email'];
			$api_key = $default_options['api_key'];

			if ( $activation_status == 'Deactivated'|| $api_key == '' && $api_email == '' ) {
				return;
			}

			$this->id                 = 'payu';
			$this->icon               = plugins_url('images/logo_by_pol.png', __FILE__);
			$this->has_fields         = false;
			$this->method_title       = 'PayU Latam';
			$this->method_description = __( 'PayU Latam works by sending the user to <a href="http://www.payulatam.com/">payulatam</a> to enter their payment information.', 'wc-gateway-payu' );

			$this->liveurl    = 'https://gateway.payulatam.com/ppp-web-gateway/';
			$this->testurl    = 'https://stg.gateway.payulatam.com/ppp-web-gateway/';
			$this->notify_url = str_replace( 'https:', 'http:', add_query_arg( 'wc-api', 'WC_Gateway_Payu', home_url( '/' ) ) );

			// Load the form fields.
			$this->init_form_fields();
			// Load the settings.
			$this->init_settings();

			$this->enabled     = $this->get_option('enabled');
			$this->title       = $this->get_option('title');
			$this->description = $this->get_option('description');
			$this->merchantId  = $this->get_option('merchantId');
			$this->apiKey      = $this->get_option('apiKey');
			$this->testmode    = $this->get_option('testmode');
			$this->debug       = $this->get_option( 'debug' );

			// Logs
			if ( $this->debug == 'yes' )
				$this->log = $woocommerce->logger();

			add_action( 'woocommerce_update_options_payment_gateways_'.$this->id, array($this, 'process_admin_options') );

			//to generate PayU form (submit it automatically using javascript)
			add_action( 'woocommerce_receipt_payu', array( $this, 'receipt_page' ) );

			// Payment listener/API hook
			add_action( 'woocommerce_api_wc_gateway_payu', array( $this, 'check_pagos_response' ) );
		}

		public function plugin_url() {
			if ( isset( $this->plugin_url ) ) return $this->plugin_url;
			return $this->plugin_url = plugins_url( '/', __FILE__ );
		}

		/**
		 * Check for software updates
		 */
		public function load_plugin_self_updater() {
			$options = get_option( 'api_manager_example' );

			$upgrade_url = $this->upgrade_url; // URL to access the Update API Manager.
			$plugin_name = untrailingslashit( plugin_basename( __FILE__ ) ); // same as plugin slug. if a theme use a theme name like 'twentyeleven'
			$product_id = get_option( 'woocommerce_gateway_payu_latam' ); // Software Title
			$api_key = $options['api_key']; // API License Key
			$activation_email = $options['activation_email']; // License Email
			$renew_license_url = 'http://8manos.com/mi-cuenta/'; // URL to renew a license
			$instance = get_option( 'api_manager_example_instance' ); // Instance ID (unique to each blog activation)
			$domain = substr(site_url(), 7); // blog domain name
			$software_version = get_option( $this->api_manager_example_version_name ); // The software version
			$plugin_or_theme = 'plugin'; // 'theme' or 'plugin'
			// $this->text_domain is used to defined localization for translation

			new API_Manager_Example_Update_API_Check( $upgrade_url, $plugin_name, $product_id, $api_key, $activation_email, $renew_license_url, $instance, $domain, $software_version, $plugin_or_theme, $this->text_domain );
		}

		/**
		 * Initialize Gateway Settings Form Fields
		 */
		function init_form_fields() {
			$this->form_fields = array(
				'enabled' => array(
					'title' => __( 'Enable/Disable', 'woocommerce' ),
					'type' => 'checkbox',
					'label' => __( 'Enable PayU Latam', 'wc-gateway-payu' ),
					'default' => 'yes'
				),
				'title' => array(
					'title' => __( 'Title', 'woocommerce' ),
					'type' => 'text',
					'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
					'default' => __( 'PayU Latam', 'woocommerce' ),
					'desc_tip' => true,
				),
				'description' => array(
					'title' => __( 'Description', 'woocommerce' ),
					'type' => 'textarea',
					'description' => __( 'This controls the description which the user sees during checkout.', 'woocommerce' ),
					'default' => 'PayU. Recibe pagos en internet en latinoamérica desde cualquier parte del mundo.'
				),
				'merchantId' => array(
					'title' => 'ID comercio',
					'type' => 'text',
					'description' => 'Número de comercio en el sistema de Payu',
					'default' => ''
				),
				'apiKey' => array(
					'title' => 'Api key',
					'type' => 'text',
					'description' => 'Esta llave se puede consultar a través del módulo  administrativo  del  sistema  dado  por  Payu',
					'default' => ''
				),
				'testmode' => array(
					'title' => __( 'Test mode', 'wc-gateway-payu' ),
					'type' => 'checkbox',
					'label' => __( 'Enable PayU test mode', 'wc-gateway-payu' ),
					'default' => 'yes',
					'description' => 'Módulo que permite realizar pruebas con tarjetas de crédito ficticias, en tiempo real.',
				),
				'debug' => array(
					'title' => __( 'Debug Log', 'woocommerce' ),
					'type' => 'checkbox',
					'label' => __( 'Enable logging', 'woocommerce' ),
					'default' => 'no',
					'description' => sprintf( __( 'Log PayU events, such as IPN requests, inside <code>woocommerce/logs/payu-%s.txt</code>', 'wc-gateway-payu' ), sanitize_file_name( wp_hash( 'payu' ) ) ),
				)
			);
		}

		/**
		 * Check if this gateway is enabled and available in the user's country
		 *
		 * @access public
		 * @return bool
		 */
		function is_valid_for_use() {
			if ( ! in_array( get_woocommerce_currency(), array('COP', 'MXN', 'USD', 'EUR', 'GBP', 'VEB') ) ) {
				return false;
			}

			return true;
		}

		/**
		 * Admin Panel Options
		 * - Options for bits like 'title' and availability on a country-by-country basis
		 *
		 * @since 1.0.0
		 */
		function admin_options() {

			?>
			<h3>PayU Latam</h3>
			<p><?php _e( 'PayU Latam works by sending the user to <a href="http://www.payulatam.com/">payulatam</a> to enter their payment information.', 'wc-gateway-payu' ); ?></p>

			<?php if ( $this->is_valid_for_use() ) : ?>

				<table class="form-table">
				<?php
					// Generate the HTML For the settings form.
					$this->generate_settings_html();
				?>
				</table>

			<?php else : ?>
				<div class="inline error"><p><strong><?php _e( 'Gateway Disabled', 'woocommerce' ); ?></strong>: <?php _e( 'PayU does not support your store currency.', 'woocommerce' ); ?></p></div>
			<?php
			endif;
		}

		/**
		 * Get args for passing to PayU
		 *
		 * @access public
		 * @param mixed $order
		 * @return array
		 */
		function get_payu_args( $order ) {
			global $woocommerce;

			$currency = get_woocommerce_currency();
			$signature = md5("$this->apiKey~$this->merchantId~$order->id~$order->order_total~$currency");

			//base only from items that have tax
			$tax_base = 0;

			$line_items = $order->get_items();//line items can have more than one of the same product
			foreach ($line_items as $item) {
				$item_tax = $order->get_line_tax($item);
				if ($item_tax > 0) {
					$tax_base += $order->get_line_total($item);
				}
			}
			//shipping with tax
			if ($order->get_shipping_tax() > 0) {
				$tax_base += $order->get_shipping();
			}

			if ( $this->debug == 'yes' ) {
				$this->log->add( 'payu', 'Generating payment form for order ' . $order->get_order_number() . '. Notify URL: ' . $this->notify_url );
			}

			// PayU Args
			$args = array(
				'merchantId'      => $this->merchantId,
				'referenceCode'   => $order->id,
				'description'     => 'orden no. '.$order->id.' - valor: '.$order->order_total,
				'amount'          => $order->order_total,
				'tax'             => $order->get_total_tax(),
				'taxReturnBase'   => (string) $tax_base,
				'signature'       => $signature,
				'currency'        => $currency,
				'buyerEmail'      => $order->billing_email,
				'buyerFullName'   => $order->billing_first_name.' '.$order->billing_last_name,
				'telephone'       => $order->billing_phone,
				'billingAddress'  => $order->billing_address_1.' '.$order->billing_address_2,
				'billingCity'     => $order->billing_city,
				'billingCountry'  => $order->billing_country,
				'shippingAddress' => $order->shipping_address_1.' '.$order->shipping_address_2,
				'shippingCity'    => $order->shipping_city,
				'shippingCountry' => $order->shipping_country,
				'zipCode'         => $order->billing_postcode,
				'responseUrl'     => $this->get_return_url( $order ),
				'confirmationUrl' => $this->notify_url
			);

			$args['test'] = ($this->testmode == 'yes') ? 1 : 0;
			if ($this->testmode == 'yes') {
				$args['accountId'] = '500537';
			}

			return $args;
		}


		/**
		 * Generate the payu button link
		 *
		 * @access public
		 * @param mixed $order_id
		 * @return string
		 */
		function generate_payu_form( $order_id ) {
			global $woocommerce;

			$order = new WC_Order( $order_id );

			$payu_adr = ($this->testmode == 'yes') ? $this->testurl : $this->liveurl;

			$payu_args = $this->get_payu_args( $order );

			$form_inputs = array();

			foreach ($payu_args as $key => $value) {
				$form_inputs[] = '<input type="hidden" name="'.esc_attr( $key ).'" value="'.esc_attr( $value ).'" />';
			}

			$woocommerce->add_inline_js( '
				jQuery("body").block({
					message: "' . esc_js( __( 'Thank you for your order. We are now redirecting you to PayU to make payment.', 'woocommerce' ) ) . '",
					baseZ: 99999,
					overlayCSS:
					{
						background: "#fff",
						opacity: 0.6
					},
					css: {
						padding:        "20px",
						zindex:         "9999999",
						textAlign:      "center",
						color:          "#555",
						border:         "3px solid #aaa",
						backgroundColor:"#fff",
						cursor:         "wait",
						lineHeight:		"24px",
					}
				});
				jQuery("#submit_payu_payment_form").click();
			' );

			return '<form action="'.esc_url( $payu_adr ).'" method="post" id="payu_payment_form" target="_top">
				' . implode( '', $form_inputs) . '
				<input type="submit" class="button alt" id="submit_payu_payment_form" value="' . __( 'Pay via PayU', 'woocommerce' ) . '" /> <a class="button cancel" href="'.esc_url( $order->get_cancel_order_url() ).'">'.__( 'Cancel order &amp; restore cart', 'woocommerce' ).'</a>
			</form>';
		}

		function pre_process_order($order) {
			global $woocommerce;

			$order->update_status('on-hold', 'Esperando respuesta PayU.');
			//$order->reduce_order_stock();

			$woocommerce->cart->empty_cart();
		}

		/**
		 * Process the payment and return the result
		 *
		 * @access public
		 * @param int $order_id
		 * @return array
		 */
		function process_payment( $order_id ) {

			$order = new WC_Order( $order_id );

			return array(
				'result'   => 'success',
				'redirect' => add_query_arg( 'order', $order->id, add_query_arg( 'key', $order->order_key, get_permalink( woocommerce_get_page_id('pay') ) ) )
			);
		}

		/**
		 * Output for the order received page.
		 *
		 * @access public
		 * @return void
		 */
		function receipt_page( $order_id ) {

			$order = new WC_Order( $order_id );

			$this->pre_process_order($order);

			echo '<p>'.__( 'Thank you for your order, please click the button below to pay with PayU.', 'woocommerce' ).'</p>';
			echo $this->generate_payu_form( $order_id );

		}

		/**
		 * Check for PayU IPN Response
		 *
		 * @access public
		 * @return void
		 */
		function check_pagos_response() {

			@ob_clean();

			if ( ! empty($_POST) ) {

				$merchant_id       = $_POST['merchant_id'];
				$state_pol         = $_POST['state_pol'];
				$response_code_pol = $_POST['response_code_pol'];
				$ref_sale          = $_POST['reference_sale'];
				$ref_pol           = $_POST['reference_pol'];
				$signature         = $_POST['sign'];
				$amount            = number_format($_POST['value'], 1, '.', '');
				$currency          = $_POST['currency'];

				$generated_signature = md5("$this->apiKey~$merchant_id~$ref_sale~$amount~$currency~$state_pol");

				if ( $this->merchantId != $merchant_id || $signature != $generated_signature ) {
					if ( $this->debug == 'yes' ) {
						$this->log->add( 'payu', 'Error: User Id or key are wrong.' );
						$this->log->add( 'payu', 'config_user: '.$this->merchantId.'. post_user: '.$merchant_id );
						$this->log->add( 'payu', 'sent value: '.$amount );
						$this->log->add( 'payu', 'sent_sign: '.$signature.'. gen_sign: '.$generated_signature );
					}
					exit;
				}

				$order = new WC_Order( $ref_sale );

				if ( ! isset( $order->id ) ) {
					if ( $this->debug == 'yes' )
						$this->log->add( 'payu', 'Error: Order Id does not match invoice.' );
						$this->log->add( 'payu', 'order_id sent: '.$ref_sale );
					exit;
				}

				if ( $this->debug == 'yes' ) {
					$this->log->add( 'payu', 'Found order #' . $order->id );
					$this->log->add( 'payu', 'Payment status: ' . $state_pol );
					$this->log->add( 'payu', 'Payment code: ' . $response_code_pol );
				}

				//número de transacción PayU
				$order->add_order_note('ref_pol: '.$ref_pol);

				$response_code_pol = $this->pol_codes[$response_code_pol];

				// We are here so lets check status and do actions
				switch ( $state_pol ) {
					case 4 :
						// Payment completed
						$order->add_order_note('response_code_pol: '.$response_code_pol);
						$order->payment_complete();

						if ( $this->debug == 'yes' )
							$this->log->add( 'payu', 'Payment complete.' );

						break;
					case 5 :
						$order->update_status('cancelled', 'response_code_pol: '.$response_code_pol);
						break;
					case 6 :
						$order->update_status('failed', 'response_code_pol: '.$response_code_pol);
						break;
					case 7 :
						$order->update_status('processing', 'response_code_pol: '.$response_code_pol);
						break;
					case 8 :
					case 9 :
						$order->update_status('refunded', 'Orden reversada. response_code_pol: '.$response_code_pol);
						break;
					default:
						$order->add_order_note('state_pol: '.$state_pol.' - response_code_pol: '.$response_code_pol);
				}
				exit;

			} else {

				wp_die( "PayU IPN Request Failure" );

			}

		}
	}

	$GLOBALS['api_manager_example'] = new WC_Gateway_Payu();

	/**
	 * Overrides woocommerce core templates. We use it for thank you page
	 */
	function payu_locate_template($template, $template_name, $template_path) {
		global $woocommerce;
		$default_template = $template;

		if ( ! $template_path ) $template_path = $woocommerce->template_url;
		$plugin_path  = untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/woocommerce/';

		// Look within passed path within the theme - this is priority
		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name
			)
		);

		// Get the template from this plugin, if it exists
		if ( ! $template && file_exists( $plugin_path . $template_name ) ) {
		    $template = $plugin_path . $template_name;
		}

		// Use default template
		if ( ! $template ) {
		    $template = $default_template;
		}

		// Return what we found
		return $template;
	}
	add_filter('woocommerce_locate_template', 'payu_locate_template', 10, 3);

	/**
	* Add the Gateway to WooCommerce
	**/
	function woocommerce_add_gateway_payu($methods) {
		$methods[] = 'WC_Gateway_Payu';
		return $methods;
	}

	add_filter('woocommerce_payment_gateways', 'woocommerce_add_gateway_payu' );
}
