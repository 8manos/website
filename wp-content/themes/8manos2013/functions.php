<?php
/**
 * Register latest jQuery, load on footer
 */
function minimal_jquery_script() {
    if (!is_admin()) {
        wp_deregister_script('jquery');
        wp_register_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js', false, '1.10.2');
    }
}
add_action('wp_enqueue_scripts', 'minimal_jquery_script');

/**
 * Theme setup
 */
function minimal_theme_setup() {
    // Adds RSS feed links to <head> for posts and comments.
    add_theme_support('automatic-feed-links');

    // Most themes need featured images.
    add_theme_support('post-thumbnails');
    add_image_size( 'team-thumb', 240, 240, true );

    // Custom menu support.
    register_nav_menu('primary', 'Primary Menu');

    //disable cleaner gallery stylesheet
    add_theme_support( 'cleaner-gallery' );
}
add_action('after_setup_theme', 'minimal_theme_setup');

/**
 * Remove code from the <head>
 */
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0); // http://www.tech-evangelist.com/2011/09/05/disable-remove-wordpress-shortlink/
//remove_action('wp_head', 'rsd_link'); // Might be necessary if you or other people on this site use remote editors.
//remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
//remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
//remove_action('wp_head', 'index_rel_link'); // Displays relations link for site index
remove_action('wp_head', 'wlwmanifest_link'); // Might be necessary if you or other people on this site use Windows Live Writer.
//remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
//remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0); // Display relational links for the posts adjacent to the current post.
// http://justintadlock.com/archives/2010/07/08/lowercase-p-dangit
remove_filter('the_title', 'capital_P_dangit', 11);
remove_filter('the_content', 'capital_P_dangit', 11);
remove_filter('comment_text', 'capital_P_dangit', 31);

// Hide the version of WordPress you're running from source and RSS feed // Want to JUST remove it from the source? Try: remove_action('wp_head', 'wp_generator');
add_filter('the_generator', '__return_false');

function no_sub_team( $query ) {

    if ( ! is_admin() && $query->is_main_query() && $query->is_post_type_archive( 'equipo' ) ) {
        $subteams = get_terms( 'subteam');

        $subteams_slugs = array_map (function ($term) {
            return $term->slug;
        }, $subteams);

        $query->set( 'tax_query', array( array('taxonomy' => 'subteam', 'field' => 'slug', 'terms' => $subteams_slugs, 'operator' => 'NOT IN') ) );
    }
}
add_action( 'pre_get_posts', 'no_sub_team' );
