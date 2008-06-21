<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$

*//**

	Version helper functions
	
	@package Grain Theme for WordPress
	@subpackage Version
*/

	/* defines */

	/** The version of the theme */
	define('GRAIN_THEME_VERSION_BASE', '0.3');
	
	/** An extension to the current version */
	define('GRAIN_THEME_VERSION_EXTENDED', 'beta 1');
	
	/** The URL of the theme project */
	define('GRAIN_THEME_URL', 'http://wp-grain.wiki.sourceforge.net/');
	
	/** The URL of the YAPB project */
	define('GRAIN_YAPB_URL', 'http://wordpress.org/extend/plugins/yet-another-photoblog/');

	// set the extended version
	if( defined("GRAIN_THEME_VERSION_EXTENDED") && GRAIN_THEME_VERSION_EXTENDED != "" )  {
		/** The version of the theme */
		define('GRAIN_THEME_VERSION', GRAIN_THEME_VERSION_BASE." ".GRAIN_THEME_VERSION_EXTENDED);
	} else {
		define('GRAIN_THEME_VERSION', GRAIN_THEME_VERSION_BASE);
	}

	/* include development build information */
	if( file_exists(TEMPLATEPATH."/func/moonsugar.php") ) {
		@require_once(TEMPLATEPATH."/func/moonsugar.php");
		if(!defined("GRAIN_THEME_VERSION_DEVBUILD")) define('GRAIN_THEME_VERSION_DEVBUILD', true);
		// Set header
		if( !headers_sent() ) header("X-Grain-Devbuild: R".GRAIN_THEME_VERSION_REVISION);
	}
	else {
		if(!defined("GRAIN_THEME_VERSION_DEVBUILD")) define('GRAIN_THEME_VERSION_DEVBUILD', false);
	}
	
	// Add version response header. Used for version determination in case of support requests.
	if( !headers_sent() && !defined("GRAIN_NO_VERSIONRESPONSE")) header("X-Grain-Version: ".GRAIN_THEME_VERSION);
	if( !headers_sent() ) { global $yapb; header("X-Yapb-Version: ".$yapb->pluginVersion); }
	
	/**
	 * grain_getgrainfvlink() - Gets the HTML markup for the footer, containing the Version of Grain
	 *
	 * @since 0.3
	 * @return string HTML markup containing the current version of Grain
	 */
	function grain_getgrainfvlink() {
		$grainName = "Grain" . (GRAIN_THEME_VERSION_DEVBUILD?" ".GRAIN_THEME_VERSION:"");
		$grainTitle = "Grain" . (GRAIN_THEME_VERSION_DEVBUILD?" (development".(defined("GRAIN_THEME_VERSION_REVISION")?" R".GRAIN_THEME_VERSION_REVISION:"").")":"");
		$grainURL = '<a class="grain" href="'.GRAIN_THEME_URL.'" title="'.$grainTitle.'">'.$grainName.'</a>';
		return $grainURL;
	}
	
	/**
	 * grain_version() - Gets a string containing the current version of Grain
	 *
	 * On development builds, "(dev)" is appended.
	 *
	 * @since 0.3
	 * @return string The current version of Grain
	 */
	function grain_version() 
	{
		$version = GRAIN_THEME_VERSION;
		iF(GRAIN_THEME_VERSION_DEVBUILD) $version .= ' (dev)';
		return $version;
	}

?>