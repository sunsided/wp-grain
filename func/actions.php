<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$

*//**

	Grain action definitions
	
	@package Grain Theme for WordPress
	@subpackage Grain Actions and Filters
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

/* define actions */
	
	/** Signaled before the image is embedded. */
	define('GRAIN_BEFORE_IMAGE', 'grain_before_image');
	
	/** Signaled after the image was embedded. */
	define('GRAIN_AFTER_IMAGE', 'grain_after_image');
	
	/** Signaled before the user defined content is embedded. */
	define('GRAIN_BEFORE_USERCONTENT', 'GRAIN_BEFORE_USERCONTENT');
	
	/** Signaled after the user defined content was embedded. */
	define('GRAIN_AFTER_USERCONTENT', 'GRAIN_AFTER_USERCONTENT');	
	
	/** Signaled bevore a panorama is embedded. */
	define('GRAIN_BEFORE_PANORAMA', 'grain_before_pano');
	
	/** Signaled after a panorama was embedded. */
	define('GRAIN_AFTER_PANORAMA', 'grain_after_pano');
	
	/** Signaled when the panorama applet's parameters are defined */
	define('GRAIN_PANO_APPLET_PARAMS', 'grain_panoAppParams');
	
	/** Signaled after a photo page error occured */
	define('GRAIN_PHOTO_PAGE_ERROR', 'grain_photo_page_error');
	
	/** Signaled after the search form was embedded */
	define('GRAIN_AFTER_SEARCH_FORM', 'grain_after_search_form');
	
	/** Signaled before a thumbnail is embedded */
	define('GRAIN_ARCHIVE_BEFORE_THUMB', 'grain_archive_before_thumb');
	
	/** Signaled after a thumbnail is embedded */
	define('GRAIN_ARCHIVE_AFTER_THUMB', 'grain_archive_after_thumb');
	
	/** Signaled before the exif block is embedded */
	define('GRAIN_BEFORE_EXIFBLOCK', 'GRAIN_BEFORE_EXIFBLOCK');
	
	/** Signaled after the exif block is embedded */
	define('GRAIN_AFTER_EXIFBLOCK', 'GRAIN_AFTER_EXIFBLOCK');
	
	/** Signaled before the navigation is embedded. */
	define('GRAIN_BEFORE_NAVIGATION', 'GRAIN_BEFORE_NAVIGATION');
	
	/** Signaled after the navigation is embedded. */
	define('GRAIN_AFTER_NAVIGATION', 'GRAIN_AFTER_NAVIGATION');
	
?>