=== Tumblr Widget ===
Contributors: gabrielroth
Tags: Tumblr, widget
Requires at least: 2.8
Tested up to: 3.1
Stable tag: trunk

Allows you to import a Tumblr into any widgetized area of a WordPress blog.

== Description ==

Tumblr Widget allows you to display the contents of a Tumblr in any widget-enabled area of your WordPress blog. You can import all Tumblr posts or specify certain categories (photo, link, quotation, etc.) to display.

Requires cURL and SimpleXML. (Most PHP installations have these features enabled by default.)

If you find this plugin useful, I'd love to check out your site. Send me an email and let me know where you're using it! I'm also available for custom WordPress development projects.
gabe.roth@gmail.com



**Controls**

+ *Title:* The title you want to appear above the Tumblr on your WordPress page. Leave blank if you like.

+ *Your Tumblr:* The URL of the Tumblr you want to import. It doesn't have to contain 'tumblr.com'. Leave off the 'http://' at the beginning.

+ *Maximum number of posts to display:* This number is a *maximum,* as the text suggests.

+ *Link title to Tumblr:* Turns the widget title into a link to your Tumblr's URL. If you don't enter a title in the title field, you won't get a link.

+ *Link to each post on Tumblr* When checked, this displays the date of the Tumblr post, linking the date to the original post on the Tumblr site.

+ *Add inline CSS padding* Adds a CSS style rule adding 8 pixels of padding above and below each Tumblr post. Disable to prevent it messing up your own CSS.

+ *Set video width:* Resizes videos to help them fit in your theme. Enter a value in pixels. 50px is the minimum. Height will be adjusted automatically in proportion to the width you choose.

+ *Show:* Include or exclude different post types in the feed.

+ *Photo size:* Tumblr provides each photo in five different sizes. Whichever size you choose to display, the image links to the 500 pixel version.

== Installation ==

1. Upload `tumblr-widget.php` to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Widgets' page in the 'Appearance' menu in WordPress.

== Frequently Asked Questions ==

= Can I display photos at a different size other than the five in the dropdown menu? =

Not at the moment. Tumblr provides the photos at those sizes; the widget just pulls them down from Tumblr and displays them.

More powerful photo resizing is planned for version 2.0. I can't say how soon that'll happen, though.

= How can I change the way the Tumblr posts display? =

Use CSS. You can add targeted style rules to your `style.css` file, in your theme folder. Each post is a <code><li></code> with the class "tumblr_post." Each post also has the class of its post type: "quote", "link", "conversation", "regular", "photo", "audio", or "video".

= Can I import and display someone else's Tumblr? =

Yes, you can. But you shouldn't, unless you have their permission.

= I have another question that's not covered here. =

Email me: gabe.roth at gmail.


== Changelog ==

= 1.4.6 =
* Fixed bug with video resizing code.

= 1.4.5 =
* Fixed bug that caused a dot to appear in link posts where it didn't belong.

= 1.4.4 =
* Minor bug fixes.

= 1.4.3 =
* Better error checking.
* Various minor fixes.

= 1.4.2 =
* Now flushing cache when changing Tumblr URL.
* Added 'Hide error messages' option

= 1.4.1 =
* Fixed reappearing bug that prevented some themes from finishing loading on Tumblr failure.

= 1.4 =
* Added caching, which should help when Tumblr's servers are being flaky.
* We now use WP_Http instead of cURL, as recommended.

= 1.3 =
* Added two features: 'Resize videos' and 'link title to Tumblr'. 

= 1.2 =
* Fixed bug that was preventing settings from being preserved with WP 2.9.

= 1.1 =
* Added 'link to Tumblr' feature

= 1.0 =
* First release.