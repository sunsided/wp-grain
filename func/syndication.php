<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$

*//**

	Syndication functions
	
	@package Grain Theme for WordPress
	@subpackage Syndication
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

	/* includes */

	@require_once(TEMPLATEPATH . '/functions.syndication.php');

	/* Hooks */
	
	add_action('init', 'grain_initialize_syndication');

	/* globals */
	
	/**
	 * An array of syndication links
	 *
	 * This value will be set upon a call to grain_add_to_syndication()
	 *
	 * @see grain_add_to_syndication()
	 * @global array $grain_syndication_list
	 * @name $grain_syndication_list
	 * @access private
	 */
	$grain_syndication_list = array();

	/* functions */

	/**
	 * grain_add_to_syndication() - Adds a syndication link
	 *
	 * This function is used to build the syndication list
	 *
	 * @since 0.2
	 * @param string $text 				The text ("name") of the syndication link
	 * @param string $title 				The title that shall be applied to the link. This can be more specific than $text.
	 * @param string $url 				The URL to the syndicated website
	 * @param bool $show_in_footer 		Optional. Set to TRUE if the link should be shown in the flat syndication
	 * @param string $button_url 		Optional. A link to an image that should be displayed instead of the link when not in flat syndication.
	 */
	function grain_add_to_syndication( $text, $title, $url, $show_in_footer = FALSE, $button_url = '' ) {
		global $grain_syndication_list;

		$object = array(
			'text' => $text,
			'title' => $title,
			'url' => $url,
			'button_url' => $button_url,
			'in_footer' => $show_in_footer );

		array_push( $grain_syndication_list, $object );
	}

	/**
	 * grain_flat_syndication() - Builds the syndication list for the footer
	 *
	 * This function is used to build the flat syndication fromt he values defined by
	 * former calls to grain_add_to_syndication()
	 *
	 * @since 0.2
	 * @see grain_add_to_syndication()
	 * @param string $delimiter 		Optional. The string used to delimit the syndication links
	 */
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

	/**
	 * grain_sidebar_syndication() - Builds the syndication list for the sidebar
	 *
	 * This function is used to build the flat syndication fromt he values defined by
	 * former calls to grain_add_to_syndication()
	 *
	 * @since 0.2
	 * @see grain_add_to_syndication()
	 */
	function grain_sidebar_syndication() {
		global $grain_syndication_list;
		$i = 0; $output = '';
		$count = count( $grain_syndication_list );
		$delimiter = ""; // @todo: Set delimiter for sidebar syndication 
		foreach( $grain_syndication_list as $item ) {
			$output .= '<li id="list-synd-'.(++$i).'"><a alt="'.$item['title'].'" href="'.$item['url'].'"><img class="syndbutton" src="'.$item['button_url'].'" alt="'.$item['text'].'" title='.$item['title'].' border="0" /></a></li>';
			if( $i < $count ) $output .= ' ' . $delimiter . ' ';
		}
		return $output;
	}

?>