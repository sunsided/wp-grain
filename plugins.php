<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id: paths.php 11 2008-05-18 23:50:00Z sunside $
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));


	/* definitions */

	if( file_exists(TEMPLATEPATH . '/iplugs/suite.0.1.php') ) @require_once(TEMPLATEPATH . '/iplugs/suite.0.1.php');
	if( file_exists(TEMPLATEPATH . '/iplugs/suite.0.2.php') ) @require_once(TEMPLATEPATH . '/iplugs/suite.0.2.php');

?>