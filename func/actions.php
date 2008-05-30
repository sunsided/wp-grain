<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

/* define filters */
	
	define('GRAIN_BEFORE_IMAGE', 'grain_before_image');
	define('GRAIN_AFTER_IMAGE', 'grain_after_image');
	define('GRAIN_BEFORE_PANORAMA', 'grain_before_pano');
	define('GRAIN_AFTER_PANORAMA', 'grain_after_pano');
	define('GRAIN_PANO_APPLET_PARAMS', 'grain_panoAppParams');
	
	define('GRAIN_PHOTO_PAGE_ERROR', 'grain_photo_page_error');
	define('GRAIN_AFTER_SEARCH_FORM', 'grain_after_search_form');
	
	define('GRAIN_ARCHIVE_BEFORE_THUMB', 'grain_archive_before_thumb');
	define('GRAIN_ARCHIVE_AFTER_THUMB', 'grain_archive_after_thumb');
	
?>