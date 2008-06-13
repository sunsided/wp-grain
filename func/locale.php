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
		load_theme_textdomain('grain');
	}
	
	function grain_get_base_locale($locale=WPLANG) {
		// try full locale
		$loc = strtolower($locale);

		// test for country code
		$index = strpos( $loc, '_' );
		if( $index > 0 ) 
			return substr( $loc, 0, $index );
		
		return $locale;
	}

	function grain_switch_locale($locale=WPLANG) {
		putenv("LC_ALL=$locale");
		setlocale(LC_ALL, $locale);	
	}
	
	// tries to find a matching locale file based on a locale given
	// Examples; assuming the default locale (WPLANG) is "en_US"
	//		You enter		You have						it returns
	//		"de"			"de.mo"							"de"	
	//		"de"			"de_AT.mo"						"de_AT"
	//		"de"			"de_AT.mo" and "de_DE.mo"		"de_DE"
	//		"de_DE"			"de_AT.mo" and "de_DE.mo"		"de_DE"
	//		"de_AT"			"de_AT.mo" and "de_DE.mo"		"de_AT"
	//		"de_AT"			"de.mo" 						"de"
	//		"de_AT"			"fr.mo" 						"en_US"
	//		"de"			"fr.mo" 						"en_US"
	function grain_find_locale($locale, $fallback=WPLANG) {
		// test for a direct hit
		if( file_exists(TEMPLATEPATH."/$locale.mo") ) return $locale;
		// no direct hit; check for a specialized locale
		// get base locale
		$baselocale = grain_get_base_locale($locale);
		// lost all files of scheme xx_YY.mo
		$hit = null;
		$fullmatch = $baselocale."_".$baselocale;
		// walk through all files
		if ($handle = opendir(TEMPLATEPATH)) {
			while (false !== ($file = readdir($handle))) {
				// test pattern for "xx" and ".mo"
				if( substr($file, 0, 2) == $baselocale && substr($file, -3, 3) == ".mo") {
					// remember first hit
					if( $hit == null ) $hit = substr($file, 0, -3);
					// if it is a full hit, take it
					if( strtolower($hit) == $fullmatch ) {
						$hit = substr($file, 0, -3);
						break;
					}
				}
			}
		}
		return $hit?$hit:$fallback;
	}


?>