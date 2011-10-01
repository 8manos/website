<?php
/*
Plugin Name: Lavalamp menu
Plugin URI: http://www.3dolab.net/en/256/wordpress-lavalamp-menu
Description: Add the lavalamp menu to your blog by filtering the default page list. 
Version: 0.1
Author: 3dolab
Author URI: http://www.3dolab.net

Copyright 2010 3dolab

This work is largely based on the LavaLamp for jQuery menu
(http://www.gmarwaha.com/blog/2007/08/23/lavalamp-for-jquery-lovers/)
*/
####### Lavalamp install section
$version = '0.1';
register_activation_hook(__FILE__,'lavalampmenu_install');
function lavalampmenu_install () {
	get_option('internals')==' ' ? update_option( 'internals', '0' ) : $internals;
	get_option('lava_pages')==' ' ? update_option( 'lava_pages', '0' ) : $lava_pages;
	get_option('lava_categories')==' ' ? update_option( 'lava_categories', '0' ) : $lava_categories;
	get_option('lava_bookmarks')==' ' ? update_option( 'lava_bookmarks', '0' ) : $lava_bookmarks;
	get_option('external_css')==' ' ? update_option( 'external_css', '1' ) : $external_css;
	get_option('list_background')==' ' ? update_option( 'list_background', WP_PLUGIN_URL . '/lavalamp-menu/bg.gif' ) : $list_background;
	get_option('item_background')==' ' ? update_option( 'item_background', WP_PLUGIN_URL . '/lavalamp-menu/lava.gif' ) : $item_background;
	get_option('menu_height')==' ' ? update_option( 'menu_height', '30px' ) : $menu_height;
	get_option('menu_margin')==' ' ? update_option( 'menu_margin', '10px 0' ) : $menu_margin;
	get_option('menu_padding')==' ' ? update_option( 'menu_padding', '15px' ) : $menu_padding;
	get_option('item_color')==' ' ? update_option( 'item_color', '#fff' ) : $item_color;
	get_option('hover_color')==' ' ? update_option( 'item_color', '#f00' ) : $hover_color;
	get_option('background_color')==' ' ? update_option( 'background_color', '#000' ) : $background_color;
}
####### Lavalamp admin section

function add_LavaOptionsPage() 
{
  if (function_exists('add_options_page')) {
	add_options_page('Lavalamp menu', 'Lavalamp menu', 8, 'lavalamp-menu/lavalamp-menu.php', 'lavalamp_optionspage');
  }
}

function lavalamp_optionspage() {
	global $_POST;
	if( $_POST['lava_update'] == 1 ) 
  {
			update_option( 'internals', $_POST['internals'] );
			update_option( 'lava_pages', $_POST['lava_pages'] );
			update_option( 'lava_categories', $_POST['lava_categories'] );
			update_option( 'lava_bookmarks', $_POST['lava_bookmarks'] );
			update_option( 'external_css', $_POST['external_css'] );
			update_option( 'list_background', $_POST['list_background'] );
			update_option( 'item_background', $_POST['item_background'] );
			update_option( 'menu_height', $_POST['menu_height'] );
			update_option( 'menu_margin', $_POST['menu_margin'] );
			update_option( 'menu_padding', $_POST['menu_padding'] );
			update_option( 'item_color', $_POST['item_color'] );
			update_option( 'hover_color', $_POST['hover_color'] );
			update_option( 'background_color', $_POST['background_color'] );
			$content.='<div class="updated"><p><strong>Options saved.</strong></p></div>';
	}
		$internals = get_option('internals');
		$lava_categories = get_option('lava_categories');
		$lava_pages = get_option('lava_pages');
		$lava_bookmarks = get_option('lava_bookmarks');
		$external_css = get_option('external_css');
		$list_background = get_option('list_background');
		$item_background = get_option('item_background');
		$menu_height = get_option('menu_height');
		$menu_margin = get_option('menu_margin');
		$menu_padding = get_option('menu_padding');
		$item_color = get_option('item_color');
		$item_color = get_option('item_color');
		$hover_color = get_option('hover_color');
		$background_color = get_option('background_color');
		$content.='
			<h2>LavaLamp Menu Plugin Options</h2>			
			<p>you could always use the PHP function lavalamp_list([pages, categories, bookmarks]) function in your template</p>
			<form name="lavalampsettings" method="post" action="'. str_replace( '%7E', '~', $_SERVER['REQUEST_URI']).'">
				<input type="hidden" name="lava_update" value="1">
				<div>
						<label style="width: 275px; display: block; float:left; margin-right:10px;" for="internals">Use internal jQuery easing and interface shipped with Wordpress</label>
						';
						$internals==1 ? $content.='<input style="float: left;" type="checkbox" name="internals" id="internals" value="1" checked>' : $content.='<input style="float: left;" type="checkbox" name="internals" id="internals" value="1">';
						$content.='
						<div style="clear: both"></div>
				</div>
				<p>please make always sure that the listing functions argument "title_li=" is set to empty in your template
				<div>
						<label style="width: 275px; display: block; float:left; margin-right:10px;" for="lava_pages">Apply LavaLamp menu to list of pages</label>
						';
						$lava_pages==1 ? $content.='<input style="float: left;" type="checkbox" name="lava_pages" id="lava_pages" value="1" checked>' : $content.='<input style="float: left;" type="checkbox" name="lava_pages" id="lava_pages" value="1">';
						$content.='
						<div style="clear: both"></div>
				</div>
				<div>
						<label style="width: 275px; display: block; float:left; margin-right:10px;" for="lava_categories">Apply LavaLamp menu to list of categories</label>
						';
						$lava_categories==1 ? $content.='<input style="float: left;" type="checkbox" name="lava_categories" id="lava_categories" value="1" checked>' : $content.='<input style="float: left;" type="checkbox" name="lava_categories" id="lava_categories" value="1">';
						$content.='
						<div style="clear: both"></div>
				</div>
				<div>
						<label style="width: 275px; display: block; float:left; margin-right:10px;" for="lava_bookmarks">Apply LavaLamp menu to list of bookmarks</label>
						';
						$lava_bookmarks==1 ? $content.='<input style="float: left;" type="checkbox" name="lava_bookmarks" id="lava_bookmarks" value="1" checked>' : $content.='<input style="float: left;" type="checkbox" name="lava_bookmarks" id="lava_bookmarks" value="1">';
						$content.='
						<div style="clear: both"></div>
				</div>
				<h3>Customization</h3>
				<div>
						<label style="width: 275px; display: block; float:left; margin-right:10px;" for="external_css">Use default or customized lavalamp.css rather than embed into the body.</label>
						';
						$external_css==1 ? $content.='<input style="float: left;" type="checkbox" name="external_css" id="external_css" value="1" checked>' : $content.='<input style="float: left;" type="checkbox" name="external_css" id="external_css" value="1">';
						$content.='
						<div style="clear: both"></div>
						* if active, it will override the following settings:
				</div>
				<div>
						<label style="width: 275px; display: block; float:left; margin-right:10px;" for="list_background">Set path of a custom menu background image</label>
						<input name="list_background" type="text" id="list_background" value="' .$list_background . '" size="50" /> 
						could be <code>'. trailingslashit(get_option('siteurl')) .'wp-content/upload/</code> ?
				<div style="clear: both"></div>
				<div>
						<label style="width: 275px; display: block; float:left; margin-right:10px;" for="item_background">Set path of a custom item background image</label>
						<input name="item_background" type="text" id="item_background" value="' . $item_background . '" size="50" /> 
						could be <code>'. trailingslashit(get_option('siteurl')) .'wp-content/upload/</code> ?
				<div style="clear: both"></div>
				</div>
				<div>
						<label for="item_color" style="width: 275px; display: block; float:left; margin-right:10px;">Menu item color</label>
						<input style="float: left;"type="text" size="8" name="item_color" id="item_color" value="' . $item_color . '">
						<div style="clear: both"></div>
				</div>
				<div>
						<label for="hover_color" style="width: 275px; display: block; float:left; margin-right:10px;">Menu item hover color</label>
						<input style="float: left;"type="text" size="8" name="hover_color" id="hover_color" value="' . $hover_color . '">
						<div style="clear: both"></div>
				</div>
				<div>
						<label for="background_color" style="width: 275px; display: block; float:left; margin-right:10px;">Menu background color</label>
						<input style="float: left;"type="text" size="8" name="background_color" id="background_color" value="' . $background_color . '">
						<div style="clear: both"></div>
				</div>
				<div>
						<label for="menu_height" style="width: 275px; display: block; float:left; margin-right:10px;">Menu height</label>
						<input style="float: left;"type="text" name="menu_height" id="menu_height" value="' . $menu_height . '">
						<div style="clear: both"></div>
				</div>
				<div>
						<label for="menu_margin" style="width: 275px; display: block; float:left; margin-right:10px;">Menu margin</label>
						<input style="float: left;"type="text" name="menu_margin" id="menu_margin" value="' . $menu_margin . '">
						<div style="clear: both"></div>
				</div>
				<div>
						<label for="menu_padding" style="width: 275px; display: block; float:left; margin-right:10px;">Menu padding</label>
						<input style="float: left;"type="text" name="menu_padding" id="menu_padding" value="' . $menu_padding . '">
						<div style="clear: both"></div>
				</div>
			<hr />
			<p class="submit"><input type="submit" name="Submit" value="Update Options" /></p>
			</form></div>';
	echo $content;
	}

add_action('admin_menu', 'add_LavaOptionsPage');
function lavalamp_load() {
if (!is_admin()) { // avoid the scripts from loading on admin panel
	makeLavalampScripts(get_option('internals'));
	}
}
function lavalamp_styles() {
if (!is_admin()) { // avoid the scripts from loading on admin panel
	echo makeLavalampStyle();
	}
}
function makeLavalampScripts($useinternals) 
{
	wp_enqueue_script('jquery');
	$internals = get_option('internals');
	if($useinternals == 1) 
  	{
		wp_enqueue_script('interface');
		wp_enqueue_script('easing', WP_PLUGIN_URL . '/lavalamp-menu/js/jquery.easing.1.3.pack.js', array('jquery', 'interface'));
		wp_enqueue_script('lavalamp', WP_PLUGIN_URL . '/lavalamp-menu/js/jquery.lavalamp.pack.js', array('jquery', 'interface', 'easing'));
		wp_enqueue_script('mymenu', WP_PLUGIN_URL . '/lavalamp-menu/js/my-menu.js', array('jquery', 'interface', 'easing', 'lavalamp'));
	}
	else 
  	{	wp_register_script('interfacemod', WP_PLUGIN_URL . '/lavalamp-menu/js/intface4.pack.js', false, '1.3');
		wp_enqueue_script('interfacemod');
		wp_enqueue_script('easing', WP_PLUGIN_URL . '/lavalamp-menu/js/jquery.easing.1.3.pack.js', array('jquery', 'interfacemod'));
		wp_enqueue_script('lavalamp', WP_PLUGIN_URL . '/lavalamp-menu/js/jquery.lavalamp.min.js', array('jquery', 'interfacemod', 'easing'));
		wp_enqueue_script('mymenu', WP_PLUGIN_URL . '/lavalamp-menu/js/my-menu.js', array('jquery', 'interfacemod', 'easing', 'lavalamp'));
	}	
}
function makeLavalampStyle() 
{
		$external_css = get_option('external_css');
		$list_background = get_option('list_background');
		$item_background = get_option('item_background');
		$menu_height = get_option('menu_height');
		$menu_margin = get_option('menu_margin');
		$menu_padding = get_option('menu_padding');
		$item_color = get_option('item_color');
		$hover_color = get_option('hover_color');
		$background_color = get_option('background_color');
			$lavalamp_path = WP_PLUGIN_URL . '/lavalamp-menu/';
			if($external_css == 1) 
			  { $content.='<link rel="stylesheet" href="' . $lavalamp_path . 'lavalamp.css" type="text/css" media="screen" />
			    ';
			  }
			else { $content.='
<!-- added by the lavalamp menu plugin -->
<style type="text/css" media="screen">
/* Styles LavaLamp menu */
.lava_menu {
	position: relative;
	height: '. $menu_height .';
	padding: '. $menu_padding .';
	margin: '. $menu_margin .';
	overflow:visible;
	background-color:'. $background_color .';
	background: url('. $list_background .') no-repeat top;
  }
.lava_menu ul {
    margin: 0;
    padding: 0;
    list-style: none;
    float: left;
  }
.lava_menu li {
    position: relative;
    z-index: 9;
    margin: 0;
    padding: 0;
    display: block;
    float: left;
  }
.lava_menu a {
    position: relative;
    z-index: 10;
    height: '. $menu_height .';
    display: block;
    float: left;
    line-height: '. $menu_height .';
    text-decoration: none;
    vertical-align: middle;
    padding: '. $menu_padding .';
    color:'. $item_color .';
  }
.lava_menu li a:hover {
  color:'. $hover_color .';
  text-decoration: none;
  display: block;
  }
.lava_menu li ul {
  list-style: none;
  position: absolute;
  width: 150px;
  top: '. $menu_height .';
  background-color:'. $background_color .';
  left: -999em;
}
.lava_menu li:hover ul, .lava_menu li.sfhover ul {
  left: 0px;
}
.lava_menu li li {  
  	margin: 0;
  	padding: 0;
	left: auto;
  }
.lava_menu li li a {
  padding: 0px 0px 0px 15px;
  height: '. $menu_height .';
  line-height: '. $menu_height .';
  color:'. $item_color .';
  border-bottom:1px solid '. $item_color .';
  background-color:'. $background_color .';
  margin: 0;
}
.lava_menu li li a:hover {
  background-color:'. $item_color .';
  color:'. $hover_color .';
}
.lava_menu li.back {
    background: url('. $item_background .') no-repeat right -44px !important;
    width: 13px;
    height: '. $menu_height .';
    z-index: 8;
    position: absolute;
    margin: -1px 0 0 0;
}	
.lava_menu li.back .left {
    background: url('. $item_background .') no-repeat top left !important;
    height: '. $menu_height .';
    margin-right: 8px;
}
.lavalamp li a {
  z-index: 100;
}
/* menu::level2 */
div.lava_menu ul ul li { background: none; }
div.lava_menu ul ul {
    position: absolute;
    top: '. $menu_height .';
    left: -999em;
    padding: 0px;
}
div.lava_menu ul ul a,
div.lava_menu ul ul ul a,
div.lava_menu ul ul ul ul a {
    padding: 0 0 0 15px;
    height: '. $menu_height .';
    float: none;
    display: block;
    line-height: '. $menu_height .';
    color: '. $item_color .';
}
div.lava_menu ul ul li.last { background: none; }
div.lava_menu ul ul li {
    width: 100%;
}
/* menu::level3 */
div.lava_menu ul ul ul {
    padding: 0;
    margin: 0px 0 0 150px !important;
    left: -999em;
	top: -1px;	
}
div.lava_menu ul li:hover ul ul{
    position: absolute;
    left: -999em;
}
div.lava_menu ul ul li:hover ul{
    position: absolute;
    left: 0px;
}
/* colors */
div.lava_menu ul ul ul { background: '. $background_color .';}
div.lava_menu ul ul ul ul { background: '. $background_color .';}
div.lava_menu ul ul ul ul ul { background:'. $background_color .';}
div.lava_menu ul ul li,
div.lava_menu ul ul ul li,
div.lava_menu ul ul ul ul li{
	margin: 0px;
	padding: 0px;
}
div.lava_menu ul ul,
div.lava_menu ul ul ul,
div.lava_menu ul ul ul ul {
	border-left: 1px solid '. $item_color .';
	border-right: 1px solid '. $item_color .';
	border-top: 1px solid '. $item_color .';
}
div.lava_menu ul li.current_page_item a, div.lava_menu ul li.current_page_parent {
	color:'. $item_color .';
}
li.current_page_parent a{
	color:'. $item_color .';
}
</style>
<!-- added by the lavalamp plugin -->
';
			  }
	return $content;
}
function lavalamp_menu($output) {
    // $myarray = array(
    //  'title_li'    => '',
    //  'echo'   => 0
    //  );
    // $args = array_merge($args, $myarray);
    // $r = wp_parse_args($args, $myarray);
    // $output = apply_filters('wp_list_pages', $output, $r);
    $output = '<div class="lava_menu"><ul class="lavaLamp">'.$output;
    $output.= '</ul></div>';
    $filtered = str_replace(array("\r", "\n", "\t"), '', $output);
    return $filtered;
}
function lavalamp_list($type) {
    echo '<div id="lava_menu"><ul class="lavaLamp">';
    if ($type=='pages'){
      $menu = wp_list_pages('title_li=&sort_column=menu_order&echo=0');
    }
    if ($type=='categories'){
      $menu = wp_list_categories('title_li=&&echo=0');
    }
    if ($type=='bookmarks'){
      $menu = wp_list_pages('title_li=&echo=0');
    }
    echo str_replace(array("\r", "\n", "\t"), '', $menu);
    echo '</ul></div>';
}
if(get_option('lava_pages')==1) {
	add_filter('wp_list_pages', 'lavalamp_menu', 2);
}
if(get_option('lava_categories')==1) {
	add_filter('wp_list_categories', 'lavalamp_menu', 2);
}
if(get_option('lava_bookmarks')==1) {
	add_filter('wp_list_bookmarks', 'lavalamp_menu', 2);
}
add_action('wp_print_scripts', 'lavalamp_load');
add_action('wp_head', 'lavalamp_styles');
?>