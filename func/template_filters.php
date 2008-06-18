<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$

*//**

	Template functions
	
	@package Grain Theme for WordPress
	@subpackage Filtering
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

/* Additional filters */

	@require_once(TEMPLATEPATH . '/func/filters.php');

/* Template filtering */
	
	add_filter(GRAIN_COPYRIGHT_START_YEAR, 'grain_fltr_current_year');
	add_filter(GRAIN_COPYRIGHT_END_YEAR, 'grain_fltr_current_year');
	
	/**
	 * grain_fltr_current_year() - Filter that processes a date string
	 *
	 * Replaces "%" with the current year
	 *
	 * @access internal
	 * @param string $value The date to be filtered
	 * @return string A filtered date string
	 */
	function grain_fltr_current_year($value) {
		return str_replace('%', date('Y'), $value);
	}

/* Template Hooks */

	add_action('wp_head', 'grain_inject_fader', '', 0);
	
	/**
	 * grain_inject_fader_hook() - Hook to inject the fader
	 *
	 * @access internal
	 * @deprecated
	 * @uses grain_inject_fader()
	 */
	function grain_inject_fader_hook() {
		grain_inject_fader();
	}
	
	add_action('wp_head', 'grain_inject_moofx_tooltips', '', 0);
	add_action('wp_head', 'grain_inject_moofx_slide', '', 0);
	
/* Feed hooks */

	if( $GrainOpt->getYesNo(GRAIN_CC_RDF_FEED) ) {
		add_action('atom_head', 'grain_feedembed_ccrdf');
		add_action('rss_head', 'grain_feedembed_ccrdf');
		add_action('rss2_head', 'grain_feedembed_ccrdf');
		// add_action('rdf_header', 'grain_feedembed_ccrdf');		
	}

?>