<?php
define('WPML_MEDIA_FOLDER', basename(WPML_MEDIA_PATH));


if(defined('WP_ADMIN') && defined('FORCE_SSL_ADMIN') && FORCE_SSL_ADMIN){
    define('WPML_MEDIA_URL', rtrim(str_replace('http://','https://', WP_PLUGIN_URL), '/') . '/' . WPML_MEDIA_FOLDER );
}else{
    define('WPML_MEDIA_URL', WP_PLUGIN_URL . '/' . WPML_MEDIA_FOLDER );
}