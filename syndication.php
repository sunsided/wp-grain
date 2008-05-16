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

	/* includes */

	@require_once(TEMPLATEPATH . '/functions.syndication.php');

	/* Hooks */
	
	add_action('init', 'grain_initialize_syndication');

	/* globals */
	
	$grain_syndication_list = array();

	/* functions */

	function grain_add_to_syndication( $text, $title, $url, $show_in_footer, $button_url = '' ) {
		global $grain_syndication_list;

		$object = array(
			'text' => $text,
			'title' => $title,
			'url' => $url,
			'button_url' => $button_url,
			'in_footer' => $show_in_footer );

		array_push( $grain_syndication_list, $object );
	}

	function grain_flat_syndication( $delimiter = '/' ) {
		global $grain_syndication_list;
		$i = 0; $output = '';
		$count = count( $grain_syndication_list );
		foreach( $grain_syndication_list as $item ) {
			$output .= '<div class="syndication-button" id="synd-'.(++$i).'"><a alt="'.$item['title'].'" href="'.$item['url'].'">'.$item['text'].'</a></div>';
			if( $i < $count ) $output .= ' ' . $delimiter . ' ';
		}
		return $output;
	}

	function grain_sidebar_syndication() {
		global $grain_syndication_list;
		$i = 0; $output = '';
		$count = count( $grain_syndication_list );
		foreach( $grain_syndication_list as $item ) {
			$output .= '<li id="list-synd-'.(++$i).'"><a alt="'.$item['title'].'" href="'.$item['url'].'"><img class="syndbutton" src="'.$item['button_url'].'" alt="'.$item['text'].'" title='.$item['title'].' border="0" /></a></li>';
			if( $i < $count ) $output .= ' ' . $delimiter . ' ';
		}
		return $output;
	}

?>