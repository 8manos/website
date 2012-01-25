<?php
if((!isset($sitepress_settings['existing_content_language_verified']) || !$sitepress_settings['existing_content_language_verified']) || 2 > count($sitepress->get_active_languages())){
    return;
}
$active_languages = $sitepress->get_active_languages();              
$locales = $sitepress->get_locale_file_names();

?>

<div class="wrap">
    <div id="icon-wpml" class="icon32"><br /></div>
    <h2><?php _e('Theme and plugins localization', 'sitepress') ?></h2>    

    <h3><?php _e('Select how to localize the theme','sitepress'); ?></h3>
    <p><?php _e("If your theme's texts are wrapped in gettext calls, WPML can help you display it multilingual.",'sitepress'); ?></p>
    <form name="icl_theme_localization_type" id="icl_theme_localization_type" method="post" action="">
    <input type="hidden" name="icl_ajx_action" value="icl_save_theme_localization_type" />
    <ul>
        <?php 
            if(!defined('WPML_ST_VERSION')){                
                $icl_st_note = __("WPML's String Translation module lets you translate the theme, plugins and admin texts. To install it, go to your WPML account, click on Downloads and get WPML String Translation.", 'sitepress');                                    
                $st_disabled = 'disabled="disabled" '; 
            }else{
                $st_disabled = ''; 
            }
            $td_value = isset($sitepress_settings['gettext_theme_domain_name'])?$sitepress_settings['gettext_theme_domain_name']:'';
            if(!empty($sitepress_settings['theme_localization_load_textdomain'])){
                $ltd_checked = 'checked="checked" ';                
            }else{
                $ltd_checked = '';
            }
        ?>
        <li><label><input <?php echo $st_disabled; ?>type="radio" name="icl_theme_localization_type" value="1" <?php 
            if($sitepress_settings['theme_localization_type']==1):?>checked="checked"<?php endif; ?> />&nbsp;<?php _e('Translate by WPML.', 'sitepress') ?></label>
            <?php if(isset($icl_st_note)) echo '<br><small><i>' . $icl_st_note . '</i></small>'; ?>
            </li>
        <li><label>
            <input type="radio" name="icl_theme_localization_type" value="2" <?php 
            if($sitepress_settings['theme_localization_type']==2):?>checked="checked"<?php endif; ?> />&nbsp;<?php _e('Translate using .mo files.', 'sitepress') ?></label>
            <div id="icl_tt_type_extra" <?php if($sitepress_settings['theme_localization_type']!=2):?>style="display:none"<?php endif;?>>
                <label><input type="checkbox" name="icl_theme_localization_load_td" value="1" <?php echo $ltd_checked ?>/>
                &nbsp;<?php _e("Automatically load the theme's .mo file using 'load_theme_textdomain'.", 'sitepress') ?></label>
                <label id="icl_tt_type_extra_td" <?php if(empty($ltd_checked)):?>style="display:none"<?php endif;?>>
                <?php _e('Enter textdomain value:', 'sitepress'); ?>
                <input type="text" name="textdomain_value" value="<?php echo esc_attr($td_value) ?>" />                            
                </label>
            </div>
        </li>
    </ul>
    <p>
        <input class="button" name="save" value="<?php echo __('Save','sitepress') ?>" type="submit" />        
        <span style="display:none" class="icl_form_errors icl_form_errors_1"><?php _e('Please enter a value for the textdomain.', 'sitepress'); ?></span>
    </p>
    <img src="<?php echo ICL_PLUGIN_URL ?>/res/img/question-green.png" width="29" height="29" alt="need help" align="left" /><p style="margin-top:14px;">&nbsp;<a href="http://wpml.org/?page_id=2717"><?php _e('Theme localization instructions', 'sitepress')?> &raquo;</a></p>
    </form>
    
    <?php if($sitepress_settings['theme_localization_type'] > 0):?>
    <br />
    <div id="icl_tl">
    <h3><?php _e('Language locale settings', 'sitepress') ?></h3>
    <p><?php _e('Select the locale to use for each language. The locale for the default language is set in your wp_config.php file.', 'sitepress') ?></p>
    <form id="icl_theme_localization" name="icl_theme_localization" method="post" action="">
    <input type="hidden" name="icl_post_action" value="save_theme_localization" />    
    <div id="icl_theme_localization_wrap"><div id="icl_theme_localization_subwrap">    
    <table id="icl_theme_localization_table" class="widefat" cellspacing="0">
    <thead>
    <tr>
    <th scope="col"><?php echo __('Language', 'sitepress') ?></th>
    <th scope="col"><?php echo __('Code', 'sitepress') ?></th>
    <th scope="col"><?php echo __('Locale file name', 'sitepress') ?></th>        
    <th scope="col"><?php printf(__('MO file in %s', 'sitepress'), LANGDIR) ?></th>        
    <?php if($sitepress_settings['theme_localization_type']==2):?>
    <th scope="col"><?php printf(__('MO file in %s', 'sitepress'), '/wp-content/themes/' . get_option('template')) ?></th>        
    <?php endif; ?>
    </tr>        
    </thead>        
    <tfoot>
    <tr>
    <th scope="col"><?php echo __('Language', 'sitepress') ?></th>
    <th scope="col"><?php echo __('Code', 'sitepress') ?></th>
    <th scope="col"><?php echo __('Locale file name', 'sitepress') ?></th>        
    <th scope="col"><?php printf(__('MO file in %s', 'sitepress'), LANGDIR) ?></th>        
    <?php if($sitepress_settings['theme_localization_type']==2):?>
    <th scope="col"><?php printf(__('MO file in %s', 'sitepress'), '/wp-content/themes/' . get_option('template')) ?></th>        
    <?php endif; ?>
    </tr>        
    </tfoot>
    <tbody>
    <?php foreach($active_languages as $lang): ?>
    <tr>
    <td scope="col"><?php echo $lang['display_name'] ?></td>
    <td scope="col"><?php echo $lang['code'] ?></td>
    <td scope="col">
        <input type="text" size="10" name="locale_file_name_<?php echo $lang['code']?>" value="<?php echo isset($locales[$lang['code']]) ? $locales[$lang['code']] : ''; ?>" />.mo
    </td> 
    <td>
        <?php if(@is_readable(ABSPATH . LANGDIR . '/' . $locales[$lang['code']] . '.mo')): ?>
        <span class="icl_valid_text"><?php echo __('File exists.', 'sitepress') ?></span>                
		<?php elseif($lang['code'] != 'en' ): ?>
        <span class="icl_error_text"><?php echo __('File not found!', 'sitepress') ?></span>
        <?php endif; ?>
    </td>
    <?php if($sitepress_settings['theme_localization_type']==2):?>       
    <td>
        <?php 
            $mofound = @is_readable($sitepress_settings['theme_language_folders']['parent'] . '/' . $locales[$lang['code']] . '.mo') 
                        || @is_readable($sitepress_settings['theme_language_folders']['child'] . '/' . $locales[$lang['code']] . '.mo') 
                        || @is_readable(TEMPLATEPATH . '/' . $locales[$lang['code']] . '.mo')
        ?>
        <?php if($mofound): ?>
        <span class="icl_valid_text"><?php echo __('File exists.', 'sitepress') ?></span>                
        <?php elseif($lang['code'] != 'en' ): ?>
        <span class="icl_error_text"><?php echo __('File not found!', 'sitepress') ?></span>
        <?php endif; ?>        
    </td>              
    <?php endif; ?> 
    </tr>
    <?php endforeach; ?>                                                          
    </tbody>        
    </table>
    
    </div>
    </div>
    <p>
        <input class="button" name="save" value="<?php echo __('Save','sitepress') ?>" type="submit" />
        <span class="icl_ajx_response" id="icl_ajx_response_fn"></span>
    </p>
    </form>
    <br /><br />
    </div> 
    <?php endif; ?>
    
    <?php do_action('icl_custom_localization_type'); ?>
    
    
    <?php do_action('icl_menu_footer'); ?>
               
</div>
