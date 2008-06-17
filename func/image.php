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

	function grain_get_phpthumb_options($width, $height) {
		global $GrainOpt;
		
		// prepare phpThumb options
		$phpThumbOptions = array();
		if( $width > 0  ) $phpThumbOptions[] = 'w='.$width;
		if( $height > 0 ) $phpThumbOptions[] = 'h='.$height;
		$phpThumbOptions[] = 'zc=1'; // zoom-cropping - can be disabled but a background color is advised then
		$phpThumbOptions[] = 'fltr[]=usm|80|0.5|3'; // usm filter
		$phpThumbOptions[] = 'iar=1'; // forced aspect ratio
		$phpThumbOptions[] = 'bg=000000'; // background if zoom-cropping is disabled
		
		// get additional options
		// check http://phpthumb.sourceforge.net/demo/demo/phpThumb.demo.demo.php for further phpThumb() configuration options
		$additionalOptsLine = $GrainOpt->get(GRAIN_PHPTHUMB_OPTIONS);
		$additionalOpts = preg_split("/[&\s]/", $additionalOptsLine, -1, PREG_SPLIT_NO_EMPTY);
		$phpThumbOptions = array_merge($phpThumbOptions, $additionalOpts);
		
		// return
		return $phpThumbOptions;
	}

	function grain_mimic_ygi_archive($image, $post) {
		global $GrainOpt;

		// get data
		$width = intval($GrainOpt->get(GRAIN_MOSAIC_THUMB_WIDTH));
		$height = intval($GrainOpt->get(GRAIN_MOSAIC_THUMB_HEIGHT));
	
		$en_title = get_post_meta($post->ID, $GrainOpt->get(GRAIN_2NDLANG_TAG), true);
		$addon = "";
		if( $GrainOpt->is(GRAIN_2NDLANG_ENABLED) && ($en_title != null && $en_title != $post->post_title) ) $addon = "<br /><span class='thin'>" . $en_title .'</span>';
		
		$message = __("click to view", "grain");
		if(empty($image)) $message = __("not ready yet", "grain");
		$tooltip = $GrainOpt->is(GRAIN_ARCHIVE_TOOLTIPS) ? grain_thumbnail_title($post->post_title.$addon, $message) : '';
		
		// build
		$image_src = ""; //GRAIN_TEMPLATE_DIR ."/images/tip-header.png";
		if( !empty($image) ) {
		
			// get phpThumb() options
			$phpThumbOptions = grain_get_phpthumb_options($width, $height);
			
			// get thumbnail
			$image_src = $image->getThumbnailHref($phpThumbOptions);
		}
		else {
			$width = 120;
		}
		
		$width_attrib = $height_attrib = NULL;
		if($width  > 0 ) $width_attrib = 'width="'.$width.'"';
		if($height > 0 ) $height_attrib = 'height="'.$height.'"';
		$sizeStyle = NULL;
		if($width  > 0 ) $sizeStyle .='width: '.$width.'px;';
		if($height > 0 ) $sizeStyle .='height: '.$height.'px;';
		$image_html = '<img id="thumbnail-'.$post->ID.'" '.$width_attrib.' '.$height_attrib.' style="'.sizeStyle.'" class="archive-thumb" src="' .$image_src. '" alt="'.$post->post_title.'" />';
		$image_html = apply_filters( 'yapb_get_thumbnail', $image_html );
		$anchor_html = '<a rel="bookmark" href="' . get_permalink($post->ID) . '">'.$image_html.'</a>';
		
		$anchor_html = '<div title="'.$tooltip.'" id="thumbframe-'.$post->ID.'" class="archive-thumbframe'.(empty($image)?"-no-image":"").'" style="width: '.$width.'px; height: '.$height.'px; overflow: visible;">'.$anchor_html."</div>";
		
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
	
	function grain_has_exif() {
		global $GrainOpt;
		if( !grain_has_content() && $GrainOpt->is(GRAIN_HIDE_EXIF_IF_NO_CONTENT) ) return FALSE;
		if( !$GrainOpt->is(GRAIN_EXIF_VISIBLE) ) return FALSE;
		if( !function_exists('yapb_get_exif') || !function_exists('yapb_has_exif') ) return FALSE;
		return yapb_has_exif();
	}
	
	function grain_get_exif() {
		if( !grain_has_exif() ) return NULL;
		return yapb_get_exif();
	}

	// Add hooks
	add_filter(GRAIN_EXIF_KEY, "grain_fancy_exif_filter_key");	
	if($GrainOpt->is(GRAIN_FANCY_EXIFFILTER)) add_filter(GRAIN_EXIF_VALUE, "grain_fancy_exif_filter");
	
	$__grain_exif_key = null;
	
	function grain_fancy_exif_filter_key($key) {
		global $__grain_exif_key;
		$__grain_exif_key = strtolower($key);
		// Translate EXIF keys
		$exifTranslationFunc = "__";
		return $exifTranslationFunc($key, "grain");
	}
	
	function grain_fancy_exif_filter($value) {
		global $__grain_exif_key, $GrainOpt;
		if(!$GrainOpt->is(GRAIN_FANCY_EXIFFILTER)) return $value;
		
		// hide non-ASCII fields
		switch($__grain_exif_key) {
			case "cfapattern":
			case "scenetype":
			case "sourcetype":
			case "filename": return NULL;
		}
		// convert some values fields
		switch($__grain_exif_key) {
			case "xresolution":
			case "yresolution":
			case "exposurebias":
			case "focallength":
			case "aperture":
			case "zoomratio": return round(floatval($value), 4);
		}
		// convert some values fields
		switch($__grain_exif_key) {
			case "exposuretime": {
				$index = strpos($value, '(');
				$index2 = strpos($value, ')');
				if( $index !== FALSE && $index2 !== FALSE) {
					return substr($value, $index+1, $index2-$index-1) ." s";
				}
				break;
			}
		}
		// convert some dates
		switch($__grain_exif_key) {
			case "filemodifieddate":
			case "datetime":
			case "datetimedigitized": {
				// Array ( [0] => 2008 [1] => 04 [2] => 30 [3] => 12 [4] => 03 [5] => 22 )
				$values = preg_split("/[:\s]/", $value);
				if( count($values) == 6 ) {
					$time = mktime($values[3], $values[4], $values[5], $values[1], $values[2], $values[0]);
					$formatted = date($GrainOpt->get(GRAIN_DTFMT_EXIF), $time);
					return $formatted;
				}
			}
		}
		
		// GRAIN_DTFMT_EXIF
		return $value;
	}

?>