<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));


	/* definitions */

	define('GRAIN_RELATIVE_PATH', substr(TEMPLATEPATH, strlen(ABSPATH)));
	define('GRAIN_TEMPLATE_DIR', get_bloginfo('template_directory'));

?>