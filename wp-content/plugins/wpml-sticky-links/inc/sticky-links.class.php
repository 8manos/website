<?php
  
  
class WPML_Sticky_Links{
    var $settings;
    var $broken_links;
    var $absolute_links_object;
    
    
    function __construct($ext = false){          
        $this->settings = get_option('alp_settings');
        add_action('init', array($this,'init'));           
    }
    
    function __destruct(){
        return;
    }
    
    function init(){

        $this->plugin_localization();
        
        // Check if WPML is active. If not display warning message and not load Sticky links
        if(!defined('ICL_SITEPRESS_VERSION') || ICL_PLUGIN_INACTIVE){
            if ( !function_exists('is_multisite') || !is_multisite() ) {
                add_action('admin_notices', array($this, '_no_wpml_warning'));
            }
            return false;            
        }elseif(version_compare(ICL_SITEPRESS_VERSION, '2.0.5', '<')){
            add_action('admin_notices', array($this, '_old_wpml_warning'));
            return false;            
        }
        
        require_once ICL_PLUGIN_PATH . '/inc/absolute-links/absolute-links.class.php';        
        $this->absolute_links_object = new AbsoluteLinks;
        
        global $sitepress_settings;
        
        if(isset($_POST['save_alp']) && $_POST['save_alp']){            
            // $this->settings = $_POST;
            // $this->save_settings();
            // TBD!
        }
        
        $this->ajax_responses();
        
        add_action('save_post', array($this,'save_default_urls'), 10, 2);
        add_action('admin_head',array($this,'js_scripts'));  

        add_filter('the_content', array($this,'show_permalinks'));
        
        if($this->settings['sticky_links_widgets'] || $this->settings['sticky_links_strings']){                               
            add_filter('widget_text', array($this,'show_permalinks'), 99); // low priority - allow translation to be set        
        }                
        if($this->settings['sticky_links_widgets']){            
            add_filter('pre_update_option_widget_text', array($this,'pre_update_option_widget_text'), 5, 2);
        }

        if(empty($this->settings) && !empty($sitepress_settings['modules']['absolute-links'])){
            $this->settings = $sitepress_settings['modules']['absolute-links'];
            $this->save_settings();
        }
        
        add_action('admin_menu', array($this, 'menu'));
        add_filter('plugin_action_links', array($this, 'plugin_action_links'), 10, 2); 
        
        if(is_admin()){
            wp_enqueue_script('wpml-sticky-links-js', WPML_STICKY_LINKS_URL . '/res/js/scripts.js', array(), WPML_STICKY_LINKS_VERSION);    
        }        
        
        add_action('wp_ajax_wpml_sticky_links_save_options', array($this, '_save_options'));
        
        // add message to WPML dashboard widget
        add_action('icl_dashboard_widget_content', array($this, 'icl_dashboard_widget_content'));
        
    }
    
    function _no_wpml_warning(){
        ?>
        <div class="message error"><p><?php printf(__('WPML Sticky Links is enabled but not effective. It requires <a href="%s">WPML</a> in order to work.', 'wpml-sticky-links'), 
            'http://wpml.org/'); ?></p></div>
        <?php
    }

    function _old_wpml_warning(){
        ?>
        <div class="message error"><p><?php printf(__('WPML Sticky Links is enabled but not effective. It is not compatible with  <a href="%s">WPML</a> versions prior 2.0.5.', 'wpml-sticky-links'), 
            'http://wpml.org/'); ?></p></div>
        <?php
    }
    
    /* MAKE IT PHP 4 COMPATIBLE */
    function WPML_Sticky_Links(){
         //destructor
         register_shutdown_function(array(&$this, '__destruct'));

         //constructor
         $argcv = func_get_args();
         call_user_func_array(array(&$this, '__construct'), $argcv);
    }    
    
    function _save_options(){
        if(wp_verify_nonce($_POST['_wpnonce'], 'icl_sticky_save')){
            $this->settings['sticky_links_widgets'] = intval($_POST['icl_sticky_links_widgets']);
            $this->settings['sticky_links_strings'] = intval($_POST['icl_sticky_links_strings']);
            $this->save_settings();        
        }
    }
    
    function save_settings(){
        update_option('alp_settings', $this->settings);
    }
    
    function ajax_responses(){  
        if(!isset($_POST['alp_ajx_action'])){
            return;
        }
        global $wpdb, $wp_post_types;
        $post_types = array_diff(array_keys($wp_post_types), array('revision','attachment','nav_menu_item'));
        
        $limit  = 5;
        
        switch($_POST['alp_ajx_action']){
            case 'rescan':
                $posts_pages = $wpdb->get_col("
                    SELECT SQL_CALC_FOUND_ROWS p1.ID FROM {$wpdb->posts} p1 WHERE post_type IN ('".join("','", $post_types)."') AND ID NOT IN 
                    (SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_alp_processed')
                    ORDER BY p1.ID ASC LIMIT $limit
                ");
                if($posts_pages){
                    $found = $wpdb->get_var("SELECT FOUND_ROWS()");                
                    foreach($posts_pages as $ppid){
                        $this->absolute_links_object->process_post($ppid);
                    }
                    echo $found >= $limit ? $found - $limit : 0;
                }else{
                    echo -1;
                }                
                break;
            case 'rescan_reset':
                $wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE meta_key='_alp_processed'");
                echo mysql_affected_rows();
                break;
            case 'use_suggestion':
                $broken_links = get_post_meta($_POST['post_id'],'_alp_broken_links', true);
                foreach($broken_links as $k=>$bl){
                    if($k==$_POST['orig_url']){
                        $broken = $k;
                        $repl = $bl['suggestions'][$_POST['sug_id']]['absolute'];
                        unset($broken_links[$k]);
                        $c = count($broken_links);
                        if($c){
                            update_post_meta($_POST['post_id'],'_alp_broken_links', $broken_links);
                        }else{
                            delete_post_meta($_POST['post_id'],'_alp_broken_links');
                        }
                        echo $c.'|'.$bl['suggestions'][$_POST['sug_id']]['perma'];
                        break;
                    }
                }
                if(!empty($broken)){
                    $post_content = $wpdb->get_var("SELECT post_content FROM {$wpdb->posts} WHERE ID={$_POST['post_id']}");
                    $post_content = preg_replace('@href="('.$broken.')"@i', 'href="'.$repl.'"', $post_content);
                    $wpdb->update($wpdb->posts, array('post_content'=>$post_content), array('ID'=>$_POST['post_id']));
                }
                break;
            case 'alp_revert_urls':
                $posts_pages = $wpdb->get_results("
                    SELECT SQL_CALC_FOUND_ROWS p1.ID, p1.post_content FROM {$wpdb->posts} p1
                    JOIN {$wpdb->postmeta} p2 ON p1.ID = p2.post_id
                    WHERE p2.meta_key = '_alp_processed'
                    ORDER BY p1.ID ASC LIMIT $limit
                ");   
                if($posts_pages){
                    $found = $wpdb->get_var("SELECT FOUND_ROWS()");                
                    foreach($posts_pages as $p){
                        $cont = $this->show_permalinks($p->post_content);
                        $wpdb->update($wpdb->posts, array('post_content'=>$cont), array('ID'=>$p->ID));                        
                        delete_post_meta($p->ID,'_alp_processed');
                        delete_post_meta($p->ID,'_alp_broken_links');
                    }
                    echo $found >= $limit ? $found - $limit : 0;
                }else{
                    echo -1;
                }                                    
                break;
        }
        exit;
    }    
    
    function js_scripts(){
        ?>
        <script type="text/javascript">
            addLoadEvent(function(){                     
                jQuery('#alp_re_scan_but').click(alp_toogle_scan);                
                jQuery('#alp_re_scan_but_all').click(alp_reset_scan_flags);
                jQuery('.alp_use_sug').click(alp_use_suggestion);
                jQuery('#alp_revert_urls').click(alp_do_revert_urls);
                
            });
            var alp_scan_started = false;
            var req_timer = 0;
            function alp_toogle_scan(){                       
                if(!alp_scan_started){  
                    alp_send_request(0); 
                    jQuery('#alp_ajx_ldr_1').fadeIn();
                    jQuery('#alp_re_scan_but').attr('value','<?php echo icl_js_escape(__('Running', 'wpml-sticky-links')) ?>');    
                }else{
                    jQuery('#alp_re_scan_but').attr('value','<?php echo icl_js_escape(__('Scan', 'wpml-sticky-links')); ?>');    
                    window.clearTimeout(req_timer);
                    jQuery('#alp_ajx_ldr_1').fadeOut();
                    location.reload();
                }
                alp_scan_started = !alp_scan_started;
                return false;
            }
            
            function alp_send_request(offset){
                jQuery.ajax({
                    type: "POST",
                    url: "<?php echo htmlentities($_SERVER['REQUEST_URI']) ?>",
                    data: "alp_ajx_action=rescan&amp;offset="+offset,
                    success: function(msg){                        
                        if(-1==msg || msg==0){
                            left = '0';
                            alp_toogle_scan();
                        }else{
                            left=msg;
                        }
                        
                        if(left=='0'){
                            jQuery('#alp_re_scan_but').attr('disabled','disabled');    
                        }
                        
                        jQuery('#alp_re_scan_toscan').html(left);
                        if(alp_scan_started){
                            req_timer = window.setTimeout(alp_send_request,3000,offset);
                        }
                    }                                                            
                });
            }
            
            function alp_reset_scan_flags(){
                if(alp_scan_started) return;
                alp_scan_started = false;
                jQuery('#alp_re_scan_but').removeAttr('disabled');    
                jQuery.ajax({
                    type: "POST",
                    url: "<?php echo htmlentities($_SERVER['REQUEST_URI']) ?>",
                    data: "alp_ajx_action=rescan_reset",
                    success: function(msg){    
                        if(msg){
                            alp_toogle_scan()
                        }
                    }                                                            
                });
            }
            function alp_use_suggestion(){
                jqthis = jQuery(this);
                jqthis.parent().parent().css('background-color','#eee');                
                spl = jqthis.attr('id').split('_');
                sug_id = spl[3];
                post_id = spl[4];
                orig_url = jQuery('#alp_bl_'+post_id+'_'+spl[5]).html().replace(/&amp;/,'&').replace(/&/, '%26');
                jQuery.ajax({
                    type: "POST",
                    url: "<?php echo htmlentities($_SERVER['REQUEST_URI']) ?>",
                    data: "alp_ajx_action=use_suggestion&sug_id="+sug_id+"&post_id="+post_id+"&orig_url="+orig_url,
                    success: function(msg){                                                    
                        spl = msg.split('|');
                        jqthis.parent().html('<?php echo icl_js_escape(__('fixed', 'wpml-sticky-links')); ?> - ' + spl[1]);
                    },
                    error: function (msg){
                        alert('Something went wrong');
                        jqthis.parent().parent().css('background-color','#fff');
                    }                                                            
                });
                                
            }
            
            var req_rev_timer = '';
            function alp_do_revert_urls(){
                jQuery('#alp_revert_urls').attr('disabled','disabled');
                jQuery('#alp_revert_urls').attr('value','<?php echo icl_js_escape(__('Running', 'wpml-sticky-links')); ?>');
                jQuery.ajax({
                    type: "POST",
                    url: "<?php echo htmlentities($_SERVER['REQUEST_URI']) ?>",
                    data: "alp_ajx_action=alp_revert_urls",
                    success: function(msg){                                                    
                        if(-1==msg || msg==0){
                            jQuery('#alp_ajx_ldr_2').fadeOut();
                            jQuery('#alp_rev_items_left').html('');
                            window.clearTimeout(req_rev_timer);
                            jQuery('#alp_revert_urls').removeAttr('disabled');                            
                            jQuery('#alp_revert_urls').attr('value','<?php echo icl_js_escape(__('Start', 'wpml-sticky-links')); ?>');                            
                            location.reload();
                        }else{
                            jQuery('#alp_rev_items_left').html(msg + ' <?php echo icl_js_escape(__('items left', 'wpml-sticky-links')); ?>');
                            req_rev_timer = window.setTimeout(alp_do_revert_urls,3000);
                            jQuery('#alp_ajx_ldr_2').fadeIn();
                        }                            
                    },
                    error: function (msg){
                        //alert('Something went wrong');
                    }                                                            
                });
            }
            
        </script>
        <?php
    }
    
    function menu(){
        $top_page = apply_filters('icl_menu_main_page', basename(ICL_PLUGIN_PATH).'/menu/languages.php');
        add_submenu_page($top_page, 
            __('Sticky Links','wpml-sticky-links'), __('Sticky Links','wpml-sticky-links'),
            'manage_options', 'wpml-sticky-links', array($this,'menu_content'));
    }
    
    function menu_content(){
        global $wpdb;
        
        $this->get_broken_links();
        $total_posts_pages = $wpdb->get_var("
            SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type IN ('page','post') AND ID NOT IN 
            (SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_alp_processed')
        ");
        
        $total_posts_pages_processed = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE meta_key = '_alp_processed'");   
        
        include WPML_STICKY_LINKS_PATH . '/menu/management.php';
        
    }
            
    function pre_update_option_widget_text($new_value, $old_value){
        global $wpdb;
        if(is_array($new_value)){ 
            foreach($new_value as $k=>$w){
                if(isset($w['text'])){
                    $new_value[$k]['text'] = $this->absolute_links_object->_process_generic_text($w['text']);
                }
            }
            if($new_value !== $old_value){
                $wpdb->update($wpdb->options, array('option_value'=>$new_value), array('option_name'=>'widget_text'));
            }            
        }
        return $new_value;
    }

    function save_default_urls($post_id, $post){    
        if($post->post_status == 'auto-draft' || isset($_POST['autosave'])) 
            return;
        if(!in_array( $post->post_type, get_post_types( array('show_ui' => true ) ) )) 
            return;
        if(!post_type_supports($post->post_type, 'editor'))
            return;
        if(in_array($post->post_type, array('revision', 'attachment', 'nav_menu_item')))
            return;
        
        $this->absolute_links_object->process_post($post_id);
               
    }    
    
    function show_permalinks($cont){
        if(!isset($GLOBALS['__disable_absolute_links_permalink_filter']) || !$GLOBALS['__disable_absolute_links_permalink_filter']){
            $home = rtrim(get_option('home'),'/');        
            $parts = parse_url($home);        
            $abshome = $parts['scheme'] .'://' . $parts['host'];
            $path = @ltrim($parts['path'],'/');    
            $tx_qvs = join('|',$this->absolute_links_object->taxonomies_query_vars);
            $reg_ex = '@<a([^>]+)?href="(('.$abshome.')?/'.$path.'/?\?(p|page_id|cat_ID|'.$tx_qvs.')=([0-9a-z-]+))(#?[^"]*)"([^>]+)?>@i';
            $cont = preg_replace_callback(
                $reg_ex,
                array($this,'show_permalinks_cb'),$cont);                    
        }
        return $cont;
    }
       
    function show_permalinks_cb($matches){
        if($matches[4]=='cat_ID'){
            $url = get_category_link($matches[5]);
        }elseif($tax = array_search($matches[4],$this->absolute_links_object->taxonomies_query_vars)){
            $url = get_term_link($matches[5], $tax);
        }else{
            $url = get_permalink($matches[5]);
        }  
        $fragment = $matches[6];
        if ($fragment != '') {
            $fragment = str_replace('&#038;', '&', $fragment);
            $fragment = str_replace('&amp;', '&', $fragment);
            if ($fragment[0] == '&') {
                if (strpos($url, '?') === FALSE) {
                    $fragment[0] = '?';
                }
            }
        }
        
        $trail = '';
        if (isset($matches[7])) {
            $trail = $matches[7];
        }
        return '<a'.$matches[1]. 'href="'. $url . $fragment . '"' . $trail . '>';
    }
    
    function get_broken_links(){
        global $wpdb;
        $this->broken_links = $wpdb->get_results("SELECT p2.ID, p2.post_title, p1.meta_value AS links
            FROM {$wpdb->postmeta} p1 JOIN {$wpdb->posts} p2 ON p1.post_id=p2.ID WHERE p1.meta_key='_alp_broken_links'");
    }
    
    function icl_dashboard_widget_content(){
        ?>
        <div><a href="javascript:void(0)" onclick="jQuery(this).parent().next('.wrapper').slideToggle();" style="display:block; padding:5px; border: 1px solid #eee; margin-bottom:2px; background-color: #F7F7F7;"><?php _e('Sticky links', 'wpml-sticky-links') ?></a></div>

        <div class="wrapper" style="display:none; padding: 5px 10px; border: 1px solid #eee; border-top: 0px; margin:-11px 0 2px 0;"><p><?php 
            echo __('With Sticky Links, WPML can automatically ensure that all links on posts and pages are up-to-date, should their URL change.',
                 'wpml-sticky-links'); ?></p>        

        <p><a class="button secondary" href="<?php echo 'admin.php?page=wpml-sticky-links';?>"><?php 
            echo __('Configure Sticky Links', 'wpml-sticky-links') ?></a></p>    
        
        </div>
                                     
        <?php        
    }
    
    function plugin_action_links($links, $file){
        $this_plugin = basename(WPML_STICKY_LINKS_PATH) . '/plugin.php';
        if($file == $this_plugin) {
            $links[] = '<a href="admin.php?page=wpml-sticky-links">' . 
                __('Configure', 'wpml-sticky-links') . '</a>';
        }
        return $links;
    }
    
    // Localization
    function plugin_localization(){
        load_plugin_textdomain( 'wpml-sticky-links', false, WPML_STICKY_LINKS_FOLDER . '/locale');
    }
}  
