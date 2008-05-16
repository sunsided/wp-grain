<?php
	
/* Warm up engine */	

	define('GRAIN_THEME_VERSION', '0.2');

/* Paths helper */

	@require_once(TEMPLATEPATH . '/func/paths.php');

/* Options helper */

	@require_once(TEMPLATEPATH . '/func/options.php');

/* upgrade helper */

	@require_once(TEMPLATEPATH . '/func/upgrade.php');

/* load locale functions */
	
	@require_once(TEMPLATEPATH . '/func/locale.php');

/* load plugin functions */
	
	@require_once(TEMPLATEPATH . '/iplugs/suite.0.1.php');
	@require_once(TEMPLATEPATH . '/iplugs/suite.0.2.php');

/* Grain Syndication */

	@require_once(TEMPLATEPATH . '/func/syndication.php');

/* Navigation link functions */

	@require_once(TEMPLATEPATH . '/func/navlinks.php');

/* template filters and action */
	
	@require_once(TEMPLATEPATH . '/func/actions.php');
	@require_once(TEMPLATEPATH . '/func/filters.php');
	@require_once(TEMPLATEPATH . '/func/template_filters.php');

/* Template functions */

	@require_once(TEMPLATEPATH . '/func/template.php');

/* Comment functions */

	@require_once(TEMPLATEPATH . '/func/comments.php');	

/* Image helper functions */

	@require_once(TEMPLATEPATH . '/func/image.php');

/* Creative Commons functions following */

	@require_once(TEMPLATEPATH . '/func/creativecommons.php');

/* Grain admin panel following */

	@require_once(TEMPLATEPATH . '/func/feeds.php');

/* Grain admin panel following */

	@require_once(TEMPLATEPATH . '/func/content.php');

/* Grain admin panel following */

	@require_once(TEMPLATEPATH . '/admin/menu.php');

/* General wordpress tweaking */

	if ( function_exists('register_sidebar') )
		register_sidebar(array(
			'name' => 'Default Sidebar',
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h2 class="widgettitle">',
			'after_title' => '</h2>',
		));
		
	if ( function_exists('register_sidebar') )
		register_sidebar(array(
			'name' => 'Above Image',
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h2 class="widgettitle">',
			'after_title' => '</h2>',
		));
		
	if ( function_exists('register_sidebar') )
		register_sidebar(array(
			'name' => 'Below Image',
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h2 class="widgettitle">',
			'after_title' => '</h2>',
		));
		
	if ( function_exists('register_sidebar') )
		register_sidebar(array(
			'name' => 'Footer',
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h2 class="widgettitle">',
			'after_title' => '</h2>',
		));

?>