<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));
/* Option keys */

	@require_once(TEMPLATEPATH . '/func/optionskeys.php');

/* definitions */

	define('GRAIN_MAX_IMAGE_WIDTH', 800);
	define('GRAIN_MAX_IMAGE_HEIGHT', 533); // only applies in certain cases, such as panorama etc.

/* Globals */
	
	$grain_options = get_option( GRAIN_OPTIONS_KEY );

/* Options helper */

	function grain_getoption($tag, $apply_filter = TRUE, $default=null) {
		global $grain_options;
		global $grain_admin;	
		
		// apply the default
		$value = $default;
		
		// if the value exists, get it
		if(grain_option_isset($tag)) $value = $grain_options[$tag];
		
		// if there are filters to be applied, apply them
		if($apply_filter) $value = apply_filters($tag, $value);
		
		// return the value
		return $value;
	}

	function grain_setoption($tag, $value) {
		global $grain_options;
		$grain_options[$tag] = $value;
		update_option( GRAIN_OPTIONS_KEY, $grain_options );
	}

	function grain_getoption_for_output($tag, $default=null) {
		return attribute_escape(grain_getoption($tag, $default));
	}
	
	function grain_value_checkbox($value) {
		return isset($value) ? TRUE : FALSE;
	}
	
	function grain_value_isenabled($value) {	
		if( !isset($value) ) return FALSE;
		if( empty($value) ) return FALSE;
		if( $value === FALSE ) return FALSE;
		if( $value === 0 ) return FALSE;
		if( $value === '' ) return FALSE;
		if( $value === 'off' ) return FALSE;
		if( $value === 'false' ) return FALSE;
		if( $value === 'no' ) return FALSE;
		return TRUE;
	}
	
	function grain_option_isset($value) {
		global $grain_options;
		return array_key_exists($value, $grain_options );
	}
	
	function grain_getoption_yesno($tag, $default=null) {
		global $grain_options;
		if( !grain_option_isset($tag) ) return $default;
		return grain_value_isenabled($grain_options[$tag]) ? TRUE : FALSE;
	}

/* Shortcut functions helper */

	function grain_infopage_id( $apply_filter = TRUE, $default = 2 ) {
		return grain_getoption(GRAIN_INFOPAGE_ID, $apply_filter, $default);
	}

	function grain_permalink_enabled( $default=false) {
		return grain_getoption_yesno(GRAIN_MNU_PERMALINK_VISIBLE, $default);
	}

	function grain_permalink_text( $apply_filter = TRUE, $default = '#' ) {
		return grain_getoption(GRAIN_MNU_PERMALINK_TEXT, $apply_filter, $default);
	}

	function grain_random_enabled( $default = true ) {
		return grain_getoption_yesno(GRAIN_MNU_RANDOM_VISIBLE, $default);
	}

	function grain_newest_enabled( $default = false ) {
		return grain_getoption_yesno(GRAIN_MNU_NEWEST_VISIBLE, $default);
	}

	function grain_info_enabled( $default=false ) {
		return grain_getoption_yesno(GRAIN_MNU_INFO_VISIBLE, $default);
	}

	function grain_flat_delimiter( $apply_filter = TRUE, $default = '/' ) {
		return grain_getoption(GRAIN_SYND_FLAT_DELIMITER, $apply_filter, $default);
	}

	function grain_flat_syndication_enabled( $default=false) {
		return grain_getoption_yesno(GRAIN_SYND_FLAT_ENABLED, $default);
	}

	function grain_sidebar_syndication_enabled($default=false) {
		return grain_getoption_yesno(GRAIN_SDBR_SYND_ENABLED, $default);
	}

	function grain_sidebar_mc_enabled($default=true) {
		return grain_getoption_yesno(GRAIN_SDBR_MOSTCOMM_ENABLED, $default);
	}

	function grain_sidebar_meta_enabled($default=false) {
		$value = grain_getoption_yesno(GRAIN_SDBR_META_ENABLED, $default);
	}

	function grain_sidebar_blogroll_enabled($default=false) {
		return grain_getoption_yesno(GRAIN_SDBR_BLOGROLL_ENABLED, $default);
	}

	function grain_sidebar_calendar_enabled($default=false) {
		return grain_getoption_yesno(GRAIN_SDBR_CALENDAR_ENABLED, $default);
	}

	function grain_sidebar_mc_count( $apply_filter = TRUE, $default = 10) {
		return intval(grain_getoption(GRAIN_SDBR_MOSTCOMM_COUNT, $apply_filter, $default));
	}

	function grain_2ndlang_enabled($default=false) {
		return grain_getoption_yesno(GRAIN_2NDLANG_ENABLED, $default);
	}

	function grain_2ndlang_tag($apply_filter = TRUE, $default = 'grain_2nd_title') {
		return grain_getoption(GRAIN_2NDLANG_TAG, $apply_filter, $default);
	}

	function grain_atomfeed_enabled($default=false) {
		return grain_getoption_yesno(GRAIN_FEED_ATOM_ENABLED, $default);
	}

	function grain_mosaic_count($apply_filter = TRUE, $default = 50) {
		$value = intval(grain_getoption(GRAIN_MOSAIC_COUNT, $apply_filter, $default));
		if( $value <= 0 ) $value=$default;
		return $value;
	}

	function grain_mosaic_years($default=FALSE) {
		return grain_getoption_yesno(GRAIN_MOSAIC_DISPLAY_YEARS, $default);
	}

	function grain_mosaic_enabled($default=FALSE) {
		return grain_getoption_yesno(GRAIN_MOSAIC_ENABLED, $default);
	}

	function grain_mosaicpage_id( $apply_filter = TRUE, $default = 2 ) {
		return intval(grain_getoption(GRAIN_MOSAIC_PAGEID, $apply_filter, $default));
	}

	function grain_mosaicpage_title($apply_filter = TRUE, $default = '[~]') {
		return grain_getoption(GRAIN_MOSAIC_LINKTITLE, $apply_filter, $default);
	}

	function grain_extended_comments($default=true) {
		return grain_getoption_yesno(GRAIN_EXTENDEDINFO_ENABLED, $default);
	}

	function grain_bidir_nav_enabled($default=false) {
		return grain_getoption_yesno(GRAIN_NAV_BIDIR_ENABLED, $default);
	}

	function grain_copyright_end_year_offset($apply_filter = TRUE, $default=0) {
		return intval(grain_getoption(GRAIN_COPYRIGHT_END_OFFSET, $apply_filter, $default));
	}

	function grain_cc_enabled($default=false) {
		return grain_getoption_yesno(GRAIN_COPYRIGHT_CC_ENABLED, $default);
	}

	function grain_copyright( $apply_filter = TRUE, $default = '' ) {
		return grain_getoption(GRAIN_COPYRIGHT_HOLDER, $apply_filter, $default);
	}

	function grain_copyright_ex( $apply_filter = TRUE, $default = '' ) {
		return grain_getoption(GRAIN_COPYRIGHT_HOLDER_HTML, $apply_filter, $default);
	}

	function grain_copyright_start_year($apply_filter = TRUE, $default='%') {
		return grain_getoption(GRAIN_COPYRIGHT_START_YEAR, $apply_filter, $default);
	}
	
	function grain_copyright_end_year($apply_filter = TRUE, $default='%') {
		return grain_getoption(GRAIN_COPYRIGHT_END_YEAR, $apply_filter, $default);
	}
	
	function grain_copyright_end_year_ex($apply_filter = TRUE, $default='%') {
		// this one is here for historical reasons
		return grain_copyright_end_year($apply_filter, $default);
	}

	function grain_exif_visible($default=true) {
		return grain_getoption_yesno(GRAIN_EXIF_VISIBLE, $default);
	}
	
	function grain_show_content_permalink($default=true) {
		return grain_getoption_yesno(GRAIN_CONTENT_PERMALINK_VISIBLE, $default);
	}
	
	function grain_eyecandy_use_moofx($default=true) {
		return grain_getoption_yesno(GRAIN_EYECANDY_MOOFX, $default);
	}
	
	function grain_eyecandy_use_reflection($default=false) {
		return grain_getoption_yesno(GRAIN_EYECANDY_REFLECTION_ENABLED, $default);
	}
	
	function grain_eyecandy_use_gravatars($default=false) {
		return grain_getoption_yesno(GRAIN_EYECANDY_GRAVATARS_ENABLED, $default);
	}
	
	function grain_eyecandy_gravatar_size($apply_filter = TRUE, $default=40) {
		return intval(grain_getoption(GRAIN_EYECANDY_GRAVATAR_SIZE, $apply_filter, $default));
	}
	
	function grain_eyecandy_gravatar_alternate($apply_filter = TRUE, $default=null) {
		return grain_getoption(GRAIN_EYECANDY_GRAVATAR_ALTERNATE, $apply_filter, $default);
	}
	
	function grain_eyecandy_gravatar_rating($apply_filter = TRUE, $default='G') {
		return grain_getoption(GRAIN_EYECANDY_GRAVATAR_RATING, $apply_filter, $default);
	}
	
	function grain_eyecandy_use_coolpb_button($default=false) {
		return grain_getoption_yesno(GRAIN_EYECANDY_COOLPB_ENABLED, $default);
	}
	
	function grain_eyecandy_use_pborg_button($default=false) {
		return grain_getoption_yesno(GRAIN_EYECANDY_PBORG_BOOKMARK_ENABLED, $default);
	}
	
	function grain_eyecandy_pborg_uri($apply_filter = TRUE, $default='http://www.photoblogs.org/profile/defx.de/') {
		return grain_getoption(GRAIN_EYECANDY_PBORG_URI, $apply_filter, $default);
	}
	
	function grain_eyecandy_coolpb_uri($apply_filter = TRUE, $default='http://www.coolphotoblogs.com/?do=profile&id=1324') {
		return grain_getoption(GRAIN_EYECANDY_COOLPB_URI, $apply_filter, $default);
	}
	
	function grain_eyecandy_use_moofx_tips($default=false) {
		return grain_getoption_yesno(GRAIN_EYECANDY_USE_MOOTIPS, $default);
	}
	
	function grain_eyecandy_use_moofx_slide($default=false) {
		return grain_getoption_yesno(GRAIN_EYECANDY_USE_SLIDE, $default);
	}
	
	function grain_enforce_info($default=true) {
		return grain_getoption_yesno(GRAIN_CONTENT_ENFORCE_INFO, $default);
	}
	
	function grain_show_comments_hint($default=true) {
		return grain_getoption_yesno(GRAIN_CONTENT_COMMENTS_HINT, $default);
	}
	
	function grain_show_content_dates($default=true) {
		return grain_getoption_yesno(GRAIN_CONTENT_DATES, $default);
	}
	
	function grain_eyecandy_use_fader($default=true) {
		return grain_getoption_yesno(GRAIN_EYECANDY_FADER, $default);
	}		

	function grain_show_content_categories($default=true) {
		return grain_getoption_yesno(GRAIN_CONTENT_CATEGORIES, $default);
	}			

	function grain_show_content_tags($default=true) {
		return grain_getoption_yesno(GRAIN_CONTENT_TAGS, $default);
	}			

	function grain_override_style($apply_filter = TRUE, $default='style.override.css') {
		return grain_getoption(GRAIN_STYLE_OVERRIDE, $apply_filter, $default);
	}
	
	function grain_navigation_bar_location($apply_filter = TRUE, $default='header') {
		return grain_getoption(GRAIN_NAVBAR_LOCATION, $apply_filter, $default);
	}
	
	
	
	function grain_dtfmt_dailyarchive($apply_filter = TRUE, $default='F jS, Y') {
		return grain_getoption(GRAIN_DTFMT_DAILYARCHIVE, $apply_filter, $default);
	}
	
	function grain_dtfmt_monthlyarchive($apply_filter = TRUE, $default='F, Y') {
		return grain_getoption(GRAIN_DTFMT_MONTHLYARCHIVE, $apply_filter, $default);
	}
	
	function grain_dtfmt_published($apply_filter = TRUE, $default='j. F Y, H:i') {
		return grain_getoption(GRAIN_DTFMT_PUBLISHED, $apply_filter, $default);
	}
	
	function grain_dfmt_comments($apply_filter = TRUE, $default='F jS, Y') {
		return grain_getoption(GRAIN_DFMT_COMMENTS, $apply_filter, $default);
	}
	
	function grain_tfmt_comments($apply_filter = TRUE, $default='H:i') {
		return grain_getoption(GRAIN_TFMT_COMMENTS, $apply_filter, $default);
	}
	
	function grain_begin_catlist($apply_filter = TRUE, $default="") {
		return grain_getoption(GRAIN_OPENTAGS_CATLIST, $apply_filter, $default);
	}
	
	function grain_end_catlist($apply_filter = TRUE, $default="") {
		return grain_getoption(GRAIN_CLOSETAGS_CATLIST, $apply_filter, $default);
	}
	
	function grain_begin_taglist($apply_filter = TRUE, $default="") {
		return grain_getoption(GRAIN_OPENTAGS_TAGLIST, $apply_filter, $default);
	}
	
	function grain_end_taglist($apply_filter = TRUE, $default="") {
		return grain_getoption(GRAIN_CLOSETAGS_TAGLIST, $apply_filter, $default);
	}
	
	function grain_show_popup_thumb($default=true) {
		return grain_getoption_yesno(GRAIN_POPUP_SHOW_THUMB, $default);
	}	
	
	function grain_mosaicthumb_width($apply_filter = TRUE, $default=120) {
		return intval(grain_getoption(GRAIN_MOSAIC_THUMB_WIDTH, $apply_filter, $default));
	}
	
	function grain_mosaicthumb_height($apply_filter = TRUE, $default=90) {
		return intval(grain_getoption(GRAIN_MOSAIC_THUMB_HEIGHT, $apply_filter, $default));
	}
	
	function grain_archive_tooltips($default=true) {
		return grain_getoption_yesno(GRAIN_ARCHIVE_TOOLTIPS, $default);
	}	
	
	function grain_comments_enabled($default=true) {
		return grain_getoption_yesno(GRAIN_COMMENTS_ENABLED, $default);
	}
	
	function grain_popupthumb_width($apply_filter = TRUE, $default=200) {
		return intval(grain_getoption(GRAIN_POPUP_THUMB_WIDTH, $apply_filter, $default));
	}
	
	function grain_popupthumb_height($apply_filter = TRUE, $default=200) {
		return intval(grain_getoption(GRAIN_POPUP_THUMB_HEIGHT, $apply_filter, $default));
	}
	
	function grain_popupthumb_stf($default=false) {
		return grain_getoption_yesno(GRAIN_POPUP_THUMB_STF, $default);
	}
	
	function grain_popup_jumptocomments($default=true) {
		return grain_getoption_yesno(GRAIN_POPUP_JTC, $default);
	}
	
	function grain_imprint_url($apply_filter = TRUE, $default=null) {
		return grain_getoption(GRAIN_IMPRINT_URL, $apply_filter, $default);
	}
	
	function grain_ccrdf_feed_embed($default=false) {
		return grain_getoption_yesno(GRAIN_CC_RDF_FEED, $default);
	}
	
	function grain_whoopsimage_url($apply_filter = TRUE, $default='{default}') {
		if($default == '{default}') $default = GRAIN_TEMPLATE_DIR.'/images/whoops.png';
		return grain_getoption(GRAIN_WHOOPS_URL, $apply_filter, $default);
	}
	
	function grain_whoopsimage_width($apply_filter = TRUE, $default=GRAIN_MAX_IMAGE_WIDTH) {
		return intval(grain_getoption(GRAIN_WHOOPS_WIDTH, $apply_filter, $default));
	}
	
	function grain_whoopsimage_height($apply_filter = TRUE, $default=532) {
		return intval(grain_getoption(GRAIN_WHOOPS_HEIGHT, $apply_filter, $default));
	}

	function grain_catlist_separator($apply_filter = TRUE, $default=", ") {
		return grain_getoption(GRAIN_CATLIST_SEPARATOR, $apply_filter, $default);
	}
	
	function grain_taglist_separator($apply_filter = TRUE, $default=", ") {
		return grain_getoption(GRAIN_TAGLIST_SEPARATOR, $apply_filter, $default);
	}

?>