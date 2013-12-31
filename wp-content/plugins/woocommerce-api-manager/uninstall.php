<?php

// Make sure that we are uninstalling
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

// Removes all data from the database
delete_option( 'wc_api_manager' );
delete_option('wc_api_manager_product_id');
// delete_option( 'wc_api_manager_instance' );
delete_option( 'wc_api_manager_deactivate_checkbox' );
delete_option( 'wc_api_manager_activated' );
delete_option( 'wc_plugin_api_manager_version' );
delete_option( '_api_manager_array' );
