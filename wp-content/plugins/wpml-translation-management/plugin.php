<?php 
/*
Plugin Name: WPML Translation Management
Plugin URI: http://wpml.org/
Description: Add a complete translation process for WPML. <a href="http://wpml.org">Documentation</a>.
Author: ICanLocalize
Author URI: http://wpml.org
Version: 1.2.8
*/

if(defined('WPML_TM_VERSION')) return;

define('WPML_TM_VERSION', '1.2.8');
define('WPML_TM_PATH', dirname(__FILE__));

require WPML_TM_PATH . '/inc/constants.inc';
require WPML_TM_PATH . '/inc/wpml-translation-management.class.php';

if ( function_exists('is_multisite') && is_multisite() ) {
    @include_once( ABSPATH . WPINC . '/vars.php' );
}

$WPML_Translation_Management = new WPML_Translation_Management;