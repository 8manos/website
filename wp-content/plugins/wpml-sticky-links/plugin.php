<?php 
/*
Plugin Name: WPML Sticky Links
Plugin URI: http://wpml.org/
Description: Prevents internal links from ever breaking. <a href="http://wpml.org">Documentation</a>.
Author: ICanLocalize
Author URI: http://wpml.org
Version: 1.1.7
*/

if(defined('WPML_STICKY_LINKS_VERSION')) return;

define('WPML_STICKY_LINKS_VERSION', '1.1.7');
define('WPML_STICKY_LINKS_PATH', dirname(__FILE__));

require WPML_STICKY_LINKS_PATH . '/inc/constants.inc';
require WPML_STICKY_LINKS_PATH . '/inc/sticky-links.class.php';

$WPML_Sticky_Links = new WPML_Sticky_Links();
?>
