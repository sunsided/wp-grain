<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$

*//**

	Grain filter definitions
	
	@package Grain Theme for WordPress
	@subpackage Grain Actions and Filters
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

/* define filters */
	
	/** Filter the copyright years */
	define('GRAIN_COPYRIGHT_YEARS', 'grain_copyright_years');
	
	/** Filter the extended copyright years */
	define('GRAIN_COPYRIGHT_YEARS_EX', 'grain_copyright_years_ex');
	
	/** Filter the title of a photo error page */
	define('GRAIN_PHOTO_PAGE_ERROR_TITLE', 'grain_photo_page_error_title');
	
	/** Filter the message of a search form error */
	define('GRAIN_SEARCHFORM_ERROR_MESSAGE', 'grain_searchform_error_message');
	
	/** Filter the return value of grain_get_the_content() */
	define('GRAIN_GET_THE_CONTENT', 'grain_get_the_special_content');
	
	/** Filter the return value of grain_get_the_special_content() */
	define('GRAIN_GET_THE_SPECIAL_CONTENT', 'grain_get_the_special_content');
	
	/** Filter the EXIF data as returned by YAPB */
	define('GRAIN_EXIF', 'GRAIN_EXIF');
	
	/** 
	 * Filter an EXIF information's key, such as exposureTime.
	 * The GRAIN_EXIF_KEY filter is always applied to the key right before the GRAIN_EXIF_VALUE filter is applied to the value.
	 */
	define('GRAIN_EXIF_KEY', 'GRAIN_EXIF_KEY');
	
	/** 
	 * Filter an EXIF information's value.
	 * The GRAIN_EXIF_KEY filter is always applied to the key right before the GRAIN_EXIF_VALUE filter is applied to the value.
	 */
	define('GRAIN_EXIF_VALUE', 'GRAIN_EXIF_VALUE');
	
	/** Filter the navigation ("header menu") before it is embedded */
	define('GRAIN_FILTER_NAVIGATION', 'GRAIN_FILTER_NAVIGATION');
?>