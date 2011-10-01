<div class="aside"<?php echo (is_category() || is_tag() || is_page())? 'style="border-bottom: 1px solid #e0e0e0 !important; border-top: none !important;"' : ''; ?>>
    <div class="logo-space"></div>
    <?php if (!function_exists('dynamic_sidebar') ||
              !dynamic_sidebar('Sidebar')) : ?>

        <h3>Pages</h3>
        <ul>
        <?php wp_list_pages('depth=1&title_li='); ?>
        </ul>

        <h3>Categories</h3>
        <ul>
        <?php wp_list_categories('show_count=1&title_li='); ?>
        </ul>

    <?php endif; ?>
</div>
