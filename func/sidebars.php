<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$

*//**

	Sidebar definitions
	
	@package Grain Theme for WordPress
	@subpackage Sidebars
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

	/* Helper: Content sidebars */

	/**
	 * grain_inject_sidebar_above() - Injects the sidebar above the image
	 *
	 * @since 0.2
	 */
	function grain_inject_sidebar_above() 
	{
		// Widgetized sidebar, if you have the plugin installed.
		if ( function_exists('dynamic_sidebar')) {
		?>
			<div id="content-sidebar-above" class="grain-sidebar">
				<ul>
		<?php
			dynamic_sidebar('Above Image');
		?>							
				</ul>
			</div>
		<?php
		}	
	}
	
	/**
	 * grain_inject_sidebar_below() - Injects the sidebar below the image
	 *
	 * @since 0.2
	 */
	function grain_inject_sidebar_below() {
		// Widgetized sidebar, if you have the plugin installed.
		if ( function_exists('dynamic_sidebar')) {
		?>
			<div id="content-sidebar-below" class="grain-sidebar">
				<ul>
		<?php
			dynamic_sidebar('Below Image');
		?>							
				</ul>
			</div>
		<?php
		}	
	}
	
	/**
	 * grain_inject_sidebar_footer() - Injects the sidebar in the footer
	 *
	 * @since 0.2
	 */
	function grain_inject_sidebar_footer() {
		// Widgetized sidebar, if you have the plugin installed.
		if ( function_exists('dynamic_sidebar')) {
		?>
			<div id="content-sidebar-footer" class="grain-sidebar">
				<ul>
		<?php
			dynamic_sidebar('Footer');
		?>							
				</ul>
			</div>
		<?php
		}	
	}


?>