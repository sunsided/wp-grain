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
	$commentCount = grain_getcommentcount();

/**********************************************************************************************/

	// get post object
	global $post, $GrainOpt;

	// this will be the array that contains the menu items
	$links = array();

	// Navigation photo links
	if( $postCount && $isContentPage ):

		// get next / previous post link
		$prev = $next = null;
		if( get_previous_post() != null )	$prev = grain_mimic_previous_post_link( '%link', __("&laquo; previous", "grain") );
		if( get_next_post() != null )		$next = grain_mimic_next_post_link( '%link', __("next &raquo;", "grain") );

		// build the link
		$link = "";
		if( $prev ) 			$link = '<span id="menu-prev" class="postlink">'.$prev.'</span>';
		if( $prev && $next ) 	$link .= " ";
		if( $next ) 			$link .= '<span id="menu-next" class="postlink">'.$next.'</span>';
		
		// add the link
		if( !empty($link) ) {
			$classes = array();
			$classes[] = $prev ? "has-prev" : "no-prev";
			$classes[] = $next ? "has-next" : "no-next";
			$classes[] = ($prev && $next) ? "bidir" : "unidir";
			$classes = implode(" ", $classes);
			
			$link  = '<span id="menu-navigation" class="'.$classes.' postlink">'.$link.'</span>';
			array_push( $links, $link );
		}

		// add comments link
		if( grain_can_comment() ) {
			$link = grain_generate_comments_link();
			$class = ($commentCount > 0) ? "has-comments" : "no-comments";
			array_push( $links, '<span id="menu-comments" class="'.$class.' infolink">'.$link.'</span>' );
		}

		// add permalink
		if( $GrainOpt->getYesNo(GRAIN_MNU_PERMALINK_VISIBLE) ):
			$link = '<a class="tooltipped" id="permalink" alt="'.__("Permalink for: ", "grain").$post->post_title.'" title="'.grain_thumbnail_title(__("Permalink", "grain"), $post->post_title).'" href="'.get_permalink($post->ID).'">'.__("#", "grain").'</a>';
			array_push( $links, '<span id="menu-permalink" class="postlink">'.$link.'</span>' );
		endif;
	
	endif;

	// Newest photo link
	if( $postCount > 1 && $grain_newest_enabled && !is_home() && ((is_single() && get_next_post()) || is_page()) ):
		$link = '<a title="'.__("go to the newest photo", "grain").'" accesskey="h" rel="start" href="'.get_settings('home').'/">'.__("newest", "grain").'</a>';
        array_push( $links, '<span id="menu-newest" class="postlink">'.$link.'</span>' );
	endif;

	// Random photo link
	if( $postCount > 2 && $grain_random_enabled ):
		$link = grain_randompost( __("random", "grain") );
        array_push( $links, '<span id="menu-random" class="postlink">'.$link.'</span>' );
	endif;
	
	// Mosaic page link
	if( $postCount && $grain_mosaic_enabled && !$thisIsMosaicPage ):
		$mosaicpost = get_post($mosaicPageId);
		if($mosaicpost) {
			$link = '<a class="tooltipped" title="'.grain_thumbnail_title($mosaicpost->post_title,__("To the overview", "grain")).'" accesskey="m" href="'.get_permalink($mosaicPageId).'">'.$GrainOpt->get(GRAIN_MOSAIC_LINKTITLE).'</a>';
			array_push( $links, '<span id="menu-mosaic" class="pagelink">'.$link.'</span>' );
		}
	endif;

	// Info page link
	if( $grain_info_enabled && !$thisIsInfoPage ):
		$infopost = get_post($infoPageId);
		if($infopost) {
			$link = '<a title="'.__("about &amp; information", "grain").'" accesskey="a" href="'.get_permalink($infoPageId).'">'.__("about", "grain").'</a>';
			array_push( $links, '<span id="menu-about" class="pagelink">'.$link.'</span>' );
		}
	endif;

	// combine the array elements to one large menu
	do_action(GRAIN_BEFORE_NAVIGATION);
 	echo apply_filters(GRAIN_FILTER_NAVIGATION, implode( $links, ' <span class="menu-delimiter">|</span> ') );
	do_action(GRAIN_AFTER_NAVIGATION);

?>