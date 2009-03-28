<?php 
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$

*//**

	Administration menu options for general settings
	
	@package Grain Theme for WordPress
	@subpackage Administration Menu
*/

	
	if(!defined('GRAIN_ADMINPAGE_LOADED') ) die(basename(__FILE__));
	

/* functions */

	/**
	 * grain_adminpage_general() - Builds the general options page
	 *
	 * @since 0.2
	 * @uses grain_admin_inject_yapb_msg()
	 * @uses grain_admin_start_page()
	 * @uses grain_admin_infoline()
	 * @uses grain_admin_shortline()
	 * @uses grain_admin_checkbox()
	 * @global $HTML_allowed
	 * @global $no_HTML
	 */
	function grain_adminpage_general() 
	{
		global $HTML_allowed, $no_HTML;
		grain_admin_inject_yapb_msg();
		
		if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.__("Changes saved", "grain").'</strong></p></div>';
		
		grain_admin_start_page();
	?>
	<div class='wrap'>
		<div id="grain-header">
		<div id="nonJsForm">
			<form method="post" action="">
				<h2 id="first"><?php _e("Info and commenting mode", "grain"); ?></h2>
					<div class="zerosize"><input type="submit" name="defaultsubmit" value="Save" /></div>

				<fieldset>
					<legend><?php _e("Basic behavior", "grain"); ?></legend>
					<?php 				
					$url = get_bloginfo('url') . '/wp-admin/options-discussion.php';
					$link = '<a href="'.$url.'" target="_blank">'.__("Discussion").'</a>';
					$message = __("Most probably you want to leave this enabled to receive feedback. But maybe you really just want to <em>show</em> your photos. This is the showcase option then: If you disable it, visitors will neither be able to comment (which is a standard feature) nor to view any existing comments. You may then also want to disable WordPress' commenting options on the %LINK screen altogether to prevent comments coming in through track-/pingbacks or backdoors.", "grain"); 
					$message = str_replace('%LINK', $link, $message);
					grain_admin_checkbox(GRAIN_COMMENTS_ENABLED, "enable_comments", NULL, __("Enable comments:", "grain"), __("Allow visitors to comment or view existing comments", "grain"), $message);
					grain_admin_checkbox(GRAIN_EXTENDEDINFO_ENABLED, "extended_comments", NULL, __("No popups!", "grain"), __("Don't use a popup to display an image's info and commenting form", "grain"), __("If you enable this, the \"Extended Info page\" mechanism will be used. This is a replacement for the older comments popup, showing the info and comments on the same page as the photo. One will still have to click the comments link to display it, though.<br />If you prefer a popup for that task, disable this option.", "grain"));
					grain_admin_checkbox(GRAIN_CONTENT_ENFORCE_INFO, "enforce_extended_info", NULL, __("Always show info:", "grain"), __("Always show photo title/description regardless of the popup setting above", "grain"), __("If you always want to display the photo's title and it's description on the photo page, enable this option. If you uncheck this option and have the popups disabled (enabled the option above that is), be aware that the user won't see any information about your photo. (They would be shown on the popup, but you disabled it.)", "grain"));					
					?>							
										
				</fieldset>
				
				<fieldset>
					<legend><?php _e("Basic behavior II", "grain"); ?></legend>
					<?php 			
					grain_admin_checkbox(GRAIN_COMMENTS_ON_EMPTY_ENABLED, "enable_comments_oe", NULL, __("Comments always allowed:", "grain"), __("Allow visitors to comment on pages considered \"not ready\"", "grain"), __("Sometimes it happens that a visitor sees a page whose image is not available. Leave this option disabled to prevent access to the comments form in this special case.<br />This also implies that the image infos can be seen.", "grain"));
					grain_admin_checkbox(GRAIN_POPUP_JTC, "popup_jtc", NULL, __("Directly jump to comments:", "grain"), __("If the comments popup opens, skip the description text there", "grain"), __("This option only applies when the info is already shown on the main page, so that the visitor won't have to scroll to the comments on the popup. Rule of thumb: If you tend to write long texts and use the popup, you may want to enable this option.", "grain"));
					grain_admin_checkbox(GRAIN_EXCERPTONLY, "excerpt_only", NULL, __("Show only excerpts:", "grain"), __("Show only a post's excerpt on a comment popup", "grain"), __("This option doesn't apply to you if you never use the <code>&lt!--more--&gt</code> tag anyway. If you choose to disable this option and such tag is found in the photo's description text, only the text in front of the tag will be shown. This only applies to the popup; Grain will generally show only the excerpt on a photo page, except when it's in extended view.", "grain"));
					?>
				</fieldset>
				<fieldset>
					<legend><?php _e("Info display: Your photo's description", "grain"); ?></legend>
					<?php				
					grain_admin_checkbox(GRAIN_CONTENT_PERMALINK_VISIBLE, "show_permalink", NULL, __("Enable permalink:", "grain"), __("Show the permalink in the image info", "grain"), __("The permalink is an address that won't change. This way a visitor can securely link one of your photo pages. Permalinks are managed by WordPress.", "grain"));
					grain_admin_checkbox(GRAIN_CONTENT_COMMENTS_HINT, "show_comments_hint", NULL, __("Enable comment hint:", "grain"), __("Show the permalink in the image info", "grain"), __("If enabled, Grain shows a link to the comments in the short info display below the image.", "grain"));				
					grain_admin_checkbox(GRAIN_CONTENT_DATES, "show_dates", NULL, __("Show Date:", "grain"), __("Display the date the photo was published", "grain"), __("This shows the date and time the photo page was published in the description text.", "grain") );
					?>
				</fieldset>
				<fieldset>
					<legend><?php _e("Info display: EXIF data", "grain"); ?></legend>
					<?php

					$link_uri = get_bloginfo('url').'/wp-admin/theme-editor.php?file='.GRAIN_RELATIVE_PATH.'/exif-block.php&theme=Grain';
					$link = '<a target="_blank" href="'.$link_uri.'">exif-block.php</a>';
					$message = __("If you understand PHP, you can affect how the EXIF information is displayed by editing the %FILE file.<br /><strong>Hint:</strong> Enable YAPBs EXIF filtering to keep things clean.", "grain"); 
					$message = str_replace('%FILE', $link, $message);
					grain_admin_checkbox(GRAIN_EXIF_VISIBLE, "display_exif", NULL, __("Display EXIF info:", "grain"), __("Show a photo's EXIF information next to the description", "grain"), $message);
					grain_admin_checkbox(GRAIN_HIDE_EXIF_IF_NO_CONTENT, "hide_exif", NULL, __("Hide EXIF if no content:", "grain"), __("Don't show a photo's EXIF information if no content text was given.", "grain"));
					?>
				</fieldset>
				<fieldset>
					<legend><?php _e("Info display: Categories", "grain"); ?></legend>
					<?php				
					grain_admin_checkbox(GRAIN_CONTENT_CATEGORIES, "show_categories", NULL, __("Show category list:", "grain"), __("Shows the categories in which the photo is filed", "grain"), NULL);
					
					// TODO: Move these to the styling/layout page
					grain_admin_shortline(GRAIN_OPENTAGS_CATLIST, "before_categories", NULL, __("Begin category list:", "grain"), $HTML_allowed, __("This text will be embedded right before the category list. You can put specific markup here to style your theme.", "grain"));
					grain_admin_shortline(GRAIN_CATLIST_SEPARATOR, "delimit_categories", NULL, __("Category list delimiter:", "grain"), $HTML_allowed, __("This will be embedded within the category list to separate two entries.", "grain"));
					grain_admin_shortline(GRAIN_CLOSETAGS_CATLIST, "after_categories", NULL, __("End category list:", "grain"), $HTML_allowed, __("This will be embedded right after the category list.", "grain"));
					?>
				</fieldset>
				<fieldset>
					<legend><?php _e("Info display: Tags", "grain"); ?></legend>
					<?php				
					grain_admin_checkbox(GRAIN_CONTENT_TAGS, "show_taglist", NULL, __("Show tag list:", "grain"), __("Shows the tags with which the photo is marked", "grain"), NULL);
					// TODO: Move these to the styling/layout page
					grain_admin_shortline(GRAIN_OPENTAGS_TAGLIST, "before_taglist", NULL, __("Begin tag list:", "grain"), $HTML_allowed, __("This text will be embedded right before the tag list. You can put specific markup here to style your theme.", "grain"));
					grain_admin_shortline(GRAIN_TAGLIST_SEPARATOR, "delimit_taglist", NULL, __("Tag list delimiter:", "grain"), $HTML_allowed, __("This will be embedded within the tag list to separate two entries.", "grain"));
					grain_admin_shortline(GRAIN_CLOSETAGS_TAGLIST, "after_taglist", NULL, __("End tag list:", "grain"), $HTML_allowed, __("This will be embedded right after the tag list.", "grain"));
					?>
				</fieldset>
					
				<h2><?php _e("Newsfeeds", "grain"); ?></h2>

				<fieldset>
					<legend><?php _e("Atom Feed Settings", "grain"); ?></legend>
					<?php
					grain_admin_checkbox(GRAIN_FEED_ATOM_ENABLED, "atom_feed", NULL, __("Atom Feed:", "grain"), __("Show the Atom Feed icon next to the RSS feed icon", "grain"), NULL);
					?>
				</fieldset>
				<fieldset>
					<legend><?php _e("Media RSS Settings", "grain"); ?></legend>
					<?php
					grain_admin_checkbox(GRAIN_FTR_MEDIARSS, "mediarss_feed", NULL, __("Media RSS:", "grain"), __("Enable the Media RSS/MRSS feed", "grain"), __('The <a href="http://en.wikipedia.org/wiki/Media_RSS">Media RSS</a> feed is an extended RSS feed for multimedia applications.', "grain") ." ". __('Note that enabling this option may increase the traffic of your blog, depending on the browser.', "grain"));
					grain_admin_shortline(GRAIN_FTR_MEDIARSS_COUNT, "mediarss_count", NULL, __("Media RSS items:", "grain"), $no_HTML, __("Number of recent items in the Media RSS feed. A higher value provides a more detailed representation of your blog but adds more web traffic. A value of zero sets this to the default; a negative number represents no limitation.", "grain"));
					?>
				</fieldset>
			
				<h2><?php _e("Syndication Options", "grain"); ?></h2>

				<?php 
					$url = "<a href=\"theme-editor.php?file=wp-content/themes/grain/functions.syndication.php&theme=Grain\">functions.syndication.php</a>";
					$message = str_replace( "{FILE}", $url, __("<strong>Hint:</strong> Syndication data can (and has to be) changed in the {FILE} file to reflect your own syndicate. If you are not sure what syndication means or what to do in that file, please disable the Flat Syndication option.", "grain"));
					grain_admin_infoline(NULL, $message);
				?>

				<fieldset>
					<legend><?php _e("Flat Syndication", "grain"); ?></legend>
					<?php 
					grain_admin_checkbox(GRAIN_SYND_FLAT_ENABLED, "flat_syndication", NULL, __("Flat Syndication:", "grain"), __("Show ring / syndication info as a list in the blog's footer", "grain"), NULL);
					// TODO: Move these to the styling/layout page
					grain_admin_shortline(GRAIN_SYND_FLAT_DELIMITER, "flat_syndication_delimiter", NULL, __("Delimiter:", "grain"), $HTML_allowed, __("This will be embedded as a separator of the list items.", "grain"));
					?>
				</fieldset>
	
				<h2><?php _e("Localization Options", "grain"); ?></h2>

				<fieldset>				
					<legend><?php _e("Secondary Language Options", "grain"); ?></legend>
					<?php 
					grain_admin_infoline(NULL, __("Grain has an option that allows you to display photo titles in an additional second language or tho use an additional texts as a subtitle.", "grain"));

					grain_admin_checkbox(GRAIN_2NDLANG_ENABLED, "second_language", NULL, __("2nd Line:", "grain"), __("Enable 2nd line/2nd language support", "grain"), NULL);
					grain_admin_shortline(GRAIN_2NDLANG_TAG, "second_language_tag", NULL, __("2nd line custom field:", "grain"), $no_HTML, __("The name/key of the custom field that contains the secondary language title.<br />You may want to read the WordPress Codex article on <a title=\"_blank\" href=\"http://codex.wordpress.org/Using_Custom_Fields\">Using Custom Fields</a>.", "grain"));
					?>				
				</fieldset>
				
				<fieldset>				
					<legend><?php _e("Auto Locale", "grain"); ?></legend>
					<?php 
					grain_admin_infoline(NULL, __("Browsers may send an information about their preferred language. Grain can make use of this information to automatically select a translation file, displaying the blog in the visitor's language.", "grain"));

					grain_admin_checkbox(GRAIN_AUTOLOCALE_ENABLED, "auto_locale", NULL, __("Enable Auto Locale:", "grain"), __("Activate visitor based translation", "grain"), __("Grain tries to match a browser's <code>HTTP_ACCEPT_LANGUAGE</code> against a set of locale (<code>*.mo</code>) files in your theme's directory. If no match can be found, the default translation based on your WordPress settings will be used.", "grain"));
					?>				
				</fieldset>

				<h2><?php _e("Archives Sidebar", "grain"); ?></h2>

				<fieldset>
					<?php
					grain_admin_infoline(NULL, __("These settings affect the sidebar you can see when you open an archive page, such as the Mosaic, the category archives etc.<br />Grain is a theme with widget support, so you can easily customize your sidebar in the Widgets menu; These options will only apply if there is no widget plugged into the according sidebar.", "grain"));

					$url = "<a href=\"theme-editor.php?file=wp-content/themes/grain/functions.syndication.php&theme=Grain\">functions.syndication.php</a>";
					$message = str_replace( "{FILE}", $url, __("The values in the {FILE} file will be used for that.", "grain"));
					grain_admin_checkbox(GRAIN_SDBR_SYND_ENABLED, "sidebar_syndication", NULL, __("Sidebar Syndication:", "grain"), __("Show Syndication in the Sidebar", "grain"), $message);
					
					grain_admin_checkbox(GRAIN_SDBR_MOSTCOMM_ENABLED, "most_commented", NULL, __("Most commented:", "grain"), __("List most commented photos", "grain"), NULL);
					grain_admin_shortline(GRAIN_SDBR_MOSTCOMM_COUNT, "most_commented_count", NULL, __("Show at most:", "grain"), $no_HTML, __("This sets the maximum number of recent comments that will be displayed in the list.", "grain"));					
					
					grain_admin_checkbox(GRAIN_SDBR_CALENDAR_ENABLED, "calendar_enabled", NULL, __("Display Calendar:", "grain"), __("Show the post calendar", "grain"), NULL);
					grain_admin_checkbox(GRAIN_SDBR_BLOGROLL_ENABLED, "blogroll_enabled", NULL, __("Display Blogroll:", "grain"), __("Show your blogroll", "grain"), NULL);
					grain_admin_checkbox(GRAIN_SDBR_META_ENABLED, "meta_enabled", NULL, __("Display \"Meta\" block:", "grain"), __("Show WordPress' \"Meta\" blog to enable users to register or log in", "grain"), NULL);
					?>
				</fieldset>

					<!-- <input type="submit" name="defaults" value="<?php echo _e("Factory Defaults", "grain"); ?>" /> -->
					<input type="submit" class="defbutton" name="submitform" value="<?php _e("Save Settings", "grain"); ?>" />
					<input type="hidden" name="action" value="save" />
					<input type="hidden" name="general_form" value="true" />
			</form>
		</div>
		</div>
	</div>
<?php 
	} // function grain_adminpage_general()

?>