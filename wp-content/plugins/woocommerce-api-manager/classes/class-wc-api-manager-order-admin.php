<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * WooCommerce API Manager Order Admin Class
 *
 * @package Update API Manager/Order Admin
 * @author Todd Lahman LLC
 * @copyright   Copyright (c) 2011-2013, Todd Lahman LLC
 * @since 1.1.1
 *
 */

class WC_Update_API_Order_Admin {

	public function __construct() {

		//AJAX
		add_action( 'wp_ajax_woocommerce_delete_activation', array( $this, 'delete_activation' ) );

		//Hooks
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'woocommerce_process_shop_order_meta', array( $this, 'order_save_data' ) );

	}

	public function add_meta_boxes() {
		global $woocommerce_plugin_update_api_manager;

		add_meta_box( 'woocommerce-upgrade-api-order-keys', __( 'Update API Key', $woocommerce_plugin_update_api_manager->text_domain ), array( $this, 'licence_keys_meta_box' ), 'shop_order', 'normal', 'high' );
		add_meta_box( 'wc_upgrade_api-orderkey-activations', __( 'License Key API Activations', $woocommerce_plugin_update_api_manager->text_domain ), array( $this, 'activation_meta_box' ), 'shop_order', 'normal', 'high' );
	}

	/**
	 * Delete a activation via ajax
	 */
	public function delete_activation() {

		check_ajax_referer( 'am-delete-activation', 'security' );

		global $wpdb, $wc_api_manager_helpers;

		$domain 	= sanitize_text_field( $_POST['domain'] );
		$instance 	= sanitize_text_field( $_POST['instance'] );
		$order_key 	= sanitize_text_field( $_POST['order_key'] );
		$user_id 	= intval( $_POST['user_id'] );

		$current_info = $wc_api_manager_helpers->get_users_activation_data( $user_id, $order_key );

    	if ( ! empty( $current_info ) ) {

			$active_activations = 0;

	    	foreach ( $current_info as $key => $activations ) {

	    		if ( $activations['activation_active'] == 1 && $order_key == $activations['order_key'] ) {

					$active_activations++;

	    		}

	    	}

    		foreach ( $current_info as $key => $activation_info ) {

    			if ( $active_activations <= 1 && $activation_info['activation_active'] == 1 && $activation_info['instance'] == $instance && $activation_info['activation_domain'] == $domain ) {

					delete_user_meta( $user_id, $wpdb->get_blog_prefix() . WC_Api_Manager_Helpers::$user_meta_key_activations . $order_key );

					break;

					die();

				} else if ( $activation_info['activation_active'] == 1 && $activation_info['activation_active'] == 1 && $activation_info['instance'] == $instance && $activation_info['activation_domain'] == $domain ) {

					// Delete the activation data array
	    			unset( $current_info[$key] );

		    		// Re-index the numerical array keys:
					$new_info = array_values( $current_info );

					update_user_meta( $user_id, $wpdb->get_blog_prefix() . WC_Api_Manager_Helpers::$user_meta_key_activations . $order_key, $new_info );

					break;

					die();

				}

    		} // end foreach

		}

		die();

	}

	/**
	 * Order notes meta box
	 */
	public function licence_keys_meta_box() {
		global $woocommerce, $post, $wpdb, $wc_api_manager_helpers, $woocommerce_plugin_update_api_manager;

		$post_data = $wc_api_manager_helpers->get_postmeta_data( $post->ID );

		if ( isset( $post_data['_billing_email'][0] ) )
			$email = $post_data['_billing_email'][0];

		if ( isset( $post_data['_order_key'][0] ) )
			$order_key = $post_data['_order_key'][0];

		// Get the user order info
		$data = $wc_api_manager_helpers->get_order_info_by_email_with_order_key( $email, $order_key );

		if ( ! empty( $data ) ) {

			?>
			<div class="order_licence_keys wc-metaboxes-wrapper">

				<div class="wc-metaboxes">

					<?php

					// Get activation info
					$current_info = $wc_api_manager_helpers->get_users_activation_data( $data['user_id'], $data['order_key'] );

					$active_activations = 0;

			    	if ( ! empty( $current_info ) ) foreach ( $current_info as $key => $activations ) {

			    		if ( $activations['activation_active'] == 1 && $post_data['_order_key'][0] == $activations['order_key'] ) {

							$active_activations++;

			    		}

			    	}

			    	$num_activations = ( $active_activations  > 0 ) ? $active_activations : 0;

					// Activations limit or unlimited
					if ( $data['is_variable_product'] == 'no' && $data['_api_activations_parent'] != '' )
						$activations_limit = absint( $data['_api_activations_parent'] );
					else if ( $data['is_variable_product'] =='no' && $data['_api_activations_parent'] == '' )
						$activations_limit = 'unlimited';
					else if ( $data['is_variable_product'] == 'yes' && $data['_api_activations'] != '' )
						$activations_limit = absint( $data['_api_activations'] );
					else if ( $data['is_variable_product'] == 'yes' && $data['_api_activations'] == '' )
						$activations_limit = 'unlimited';

					// Software Title
					if ( $data['is_variable_product'] == 'no' )
						$software_title = sanitize_text_field( $data['_api_software_title_parent'] );
					else if ( $data['is_variable_product'] == 'yes' )
						$software_title = sanitize_text_field( $data['_api_software_title_var'] );
					else
						$software_title = sanitize_text_field( $data['software_title'] );

					?>
		    		<div class="wc-metabox closed">
						<h3 class="fixed">
							<div class="handlediv" title="<?php _e( 'Click to toggle', $woocommerce_plugin_update_api_manager->text_domain ); ?>"></div>
							<strong><?php printf( __( 'API Key: %s | Activation Limit: %s | Activations: %s | API Access Permission: %s |  Product Title: %s | Version: %s', $woocommerce_plugin_update_api_manager->text_domain ), $data['order_key'], $activations_limit, $num_activations, $data['_api_update_permission'], $software_title, $data['current_version'] ); ?></strong>
							<input type="hidden" name="user_id" value="<?php echo $data['user_id']; ?>" />
						</h3>
						<table cellpadding="0" cellspacing="0" class="wc-metabox-content">
							<tbody>
								<tr>
									<td>
										<label><?php _e( 'API License Key', $woocommerce_plugin_update_api_manager->text_domain ); ?>:</label>
										<input type="text" class="short" name="order_key" value="<?php echo $data['order_key']; ?>" readonly />
									</td>
									<td>
										<label><?php _e( 'Activation Limit', $woocommerce_plugin_update_api_manager->text_domain ); ?>:</label>
									<?php
									if ( $data['is_variable_product'] =='no' ) :
									?>
										<input type="text" class="short" name="_api_activations_parent" value="<?php echo $data['_api_activations_parent'] ?>" placeholder="<?php _e( 'Unlimited', $woocommerce_plugin_update_api_manager->text_domain ); ?>" />
									<?php
									elseif ( $data['is_variable_product'] == 'yes' ) :
									?>
										<input type="text" class="short" name="_api_activations" value="<?php echo $data['_api_activations'] ?>" placeholder="<?php _e( 'Unlimited', $woocommerce_plugin_update_api_manager->text_domain ); ?>" />
									<?php
									endif;
									?>
									</td>
									<td>
										<label><?php _e( 'API Access Permission', $woocommerce_plugin_update_api_manager->text_domain ); ?>:</label>
										<input type="checkbox" class="short" name="_api_update_permission" value="yes" <?php checked( $data['_api_update_permission'], 'yes' ); ?> placeholder="<?php _e( 'Unlimited', $woocommerce_plugin_update_api_manager->text_domain ); ?>" />
									</td>
								</tr>
								<tr>
									<td>
										<label><?php _e( 'Software Title', $woocommerce_plugin_update_api_manager->text_domain ); ?>:</label>
									<?php
									if ( $data['is_variable_product'] =='no' ) :
									?>
										<input type="text" class="short" name="_api_software_title_parent" value="<?php echo $data['_api_software_title_parent']; ?>" />
									<?php
									elseif ( $data['is_variable_product'] == 'yes' ) :
									?>
										<input type="text" class="short" name="_api_software_title_var" value="<?php echo $data['_api_software_title_var']; ?>" />
									<?php
									endif;
									?>
									</td>
									<td>
										<label><?php _e( 'API License Email', $woocommerce_plugin_update_api_manager->text_domain ); ?>:</label>
										<input type="text" class="short" name="license_email" value="<?php echo $data['license_email']; ?>" placeholder="<?php _e( 'Email Required', $woocommerce_plugin_update_api_manager->text_domain ); ?>" />
									</td>
									<td>
										<label><?php _e( 'Software Version', $woocommerce_plugin_update_api_manager->text_domain ); ?>:</label>
										<input type="text" class="short" name="current_version" value="<?php echo $data['current_version']; ?>" />
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		<?php

		} // end if $data

	}

	/**
	 * adds activations meta box
	 *
	 * @since 1.0
	 * @param object $post the current post object
	 * @return void
	 */
	public function activation_meta_box( $post ) {
		global $wpdb, $woocommerce, $wc_api_manager_helpers, $woocommerce_plugin_update_api_manager;

		$post_data = $wc_api_manager_helpers->get_postmeta_data( $post->ID );

		// Get the user order info
		$data = $wc_api_manager_helpers->get_order_info_by_email_with_order_key( $post_data['_billing_email'][0], $post_data['_order_key'][0] );

		if ( $data ) {

			// Get activation info
			$activations = $wc_api_manager_helpers->get_users_activation_data( $data['user_id'], $data['order_key'] );

			$num_activations = count( $activations );

			if ( $num_activations > 0 ) {

				?>
				<div class="woocommerce_order_items_wrapper">
					<table id="activations-table" class="woocommerce_order_items" cellspacing="0">
						<thead>
					    	<tr>
								<th><?php _e( 'API License Key', $woocommerce_plugin_update_api_manager->text_domain ) ?></th>
								<th><?php _e( 'Instance ID', $woocommerce_plugin_update_api_manager->text_domain ) ?></th>
								<th><?php _e( 'Software Title', $woocommerce_plugin_update_api_manager->text_domain ) ?></th>
								<th><?php _e( 'Status', $woocommerce_plugin_update_api_manager->text_domain ) ?></th>
								<th><?php _e( 'Date &amp; Time', $woocommerce_plugin_update_api_manager->text_domain ) ?></th>
								<th><?php _e( 'Domain Name', $woocommerce_plugin_update_api_manager->text_domain ) ?></th>
								<th><?php _e( 'Action', $woocommerce_plugin_update_api_manager->text_domain ) ?></th>
							</tr>
						</thead>
						<tbody>
					    	<?php $i = 1; foreach ( $activations as $activation ) : $i++ ?>
					    	<?php if ( $post_data['_order_key'][0] == $activation['order_key'] ) : ?>
								<tr<?php if ( $i % 2 == 1 ) echo ' class="alternate"' ?>>
									<td><?php echo sanitize_text_field( $activation['order_key'] ) ?></td>
									<td><?php echo ( $activation['instance'] ) ? sanitize_text_field( $activation['instance'] ) : _e('N/A', $woocommerce_plugin_update_api_manager->text_domain ); ?></td>
									<td><?php echo sanitize_text_field( $activation['product_id'] ); ?></td>
									<td class="activation_active"><?php echo ( $activation['activation_active'] ) ? __( 'Activated', $woocommerce_plugin_update_api_manager->text_domain ) : __( 'Deactivated', $woocommerce_plugin_update_api_manager->text_domain ) ?></td>
									<td><?php echo date( __( 'M j Y \a\t h:ia', $woocommerce_plugin_update_api_manager->text_domain ), strtotime( $activation['activation_time'] ) ) ?></td>
									<td><a href="<?php echo esc_url( $activation['activation_domain'] ) ?>" target="_blank"><?php echo esc_url( $activation['activation_domain'] ) ?></a></td>
									<td>
										<button type="button" rel="<?php echo esc_url( $activation['activation_domain'] ); ?>" id="<?php echo sanitize_text_field( $activation['instance'] ); ?>" class="delete_key button"><?php _e( 'Delete Activation', $woocommerce_plugin_update_api_manager->text_domain ); ?></button>
									</td>
					      		</tr>
					      	<?php else : ?>
					      	<tr><td style="padding:0 8px;"><?php _e( 'No activations yet', $woocommerce_plugin_update_api_manager->text_domain ) ?></td></tr>
					      	<?php break; endif; ?>
					    	<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<?php
				/**
				 * Javascript
				 */
				ob_start();
				?>
				jQuery(function(){

					jQuery('#activations-table').on('click', 'button.delete_key', function(e){
						e.preventDefault();
						var answer = confirm('<?php _e('Are you sure you want to delete this activation?', $woocommerce_plugin_update_api_manager->text_domain ); ?>');
						if (answer){

							var el 			= jQuery(this).parent().parent();

							var domain 		= jQuery(this).attr('rel');
							var instance 	= jQuery(this).attr('id');

							if ( domain ) {

								jQuery(el).block({ message: null, overlayCSS: { background: '#fff url(<?php echo $woocommerce->plugin_url(); ?>/assets/images/ajax-loader.gif) no-repeat center', opacity: 0.6 } });

								var data = {
									action: 	'woocommerce_delete_activation',
									domain: 	domain,
									instance: 	instance,
									user_id: 	'<?php echo $data['user_id']; ?>',
									order_key: 	'<?php echo $post_data['_order_key'][0]; ?>',
									security: 	'<?php echo wp_create_nonce("am-delete-activation"); ?>'
								};

								jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
									// Success
									jQuery(el).fadeOut('300', function(){
										jQuery(el).remove();
									});
								});

							} else {
								jQuery(el).fadeOut('300', function(){
									jQuery(el).remove();
								});
							}

						}
						return false;
					});

				});
				<?php
				$javascript = ob_get_clean();
				$woocommerce->add_inline_js( $javascript );

			} else {
				?><p style="padding:0 8px;"><?php _e( 'No activations yet', $woocommerce_plugin_update_api_manager->text_domain ) ?></p><?php
			}
		}
	}

	/**
	 * saves the data inputed into the order boxes
	 *
	 * @see order_meta_box()
	 * @since 1.0
	 * @return void
	 */
	public function order_save_data() {
		global $wpdb;

		$user_id = $_POST['user_id'];

		// Get order info that can be manipulated and compared
		$current_info 	= WC_Api_Manager_Helpers::get_users_data( $user_id );

		$order_key 		= esc_attr( stripslashes_deep( $_POST['order_key'] ) );

		if ( ! empty( $_POST['_api_update_permission'] ) )
			$update_permission = 'yes';
		else
			$update_permission = 'no';

		$info = WC_Api_Manager_Helpers::get_users_data( $user_id );

		// Get order info that can be used to populate values
		$data = $info[$order_key];

		if ( isset( $data ) ) {

			if ( $order_key ) {

				unset( $current_info[$order_key] );

				$update =
		    		array( $order_key =>
		    			array(
		                	'user_id'						=> absint( $data['user_id'] ),
							'order_id' 						=> absint( $data['order_id'] ),
							'order_key' 					=> sanitize_text_field( $order_key ),
							'license_email' 				=> sanitize_text_field( stripslashes_deep( $_POST['license_email'] ) ),
							'_api_software_title_parent' 	=> empty( $_POST['_api_software_title_parent'] ) ? '' : sanitize_text_field( stripslashes_deep( $_POST['_api_software_title_parent'] ) ),
							'_api_software_title_var' 		=> empty( $_POST['_api_software_title_var'] ) ? '' : sanitize_text_field( stripslashes_deep( $_POST['_api_software_title_var'] ) ),
							'software_title' 				=> empty( $_POST['software_title'] ) ? '' : sanitize_text_field( stripslashes_deep( $_POST['software_title'] ) ),
							'parent_product_id'				=> sanitize_text_field( $data['parent_product_id'] ),
							'variable_product_id'			=> sanitize_text_field( $data['variable_product_id'] ),
							'current_version'				=> sanitize_text_field( stripslashes_deep( $_POST['current_version'] ) ),
							'_api_activations'				=> empty( $_POST['_api_activations'] ) ? '' : absint( $_POST['_api_activations'] ),
							'_api_activations_parent'		=> empty( $_POST['_api_activations_parent'] ) ? '' : absint( $_POST['_api_activations_parent'] ),
							'_api_update_permission'		=> sanitize_text_field( $update_permission ),
							'is_variable_product'			=> sanitize_text_field( $data['is_variable_product'] ),
							'license_type'					=> '',
							'expires'						=> '',
							)
		    			);

				$new_info = array_merge_recursive( $update, $current_info );

				update_user_meta( $user_id, $wpdb->get_blog_prefix() . WC_Api_Manager_Helpers::$user_meta_key_orders, $new_info );

			}

		}

	}

} // End of class

new WC_Update_API_Order_Admin();
