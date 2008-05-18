<?php
/*     
	This file is part of Grain Theme for WordPress.

    Grain Theme for WordPress is free software: you can redistribute it 
	and/or modify it under the terms of the GNU General Public License 
	as published by the Free Software Foundation, either version 3 of 
	the License, or (at your option) any later version.

    Grain Theme for WordPress is distributed in the hope that it will 
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
	of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Grain Theme for WordPress.  
	If not, see <http://www.gnu.org/licenses/>.
	
	------------------------------------------------------------------
	File version: $Id$
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

/* Additional filters */

	@require_once(TEMPLATEPATH . '/func/filters.php');

/* Template filtering */
	
	add_filter(GRAIN_COPYRIGHT_START_YEAR, 'grain_fltr_current_year');
	add_filter(GRAIN_COPYRIGHT_END_YEAR, 'grain_fltr_current_year');
	
	function grain_fltr_current_year($value) {
		return str_replace('%', date('Y'), $value);
	}

/* Template Hooks */

	add_action('wp_head', 'grain_inject_fader', '', 0);
	function grain_inject_fader_hook() {
		grain_inject_fader();
	}
	
	add_action('wp_head', 'grain_inject_moofx_tooltips', '', 0);
	add_action('wp_head', 'grain_inject_moofx_slide', '', 0);
	
/* Feed hooks */

	if( grain_ccrdf_feed_embed() ) {
		add_action('atom_head', 'grain_feedembed_ccrdf');
		add_action('rss_head', 'grain_feedembed_ccrdf');
		add_action('rss2_head', 'grain_feedembed_ccrdf');
		// add_action('rdf_header', 'grain_feedembed_ccrdf');		
	}

?>