<?php
/*************************************************************
 * 
 * core.class.php
 * 
 * Upgrade Plugins
 * 
 * 
 * Copyright (c) 2011 Prelovac Media
 * www.prelovac.com
 **************************************************************/
class MMB_Core extends MMB_Helper
{
    var $name;
    var $slug;
    var $settings;
    var $remote_client;
    var $comment_instance;
    var $plugin_instance;
    var $theme_instance;
    var $wp_instance;
    var $post_instance;
    var $stats_instance;
    var $search_instance;
    var $links_instance;
    var $user_instance;
    var $backup_instance;
    var $installer_instance;
    var $mmb_multisite;
    var $network_admin_install;
    private $action_call;
    private $action_params;
    private $mmb_pre_init_actions;
    private $mmb_pre_init_filters;
    private $mmb_init_actions;
    
    
    function __construct()
    {
        global $mmb_plugin_dir, $wpmu_version, $blog_id, $_mmb_plugin_actions, $_mmb_item_filter;
        
		$_mmb_plugin_actions = array();
        $this->name     = 'Manage Multiple Blogs';
        $this->slug     = 'manage-multiple-blogs';
		$this->action_call = null;
		$this->action_params = null;
		
		
        $this->settings = get_option($this->slug);
        if (!$this->settings) {
            $this->settings = array(
                'blogs' => array(),
                'current_blog' => array(
                    'type' => null
                )
            );
            $this->save_options();
        }
		if ( function_exists('is_multisite') ) {
            if ( is_multisite() ) {
                $this->mmb_multisite = $blog_id;
                $this->network_admin_install = get_option('mmb_network_admin_install');
            }
        } else if (!empty($wpmu_version)) {
            $this->mmb_multisite = $blog_id;
            $this->network_admin_install = get_option('mmb_network_admin_install');
        } else {
			$this->mmb_multisite = false;
			$this->network_admin_install = null;
		}
		
		// admin notices
		if ( !get_option('_worker_public_key') ){
			if( $this->mmb_multisite ){
				if( is_network_admin() && $this->network_admin_install == '1'){
					add_action('network_admin_notices', array( &$this, 'network_admin_notice' ));
				} else if( $this->network_admin_install != '1' ){
					$parent_key = $this->get_parent_blog_option('_worker_public_key');
					if(empty($parent_key))
						add_action('admin_notices', array( &$this, 'admin_notice' ));
				}
			} else {
				add_action('admin_notices', array( &$this, 'admin_notice' ));
			}
		}
		
		// default filters
		//$this->mmb_pre_init_filters['get_stats']['mmb_stats_filter'][] = array('MMB_Stats', 'pre_init_stats'); // called with class name, use global $mmb_core inside the function instead of $this
		$this->mmb_pre_init_filters['get_stats']['mmb_stats_filter'][] = 'mmb_pre_init_stats';
		
		$_mmb_item_filter['pre_init_stats'] = array( 'core_update', 'hit_counter', 'comments', 'backups', 'posts', 'drafts', 'scheduled' );
		$_mmb_item_filter['get'] = array( 'updates', 'errors' );
		
		$this->mmb_pre_init_actions = array(
			'backup_req' => 'mmb_get_backup_req',
		);
		
		$this->mmb_init_actions = array(
			'do_upgrade' => 'mmb_do_upgrade',
			'get_stats' => 'mmb_stats_get',
			'remove_site' => 'mmb_remove_site',
			'backup_clone' => 'mmb_backup_now',
			'restore' => 'mmb_restore_now',
			'optimize_tables' => 'mmb_optimize_tables',
			'check_wp_version' => 'mmb_wp_checkversion',
			'create_post' => 'mmb_post_create',
			'update_worker' => 'mmb_update_worker_plugin',
			'change_comment_status' => 'mmb_change_comment_status',
			'change_post_status' => 'mmb_change_post_status',
			'get_comment_stats' => 'mmb_comment_stats_get',
			'install_addon' => 'mmb_install_addon',
			'get_links' => 'mmb_get_links',
			'add_link' => 'mmb_add_link',
			'delete_link' => 'mmb_delete_link',
			'delete_links' => 'mmb_delete_links',
			'add_user' => 'mmb_add_user',
			'email_backup' => 'mmb_email_backup',
			'check_backup_compat' => 'mmb_check_backup_compat',
			'scheduled_backup' => 'mmb_scheduled_backup',
			'run_task' => 'mmb_run_task_now',
			'execute_php_code' => 'mmb_execute_php_code',
			'delete_backup' => 'mmm_delete_backup',
			'remote_backup_now' => 'mmb_remote_backup_now',
			'set_notifications' => 'mmb_set_notifications',
			'clean_orphan_backups' => 'mmb_clean_orphan_backups',
			'get_users' => 'mmb_get_users',
			'edit_users' => 'mmb_edit_users', 
			'get_posts' => 'mmb_get_posts',
			'delete_post' => 'mmb_delete_post',
			'delete_posts' => 'mmb_delete_posts',
			'edit_posts' => 'mmb_edit_posts',
			'get_pages' => 'mmb_get_pages',
			'delete_page' => 'mmb_delete_page',
			'get_plugins_themes' => 'mmb_get_plugins_themes',
			'edit_plugins_themes' => 'mmb_edit_plugins_themes',
			'worker_brand' => 'mmb_worker_brand',
			'set_alerts' => 'mmb_set_alerts',
			'maintenance' => 'mmb_maintenance_mode'
		);
		
		add_action('rightnow_end', array( &$this, 'add_right_now_info' ));       
		add_action('admin_init', array(&$this,'admin_actions'));   
		add_action('init', array( &$this, 'mmb_remote_action'), 9999);
		add_action('setup_theme', 'mmb_parse_request');
		add_action('set_auth_cookie', array( &$this, 'mmb_set_auth_cookie'));
		add_action('set_logged_in_cookie', array( &$this, 'mmb_set_logged_in_cookie'));
		
    }
    
	function mmb_remote_action(){
		if($this->action_call != null){
			$params = isset($this->action_params) && $this->action_params != null ? $this->action_params : array();
			call_user_func($this->action_call, $params);
		}
	}
	
	function register_action_params( $action = false, $params = array() ){
		
		if(isset($this->mmb_pre_init_actions[$action]) && function_exists($this->mmb_pre_init_actions[$action])){
			call_user_func($this->mmb_pre_init_actions[$action], $params);
		}
		
		if(isset($this->mmb_init_actions[$action]) && function_exists($this->mmb_init_actions[$action])){
			$this->action_call = $this->mmb_init_actions[$action];
			$this->action_params = $params;
			
			if( isset($this->mmb_pre_init_filters[$action]) && !empty($this->mmb_pre_init_filters[$action])){
				global $mmb_filters;
				
				foreach($this->mmb_pre_init_filters[$action] as $_name => $_functions){
					if(!empty($_functions)){
						$data = array();
						
						foreach($_functions as $_k => $_callback){
							if(is_array($_callback) && method_exists($_callback[0], $_callback[1]) ){
								$data = call_user_func( $_callback, $params );
							} elseif (is_string($_callback) && function_exists( $_callback )){
								$data = call_user_func( $_callback, $params );
							}
							$mmb_filters[$_name] = isset($mmb_filters[$_name]) && !empty($mmb_filters[$_name]) ? array_merge($mmb_filters[$_name], $data) : $data;
							add_filter( $_name, create_function( '$a' , 'global $mmb_filters; return array_merge($a, $mmb_filters["'.$_name.'"]);') );
						}
					}
					
				}
			}
			return true;
		} 
		return false;
	}
	
    /**
     * Add notice to network admin dashboard for security reasons    
     * 
     */
    function network_admin_notice()
    {
        echo '<div class="error" style="text-align: center;"><p style="color: red; font-size: 14px; font-weight: bold;">Attention !</p><p>
	  	Please add this site and your network blogs, with your network adminstrator username, to your <a target="_blank" href="http://managewp.com/wp-admin">ManageWP.com</a> account now to remove this notice or "Network Deactivate" the Worker plugin to avoid <a target="_blank" href="http://managewp.com/user-guide/security">security issues</a>.	  	
	  	</p></div>';
    }
	
		
	/**
     * Add notice to admin dashboard for security reasons    
     * 
     */
    function admin_notice()
    {
        echo '<div class="error" style="text-align: center;"><p style="color: red; font-size: 14px; font-weight: bold;">Attention !</p><p>
	  	Please add this site now to your <a target="_blank" href="http://managewp.com/wp-admin">ManageWP.com</a> account.  Or deactivate the Worker plugin to avoid <a target="_blank" href="http://managewp.com/user-guide/security">security issues</a>.	  	
	  	</p></div>';
    }
    
    /**
     * Add an item into the Right Now Dashboard widget 
     * to inform that the blog can be managed remotely
     * 
     */
    function add_right_now_info()
    {
        echo '<div class="mmb-slave-info">
            <p>This site can be managed remotely.</p>
        </div>';
    }
    
    /**
     * Get parent blog options
     * 
     */
    private function get_parent_blog_option( $option_name = '' )
    {
		global $wpdb;
		$option = $wpdb->get_var( $wpdb->prepare( "SELECT `option_value` FROM {$wpdb->base_prefix}options WHERE option_name = '{$option_name}' LIMIT 1" ) );
        return $option;
    }
    
    /**
     * Gets an instance of the Comment class
     * 
     */
    function get_comment_instance()
    {
        if (!isset($this->comment_instance)) {
            $this->comment_instance = new MMB_Comment();
        }
        
        return $this->comment_instance;
    }
    
    /**
     * Gets an instance of the Plugin class
     * 
     */
    function get_plugin_instance()
    {
        if (!isset($this->plugin_instance)) {
            $this->plugin_instance = new MMB_Plugin();
        }
        
        return $this->plugin_instance;
    }
    
    /**
     * Gets an instance of the Theme class
     * 
     */
    function get_theme_instance()
    {
        if (!isset($this->theme_instance)) {
            $this->theme_instance = new MMB_Theme();
        }
        
        return $this->theme_instance;
    }
    
    
    /**
     * Gets an instance of MMB_Post class
     * 
     */
    function get_post_instance()
    {
        if (!isset($this->post_instance)) {
            $this->post_instance = new MMB_Post();
        }
        
        return $this->post_instance;
    }
    
    /**
     * Gets an instance of Blogroll class
     * 
     */
    function get_blogroll_instance()
    {
        if (!isset($this->blogroll_instance)) {
            $this->blogroll_instance = new MMB_Blogroll();
        }
        
        return $this->blogroll_instance;
    }
    
    
    
    /**
     * Gets an instance of the WP class
     * 
     */
    function get_wp_instance()
    {
        if (!isset($this->wp_instance)) {
            $this->wp_instance = new MMB_WP();
        }
        
        return $this->wp_instance;
    }
    
    /**
     * Gets an instance of User
     * 
     */
    function get_user_instance()
    {
        if (!isset($this->user_instance)) {
            $this->user_instance = new MMB_User();
        }
        
        return $this->user_instance;
    }
    
    /**
     * Gets an instance of stats class
     * 
     */
    function get_stats_instance()
    {
        if (!isset($this->stats_instance)) {
            $this->stats_instance = new MMB_Stats();
        }
        return $this->stats_instance;
    }
    /**
     * Gets an instance of search class
     * 
     */
    function get_search_instance()
    {
        if (!isset($this->search_instance)) {
            $this->search_instance = new MMB_Search();
        }
        //return $this->search_instance;
        return $this->search_instance;
    }
    /**
     * Gets an instance of stats class
     *
     */
    function get_backup_instance()
    {
        if (!isset($this->backup_instance)) {
            $this->backup_instance = new MMB_Backup();
        }
        
        return $this->backup_instance;
    }
    
    /**
     * Gets an instance of links class
     *
     */
    function get_link_instance()
    {
        if (!isset($this->link_instance)) {
            $this->link_instance = new MMB_Link();
        }
        
        return $this->link_instance;
    }
    
    function get_installer_instance()
    {
        if (!isset($this->installer_instance)) {
            $this->installer_instance = new MMB_Installer();
        }
        return $this->installer_instance;
    }
    
    /**
     * Plugin install callback function
     * Check PHP version
     */
    function install() {
		
        global $wpdb, $_wp_using_ext_object_cache, $current_user;
        $_wp_using_ext_object_cache = false;
		
        //delete plugin options, just in case
        if ($this->mmb_multisite != false) {
			$network_blogs = $wpdb->get_results($wpdb->prepare("select `blog_id`, `site_id` from `{$wpdb->blogs}`"));
			if(!empty($network_blogs)){
				if( is_network_admin() ){
					update_option('mmb_network_admin_install', 1);
					foreach($network_blogs as $details){
						if($details->site_id == $details->blog_id)
							update_blog_option($details->blog_id, 'mmb_network_admin_install', 1);
						else 
							update_blog_option($details->blog_id, 'mmb_network_admin_install', -1);
							
						delete_blog_option($blog_id, '_worker_nossl_key');
						delete_blog_option($blog_id, '_worker_public_key');
						delete_blog_option($blog_id, '_action_message_id');
					}
				} else {
					update_option('mmb_network_admin_install', -1);
					delete_option('_worker_nossl_key');
					delete_option('_worker_public_key');
					delete_option('_action_message_id');
				}
			}
        } else {
            delete_option('_worker_nossl_key');
            delete_option('_worker_public_key');
            delete_option('_action_message_id');
        }
        
        delete_option('mwp_backup_tasks');
        delete_option('mwp_notifications');
        delete_option('mwp_worker_brand');
        delete_option('mwp_pageview_alerts');
        
    }
    
    /**
     * Saves the (modified) options into the database
     * 
     */
    function save_options()
    {
        if (get_option($this->slug)) {
            update_option($this->slug, $this->settings);
        } else {
            add_option($this->slug, $this->settings);
        }
    }
    
    /**
     * Deletes options for communication with master
     * 
     */
    function uninstall( $deactivate = false )
    {
        global $current_user, $wpdb, $_wp_using_ext_object_cache;
		$_wp_using_ext_object_cache = false;
        
        if ($this->mmb_multisite != false) {
			$network_blogs = $wpdb->get_col($wpdb->prepare("select `blog_id` from `{$wpdb->blogs}`"));
			if(!empty($network_blogs)){
				if( is_network_admin() ){
					if( $deactivate ) {
						delete_option('mmb_network_admin_install');
						foreach($network_blogs as $blog_id){
							delete_blog_option($blog_id, 'mmb_network_admin_install');
							delete_blog_option($blog_id, '_worker_nossl_key');
							delete_blog_option($blog_id, '_worker_public_key');
							delete_blog_option($blog_id, '_action_message_id');
							delete_blog_option($blog_id, 'mwp_maintenace_mode');
						}
					}
				} else {
					if( $deactivate )
						delete_option('mmb_network_admin_install');
						
					delete_option('_worker_nossl_key');
					delete_option('_worker_public_key');
					delete_option('_action_message_id');
				}
			}
        } else {
			delete_option('_worker_nossl_key');
            delete_option('_worker_public_key');
            delete_option('_action_message_id');
        }
        
        //Delete options
		delete_option('mwp_maintenace_mode');
        delete_option('mwp_backup_tasks');
        wp_clear_scheduled_hook('mwp_backup_tasks');
        delete_option('mwp_notifications');
        wp_clear_scheduled_hook('mwp_notifications');        
        delete_option('mwp_worker_brand');
        delete_option('mwp_pageview_alerts');
    }
    
    
    /**
     * Constructs a url (for ajax purpose)
     * 
     * @param mixed $base_page
     */
    function construct_url($params = array(), $base_page = 'index.php')
    {
        $url = "$base_page?_wpnonce=" . wp_create_nonce($this->slug);
        foreach ($params as $key => $value) {
            $url .= "&$key=$value";
        }
        
        return $url;
    }
    
    /**
     * Worker update
     * 
     */
    function update_worker_plugin($params)
    {
        extract($params);
        if ($download_url) {
            @include_once ABSPATH . 'wp-admin/includes/file.php';
            @include_once ABSPATH . 'wp-admin/includes/misc.php';
            @include_once ABSPATH . 'wp-admin/includes/template.php';
            @include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
            @include_once ABSPATH . 'wp-admin/includes/screen.php';
            
            if (!$this->is_server_writable()) {
                return array(
                    'error' => 'Failed, please <a target="_blank" href="http://managewp.com/user-guide#ftp">add FTP details for automatic upgrades.</a></a>'
                );
            }
            
            ob_start();
            @unlink(dirname(__FILE__));
            $upgrader = new Plugin_Upgrader();
            $result   = $upgrader->run(array(
                'package' => $download_url,
                'destination' => WP_PLUGIN_DIR,
                'clear_destination' => true,
                'clear_working' => true,
                'hook_extra' => array(
                    'plugin' => 'worker/init.php'
                )
            ));
            ob_end_clean();
            if (is_wp_error($result) || !$result) {
                return array(
                    'error' => 'ManageWP Worker plugin could not be updated.'
                );
            } else {
                return array(
                    'success' => 'ManageWP Worker plugin successfully updated.'
                );
            }
        }
        return array(
            'error' => 'Bad download path for worker installation file.'
        );
    }
    
    /**
     * Automatically logs in when called from Master
     * 
     */
    function automatic_login()
    {
		$where      = isset($_GET['mwp_goto']) ? $_GET['mwp_goto'] : false;
        $username   = isset($_GET['username']) ? $_GET['username'] : '';
        $auto_login = isset($_GET['auto_login']) ? $_GET['auto_login'] : 0;
        
		if( !function_exists('is_user_logged_in') )
			include_once( ABSPATH.'wp-includes/pluggable.php' );
		
		if (( $auto_login && strlen(trim($username)) && !is_user_logged_in() ) || (isset($this->mmb_multisite) && $this->mmb_multisite )) {
			$signature  = base64_decode($_GET['signature']);
            $message_id = trim($_GET['message_id']);
            
            $auth = $this->authenticate_message($where . $message_id, $signature, $message_id);
			if ($auth === true) {
				
				if (!headers_sent())
					header('P3P: CP="CAO PSA OUR"');
				
				if(!defined('MMB_USER_LOGIN'))
					define('MMB_USER_LOGIN', true);
				
				$siteurl = function_exists('get_site_option') ? get_site_option( 'siteurl' ) : get_option('siteurl');
				$user = $this->mmb_get_user_info($username);
				wp_set_current_user($user->ID);
				
				if(!defined('COOKIEHASH') || (isset($this->mmb_multisite) && $this->mmb_multisite) )
					wp_cookie_constants();
				
				wp_set_auth_cookie($user->ID);
				@mmb_worker_header();
				
				if((isset($this->mmb_multisite) && $this->mmb_multisite ) || isset($_REQUEST['mwpredirect'])){
					if(function_exists('wp_safe_redirect') && function_exists('admin_url')){
						wp_safe_redirect(admin_url($where));
						exit();
					}
				}
			} else {
                wp_die($auth['error']);
            }
        } elseif( is_user_logged_in() ) {
			@mmb_worker_header();
			if(isset($_REQUEST['mwpredirect'])){
				if(function_exists('wp_safe_redirect') && function_exists('admin_url')){
					wp_safe_redirect(admin_url($where));
					exit();
				}
			}
		}
    }
    
	function mmb_set_auth_cookie( $auth_cookie ){
		if(!defined('MMB_USER_LOGIN'))
			return false;
		
		if( !defined('COOKIEHASH') )
			wp_cookie_constants();
			
		$_COOKIE['wordpress_'.COOKIEHASH] = $auth_cookie;
		
	}
	function mmb_set_logged_in_cookie( $logged_in_cookie ){
		if(!defined('MMB_USER_LOGIN'))
			return false;
	
		if( !defined('COOKIEHASH') )
			wp_cookie_constants();
			
		$_COOKIE['wordpress_logged_in_'.COOKIEHASH] = $logged_in_cookie;
	}
		
    function admin_actions(){
    	add_filter('all_plugins', array($this, 'worker_replace'));
    }
    
    function worker_replace($all_plugins){
    	$replace = get_option("mwp_worker_brand");
    	if(is_array($replace)){
    		if($replace['name'] || $replace['desc'] || $replace['author'] || $replace['author_url']){
    			$all_plugins['worker/init.php']['Name'] = $replace['name'];
    			$all_plugins['worker/init.php']['Title'] = $replace['name'];
    			$all_plugins['worker/init.php']['Description'] = $replace['desc'];
    			$all_plugins['worker/init.php']['AuthorURI'] = $replace['author_url'];
    			$all_plugins['worker/init.php']['Author'] = $replace['author'];
    			$all_plugins['worker/init.php']['AuthorName'] = $replace['author'];
    			$all_plugins['worker/init.php']['PluginURI'] = '';
    		}
    		
    		if($replace['hide']){
    			if (!function_exists('get_plugins')) {
            include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        	}
          $activated_plugins = get_option('active_plugins');
          if (!$activated_plugins)
                $activated_plugins = array();
          if(in_array('worker/init.php',$activated_plugins))
           	unset($all_plugins['worker/init.php']);   	
    		}
    	}
    	
    	  	
    	return $all_plugins;
    }
    
}
?>