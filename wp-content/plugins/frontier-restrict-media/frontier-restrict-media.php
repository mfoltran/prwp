<?php
/*
Plugin Name: Frontier Restrict Media
Plugin URI: http://wordpress.org/extend/plugins/frontier-restrict-media
Description: Restrict users to only access their own media files 
Author: finnj
Version: 1.1.3
Author URI: http://wpfrontier.com
*/

define('FRONTIER_RESTRICT_MEDIA_VERSION', "1.1.3"); 
define('FRONTIER_RESTRICT_MEDIA_DIR', dirname( __FILE__ )); //an absolute path to this directory

//Restrict users who dont have capability edit_others_posts, to only see media they have uploaded themselves
function frontier_restrict_media($query) 
	{ 
	//Check if it is an admin query
	if ($query->is_admin && !current_user_can( 'edit_others_posts' ))
		{
		//Check if it is an attachment query
		if ($query->query['post_type'] === 'attachment')
			{
			// Get current user, and author=current user to query
			$current_user = wp_get_current_user();
			$query->set('author', $current_user->ID);
			}
		}
	return $query;
	}
add_filter('pre_get_posts', 'frontier_restrict_media');
?>