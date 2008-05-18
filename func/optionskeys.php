<?php
/*     
	This file is part of Grain Theme for WordPress.

    Grain Theme for WordPress is free software: you can redistribute it 
	and/or modify it under the terms of the GNU General Public License 
	as published by the Free Software Foundation, either version 3 of 
	the License, or (at your option) any later version.

    Grain Theme for WordPress is distributed in the hope that it will 
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
	of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Grain Theme for WordPress.  
	If not, see <http://www.gnu.org/licenses/>.
	
	------------------------------------------------------------------
	File version: $Id$
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

	// define the main option key
	define( 'GRAIN_OPTIONS_KEY', 'grain_theme' );
	define( 'GRAIN_OPTION_KEY', GRAIN_OPTIONS_KEY );
	
	// version
	define( 'GRAIN_VERSION_KEY', 'version' );
	
	// menu and navigation
	define( 'GRAIN_INFOPAGE_ID', 'infopage_id' );
	define( 'GRAIN_MNU_PERMALINK_VISIBLE', 'menu_permalink_visible' );
	define( 'GRAIN_MNU_PERMALINK_TEXT', 'menu_permalink_text' );
	define( 'GRAIN_MNU_RANDOM_VISIBLE', 'menu_random_visible' );
	define( 'GRAIN_MNU_NEWEST_VISIBLE', 'menu_newest_visible' );
	define( 'GRAIN_MNU_INFO_VISIBLE', 'menu_info_visible' );
	define( 'GRAIN_NAV_BIDIR_ENABLED', 'nav_bidir_enabled' );
	
	define( 'GRAIN_NAVBAR_LOCATION', 'nav_navbar_location' );
	
	// flat syndication
	define( 'GRAIN_SYND_FLAT_DELIMITER', 'synd_flat_delimiter' );
	define( 'GRAIN_SYND_FLAT_ENABLED', 'synd_flat_enabled' );
	
	// sidebar
	define( 'GRAIN_SDBR_SYND_ENABLED', 'sdbr_syndication' );
	define( 'GRAIN_SDBR_MOSTCOMM_ENABLED', 'sdbr_mc_enabled' );
	define( 'GRAIN_SDBR_MOSTCOMM_COUNT', 'sdbr_mc_count' );
	define( 'GRAIN_SDBR_META_ENABLED', 'sdbr_meta_enabled' );
	define( 'GRAIN_SDBR_BLOGROLL_ENABLED', 'sdbr_blogroll_enabled' );
	define( 'GRAIN_SDBR_CALENDAR_ENABLED', 'sdbr_calendar_enabled' );
	
	// 2nd language
	define( 'GRAIN_2NDLANG_ENABLED', 'feature_2ndl_enabled' );
	define( 'GRAIN_2NDLANG_TAG', 'feature_2ndl_tag' );
	
	// feeds
	define( 'GRAIN_FEED_ATOM_ENABLED', 'feed_atom_enabled' );
	
	// archive and mosaic
	define( 'GRAIN_MOSAIC_ENABLED', 'mosaic_enabled' );
	define( 'GRAIN_MOSAIC_LINKTITLE', 'mosaic_linktitle' );
	define( 'GRAIN_MOSAIC_COUNT', 'mosaic_count' );
	define( 'GRAIN_MOSAIC_DISPLAY_YEARS', 'mosaic_years' );
	define( 'GRAIN_MOSAIC_PAGEID', 'mosaicpage_id' );
	
	// extended info page
	define( 'GRAIN_EXTENDEDINFO_ENABLED', 'extended_comments' );

	// copyright and creative commons
	define( 'GRAIN_COPYRIGHT_END_OFFSET', 'copyright_end_year_offset' );
	define( 'GRAIN_COPYRIGHT_END_YEAR', 'copyright_end_year' );
	define( 'GRAIN_COPYRIGHT_START_YEAR', 'copyright_start_year' );
	define( 'GRAIN_COPYRIGHT_CC_ENABLED', 'cc_enabled' );
	define( 'GRAIN_COPYRIGHT_CC_CODE', 'cc_code' );
	define( 'GRAIN_COPYRIGHT_CC_RDF', 'cc_rdf' );
	define( 'GRAIN_CC_RDF_FEED', 'cc_rdf_feed' );
	define( 'GRAIN_COPYRIGHT_HOLDER', 'copyright_holder' );
	define( 'GRAIN_COPYRIGHT_HOLDER_HTML', 'copyright_holder_markup' );
	
	// content switches
	define( 'GRAIN_EXIF_VISIBLE', 'exif_visible' );
	define( 'GRAIN_CONTENT_PERMALINK_VISIBLE', 'content_permalink_visible' );
	define( 'GRAIN_CONTENT_ENFORCE_INFO', 'content_enforce_info' );
	define( 'GRAIN_CONTENT_COMMENTS_HINT', 'content_comments_hint' );
	define( 'GRAIN_CONTENT_DATES', 'content_dates_enabled' );
	define( 'GRAIN_CONTENT_CATEGORIES', 'content_categories_enabled' );
	define( 'GRAIN_CONTENT_TAGS', 'content_tags_enabled' );
	
	// styling and stuff
	define( 'GRAIN_EYECANDY_MOOFX', 'eyecandy_moofx' );
	define( 'GRAIN_EYECANDY_REFLECTION_ENABLED', 'eyecandy_moo_reflection' );
	define( 'GRAIN_EYECANDY_GRAVATARS_ENABLED', 'eyecandy_gravatars_enabled' );
	define( 'GRAIN_EYECANDY_GRAVATAR_SIZE', 'eyecandy_gravatar_size' );
	define( 'GRAIN_EYECANDY_GRAVATAR_ALTERNATE', 'eyecandy_gravatar_alternate_uri' );
	define( 'GRAIN_EYECANDY_GRAVATAR_RATING', 'eyecandy_gravatar_rating' );
	
	define( 'GRAIN_EYECANDY_COOLPB_ENABLED', 'eyecandy_use_coolpb_button' );
	define( 'GRAIN_EYECANDY_COOLPB_URI', 'eyecandy_coolpb_uri' );
	define( 'GRAIN_EYECANDY_PBORG_BOOKMARK_ENABLED', 'eyecandy_pborg_bookmark_enabled' );
	define( 'GRAIN_EYECANDY_PBORG_URI', 'eyecandy_pborg_uri' );
	
	define( 'GRAIN_EYECANDY_FADER', 'eyecandy_use_fader' );
	define( 'GRAIN_EYECANDY_USE_MOOTIPS', 'eyecandy_use_mootips' );
	define( 'GRAIN_EYECANDY_USE_SLIDE', 'eyecandy_use_slode' );
	
	define( 'GRAIN_STYLE_OVERRIDE', 'style_override_file' );
	
	define( 'GRAIN_DTFMT_DAILYARCHIVE', 'dtfmt_dailyarchive');
	define( 'GRAIN_DTFMT_MONTHLYARCHIVE', 'dtfmt_monthlyarchive');
	define( 'GRAIN_DTFMT_PUBLISHED', 'dtfmt_published');
	define( 'GRAIN_DFMT_COMMENTS', 'dtfmt_comment_date');
	define( 'GRAIN_TFMT_COMMENTS', 'dtfmt_comment_time');
	
	define( 'GRAIN_OPENTAGS_CATLIST', 'tags_open_catlist');
	define( 'GRAIN_CLOSETAGS_CATLIST', 'tags_close_catlist');
	define( 'GRAIN_OPENTAGS_TAGLIST', 'tags_open_taglist');
	define( 'GRAIN_CLOSETAGS_TAGLIST', 'tags_close_taglist');
	define( 'GRAIN_TAGLIST_SEPARATOR', 'tags_separator' );
	define( 'GRAIN_CATLIST_SEPARATOR', 'cats_separator');
	
	define( 'GRAIN_POPUP_SHOW_THUMB', 'popup_show_thumb');
	
	define( 'GRAIN_MOSAIC_THUMB_WIDTH', 'mosaic_thumb_width');
	define( 'GRAIN_MOSAIC_THUMB_HEIGHT', 'mosaic_thumb_height');
	define( 'GRAIN_POPUP_THUMB_WIDTH', 'popup_thumb_width');
	define( 'GRAIN_POPUP_THUMB_HEIGHT', 'popup_thumb_height');
	define( 'GRAIN_POPUP_THUMB_STF', 'popup_thumb_stf');
	define( 'GRAIN_POPUP_JTC', 'popup_link_jtc');

	define( 'GRAIN_ARCHIVE_TOOLTIPS', 'archive_tooltips');
	define( 'GRAIN_COMMENTS_ENABLED', 'comments_enabled');

	define( 'GRAIN_IMPRINT_URL', 'imprint_url' );
	define( 'GRAIN_WHOOPS_URL', 'whoops_image_url' );
	define( 'GRAIN_WHOOPS_WIDTH', 'whoops_image_width' );
	define( 'GRAIN_WHOOPS_HEIGHT', 'whoops_image_height' );
	
?>