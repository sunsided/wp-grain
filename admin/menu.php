<?php 
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/

	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));
	
/* Options */

	@require_once(TEMPLATEPATH . '/func/options.php');
	define(GRAIN_ADMINPAGE_LOADED, true);

/* Hooks */

	add_action('admin_head', 'grain_admin_pagestyle');	
	add_action('admin_menu', 'grain_admin_createmenus');

/* known menus */

	$knownPagesList = array( "copyright", "general", "styling", "datetime", "navigation" );
	$knownPage = in_array( $knownPagesList, $_GET['page'] );

	@require_once(TEMPLATEPATH . '/admin/page.copyright.php');
	@require_once(TEMPLATEPATH . '/admin/page.general.php');
	@require_once(TEMPLATEPATH . '/admin/page.styling.php');
	@require_once(TEMPLATEPATH . '/admin/page.datetime.php');
	@require_once(TEMPLATEPATH . '/admin/page.navigation.php');

/* top level menu */

	function grain_admin_createmenus() 
	{	
		$baseTitle = __("Configure Grain", "grain") . ' &raquo; ';

		add_theme_page(	$baseTitle . __("Copyright Settings", "grain"), 		
						__("Copyright Settings", "grain"), 
						'edit_themes', 
						'copyright', 
						'grain_adminpage_copyright');
		
		add_theme_page( $baseTitle . __("General Settings", "grain"), 			
						__("General Settings", "grain"), 
						'edit_themes', 
						'general', 
						'grain_adminpage_general');
		
		add_theme_page( $baseTitle . __("Navigation Settings", "grain"), 		
						__("Navigation Bar", "grain"), 
						'edit_themes', 
						'navigation', 
						'grain_adminpage_navigation');
		
		add_theme_page( $baseTitle . __("Styling and Layout", "grain"), 		
						__("Styling and Layout", "grain"), 
						'edit_themes', 
						'styling', 
						'grain_adminpage_styling');
		
		add_theme_page( $baseTitle . __("Date and Time Settings", "grain"), 	
						__("Date and Time", "grain"), 
						'edit_themes', 
						'datetime', 
						'grain_adminpage_datetime');
		
		
		// shortcut to yapb
		if(grain_is_yapb_installed() ) 
		{
			$yapb_page = get_bloginfo('url').'/wp-admin/options-general.php?page=Yapb.class.php';
			add_theme_page( __("Yet Another Photoblog", "grain"), 
							__("YAPB", "grain"), 
							'edit_plugins', 
							$yapb_page);
		}
				
		// do the logic
		grain_admin_dologic();

	}

/* functions */

	function grain_admin_dologic() 
	{
		global $grain_options;
	
		if ( $knownPage ) 
		{

			if ( 'save' == $_REQUEST['action'] ) 
			{

				$newoptions = $grain_options;

				if ( isset($_REQUEST['copyright_form']) ) 
				{
				
					if ( isset($_REQUEST['defaults']) ) {
						
					} else {
						// Copyright
						$newoptions[GRAIN_COPYRIGHT_HOLDER] = strip_tags(stripslashes($_POST['copyright_person']));
						$newoptions[GRAIN_COPYRIGHT_HOLDER_HTML] = stripslashes($_POST['copyright_person_ex']);
						$newoptions[GRAIN_COPYRIGHT_START_YEAR] = strip_tags(stripslashes($_POST['copyright_start_year']));
						$newoptions[GRAIN_COPYRIGHT_END_YEAR] = strip_tags(stripslashes($_POST['copyright_end_year']));
						$newoptions[GRAIN_COPYRIGHT_END_OFFSET] = strip_tags(stripslashes($_POST['copyright_end_year_offset']));

						// Creative Commons
						$newoptions[GRAIN_COPYRIGHT_CC_ENABLED] = grain_value_checkbox($_POST['cc_license_enabled']);
						$newoptions[GRAIN_COPYRIGHT_CC_CODE] = stripslashes($_POST['cc_license_code']);
						$newoptions[GRAIN_COPYRIGHT_CC_RDF] = stripslashes($_POST['cc_license_rdf']);
						$newoptions[GRAIN_CC_RDF_FEED] = grain_value_checkbox($_POST['cc_rdf_feed']);
						
						// imprint
						$newoptions[GRAIN_IMPRINT_URL] = strip_tags(stripslashes($_POST['imprint_url']));
					}

				}
				else if ( isset($_REQUEST['navigation_form']) ) 
				{
				
					if ( isset($_REQUEST['defaults']) ) {
						
					} else {
						// Navigation
						$newoptions[GRAIN_NAVBAR_LOCATION] = strip_tags(stripslashes($_POST['navbar_location']));
						$newoptions[GRAIN_INFOPAGE_ID] = strip_tags(stripslashes($_POST['info_page_id']));
						$newoptions[GRAIN_MOSAIC_PAGEID] = strip_tags(stripslashes($_POST['mosaic_page_id']));
						$newoptions[GRAIN_MOSAIC_LINKTITLE] = stripslashes($_POST['mosaic_title']);
						$newoptions[GRAIN_MNU_PERMALINK_VISIBLE] = grain_value_checkbox($_POST['permalink_visible']);
						$newoptions[GRAIN_MNU_RANDOM_VISIBLE] = grain_value_checkbox($_POST['random_visible']);
						$newoptions[GRAIN_MNU_NEWEST_VISIBLE] = grain_value_checkbox($_POST['newest_visible']);
						$newoptions[GRAIN_MNU_INFO_VISIBLE] = grain_value_checkbox($_POST['info_visible']);
						$newoptions[GRAIN_MOSAIC_ENABLED] = grain_value_checkbox($_POST['mosaic_visible']);
						$newoptions[GRAIN_NAV_BIDIR_ENABLED] = grain_value_checkbox($_POST['bidir_nav']);
					}				
				
				}
				else if ( isset($_REQUEST['general_form']) ) 
				{

					if ( isset($_REQUEST['defaults']) ) {
						
					} else {					
						$newoptions[GRAIN_SYND_FLAT_ENABLED] = grain_value_checkbox($_POST['flat_syndication']);
						$newoptions[GRAIN_SYND_FLAT_DELIMITER] = strip_tags(stripslashes($_POST['flat_syndication_delimiter']));
						$newoptions[GRAIN_SDBR_SYND_ENABLED] = grain_value_checkbox($_POST['sidebar_syndication']);
						$newoptions[GRAIN_SDBR_MOSTCOMM_ENABLED] = grain_value_checkbox($_POST['most_commented']);
						$newoptions[GRAIN_SDBR_BLOGROLL_ENABLED] = grain_value_checkbox($_POST['blogroll_enabled']);
						$newoptions[GRAIN_SDBR_META_ENABLED] = grain_value_checkbox($_POST['meta_enabled']);
						$newoptions[GRAIN_MOSAIC_COUNT] = strip_tags(stripslashes($_POST['most_commented_count']));
						$newoptions[GRAIN_SDBR_CALENDAR_ENABLED] = grain_value_checkbox($_POST['calendar_enabled']);
						$newoptions[GRAIN_2NDLANG_ENABLED] = grain_value_checkbox($_POST['second_language']);
						$newoptions[GRAIN_2NDLANG_TAG] = strip_tags(stripslashes($_POST['second_language_tag']));
						$newoptions[GRAIN_FEED_ATOM_ENABLED] = grain_value_checkbox($_POST['atom_feed']);
						$newoptions[GRAIN_MOSAIC_COUNT] = strip_tags(stripslashes($_POST['mosaic_count']));
						$newoptions[GRAIN_EXTENDEDINFO_ENABLED] = !grain_value_checkbox($_POST['extended_comments']);
						$newoptions[GRAIN_CONTENT_ENFORCE_INFO] = grain_value_checkbox($_POST['enforce_extended_info']);
						$newoptions[GRAIN_CONTENT_PERMALINK_VISIBLE] = grain_value_checkbox($_POST['show_permalink']);
						$newoptions[GRAIN_CONTENT_COMMENTS_HINT] = grain_value_checkbox($_POST['show_comments_hint']);
						$newoptions[GRAIN_CONTENT_DATES] = grain_value_checkbox($_POST['show_dates']);
						$newoptions[GRAIN_CONTENT_CATEGORIES] = grain_value_checkbox($_POST['show_categories']);
						$newoptions[GRAIN_OPENTAGS_CATLIST] = stripslashes($_POST['before_categories']);
						$newoptions[GRAIN_CLOSETAGS_CATLIST] = stripslashes($_POST['after_categories']);					
						$newoptions[GRAIN_POPUP_JTC] = grain_value_checkbox($_POST['popup_jtc']);						
						// exif
						$newoptions[GRAIN_EXIF_VISIBLE] = grain_value_checkbox($_POST['display_exif']);						
						$newoptions[GRAIN_COMMENTS_ENABLED] = grain_value_checkbox($_POST['enable_comments']);
						
					}
				}
				else if ( isset($_REQUEST['styling_form']) ) 
				{

					if ( isset($_REQUEST['defaults']) ) {
						
					} else {				
						// override				
						$newoptions[GRAIN_STYLE_OVERRIDE] = strip_tags(stripslashes($_POST['css_override_file']));
						
						// moo.fx
						$newoptions[GRAIN_EYECANDY_MOOFX] = grain_value_checkbox($_POST['use_moofx']);
						$newoptions[GRAIN_EYECANDY_REFLECTION_ENABLED] = grain_value_checkbox($_POST['use_moofx_reflec']);
						$newoptions[GRAIN_EYECANDY_FADER] = grain_value_checkbox($_POST['use_moofx_fade']);
						
						// gravatars
						$newoptions[GRAIN_EYECANDY_GRAVATARS_ENABLED] = grain_value_checkbox($_POST['use_gravatars']);
						$newoptions[GRAIN_EYECANDY_GRAVATAR_ALTERNATE] = strip_tags(stripslashes($_POST['gravatar_alternate']));
						
						// addon buttons
						$newoptions[GRAIN_EYECANDY_PBORG_BOOKMARK_ENABLED] = grain_value_checkbox($_POST['use_pborg_button']);
						$newoptions[GRAIN_EYECANDY_PBORG_URI] = strip_tags(stripslashes($_POST['pborg_uri']));
						$newoptions[GRAIN_EYECANDY_COOLPB_ENABLED] = grain_value_checkbox($_POST['use_coolpb_button']);
						$newoptions[GRAIN_EYECANDY_COOLPB_URI] = strip_tags(stripslashes($_POST['coolpb_uri']));
						
						// mosaic
						$newoptions[GRAIN_MOSAIC_DISPLAY_YEARS] = grain_value_checkbox($_POST['show_mosaic_years']);
						
						// thumbnails
						$newoptions[GRAIN_ARCHIVE_TOOLTIPS] = grain_value_checkbox($_POST['show_tooltips']);
						$newoptions[GRAIN_MOSAIC_THUMB_WIDTH] = strip_tags(stripslashes($_POST['thumb_width']));
						$newoptions[GRAIN_MOSAIC_THUMB_HEIGHT] = strip_tags(stripslashes($_POST['thumb_height']));
						
						// popup
						$newoptions[GRAIN_POPUP_SHOW_THUMB] = grain_value_checkbox($_POST['show_popup_thumbnail']);
						$newoptions[GRAIN_POPUP_THUMB_WIDTH] = strip_tags(stripslashes($_POST['popup_thumb_width']));
						$newoptions[GRAIN_POPUP_THUMB_HEIGHT] = strip_tags(stripslashes($_POST['popup_thumb_height']));
						$newoptions[GRAIN_POPUP_THUMB_STF] = strip_tags(stripslashes($_POST['popup_thumb_stf']));
						
						// whoops
						$newoptions[GRAIN_WHOOPS_URL] = strip_tags(stripslashes($_POST['whoops_uri']));
						$newoptions[GRAIN_WHOOPS_WIDTH] = strip_tags(stripslashes($_POST['whoops_width']));
						$newoptions[GRAIN_WHOOPS_HEIGHT] = strip_tags(stripslashes($_POST['whoops_height']));
					
					}
				}
				else if ( isset($_REQUEST['datetime_form']) ) 
				{

					if ( isset($_REQUEST['defaults']) ) {
						
					} else {				
						$newoptions[GRAIN_DTFMT_DAILYARCHIVE] = strip_tags(stripslashes($_POST['archive_daily_dt']));
						$newoptions[GRAIN_DTFMT_MONTHLYARCHIVE] = strip_tags(stripslashes($_POST['archive_monthly_dt']));
						$newoptions[GRAIN_DTFMT_PUBLISHED] = strip_tags(stripslashes($_POST['post_publish_dt']));
						$newoptions[GRAIN_DFMT_COMMENTS] = strip_tags(stripslashes($_POST['comments_date']));
						$newoptions[GRAIN_TFMT_COMMENTS] = strip_tags(stripslashes($_POST['comments_time']));
					}
				}

				// update options if changed
				if( $grain_options != $newoptions ) {
					$grain_options = $newoptions;
					update_option(GRAIN_OPTIONS_KEY, $grain_options);
				}

				//print_r($_REQUEST);
				wp_redirect("themes.php?page=".$_GET['page']."&saved=true");
				die;
			
			} // save
			
		} // known page
	}

	function grain_admin_pagestyle() {
		echo "<link rel='stylesheet' href='".GRAIN_TEMPLATE_DIR."/admin/admin.css' type='text/css' />";
	}

	function grain_admin_inject_yapb_msg() 
	{
		if( !grain_is_yapb_installed() )
		?>
			<div id="errormessage" class="error"><p><strong><?php _e("The YAPB plugin could not be found.", "grain"); ?></strong> <a title="<?php _e("Yet Another Photoblog", "grain"); ?>" target="_blank" href="http://wp-grain.wiki.sourceforge.net/YaPB"><?php _e("Click for more information", "grain"); ?></a></a></p></div>	
		<?php
	}

?>