<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

/* little helpers */

	function grain_version() 
	{
		$version = GRAIN_THEME_VERSION;
		iF(GRAIN_THEME_VERSION_DEVBUILD) $version .= ' dev';
		return $version;
	}

	function grain_rmlinebreaks($string) 
	{
		$breaks = array('<br />', '<br/>', '<br>');
		return str_replace($breaks, '', $string);
	}

	// Returns the number of published posts
	// Kudos: http://perishablepress.com/press/2006/08/28/display-total-number-of-posts/
	$__grain_post_count = -1;
	function grain_getpostcount() 
	{
		global $wpdb; global $__grain_post_count;
		if( $__grain_post_count < 0 ) $__grain_post_count = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish';");
		return $__grain_post_count;
	}

	function grain_is_yapb_installed() 
	{
		global $yapb;
		return !empty($yapb);
	}

?>