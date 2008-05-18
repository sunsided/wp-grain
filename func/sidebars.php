<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));


	/* define grain-specific sidebars */

	if ( function_exists('register_sidebar') ) {
		
		register_sidebar(array(
			'name' => 'Default Sidebar',
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h2 class="widgettitle sidebar_default">',
			'after_title' => '</h2>',
		));
		
		register_sidebar(array(
			'name' => 'Above Image',
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h2 class="widgettitle sidebar_above_image">',
			'after_title' => '</h2>',
		));
		
		register_sidebar(array(
			'name' => 'Below Image',
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h2 class="widgettitle sidebar_below_image">',
			'after_title' => '</h2>',
		));
		
		register_sidebar(array(
			'name' => 'Footer',
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h2 class="widgettitle sidebar_footer">',
			'after_title' => '</h2>',
		));
		
	}

?>