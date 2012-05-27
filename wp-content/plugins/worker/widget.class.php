<?php
/**
 * ManageWP_Widget Class
 */
class ManageWP_Widget extends WP_Widget {
 
 
    /** constructor -- name this the same as the class above */
    function ManageWP_Widget() {
        parent::WP_Widget(false, $name = 'ManageWP', array('description' => 'ManageWP widget.'));	
    }
 
    /** @see WP_Widget::widget -- do not rename this */
    function widget($args, $instance) {	
        extract( $args );
        $instance['title'] = 'ManageWP';
        $instance['message'] = 'We are happily using <a href="http://managewp.com" target="_blank">ManageWP</a>';
        $title 		= apply_filters('widget_title', $instance['title']);
        $message 	= $instance['message'];
        ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
							<ul>
								<li><?php echo $message; ?></li>
							</ul>
              <?php echo $after_widget; ?>
        <?php
    }
    
    /** @see WP_Widget::form -- do not rename this */
    function form($instance) {	
        $title 		= 'ManageWP';
        $message	= 'We are happily using <a href="http://managewp.com" target="_blank">ManageWP</a>';
        echo '<p>'.$message.'</p>';
    }
 
 
} // end class example_widget

$mwp_worker_brand = get_option("mwp_worker_brand");
$worker_brand = 0;    	
if(is_array($mwp_worker_brand)){
    		if($mwp_worker_brand['name'] || $mwp_worker_brand['desc'] || $mwp_worker_brand['author'] || $mwp_worker_brand['author_url']){
    			$worker_brand= 1;
    		} 
}
if(!$worker_brand){
	add_action('widgets_init', create_function('', 'return register_widget("ManageWP_Widget");'));
}
				
?>