<?php
/**
 * My API Manager Account Page
 */
 if ( ! empty( $user_id ) ) :
	global $wc_api_manager_helpers, $woocommerce_plugin_update_api_manager;

	$user_orders = WC_Api_Manager_Helpers::get_users_data( $user_id );

	if ( ! empty( $user_orders ) ) :
	?>
	<h2><?php _e( 'My API License Keys', $woocommerce_plugin_update_api_manager->text_domain ); ?></h2>
		<table class="shop_table my_account_api_manager my_account_orders">

			<thead>
				<tr>
					<th class="api-manager-software-title" style="text-align:center; vertical-align: middle;"><span class="nobr"><?php _e( 'Product', $woocommerce_plugin_update_api_manager->text_domain ); ?></span></th>
					<th class="api-manager-key" style="text-align:center; vertical-align: middle;"><span class="nobr"><?php _e( 'API License Key', $woocommerce_plugin_update_api_manager->text_domain ); ?></span></th>
					<th class="api-manager-email" style="text-align:center; vertical-align: middle;"><span class="nobr"><?php _e( 'License Email', $woocommerce_plugin_update_api_manager->text_domain ); ?></span></th>
					<th class="api-manager-activation" style="text-align:center; vertical-align: middle;"><span class="nobr"><?php _e( 'Activations', $woocommerce_plugin_update_api_manager->text_domain ); ?></span></th>
				<?php if ( WC_Api_Manager_Helpers::is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ) : ?>
					<th class="api-manager-subscription" style="text-align:center; vertical-align: middle;"><span class="nobr"><?php _e( 'Subscription', $woocommerce_plugin_update_api_manager->text_domain ); ?></span></th>
				<?php endif; ?>
				</tr>
			</thead>

			<tbody>

			<?php foreach ( $user_orders as $order_key => $data ) :

					/**
					 * Prepare the Subscription information
					 */

					// Finds the post ID (integer) for a product even if it is a variable product
					if ( $data['is_variable_product'] == 'no' )
						$post_id 	= $data['parent_product_id'];
					else
						$post_id 	= $data['variable_product_id'];

					// Finds order ID that matches the license key. Order ID is the post_id in the post meta table
					$order_id 	= $data['order_id'];

					// Finds the product ID, which can only be the parent ID for a product
					$product_id = $data['parent_product_id'];

					if ( isset( $user_id ) && isset( $post_id ) && isset( $order_id ) && isset( $product_id ) && isset( $order_key ) ) {

						if ( WC_Api_Manager_Helpers::is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) && $wc_api_manager_helpers->get_product_checkbox_status( $post_id, '_api_is_subscription' ) === true ) {

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

					// End Subscription information prep

					// Software Title
					if ( $data['is_variable_product'] == 'no' )
						$software_title = $data['_api_software_title_parent'];
					else if ( $data['is_variable_product'] == 'yes' )
						$software_title = $data['_api_software_title_var'];
					else
						$software_title = $data['software_title'];
			?>

				<tr class="order">
					<td class="api-manager-product" style="text-align:center; white-space:nowrap; vertical-align: middle;">
						<?php echo $software_title; ?>
					</td>
					<td class="api-manager-license-key" style="text-align:center; white-space:nowrap; vertical-align: middle;">
						<?php
						if ( ! empty( $status ) && $status != 'active' && WC_Api_Manager_Helpers::is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ) echo '<del>';
						echo $data['order_key'];
						if ( ! empty( $status ) && $status != 'active' && WC_Api_Manager_Helpers::is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ) echo '</del>';
						?>
					</td>
					<td class="api-manager-license-email" style="text-align:center; white-space:nowrap; vertical-align: middle;">
						<?php echo $data['license_email']; ?>
					</td>
					<td class="api-manager-activations" style="text-align:center; white-space:nowrap; vertical-align: middle;">
						<?php
						// Get activation info
						$a_info = $wc_api_manager_helpers->get_users_activation_data( $user_id, $data['order_key'] );

						$active_activations = 0;

						if ( ! empty( $a_info ) ) :

					    	foreach ( $a_info as $key => $activations ) :

					    		if ( $activations['activation_active'] == 1 && $activations['order_key'] == $data['order_key'] ) :

									$active_activations++;

								endif; // end if activations

							endforeach; // end a_info

						endif; // not empty a_info

						$order_data = $wc_api_manager_helpers->get_order_info_by_email_with_order_key( $data['license_email'], $data['order_key'] );

						if ( ! empty( $order_data ) ) :

							// Activations limit or unlimited
							if ( $order_data['is_variable_product'] == 'no' && $order_data['_api_activations_parent'] != '' ) :
								$activations_limit = absint( $order_data['_api_activations_parent'] );
							elseif ( $order_data['is_variable_product'] =='no' && $order_data['_api_activations_parent'] == '' ) :
								$activations_limit = 'unlimited';
							elseif ( $order_data['is_variable_product'] == 'yes' && $order_data['_api_activations'] != '' ) :
								$activations_limit = absint( $order_data['_api_activations'] );
							elseif ( $order_data['is_variable_product'] == 'yes' && $order_data['_api_activations'] == '' ) :
								$activations_limit = 'unlimited';
							endif;

							$num_activations = ( $active_activations  > 0 ) ? $active_activations : 0;

							$my_acccount_activations = $num_activations . __( ' out of ', $woocommerce_plugin_update_api_manager->text_domain ) . $activations_limit;

							echo $my_acccount_activations;

							endif; // not empty order_data

						?>
					</td>
				<?php if ( WC_Api_Manager_Helpers::is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ) : ?>
					<td class="api-manager-subscriptions" style="text-align:center; white-space:nowrap; vertical-align: middle;">
						<?php
							if ( ! empty( $status ) ) :

								echo $status;

							else :

								echo 'No';

							endif;
						?>

					</td>
				<?php endif; ?>
				</tr>

				<?php $current_info = $wc_api_manager_helpers->get_users_activation_data( $user_id, $data['order_key'] ); ?>
				<?php
					foreach ( $current_info as $key => $activation_info ) :
						if ( $activation_info['order_key'] == $data['order_key'] ) :
				?>
				<tr class="api-manager-domains">
					<td <?php echo ( WC_Api_Manager_Helpers::is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ? 'colspan="5"' : 'colspan="4"' ); ?> class="api-manager-activations" style="text-align:left; white-space:nowrap; vertical-align: middle;">
						<a href="<?php echo esc_url( $wc_api_manager_helpers->nonce_url( array( 'domain' => $activation_info['activation_domain'], 'instance' => $activation_info['instance'], 'order_key' => $activation_info['order_key'], 'user_id' => $user_id ) ) ); ?>" class="button <?php echo sanitize_html_class( 'delete' ) ?>"><?php echo esc_html( __( 'Delete', $woocommerce_plugin_update_api_manager->text_domain ) ); ?></a>
						<a href="<?php echo $activation_info['activation_domain']; ?>" target="_blank"><?php echo $activation_info['activation_domain']; ?></a>
						<?php if ( ! empty( $status ) && $status != 'active' && WC_Api_Manager_Helpers::is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ) : ?>
							<span style="background-color:#faebeb; border:1px solid #dc7070; color:#212121; padding:2px">
							<?php _e( 'Domain activated with invalid API License Key.', $woocommerce_plugin_update_api_manager->text_domain ); ?>
							</span>
						<?php endif; ?>
					</td>
				</tr>

			<?php endif; // end if order_key ?>

			<?php endforeach; // end current_info ?>

			<?php endforeach; // end user_orders ?>

			<?php endif; // end if user_orders ?>

			</tbody>

		</table>

	<?php else : ?>

	<p><?php _e( 'You have no API keys.', $woocommerce_plugin_update_api_manager->text_domain ); ?></p>

<?php endif; // end if user_id
