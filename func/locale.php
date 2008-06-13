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
	
	// cached mo file names
	$__grain_mo_cache = null;
	
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
		$fullmatch = strtolower($baselocale."_".$baselocale);
		
		// get cached files
		global $__grain_mo_cache;
		if( !$__grain_mo_cache ) {
			// create new cache
			$__grain_mo_cache = array();
		
			// walk through all files
			if ($handle = opendir(TEMPLATEPATH)) {
				while (false !== ($file = readdir($handle))) {
					// test pattern for "xx" and ".mo"
					if( substr($file, -3, 3) == ".mo") {
						$__grain_mo_cache[] = $file;
						if( substr($file, 0, 2) != $baselocale ) continue;
						
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
		}
		// use cached files
		else {
			foreach($__grain_mo_cache as $file) {
				// test pattern for "xx" and ".mo"
				if( substr($file, 0, 2) != $baselocale ) continue;
				
				// remember first hit
				if( $hit == null ) $hit = substr($file, 0, -3);
				// if it is a full hit, take it
				if( strtolower($hit) == $fullmatch ) {
					$hit = substr($file, 0, -3);
					break;
				}
			}
		}
		
		return $hit?$hit:$fallback;
	}


	// tries to find a locale from the HTTP_ACCEPT_LANGUAGE header
	// that matches an existing locale file in the theme directory
	function grain_get_acceptedlocale($fallback=WPLANG) {
		// de-de,de;q=0.8,en-us;q=0.5,en;q=0.3
		// --> de-de,de;q=0.8
		// --> en-us;q=0.5
		// --> en;q=0.3
		$accepted = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
		if( empty($accepted) ) return $fallback;
		
		// find the next separator
		// de-de,de;q=0.8,en-us;q=0.5,en;q=0.3
		$de = "([a-zA-Z]{2})";
		$deDE = "($de(-$de)?)";
		$langblock = "(?<langs>$deDE(,$deDE)*)";
		$weighted = "(?<block>$langblock;q=(?<q>\d\.\d))";
		$regex = "/".$weighted."/";

		// fdnd the pairs
		$results = array();
		preg_match_all( $regex, $accepted, $results, PREG_SET_ORDER );
		
		// loop through each set
		$findings = array();
		for($i=0; $i<count($results); $i++) {
			$q = $results[$i]["q"];
			$langs = explode( ",", $results[$i]["langs"] );
			$langs = str_replace("-", "_", $langs);
			$findings[$q] = $langs;
		}
		unset($results); unset($regex);
		
		// sort by weighting
		krsort($findings, SORT_NUMERIC);
		
		// test for existing locale files
		foreach( $findings as $weighting => $languages ) {
			// grain_find_locale
			foreach( $languages as $lang ) {
				$hit = grain_find_locale($lang, null);
				if( $hit ) {
					return $hit;
				}
			}
			
		}
		
		return $fallback;
	}

?>