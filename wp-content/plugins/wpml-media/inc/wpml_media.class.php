<?php


class WPML_media{


    function __construct($ext = false){
        add_action('init', array($this,'init'));
    }

    function __destruct(){
        return;
    }

    function init(){
		
        $this->plugin_localization();
		
        // Check if WPML is active. If not display warning message and don't load WPML-media
        if(!defined('ICL_SITEPRESS_VERSION')){
            add_action('admin_notices', array($this, '_no_wpml_warning'));
            return false;            
        }elseif(version_compare(ICL_SITEPRESS_VERSION, '2.0.5', '<')){
            add_action('admin_notices', array($this, '_old_wpml_warning'));
            return false;            
        }        

		$this->languages = null;
		
        if(is_admin()){        
	
	        add_action('admin_head',array($this,'js_scripts'));  
	
			global $sitepress, $pagenow;
			
			if (1 < count($sitepress->get_active_languages())) {
				$this->settings = get_option('wpml_media_settings', array('initial_message_shown' => false));

				if (!isset($_GET['page']) || $_GET['page'] != 'wpml-media') {
					if (!$this->settings['initial_message_shown']) {
						add_action('admin_notices', array($this, '_initialize_message'));
					}
				}
				
				add_action('admin_menu', array($this,'menu'));
				add_filter('manage_media_columns', array($this, 'manage_media_columns'), 10 , 1);
				add_action('manage_media_custom_column', array($this, 'manage_media_custom_column'), 10, 2);
				//add_filter('manage_upload_sortable_columns', array($this, 'manage_upload_sortable_columns'));
                add_action('parse_query', array($this, 'parse_query'));
	            add_filter('posts_where', array($this,'posts_where_filter'));
				add_filter('views_upload', array($this, 'views_upload'));
				add_action('icl_post_languages_options_after', array($this, 'language_options'));
				
				// Post/page save actions
				add_action('save_post', array($this,'save_post_actions'), 10, 2);
                
                add_action('add_attachment', array($this,'save_attachment_actions'));
                add_action('edit_attachment', array($this,'save_attachment_actions'));
                

				
                if($pagenow == 'media-upload.php'){
                    add_action('media_upload_library', array($this,'language_filter'), 99);
				}
				
				if($pagenow == 'media.php') {
	                add_action('admin_footer', array($this,'media_language_options'));            
				}
				
			}
			
	        $this->ajax_responses();
			
		}
		
		//add_filter('get_post_metadata', array($this, 'get_post_metadata'), 10, 4);
        add_filter('WPML_filter_link', array($this, 'filter_link'), 10, 2);
		add_filter('icl_ls_languages', array($this, 'icl_ls_languages'), 10, 1);
		add_action('icl_pro_translation_saved', array($this, 'icl_pro_translation_saved'), 10, 1);

    }
	
	function media_language_options() {
		global $sitepress;
		
		$att_id = $_GET['attachment_id'];
		$translations = $this->_get_translations($att_id);
		$current_lang = '';
		foreach($translations as $lang => $id) {
			if ($id == $att_id) {
				$current_lang = $lang;
				unset($translations[$lang]);
				break;
			}
		}
		
		$active_languages = icl_get_languages('orderby=id&order=asc&skip_missing=0');
		$lang_links = '';
		
		if ($current_lang) {
			
			$lang_links = '<strong>' . $active_languages[$current_lang]['native_name'] . '</strong>';
			
		}
		
		foreach ($translations as $lang => $id) {
			$lang_links .= ' | <a href="' . admin_url('media.php?attachment_id=' . $id . '&action=edit') .  '">' . $active_languages[$lang]['native_name'] . '</a>';
		}
		


		echo '<div id="icl_lang_options" style="display:none">' . $lang_links . '</div>';	
	}
	
	function icl_pro_translation_saved($new_post_id) {
		global $wpdb;
		
        $post_type = $wpdb->get_var("SELECT post_type FROM {$wpdb->posts} WHERE ID = " . $new_post_id);
		$trid = $_POST['trid'];
		$lang = $_POST['lang'];
		
		$source_lang = $wpdb->get_var("SELECT language_code FROM {$wpdb->prefix}icl_translations WHERE trid={$trid} AND source_language_code IS NULL");
		
		$this->duplicate_post_attachments($new_post_id, $trid, $source_lang, $lang);
	}

    function save_post_actions($pidd, $post){
        global $wpdb, $sitepress;
		
        list($post_type, $post_status) = $wpdb->get_row("SELECT post_type, post_status FROM {$wpdb->posts} WHERE ID = " . $pidd, ARRAY_N);            
        // exceptions
        if(
               !$sitepress->is_translated_post_type($post_type)
            || isset($_POST['autosave'])
            || (isset($_POST['post_ID']) && $_POST['post_ID']!=$pidd) || (isset($_POST['post_type']) && $_POST['post_type']=='revision')
            || $post_type == 'revision'
            || get_post_meta($pidd, '_wp_trash_meta_status', true)
            || ( isset($_GET['action']) && $_GET['action']=='restore')
            || $post_status == 'auto-draft'
        ){
            return;
        }
		
		if (isset($_POST['icl_trid'])) {
			// save the post from the edit screen.
			if (isset($_POST['icl_duplicate_attachments'])) {
				update_post_meta($pidd, '_wpml_media_duplicate', intval($_POST['icl_duplicate_attachments']));
			} else {
				update_post_meta($pidd, '_wpml_media_duplicate', "0");
			}
			
			if (isset($_POST['icl_duplicate_featured_image'])) {
				update_post_meta($pidd, '_wpml_media_featured', intval($_POST['icl_duplicate_featured_image']));
			} else {
				update_post_meta($pidd, '_wpml_media_featured', "0");
			}

			$icl_trid = $_POST['icl_trid'];
		} else {
			// get trid from database.
			$icl_trid = $wpdb->get_var("SELECT trid FROM {$wpdb->prefix}icl_translations WHERE element_id={$pidd} AND element_type = 'post_$post_type'");
		}
		
		if ($icl_trid) {
			$this->duplicate_post_attachments($pidd, $icl_trid);
		}
	}
	
    function save_attachment_actions($post_id){
        global $wpdb, $sitepress;     
        
        $wpml_media_lang = get_post_meta($post_id, 'wpml_media_lang', true);
        
        if(empty($wpml_media_lang)){
            $parent_post = $wpdb->get_row($wpdb->prepare(
                "SELECT p2.ID, p2.post_type FROM $wpdb->posts p1 JOIN $wpdb->posts p2 ON p1.post_parent =p2.ID WHERE p1.ID=%d"
                , $post_id));
                
            if($parent_post){
                $wpml_media_lang = $sitepress->get_language_for_element($parent_post->ID, 'post_' . $parent_post->post_type);
            }

            if(empty($wpml_media_lang)){
                $wpml_media_lang = $sitepress->get_admin_language_cookie();
            }
            if(empty($wpml_media_lang)){
                $wpml_media_lang = $sitepress->get_default_language();
            }
            
        }
        
        if(!empty($wpml_media_lang)){
            update_post_meta($post_id, 'wpml_media_lang', $wpml_media_lang);
        }
                
    }
    	
	function duplicate_post_attachments($pidd, $icl_trid, $source_lang = null, $lang = null) {
        global $wpdb, $sitepress;
		if ($icl_trid == "") {
			return;
		}
		
		if (!$source_lang) {
			$source_lang = $wpdb->get_var("SELECT source_language_code FROM {$wpdb->prefix}icl_translations WHERE element_id = $pidd AND trid = $icl_trid");
		}
		
		if ($source_lang == null || $source_lang == "") {
			// This is the original see if we should copy to translations
			
			$duplicate = get_post_meta($pidd, '_wpml_media_duplicate', true);
			$featured = get_post_meta($pidd, '_wpml_media_featured', true);
			if ($duplicate || $featured) {
				$translations = $wpdb->get_col("SELECT element_id FROM {$wpdb->prefix}icl_translations WHERE trid = $icl_trid");
				
				foreach ($translations as $translation_id) {
					if ($translation_id && $translation_id != $pidd) {
						$duplicate_t = $duplicate;
						if ($duplicate_t) {
							// See if the translation is marked for duplication
							$duplicate_t = get_post_meta($translation_id, '_wpml_media_duplicate', true);
						}
						
						$lang = $wpdb->get_var("SELECT language_code FROM {$wpdb->prefix}icl_translations WHERE element_id = $translation_id AND trid = $icl_trid");
						if ($duplicate_t || $duplicate_t == '') {
							$source_attachments = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_parent = $pidd AND post_type = 'attachment'");
							$attachments = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_parent = $translation_id AND post_type = 'attachment'");
	
							foreach ($source_attachments as $source_att_id) {
								$found = false;
								foreach($attachments as $att_id) {
									$duplicate_of = get_post_meta($att_id, 'wpml_media_duplicate_of', true);
									if ($duplicate_of == $source_att_id) {
										$found = true;
									}
								}
								
								if (!$found) {
									$this->create_duplicate_attachment($source_att_id, $translation_id, $lang);
								}
							}
						}
						
						$featured_t = $featured;
						if ($featured_t) {
							// See if the translation is marked for duplication
							$featured_t = get_post_meta($translation_id, '_wpml_media_featured', true);
						}
						if ($featured_t || $featured_t == '') {
							$thumbnail_id = get_post_meta($pidd, '_thumbnail_id', true);
							if ($thumbnail_id) {
								update_post_meta($translation_id, '_thumbnail_id', $thumbnail_id);
							}
						}
					}
				}
			}
			
		} else {
			// This is a translation.
			
			$source_id = $wpdb->get_var("SELECT element_id FROM {$wpdb->prefix}icl_translations WHERE language_code = '$source_lang' AND trid = $icl_trid");
			
			if (!$lang) {
				$lang = $wpdb->get_var("SELECT language_code FROM {$wpdb->prefix}icl_translations WHERE element_id = $pidd AND trid = $icl_trid");
			}

			$duplicate = get_post_meta($pidd, '_wpml_media_duplicate', true);
			if ($duplicate === "") {
				// check the original state
				$duplicate = get_post_meta($source_id, '_wpml_media_duplicate', true);
			}
			
			if ($duplicate) {
				$attachments = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_parent = $pidd AND post_type = 'attachment'");
				$source_attachments = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_parent = $source_id AND post_type = 'attachment'");
				
				foreach ($source_attachments as $source_att_id) {
					$found = false;
					foreach($attachments as $att_id) {
						$duplicate_of = get_post_meta($att_id, 'wpml_media_duplicate_of', true);
						if ($duplicate_of == $source_att_id) {
							$found = true;
						}
					}
					
					if (!$found) {
						$this->create_duplicate_attachment($source_att_id, $pidd, $lang);
					}
					
				}
			}
			
			$featured = get_post_meta($pidd, '_wpml_media_featured', true);
			if ($featured === "") {
				// check the original state
				$featured = get_post_meta($source_id, '_wpml_media_featured', true);
			}
			
			if ($featured) {
				$thumbnail_id = get_post_meta($source_id, '_thumbnail_id', true);
				if ($thumbnail_id) {
					update_post_meta($pidd, '_thumbnail_id', $thumbnail_id);
				}
				
			}
			
		}

	}
	
	function language_options() {
		global $icl_meta_box_globals, $wpdb;
		
		$translation = false;
		$source_id = null;
		$translated_id = null;
		if (sizeof($icl_meta_box_globals['translations']) > 0) {
			if (!isset($icl_meta_box_globals['translations'][$icl_meta_box_globals['selected_language']])) {
				// We are creating a new translation
				$translation = true;
				// find the original
				foreach ($icl_meta_box_globals['translations'] as $trans_data) {
					if ($trans_data->original == '1') {
						$source_id = $trans_data->element_id;
						break;
					}
				}
			} else {
				$trans_data = $icl_meta_box_globals['translations'][$icl_meta_box_globals['selected_language']];
				// see if this is an original or a translation.
				if ($trans_data->original == '0') {
					// double check that it's not the original
					// This is because the source_language_code field in icl_translations is not always being set to null.
					
					$source_language_code = $wpdb->get_var("SELECT source_language_code FROM {$wpdb->prefix}icl_translations WHERE translation_id = $trans_data->translation_id");
					$translation = !($source_language_code == "" || $source_language_code == null);
					if ($translation) {
						$source_id = $icl_meta_box_globals['translations'][$source_language_code]->element_id;
						$translated_id = $trans_data->element_id;
					} else {
						$source_id = $trans_data->element_id;
					}
				} else {
					$source_id = $trans_data->element_id;
				}
			}
		}
		
		echo '<br /><br /><strong>' . __('Media attachments', 'wpml-media') . '</strong>';
		
		$checked = '';
		if ($translation) {
			if ($translated_id) {
				$duplicate = get_post_meta($translated_id, '_wpml_media_duplicate', true);
				if ($duplicate === "") {
					// check the original state
					$duplicate = get_post_meta($source_id, '_wpml_media_duplicate', true);
				}
				$featured = get_post_meta($translated_id, '_wpml_media_featured', true);
				if ($featured === "") {
					// check the original state
					$featured = get_post_meta($source_id, '_wpml_media_featured', true);
				}
				
			} else {
				// This is a new translation.
				$duplicate = get_post_meta($source_id, '_wpml_media_duplicate', true);
				$featured = get_post_meta($source_id, '_wpml_media_featured', true);
			}
			
			if ($duplicate) {
				$checked = ' checked="checked"';
			}
            echo '<br /><label><input name="icl_duplicate_attachments" type="checkbox" value="1" '.$checked . '/>' . __('Duplicate uploaded media from original', 'wpml-media') . '</label>'; 
			
			if ($featured) {
				$checked = ' checked="checked"';
			} else {
				$checked = '';
			}
            echo '<br /><label><input name="icl_duplicate_featured_image" type="checkbox" value="1" '.$checked . '/>' . __('Duplicate featured image from original', 'wpml-media') . '</label>'; 
		} else {

			$duplicate = get_post_meta($source_id, '_wpml_media_duplicate', true);
			if ($duplicate) {
				$checked = ' checked="checked"';
			}
            echo '<br /><label><input name="icl_duplicate_attachments" type="checkbox" value="1" '.$checked . '/>' . __('Duplicate uploaded media to translations', 'wpml-media') . '</label>'; 

			$featured = get_post_meta($source_id, '_wpml_media_featured', true);
			if ($featured) {
				$checked = ' checked="checked"';
			} else {
				$checked = '';
			}
            echo '<br /><label><input name="icl_duplicate_featured_image" type="checkbox" value="1" '.$checked . '/>' . __('Duplicate featured image to translations', 'wpml-media') . '</label>'; 
		}
	}
	
	function manage_media_columns($posts_columns) {
		$posts_columns['language'] = __('Language', 'wpml-media');
		return $posts_columns;
	}
	
	function manage_media_custom_column($column_name, $id) {
		if ($column_name == 'language') {
			global $wpdb, $sitepress; 
            if(!empty($this->languages[$id])){           
			    echo $sitepress->get_display_language_name($this->languages[$id], $sitepress->get_admin_language());
            }else{
                echo __('None', 'wpml-media');
            }
		}
	}
	
	//function manage_upload_sortable_columns($sortable_columns) {
	//	$sortable_columns['language'] = 'language';
	//	return $sortable_columns;
	//}
	
    function parse_query($q){
        global $wp_query, $wpdb, $pagenow, $sitepress;
        if($pagenow == 'upload.php' || $pagenow == 'media-upload.php') {
			
			$this->_get_lang_info();			
			
		}
	}
	
	function _get_lang_info() {
		global $wpdb, $sitepress;
		
		// get the attachment languages.
		$results = $wpdb->get_results("SELECT ID, post_parent FROM {$wpdb->posts} WHERE post_type='attachment'");
		$this->parents = array();
		$this->unattached = array();
		foreach ($results as $result) {
			$this->parents[$result->ID] = $result->post_parent;
			if (!$result->post_parent) {
				$this->unattached[] = $result->ID;
			}
		}
		
		$results = $wpdb->get_results("SELECT post_id, meta_value FROM {$wpdb->postmeta} WHERE meta_key='wpml_media_lang'");
		$this->languages = array();
		foreach($results as $result) {
			$this->languages[$result->post_id] = $result->meta_value;
		}
		
		// check for missing languages
		foreach($this->parents as $att_id => $parent_id) {
			if (!isset($this->languages[$att_id])) {
				// need to check the language of the parent
				$post_type = $wpdb->get_var("SELECT post_type FROM {$wpdb->posts} WHERE ID = $parent_id");
				$element_lang_details = $sitepress->get_element_language_details($parent_id,'post_'.$post_type);
				if ($element_lang_details) {
					$this->languages[$att_id] = $element_lang_details->language_code;
				}
				
			}
		}
		
	}
	
    /**
     *Add a filter to fix the links for attachments in the language switcher so
     *they point to the corresponding pages in different languages.
     */
    function filter_link($url, $lang_info) {
		global $wp_query, $sitepress;
		
		$current_lang = $sitepress->get_current_language();
		if ($wp_query->is_attachment && $lang_info['language_code'] != $current_lang) {
			$att_id = $wp_query->queried_object_id;
			// is this a duplicate of another attachment
			$translations = $this->_get_translations($att_id);
			
			if (isset($translations[$lang_info['language_code']])) {
				$att_id = $translations[$lang_info['language_code']];
			}
			$link = get_attachment_link($att_id);
			$link = str_replace('?lang='.$current_lang, '', $link);
			$link = str_replace('&lang='.$current_lang, '', $link);
			$link = str_replace('&amp;lang='.$current_lang, '', $link);
			$link = str_replace('/'.$current_lang.'/', '/', $link);
			$url = $sitepress->convert_url($link, $lang_info['language_code']);
			
		}
		
		return $url;
	}
	
	function _get_translations($att_id) {
		global $wpdb;
		
		if ($this->languages == null) {
			$this->_get_lang_info();
		}
		
		$duplicates = array();
		$duplicate_of = $wpdb->get_var("SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id={$att_id} AND meta_key='wpml_media_duplicate_of'");
		if ($duplicate_of) {
			$duplicates = $wpdb->get_col("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_value={$duplicate_of} AND meta_key='wpml_media_duplicate_of'");
			$duplicates[] = $duplicate_of;
			
		} else {
			// this might be an original
			$duplicates = $wpdb->get_col("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_value={$att_id} AND meta_key='wpml_media_duplicate_of'");
			$duplicates[] = $att_id;
		}
		
		$translations = array();
		foreach ($duplicates as $duplicate) {
			if (isset($this->languages[$duplicate])) {
				$translations[$this->languages[$duplicate]] = $duplicate;
			}
		}
		
		return $translations;
	}

	function icl_ls_languages($w_active_languages) {
		static $doing_it = false;
		
		if(is_attachment() && !$doing_it){
			$doing_it = true;
			// Always include missing languages.
			$w_active_languages = icl_get_languages('skip_missing=0');
			$doing_it = false;
		}
		
		return $w_active_languages;
	}
		
    function posts_where_filter($where){
        global $wpdb, $pagenow, $sitepress;
		
		if ($pagenow == 'upload.php' || $pagenow == 'media-upload.php') {
			$lang_code = '';
			if (isset($_GET['lang'])) {
				$lang_code = $_GET['lang'];
			} else {
				if (method_exists($sitepress, 'get_admin_language_cookie')) {
					$lang_code = $sitepress->get_admin_language_cookie();
				}
			}
			
			if ($lang_code != "" && $lang_code != "all") {
				
				$att_ids = array();
				foreach ($this->languages as $att_id => $lang) {
					if ($lang == $lang_code) {
						$att_ids[] = $att_id;
					}
				}
				
				$att_ids = array_merge($att_ids, $this->unattached);
				
				if (sizeof($att_ids) > 0) {
					$att_ids = '(' . implode(',', $att_ids) . ')';
					
					$where .= " AND {$wpdb->posts}.ID in {$att_ids}";
				} else {
					// Add a where clause that wont return any matches.
					$where .= " AND {$wpdb->posts}.ID = -1";
				}
			}
			
		}
		return $where;
	}	
	function views_upload($views) {
		
		return $views;
		
	}
	
	function get_post_metadata($value, $object_id, $meta_key, $single) {
		if ($meta_key == '_thumbnail_id') {
		
			global $wpdb;
			
			$thumbnail = $wpdb->get_var("SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = {$object_id} AND meta_key = '{$meta_key}'");
			
			if ($thumbnail == null) {
				// see if it's available in the original language.
				
				$post_type = $wpdb->get_var("SELECT post_type FROM {$wpdb->posts} WHERE ID = $object_id");
				$trid = $wpdb->get_row("SELECT trid, source_language_code FROM {$wpdb->prefix}icl_translations WHERE element_id={$object_id} AND element_type = 'post_$post_type'");
				if ($trid) {
				
					global $sitepress;
					
					$translations = $sitepress->get_element_translations($trid->trid, 'post_' . $post_type);
					if (isset($translations[$trid->source_language_code])) {
						$translation = $translations[$trid->source_language_code];
						// see if the original has a thumbnail.
						$thumbnail = $wpdb->get_var("SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = {$translation->element_id} AND meta_key = '{$meta_key}'");
						if ($thumbnail) {
							$value = $thumbnail;
						}
					}
				
				}				
				
			} else {
				$value = $thumbnail;
			}
			
		}
		return $value;
	}
	
    function menu(){
        $top_page = apply_filters('icl_menu_main_page', basename(ICL_PLUGIN_PATH).'/menu/languages.php');
		$this->settings['initial_message_shown'] = true;
		update_option('wpml_media_settings', $this->settings);
		
        add_submenu_page($top_page,
							__('Media translation','wpml-media'), 
							__('Media translation','wpml-media'), 'manage_options',
							'wpml-media', array($this,'menu_content'));
    }
    
    function menu_content(){
        global $wpdb;
		
		$total_attachments = $wpdb->get_var("
            SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = 'attachment' AND ID NOT IN 
			(SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = 'wpml_media_processed')");
												
        
        include WPML_MEDIA_PATH . '/menu/management.php';
    }

    function ajax_responses(){  
        if(!isset($_POST['wpml_media_ajx_action'])){
            return;
        }
        global $wpdb;
        
        $limit  = 10;
        
        switch($_POST['wpml_media_ajx_action']){
			case 'rescan_all':
				$wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE meta_key='wpml_media_processed'");
				// Drop throught and do the rescan.
				
            case 'rescan':
                $attachments = $wpdb->get_results("
                    SELECT SQL_CALC_FOUND_ROWS p1.ID, p1.post_parent FROM {$wpdb->posts} p1 WHERE post_type = 'attachment' AND ID NOT IN 
                    (SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = 'wpml_media_processed')
                    ORDER BY p1.ID ASC LIMIT $limit
                ");
                if($attachments){
                    $found = $wpdb->get_var("SELECT FOUND_ROWS()");                
                    foreach($attachments as $attachment){
                        $this->create_duplicate_media($attachment);
                    }
                    echo $found >= $limit ? $found - $limit : 0;
                }else{
                    echo -1;
                }                
                break;
			
			case 'featured_image_scan':
				global $sitepress;
				
				$count = 0;
				
				$featured_images = $wpdb->get_results("SELECT * FROM {$wpdb->postmeta} WHERE meta_key = '_thumbnail_id'");
				$thumbnails = array();
				foreach ($featured_images as $featured) {
					$thumbnails[$featured->post_id] = $featured->meta_value;
				}
				
				if (sizeof($thumbnails)) {
					$ids = implode(', ', array_keys($thumbnails));
					$posts = $wpdb->get_results("SELECT ID, post_type FROM {$wpdb->posts} WHERE ID in ({$ids})");
					foreach ($posts as $post) {
						$row = $wpdb->get_row("SELECT trid, source_language_code FROM {$wpdb->prefix}icl_translations WHERE element_id={$post->ID} AND element_type = 'post_$post->post_type'");
						if ($row && $row->trid && ($row->source_language_code == null || $row->source_language_code == "")) {
					
							$translations = $sitepress->get_element_translations($row->trid, 'post_' . $post->post_type);
							foreach ($translations as $translation) {
								if ($translation->element_id != $post->ID) {
									if (!in_array($translation->element_id, array_keys($thumbnails))) {
										// translation doesn't have a feature image
										update_post_meta($translation->element_id, '_thumbnail_id', $thumbnails[$post->ID]);
										$count += 1;
										
									}
								}
							}
						}
						
					}
				}
				echo $count . __(' Featured images duplicate to translated content', 'wpml-media');
				break;
        }
        exit;
    }    
	
	function create_duplicate_media($attachment) {
		static $parents_processed = array();
		
		if ($attachment->post_parent && !in_array($attachment->post_parent, $parents_processed)) {
			global $wpdb, $sitepress;
			
			// see if we have translations.
			
			$post_type = $wpdb->get_var("SELECT post_type FROM {$wpdb->posts} WHERE ID = $attachment->post_parent");
            $trid = $wpdb->get_var("SELECT trid FROM {$wpdb->prefix}icl_translations WHERE element_id={$attachment->post_parent} AND element_type = 'post_$post_type'");
			if ($trid) {
				
				$attachments = $wpdb->get_col("SELECT ID FROM {$wpdb->posts} WHERE post_type = 'attachment' AND post_parent = $attachment->post_parent");

				$translations = $sitepress->get_element_translations($trid, 'post_' . $post_type);
				foreach ($translations as $translation) {
					if ($translation->element_id && $translation->element_id != $attachment->post_parent) {
						
						$attachments_in_translation = $wpdb->get_col("SELECT ID FROM {$wpdb->posts} WHERE post_type = 'attachment' AND post_parent = $translation->element_id");
						if (sizeof($attachments_in_translation) == 0) {
							// only duplicate attachments if there a none already.
							foreach ($attachments as $att_id) {
								// duplicate the attachement
								
								$this->create_duplicate_attachment($att_id, $translation->element_id, $translation->language_code);
								
							}
						}							
					}
				}
			}
			
			$parents_processed[] = $attachment->post_parent;
			
		}
		update_post_meta($attachment->ID, 'wpml_media_processed', 1);
	}

	function create_duplicate_attachment($att_id, $parent_id, $lang) {
		$post = get_post($att_id);
		$post->post_parent = $parent_id;
		$post->ID = NULL;
		$dup_att_id = wp_insert_post($post);
		// duplicate the post meta data.
		$meta = get_post_meta($att_id, '_wp_attachment_metadata', true);
		add_post_meta($dup_att_id, '_wp_attachment_metadata', $meta);
		update_post_meta($dup_att_id, 'wpml_media_processed', 1);
		update_post_meta($dup_att_id, 'wpml_media_lang', $lang);
		update_post_meta($dup_att_id, 'wpml_media_duplicate_of', $att_id);
		
		update_post_meta($dup_att_id, '_wp_attached_file', get_attached_file($att_id) );
	}
	
    function js_scripts(){
		global $pagenow;
		if ($pagenow == 'media.php') {
			?>
			<script type="text/javascript">
				addLoadEvent(function(){                     
					jQuery('#icl_lang_options').insertBefore(jQuery('#post_id'));
					jQuery('#icl_lang_options').fadeIn();
				});
			</script>
			
			<?php
		}
		if (isset($_GET['page']) && $_GET['page'] == 'wpml-media') {
			?>
			<script type="text/javascript">
				addLoadEvent(function(){                     
					jQuery('#wpml_media_re_scan_but').click(wpml_media_re_scan);                
					jQuery('#wpml_media_re_scan_all_but').click(wpml_media_re_scan_all);
					jQuery('#wpml_media_feature_image_but').click(wpml_media_feature_image_scan)
					
				});
				var wpml_media_scan_started = false;
				var req_timer = 0;
				function wpml_media_toogle_scan(action){
					action = typeof(action) != 'undefined' ? action : 'rescan';

					if(!wpml_media_scan_started){  
						wpml_media_send_request(action); 
						jQuery('#wpml_media_ajx_ldr_1').fadeIn();
						jQuery('#wpml_media_re_scan_but').attr('value','<?php echo icl_js_escape(__('Running', 'wpml-media')) ?>');    
						jQuery('#wpml_media_re_scan_all_but').attr('value','<?php echo icl_js_escape(__('Running', 'wpml-media')) ?>');    
					}else{
						jQuery('#wpml_media_re_scan_but').attr('value','<?php echo icl_js_escape(__('Scan and duplicate attachments', 'wpml-media')); ?>');    
						jQuery('#wpml_media_re_scan_all_but').attr('value','<?php echo icl_js_escape(__('Scan All', 'wpml-media')); ?>');    
						window.clearTimeout(req_timer);
						jQuery('#wpml_media_ajx_ldr_1').fadeOut();
						location.reload();
					}
					wpml_media_scan_started = !wpml_media_scan_started;
					return false;
				}
				
				function wpml_media_send_request(action){
					jQuery.ajax({
						type: "POST",
						url: "<?php echo htmlentities($_SERVER['REQUEST_URI']) ?>",
						data: "wpml_media_ajx_action=" + action,
						success: function(msg){                        
							if(-1==msg || msg==0){
								left = '0';
								wpml_media_toogle_scan();
							}else{
								left=msg;
							}
							
							
							jQuery('#wpml_media_re_scan_toscan').html(left);
							if(wpml_media_scan_started){
								req_timer = window.setTimeout('wpml_media_send_request("rescan")', 1000);
							}
						}                                                            
					});
					
				}
				function wpml_media_re_scan(){
					wpml_media_toogle_scan("rescan");
				}
				function wpml_media_re_scan_all(){
					wpml_media_toogle_scan("rescan_all");
				}
				
				function wpml_media_feature_image_scan(){
					jQuery('#wpml_media_result').fadeOut();
					jQuery('#wpml_media_ajx_ldr_2').fadeIn();
					jQuery('#wpml_media_feature_image_but').attr('value','<?php echo icl_js_escape(__('Running', 'wpml-media')) ?>');    
					jQuery.ajax({
						type: "POST",
						url: "<?php echo htmlentities($_SERVER['REQUEST_URI']) ?>",
						data: "wpml_media_ajx_action=featured_image_scan",
						success: function(msg){
							jQuery('#wpml_media_ajx_ldr_2').fadeOut();
							jQuery('#wpml_media_feature_image_but').attr('value','<?php echo icl_js_escape(__('Scan and duplicate featured images', 'wpml-media')) ?>');
							jQuery('#wpml_media_result').html(msg);
							jQuery('#wpml_media_result').fadeIn();
						}
					});
				}
				
			</script>
			<?php
		}
    }
    
	

    function _initialize_message(){
        $url = rtrim(get_option('siteurl'),'/') . '/wp-admin/admin.php?page=wpml-media';
        ?>
        <div class="message updated"><p><?php printf(__('WPML Media needs to be set up. <a href="%s">Setup translation of media files</a>', 'wpml-media'), 
            $url); ?></p></div>
        <?php
    }
    
    function _no_wpml_warning(){
        ?>
        <div class="message error"><p><?php printf(__('WPML Media is enabled but not effective. It requires <a href="%s">WPML</a> in order to work.', 'wpml-translation-management'), 
            'http://wpml.org/'); ?></p></div>
        <?php
    }
    
    function _old_wpml_warning(){
        ?>
        <div class="message error"><p><?php printf(__('WPML Media is enabled but not effective. It is not compatible with  <a href="%s">WPML</a> versions prior 2.0.5.', 'wpml-translation-management'), 
            'http://wpml.org/'); ?></p></div>
        <?php
    }    
    // Localization
    function plugin_localization(){
        load_plugin_textdomain( 'wpml-media', false, WPML_MEDIA_FOLDER . '/locale');
    }

    function language_filter(){
        global $sitepress;
	
		if (isset($_GET['lang'])) {
			$lang_code = $_GET['lang'];
		} else {
			if (method_exists($sitepress, 'get_admin_language_cookie')) {
				$lang_code = $sitepress->get_admin_language_cookie();
			}
		}

        $active_languages = $sitepress->get_active_languages();


        $active_languages[] = array('code'=>'all','display_name'=>__('All languages','sitepress'));
        foreach($active_languages as $lang){
            if($lang['code'] == $lang_code){
                $px = '<strong>';
                $sx = ' <span class="count">('. $lang['code'] .')<\/span><\/strong>';
            } else {
				$px = '<a href="' . $_SERVER['REQUEST_URI'] . '&lang=' . $lang['code']. '">';
                $sx = '<\/a> <span class="count">('. $lang['code'] .')<\/span>';
			}
            $as[] =  $px . $lang['display_name'] . $sx;
        }

        $allas = join(' | ', $as);
		
        $prot_link = '';
        ?>
        <script type="text/javascript">
            jQuery(".subsubsub").append('<br /><span id="icl_subsubsub"><?php echo $allas ?><\/span><br /><?php echo $prot_link ?>');
        </script>
        <?php
    }


}
