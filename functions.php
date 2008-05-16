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