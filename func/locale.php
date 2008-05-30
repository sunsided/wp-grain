<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

	/* Hooks */

	add_action('init', 'grain_load_locale');

	/* functions */

	function grain_load_locale() {	
		// I18N support through GNU-Gettext files
		load_theme_textdomain('grain', GRAIN_RELATIVE_PATH.'/po/');
	}
	
	function grain_get_base_locale() {
		// try full locale
		$loc = strtolower(WPLANG);

		// test for country code
		$index = strpos( $loc, '_' );
		if( $index > 0 ) 
			return substr( $loc, 0, $index );
		
		return WPLANG;
	}

?>