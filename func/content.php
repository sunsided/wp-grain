<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));


/* functions */

	define('GRAIN_POSTTYPE_PHOTO', 'photo');
	define('GRAIN_POSTTYPE_SPLITPOST', 'split-post');

	function grain_posttype($post_id, $default=GRAIN_POSTTYPE_PHOTO) {
		
		return GRAIN_POSTTYPE_PHOTO;
		//return GRAIN_POSTTYPE_SPLITPOST;
		//return 'split-post';
		
	}
	
	function grain_get_the_content() {
		global $post;
	
		$array = explode('<!--more-->', $post->post_content);
		//$content = get_the_content('', 0, '');
		$content = $array[0];
		$content = apply_filters('get_the_content', $content);
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]&gt;', $content);
		$content = apply_filters(GRAIN_GET_THE_CONTENT, $content);
		return $content;
	}
	
	function grain_get_the_special_content() {
		global $post;
	
		$array = explode('<!--more-->', $post->post_content);
		//$content = get_the_content('', 0, '');
		$content = $array[1];
		$content = apply_filters('get_the_content', $content);
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]&gt;', $content);
		$content = apply_filters(GRAIN_THE_SPECIAL_CONTENT, $content);
		return $content;
	}

/* Common post tasks */

	function grain_prepare_post_logic() {
	
		global $GrainOpt, $post; //, $previous, $next, $pagePosition, $message_left, $message_right;
	
		// this array will hold the options
		$returnValue = array();
	
		// prepare post
		if( $GrainOpt->getYesNo(GRAIN_2NDLANG_ENABLED) ) 
		{
			$en_title = get_post_meta($post->ID, $GrainOpt->get(GRAIN_2NDLANG_TAG), true);
			$returnValue["addon"] = "";
			if( $en_title != null && $en_title != $post->post_title ) $returnValue["addon"] = "<br /><span class='thin'>" . $en_title .'</span>';
		}

		// get surrounding posts
		$returnValue["previous"] = get_previous_post();
		$returnValue["next"] = get_next_post();

		// compose messages
		$returnValue["pagePosition"] = GRAIN_SURROUNDED_POST;
		if( $returnValue["previous"] != null ) {
			$returnValue["message_left"] = __("&lArr; click for previous photo", "grain");
		} else {
			$returnValue["message_left"] = __("(this is the first photo)", "grain");
			$returnValue["pagePosition"] = GRAIN_FIRST_POST;
		}

		if( $returnValue["next"] != null ) {
			$returnValue["message_right"] = __("click for next photo &rArr;", "grain");
		} else {
			$returnValue["message_right"] = __("(this is the newest photo)", "grain");
			$returnValue["pagePosition"] = GRAIN_NEWEST_POST;
		}
	
		// return the information
		return $returnValue;
	}

/* Regular photo page */

	function grain_do_regularpost_logic() {
		global $GrainOpt, $post;

		// prepare post
		$PostOpt = grain_prepare_post_logic();
		$next = $PostOpt["next"]; $previous = $PostOpt["previous"];
	
		// The URL of the image that will be displayed if no photo could be found for a post
		$whoops_url = $GrainOpt->get(GRAIN_WHOOPS_URL);
		
		// open the region
		echo '<div id="photo-frame">';
			
		// If the post has no image and no replacement ("whoops") image, display an error text
		$hasImage = grain_post_has_image();
		if (!$hasImage && empty($whoops_url) )
		{
			grain_inject_photopage_error(__("This photo page is not ready yet. Please check back later.", "grain"));
		} 
		else
		{
			// get image path and URL
			if ( $hasImage )
			{
				$path = $post->image->systemFilePath();
				$image_url = $post->image->uri;					
				$image_dimensions = getimagesize($path);	
			}
			else
			{
				$image_url = $whoops_url;
				$image_dimensions = array ( $GrainOpt->get(GRAIN_WHOOPS_WIDTH), $GrainOpt->get(GRAIN_WHOOPS_HEIGHT) );				
			}

				
			// scale image
			$dimensions = grain_scale_image_size($image_dimensions, $GrainOpt->get(GRAIN_MAX_IMAGE_WIDTH), $GrainOpt->get(GRAIN_MAX_IMAGE_HEIGHT)); // max width, max height
			$width = $dimensions['width']; $height = $dimensions['height']; $width2 = $dimensions['halfWidth']; $rigth_width = $dimensions['halfWidth2'];

			// get reduced size image if necessary
			$imageIsBiggerThanMax = $image_dimensions[0] > GRAIN_MAX_IMAGE_WIDTH;
			$quality = 100; // <- this value can be altered to set the quality (percent) of the rescaled image
			if ( $hasImage && $imageIsBiggerThanMax ) {
				$image_url = $post->image->getThumbnailHref(array('w='.$width,'h='.$height,'q='.$quality));
			}

			// compose tooltips
			$tooltips = grain_compose_post_tooltips($PostOpt["message_left"], $PostOpt["message_right"], $PostOpt["addon"] );
			$title_prev = $tooltips["prev"]; $title_next = $tooltips["next"];
						
			// get links
			$link_prev = ($previous != null ) ? get_permalink($previous->ID) : '#';
			$link_next = ($next != null ) ? get_permalink($next->ID) : '#';

			// tweak title
			$title_attr = "";
			if( $PostOpt["pagePosition"] != GRAIN_SURROUNDED_POST )
			{
				$title_attr = 'title="';
				$title_attr .= ($PostOpt["pagePosition"] == GRAIN_NEWEST_POST) ? $title_next : $title_prev;
				$title_attr .= '"';
			}
						
			// build image link and link map
			$string =  '';
			$useReflection = grain_use_reflection();
			if( $GrainOpt->is(GRAIN_NAV_BIDIR_ENABLED) )
			{						
				// create image
				if( !$useReflection ) $string .= '<div class="photo">';
				$string .= '<img '.$title_attr.' id="photo" alt="'. $post->post_title . '" class="photo'.($useReflection? '-with-reflection' : '' ).'" style="width: '.$width.'px; height: '.$height.'px;" src="'. $image_url .'" usemap="#bloglinks" />';				
				if( !$useReflection ) $string .= '</div>';
				
				// create linkmap
				if( $previous || $next ) {
					$string .= ' <map name="bloglinks">'."\n";					
					if( $previous != null )	$string .= '<area shape="rect" class="tooltipped" coords="0,0,'.$width2.','.$height.'" title="'.$title_prev.'" rel="prev" href="'. get_permalink($previous->ID) .'">'."\n";
					if( $next!= null ) 		$string .= '<area shape="rect" class="tooltipped" coords="'.$width2.',0,'.($rigth_width+$width2).','.$height.'" title="'.$title_next.'" rel="next" href="'. get_permalink($next->ID) .'">'."\n";
					$string .= '</map>';
				}
			}
			else
			{
				if( $previous != null )
					$string = '<a class="tooltipped" title="'.$title_prev.'" rel="prev" href="'. get_permalink($previous->ID) .'"><img title="'.$title_prev.'" id="photo" alt="'. $post->post_title . '" class="photo'.($useReflection? '-with-reflection' : '' ).'" style="width: '.$width.'px; height: '.$height.'px;" src="'. $image_url .'"/></a>';
				else
					$string = '<img '.$title_attr.' id="photo" alt="'. $post->post_title . '" class="photo'.($useReflection ? '-with-reflection' : '' ).'" style="width: '.$width.'px; height: '.$height.'px;" src="'. $image_url .'"/>';
				
			}

			// action
			do_action(GRAIN_BEFORE_IMAGE);				
			
			// display image
			if( grain_fx_can_fade() ) echo '<div id="photo-fade">';
			echo $string;				
			if( grain_fx_can_fade() ) echo '</div>';
			
			// action
			do_action(GRAIN_AFTER_IMAGE);
			
			// inject reflection script
			grain_inject_reflec_script('photo');

		} // (!grain_post_has_image() && empty($whoops_url) ) 

		echo '</div>		<!-- <div id="photo-frame"> -->';
		//echo '</div>		<!-- <div id="loading-frame"> -->';	
	}

/* Splitpost */
	
	function grain_do_splitpost_logic() {
		global $post;
		
		// prepare post
		$PostOpt = grain_prepare_post_logic();
		
		echo '<div id="special-frame">';
				
		// action
		do_action(GRAIN_BEFORE_PANORAMA);
		
		if( !grain_use_reflection() ) 
			echo '<div class="photo">';
		else
			echo '<div class="photo-with-reflection">';
			
	
		$path = $post->image->systemFilePath();
		$image_url = get_bloginfo('url').$post->image->uri;
		// get image size
		$image_dimensions = getimagesize($path);					
		
		/*
		grain_embed_ptviewer( 
			array( 
				'file' => $image_url,
				'pwidth' => $image_dimensions[0], 
				'pheight' => $image_dimensions[1]
			));
		*/
		
		//$image_url = get_bloginfo('template_directory').'/iplugs/devalvr/testfiles/testfileForQT.mov';
		
		grain_embed_devalvr( array( 
			'file' => $image_url,
			'ptviewer' => 
				array( 
					'pwidth' => $image_dimensions[0], 
					'pheight' => $image_dimensions[1],
					'wait' => GRAIN_TEMPLATE_DIR.'/images/loading.gif',
					'panmin' => '-90',
					'panmax' => '90'
				)
			));
	
		// output the 'special' content here
		echo grain_get_the_special_content();
		
		echo '</div>'; // photo-(with)?border
		
		// action
		do_action(GRAIN_AFTER_PANORAMA);
		
		echo '</div>'; // special-frame
	
		// inject reflection script
		// grain_inject_reflec_script('panorama-applet');
	}

?>