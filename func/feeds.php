<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));


	/* functions */

	function grain_feedembed_ccrdf() 
	{
		echo grain_cc_rdf();
	}
		
	/* icon embedding */

	function grain_embed_rss_icon() 
	{
		// get some values
		$iconTitle        = __("RSS Feed", "grain");
		$syndicationTitle = __("Syndication", "grain");
		$rssUrl           = get_bloginfo('rss2_url');
		
		// get thumbnail title
		$thumbnailTitle   = grain_thumbnail_title($iconTitle, $syndicationTitle);
		
		// print the line
		echo '<a title="'.$thumbnailTitle.'" href="'.$rssUrl.'"><img onMouseover="this.src=\''.GRAIN_TEMPLATE_DIR.'/images/rss-feed.gif\'" onMouseout="this.src=\''.GRAIN_TEMPLATE_DIR.'/images/rss-feed-low.gif\'" id="rss-feed" src="'.GRAIN_TEMPLATE_DIR.'/images/rss-feed-low.gif" alt="RSS feed icon" /></a>';
	}
	
	function grain_embed_atom_icon() 
	{
		// get some values
		$iconTitle        = __("Atom Feed", "grain");
		$syndicationTitle = __("Syndication", "grain");
		$atomUrl           = get_bloginfo('atom_url');
		
		// get thumbnail title
		$thumbnailTitle   = grain_thumbnail_title($iconTitle, $syndicationTitle);
		
		// print the line
		echo '<a title="'.$thumbnailTitle.'" href="'.$atomUrl.'"><img onMouseover="this.src=\''.GRAIN_TEMPLATE_DIR.'/images/atom-feed.gif\'" onMouseout="this.src=\''.GRAIN_TEMPLATE_DIR.'/images/atom-feed-low.gif\'"id="atom-feed" src="'.GRAIN_TEMPLATE_DIR.'/images/atom-feed-low.gif" alt="Atom feed icon" /></a>';
	}

?>