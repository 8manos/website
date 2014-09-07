=== Theme Switcher ===
Contributors: ryan, filosofo
Tags: themes
Tested up to: 2.9
Stable tag: 1.0
Requires at least: 2.8

Allow users to switch which theme they use on your WordPress, WordPress MU, or BuddyPress site.

== Description ==

Allow users to switch which theme they use on your WordPress, WordPress MU, or BuddyPress site.  Includes a widget for easily putting the theme-switcher as a list or dropdown in your sidebar.

== Installation ==

1. Download and extract the `theme-switcher` plugin file.
1. Upload the `theme-switcher` directory to the `/wp-content/plugins/` directory.
1. Activate the plugin under the 'Plugins' menu in the WordPress admin.
1. Add the "Theme Switcher" widget to one of your widgetized sidebars, or call `wp_theme_switcher()` directly.

== Frequently Asked Questions ==

= How do I print the Theme Switcher markup without using widgets? =

You can call `wp_theme_switcher()` directly.  Calling `wp_theme_switcher()` alone (no arguments) will print a list of themes; calling `wp_theme_switcher('dropdown')` will print a dropdown of themes.

== Screenshots ==
1. The Theme Switcher widget allows you to set the title of the widget and to choose the "list" or "dropdown" option.
2. The Theme Switcher widget in action on the sidebar.
