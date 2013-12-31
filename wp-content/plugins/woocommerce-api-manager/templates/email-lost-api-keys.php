<?php global $woocommerce_plugin_update_api_manager; ?>

<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php do_action( 'woocommerce_email_header', $email_heading ); ?>

<?php if ( count( $keys ) > 0 ) : ?>

	<?php foreach ( $keys as $key ) : ?>

		<h3><?php echo $key['software_title']; ?></h3>

		<ul>
			<li><?php _e( 'API License Key:', $woocommerce_plugin_update_api_manager->text_domain ); ?> <strong><?php echo $key['order_key']; ?></strong></li>
			<li><?php _e( 'Licence Email:', $woocommerce_plugin_update_api_manager->text_domain ); ?> <strong><?php echo $key['license_email']; ?></strong></li>
		</ul>

	<?php endforeach; ?>

<?php endif; ?>

<?php do_action( 'woocommerce_email_footer' ); ?>
