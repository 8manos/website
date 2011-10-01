<?php
// =========================
// = Initiate widget areas =
// =========================
if (function_exists('register_sidebar')) {
    register_sidebar(array(
          'name'=>'Sidebar',
          'before_widget' => '',
          'after_widget' => '',
          'before_title' => '<h3 class="sidebar-left">',
          'after_title' => '</h3>',
    ));
} 

if (function_exists('register_sidebar')) {
    register_sidebar(array(
          'name'=>'Bottom 1',
          'before_widget' => '',
          'after_widget' => '',
          'before_title' => '<h3 class="sidebar-bottom">',
          'after_title' => '</h3>',
    ));
} 
if (function_exists('register_sidebar')) {
    register_sidebar(array(
          'name'=>'Bottom 2',
          'before_widget' => '',
          'after_widget' => '',
          'before_title' => '<h3 class="sidebar-bottom">',
          'after_title' => '</h3>',
    ));
} 
if (function_exists('register_sidebar')) {
    register_sidebar(array(
          'name'=>'Bottom 3',
          'before_widget' => '',
          'after_widget' => '',
          'before_title' => '<h3 class="sidebar-bottom">',
          'after_title' => '</h3>',
    ));
} 
if (function_exists('register_sidebar')) {
    register_sidebar(array(
          'name'=>'Bottom 4',
          'before_widget' => '',
          'after_widget' => '',
          'before_title' => '<h3 class="sidebar-bottom">',
          'after_title' => '</h3>',
    ));
}

update_option('posts_per_page', 7); // Posts per page

// ====================================
// = WordPress 2.9+ Thumbnail Support =
// ====================================
add_theme_support( 'post-thumbnails' );
set_post_thumbnail_size( 155, 110, true ); // 305 pixels wide by 380 pixels tall, set last parameter to true for hard crop mode
add_image_size( 'one', 155, 110, true ); // Set thumbnail size
add_image_size( 'two', 350, 248, true ); // Set thumbnail size
add_image_size( 'big', 546, 387, true ); // Set thumbnail size


// ===========================
// = WordPress 3.0+ Nav Menu =
// ===========================
register_nav_menus(
	array(
	'custom-menu'=>__('Sephia menu'),
	)
);
function custom_menu(){
	wp_list_pages('title_li=&depth=1');
}

// ==================================
// = WP 3.0 Custom Background Setup =
// ==================================
if ( function_exists( 'add_custom_background' ) )
    { add_custom_background(); }

// ========================
// = Display latest tweet =
// ========================
function displayLatestTweet($demianpeeters){
 include_once(ABSPATH.WPINC.'/rss.php');
 $latest_tweet = fetch_rss("http://search.twitter.com/search.atom?q=from:" . $demianpeeters . "&rpp=3");
 echo $latest_tweet->items[0]['atom_content'];
}

// ==============
// = Get TinyUrl =
// ==============
function getTinyUrl($url) {
    $tinyurl = @file_get_contents("http://tinyurl.com/api-create.php?url=".$url);
    return $tinyurl;
}

// ==============
// = Word Count =
// ==============
function wcount(){
    ob_start();
    the_content();
    $content = ob_get_clean();
    return sizeof(explode(" ", $content));
}

// =========================
// = Change excerpt lenght =
// =========================
add_filter('excerpt_length', 'my_excerpt_length');
function my_excerpt_length($length) {
return 35; }

// =================================
// = Change default excerpt symbol =
// =================================
function suburbia_excerpt($text) { return str_replace('[...]', '...', $text); } add_filter('the_excerpt', 'suburbia_excerpt');



// =================================
// = Add comment callback function =
// =================================
function suburbia_comments($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
        <div id="comment-<?php comment_ID(); ?>" class="clear">
            <?php if ($comment->comment_approved == '0') : ?>
                <em><?php _e('Your comment is awaiting moderation.') ?></em>
                <br />
            <?php endif; ?>
            <div class="comment-meta">
                <?php printf(__('<span class="fn">%s</span>'), get_comment_author_link()) ?>
                <div class="comment-date"><?php printf(__('%1$s'), get_comment_date()) ?></div>
                <?php echo get_avatar( $comment , $size='55' ); ?>
            </div>
            
            <?php comment_text() ?>
            <div class="reply">
                <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
            </div>

         </div>
<?php
} ?>
<?php

// ====================
// = Add options page =
// ====================
function themeoptions_admin_menu()
{
	// here's where we add our theme options page link to the dashboard sidebar
	add_theme_page("Theme Options", "Theme Options", 'edit_themes', basename(__FILE__), 'themeoptions_page');
}

function themeoptions_page()
{
	if ( $_POST['update_themeoptions'] == 'true' ) { themeoptions_update(); }  //check options update
	// here's the main function that will generate our options page
	?>
	<div class="wrap">
		<div id="icon-themes" class="icon32"><br /></div>
		<h2>SUBURBIA Theme Options</h2>

		<form method="POST" action="">
			<input type="hidden" name="update_themeoptions" value="true" />

			<h3>Your social links</h3>
			
			
<table width="90%" border="0">
  <tr>
    <td valign="top" width="50%"><p><label for="fbkurl"><strong>Facebook URL</strong></label><br /><input type="text" name="fbkurl" id="fbkurl" size="32" value="<?php echo get_option('suburbia_fbkurl'); ?>"/></p><p><small><strong>example:</strong><br /><em>http://www.facebook.com/wpshower</em></small></p></td>
    <td valign="top"width="50%"><p><label for="twturl"><strong>Twitter URL</strong></label><br /><input type="text" name="twturl" id="twturl" size="32" value="<?php echo get_option('suburbia_twturl'); ?>"/></p><p><small><strong>example:</strong><br /><em>http://twitter.com/wpshower</em></small></p>
</td>
  </tr>
</table>

			<h3>Custom logo</h3>
			
			
<table width="90%" border="0">
  <tr>
    <td valign="top" width="50%"><p><label for="custom_logo"><strong>URL to your custom logo</strong></label><br /><input type="text" name="custom_logo" id="custom_logo" size="32" value="<?php echo get_option('suburbia_custom_logo'); ?>"/></p><p><small><strong>Usage:</strong><br /><em><a href="<?php bloginfo("url"); ?>/wp-admin/media-new.php">Upload your logo</a> (246 x 35px) using WordPress Media Library and insert its URL here</em></small></p></td>
    <td valign="top"width="50%"><p>
    	        <?php         		
	        	ob_start();
				ob_implicit_flush(0);
				echo get_option('suburbia_custom_logo'); 
				$my_logo = ob_get_contents();
				ob_end_clean();
        		if (
		        $my_logo == ''
        		): ?>
        		<a href="<?php bloginfo("url"); ?>/">
				<img src="<?php bloginfo('template_url'); ?>/images/logo.png" alt="<?php bloginfo('name'); ?>"></a>
        		<?php else: ?>
        		<a href="<?php bloginfo("url"); ?>/"><img src="<?php echo get_option('suburbia_custom_logo'); ?>"></a>       		
        		<?php endif ?>
    </p>
</td>
  </tr>
</table>


			<h3>Ápropos</h3>
			
			
<table width="90%" border="0">
  <tr>
    <td valign="top" width="50%"><p><label for="apropos"><strong>URL to your custom logo</strong></label><br /><input type="text" name="apropos" id="apropos" size="32" value="<?php echo get_option('suburbia_apropos'); ?>"/></p><p><small><strong>Usage:</strong><br /><em><a href="<?php bloginfo("url"); ?>/wp-admin/media-new.php">Upload your logo</a> (155 x 155px) using WordPress Media Library and insert its URL here</em></small></p></td>
    <td valign="top"width="50%"><p>
    	        <?php         		
	        	ob_start();
				ob_implicit_flush(0);
				echo get_option('suburbia_apropos'); 
				$my_logo = ob_get_contents();
				ob_end_clean();
        		if (
		        $my_logo == ''
        		): ?>
        		<a href="<?php bloginfo("url"); ?>/">
				<img src="<?php bloginfo('template_url'); ?>/images/logo2.gif" alt="<?php bloginfo('name'); ?>"></a>
        		<?php else: ?>
        		<a href="<?php bloginfo("url"); ?>/"><img src="<?php echo get_option('suburbia_apropos'); ?>"></a>       		
        		<?php endif ?>
    </p>
</td>
  </tr>
</table>



			<h3>Advanced options</h3>
			
			
<table width="90%" border="0">
<tr>
    <td valign="top" width="50%"><p><label for="twtun"><strong>Twitter username</strong></label><br /><input type="text" name="twtun" id="twtun" size="32" value="<?php echo get_option('suburbia_twtun'); ?>"/><p><small><strong>Example: </strong><em>wpshower</em><br />Your latest tweet will be displayed within the 3rd bottom widget area.</small></p>
    </td>
    <td valign="top" width="50%"><p><label for="eml"><strong>E-mail</strong></label><br /><input type="text" name="eml" id="eml" size="32" value="<?php echo get_option('suburbia_eml'); ?>"/><p><small><strong>Example: </strong><em>wpshower@gmail.com</em></small></p>
    </td>
  </tr>  
</table>
			
			
			
			<p><input type="submit" name="search" value="Update Options" class="button button-primary" /></p>
		</form>

	</div>
	<?php
}

add_action('admin_menu', 'themeoptions_admin_menu');



// Update options function

function themeoptions_update()
{
	// this is where validation would go
	update_option('suburbia_fbkurl', 	$_POST['fbkurl']);
	update_option('suburbia_twturl', 	$_POST['twturl']);
	update_option('suburbia_apropos', 	$_POST['apropos']);
	update_option('suburbia_custom_logo', 	$_POST['custom_logo']);
	update_option('suburbia_twtun', 	$_POST['twtun']);
	update_option('suburbia_eml', 	$_POST['eml']);


}

function n_posts_link_attributes(){
	return 'class="nextpostslink"';
}
function p_posts_link_attributes(){
	return 'class="previouspostslink"';
}
add_filter('next_posts_link_attributes', 'n_posts_link_attributes');
add_filter('previous_posts_link_attributes', 'p_posts_link_attributes');

function custom_excerpt($string, $limit) {
    $words = explode(" ",$string);
    if ( count($words) >= $limit) $dots = '...';;
    echo implode(" ",array_splice($words,0,$limit)).$dots;
}

function commentdata_fix($commentdata) {
    if ( $commentdata['comment_author_url'] == 'Website') {
        $commentdata['comment_author_url'] = '';
    }
    if ($commentdata['comment_content'] == 'Comment') {
        $commentdata['comment_content'] = '';
    }
    return $commentdata;
}
add_filter('preprocess_comment','commentdata_fix');

?>
