<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/

	/* defines */
	
	define('GRAIN_THEME_VERSION_EXTENDED', 'beta 1');
	define('GRAIN_THEME_VERSION_DEVBUILD', true);
	define('GRAIN_THEME_VERSION_REVISION', '$WCREV$'); // last SVN revision
	
	error_reporting(E_ALL ^ E_NOTICE);

	// enable error logging
	ini_set('log_errors', 'On');
	ini_set('error_log', TEMPLATEPATH . DIRECTORY_SEPARATOR . 'grain.log');
	ini_set('log_errors_max_len', 0);

	// do not display error(s) in browser
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);	
	
?>