<?php global $woocommerce_plugin_update_api_manager; ?>

<?php if ( count( $keys ) > 0 ) : ?>

	<h2><?php _e( 'Licence Keys', $woocommerce_plugin_update_api_manager->text_domain ); ?></h2>

	<?php foreach ( $keys as $key ) : ?>

		<h3><?php echo $key->software_product_id; ?> <?php if ( $key->software_version ) printf( __('Version %s', $woocommerce_plugin_update_api_manager->text_domain ), $key->software_version ); ?></h3>

		<ul>
			<li><?php _e( 'Licence Email:', $woocommerce_plugin_update_api_manager->text_domain ); ?> <strong><?php echo $key->activation_email; ?></strong></li>
			<li><?php _e( 'Licence Key:', $woocommerce_plugin_update_api_manager->text_domain ); ?> <strong><?php echo $key->licence_key; ?></strong></li>
		</ul>

	<?php endforeach; ?>

<?php endif; ?>
