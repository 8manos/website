
<script type="text/javascript">
var wpml_media_ajxloaderimg_src = '<?php echo WPML_MEDIA_URL ?>/res/img/ajax-loader.gif';
var wpml_media_ajxloaderimg = '<img src="'+wpml_media_ajxloaderimg_src+'" alt="loading" width="16" height="16" />';
</script>

<div class="wrap">

    <div id="icon-wpml" class="icon32"><br /></div>
    <h2><?php echo __('Media translation', 'wpml-media') ?></h2>    
    
    <table class="widefat">
        <thead>
            <tr>
                <th><?php echo __('Create media attachments for translated content', 'wpml-media') ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <p>
                        <?php echo __('Use this to create duplicate media attachments for translated content. For example, this can duplicate your media gallery for your translated galleries without you having to upload all the media again.', 'wpml-media'); ?>
                    </p>
                    <p>
                        <span id="wpml_media_re_scan_toscan"><?php echo $total_attachments ?></span> <?php echo __('attachments not processed', 'wpml-media')?>    
                        <input type="submit" name="wpml_media_re_scan" value="<?php echo __('Scan and duplicate attachments', 'wpml-media') ?>" id="wpml_media_re_scan_but" class="button-secondary action" title="<?php echo __('Scan and create duplicate media for translated content', 'wpml-media'); ?>" />
                        <input type="submit" name="wpml_media_re_scan_all" value="<?php echo __('Scan All', 'wpml-media') ?>" id="wpml_media_re_scan_all_but" class="button-secondary action" title="<?php echo __('Scan all attachments and create duplicate media for translated content', 'wpml-media'); ?>" />
                
                        <img id="wpml_media_ajx_ldr_1" src="<?php echo WPML_MEDIA_URL ?>/res/img/ajax-loader.gif" width="16" height="16" style="display:none" alt="loading" />
                    </p>
                    <p>
                        <i><?php echo __('Note: attachments are only duplicated if the translated content has no existing attachments.', 'wpml-media'); ?></i>
                    </p>
                </td>
            </tr>
        </tbody>
    </table>
        
    <br />
    
    <table class="widefat">
        <thead>
            <tr>
                <th><?php echo __('Featured images', 'wpml-media') ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    
                    <p>
                        <?php echo __('Scan posts and pages and duplicate the featured image from the original to any translations without a featured image already.', 'wpml-media'); ?>
                    </p>
                    <input type="submit" name="wpml_media_duplicate_featured_image" value="<?php echo __('Scan and duplicate featured images', 'wpml-media') ?>" id="wpml_media_feature_image_but" class="button-secondary action" title="<?php echo __('Scan and duplicate featured images for translated content', 'wpml-media'); ?>" />
                    <img id="wpml_media_ajx_ldr_2" src="<?php echo WPML_MEDIA_URL ?>/res/img/ajax-loader.gif" width="16" height="16" style="display:none" alt="loading" />
                    <span id="wpml_media_result" style="display:none"></span>
    
                </td>
            </tr>
        </tbody>
    </table>

</div>