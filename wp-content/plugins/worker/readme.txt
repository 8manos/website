=== ManageWP Worker ===
Contributors: freediver
Donate link: https://www.networkforgood.org/donation/MakeDonation.aspx?ORGID2=520781390
Tags: admin, administration, amazon, api, authentication, automatic, dashboard, dropbox, events, integration, manage, multsite, notification, performance, s3, security, seo, stats, tracking, managewp
Requires at least: 3.0
Tested up to: 3.3.1
Stable tag: trunk

ManageWP Worker plugin allows you to remotely manage your WordPress sites from one dashboard.

== Description ==

[ManageWP](http://managewp.com/ "Manage Multiple Blogs") is a revolutionary plugin that helps users manage multiple WordPress blogs from one dashboard.

Main features:

*   Secure and fast solution for managing your WordPress sites
*   One click upgrades for WordPress, plugin and themes across all your sites
*   Schedule automatic backups of your websites (Amazon S3 and Dropbox supported)
*   One click to access WP admin of any site
*   Install WordPress, clone or migrate a website to another domain
*   Bulk install themes and plugins to multiple sites at once
*   Add sub-users (writers, staff..) to your account
*   Bulk publish posts to multiple sites at once
*   SEO Statistics, track your keyword rankings
*   Uptime monitoring
*   Much, much more...

Check out the [ManageWP Tour video](http://vimeo.com/22099014).

http://vimeo.com/22099014

Check out [ManageWP.com](http://managewp.com/ "Manage Multiple Blogs").

== Changelog ==  

= 3.9.18 =
* New: Pagelines themes added to the list of partners
* New: Comprehensive website performance scan tool
* New: You can now bulk edit posts/pages (updating that contact info will become piece of cake)
* New: Upload and save your premium plugins/themes in your personal repository for quick installation
* New: Run code snippets now get a repository. Save your snippets and share them with other users
* New: SEO reports can now be sorted. Export as CSV and PDF reports.
* New: Manage Blogroll links
* New: Clean post revisions now has an option to save last x revisions when cleaning
* New: Bulk delete na posts/pages/links
* Fix: Amazon S3 backups failing

= 3.9.17 =
* New: Add your favorite sites to the Favorites  bar (just drag&drop them to the small heart on the top)
* New: Entirely new website menu loaded with features and tools
* New: Manage Posts and Pages across all sites in a more efficient way
* New: Support for all WPMU.org premium plugin updates
* New: Complete Dropbox integration through Oauth which allows us to restore/delete Dropbox backups directly
* New: We have the user guide as PDF now. [Download] (http://managewp.com/files/ManageWP_User_Guide.zip)


= 3.9.16 =
* New: Option to "Run now" backup tasks
* New: Traffic alerts functionality
* New: Support for Genesis premium theme updates
* Fix: In some circutmsances .htaccess was not correctly zipped in the backup archive

= 3.9.15 =
* New: Full range of SEO Statistics now trackable for your websites (Google Page Rank and Page Speed, Backlinks and 20+ more)
* New: Google keyword rank tracking with history
* New: Uptime monitoring (5 min interval with email/SMS notification)
* New: Insights into server PHP error logs right in your dashboard
* New: Remote maintenance mode for your websites
* Fix: A bug when a completed backup was reported as failed

= 3.9.14 =
* Two factor authentication
* Run code tool
* Quick access to security check and broken link tools
* More accurate pageview statistics
* You can now opt to completely hide the Worker plugin from the list of plugins (part of Worker branding features)
* We improved the backups for folks running Windows servers
* Amazon S3 directory name now "ManageWP" by default
* Read more on ManageWP.com http://managewp.com/update-two-factor-authentication-run-code-tool-sucuri-security-check-more-accurate-pageview-statistics

= 3.9.13 =
* Added bucket location for Amazon S3 backups
* Better backup feature for larger sites
* Added Disable compression to further help with larger sites
* Backing up wp-admin, wp-includes and wp-content by default now, other folders can be included manually

= 3.9.12 =
* Minor bug fixes
* Backup, clone, favorites functionality improved

= 3.9.10 =
* Supporting updates for more premium plugins/themes
* Backup notifications (users can now get notices when the backup succeeds or fails)
* Support for WordPress 3.3
* Worker Branding (useful for web agencies, add your own Name/Description)
* Manage Groups screen
* Specify wp-admin path if your site uses a custom one
* Amazon S3 backups support for mixed case bucket names
* Bulk Add Links has additional options
* Better Multisite support
* Option to set the number of items for Google Analytics
* ManageWP backup folder changed to wp-content/managewp/backups

= 3.9.9 =
* New widget on the dashboard - Backup status
* New screen for managing plugins and themes (activate, deactivate, delete, add to favorites, install) across all sites
* New screen for managing users (change role or password, delete user) across all sites 
* Option to overwrite old plugins and themes during bulk installation
* Your website admin now loads faster in ManageWP
* Added API for premium theme and plugin updates

= 3.9.8 =
* Conversion goals integration
* Update notifications
* Enhanced security for your account
* Better backups
* Better update interface
* [Full changelog](http://managewp.com/update-goals-and-adsense-analytics-integration-update-notifications-login-by-ip-better-backups "Full changelog")

= 3.9.7 =
* Fixed problem with cron schedules

= 3.9.6 =
* Improved dashboard performance
* Fixed bug with W3TC, we hope it is fully comptabile now
* Improved backup feature
* Various other fixes and improvements

= 3.9.5 =
* Now supporting scheduled backups to Amazon S3 and Dropbox
* Revamped cloning procedure
* You can now have sites in different colors
* W3 Total Cache comptability improved

= 3.9.3 =
* Included support for WordPress 3.2 partial updates

= 3.9.2 =
* Fixed problem with full backups
* Fixed problem with wordpress dev version upgrades

= 3.9.1 =
* Support for sub-users (limited access users)
* Bulk add user
* 'Select all' feature for bulk posting
* Featured image support for bulk posting
* Reload button on the dashboard (on the top of the Right now widget) will now refresh information about available updates
* Fixed a problem with the import tool
* Fixed a problem when remote dashboard would not work for some servers

= 3.9.0 =
* New feature: Up to 50% faster dashboard loading
* New feature: You can now ignore WordPress/plugin/theme updates         
* New feature: Setting 'Show favicon' for websites in the dashboad
* New feature: Full backups now include WordPress and other folders in the root of the site
* Fixed: Bug with W3 TotalCache object cache causing weird behaviour in the dashboard
* Fixed: All groups now show when adding a site

= 3.8.8 =
* New feature: Bulk add links to blogroll
* New feature: Manual backups to email address
* New feature: Backup requirements check (under ‘Manage Backups’)
* New feature: Popup menu for groups allowing to show dashboard for that group only
* New feature: Favorite list for plugins and themes for later quick installation to multiple blogs
* New feature: Invite friends
* Fixed: problem with backups and write permissions when upload dir was wrongly set
* Fixed: problem adding sites where WordPress is installed in a folder
* Fixed: 408 error message problem when adding site
* Fixed: site time out problems when adding site
* Fixed: problems with some WP plugins (WP Sentinel)
* Fixed: problems with upgrade notifications

= 3.8.7 =
* Fixed 408 error when adding sites
* Added support for IDN domains
* Fixed bug with WordPress updates
* Added comment moderation to the dashboard
* Added quick links for sites (menu appears on hover)


= 3.8.6 =
* Added seach websites feature
* Enhanced dashboard actions (spam comments, post revisions, table overhead)
* Added developer [API] (http://managewp.com/api "ManageWP API")
* Improved Migrate/Clone site feature

= 3.8.4 =
* Fixed remote dashboard problems for sites with redirects
* Fixed IE7 issues in the dashboard

= 3.8.3 =
* Fixed problem with capabilities

= 3.8.2 =
* New interface
* SSL security protocol
* No passwords required
* Improved clone/backup


= 3.6.3 =  
* Initial public release

== Installation ==

1. Upload the plugin folder to your /wp-content/plugins/ folder
2. Go to the Plugins page and activate ManageWP Worker
3. Visit [ManageWP.com](http://managewp.com/ "Manage Multiple Blogs"), sign up and add your site

Alternately

1. Visit [ManageWP.com](http://managewp.com/ "Manage Multiple Blogs"), sign up and add your site
2. ManageWP will warn you the worker plugin is not installed and offer a link for quick installation

== Screenshots ==

1. ManageWP dashboard with available upgrades, site statistics and management functions



== License ==

This file is part of ManageWP Worker.

ManageWP Worker is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

ManageWP Worker is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with ManageWP Worker. If not, see <http://www.gnu.org/licenses/>.


== Frequently Asked Questions ==

= I have problems adding my site =

Make sure you use the latest version of the worker plugin on the site you are trying to add. If you do, sometimes deactivating and activating it again will help. If you still have problems, [contact us](http://managewp.com/contact "ManageWP Contact").

= I have problems installing new plugins or upgrading WordPress through ManageWP =

ManageWP Worker relies on properly set file permissions on your server. See the [user guide](http://managewp.com/user-guide#ftp "ManageWP user guide") for more tips.