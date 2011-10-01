<?php
/**
 * Plugin Name: Tumblr Widget
 * Plugin URI: http://gabrielroth.com/tumblr-widget-for-wordpress/
 * Description: Displays a Tumblr on a WordPress page.
 * Version: 1.4.6
 * Author: Gabriel Roth
 * Author URI: http://gabrielroth.com
 */
/*  Copyright 2009  GABRIEL ROTH  (email : gabe.roth@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

add_action( 'widgets_init', 'load_tumblr_widget' );

function load_tumblr_widget() {
	register_widget( 'Tumblr_Widget' );
}

class Tumblr_Widget extends WP_Widget {

function Tumblr_Widget() {
		$widget_ops = array( 'classname' => 'Tumblr', 'description' => 'Displays a Tumblr on a WordPress page.' );
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'tumblr-widget' );
		$this->WP_Widget( 'tumblr-widget', 'Tumblr Widget', $widget_ops, $control_ops );
	}

function widget( $args, $instance ) {

	if (!function_exists('simplexml_load_string')) {
		if (!$hide_errors) {
			echo "SimpleXML is not enabled on this website. Tumblr Widget requires SimpleXML to function.";
			exit;
		}	
	}

	function link_to_tumblr($post_url, $time) {
		echo '<p><a href="'.$post_url.'" class="tumblr_link">'.date('m/d/y', intval($time)).'</a></p>';
	}

	$tumblrcache = get_option('tumblrcache');

	/* Set up variables and arguments */	
	extract( $args );
	$title = apply_filters('widget_title', $instance['title'] );
	$tumblr = $instance['tumblr'];
	$tumblr = rtrim($tumblr, "/ \t\n\r");
	$photo_size = $instance['photo_size'];
	$show_regular = $instance['show_regular'];
	$show_photo = $instance['show_photo'];
	$show_quote = $instance['show_quote'];
	$show_link = $instance['show_link'];
	$show_conversation = $instance['show_conversation'];
	$show_audio = $instance['show_audio'];
	$show_video = $instance['show_video'];
	$inline_styles = $instance['inline_styles'];
	$show_time = $instance['show_time'];
	$number = $instance['number'];
	$video_width = $instance['video_width'];
	$link_title = $instance['link_title'];
	$hide_errors = $instance['hide_errors'];

	$types = array (
		"regular" => $show_regular,
		"photo" => $show_photo,
		"quote" => $show_quote,
		"link" => $show_link,
		"conversation" => $show_conversation,
		"audio" => $show_audio,
		"video" => $show_video,
		);
	
	/* If the cache was last updated more than one minute ago ... */
	if ( $tumblrcache['lastcheck'] <  ( mktime() - 60 ) ) {	
		$count = 0;
		foreach( $types as $type ) {
			if ($type)
				$count++;
			}
		/* clean up Tumblr URL */
		if ( strpos($tumblr, "http://") === 0 )
			$tumblr = substr($tumblr, 7);
		$tumblr = rtrim($tumblr, "/");
	
		/* if there's only one category, get the next $number posts in that category */
		if ( $count == 1 ) {
			foreach ( $types as $type => $value ) {
				if ( $value )
					$the_type = $type;
				}
			$request_url = "http://".$tumblr."/api/read?num=".$number."&type=".$the_type;
			}
	
		/* if all seven categories are checked, get the next $number posts in all categories */
		elseif ( $count == 7 ) {
			$request_url = "http://".$tumblr."/api/read?num=".$number;
			}
	
		/* if there are 2-6 categories, get the next 50 posts and we'll keep count of how many are displayed. */
		else {
			$request_url = "http://".$tumblr."/api/read?num=50";
			}
		
		/* make request using WP_HTTP */
		$request = new WP_Http;
		$result = $request->request( $request_url );
		
		if ( is_wp_error($result) ) {
			echo "Error: " . $result->get_error_message();
			return;
		}
		
		
		if ( strpos($result['body'], "<!DOCT") !== 0 ) {		
			$tumblrcache['xml'] = $result['body'];
			$tumblrcache['lastcheck'] = mktime();
			update_option('tumblrcache', $tumblrcache);
		}
	} // end if

	/* Using the cached version, whether or not it was just updated. */
	$xml_string = $tumblrcache['xml'];
	try {	
		$xml = simplexml_load_string($xml_string);
	} catch (Exception $e) {
		//Ignore the error and insure $xml is null
		$xml == null;	
	}
	
	if ( !empty($xml) ) {
		/* Preliminary HTML */
		echo $before_widget;
		if ( $title ) {
			echo $before_title;
			if ( $link_title ) {
				echo "<a href='http://" . $tumblr . "'>" . $title . "</a>";
			} else {
				echo $title;
			}
			echo $after_title;
		}
		echo '<ul>';
		$post_count = 0;

		/* Starting to loop through the posts */
		foreach ( $xml->posts->post as $post ) {

			if ( $post_count < $number ) {
				/* Get post type and other info from XML attributes and store in variables */
				foreach ($post->attributes() as $key => $value) {
					if ( $key == "type" )
						$type = $value;
					if ( $key == "unix-timestamp" )
						$time = $value;
					if ( $key == "url" )
						$post_url = $value;
				}

/* Now we set up methods for displaying each type of post ... */

// REGULAR POSTS
					if ( $type == "regular" && $show_regular ) {
						echo '<li class="tumblr_post '.$type.'" ';
						if ( $inline_styles ) {
							echo 'style="padding:8px 0"';
						}
						echo '>';
						$post_title = $post->{'regular-title'};
						$body = $post->{'regular-body'};
						echo '<h3>'.$post_title.'</h3><p>'.$body.'</p>';
						if ($show_time) {
							link_to_tumblr($post_url, $time);
						}
						echo '</li>';
						$post_count++;
					}

// PHOTO POSTS
					if ( $type == "photo" && $show_photo ) {
						echo '<li class="tumblr_post '.$type.'" ';
						if ($inline_styles) {
							echo 'style="padding:8px 0"';
						}
						echo '>';
						$caption = $post->{'photo-caption'};
						foreach ($post->{'photo-url'} as $this_url) {
							foreach ($this_url->attributes() as $key => $value) {
								if ($value == $photo_size) {
									$url = $this_url;
									}
								if ($value == 500) {
									$link_url = $this_url;
									}
								}
							}
						echo '<a href="'.$link_url.'"><img src="'.$url.'" alt="photo from Tumblr" /></a><br />'.$caption.'<br />';
						if ($show_time) {
							link_to_tumblr($post_url, $time);
						}
						echo '</li>';
						$post_count++;
					}

// QUOTE POSTS
					if ($type == "quote" && $show_quote) {
						echo '<li class="tumblr_post '.$type.'" ';
						if ($inline_styles) {
							echo 'style="padding:8px 0"';
						}
						echo '>';
						$text = $post->{'quote-text'};
						$source = $post->{'quote-source'};
						echo '<p><blockquote>'.$text.'</blockquote>'.$source.'</p>';
						if ($show_time) {
							link_to_tumblr($post_url, $time);
						}
						echo '</li>';
						$post_count++;
					}

// LINK POSTS
					if ($type == "link" && $show_link) {
						echo '<li class="tumblr_post '.$type.'" ';
						if ($inline_styles) {
							echo 'style="padding:8px 0"';
						}
						echo '>';
						$text = $post->{'link-text'};
						$url = $post->{'link-url'};
						$description = $post->{'link-description'};
						echo '<p><a href="'.$url.'">'.$text.'</a> '.$description.'</p>';
						if ($show_time) {
							link_to_tumblr($post_url, $time);
						}
						echo '</li>';
						$post_count++;
					}

// CONVERSATION POSTS
					if ($type == "conversation" && $show_conversation) {
						echo '<li class="tumblr_post '.$type.'" ';
						if ($inline_styles) {
							echo 'style="padding:8px 0"';
						}
						echo '>';
						$title = $post->{'conversation-title'};
						if ($title) {
							echo '<h3>'.$title.'</h3>';
							}
						foreach ($post->conversation->line as $line) {
							foreach ($line->attributes() as $key => $value) {
								if ($key == "label") {
									$name = $value;
									}
								}
								echo '<strong>'.$name.'</strong> '.$line.'<br />';
							}
						if ($show_time) {
							link_to_tumblr($post_url, $time);
						}
						echo '</li>';
						$post_count++;
					}

// VIDEO POSTS
					if ($type == "video" && $show_video) {
					
						echo '<li class="tumblr_post '.$type.'" ';
						if ($inline_styles) {
							echo 'style="padding:8px 0"';
						}
						echo '>';
						$caption = $post->{'video-caption'};
						$player = $post->{'video-player'};
						
						if ($video_width) {
							if ( $video_width < 50 ) $video_width = 50;
							$pattern = '/width="(\d+)" height="(\d+)"/';						
							preg_match($pattern, $player, $matches);
							if ($matches) {
								$old_width = $matches[1];
								$old_height = $matches[2];
							} else {
							$pattern = '/height="(\d+)" width="(\d+)"/';						
								preg_match($pattern, $player, $matches);
								$old_height = $matches[1];
								$old_width = $matches[2];
							}
							
							$new_height = $old_height * ($video_width / $old_width );						
							$replacement = 'width="' . $video_width . '" height="' . $new_height . '"';
							$player = preg_replace($pattern, $replacement, $player);
						}
						$source = $post->{'video-source'};
						echo $player."<br />".$caption."<br />";
						if ($show_time) {
							link_to_tumblr($post_url, $time);
						}
						echo '</li>';
						$post_count++;
					}

// AUDIO POSTS
					if ($type == "audio" && $show_video) {
						echo '<li class="tumblr_post '.$type.'" ';
						if ($inline_styles) {
							echo 'style="padding:8px 0"';
						}
						echo '>';
						$caption = $post->{'audio-caption'};
						$player = $post->{'audio-player'};
						echo $player."<br />".$caption."<br />";
						if ($show_time) {
							link_to_tumblr($post_url, $time);
						}
						echo '</li>';
						$post_count++;
					}
				} // end of loop
			} // $post_count == number;
		// end of widget
		echo '</ul>'.$after_widget;
		} else {
			if (!$hide_errors) {
				echo '<span class="error">Sorry, we\'re having trouble loading this Tumblr.</span>';
			}
		}
	}




// saves widget settings
function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		
		if ($new_instance['tumblr'] != $instance['tumblr']) {
			delete_option('tumblrcache');
		}

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['tumblr'] = strip_tags( $new_instance['tumblr'] );
		$instance['photo_size'] = $new_instance['photo_size'];
		$instance['show_regular'] = $new_instance['show_regular'];
		$instance['show_photo'] = $new_instance['show_photo'];
		$instance['show_quote'] = $new_instance['show_quote'];
		$instance['show_link'] = $new_instance['show_link'];
		$instance['show_conversation'] = $new_instance['show_conversation'];
		$instance['show_audio'] = $new_instance['show_audio'];
		$instance['show_video'] = $new_instance['show_video'];
		$instance['inline_styles'] = $new_instance['inline_styles'];
		$instance['show_time'] = $new_instance['show_time'];
		$instance['number'] = $new_instance['number'];
		$instance['video_width'] = $new_instance['video_width'];
		$instance['link_title'] = $new_instance['link_title'];
		$instance['hide_errors'] = $new_instance['hide_errors'];

		return $instance;
	}

// creates controls form
function form( $instance ) {

// defaults
		$defaults = array( 'title'=>'My Tumblr', 'tumblr'=>'demo.tumblr.com', 'show_regular' => true, 'show_photo' => true, 'show_quote' => true, 'show_link' => true, 'show_conversation' => true, 'show_audio'=>true, 'show_video'=>true, 'inline_styles'=>false, 'show_time'=>false, 'number'=>10, 'video_width'=>false, 'link_title'=>false, 'hide_errors'=>false );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

<?php // form html ?>
			<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'tumblr' ); ?>">Your Tumblr:</label>
			<input id="<?php echo $this->get_field_id( 'tumblr' ); ?>" name="<?php echo $this->get_field_name( 'tumblr' ); ?>" value="<?php echo $instance['tumblr']; ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>">Maximum number of posts to display:</label>

			<select id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $instance['number']; ?>">

			<option value="1" <?php if ($instance['number']==1) echo 'selected="selected"'; ?>>1</option>

			<option value="2" <?php if ($instance['number']==2) echo 'selected="selected"'; ?>>2</option>

			<option value="3" <?php if ($instance['number']==3) echo 'selected="selected"'; ?>>3</option>

			<option value="5" <?php if ($instance['number']==5) echo 'selected="selected"'; ?>>5</option>

			<option value="10" <?php if ($instance['number']==10) echo 'selected="selected"'; ?>>10</option>

			<option value="15" <?php if ($instance['number']==15) echo 'selected="selected"'; ?>>15</option>

			<option value="20" <?php if ($instance['number']==20) echo 'selected="selected"'; ?>>20</option>

			<option value="25" <?php if ($instance['number']==25) echo 'selected="selected"'; ?>>25</option>
			</select>
		</p>

		<p>
			<input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id( 'link_title' ); ?>" name="<?php echo $this->get_field_name( 'link_title' ); ?>" <?php if ($instance['link_title']) echo 'checked'; ?> />
			<label for="<?php echo $this->get_field_id( 'link_title' ); ?>">Link title to Tumblr</label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id( 'show_time' ); ?>" name="<?php echo $this->get_field_name( 'show_time' ); ?>" <?php if ($instance['show_time']) echo 'checked'; ?> />
			<label for="<?php echo $this->get_field_id( 'show_time' ); ?>">Link to each post on Tumblr</label>
		</p>		
		
		<p>
			<input class="checkbox" type="checkbox" <?php if ($instance['inline_styles']) echo 'checked'; ?> id="<?php echo $this->get_field_id( 'inline_styles' ); ?>" name="<?php echo $this->get_field_name( 'inline_styles' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'inline_styles' ); ?>">Add inline CSS padding</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'video_width' ); ?>">Set video width:</label>
			<input id="<?php echo $this->get_field_id( 'video_width' ); ?>" name="<?php echo $this->get_field_name( 'video_width' ); ?>" value="<?php echo $instance['video_width']; ?>" maxlength='4' style="width:30px" /> px
			<br />
			<em>Leave blank to show videos at original size.</em>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php if ($instance['hide_errors']) echo 'checked'; ?> id="<?php echo $this->get_field_id( 'hide_errors' ); ?>" name="<?php echo $this->get_field_name( 'hide_errors' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'hide_errors' ); ?>">Hide error messages</label>
			<br />
			<em>If checked, the widget fails silently when it can&rsquo;t load Tumblr content.</em>
		</p>


<hr />

		<p><strong>Show:</strong></p>

		<p>
			<input class="checkbox" type="checkbox" <?php if ($instance['show_regular']) echo 'checked'; ?> id="<?php echo $this->get_field_id( 'show_regular' ); ?>" name="<?php echo $this->get_field_name( 'show_regular' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_regular' ); ?>">Regular posts</label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php if ($instance['show_photo']) echo 'checked'; ?> id="<?php echo $this->get_field_id( 'show_photo' ); ?>" name="<?php echo $this->get_field_name( 'show_photo' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_photo' ); ?>">Photo posts</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'photo_size' ); ?>">Photo size:</label>
			<select id="<?php echo $this->get_field_id( 'photo_size' ); ?>" name="<?php echo $this->get_field_name( 'photo_size' ); ?>" value="<?php echo $instance['photo_size']; ?>"><option value="75" <?php if ($instance['photo_size']==75) echo 'selected="selected"'; ?>>75px</option><option value="100" <?php if ($instance['photo_size']==100) echo 'selected="selected"'; ?>>100px</option><option value="250" <?php if ($instance['photo_size']==250) echo 'selected="selected"'; ?>>250px</option><option value="400" <?php if ($instance['photo_size']==400) echo 'selected="selected"'; ?>>400px</option><option value="500" <?php if ($instance['photo_size']==500) echo 'selected="selected"'; ?>>500px</option></select>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php if ($instance['show_quote']) echo 'checked'; ?> id="<?php echo $this->get_field_id( 'show_quote' ); ?>" name="<?php echo $this->get_field_name( 'show_quote' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_quote' ); ?>">Quotation posts</label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php if ($instance['show_link']) echo 'checked'; ?> id="<?php echo $this->get_field_id( 'show_link' ); ?>" name="<?php echo $this->get_field_name( 'show_link' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_link' ); ?>">Link posts</label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php if ($instance['show_conversation']) echo 'checked'; ?> id="<?php echo $this->get_field_id( 'show_conversation' ); ?>" name="<?php echo $this->get_field_name( 'show_conversation' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_conversation' ); ?>">Conversation posts</label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php if ($instance['show_audio']) echo 'checked'; ?> id="<?php echo $this->get_field_id( 'show_audio' ); ?>" name="<?php echo $this->get_field_name( 'show_audio' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_audio' ); ?>">Audio posts</label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php if ($instance['show_video']) echo 'checked'; ?> id="<?php echo $this->get_field_id( 'show_video' ); ?>" name="<?php echo $this->get_field_name( 'show_video' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_video' ); ?>">Video posts</label>
		</p>

			<?php
	}
}
?>