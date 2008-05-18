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

?>