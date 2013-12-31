<?php

/**
 * Tool Tips Class
 *
 * @package Update API Manager/Tips
 * @author Todd Lahman LLC
 * @copyright   Copyright (c) 2011-2013, Todd Lahman LLC
 * @since 1.0
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WC_API_Manager_Tool_Tips {

	function tips( $tip ) {
		global $woocommerce_plugin_update_api_manager;

		switch ( $tip ) {
			case 'deactivation':
				?>
				<span class='icon-pos'><a href='' class='tool-tip' title='<?php _e( 'A license key activates a single installation of the WooCommerce Update API Manager on a single blog. To move a license to another blog, the plugin license must be deactivated on the old blog installation, and reactivated with the license key and license email on the new blog. For multisite installations, each blog requires its own license key to activate the WooCommerce Update API Manager.', $woocommerce_plugin_update_api_manager->text_domain ); ?>'><img src='<?php echo $woocommerce_plugin_update_api_manager->plugin_url(); ?>assets/images/icon-question.png' title=''' /></a></span>
				<?php
				break;
		}
	}

}

// Class is instantiated as an object by other classes on-demand
