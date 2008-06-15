<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/

	/* defines */

	define('GRAIN_THEME_VERSION_BASE', '0.3');
	define('GRAIN_THEME_URL', 'http://wp-grain.wiki.sourceforge.net/');
	define('GRAIN_YAPB_URL', 'http://wordpress.org/extend/plugins/yet-another-photoblog/');

	/* include development build information */
	if( file_exists(TEMPLATEPATH."/func/moonsugar.php") ) {
		@require_once(TEMPLATEPATH."/func/moonsugar.php");
		define('GRAIN_THEME_VERSION', GRAIN_THEME_VERSION_BASE." ".GRAIN_THEME_VERSION_EXTENDED);
		if(!defined("GRAIN_THEME_VERSION_DEVBUILD")) define('GRAIN_THEME_VERSION_DEVBUILD', true);
		// Set header
		if( !headers_sent() ) header("X-Grain-Devbuild: ".GRAIN_THEME_VERSION."/R".GRAIN_THEME_VERSION_REVISION);
	}
	else {
		define('GRAIN_THEME_VERSION', GRAIN_THEME_VERSION_BASE);
		if(!defined("GRAIN_THEME_VERSION_DEVBUILD")) define('GRAIN_THEME_VERSION_DEVBUILD', false);
	}

	// get's Grains footer version link
	function grain_getgrainfvlink() {
		$grainName = "Grain" . (GRAIN_THEME_VERSION_DEVBUILD?" ".GRAIN_THEME_VERSION:"");
		$grainTitle = "Grain" . (GRAIN_THEME_VERSION_DEVBUILD?" (development".(defined("GRAIN_THEME_VERSION_REVISION")?" R".GRAIN_THEME_VERSION_REVISION:"").")":"");
		$grainURL = '<a class="grain" href="'.GRAIN_THEME_URL.'" title="'.$grainTitle.'">'.$grainName.'</a>';
		return $grainURL;
	}

?>