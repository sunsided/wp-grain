<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$

*//**

	Image helper functions
	
	@package Grain Theme for WordPress
	@subpackage Image
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

/* Image functions */
	
	/**
	 * grain_scale_image_size() - Scales a size array to fit in given dimensions
	 *
	 * This function implements aspect ratio correct scaling of given size structure.
	 * If no height is given, no height constraint will be applied. This is useful for
	 * panoramic images that are higher than wide.
	 *
	 * This function returns an associative array of ints. "width" refers to the scaled width,
	 * "height" to the scaled height. In addition to that, the keys "halfWidth" and "halfWidth2"
	 * are added, where "halfWidth" is half the scaled width and "halfWidth2" is the scaled and
	 * reduced width, so that halfWidth+halfWidth2=width for images whose scaled width is odd.
	 *
	 * @since 0.3
	 * @param array $dimensions		Array with the original sizes. Width at index 0, height at index 1.
	 * @param int $maxWidth			Maximum width
	 * @param int $maxHeight			Optional. Maximum height
	 * @return array Array of scaled dimensions.
	 */
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

	/**
	 * grain_post_has_image() - Tests whether the post has an image.
	 *
	 * @since 0.3
	 * @uses grain_is_yapb_installed() To determine if the YAPB plugin is installed.
	 * @uses yapb_is_photoblog_post() To test if this post is a photoblog post.
	 * @return array Array of scaled dimensions.
	 */
	function grain_post_has_image() {
		if( !grain_is_yapb_installed() || !function_exists("yapb_is_photoblog_post") ) return null;
		return yapb_is_photoblog_post();
	}

	/**
	 * grain_get_phpthumb_options() - Gets the options for the phpThumb thumbnail generator
	 *
	 * This function should be called with $width and $height parameters set. Otherwise
	 * the function will fall back to the GRAIN_MOSAIC_THUMB_WIDTH and GRAIN_MOSAIC_THUMB_HEIGHT
	 * configuration options' values.
	 *
	 * A basic set of options is set here, additional options are taken from the
	 * GRAIN_PHPTHUMB_OPTIONS config option.
	 *
	 * A complete list of configuration options for phpThumb can be found in the 
	 * <a href="http://phpthumb.sourceforge.net/demo/docs/phpthumb.readme.txt">phpThumb() manual</a> 
	 * or at the <a href="http://phpthumb.sourceforge.net/">phpThumb()</a> project site
	 * in general.
	 *
	 * @since 0.3
	 * @global $GrainOpt Grain options
	 * @param int $width			Optional. The width of the thumbnail
	 * @param int $height		Optional. The height of the thumbnail
	 * @return array Array of strings containing the options for phpThumb
	 */
	function grain_get_phpthumb_options($width=NULL, $height=NULL) {
		global $GrainOpt;
		
		// Fallback
		if($width === NULL) $width = intval($GrainOpt->get(GRAIN_MOSAIC_THUMB_WIDTH));
		if($height === NULL) $height = intval($GrainOpt->get(GRAIN_MOSAIC_THUMB_HEIGHT));
		
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
		// http://phpthumb.sourceforge.net/demo/docs/phpthumb.readme.txt
		$additionalOptsLine = $GrainOpt->get(GRAIN_PHPTHUMB_OPTIONS);
		$additionalOpts = preg_split("/[&\s]/", $additionalOptsLine, -1, PREG_SPLIT_NO_EMPTY);
		$phpThumbOptions = array_merge($phpThumbOptions, $additionalOpts);
		
		// return
		return $phpThumbOptions;
	}

	/**
	 * grain_mimic_ygi_archive() - Gets the HTML markup for a thumbnail frame on an archive page
	 *
	 * This function applies the "yapb_get_thumbnail" filter.
	 *
	 * @since 0.2
	 * @global $GrainOpt Grain options
	 * @uses grain_get_phpthumb_options() To get the phpThumb() configuration options
	 *
	 * @param mixed $image		The YAPB image object
	 * @param mixed $post		The current post object
	 * @param string $scope		Optional. The scope of the image. (Defaults to "archive")
	 * @return string HTML markup for the current image's/post's thumbnail
	 */
	function grain_mimic_ygi_archive($image, $post, $scope="archive") {
		global $GrainOpt;

		// get data
		$width = intval($GrainOpt->get(GRAIN_MOSAIC_THUMB_WIDTH));
		$height = intval($GrainOpt->get(GRAIN_MOSAIC_THUMB_HEIGHT));
	
		$en_title = get_post_meta($post->ID, $GrainOpt->get(GRAIN_2NDLANG_TAG), true);
		$addon = "";
		if( $GrainOpt->is(GRAIN_2NDLANG_ENABLED) && ($en_title != null && $en_title != $post->post_title) ) $addon = "<br /><span class='thin'>" . $en_title .'</span>';
		
		$message = __("click to view", "grain");
		if(empty($image)) $message = __("not ready yet", "grain");
		$tooltip = $GrainOpt->is(GRAIN_ARCHIVE_TOOLTIPS) ? grain_thumbnail_title($post->post_title.$addon, $message, "mosaic") : '';
		
		// build
		$image_src = ""; //GRAIN_TEMPLATE_DIR ."/images/tip-header.png";
		if( !empty($image) ) {
		
			// get phpThumb() options
			$phpThumbOptions = grain_get_phpthumb_options($width, $height);
			
			// get thumbnail
			$image_src = $image->getThumbnailHref($phpThumbOptions);
		}
		
		$width_attrib = $height_attrib = NULL;
		if($width  > 0 ) $width_attrib = 'width="'.$width.'"';
		if($height > 0 ) $height_attrib = 'height="'.$height.'"';
		$sizeStyle = NULL;
		if($width  > 0 ) $sizeStyle .='width: '.$width.'px;';
		if($height > 0 ) $sizeStyle .='height: '.$height.'px;';
		$image_html = "";
		if( empty($image) ) $image_src = GRAIN_TEMPLATE_DIR."/images/spacer.gif";
		$image_html = '<img id="thumbnail-'.$post->ID.'" '.$width_attrib.' '.$height_attrib.' style="'.$sizeStyle.'" class="'.$scope.'-thumb'.(empty($image)?" missing-image":"").'" src="' .$image_src. '" alt="'.$post->post_title.'" />';
		$image_html = apply_filters( 'yapb_get_thumbnail', $image_html );
		$anchor_html = '<a rel="alternate" href="' . get_permalink($post->ID) . '">'.$image_html.'</a>';
		
		$anchor_html = '<div title="'.$tooltip.'" id="thumbframe-'.$post->ID.'" class="thumbframe'.(empty($image)?"-no-image":"").'" style="'.$sizeStyle.'; overflow: visible;">'.$anchor_html."</div>";
		
		// return
		return $anchor_html;
	}

	/**
	 * grain_inject_popup_thumb() - Injects the HTML markup for a thumbnail frame on the comments popup
	 *
	 * @since 0.2
	 * @global $GrainOpt Grain options
	 * @uses grain_get_phpthumb_options() To get the phpThumb() configuration options
	 *
	 * @param mixed $image		The YAPB image object
	 * @param mixed $post		The current post object
	 * @return string HTML markup for the current image's/post's thumbnail
	 */
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
		
			// get phpThumb() options
			$phpThumbOptions = grain_get_phpthumb_options($width, $height);
		
			// create URL to the thumbnail
			$thumbHref = $post->image->getThumbnailHref($phpThumbOptions);
			$title = get_the_title();
		
			// embed thumbnail
			echo '<img id="comment-thumb" src="'.$thumbHref.'" alt="'.$title.'" title="'.$title.'" width="'.$width.'" height="'.$height.'" />';
		}
		
	}
	
	/**
	 * grain_get_mediarss_image_URL() - Gets the thumbnail URL to be used by the Media RSS
	 *
	 * @since 0.3
	 * @global $GrainOpt Grain options
	 * @uses grain_get_phpthumb_options() To get the phpThumb() configuration options
	 *
	 * @param mixed $post		The current post object
	 * @param mixed $image		The image object
	 * @param mixed $thumbnail	Optional. Set to TRUE if the full-resolution image shall be retrieved. (Defaults to FALSE, thumbnail)
	 * @return string HTML markup for the current image's/post's thumbnail
	 */
	function grain_get_mediarss_image_URL($post, $image, $thumbnail=TRUE, &$width, &$height) {
		global $GrainOpt;

		// if there is an image
		if (!empty($image))
		{
			// get image size
			$dimensions = getimagesize($image->systemFilePath());			 		
			
			// get image URI
			$img_URL = $image->uri;
			$width = $dimensions[0];
			$height = $dimensions[1];
			
			// scale?
			if( $thumbnail ) {
			
				// scale image
				$width = 300; // $GrainOpt->get(GRAIN_POPUP_THUMB_WIDTH);
				$height = 200; // $GrainOpt->get(GRAIN_POPUP_THUMB_HEIGHT);
				$dimensions = grain_scale_image_size($dimensions, $width, $height); // max size
				$width = $dimensions['width'];
				$height = $dimensions['height'];
		
				// get phpThumb() options
				$phpThumbOptions = grain_get_phpthumb_options($width, $height);
		
				// create URL to the thumbnail
				$img_URL = $image->getThumbnailHref($phpThumbOptions);
			}
		
			// embed thumbnail
			return $img_URL;
		}
		
	}
	
	/**
	 * grain_get_subtitle() - Gets a post's subtile if 2ndlang support is activated
	 *
	 * @since 0.2
	 * @global $GrainOpt Grain options
	 * @uses get_post_meta() To get post meta ifnromation
	 * @return string The subtitle or NULL
	 */
	function grain_get_subtitle() {
		global $post, $GrainOpt;
		if( !$GrainOpt->is(GRAIN_2NDLANG_ENABLED) ) return null;
		
		// get the title
		$subtitle = get_post_meta($post->ID, $GrainOpt->get(GRAIN_2NDLANG_TAG), true);
		$has_subtitle = !empty($subtitle) && ( $subtitle != $post->post_title );
		
		// return the title or
		return $has_subtitle ? $subtitle : null;
	}
	
	/**
	 * grain_exif_visible() - Checks if EXIF information shall be displayed
	 *
	 * @deprecated
	 * @since 0.2
	 * @global $GrainOpt Grain options
	 * @return bool TRUE if EXIF information shall be displayed
	 */
	function grain_exif_visible() {
		global $post, $GrainOpt;
		return !empty($post->image) && $GrainOpt->is(GRAIN_EXIF_VISIBLE);
	}
	
	/**
	 * grain_has_exif() - Checks if the current post has EXIF information
	 *
	 * This function also checks against the post's content and the GRAIN_HIDE_EXIF_IF_NO_CONTENT
	 * configuration option to determine wheter EXIF data shall be displayed.
	 *
	 * @since 0.3
	 * @uses grain_has_content() to check if the post has content
	 * @global $GrainOpt Grain options
	 * @global $GrainPostOpt Grain post options
	 * @return bool TRUE if EXIF information are available
	 */
	function grain_has_exif() {
		global $GrainOpt, $GrainPostOpt;
		if( !grain_has_content() && $GrainOpt->is(GRAIN_HIDE_EXIF_IF_NO_CONTENT) ) return FALSE;
		if( !$GrainOpt->is(GRAIN_EXIF_VISIBLE) ) return FALSE;
		if( $GrainPostOpt->get(GRAIN_POSTOPT_HIDE_EXIF) ) return FALSE;
		if( !function_exists('yapb_get_exif') || !function_exists('yapb_has_exif') ) return FALSE;
		return yapb_has_exif();
	}
	
	/**
	 * grain_get_exif() - Gets the EXIF data of the current photo
	 *
	 * @since 0.3
	 * @uses grain_has_exif() To check if EXIF information are available
	 * @uses yapb_get_exif() To get the EXIF information
	 * @return array An array of EXIF information as returned by yapb_get_exif()
	 */
	function grain_get_exif() {
		if( !grain_has_exif() ) return NULL;
		return yapb_get_exif();
	}

/* fancy EXIF filtering */

	// Add hooks
	add_filter(GRAIN_EXIF_KEY, "grain_fancy_exif_filter_key");	
	if($GrainOpt->is(GRAIN_FANCY_EXIFFILTER)) add_filter(GRAIN_EXIF_VALUE, "grain_fancy_exif_filter");
	
	/**
	 * A string containing the current EXIF data key.
	 *
	 * @see grain_fancy_exif_filter_key()
	 * @access private
	 * @global string $__grain_exif_key
	 * @name $__grain_exif_key
	 */
	$__grain_exif_key = null;
	
	/**
	 * grain_fancy_exif_filter_key() - Filters the EXIF data KEY
	 *
	 * This function implements gettext() translation for EXIF data keys.
	 * Since it is not clear on compile time what EXIF keys are available,
	 * the call to the gettext function is obfuscated.
	 *
	 * In addition to that, the function also sets the key for simple value filtering.
	 *
	 * @since 0.3
	 * @see grain_fancy_exif_filter()
	 * @global $__grain_exif_key	The EXIF data key to be set
	 * @access private
	 * @param string $key		The EXIF data key
	 * @return string A translated string or the same value as $key
	 */
	function grain_fancy_exif_filter_key($key) {
		global $__grain_exif_key;
		$__grain_exif_key = strtolower($key);
		// Translate EXIF keys
		$exifTranslationFunc = "__";
		return $exifTranslationFunc($key, "grain");
	}
	
	/**
	 * grain_fancy_exif_filter() - Filters the EXIF data VALUE
	 *
	 * This function implements simple EXIF value filtering.
	 * This is a naive approach to filter out certain long or non-text fields, as well
	 * as localize timestamps, et cetera.
	 *
	 * @since 0.3
	 * @see grain_fancy_exif_filter_key()
	 * @global $GrainOpt Grain options
	 * @global $__grain_exif_key	The current EXIF data key as set by grain_fancy_exif_filter_key()
	 * @access private
	 * @param string $key		The EXIF data key
	 * @return string The filtered value or the same value as $value
	 */
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
	
	/**
	 * grain_get_mosaic_posts() - Gets the list of posts to be used by the mosaic
	 *
	 * @since 0.3
	 * @uses get_posts() Gets the posts
	 * @param int $mosaic_count_per_page		Optional. The number of posts per page
	 * @param int $offset						Optional. The current post offset
	 * @return array Array of posts
	 */
	function grain_get_mosaic_posts($mosaic_count_per_page=-1, $offset=0) {
		global $GrainOpt;
		
		if($mosaic_count_per_page < 0 ) $mosaic_count_per_page = grain_getpostcount();
		
		// generate options
		$get_post_options = array();
		$get_post_options[] = "post_type=post";
		$get_post_options[] = "numberposts=$mosaic_count_per_page";
		$get_post_options[] = "offset=$offset";
		
		// set ordering
		$ordering = "post_date";
		if( $GrainOpt->is(GRAIN_MOSAIC_SHUFFLE) ) $ordering = "RAND()";
		$get_post_options[] = "order=DESC";
		$get_post_options[] = "orderby=$ordering";
		
		// return posts
		$get_post_options = implode("&", $get_post_options);
		return get_posts($get_post_options);
	}
	
	/**
	 * grain_get_mediarss_post_per_page() - Gets the number of posts to show per MediaRSS "page"
	 *
	 * @since 0.3.1
	 * @return int Number of posts
	 */
	function grain_get_mediarss_post_per_page() {
		global $GrainOpt;
		$count = $GrainOpt->get(GRAIN_FTR_MEDIARSS_COUNT);	
		if($count < 0) $count = grain_getpostcount();
		else if($count == 0) $count = $GrainOpt->getDefault(GRAIN_FTR_MEDIARSS_COUNT);
		return $count;
	}
	
	/**
	 * grain_get_mediarss_posts() - Gets the list of posts to be used by the meda RSS feed
	 *
	 * @since 0.3
	 * @uses get_posts() Gets the posts
	 * @param int $page						Optional. The number of the MediaRSS "page" to show.
	 * @return array Array of posts
	 */
	function grain_get_mediarss_posts($page=1, $count=-1) {
		global $GrainOpt;
		
		if($count<=0) $count = grain_get_mediarss_post_per_page();
		
		// get offset from page
		if( $page > 0 ) --$page;
		$offset = $page * $count;
		
		// generate options
		$get_post_options = array();
		$get_post_options[] = "post_type=post";
		$get_post_options[] = "numberposts=$count";
		$get_post_options[] = "offset=$offset";
		
		// set ordering
		$ordering = "post_date";
		$get_post_options[] = "order=DESC";
		$get_post_options[] = "orderby=$ordering";
		
		// return posts
		$get_post_options = implode("&", $get_post_options);
		
		return get_posts($get_post_options);
	}

?>