<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));
	
/* comments helper */

	function grain_get_comments($post_id) {
		global $wpdb;

		$post_id = (int) $post_id;
		return $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_post_ID = $post_id AND comment_approved IN ('0', '1') ORDER BY comment_date");
	}	

	function grain_inject_commenteditlink($comment) {
		global $userdata;

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

?>