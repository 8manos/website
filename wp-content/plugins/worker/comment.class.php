<?php
/*************************************************************
 * 
 * post.class.php
 * 
 * Create remote post
 * 
 * 
 * Copyright (c) 2011 Prelovac Media
 * www.prelovac.com
 **************************************************************/

class MMB_Comment extends MMB_Core
{
    function __construct()
    {
        parent::__construct();
    }
    
    function change_status($args)
    {

    	global $wpdb;
    	$comment_id = $args['comment_id'];
    	$status = $args['status']; 
    	
       	if ( 'approve' == $status )
			$status_sql = '1';
		elseif ( 'unapprove' == $status )
			$status_sql = '0';
		elseif ( 'spam' == $status )
			$status_sql =  'spam';
		elseif ( 'trash' == $status )
			$status_sql =  'trash';
		$sql = "update ".$wpdb->prefix."comments set comment_approved = '$status_sql' where comment_ID = '$comment_id'";
		$success = $wpdb->query($sql);
		
				
        return $success;
    }
    
}
?>