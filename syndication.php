<?php
/*     
	This file is part of Grain Theme for WordPress.
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