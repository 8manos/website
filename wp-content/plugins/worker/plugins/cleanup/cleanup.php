<?php
/*************************************************************
 * 
 * 
 * 
 * ManageWP Worker Plugin
 * 
 * 
 * Copyright (c) 2011 Prelovac Media
 * www.prelovac.com
 **************************************************************/

add_filter('mmb_stats_filter', 'mmb_get_extended_info');


function mmb_get_extended_info($stats)
{
	global $mmb_core;
	$params = get_option('mmb_stats_filter');
	$filter = isset($params['plugins']['cleanup']) ? $params['plugins']['cleanup'] : array();
    $stats['num_revisions']     = mmb_num_revisions($filter['revisions']);
    //$stats['num_revisions'] = 5;
    $stats['overhead']          = mmb_handle_overhead(false);
    $stats['num_spam_comments'] = mmb_num_spam_comments();
    return $stats;
}

/* Revisions */

mmb_add_action('cleanup_delete', 'cleanup_delete_worker');

function cleanup_delete_worker($params = array())
{
    global $mmb_core;
    $revision_params = get_option('mmb_stats_filter');
	$revision_filter = isset($revision_params['plugins']['cleanup']) ? $revision_params['plugins']['cleanup'] : array();
	
    $params_array = explode('_', $params['actions']);
    $return_array = array();
	
    foreach ($params_array as $param) {
        switch ($param) {
            case 'revision':
                if (mmb_delete_all_revisions($revision_filter['revisions'])) {
                    $return_array['revision'] = 'OK';
                } else {
                    $return_array['revision_error'] = 'Failed, please try again';
                }
                break;
            case 'overhead':
                if (mmb_handle_overhead(true)) {
                    $return_array['overhead'] = 'OK';
                } else {
                    $return_array['overhead_error'] = 'Failed, please try again';
                }
                break;
            case 'comment':
                if (mmb_delete_spam_comments()) {
                    $return_array['comment'] = 'OK';
                } else {
                    $return_array['comment_error'] = 'Failed, please try again';
                }
                break;
            default:
                break;
        }
        
    }
    
    unset($params);
    
    mmb_response($return_array, true);
}

function mmb_num_revisions($filter)
{
    global $wpdb;
    $sql           = "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'revision'";
    $num_revisions = $wpdb->get_var($wpdb->prepare($sql));
	if(isset($filter['num_to_keep']) && !empty($filter['num_to_keep'])){
		$num_rev = str_replace("r_","",$filter['num_to_keep']);
		if($num_revisions < $num_rev){
			return 0;
		}
    	return ($num_revisions - $num_rev);
	}else{
		return $num_revisions;
	}
}

function mmb_select_all_revisions()
{
    global $wpdb;
    $sql       = "SELECT * FROM $wpdb->posts WHERE post_type = 'revision'";
    $revisions = $wpdb->get_results($wpdb->prepare($sql));
    return $revisions;
}

function mmb_delete_all_revisions($filter)
{
    global $wpdb, $mmb_core;
	$where = '';
	if(isset($filter['num_to_keep']) && !empty($filter['num_to_keep'])){
		$num_rev = str_replace("r_","",$filter['num_to_keep']);
		$select_posts = "SELECT ID FROM $wpdb->posts WHERE post_type = 'revision' ORDER BY post_date DESC LIMIT ".$num_rev;
		$select_posts_res = $wpdb->get_results($select_posts);
		$notin = '';
		$n = 0;
		foreach($select_posts_res as $keep_post){
			$notin.=$keep_post->ID;
			$n++;
			if(count($select_posts_res)>$n){
				$notin.=',';
			}
		}
		$where = " AND a.ID NOT IN (".$notin.")";
	}
	
    $sql       = "DELETE a,b,c FROM $wpdb->posts a LEFT JOIN $wpdb->term_relationships b ON (a.ID = b.object_id) LEFT JOIN $wpdb->postmeta c ON (a.ID = c.post_id) WHERE a.post_type = 'revision'".$where;
    
	$revisions = $wpdb->query($wpdb->prepare($sql));
    
    return $revisions;
}





/* Optimize */

function mmb_handle_overhead($clear = false)
{
    global $wpdb, $mmb_core;
    $tot_data   = 0;
    $tot_idx    = 0;
    $tot_all    = 0;
    $query      = 'SHOW TABLE STATUS FROM ' . DB_NAME;
    $tables     = $wpdb->get_results($wpdb->prepare($query), ARRAY_A);
    $total_gain = 0;
	$table_string = '';
    foreach ($tables as $table) {
        if (in_array($table['Engine'], array(
            'MyISAM',
            'ISAM',
            'HEAP',
            'MEMORY',
            'ARCHIVE'
        ))) {
            if ($wpdb->base_prefix != $wpdb->prefix) {
                if (preg_match('/^' . $wpdb->prefix . '*/Ui', $table['Name'])) {
                    if ($table['Data_free'] > 0) {
                        $total_gain += $table['Data_free'] / 1024;
                        $table_string .= $table['Name'] . ",";
                    }
                }
            } else if (preg_match('/^' . $wpdb->prefix . '[0-9]{1,20}_*/Ui', $table['Name'])) {
                continue;
            } else {
                if ($table['Data_free'] > 0) {
                    $total_gain += $table['Data_free'] / 1024;
                    $table_string .= $table['Name'] . ",";
                }
            }
        } elseif ($table['Engine'] == 'InnoDB') {
            //$total_gain +=  $table['Data_free'] > 100*1024*1024 ? $table['Data_free'] / 1024 : 0;
        }
    }
    
    if ($clear) {
        $table_string = substr($table_string, 0, strlen($table_string) - 1); //remove last ,
        
        $table_string = rtrim($table_string);
        
        $query = "OPTIMIZE TABLE $table_string";
        
        $optimize = $wpdb->query($query);
        
        return $optimize === FALSE ? false : true;
    } else
        return round($total_gain, 3);
}




/* Spam Comments */

function mmb_num_spam_comments()
{
    global $wpdb;
    $sql       = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = 'spam'";
    $num_spams = $wpdb->get_var($wpdb->prepare($sql));
    return $num_spams;
}

function mmb_delete_spam_comments()
{
    global $wpdb;
    $spams = 1;
    $total = 0;
    while ($spams) {
        $sql   = "DELETE FROM $wpdb->comments WHERE comment_approved = 'spam' LIMIT 200";
        $spams = $wpdb->query($wpdb->prepare($sql));
        $total += $spams;
        if ($spams)
            usleep(100000);
    }
    return $total;
}


function mmb_get_spam_comments()
{
    global $wpdb;
    $sql   = "SELECT * FROM $wpdb->comments as a LEFT JOIN $wpdb->commentmeta as b WHERE a.comment_ID = b.comment_id AND a.comment_approved = 'spam'";
    $spams = $wpdb->get_results($wpdb->prepare($sql));
    return $spams;
}

?>