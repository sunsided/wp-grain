<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));
		
	// image sizes, ...
	$GrainOpt->defineValueOpt('GRAIN_MAX_IMAGE_WIDTH', "max_img_width", 800, FALSE);
	$GrainOpt->defineValueOpt('GRAIN_MAX_IMAGE_HEIGHT', "max_img_height", 533, FALSE);
	$GrainOpt->defineValueOpt( 'GRAIN_WHOOPS_WIDTH', 'whoops_image_width', 800, FALSE );
	$GrainOpt->defineValueOpt( 'GRAIN_WHOOPS_HEIGHT', 'whoops_image_height', 533, FALSE );		
	$GrainOpt->defineStringOpt( 'GRAIN_WHOOPS_URL', 'whoops_image_url', GRAIN_TEMPLATE_DIR.'/images/whoops.png', FALSE );
		
	// menu and navigation
	$GrainOpt->defineValueOpt("GRAIN_INFOPAGE_ID", "infopage_id", -1 );
	$GrainOpt->defineStringOpt("GRAIN_MNU_PERMALINK_TEXT", "menu_permalink_text", "#", TRUE );
	$GrainOpt->defineFlagOpt("GRAIN_MNU_PERMALINK_VISIBLE", "menu_permalink_visible", FALSE );
	$GrainOpt->defineFlagOpt("GRAIN_MNU_RANDOM_VISIBLE", "menu_random_visible", TRUE );
	$GrainOpt->defineFlagOpt("GRAIN_MNU_NEWEST_VISIBLE", "menu_newest_visible", TRUE );
	$GrainOpt->defineFlagOpt("GRAIN_MNU_INFO_VISIBLE", "menu_info_visible", FALSE );
	$GrainOpt->defineFlagOpt("GRAIN_NAV_BIDIR_ENABLED", "nav_bidir_enabled", TRUE );
	
	// navigation bar positions
	define('GRAIN_IS_HEADER', 'header');
	define('GRAIN_IS_BODY_BEFORE', 'body_before');
	define('GRAIN_IS_BODY_AFTER', 'body_after');	
	$GrainOpt->defineStringOpt("GRAIN_NAVBAR_LOCATION", "nav_navbar_location", GRAIN_IS_HEADER, FALSE );
	
	// flat syndication
	$GrainOpt->defineStringOpt("GRAIN_SYND_FLAT_DELIMITER", "synd_flat_delimiter", "/", TRUE );
	$GrainOpt->defineFlagOpt("GRAIN_SYND_FLAT_ENABLED", "synd_flat_enabled", FALSE );
	
	// sidebar
	$GrainOpt->defineFlagOpt( 'GRAIN_SDBR_SYND_ENABLED', 'sdbr_syndication', FALSE );
	$GrainOpt->defineFlagOpt( 'GRAIN_SDBR_MOSTCOMM_ENABLED', 'sdbr_mc_enabled', TRUE );
	$GrainOpt->defineValueOpt( 'GRAIN_SDBR_MOSTCOMM_COUNT', 'sdbr_mc_count', 10, FALSE );
	$GrainOpt->defineFlagOpt( 'GRAIN_SDBR_META_ENABLED', 'sdbr_meta_enabled', TRUE );
	$GrainOpt->defineFlagOpt( 'GRAIN_SDBR_BLOGROLL_ENABLED', 'sdbr_blogroll_enabled', TRUE );
	$GrainOpt->defineFlagOpt( 'GRAIN_SDBR_CALENDAR_ENABLED', 'sdbr_calendar_enabled', TRUE );
	
	// 2nd language
	$GrainOpt->defineFlagOpt( 'GRAIN_2NDLANG_ENABLED', 'feature_2ndl_enabled', FALSE );
	$GrainOpt->defineStringOpt( 'GRAIN_2NDLANG_TAG', 'feature_2ndl_tag', "grain_2nd_title", FALSE );
	$GrainOpt->defineFlagOpt( 'GRAIN_AUTOLOCALE_ENABLED', 'autolocale_enabled', FALSE );
	
	// feeds
	$GrainOpt->defineFlagOpt( 'GRAIN_FEED_ATOM_ENABLED', 'feed_atom_enabled', FALSE );
	
	// archive and mosaic
	$GrainOpt->defineFlagOpt( 'GRAIN_MOSAIC_ENABLED', 'mosaic_enabled', FALSE );
	$GrainOpt->defineStringOpt( 'GRAIN_MOSAIC_LINKTITLE', 'mosaic_linktitle', __("Mosaic", "grain"), TRUE );
	$GrainOpt->defineValueOpt( 'GRAIN_MOSAIC_COUNT', 'mosaic_count', 50, TRUE );
	$GrainOpt->defineFlagOpt( 'GRAIN_MOSAIC_DISPLAY_YEARS', 'mosaic_years', FALSE );
	$GrainOpt->defineValueOpt( 'GRAIN_MOSAIC_PAGEID', 'mosaicpage_id', -1 );
	
	// extended info page
	$GrainOpt->defineFlagOpt( 'GRAIN_EXTENDEDINFO_ENABLED', 'extended_comments', TRUE );

	// copyright and creative commons
	$GrainOpt->defineValueOpt( 'GRAIN_COPYRIGHT_END_OFFSET', 'copyright_end_year_offset', 0 );
	$GrainOpt->defineStringOpt( 'GRAIN_COPYRIGHT_END_YEAR', 'copyright_end_year', "%", FALSE );
	$GrainOpt->defineStringOpt( 'GRAIN_COPYRIGHT_START_YEAR', 'copyright_start_year', "%", FALSE );
	$GrainOpt->defineFlagOpt( 'GRAIN_COPYRIGHT_CC_ENABLED', 'cc_enabled', FALSE );
	$GrainOpt->defineFlagOpt( 'GRAIN_CC_RDF_FEED', 'cc_rdf_feed', FALSE );
	$GrainOpt->defineStringOpt( 'GRAIN_COPYRIGHT_HOLDER', 'copyright_holder', __("(Your name)", "grain"), FALSE );
	$GrainOpt->defineStringOpt( 'GRAIN_COPYRIGHT_HOLDER_HTML', 'copyright_holder_markup', __("<em>(Your name)</em>", "grain"), TRUE );

	// CC complex
	$GrainOpt->defineStringOpt( 'GRAIN_COPYRIGHT_CC_CODE', 'cc_code',
'<!--Creative Commons License-->
<a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/2.0/'.grain_get_base_locale().'/">
	<img alt="Creative Commons License" border="0" src="http://creativecommons.org/images/public/somerights20.png"/>
</a>
<br />
This content is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/2.0/">Creative Commons license</a>.
<!--/Creative Commons License-->
', TRUE );
	$GrainOpt->defineStringOpt( 'GRAIN_COPYRIGHT_CC_RDF', 'cc_rdf', 
'<rdf:RDF xmlns="http://web.resource.org/cc/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">
	<Work rdf:about="">
		<license rdf:resource="http://creativecommons.org/licenses/by-nc-nd/2.0/" />
		<dc:type rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
	</Work>
	<License rdf:about="http://creativecommons.org/licenses/by-nc-nd/2.0/">
		<permits rdf:resource="http://web.resource.org/cc/Reproduction"/>
		<permits rdf:resource="http://web.resource.org/cc/Distribution"/>
		<requires rdf:resource="http://web.resource.org/cc/Notice"/>
		<requires rdf:resource="http://web.resource.org/cc/Attribution"/>
		<prohibits rdf:resource="http://web.resource.org/cc/CommercialUse"/>
	</License>
</rdf:RDF>', TRUE );
	
	// content switches
	$GrainOpt->defineFlagOpt( 'GRAIN_EXIF_VISIBLE', 'exif_visible', TRUE );
	$GrainOpt->defineFlagOpt( 'GRAIN_CONTENT_PERMALINK_VISIBLE', 'content_permalink_visible', TRUE );
	$GrainOpt->defineFlagOpt( 'GRAIN_CONTENT_ENFORCE_INFO', 'content_enforce_info', TRUE );
	$GrainOpt->defineFlagOpt( 'GRAIN_CONTENT_COMMENTS_HINT', 'content_comments_hint', TRUE );
	$GrainOpt->defineFlagOpt( 'GRAIN_CONTENT_DATES', 'content_dates_enabled', TRUE );
	$GrainOpt->defineFlagOpt( 'GRAIN_CONTENT_CATEGORIES', 'content_categories_enabled', TRUE );
	$GrainOpt->defineFlagOpt( 'GRAIN_CONTENT_TAGS', 'content_tags_enabled', TRUE );
	
	// styling and stuff
	$GrainOpt->defineFlagOpt( 'GRAIN_EYECANDY_MOOFX', 'eyecandy_moofx', TRUE );
	$GrainOpt->defineFlagOpt( 'GRAIN_EYECANDY_REFLECTION_ENABLED', 'eyecandy_moo_reflection', FALSE );
	$GrainOpt->defineFlagOpt( 'GRAIN_EYECANDY_GRAVATARS_ENABLED', 'eyecandy_gravatars_enabled', FALSE );
	$GrainOpt->defineFlagOpt( 'GRAIN_EYECANDY_GRAVATARS_LINKED', 'eyecandy_gravatars_linked', FALSE );
	$GrainOpt->defineValueOpt( 'GRAIN_EYECANDY_GRAVATAR_SIZE', 'eyecandy_gravatar_size', 40 );
	$GrainOpt->defineStringOpt( 'GRAIN_EYECANDY_GRAVATAR_ALTERNATE', 'eyecandy_gravatar_alternate_uri', GRAIN_TEMPLATE_DIR.'/images/gravatar.png', FALSE );
	$GrainOpt->defineStringOpt( 'GRAIN_EYECANDY_GRAVATAR_RATING', 'eyecandy_gravatar_rating', "G" );
	
	$GrainOpt->defineFlagOpt( 'GRAIN_EYECANDY_COOLPB_ENABLED', 'eyecandy_use_coolpb_button', FALSE );
	$GrainOpt->defineStringOpt( 'GRAIN_EYECANDY_COOLPB_URI', 'eyecandy_coolpb_uri', 'http://www.photoblogs.org/profile/defx.de/', FALSE );
	$GrainOpt->defineFlagOpt( 'GRAIN_EYECANDY_PBORG_BOOKMARK_ENABLED', 'eyecandy_pborg_bookmark_enabled', FALSE );
	$GrainOpt->defineStringOpt( 'GRAIN_EYECANDY_PBORG_URI', 'eyecandy_pborg_uri', 'http://www.coolphotoblogs.com/?do=profile&id=1324', FALSE );
	
	$GrainOpt->defineFlagOpt( 'GRAIN_EYECANDY_FADER', 'eyecandy_use_fader', TRUE );
	$GrainOpt->defineFlagOpt( 'GRAIN_EYECANDY_USE_MOOTIPS', 'eyecandy_use_mootips', FALSE );
	$GrainOpt->defineFlagOpt( 'GRAIN_EYECANDY_USE_SLIDE', 'eyecandy_use_slide', FALSE );
	
	$GrainOpt->defineStringOpt( 'GRAIN_STYLE_OVERRIDE', "style_override_file", "style.override.css", FALSE );
	
	$GrainOpt->defineStringOpt( 'GRAIN_DTFMT_DAILYARCHIVE', 'dtfmt_dailyarchive', __("F jS, Y", "grain"), FALSE );
	$GrainOpt->defineStringOpt( 'GRAIN_DTFMT_MONTHLYARCHIVE', 'dtfmt_monthlyarchive', __("F, Y", "grain"), FALSE );
	$GrainOpt->defineStringOpt( 'GRAIN_DTFMT_PUBLISHED', 'dtfmt_published', __("j. F Y, H:i", "grain"), FALSE );
	$GrainOpt->defineStringOpt( 'GRAIN_DFMT_COMMENTS', 'dtfmt_comment_date', __("F jS, Y", "grain"), FALSE );
	$GrainOpt->defineStringOpt( 'GRAIN_TFMT_COMMENTS', 'dtfmt_comment_time', __("H:i", "grain"), FALSE );
	
	$GrainOpt->defineStringOpt( 'GRAIN_OPENTAGS_CATLIST', 'tags_open_catlist', __("Posted in: ", "grain"), TRUE);
	$GrainOpt->defineStringOpt( 'GRAIN_CLOSETAGS_CATLIST', 'tags_close_catlist', "", TRUE);
	$GrainOpt->defineStringOpt( 'GRAIN_OPENTAGS_TAGLIST', 'tags_open_taglist', __("Tagged with: ", "grain"), TRUE);
	$GrainOpt->defineStringOpt( 'GRAIN_CLOSETAGS_TAGLIST', 'tags_close_taglist', "", TRUE);
	$GrainOpt->defineStringOpt( 'GRAIN_TAGLIST_SEPARATOR', 'tags_separator', ", ", TRUE);
	$GrainOpt->defineStringOpt( 'GRAIN_CATLIST_SEPARATOR', 'cats_separator', ", ", TRUE);
	
	$GrainOpt->defineFlagOpt( 'GRAIN_POPUP_SHOW_THUMB', 'popup_show_thumb', TRUE);
	
	$GrainOpt->defineValueOpt( 'GRAIN_MOSAIC_THUMB_WIDTH', 'mosaic_thumb_width', 120);
	$GrainOpt->defineValueOpt( 'GRAIN_MOSAIC_THUMB_HEIGHT', 'mosaic_thumb_height', 90);
	$GrainOpt->defineValueOpt( 'GRAIN_POPUP_THUMB_WIDTH', 'popup_thumb_width', 200);
	$GrainOpt->defineValueOpt( 'GRAIN_POPUP_THUMB_HEIGHT', 'popup_thumb_height', 200);
	$GrainOpt->defineFlagOpt( 'GRAIN_POPUP_THUMB_STF', 'popup_thumb_stf', FALSE);
	$GrainOpt->defineFlagOpt( 'GRAIN_POPUP_JTC', 'popup_link_jtc', TRUE);

	$GrainOpt->defineFlagOpt( 'GRAIN_ARCHIVE_TOOLTIPS', 'archive_tooltips', TRUE);
	$GrainOpt->defineFlagOpt( 'GRAIN_COMMENTS_ENABLED', 'comments_enabled', TRUE);
	$GrainOpt->defineFlagOpt( 'GRAIN_COMMENTS_ON_EMPTY_ENABLED', 'comments_on_empty', FALSE);
	$GrainOpt->defineFlagOpt( 'GRAIN_EXCERPTONLY', 'excerptonly', FALSE);

	$GrainOpt->defineStringOpt( 'GRAIN_IMPRINT_URL', 'imprint_url', NULL, FALSE );

	// version
	$GrainOpt->defineStringOpt( 'GRAIN_VERSION_KEY', 'version', GRAIN_THEME_VERSION );

?>