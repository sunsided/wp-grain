<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

/* comment link generation */

	function grain_generate_comments_link() {
		global $post;
		
		$grain_extended_comments = grain_extended_comments();
		$comments_open = $post->comment_status == "open";
		$link = '';

	    // display the comment popup link
		if( !$grain_extended_comments && !GRAIN_REQUESTED_OTEXINFO )
		{
			// get string
			$_hmn_comments_more = str_replace( "%", $post->comment_count, __("comments (%)", "grain") );
			
			// inf info enforcement is on, we skip directly to the comments on the popup
			// TODO: Add apropriate option here
			$internal = (grain_enforce_info() && grain_popup_jumptocomments() ? '#comments' : '');
			// build link
			$link .= (!$comments_open ? '<del class="closed-comments">' : '');
			$link .= '<a class="open-popup" onclick="wpopen(this.href); return false" title="'.__("comments and details", "grain").'" accesskey="c" rel="nofollow" href="?comments_popup='.$post->ID.$internal.'">'.$_hmn_comments_more.'</a>';
			$link .= (!$comments_open ? '</del>' : '');
			
		}
		else
		{
			// get strings
			$_hmn_comments_more = str_replace( "%", $post->comment_count, __("show comments (%)", "grain") );
			$_hmn_comments_less = str_replace( "%", $post->comment_count, __("hide comments", "grain") );
			
			// set text
			//$text = (isset($_SESSION['grain:info']) && $_SESSION['grain:info'] == 'on') ? $_hmn_comments_less : $_hmn_comments_more;
			$text = GRAIN_REQUESTED_EXINFO ? $_hmn_comments_less : $_hmn_comments_more;
			
			// select behaviour (open/close)
			$target = (grain_enforce_info() ? '#comments' : '#info');
			//$infomode = (isset($_SESSION['grain:info']) && $_SESSION['grain:info'] == 'on') ? 'off' : 'on'.$target;
			$infomode = GRAIN_REQUESTED_EXINFO ? 'off' : 'on'.$target;
			
			// append info to permalink, based on whether it contains an ampersand or not
			$contains_amp = strstr(get_permalink($post->ID), '?');
			$permalink = get_permalink($post->ID) . ($contains_amp !== FALSE ? '&info='.$infomode : '?info='.$infomode);
			
			// build link
			$link .= (!$comments_open ? '<del class="closed-comments">' : '');
			$link .= '<a class="open-extended" title="'.__("comments and details", "grain").'" accesskey="i" rel="start" href="'.$permalink.'">'.$text.'</a>';
			$link .= (!$comments_open ? '</del>' : '');
		}
		
		return $link;
	}

?>