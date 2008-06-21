<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$

*//**

	Development build information
	
	@package Grain Theme for WordPress
	@subpackage Version
*/

	/* defines */

	/** States that this is a development build */	
	define('GRAIN_THEME_VERSION_DEVBUILD', true);
	
	/** The current SVN release */	
	define('GRAIN_THEME_VERSION_REVISION', '$WCREV$'); // last SVN revision
	
	error_reporting(E_ALL ^ E_NOTICE);

	// enable error logging
	ini_set('log_errors', 'On');
	ini_set('error_log', TEMPLATEPATH . DIRECTORY_SEPARATOR . 'grain.log');
	ini_set('log_errors_max_len', 0);

	// do not display error(s) in browser
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);	
	
	// load firephp extension
	if( file_exists(TEMPLATEPATH . '/lib/FirePHPCore/fb.php') ) @require_once(TEMPLATEPATH . '/lib/FirePHPCore/fb.php');
	if( !function_exists("fb") ) { function fb() {} 	}
	
?>