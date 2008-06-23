<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$

*//**

	Translation helper functions
	
	@package Grain Theme for WordPress
	@subpackage Localization
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

	/* Hooks */

	add_action('init', 'grain_load_locale');

	/* functions */

	/**
	 * grain_load_locale() - Loads the locale file matching WordPress' locale settings
	 *
	 * This function tries to find a matching .mo file in the ./lang/ subdirectory first
	 * and then falls back to the ./ (root) directory of the theme.
	 *
	 * @since 0.3	 
	 * @access private
	 * @param string $string A string
	 * @return string Input string without BR tags
	 */
	function grain_load_locale() {	
		// I18N support through GNU-Gettext files
		$locale = get_locale();
		$file = TEMPLATEPATH."/lang/$locale.mo";
		if( !file_exists($file ) ) $file = TEMPLATEPATH."/$locale.mo";
		load_textdomain("grain", $file );
	}
	
	/**
	 * grain_get_base_locale() - Gets the base locale from given locale
	 *
	 * This function extracts the base locale from a given locale, e.g. "de" for "de_DE"
	 *
	 * @since 0.2 
	 * @param string $locale Optional. A given locale (defaults to WPLANG)
	 * @return string The base locale
	 */
	function grain_get_base_locale($locale=WPLANG) {
		// try full locale
		$loc = strtolower($locale);

		// test for country code
		$index = strpos( $loc, '_' );
		if( $index > 0 ) 
			return substr( $loc, 0, $index );
		
		return $locale;
	}

	/**
	 * grain_switch_locale() - Loads a new loaded locale
	 *
	 * This function tries to find a matching .mo file in the ./lang/ subdirectory first
	 * and then falls back to the ./ (root) directory of the theme.
	 *
	 * @since 0.3
	 * @param string $locale Optional. The locale to switch to (defaults to WPLANG)
	 */
	function grain_switch_locale($locale=WPLANG) {
		global $wp_locale;
		$locale=apply_filters( 'locale', $locale );
		putenv("LC_ALL=$locale");
		setlocale(LC_ALL, $locale, grain_get_base_locale($locale));	
		$file = TEMPLATEPATH."/lang/$locale.mo";
		if( !file_exists($file) ) $file = TEMPLATEPATH."/$locale.mo";
		load_textdomain("grain", $file);
	}
	
	/**
	 * An array of cached .mo file names
	 *
	 * This value will be set upon a call to grain_find_locale()
	 *
	 * @see grain_find_locale()
	 * @global array $__grain_mo_cache
	 * @name $__grain_mo_cache
	 * @access private
	 */
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
	
	/**
	 * grain_find_locale() - Finds a locale file that matches a given locale string
	 *
	 * This function tries to find a matching .mo file for the $locale parameter 
	 * in the ./lang/ subdirectory first and then falls back to the ./ (root) directory of the theme.
	 * If no matching file was found there either, it falls back to the $fallback value.
	 *
	 * The results of this function are cached in the $__grain_mo_cache global variable
	 * to speed up access when testing multiple locales.
	 *
	 * <code>
	 * Examples; assuming the default locale (WPLANG) is "en_US"
	 *	  You enter      You have                    it returns
	 *	    "de"          "de.mo"                    "de"	
	 *	    "de"          "de_AT.mo"                 "de_AT"
	 *	    "de"          "de_AT.mo" and "de_DE.mo"  "de_DE"
	 *	    "de_DE"       "de_AT.mo" and "de_DE.mo"  "de_DE"
	 *	    "de_AT"       "de_AT.mo" and "de_DE.mo"  "de_AT"
	 *	    "de_AT"       "de.mo"                    "de"
	 *	    "de_AT"       "fr.mo"                    "en_US"
	 *	    "de"          "fr.mo"                    "en_US"
	 * </code>
	 *
	 * @since 0.3
	 * @access private
	 * @global $__grain_mo_cache The .mo file cache
	 * @param string $locale The locale for which a .mo file shall be searched
	 * @param string $fallback Optional. A fallback value to return. (defaults to WPLANG)
	 */
	function grain_find_locale($locale, $fallback=WPLANG) {
		// test for a direct hit
		if( file_exists(TEMPLATEPATH."/lang/$locale.mo") ) {
			grain_log("$locale is a direct hit", "Locale", FirePHP::INFO);
			return $locale;
		}
		#if( file_exists(TEMPLATEPATH."/$locale.mo") ) return $locale;
		// no direct hit; check for a specialized locale
		// get base locale
		$baselocale = grain_get_base_locale($locale);
		// lost all files of scheme xx_YY.mo
		$hit = null;
		$fullmatch = strtolower($baselocale."_".$baselocale);
		
		// get cached files
		global $__grain_mo_cache;
		if( $__grain_mo_cache === null ) {
			// create new cache
			$__grain_mo_cache = array();
		
			// walk through all files
			if ($handle = opendir(TEMPLATEPATH."/lang/")) {
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


	/**
	 * grain_get_acceptedlocale() - Gets and returns the best matching accepted locale from the request's HTTP_ACCEPT_LANGUAGE header
	 *
	 * This functions gets the values from the HTTP_ACCEPT_LANGUAGE request header,
	 * splits them, sorts them by weighting and then tries to find the best match from the list
	 * of known locales. If no match was found, a fallback value is returned.
	 *
	 * @since 0.3
	 * @access private
	 * @see grain_find_locale()
	 * @uses grain_find_locale() To find the best matching .mo for a given locale
	 * @param string $fallback Optional. The locale to fall back to if no acceptable HTTP_ACCEPT_LANGUAGE was found (defaults to WPLANG)
	 * @return string The best matching locale or the value $fallback
	 */
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
			$findings[$q] = $langs;
		}
		unset($results); unset($regex);
		
		// sort by weighting
		krsort($findings, SORT_NUMERIC);
		
		// test for existing locale files
		foreach( $findings as $weighting => $languages ) {
			// grain_find_locale
			foreach( $languages as $lang ) {		
				if(0 != ($i=strpos($lang, "-")) ) {
					$lang = substr($lang, 0, $i)."_".strtoupper(substr($lang, $i+1));
				}			
				$hit = grain_find_locale($lang, null);				
				if( !empty($hit) ) {			
					return $hit;
				}
			}
			
		}
		
		return $fallback;
	}

	/**
	 * grain_autolocale() - Performs the autlocale logic, if enabled
	 *
	 * If successful, this function tries to set a X-Grain-Autolocale response header with
	 * the loaded locale.
	 *
	 * @since 0.3
	 * @access private
	 * @global $GrainOpt Grain options
	 * @uses grain_get_acceptedlocale() To find the best matching locale
	 * @uses get_locale() To find the current locale
	 * @uses grain_switch_locale() To switch the locale
	 */
	function grain_autolocale() {
		global $GrainOpt;
		if( !$GrainOpt->is(GRAIN_AUTOLOCALE_ENABLED) ) return;
		$locale = grain_get_acceptedlocale(NULL);
		if( empty($locale) ) return;
		
		// no need to switrch if the locale is the same
		$system_locale = get_locale();
		if( !empty($system_locale) && $system_locale == $locale ) return;
		
		// switch
		grain_switch_locale($locale);
		if( !headers_sent() ) header("X-Grain-Autolocale: $locale");
	}

	// load locale
	grain_load_locale();

?>