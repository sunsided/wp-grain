<?php
	$_hmn_next 			= __("next &raquo;", "grain");
	$_hmn_previous 		= __("&laquo; previous", "grain");
	$_hmn_newest_text	= __("newest", "grain");
	//$_hmn_newest_title	= __("newest photo", "grain");
	$_hmn_random_text	= __("random", "grain");
	$_hmn_info_text		= __("about", "grain");
	$_hmn_info_title	= __("about &amp; information", "grain");


	$_hmn_infopage_id 	= grain_infopage_id(0); // defaults to 0 for availability checking below
	$_hmn_mosaicpage_id 	= grain_mosaicpage_id(0); // defaults to 0 for availability checking below
        $_hmn_mosaic_title 	= grain_mosaicpage_title();


	$grain_newest_enabled = grain_newest_enabled();
	$grain_random_enabled = grain_random_enabled();
	$grain_info_enabled = grain_info_enabled();
	$grain_mosaic_enabled = grain_mosaic_enabled();
	$grain_extended_comments = grain_extended_comments();

/**********************************************************************************************/
	
	function grain_generate_comments_link() {
		global $post;
		
		$grain_extended_comments = grain_extended_comments();
		$comments_open = $post->comment_status == "open";
		$link = '';

	    // display the comment popup link
	    //if( !$grain_extended_comments && !(isset($_SESSION['grain:info']) && !empty($_SESSION['grain:info']))):
		//if( !$grain_extended_comments && !(isset($_SESSION['grain:oti']) && !empty($_SESSION['grain:oti']))):		
		if( !$grain_extended_comments && !GRAIN_REQUESTED_OTEXINFO ):
			// get string
			$_hmn_comments_more = str_replace( "%", $post->comment_count, __("comments (%)", "grain") );
			
			// inf info enforcement is on, we skip directly to the comments on the popup
			// TODO: Add apropriate option here
			$internal = (grain_enforce_info() && grain_popup_jumptocomments() ? '#comments' : '');
			// build link
			$link .= (!$comments_open ? '<del class="closed-comments">' : '');
			$link .= '<a class="open-popup" onclick="wpopen(this.href); return false" title="'.__("comments and details", "grain").'" accesskey="c" rel="nofollow" href="?comments_popup='.$post->ID.$internal.'">'.$_hmn_comments_more.'</a>';
			$link .= (!$comments_open ? '</del>' : '');
		else:
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
		endif;
		
		return $link;
	}

/**********************************************************************************************/

	$links = array();

	// get post object
	global $post;

	if( is_single() || is_home() ):

		// get next / previous post link
		if( get_previous_post() != null ) {
			$prev = grain_mimic_previous_post_link( '%link', $_hmn_previous );
		}
		if( get_next_post() != null ) {
			$next = grain_mimic_next_post_link( '%link', $_hmn_next );
		}

                $link = "";
		if( $prev ) $link = '<span id="menu-prev">'.$prev.'</span>';
		if( $prev && $next ) $link .= " ";
		if( $next ) $link .= '<span id="menu-next">'.$next.'</span>';
		array_push( $links, $link );

		// comments link
		if( grain_comments_enabled() ) {
			$link = grain_generate_comments_link();
			array_push( $links, '<span id="menu-comments">'.$link.'</span>' );
		}
	endif;

 	// display various other links
?>
<?php 	

	if( (is_single() || is_home()) && grain_permalink_enabled() ):
		$link = '<a class="tooltipped" id="permalink" alt="'.__("Permalink for: ", "grain").$post->post_title.'" title="'.grain_thumbnail_title(__("Permalink", "grain"), $post->post_title).'" href="'.get_permalink($post->ID).'">'.__("#", "grain").'</a>';
                array_push( $links, '<span id="menu-permalink">'.$link.'</span>' );
	endif;

	if( $grain_newest_enabled ):
		$link = '<a title="Neuester Artikel" accesskey="h" rel="start" href="'.get_settings('home').'/">'.$_hmn_newest_text.'</a>';
                array_push( $links, '<span id="menu-newest">'.$link.'</span>' );
	endif;

	if( $grain_random_enabled ):
		$link = grain_randompost( $_hmn_random_text );
                array_push( $links, '<span id="menu-random">'.$link.'</span>' );
	endif;
	
	if( 	$grain_mosaic_enabled &&
		($post->ID != $_hmn_mosaicpage_id)  &&
		$_hmn_mosaicpage_id != 0
	):
		$mosaicpost = get_post($_hmn_mosaicpage_id);
		if($mosaicpost):
			$link = '<a class="tooltipped" title="'.grain_thumbnail_title($mosaicpost->post_title,__("To the overview", "grain")).'" accesskey="m" href="'.get_permalink($_hmn_mosaicpage_id).'">'.grain_mosaicpage_title().'</a>';
			array_push( $links, '<span id="menu-mosaic">'.$link.'</span>' );
		endif;
	endif;

	// display info link only if we are not on the info page and if the info page is enabled
	if( $grain_info_enabled && ($post->ID != $_hmn_infopage_id)  && $_hmn_infopage_id!= 0 && (get_post($_hmn_infopage_id) != null) ):
 		$link = '<a title="'.$_hmn_info_title.'" accesskey="a" href="'.get_permalink($_hmn_infopage_id).'">'.$_hmn_info_text.'</a>';
 		array_push( $links, '<span id="menu-about">'.$link.'</span>' );
	endif;

 	echo implode( $links, ' <span id="menu-delimiter">|</span> ' );

?>