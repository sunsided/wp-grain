<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/

	// get info / mosaic page
	$infoPageId      = $GrainOpt->get(GRAIN_INFOPAGE_ID);
	$mosaicPageId    = $GrainOpt->get(GRAIN_MOSAIC_PAGEID);
    $mosaicLinkTitle = $GrainOpt->get(GRAIN_MOSAIC_LINKTITLE);
	$thisIsInfoPage  = ($infoPageId > 0) && ($post->ID == $infoPageId);
	$thisIsMosaicPage  = ($mosaicPageId > 0) && ($post->ID == $mosaicPageId);

	// test the availability of some options
	$grain_newest_enabled = $GrainOpt->getYesNo(GRAIN_MNU_NEWEST_VISIBLE);
	$grain_random_enabled = $GrainOpt->getYesNo(GRAIN_MNU_RANDOM_VISIBLE);
	$grain_info_enabled = $GrainOpt->getYesNo(GRAIN_MNU_INFO_VISIBLE);
	$grain_mosaic_enabled = $GrainOpt->getYesNo(GRAIN_MOSAIC_ENABLED);
	$grain_extended_comments = $GrainOpt->getYesNo(GRAIN_EXTENDEDINFO_ENABLED);

	// get some system related values
	$isContentPage = is_single() || is_home();
	$postCount = grain_getpostcount();

/**********************************************************************************************/

	// get post object
	global $post, $GrainOpt;

	// this will be the array that contains the menu items
	$links = array();

	// Navigation photo links
	if( $postCount && $isContentPage ):

		// get next / previous post link
		if( get_previous_post() != null )	$prev = grain_mimic_previous_post_link( '%link', __("&laquo; previous", "grain") );
		if( get_next_post() != null )		$next = grain_mimic_next_post_link( '%link', __("next &raquo;", "grain") );

		// build the link
		$link = "";
		if( $prev ) 			$link = '<span id="menu-prev">'.$prev.'</span>';
		if( $prev && $next ) 	$link .= " ";
		if( $next ) 			$link .= '<span id="menu-next">'.$next.'</span>';
		
		// add the link
		if( !empty($link) ) 	array_push( $links, $link );

		// add comments link
		if( grain_can_comment() ) {
			$link = grain_generate_comments_link();			
			array_push( $links, '<span id="menu-comments">'.$link.'</span>' );
		}

		// add permalink
		if( $GrainOpt->getYesNo(GRAIN_MNU_PERMALINK_VISIBLE) ):
			$link = '<a class="tooltipped" id="permalink" alt="'.__("Permalink for: ", "grain").$post->post_title.'" title="'.grain_thumbnail_title(__("Permalink", "grain"), $post->post_title).'" href="'.get_permalink($post->ID).'">'.__("#", "grain").'</a>';
			array_push( $links, '<span id="menu-permalink">'.$link.'</span>' );
		endif;
	
	endif;

	// Newest photo link
	if( $postCount > 1 && $grain_newest_enabled && !is_home() && get_next_post() ):
		$link = '<a title="'.__("go to the newest photo", "grain").'" accesskey="h" rel="start" href="'.get_settings('home').'/">'.__("newest", "grain").'</a>';
        array_push( $links, '<span id="menu-newest">'.$link.'</span>' );
	endif;

	// Random photo link
	if( $postCount > 2 && $grain_random_enabled ):
		$link = grain_randompost( __("random", "grain") );
        array_push( $links, '<span id="menu-random">'.$link.'</span>' );
	endif;
	
	// Mosaic page link
	if( $postCount && $grain_mosaic_enabled && $thisIsMosaicPage ):
		$mosaicpost = get_post($mosaicPageId);
		if($mosaicpost) {
			$link = '<a class="tooltipped" title="'.grain_thumbnail_title($mosaicpost->post_title,__("To the overview", "grain")).'" accesskey="m" href="'.get_permalink($mosaicPageId).'">'.grain_mosaicpage_title().'</a>';
			array_push( $links, '<span id="menu-mosaic">'.$link.'</span>' );
		}
	endif;

	// Info page link
	if( $grain_info_enabled && $thisIsInfoPage ):
		$infopost = get_post($infoPageId);
		if($infopost) {
			$link = '<a title="'.__("about &amp; information", "grain").'" accesskey="a" href="'.get_permalink($infoPageId).'">'.__("about", "grain").'</a>';
			array_push( $links, '<span id="menu-about">'.$link.'</span>' );
		}
	endif;

	// combine the array elements to one large menu
 	echo implode( $links, ' <span id="menu-delimiter">|</span> ' );

?>