<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$	
*/
	
/* Warm up engine */	

	@require_once(TEMPLATEPATH . '/func/version.php');

/* Helper functions */

	@require_once(TEMPLATEPATH . '/func/littlehelpers.php');
	@require_once(TEMPLATEPATH . '/func/paths.php');
	@require_once(TEMPLATEPATH . '/func/locale.php');
	@require_once(TEMPLATEPATH . '/func/options.php');
	@require_once(TEMPLATEPATH . '/func/upgrade.php');

/* fire autolocale */

	grain_autolocale();

/* load plugin functions */
	
	@require_once(TEMPLATEPATH . '/func/plugins.php');

/* Grain Syndication */

	@require_once(TEMPLATEPATH . '/func/syndication.php');

/* HTML header */

	@require_once(TEMPLATEPATH . '/func/stylesheets.php');	
	@require_once(TEMPLATEPATH . '/func/javascript.php');	
	@require_once(TEMPLATEPATH . '/func/header.php');

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

/* Creative Commons functions */

	@require_once(TEMPLATEPATH . '/func/creativecommons.php');

/* Feed tweaking */

	@require_once(TEMPLATEPATH . '/func/feeds.php');

/* Content helper files */

	@require_once(TEMPLATEPATH . '/func/content.php');

/* Grain admin panel following */

	@require_once(TEMPLATEPATH . '/admin/menu.php');

/* Sidebars */

	@require_once(TEMPLATEPATH . '/func/sidebars.php');

?>