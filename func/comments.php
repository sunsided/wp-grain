<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$

*//**

	Comment helper functions
	
	@package Grain Theme for WordPress
	@subpackage Comments
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));
	
/* comments helper */

	/**
	 * grain_can_comment() - Checks if the current post is open for comments
	 *
	 * @global $GrainOpt Grain options
	 * @uses $post Global post object
	 * @return bool TRUE if comments are allowed on the current post
	 */
	function grain_can_comment() 
	{
		global $GrainOpt, $post;
		if( empty($post) ) return false;
		if( !$GrainOpt->getYesNo(GRAIN_COMMENTS_ENABLED) ) return false;
		if( !grain_post_has_image()	) {
			return $GrainOpt->getYesNo(GRAIN_COMMENTS_ON_EMPTY_ENABLED);
		}
		return true;
	}

	/**
	 * grain_get_comments() - Gets all comments on a post, ordered by date
	 *
	 * @uses $wpdb Database Object
	 * @param int $post_id 				The post's id
	 * @return array An array of comments as returned from the database.
	 */
	function grain_get_comments($post_id) {
		global $wpdb;

		$post_id = intval($post_id);
		return $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_post_ID = $post_id AND comment_approved IN ('0', '1') ORDER BY comment_date");
	}	

	/**
	 * grain_inject_commenteditlink() - Injects the edit link for a given comment
	 *
	 * @uses $userdata To determine wheter the current user is allowed to moderate a comment
	 * @uses current_user_can() To determine wheter the current user is allowed to moderate a comment
	 * @param int $comment 				The comment object
	 */
	function grain_inject_commenteditlink($comment) {
		global $userdata;
		if( empty($comment) ) return;
	
		// get the ID
		$user_ID = $userdata->ID;
		
		// test if the current user can moderate comments
		if( !empty($user_ID) && current_user_can('moderate_comments') ):
				$approved = ($comment->comment_approved == "1");
			?>
			<div class="comment-admin-tools<?php if(!$approved) echo "-unapproved"; ?>">
				<?php 
					$target = GRAIN_IS_POPUP ? "_blank" : "_self";
					$tooltip = grain_thumbnail_title(__("edit comment", "grain"), __("comment ID:", "grain") .' '. $comment->comment_ID);
					$edit_text = __("edit comment", "grain");
					$html = '<a href="'.get_bloginfo('url').'/wp-admin/comment.php?action=editcomment&c='.get_comment_ID().'" target="'.$target.'" title="'.$tooltip.'">'.__("edit comment", "grain").'</a>';
					if( !$approved ) $html = '<span class="unapproved-comment">'.__("unapproved &rarr;", "grain").' '.$html.'</span>';
					echo $html;
				?>
			</div>
			<?php 
		endif; // moderation test	
	}
	
	/**
	 * grain_commentcount_string() - Gets a string containing the count of comments on the current post
	 *
	 * The returned string is a string representation of the number of comments on the current post.
	 * If the current user is allowed to moderate, the count of unapproved comments on the current post
	 * is appended.
	 *
	 * @uses get_comment_count() 		To get the number of comments on the current post
	 * @uses current_user_can() 		To determine wheter the current user is allowed to moderate a comment
	 * @return string						A string containing the number of comments
	 */	
	function grain_commentcount_string() {
		global $post;
		
		// get counts
		$comment_count = get_comment_count($post->ID);
		$unapproved_count = $comment_count["awaiting_moderation"];
		$approved_count = $post->comment_count;
		$comment_count = $approved_count;
		
		// check user privileges
		$user_can_moderate = current_user_can('moderate_comments');
		if( $user_can_moderate ) {
			if($unapproved_count > 0) $comment_count = $approved_count.'<span class="unapproved_count">/'.$unapproved_count.'</span>';
		}
		
		return $comment_count;
	}

?>