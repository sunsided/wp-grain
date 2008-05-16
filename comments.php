<?php
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));
	
/* comments helper */

	function grain_get_comments($post_id) {
		global $wpdb;

		$post_id = (int) $post_id;
		return $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_post_ID = $post_id AND comment_approved IN ('0', '1') ORDER BY comment_date");
	}	

?>