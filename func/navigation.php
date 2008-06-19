<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$

*//**

	GrainNavigation class
	
	@package Grain Theme for WordPress
	@subpackage Navigation
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

/* Options class */

	// define the main option key
	define( 'GRAIN_OPTIONS_KEY', 'grain_theme' );
	define( 'GRAIN_OPTION_KEY', GRAIN_OPTIONS_KEY );

	/**
	 * An instance of the Grain navigation class
	 * @global GrainNavigation $GrainNav
	 * @name $GrainNav
	 */
	$GrainNav = new GrainNavigation();

	/**
	 * GrainNavigation class
	 *
	 * @since 0.3
	 */
	class GrainNavigation {
		
		/**
		 * Initializes the class
		 * @access private
		 * @since 0.3
		*/
		function GrainNavigation() {
		}
		
			
	} // GrainOpt

?>