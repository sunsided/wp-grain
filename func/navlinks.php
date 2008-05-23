<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

	/* left/right navigation */

	function grain_mimic_previous_post_link($format='&laquo; %link', $link='%title', $in_same_cat = false, $excluded_categories = '') {
		global $post;

		if ( is_attachment() )
			$prev = & get_post($post->post_parent);
		else
			$prev = get_previous_post($in_same_cat, $excluded_categories);

		if ( !$prev )
			return;

		$addon = "";
			//if( grain_extended_comments() ) $addon = '?info=' . ((isset($_REQUEST['info']) && $_REQUEST['info'] == 'on') ? 'on' : 'off');

		$title = apply_filters('the_title', $prev->post_title, $prev);
		$string = '<a rel="prev" href="'.get_permalink($prev->ID).$addon.'">';
		$link = str_replace('%title', $title, $link);
		$link = $pre . $string . $link . '</a>';

		$format = str_replace('%link', $link, $format);

		return $format;
	}

	function grain_mimic_next_post_link($format='%link &raquo;', $link='%title', $in_same_cat = false, $excluded_categories = '') {
		global $post;	
	
		$next = get_next_post($in_same_cat, $excluded_categories);

		if ( !$next )
			return;

		$title = apply_filters('the_title', $next->post_title, $next);
		$string = '<a rel="next" href="'.get_permalink($next->ID).'">';
		$link = str_replace('%title', $title, $link);
		$link = $string . $link . '</a>';
		$format = str_replace('%link', $link, $format);

		return $format;
	}
	
	/* comment link generation */

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

	function grain_generate_comments_link() {
		global $post, $GrainOpt;
		
		$grain_extended_comments = $GrainOpt->getYesNo(GRAIN_EXTENDEDINFO_ENABLED);
		$comments_open = $post->comment_status == "open";
		$link = '';

	    // display the comment popup link
		if( !$grain_extended_comments && !GRAIN_REQUESTED_OTEXINFO )
		{
			// get string
			$_hmn_comments_more = str_replace( "%", $post->comment_count, __("comments (%)", "grain") );
			
			// inf info enforcement is on, we skip directly to the comments on the popup
			$internal = ($GrainOpt->getYesNo(GRAIN_CONTENT_ENFORCE_INFO) && $GrainOpt->getYesNo(GRAIN_POPUP_JTC) ? '#comments' : '');
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
			
			// select behavior (open/close)
			// $target = ($GrainOpt->getYesNo(GRAIN_CONTENT_ENFORCE_INFO) ? '#comments' : '#info');
			$target = "#info";
			$infomode = GRAIN_REQUESTED_EXINFO ? 'off' : 'on'.$target;
			
			// append info to permalink, based on whether it contains an ampersand or not
			$contains_amp = strstr(get_permalink($post->ID), '?');
			$permalink = get_permalink($post->ID) . ($contains_amp !== FALSE ? '&info='.$infomode : '?info='.$infomode);
			
			// build link
			$link .= (!$comments_open ? '<del class="closed-comments">' : '');
			$link .= '<a class="open-extended" title="'.__("comments and details", "grain").'" accesskey="i" rel="alternate" href="'.$permalink.'">'.$text.'</a>';
			$link .= (!$comments_open ? '</del>' : '');
		}
		
		return $link;
	}	

?>