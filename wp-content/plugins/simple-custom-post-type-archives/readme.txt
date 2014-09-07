=== Simple Custom Post Type Archives ===
Contributors: jakemgold, thinkoomph
Donate link: http://www.get10up.com/plugins/simple-custom-post-type-archives-wordpress/
Tags: custom post types, template files, archives, permalinks, conditionals
Requires at least: 3.0
Tested up to: 3.0.1
Stable tag: 0.9.3

Adds friendly permalink support, template files, and a new conditional for public, non-hierarchical custom post types.

== Description ==

Adds friendly permalink support, template files, and a conditional for public, non-hierarchical custom post types.

WordPress 3.0 opens the door for entirely new content management possibilities with custom post types. As awesome as it is, the first version to implement custom post types is missing a few things. This plug-in patches one of those holes: templates and friendly permalinks that enable "archives" for custom post types, much the like the "blog home" is an archive for all "post" post types.

This plug-in will only add custom post type archives for non-hierarchical (post-like), public custom post types with a "slug" or permalink.

For example, suppose you have a custom post type "Movies". WordPress 3.0 takes care of permalinks for individual movie content, for example, http://yourblog.com/movies/casa-blanca/. You might think you could get an archive of all recently added movies (just like the blog home is an archive of all recently added posts) by going to http://yourblog.com/movies/, but this isn't supported out of the gate. You can do it without permalinks by going to http://yourblog.com/?post_type=movies, but even then, you're forced to use the index.php template file in your theme. This plug-in enables archive permalinks (yourblog.com/movies), adds two new template files in the hierarchy (i.e. type-movies.php and type.php), and adds a new "is_custom_post_type_archive" conditional you can use in your theme!

1. Adds support for custom post type archive permalinks, i.e. `yourblog.com/(custom-post-type-name)` - including paging (/page/2/) and feedS (/feed/)
1. Adds two new template files to the hierarchy, `type-(custom-post-type-name).php` (or post type slug) and `type.php`
1. Adds new body classes to custom post type archives, `custom-post-type-archive` and `custom-post-type-(post-type-name)-archive`
1. Fixes the `is_home` conditional check on custom post type archives (incorrectly reports true by default)
1. Adds a new conditional, `is_custom_post_type_archive` for use in your themes: can optionally be passed name of post type
1. Fixes the wp_title output on custom post type archives to show the custom type's label
1. Automatically adds feed links for custom post type archives when on a custom archive or singular custom post type if "automatic feed links" is enabled in your theme

What _didn't_ make it in this pre-1.0 release:

1. New navigation menu widget for easily adding custom post type archives to navigation


== Installation ==

1. Install easily with the WordPress plugin control panel or manually download the plugin and upload the extracted
folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Create new `type-(custom-post-type-name).php` (may also use slug) and / or `type.php` template files in theme, if desired 


== Frequently Asked Questions ==

= Why aren't my new custom post type archive permalinks working? =

First, make certain the custom post type is supported by the plug-in. This plug-in will only add archive permalink for non-hierarchical, public custom post types that have a "slug." There's plenty of documentation on register_post_type if you need help verifying that.

If you're certain it's a supported post type archive, try going to Settings > Permalinks, and just hit "save" to refresh the permalink settings and cache.

If that doesn't work, try getting in touch on our support page.

= How do I add a navigation element for my custom post type archive? =

Manually. Any number of ways, like a "Custom Links" menu item using the new nav menus, manually adding a link in your theme, or (if you're clever) even fetching the post type objects that meet the requirements using some API jujitsu.

I'm going to try and add a new meta box to the "Menus" manager that allows easy adding of custom post types. I got about 75% of the way there already, but there's a lot going on in that API that I still have to wrap my head around!

In the mean time, consider using the body class as a work around.


== Changelog ==

= 0.9.3 =
* Support for post queries with multiple post types (fixes illegal offset error)
* Allows passing post type via array in a custom query (another potential illegal offset cause)
* Better protection against passing invalid post types into functions
* Special filters and post query properties now properly registered upon query, not template redirection
* Fewer redundant checks in code / general optimizations

= 0.9.2 =
* Automatically outputs custom post type archive feed links if "automatic feed links" is enabled in your theme
* Further improved validation / conditional checks to avoid very rare warnings in debug mode

= 0.9 =
* Full feed (rss / rss2 / etc) support for custom post types via permalink, i.e. `/(post-type-name)/feed/`
* Better validation in code - no more warnings when debug is turned on
* Supports `type-(slug).php` in addition to `type-(name).php` template files (confused many users)
* `is_custom_post_type_archive` function can now be passed a name of a post type to check against

= 0.8.5 =
* Enhancement: full support for child themes (proper calling of new template files via `locate_template` function)
* Bug fix: looser conditional matching of custom post type properties (should fix many "just doesn't work" cases)
* Bug fix: proper filtering / handling of wp_title override (respects separator, separator location)