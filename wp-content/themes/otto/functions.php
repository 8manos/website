<?php
/**
 * Register latest jQuery, load on footer
 */
function minimal_jquery_script() {
	if ( ! is_admin() ) {
		wp_deregister_script('jquery');
		wp_register_script('jquery', '//cdnjs.cloudflare.com/ajax/libs/jquery/1.11.1/jquery.min.js', false, '1.11.1', true);
	}
}
add_action('wp_enqueue_scripts', 'minimal_jquery_script');

/**
 * Theme setup
 */
function minimal_theme_setup() {
	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support('automatic-feed-links');

	// Custom menu support.
	register_nav_menu('primary', 'Primary Menu');

	// Most themes need featured images.
	add_theme_support('post-thumbnails' );
	add_image_size('thumb', 150, 150, true);
	add_image_size('thumb2x', 300, 300, true);
	add_image_size('thumb4x', 600, 600, true);
	add_image_size('project', 300, 200);
	add_image_size('project2x', 600, 400);
}
add_action('after_setup_theme', 'minimal_theme_setup');

add_filter('show_admin_bar', '__return_false');

/**
 * Remove code from the <head>
 */
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);// http://www.tech-evangelist.com/2011/09/05/disable-remove-wordpress-shortlink/
//remove_action('wp_head', 'rsd_link'); // Might be necessary if you or other people on this site use remote editors.
//remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
//remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
//remove_action('wp_head', 'index_rel_link'); // Displays relations link for site index
remove_action('wp_head', 'wlwmanifest_link'); // Might be necessary if you or other people on this site use Windows Live Writer.
//remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
//remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0); // Display relational links for the posts adjacent to the current post.

// http://justintadlock.com/archives/2010/07/08/lowercase-p-dangit
remove_filter( 'the_title', 'capital_P_dangit', 11 );
remove_filter( 'the_content', 'capital_P_dangit', 11 );
remove_filter( 'comment_text', 'capital_P_dangit', 31 );

// Hide the version of WordPress you're running from source and RSS feed // Want to JUST remove it from the source? Try: remove_action('wp_head', 'wp_generator');
add_filter('the_generator', '__return_false');

add_filter('nav_menu_css_class' , 'page_id_nav_class' , 10 , 2);
function page_id_nav_class($classes, $item){
	if($item->object == "page"){
		$classes[] = "page-id-".$item->object_id;
	}
	return $classes;
}

add_filter( 'thermal_post_entity', 'add_team_meta' );
function add_team_meta($data){
	$contact_links = get_post_meta( $data->id, '_contact_link', true );
	$data->contact_links = $contact_links;

	$ext_link = get_post_meta( $data->id, '_url', true );
	$data->ext_link = $ext_link;

	$featuring = get_post_meta( $data->id, '_featuring', true );
	$data->featuring = $featuring;

	$img_id = get_post_thumbnail_id( $data->id );
	$images = array();
	if ($img_id) {
		$images['thumb'] = wp_get_attachment_image_src( $img_id, 'thumb' )[0];
		$images['thumb2x'] = wp_get_attachment_image_src( $img_id, 'thumb2x' )[0];
		$images['thumb4x'] = wp_get_attachment_image_src( $img_id, 'thumb4x' )[0];
		$images['project'] = wp_get_attachment_image_src( $img_id, 'project' )[0];
		$images['project2x'] = wp_get_attachment_image_src( $img_id, 'project2x' )[0];
	}
	$data->images = $images;

	$gallery = get_post_meta( $data->id, '_gallery', true );
	if (is_array($gallery)) {
		foreach ($gallery as $key => $img_id) {
			$gallery_src[$key]['small'] = wp_get_attachment_image_src( $img_id, 'project' );
			$gallery_src[$key]['medium'] = wp_get_attachment_image_src( $img_id, 'project2x' );
		}
		$data->gallery = $gallery_src;
	}

  return $data;
}

function flags_menu() {
	$langs = pll_the_languages( array('raw'=>1) );
	foreach ( $langs as $key => $lang ) {
		if ( $lang['current_lang'] ) {
			$current = $lang;
			unset( $langs[$key] );
			break;
		}
	}
	array_unshift( $langs, $current );

	foreach ( $langs as $key => $lang ) {
		$classes = implode(' ', $lang['classes']);

		echo "<li class='$classes'>";
			echo ( $key == 0 )? "<span>":"<a hreflang='{$lang['slug']}' href='{$lang['url']}'>";
				echo "<img src='{$lang['flag']}' title='{$lang['name']}' alt='{$lang['name']}' />&nbsp;{$lang['name']}";
			echo ( $key == 0 )? "</span>":"</a>";
		echo "</li>";
	}
}
