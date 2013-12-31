<?php global $woocommerce_plugin_update_api_manager; ?>

<?php if ( count( $keys ) > 0 ) : ?>

	<h2><?php _e( 'API License Keys', $woocommerce_plugin_update_api_manager->text_domain ); ?></h2>

	<?php foreach ( $keys as $key ) :

		if ( $key['order_key'] == $order_key ) :
	?>

			<h3><?php echo $key['software_title']; ?></h3>

			<ul>
				<li><?php _e( 'API License Key:', $woocommerce_plugin_update_api_manager->text_domain ); ?> <strong><?php echo $key['order_key']; ?></strong></li>
				<li><?php _e( 'License Email:', $woocommerce_plugin_update_api_manager->text_domain ); ?> <strong><?php echo $key['license_email']; ?></strong></li>
			</ul>

		<?php endif; ?>

	<?php endforeach; ?>

<?php endif; ?>
