<?php 
    global $iclTranslationManagement;
    $selected_translator = $iclTranslationManagement->get_selected_translator();
?>
<div class="wrap">
    <div id="icon-wpml" class="icon32"><br /></div>
    <h2><?php echo __('Translation management', 'wpml-translation-management') ?></h2>    
    
    <?php do_action('icl_tm_messages'); ?>
    
    <a class="nav-tab <?php if(!isset($_GET['sm']) || (isset($_GET['sm']) && $_GET['sm']=='dashboard')): ?> nav-tab-active<?php endif;?>" 
        href="admin.php?page=<?php echo WPML_TM_FOLDER ?>/menu/main.php&sm=dashboard"><?php _e('Translation Dashboard', 'wpml-translation-management') ?></a>
    <?php if ( current_user_can('list_users') || ICL_PRE_WP3): ?>
    <a class="nav-tab<?php if(isset($_GET['sm']) && $_GET['sm']=='translators'): ?> nav-tab-active<?php endif;?>" 
        href="admin.php?page=<?php echo WPML_TM_FOLDER ?>/menu/main.php&sm=translators"><?php _e('Translators', 'wpml-translation-management') ?></a> 
    <?php endif;  ?>        
    <a class="nav-tab <?php if(isset($_GET['sm']) && $_GET['sm']=='jobs'): ?> nav-tab-active<?php endif;?>" 
        href="admin.php?page=<?php echo WPML_TM_FOLDER ?>/menu/main.php&sm=jobs"><?php _e('Translation Jobs', 'wpml-translation-management') ?></a>
    <a class="nav-tab <?php if(isset($_GET['sm']) && $_GET['sm']=='mcsetup'): ?> nav-tab-active<?php endif;?>" 
        href="admin.php?page=<?php echo WPML_TM_FOLDER ?>/menu/main.php&sm=mcsetup"><?php _e('Multilingual Content Setup', 'wpml-translation-management') ?></a>
    <a class="nav-tab <?php if(isset($_GET['sm']) && $_GET['sm']=='notifications'): ?> nav-tab-active<?php endif;?>" 
        href="admin.php?page=<?php echo WPML_TM_FOLDER ?>/menu/main.php&sm=notifications"><?php _e('Translation Notifications', 'wpml-translation-management') ?></a>
    
    <div class="icl_tm_wrap">
    
    <?php 
        switch(@$_GET['sm']){
            case 'translators':
                include dirname(__FILE__) . '/sub/translators.php';
                break;
            case 'jobs':
                include dirname(__FILE__) . '/sub/jobs.php';
                break;
            case 'mcsetup':
                include dirname(__FILE__) . '/sub/mcsetup.php';
                break;
            case 'notifications':
                include dirname(__FILE__) . '/sub/notifications.php';
                break;
            default:
                include dirname(__FILE__) . '/sub/dashboard.php';
                
        }
    ?>
    
    </div>
    
    
</div>
