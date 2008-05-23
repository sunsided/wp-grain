<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

/* Image functions */
	
	function grain_scale_image_size( $dimensions, $maxWidth, $maxHeight=null ) {
		$width = $dimensions[0];
		$height = $dimensions[1];
		
		// reduce image width to $maxWidth
		if( $width > $maxWidth ) {
			$height = $height * $maxWidth / $width;
			$width = $maxWidth;
		}
		
		// reduce image height to $maxHeight
		if(!empty($maxHeight) && $height > $maxHeight) {
			$width = $width * $maxHeight / $height;
			$height = $maxHeight;
		}
		
		$width2 = (int)($width / 2);

		// issue with odd image widths
		$rigth_width = $width2;
		if( 2*$width2 < $width ) $rigth_width++;
		
		return array(
			'width' => intval($width),
			'height' => intval($height),
			'halfWidth' => intval($width2),
			'halfWidth2' => intval($rigth_width)
		);
	}

	function grain_post_has_image() {
		if( !grain_is_yapb_installed() || !function_exists("yapb_is_photoblog_post") ) return null;
		return yapb_is_photoblog_post();
	}

	function grain_mimic_ygi_archive($image, $post) {

		// get data
		$width = grain_mosaicthumb_width();
		$height = grain_mosaicthumb_height();
	
		$en_title = get_post_meta($post->ID, grain_2ndlang_tag(), true);
		$addon = "";
		if( grain_2ndlang_enabled() && ($en_title != null && $en_title != $post->post_title) ) $addon = "<br /><span class='thin'>" . $en_title .'</span>';
		
		$tooltip = grain_archive_tooltips() ? grain_thumbnail_title($post->post_title.$addon, __("click to view", "grain")) : '';
		
		// build
		$image_html = '<img width="'.$width.'" height="'.$height.'" class="archive-thumb" src="' . $image->getThumbnailHref(array('w='.$width,'h='.$height,'zc=1')) . '" alt="'.$post->post_title.'" title="'.$tooltip.'"/>';
		$image_html = apply_filters( 'yapb_get_thumbnail', $image_html );
		$anchor_html = '<a rel="bookmark" href="' . get_permalink($post->ID) . '">'.$image_html.'</a>';
		
		// return
		return $anchor_html;
	}

	function grain_inject_popup_thumb() {
		global $post, $GrainOpt;
		if( !$GrainOpt->is(GRAIN_POPUP_SHOW_THUMB) ) return;

		// if there is an image
		if (!empty($post->image))
		{
			// get image size
			$dimensions = getimagesize($post->image->systemFilePath());			 
			
			// scale image
			$width = $GrainOpt->get(GRAIN_POPUP_THUMB_WIDTH);
			$height = $GrainOpt->get(GRAIN_POPUP_THUMB_HEIGHT);
			if( $GrainOpt->getYesNo(GRAIN_POPUP_THUMB_STF) ) {
				$dimensions = grain_scale_image_size($dimensions, $width, $height); // max size
				$width = $dimensions['width'];
				$height = $dimensions['height'];
			}
		
			// create URL to the thumbnail
			$thumbHref = $post->image->getThumbnailHref(array('w='.$width,'h='.$height, 'zc=1'));
			$title = get_the_title();
		
			// embed thumbnail
			echo '<img id="comment-thumb" src="'.$thumbHref.'" alt="'.$title.'" title="'.$title.'" width="'.$width.'" height="'.$height.'" />';
		}
		
	}
	
	function grain_get_subtitle() {
		global $post, $GrainOpt;
		if( !$GrainOpt->is(GRAIN_2NDLANG_ENABLED) ) return null;
		
		// get the title
		$subtitle = get_post_meta($post->ID, $GrainOpt->get(GRAIN_2NDLANG_TAG), true);
		$has_subtitle = !empty($subtitle) && ( $subtitle != $post->post_title );
		
		// return the title or
		return $has_subtitle ? $subtitle : null;
	}
	
	function grain_exif_visible() {
		global $post, $GrainOpt;
		return !empty($post->image) && $GrainOpt->is(GRAIN_EXIF_VISIBLE);
	}

?>