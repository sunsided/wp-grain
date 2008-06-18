<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$

*//**

	Plugin loader
	
	@package Grain Theme for WordPress
	@subpackage Plugin Suite
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));


	/* definitions */

	if( file_exists(TEMPLATEPATH . '/iplugs/suite.0.1.php') ) @require_once(TEMPLATEPATH . '/iplugs/suite.0.1.php');
	if( file_exists(TEMPLATEPATH . '/iplugs/suite.0.2.php') ) @require_once(TEMPLATEPATH . '/iplugs/suite.0.2.php');

?>