<?php
/*     
	This file is part of Grain Theme for WordPress.

    Grain Theme for WordPress is free software: you can redistribute it 
	and/or modify it under the terms of the GNU General Public License 
	as published by the Free Software Foundation, either version 3 of 
	the License, or (at your option) any later version.

    Foobar is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Grain Theme for WordPress.  
	If not, see <http://www.gnu.org/licenses/>.
	
	------------------------------------------------------------------
	File version: $Id$
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

	/* functions */

	function grain_mimic_previous_post_link($format='&laquo; %link', $link='%title', $in_same_cat = false, $excluded_categories = '') {
		global $post;

		if ( is_attachment() )
			$prev = & get_post($post->post_parent);
		else
			$prev = get_previous_post($in_same_cat, $excluded_categories);

		if ( !$prev )
			return;

		$addon = "";
			//if( grain_extended_comments() ) $addon = '?info=' . ((isset($_REQUEST['info']) && $_REQUEST['info'] == 'on') ? 'on' : 'off');

		$title = apply_filters('the_title', $prev->post_title, $prev);
		$string = '<a rel="prev" href="'.get_permalink($prev->ID).$addon.'">';
		$link = str_replace('%title', $title, $link);
		$link = $pre . $string . $link . '</a>';

		$format = str_replace('%link', $link, $format);

		return $format;
	}

	function grain_mimic_next_post_link($format='%link &raquo;', $link='%title', $in_same_cat = false, $excluded_categories = '') {
		global $post;	
	
		$next = get_next_post($in_same_cat, $excluded_categories);

		if ( !$next )
			return;

		$title = apply_filters('the_title', $next->post_title, $next);
		$string = '<a rel="next" href="'.get_permalink($next->ID).'">';
		$link = str_replace('%title', $title, $link);
		$link = $string . $link . '</a>';
		$format = str_replace('%link', $link, $format);

		return $format;
	}

?>