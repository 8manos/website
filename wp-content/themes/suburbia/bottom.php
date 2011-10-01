<?php if ( !is_404() ) :?>
    <div class="bottom navigation">
        <h3>Navigation</h3>
        <?php
            if (function_exists('wp_pagenavi')) {
                wp_pagenavi();
            } else {
                previous_posts_link(__('Prev'));
                next_posts_link(__('Next'));
            }
        ?>
    </div>
<?php endif; ?>

<div class="bottom">
<?php if (!function_exists('dynamic_sidebar') ||  
          !dynamic_sidebar('Bottom 1')) : ?>
	<h3>Links</h3>
	<ul><?php wp_list_bookmarks('categorize=&title_li='); ?></ul> 
<?php endif; ?>
</div>

<div class="bottom">
	
<?php if (!function_exists('dynamic_sidebar') ||  
          !dynamic_sidebar('Bottom 2')) : ?>
          
          
    <h3>Contact</h3>
    <ul>
    	<li><a href="mailto:<?php echo (get_option('suburbia_eml'))? get_option('suburbia_eml') : 'wpshower@gmail.com'; ?>" title="E-mail">Send me an E-mail</a></li>
        <li><a href="<?php echo (get_option('suburbia_fbkurl'))? get_option('suburbia_fbkurl') : 'http://www.facebook.com/wpshower'; ?>" title="Facebook">Become fan on Facebook</a></li>
        <li><a href="<?php echo (get_option('suburbia_twturl'))? get_option('suburbia_twturl') : 'http://twitter.com/wpshower'; ?>" title="Twitter">Follow me on Twitter</a></li>
    </ul>
<?php endif; ?>

</div>

<div class="bottom">
<?php if (!function_exists('dynamic_sidebar') ||  
          !dynamic_sidebar('Bottom 3')) : ?>


        		<?php         		
	        	ob_start();
				ob_implicit_flush(0);
				echo (get_option('suburbia_twtun'))? get_option('suburbia_twtun') : 'wpshower'; 
				$twitter_username = ob_get_contents();
				ob_end_clean();
				?>		

	<h3>@<?php echo $twitter_username; ?></h3>

    <p><?php displayLatestTweet($twitter_username); ?></p>
<?php endif; ?>
</div>

<div class="bottom">
<?php if (!function_exists('dynamic_sidebar') ||  
          !dynamic_sidebar('Bottom 4')) : ?>
	<h3>Feeds</h3>
    <p>Susbscribe to our awesome <a href="<?php bloginfo('rss2_url'); ?>" title="RSS">Blog Feed</a> or <a href="<?php bloginfo('comments_rss2_url'); ?>">Comments Feed</a></p>
<?php endif; ?>

</div>
<div style="clear: both;"></div>
