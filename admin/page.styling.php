<?php 
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$

*//**

	Administration menu options for styling settings
	
	@package Grain Theme for WordPress
	@subpackage Administration Menu
*/

	if(!defined('GRAIN_ADMINPAGE_LOADED') ) die(basename(__FILE__));

	// load AJAX functions
	@require_once(TEMPLATEPATH . '/admin/page.styling.ajax.php');

/* functions */

	/**
	 * grain_adminpage_styling() - Builds the styling options page
	 *
	 * @since 0.2
	 * @uses grain_admin_inject_yapb_msg()
	 * @uses grain_admin_start_page()
	 * @uses grain_admin_sizeboxes()
	 * @uses grain_admin_combobox()
	 * @uses grain_admin_longline()
	 * @uses grain_admin_shortline()
	 * @uses grain_admin_infoline()
	 * @uses grain_admin_checkbox()
	 * @global $HTML_allowed
	 * @global $no_HTML
	 */
	function grain_adminpage_styling() 
	{
		global $HTML_allowed, $no_HTML, $GrainOpt;
		grain_admin_inject_yapb_msg();
		
		if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.__("Changes saved", "grain").'</strong></p></div>';
		
		grain_admin_start_page();
	?>
	<div class='wrap'>
		<div id="grain-header">
		<div id="nonJsForm">
			<form method="post" action="">
			
				<h2 id="first"><?php _e("Style Override", "grain"); ?></h2>
					<div class="zerosize"><input type="submit" name="defaultsubmit" value="Save" /></div>

				<fieldset>
					<legend><?php _e("Style Override settings", "grain"); ?></legend>
					<?php

					$files = grain_get_css_overrides();
					$selection["-"] = __("none", "grain");
					foreach($files as $file) {
						$selection[$file] = $file;
					}

					$message = NULL;
					$style = $GrainOpt->get(GRAIN_STYLE_OVERRIDE);
					if( !empty($style) ) 
					{
						$style_overrides = grain_get_css_overrides();
						if( array_search($style, $style_overrides) !== FALSE ) 
						{
							$link_uri = get_bloginfo('url').'/wp-admin/theme-editor.php?file='.GRAIN_RELATIVE_PATH.'/'.$style.'&theme=Grain';
							$link = '<a target="_blank" href="'.$link_uri.'">'.$style.'</a>';
							$message = __("You can edit the currently selected override file here: %FILE", "grain"); 
							$message = str_replace('%FILE', $link, $message);
						}
					}
					
					grain_admin_combobox(GRAIN_STYLE_OVERRIDE, "css_override_file", NULL, $selection, __("CSS File:", "grain"), NULL, __("The file selected here is loaded in addition to the base stylesheet and allows for style overrides. You can use this for quick switching or non-destructive editing of your blog's CSS styles.", "grain") . "<br /><br />" . $message);

					?>						
				</fieldset>	
			
				<h2><?php _e("Image Settings", "grain"); ?></h2>
				<fieldset>
					<legend><?php _e("Image settings", "grain"); ?></legend>
					<?php					
					grain_admin_sizeboxes(GRAIN_MAX_IMAGE_WIDTH, "max_image_width", GRAIN_MAX_IMAGE_HEIGHT, "max_image_height", NULL, __("Image dimensions:", "grain"), __("Pixels", "grain"), __("The maximum dimensions of a photo. The photo will be scaled to fit.<br />If you change these to higher values you probably also have to change your CSS styles for the blog.", "grain"));
					?>	
				</fieldset>
			
				<h2><?php _e("Missing Image", "grain"); ?></h2>		
				<fieldset>
					<legend><?php _e("<em>Whoops</em> image", "grain"); ?></legend>
					<?php
					grain_admin_infoline(NULL, __("If it happens that YAPB is unable to load an image or if you haven't set any image for a post Grain will show a message about that fact. If you want to you can set an image that is to be displayed instead.", "grain"));
					grain_admin_longline(GRAIN_WHOOPS_URL, "whoops_uri", NULL, __("Whoops image URL:", "grain"), $no_HTML, __("The URL of the image that will be displayed if a photoblog post has no image assigned or the image is not uploaded yet.<br />If you want to display a text instead of an image, leave the field empty.", "grain"));
					grain_admin_sizeboxes(GRAIN_WHOOPS_WIDTH, "whoops_width", GRAIN_WHOOPS_HEIGHT, "whoops_height", NULL, __("Image size:", "grain"), __("Pixels", "grain"), __("The actual size of the replacement image.", "grain"));
					?>					
				</fieldset>
			
				<fieldset>
					<legend><?php _e("Info display: EXIF data", "grain"); ?></legend>
					<?php
					grain_admin_checkbox(GRAIN_FANCY_EXIFFILTER, "fancy_exiffilter", NULL, __("EXIF filtering:", "grain"), __("Enable quick EXIF filtering.", "grain"), __("If this option is enabled, Grain will filter some of the EXIF fields that it gets from YAPB: Some fields will be removed even if they are enabled in the YAPB options, some fields will be displayed differently. It is recommended to disable this option only if you take another (own) approach to EXIF filtering.", "grain"));
					grain_admin_checkbox(GRAIN_EXIF_RENDER_INLINE, "exif_inline", NULL, __("Display as inline:", "grain"), __("Display EXIF data \"inline\".", "grain"), __("If you disable this option, Grain will render any EXIF data in a table.", "grain"));
					?>
				</fieldset>			
			
				<h2><?php _e("Mosaic Settings", "grain"); ?></h2>
				<fieldset>
					<legend><?php _e("Mosaic settings", "grain"); ?></legend>
					<?php					
					grain_admin_checkbox(GRAIN_SDBR_ON_MOSAIC, "mosaic_sdbr", NULL, __("Show Sidebar:", "grain"), __("Show the sidebar on the mosaic page.", "grain"), NULL);
					grain_admin_checkbox(GRAIN_MOSAIC_SKIP_EMPTY, "mosaic_skip_empty", NULL, __("Skip empty posts:", "grain"), __("Skip posts that have no photo attached.", "grain"), __("If you disable this option, posts without a photo will be rendered as an empty box in the mosaic.", "grain"));
					grain_admin_checkbox(GRAIN_MOSAIC_DISPLAY_YEARS, "show_mosaic_years", NULL, __("Show Years:", "grain"), __("Group thumbnails by years in the mosaic page", "grain"), NULL);
					grain_admin_checkbox(GRAIN_MOSAIC_SHUFFLE, "mosaic_shuffle", NULL, __("Shuffle mosaic posts:", "grain"), __("Display posts on the mosaic in a random order.", "grain"), __("It is recommended to disable the \"Show Years\" option above if you enable this.", "grain"));
					grain_admin_shortline(GRAIN_MOSAIC_COUNT, "mosaic_count", NULL, __("Photos per page:", "grain"), $no_HTML, __("This value sets how many photos will be displayed at most on a mosaic page.", "grain").__("If you set a negative value or zero, no limitation will be applied.", "grain"));
					grain_admin_longline(GRAIN_PHPTHUMB_OPTIONS, "phpthumb_opt", NULL, __("phpThumb Options:", "grain"), $no_HTML, __("Here you can set an additional configuration string that will be passed to phpThumb for the thumbnail generation.", "grain").'<br />'.__('Configuration options are separated by an ampersand or whitespace; See <a target="_blank" href="http://phpthumb.sourceforge.net/">phpThumb()</a> for more information.', "grain").'<br />'.__("If you are in doubt please leave this field empty.", "grain"));
					?>	
				</fieldset>
						
				<h2><?php _e("Thumbnail settings", "grain"); ?></h2>			
				<fieldset>
					<legend><?php _e("Comments popup", "grain"); ?></legend>
					<?php					
					grain_admin_checkbox(GRAIN_POPUP_SHOW_THUMB, "show_popup_thumbnail", NULL, __("Show Thumbnail:", "grain"), __("Show the photo's thumbnail image on the comments popup", "grain"), NULL);
					grain_admin_sizeboxes(GRAIN_POPUP_THUMB_WIDTH, "popup_thumb_width", GRAIN_POPUP_THUMB_HEIGHT, "popup_thumb_height", NULL, __("Thumbnail size:", "grain"), __("Pixels", "grain"), NULL);
					grain_admin_checkbox(GRAIN_POPUP_THUMB_STF, "popup_thumb_stf", NULL, __("Size to fit:", "grain"), __("Values given above are the maximum dimensions.", "grain"), __("If disabled, the thumbnail will be displayed in the size given above, cropping the parts that don't fit.<br />If it is enabled the thumbnail image will be resized so that it fits within these boundaries.", "grain"));
					?>
				</fieldset>
										
				<fieldset>
					<legend><?php _e("Mosaic & Archive", "grain"); ?></legend>
					<?php
					grain_admin_checkbox(GRAIN_ARCHIVE_TOOLTIPS, "show_tooltips", NULL, __("Show Tooltips:", "grain"), __("Show tooltip when hovering a thumbnail", "grain"), NULL);	
					grain_admin_sizeboxes(GRAIN_MOSAIC_THUMB_WIDTH, "thumb_width", GRAIN_MOSAIC_THUMB_HEIGHT, "thumb_height", NULL, __("Thumbnail size:", "grain"), __("Pixels", "grain"), __("If you set any of these values a negative number or zero, the according attribute of the thumbnail won't be set. A common usage would be to set the first value to zero and the second to a fixed size.", "grain"));
					?>
				</fieldset>

				<h2><?php _e("Comments", "grain"); ?></h2>

				<fieldset>
					<legend><?php _e("Gravatar options", "grain"); ?></legend>
					<?php
					grain_admin_checkbox(GRAIN_EYECANDY_GRAVATARS_ENABLED, "use_gravatars", NULL, __("Use Gravatars:", "grain"), __("If enabled, <a href=\"http://www.gravatar.com/\" target=\"_blank\">Gravatar</a> icons are shown on user comments", "grain"), NULL);
					grain_admin_checkbox(GRAIN_EYECANDY_GRAVATARS_LINKED, "link_gravatars", NULL, __("Link Gravatars:", "grain"), __("If a commenter gives a website URL, the gravatar icon links to his page", "grain"), NULL);
					
					grain_admin_longline(GRAIN_EYECANDY_GRAVATAR_ALTERNATE, "gravatar_alternate", NULL, __("Alternative Image:", "grain"), $no_HTML, __("URL of an alternate image for the case the commenter has no Gravatar. If you don't specify an image URL Gravatar will use it's own dummy image if necessary.", "grain"));
					
					grain_admin_infoline(NULL, __("The following values are extended configuration options for the Gravatar service. Please change them only if you know what you are doing.", "grain"));
					
					grain_admin_shortline(GRAIN_EYECANDY_GRAVATAR_RATING, "gravatar_rating", NULL, __("Gravatar rating:", "grain"), $no_HTML, __("Here you can specify the rating of the gravatars. Allowed values are <code>G, PG, R</code> and <code>X</code>. Only change this if you know why you would want to.", "grain"));
					grain_admin_shortline(GRAIN_EYECANDY_GRAVATAR_SIZE, "gravatar_size", NULL, __("Gravatar size:", "grain"), $no_HTML, __("Here you can change the size of the Gravatar. If you change this value you will have to adapt your stylesheet.", "grain"));
					
					?>
				</fieldset>
				<fieldset>
					<legend><?php _e("Ring / Syndication options", "grain"); ?></legend>
					<?php
					$message = str_replace("%", __("bookmark me at photoblogs.org"), __("If enabled, a \"%\" button will be added to the comment form", "grain"));
					grain_admin_checkbox(GRAIN_EYECANDY_PBORG_BOOKMARK_ENABLED, "use_pborg_button", NULL, __("photoblogs.org button:", "grain"), $message, NULL);
					grain_admin_longline(GRAIN_EYECANDY_PBORG_URI, "pborg_uri", NULL, __("Profile URL:", "grain"), $no_HTML, __("The URL of your <a href=\"http://photoblogs.org\" target=\"_blank\">photoblogs.org</a> profile", "grain"));

					$message = str_replace("%", __("vote for me at coolphotoblogs.com"), __("If enabled, a \"%\" button will be added to the comment form", "grain"));
					grain_admin_checkbox(GRAIN_EYECANDY_COOLPB_ENABLED, "use_coolpb_button", NULL, __("coolphotoblogs.com button:", "grain"), $message, NULL);
					grain_admin_longline(GRAIN_EYECANDY_COOLPB_URI, "coolpb_uri", NULL, __("Profile URL:", "grain"), $no_HTML, __("The URL of your <a href=\"http://photoblogs.org\" target=\"_blank\">photoblogs.org</a> profile", "grain"));
					?>		
				</fieldset>
				
			
				<h2><?php _e("Effects Options", "grain"); ?></h2>
										
				<fieldset>
					<legend><?php _e("moo.fx options", "grain"); ?></legend>
					<?php
					grain_admin_checkbox(GRAIN_EYECANDY_MOOFX, "use_moofx", NULL, __("Use moo.fx:", "grain"), '<strong>'.__("Enable JavaScript effects (<a href=\"http://moofx.mad4milk.net/\" target=\"_blank\">moo.fx</a> engine)", "grain").'</strong>', NULL);	
					
					grain_admin_infoline(NULL, __("Once you have enabled moo.fx, you can choose use the following effects.", "grain"));
					
					grain_admin_checkbox(GRAIN_EYECANDY_REFLECTION_ENABLED, "use_moofx_reflec", NULL, __("Reflection effect:", "grain"), __("If enabled, the image reflects on the background", "grain"), NULL);		
					grain_admin_checkbox(GRAIN_EYECANDY_FADER, "use_moofx_fade", NULL, __("Fade effect:", "grain"), __("Use a fade-in effect to display the image", "grain"), __("The effect may be slow for some machines and is definitly slower for larger images, as well as complex theme styles. Double check that. In addition it requires support on the browser's side, so it isn't necessarily visible to every visitor.", "grain"));		
					?>
					
				</fieldset>				

					<!-- <input type="submit" name="defaults" value="<?php _e("Factory Defaults", "grain"); ?>" /> -->
					<input type="submit" class="defbutton" name="submitform" value="<?php _e("Save Settings", "grain"); ?>" />
					<input type="hidden" name="action" value="save" />
					<input type="hidden" name="styling_form" value="true" />
			</form>
		</div>
		</div>
	</div>
	<?php 
	} // function grain_adminpage_styling()

?>