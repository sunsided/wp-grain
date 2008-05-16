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

	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));
	
/* Options */

	@require_once(TEMPLATEPATH . '/func/options.php');

/* Hooks */

	add_action('admin_head', 'grain_admin_pagestyle');	
	add_action('admin_menu', 'grain_admin_createmenus');

/* top level menu */

	function grain_admin_createmenus() {

		add_theme_page(__("Configure Grain", "grain") . ' &raquo; ' . __("Copyright Settings", "grain"), __("Copyright Settings", "grain"), 'edit_themes', 'copyright', 'grain_adminpage_copyright');
		add_theme_page(__("Configure Grain", "grain") . ' &raquo; ' . __("General Settings", "grain"), __("General Settings", "grain"), 'edit_themes', 'general', 'grain_adminpage_general');
		add_theme_page(__("Configure Grain", "grain") . ' &raquo; ' . __("Navigation Settings", "grain"), __("Navigation Bar", "grain"), 'edit_themes', 'navigation', 'grain_adminpage_navigation');
		add_theme_page(__("Configure Grain", "grain") . ' &raquo; ' . __("Styling and Layout", "grain"), __("Styling and Layout", "grain"), 'edit_themes', 'styling', 'grain_adminpage_styling');
		add_theme_page(__("Configure Grain", "grain") . ' &raquo; ' . __("Date and Time Settings", "grain"), __("Date and Time", "grain"), 'edit_themes', 'datetime', 'grain_adminpage_datetime');
		
		// shortcut to yapb
		$yapb_page = get_bloginfo('url').'/wp-admin/options-general.php?page=Yapb.class.php';
		add_theme_page(__("Yet Another Photoblog", "grain"), __("YAPB", "grain"), 'edit_plugins', $yapb_page);
				
		grain_admin_dologic();
	}

/* functions */

	function grain_admin_dologic() {
		global $grain_options;
	
		if ( 	$_GET['page'] == 'copyright' ||
				$_GET['page'] == 'general' || 
				$_GET['page'] == 'styling' ||
				$_GET['page'] == 'datetime' ||
				$_GET['page'] == 'navigation') {

			if ( 'save' == $_REQUEST['action'] ) {

				$newoptions = $grain_options;

				if ( isset($_REQUEST['copyright_form']) ) {
				
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
				else if ( isset($_REQUEST['navigation_form']) ) {
				
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
				else if ( isset($_REQUEST['general_form']) ) {

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
				else if ( isset($_REQUEST['styling_form']) ) {

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
				else if ( isset($_REQUEST['datetime_form']) ) {

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
			}
		}
	}

	function grain_admin_pagestyle() {
	?>
	<style type='text/css'>
		#grain-header {
			font-size: 1em;
		}
		#grain-header .hibrowser {
			width: 780px;
			height: 260px;
			overflow: scroll;
		}
		#grain-header #hitarget {
			display: none;
		}

		#nonJsForm {
			position: relative;
			text-align: left;
		}
		#nonJsForm label {
			padding-top: 6px;
			padding-right: 5px;
			float: left;
			width: 200px;
			text-align: right;
		}

		#nonJsForm input,
		#nonJsForm textarea,
		.input_pad {
			padding-top: 6px;
			padding-right: 5px;
			margin-left: 10px;
			/*width: 200px;*/
			text-align: left;
		}

		.input_pad {
			padding-left: 205px;
			margin-top: -10px;
			padding-top: 0;
		}

		#nonJsForm textarea.license-code {
			font-family: "Courier New", Courier, monospace;
		}

		.defbutton {
			font-weight: bold;
		}
		.zerosize {
			width: 0px;
			height: 0px;
			overflow: hidden;
		}
		
		#nonJsForm hr {
			border: none;
			border-top: 1px dotted black;
			margin: 10px 0;
		}
		
		#nonJsForm h2 {
			padding-top: 20px;
		}
		
		#nonJsForm h2#first {
			padding-top: 0px;
		}
		
		fieldset {
			border: 1px solid silver;
			margin-bottom: 10px;
			padding: 10px;
		}
		
		fieldset p {
			margin-top: 10px;
			margin-bottom: 10px;
		}
		
		table {
			width: 100%;
		}
		
		td.key {
			width: 200px;
			padding-left: 2px;
			padding-right: 2px;
		}
		td.value {
			padding-left: 2px;
			padding-right: 2px;
		}
	</style>
	<?php
	}


	function grain_adminpage_datetime() {
		if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.__("Changes saved", "grain").'</strong></p></div>';
	?>
	<div class='wrap'>
		<div id="grain-header">
		<div id="nonJsForm">
			<form method="post" action="">
				<h2 id="first"><?php _e("Date and Time Settings", "grain"); ?></h2>
					<div class="zerosize"><input type="submit" name="defaultsubmit" value="Save" /></div>
					
					<p>
						<?php _e("For information on date and time formatting codes refer to the PHP manual entry of <a href=\"http://www.php.net/date\" target=\"_blank\">the <code>date()</code> function</a>.", "grain"); ?><br />
						<?php _e("If you want to include a specific text in the format, such as \"<code>o'clock</code>\", you may include it in curly braces, e.g. \"<code>{o'clock'}</code>\"", "grain"); ?>						
					</p>
					
					<fieldset>
					<legend><?php _e("Some examples", "grain"); ?></legend>
					
					<table class="examples">
					<thead>
						<tr>
							<td><?php _e("format"); ?></td>
							<td><?php _e("result"); ?></td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<?php $format = 'l, F jS, Y'; ?>
							<td class="key"><?php echo $format; ?></td>
							<td class="value"><?php echo date(grain_filter_dt($format)); ?></td>
						</tr>
						<tr>
							<?php $format = 'F, Y'; ?>
							<td class="key"><?php echo $format; ?></td>
							<td class="value"><?php echo date(grain_filter_dt($format)); ?></td>
						</tr>
						<tr>
							<?php $format = 'g:ia'; ?>
							<td class="key"><?php echo $format; ?></td>
							<td class="value"><?php echo date(grain_filter_dt($format)); ?></td>
						</tr>
						<tr>
							<?php $format = '{it is} G:i T'; ?>
							<td class="key"><?php echo $format; ?></td>
							<td class="value"><?php echo date(grain_filter_dt($format)); ?></td>
						</tr>						
						<tr>
							<?php $format = '{abc}'; ?>
							<td class="key"><?php echo $format; ?></td>
							<td class="value"><?php echo date(grain_filter_dt($format)); ?></td>
						</tr>
						<tr>
							<?php $format = 'abc'; ?>
							<td class="key"><?php echo $format; ?></td>
							<td class="value"><?php echo date(grain_filter_dt($format)); ?></td>
						</tr>
					</tbody>
					</table>
					
					</fieldset>
					
					<fieldset>
					<legend><?php _e("Archive settings", "grain"); ?></legend>
					
					<p><label for="archive_daily_dt"><?php _e("Daily Archive:", "grain"); ?></label>
						<input 
							name="archive_daily_dt" 
							id="archive_daily_dt" 
							style="width: 200px" 
							type="text" 
							value="<?php echo htmlentities(grain_dtfmt_dailyarchive(FALSE)); ?>" /><br />
						<div class="input_pad">
							<?php _e("The date or time format in the archive for a specific day.", "grain"); ?>
							<br />
							<?php 
								$value = str_replace('%FORMAT', grain_dtfmt_dailyarchive(), __("The current selection (<code>%FORMAT</code>) results in '<code>%RESULT</code>' for the current time.", "grain")); 
								$value = str_replace('%RESULT', date(grain_filter_dt(grain_dtfmt_dailyarchive())), $value); 
								echo $value;
							?>
						</div>
					</p>
					
					<p><label for="archive_monthly_dt"><?php _e("Monthly Archive:", "grain"); ?></label>
						<input 
							name="archive_monthly_dt" 
							id="archive_monthly_dt" 
							style="width: 200px" 
							type="text" 
							value="<?php echo htmlentities(grain_dtfmt_monthlyarchive(FALSE)); ?>" /><br />
						<div class="input_pad"><?php _e("The date or time format in the archive for a specific month.", "grain"); ?>
						<br />
							<?php 
								$value = str_replace('%FORMAT', grain_dtfmt_monthlyarchive(), __("The current selection (<code>%FORMAT</code>) results in '<code>%RESULT</code>' for the current time.", "grain")); 
								$value = str_replace('%RESULT', date(grain_filter_dt(grain_dtfmt_monthlyarchive())), $value); 
								echo $value;
							?>
						</div>
					</p>
					
					</fieldset>
					
					<fieldset>
					<legend><?php _e("Photo Info", "grain"); ?></legend>
					
					<p><label for="post_publish_dt"><?php _e("Time of comment:", "grain"); ?></label>
						<input 
							name="post_publish_dt" 
							id="post_publish_dt" 
							style="width: 200px" 
							type="text" 
							value="<?php echo htmlentities(grain_dtfmt_published(FALSE)); ?>" /><br />
						<div class="input_pad"><?php _e("The date and time format for the photo's 'published' date.", "grain"); ?>
						<br />
							<?php 
								$value = str_replace('%FORMAT', grain_dtfmt_published(), __("The current selection (<code>%FORMAT</code>) results in '<code>%RESULT</code>' for the current time.", "grain")); 
								$value = str_replace('%RESULT', date(grain_filter_dt(grain_dtfmt_published())), $value); 
								echo $value;
							?>
						</div>
					</p>
					
					</fieldset>
					
					<fieldset>
					<legend><?php _e("Per comment", "grain"); ?></legend>

					<p><label for="comments_date"><?php _e("Date of comment:", "grain"); ?></label>
						<input 
							name="comments_date" 
							id="comments_date" 
							style="width: 200px" 
							type="text" 
							value="<?php echo htmlentities(grain_dfmt_comments(FALSE)); ?>" /><br />
						<div class="input_pad"><?php _e("The date format for a comment.", "grain"); ?>
						<br />
							<?php 
								$value = str_replace('%FORMAT', grain_dfmt_comments(), __("The current selection (<code>%FORMAT</code>) results in '<code>%RESULT</code>' for the current time.", "grain")); 
								$value = str_replace('%RESULT', date(grain_filter_dt(grain_dfmt_comments())), $value); 
								echo $value;
							?>
						</div>
					</p>
					
					<p><label for="comments_time"><?php _e("Time of comment:", "grain"); ?></label>
						<input 
							name="comments_time" 
							id="comments_time" 
							style="width: 200px" 
							type="text" 
							value="<?php echo htmlentities(grain_tfmt_comments(FALSE)); ?>" /><br />
						<div class="input_pad"><?php _e("The time format for a comment.", "grain"); ?>
						<br />
							<?php 
								$value = str_replace('%FORMAT', grain_tfmt_comments(), __("The current selection (<code>%FORMAT</code>) results in '<code>%RESULT</code>' for the current time.", "grain")); 
								$value = str_replace('%RESULT', date(grain_filter_dt(grain_tfmt_comments())), $value); 
								echo $value;
							?>
						</div>
					</p>
					
					</fieldset>

					<!-- <input type="submit" name="defaults" value="<?php _e("Factory Defaults", "grain"); ?>" /> -->
					<input type="submit" class="defbutton" name="submitform" value="<?php _e("Save Settings", "grain"); ?>" />
					<input type="hidden" name="action" value="save" />
					<input type="hidden" name="datetime_form" value="true" />
			</form>
		</div>
		</div>
	</div>
	<?php 
	} // function grain_adminpage_datetime()



	function grain_adminpage_styling() {
		if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.__("Changes saved", "grain").'</strong></p></div>';
	?>
	<div class='wrap'>
		<div id="grain-header">
		<div id="nonJsForm">
			<form method="post" action="">
			
				<h2 id="first"><?php _e("Style Override", "grain"); ?></h2>
					<div class="zerosize"><input type="submit" name="defaultsubmit" value="Save" /></div>

				<fieldset>
					<legend><?php _e("Style Override settings", "grain"); ?></legend>

					<p><label for="css_override_file"><?php _e("CSS File:", "grain"); ?></label>
						<select 
							name="css_override_file" 
							id="css_override_file">
								<option value="">Keine</option>
						<?php 
							$files = grain_get_css_overrides();
							foreach($files as $file) {
								$selection = (grain_override_style(FALSE) == $file ? 'selected="selected"' : '');
								echo '<option value="'.$file.'" '.$selection.'>'.$file.'</option>';
							}
						?>	
						</select>
						<div class="input_pad"><?php _e("The file selected here is loaded in addition to the base stylesheet and allows for style overrides.", "grain"); ?>
						
						<?php 
	
							$style = grain_override_style();
							if( !empty($style) ) 
							{
								$style_overrides = grain_get_css_overrides();
								if( array_search($style, $style_overrides) !== FALSE ) 
								{
									$link_uri = get_bloginfo('url').'/wp-admin/theme-editor.php?file='.GRAIN_RELATIVE_PATH.'/'.grain_override_style().'&theme=Grain';
									$link = '<a target="_blank" href="'.$link_uri.'">'.grain_override_style(FALSE).'</a>';
									$message = __("You can edit the selected override file here: %FILE", "grain"); 
									$message = str_replace('%FILE', $link, $message);
									echo '<br />'.$message;
								}
							}							
						?>						
						
						</div>
						
					</p>		

				</fieldset>	
			
				<h2><?php _e("Missing Image", "grain"); ?></h2>
										
				<fieldset>
					<legend><?php _e("<em>Whoops</em> image", "grain"); ?></legend>
										
					
					<p><label for="whoops_uri"><?php _e("Whoops image URL:", "grain"); ?></label>
						<input 
							style="width: 350px" 
							type="text" 
							name="whoops_uri" 
							id="whoops_uri" 
							value="<?php echo htmlentities(grain_whoopsimage_url(FALSE)); ?>" />
						<br />
						<div class="input_pad">
							<?php _e("The URL of the image that will be displayed if a photoblog post has no image assigned or the image is not uploaded yet.<br />If you want to display a text instead of an image, leave the field empty.", "grain"); ?>
						</div>
					</p>
					
					<p><label for="whoops_width"><?php _e("Image size:", "grain"); ?></label>
							<input style="width: 50px" 
								type="text" 
								name="whoops_width" 
								id="whoops_width" 
								value="<?php echo htmlentities(grain_whoopsimage_width(FALSE)); ?>" /> 
							by
							<input style="width: 50px" 
								type="text" 
								name="whoops_height" 
								id="whoops_height" 
								value="<?php echo htmlentities(grain_whoopsimage_height(FALSE)); ?>" /> 
							<?php _e("Pixels"); ?><br />
							<div class="input_pad"><?php _e("The actual size of the replacement image.", "grain"); ?></div>
					</p>
					
					
				</fieldset>
			
				<h2><?php _e("Mosaic Settings", "grain"); ?></h2>
										
				<fieldset>
					<legend><?php _e("Mosaic settings", "grain"); ?></legend>
										
					<p><label for="show_mosaic_years"><?php _e("Show Years:", "grain"); ?></label> 
						<input style="margin-top: 8px;" 
							type="checkbox" 
							name="show_mosaic_years" 
							id="show_mosaic_years" 
							<?php if( grain_mosaic_years() ) echo ' checked="checked" ';?> 
							value="1" /> 
							<?php _e("Group thumbnails by years in the mosaic page", "grain"); ?><br />
					</p>
			
					
				</fieldset>
			
				<h2><?php _e("Thumbnail settings", "grain"); ?></h2>
										
				<fieldset>
					<legend><?php _e("Comments popup", "grain"); ?></legend>
										
					<p><label for="show_popup_thumbnail"><?php _e("Show Thumbnail:", "grain"); ?></label> <input style="margin-top: 8px;" type="checkbox" name="show_popup_thumbnail" id="show_popup_thumbnail" <?php if( grain_show_popup_thumb() ) echo ' checked="checked" ';?> value="1" /> <?php _e("Show the photo's thumbnail image on the comments popup", "grain"); ?><br />
					</p>
					
					<p><label for="popup_thumb_width"><?php _e("Thumbnail size:", "grain"); ?></label>
							<input style="width: 50px" type="text" name="popup_thumb_width" id="popup_thumb_width" value="<?php echo htmlentities(grain_popupthumb_width(FALSE)); ?>" /> 
							by
							<input style="width: 50px" type="text" name="popup_thumb_height" id="popup_thumb_height" value="<?php echo htmlentities(grain_popupthumb_height(FALSE)); ?>" /> 
							<?php _e("Pixels"); ?>
					</p>
					
					<p><label for="popup_thumb_stf"><?php _e("Size to fit:", "grain"); ?></label> <input style="margin-top: 8px;" type="checkbox" name="popup_thumb_stf" id="popup_thumb_stf" <?php if( grain_popupthumb_stf() ) echo ' checked="checked" ';?> value="1" /> <?php _e("Values given above are the maximum dimensions.", "grain"); ?><br />
							<div class="input_pad"><?php _e("If disabled, the thumbnail will be displayed in the size given above, cropping the parts that don't fit.<br />If it is enabled the thumbnail image will be resized so that it fits within these boundaries.", "grain"); ?></div>
					</p>
					
					
				</fieldset>
										
				<fieldset>
					<legend><?php _e("Mosaic & Archive", "grain"); ?></legend>
										
					<p><label for="show_tooltips"><?php _e("Show Tooltips:", "grain"); ?></label> <input style="margin-top: 8px;" type="checkbox" name="show_tooltips" id="show_tooltips" <?php if( grain_archive_tooltips() ) echo ' checked="checked" ';?> value="1" /> <?php _e("Show tooltip when hovering a thumbnail", "grain"); ?><br />
					</p>
					<p><label for="thumb_width"><?php _e("Thumbnail size:", "grain"); ?></label>
							<input style="width: 50px" type="text" name="thumb_width" id="thumb_width" value="<?php echo htmlentities(grain_mosaicthumb_width(FALSE)); ?>" /> 
							by
							<input style="width: 50px" type="text" name="thumb_height" id="thumb_height" value="<?php echo htmlentities(grain_mosaicthumb_height(FALSE)); ?>" /> 
							<?php _e("Pixels"); ?>
					</p>
					
					
				</fieldset>

				<h2><?php _e("Comments", "grain"); ?></h2>

				<fieldset>
					<legend><?php _e("Gravatar options", "grain"); ?></legend>

					<p><label for="use_gravatars"><?php _e("Use Gravatars", "grain"); ?></label> <input style="margin-top: 8px;" type="checkbox" name="use_gravatars" id="use_gravatars" <?php if( grain_eyecandy_use_gravatars() ) echo ' checked="checked" ';?> value="1" /> <?php _e("If enabled, <a href=\"http://www.gravatar.com/\" target=\"_blank\">Gravatar</a> icons are shown on user comments", "grain"); ?><br />
					<p><label for="gravatar_alternate"><?php _e("Alternative Image:", "grain"); ?></label>
						<input style="width: 350px" type="text" name="gravatar_alternate" id="gravatar_alternate" value="<?php echo htmlentities(grain_eyecandy_gravatar_alternate(FALSE)); ?>" /><br />
						<div class="input_pad"><?php _e("URL of an alternate image for the case the commenter has no Gravatar", "grain"); ?></div>
					</p>
					
				</fieldset>
				<fieldset>
					<legend><?php _e("Ring options", "grain"); ?></legend>
					
					<p><label for="use_pborg_button"><?php _e("photoblogs.org button: ", "grain"); ?></label> <input style="margin-top: 8px;" type="checkbox" name="use_pborg_button" id="use_pborg_button" <?php if( grain_eyecandy_use_pborg_button() ) echo ' checked="checked" ';?> value="1" /> <?php echo str_replace("%", __("bookmark me at photoblogs.org"), __("If enabled, a \"%\" button will be added to the comment form", "grain")); ?><br />
					</p>
					<p><label for="pborg_uri"><?php _e("Profile URL:", "grain"); ?></label>
						<input style="width: 350px" type="text" name="pborg_uri" id="pborg_uri" value="<?php echo htmlentities(grain_eyecandy_pborg_uri(FALSE)); ?>" /><br />
						<div class="input_pad"><?php _e("The URL of your <a href=\"http://photoblogs.org\" target=\"_blank\">photoblogs.org</a> profile", "grain"); ?></div>
					</p>
					
					<p><label for="use_coolpb_button"><?php _e("coolphotoblogs.com button: ", "grain"); ?></label> <input style="margin-top: 8px;" type="checkbox" name="use_coolpb_button" id="use_coolpb_button" <?php if( grain_eyecandy_use_coolpb_button() ) echo ' checked="checked" ';?> value="1" /> <?php echo str_replace("%", __("vote for me at coolphotoblogs.com", "grain"), __("If enabled, a \"%\" button will be added to the comment form", "grain")); ?><br />
					</p>
					<p><label for="coolpb_uri"><?php _e("Profile URL:", "grain"); ?></label>
						<input style="width: 350px" type="text" name="coolpb_uri" id="coolpb_uri" value="<?php echo htmlentities(grain_eyecandy_coolpb_uri(FALSE)); ?>" /><br />
						<div class="input_pad"><?php _e("The URL of your <a href=\"http://coolphotoblogs.com\" target=\"_blank\">coolphotoblogs.com</a> profile", "grain"); ?></div>
					</p>					
				
				</fieldset>
				
			
				<h2><?php _e("Effects Options", "grain"); ?></h2>
										
				<fieldset>
					<legend><?php _e("moo.fx options", "grain"); ?></legend>
										
					<p><label for="use_moofx"><?php _e("Use moo.fx:", "grain"); ?></label> 
						<input style="margin-top: 8px;" type="checkbox" name="use_moofx" id="use_moofx" <?php if( grain_eyecandy_use_moofx() ) echo ' checked="checked" ';?> value="1" /> 
						<strong><?php _e("Enable JavaScript effects (<a href=\"http://moofx.mad4milk.net/\" target=\"_blank\">moo.fx</a> engine)", "grain"); ?></strong><br />
					</p>
					<p><label for="use_moofx_reflec"><?php _e("Reflection effect:", "grain"); ?></label> <input style="margin-top: 8px;" type="checkbox" name="use_moofx_reflec" id="use_moofx_reflec" <?php if( grain_eyecandy_use_reflection() ) echo ' checked="checked" ';?> value="1" /> <?php _e("If enabled, the image reflects on the background", "grain"); ?><br />
					</p>
					<p><label for="use_moofx_fade"><?php _e("Fade effect:", "grain"); ?></label> <input style="margin-top: 8px;" type="checkbox" name="use_moofx_fade" id="use_moofx_fade" <?php if( grain_eyecandy_use_fader() ) echo ' checked="checked" ';?> value="1" /> <?php _e("Fade the image in instead of just display it", "grain"); ?><br />
							<div class="input_pad"><?php _e("The effect may be slow for some machines and is definitly slower for larger images, as well as complex theme styles. Double check that. In addition it requires support on the browser's side, so it isn't necessarily visible to every visitor.", "grain"); ?></div>
					</p>
					
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


	function grain_adminpage_copyright() {
		if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.__("Changes saved", "grain").'</strong></p></div>';
	?>
	<div class='wrap'>
		<div id="grain-header">
		<div id="nonJsForm">
			<form method="post" action="">
				<h2 id="first"><?php _e("Copyright Settings", "grain"); ?></h2>
					<div class="zerosize"><input type="submit" name="defaultsubmit" value="Save" /></div>
									
				<fieldset>
					<legend><?php _e("Short Copyright Message", "grain"); ?></legend>
				
					<label for="copyright_person"><?php _e("Copyright holder:", "grain"); ?></label><input style="width: 400px" type="text" name="copyright_person" id="copyright_person" value="<?php echo htmlentities(grain_copyright(FALSE, __("(Your name)", "grain"))); ?>" /> <?php _e("No HTML here", "grain"); ?><br />
					<label for="copyright_person_ex"><?php _e("Copyright holder (HTML):", "grain"); ?></label><input style="width: 400px" type="text" name="copyright_person_ex" id="copyright_person_ex" value="<?php echo htmlentities(grain_copyright_ex(FALSE, '<em>'.__("(Your name)", "grain").'</em>')); ?>" /> <?php _e("HTML allowed here", "grain"); ?><br />
					<label for="copyright_start_year"><?php _e("Start year:", "grain"); ?></label><input style="width: 150px" type="text" name="copyright_start_year" id="copyright_start_year" value="<?php echo grain_copyright_start_year(FALSE); ?>" /> <?php _e("% for current year", "grain"); ?> (<?php echo date('Y'); ?>)<br />
					<label for="copyright_end_year"><?php _e("End year:", "grain"); ?></label><input style="width: 150px" type="text" name="copyright_end_year" id="copyright_end_year" value="<?php echo grain_copyright_end_year_ex(FALSE); ?>" /> <?php _e("% for current year", "grain"); ?> (<?php echo date('Y'); ?>)<br />
					<label for="copyright_end_year_offset"><?php _e("Offset to end year:", "grain"); ?></label><input style="width: 150px" type="text" name="copyright_end_year_offset" id="copyright_end_year_offset" value="<?php echo grain_copyright_end_year_offset(FALSE); ?>" /> <?php

					$string = __("i.e. <code>%OFFSET</code> to get <code>%SUMMED_YEAR</code> if the end year is <code>%YEAR</code>", "grain");
					$string = str_replace( "%OFFSET", "10", $string);
					$string = str_replace( "%YEAR", date('Y'), $string);
					$string = str_replace( "%SUMMED_YEAR", (date('Y') + 10), $string);
					echo $string;

					?><br />
				</fieldset>
				
				<fieldset>
					<legend><?php _e("Imprint", "grain"); ?></legend>

					<p><?php _e("You can embed an URL to your imprint page in the head of the webpage via a <a href=\"http://dublincore.org/\" target=\"_blank\">Dublin Core</a> meta tag.", "grain"); ?></p>
				
					<p><label for="imprint_url"><?php _e("Imprint URL:", "grain"); ?></label>
						<input style="width: 400px" type="text" name="imprint_url" id="imprint_url" value="<?php echo htmlentities(grain_imprint_url(FALSE)); ?>" /> 
						<?php _e("No HTML here", "grain"); ?><br />
						<div class="input_pad"><?php _e("The URL to your imprint page. Leave empty if you do not want to embed an <code>DC.Rights</code> meta tag.", "grain"); ?></div>
					</p>

				</fieldset>									

				<h2><?php _e("Creative Commons License", "grain"); ?></h2>
				
				<fieldset>
					<legend><?php _e("Creative Commons Embedding", "grain"); ?></legend>
				
					<p>
						<?php 
							$message = __("If you like to, you can publish your photos under a <a href=\"http://creativecommons.org/\" target=\"_blank\">Creative Commons</a> License. You can choose a license <a href=\"http://creativecommons.org/license/?lang=%LANGCODE\" target=\"_blank\">here</a>.", "grain"); 
							$message = str_replace('%LANGCODE', grain_get_base_locale(), $message);
							echo $message;
						?>
						<?php _e("You may also want to read the <a href=\"http://wiki.creativecommons.org/Frequently_Asked_Questions\" target=\"_blank\">Frequently Asked Questions</a> page.", "grain"); ?>
					</p>
					<p><label for="cc_license_enabled"><?php _e("Copyright:", "grain"); ?></label> <input style="margin-top: 8px;" type="checkbox" name="cc_license_enabled" id="cc_license_enabled" <?php if( grain_cc_enabled() ) echo ' checked="checked" ';?> value="1" /> <strong><?php _e("Embed Creative Commons license", "grain"); ?></strong><br />
						<div class="input_pad"><?php _e("Unchecking this option will remove the Creative Commons Logo, the textual message, as well as the embedded RDF.", "grain"); ?></div>
					</p>
					<p><label for="cc_license_code"><?php _e("The License's HTML code:", "grain"); ?></label>
						<textarea class="license-code" cols="100" wrap="off" rows="10" name="cc_license_code" id="cc_license_code"><?php
							echo htmlentities(grain_cc_code(FALSE));
						?></textarea>
						<div class="input_pad">
							<?php _e("To simplify CSS styling of the theme, all occurrences of <code>&lt;br /&gt;</code>, <code>&lt;br/&gt;</code> and <code>&lt;br&gt;</code> tags will be removed when embedding the code.", "grain"); ?>
						</div>
					</p>
					
					<p>
						<?php _e("In addition to the code above you can choose to embed an <acronym title=\"Resource Description Framework\">RDF</acronym> representation of the license. If you don't want to do that or don't know what it does, you may that option.", "grain"); ?>
					</p>
					
					<p><label for="cc_license_rdf"><?php _e("The License's RDF code:", "grain"); ?></label>
						<textarea class="license-code" cols="100" wrap="off" rows="10" name="cc_license_rdf" id="cc_license_rdf"><?php
							echo htmlentities(grain_cc_rdf(FALSE));
						?></textarea>
						<div class="input_pad">
							<?php _e("The RDF code is another way of displaying the CC license that can be embedded into the Feeds, as well as the HTML pages.", "grain"); ?>
							<br /><?php _e("The RDF code for the license you selected by downloading and opening the template for the PDF or XML embedding on the license selection page.<br />The code begins with <code>&lt;rdf:RDF ...</code> and ends with <code>&lt;/rdf:RDF&gt;</code>.", "grain"); ?>
							<br /><?php _e("You can validate RDF encoded licenses with the <a href=\"http://validator.creativecommons.org/\" target=\"_blank\">CC RDF License Validator</a>.", "grain"); ?>
						</div>
					</p>
					<p>
						<label for="cc_rdf_feed"><?php _e("Embed RDF in Feeds:", "grain"); ?></label> 
						<input style="margin-top: 8px;" type="checkbox" name="cc_rdf_feed" id="cc_rdf_feed" <?php if( grain_ccrdf_feed_embed() ) echo ' checked="checked" ';?> value="1" />
						<?php _e("Embed the RDF in the Atom and RSS feeds", "grain"); ?><br />
						<div class="input_pad">
							<strong><?php _e("Only enable this option if you know what you are doing, as faulty RDF markup may render your feeds invalid.", "grain"); ?></strong>
						</div>
					</p>



				</fieldset>

					<!-- <input type="submit" name="defaults" value="<?php echo _e("Factory Defaults", "grain"); ?>" /> -->
					<input type="submit" class="defbutton" name="submitform" value="<?php _e("Save Settings", "grain"); ?>" />
					<input type="hidden" name="action" value="save" />
					<input type="hidden" name="copyright_form" value="true" />
			</form>
		</div>
		</div>
	</div>
	<?php 
	} // function grain_adminpage_copyright()


	function grain_adminpage_navigation() {
		if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.__("Changes saved", "grain").'</strong></p></div>';
	?>
	<div class='wrap'>
		<div id="grain-header">
		<div id="nonJsForm">
			<form method="post" action="">
			
				<h2 id="first"><?php _e("Navigation"); ?></h2>
					<div class="zerosize"><input type="submit" name="defaultsubmit" value="Save" /></div>
				
				<fieldset>
					<legend><?php _e("Navigation Bar Location", "grain"); ?></legend>
				
					<p><label for="navbar_location"><?php _e("Navigation Bar:", "grain"); ?></label>
						<select 
							name="navbar_location" 
							id="navbar_location">
								<option value="<?php echo GRAIN_IS_HEADER; ?>" <?php if(grain_navigation_bar_location(FALSE) == GRAIN_IS_HEADER) echo 'selected="selected"'; ?>><?php _e("Page header", "grain"); ?></option>
								<option value="<?php echo GRAIN_IS_BODY_BEFORE; ?>" <?php if(grain_navigation_bar_location(FALSE) == GRAIN_IS_BODY_BEFORE) echo 'selected="selected"'; ?>><?php _e("In front/top of the photo", "grain"); ?></option>
								<option value="<?php echo GRAIN_IS_BODY_AFTER; ?>" <?php if(grain_navigation_bar_location(FALSE) == GRAIN_IS_BODY_AFTER) echo 'selected="selected"'; ?>><?php _e("Behind/below the photo", "grain"); ?></option>
						</select>
						<div class="input_pad"><?php _e("Sets the position of the navigation bar.", "grain"); ?></div>
					</p>

				</fieldset>
				<fieldset>
					<legend><?php _e("On-Image navigation", "grain"); ?></legend>
				
					<p><label for="bidir_nav"><?php _e("Bidirectional Navigation:", "grain"); ?></label> <input style="margin-top: 8px;" type="checkbox" name="bidir_nav" id="bidir_nav" <?php if( grain_bidir_nav_enabled() ) echo ' checked="checked" ';?> value="1" /> <?php _e("Enable the bidirectional on-image navigation"); ?><br />
						<div class="input_pad"><?php _e("If enabled, the photo will contain links to the previous (older) and next (newer) photos. If disabled, only the previous link will be used.", "grain"); ?></div>
					</p>

				</fieldset>
				<fieldset>
					<legend><?php _e("About & Information Link", "grain"); ?></legend>
				
					<div class="zerosize"><input type="submit" name="defaultsubmit" value="Save" /></div>
					<p><label for="info_visible"><?php _e("About:", "grain"); ?></label> <input style="margin-top: 8px;" type="checkbox" name="info_visible" id="info_visible" <?php if( grain_info_enabled() ) echo ' checked="checked" ';?> value="1" /> <?php _e("Show the About link", "grain"); ?><br />
					</p>
					<p><label for="info_page_id"><?php _e("About page ID:", "grain"); ?></label>

						<?php /* <input style="width: 150px" type="text" name="info_page_id" id="info_page_id" value="<?php echo htmlentities(grain_infopage_id(FALSE, 1)); ?>" /> <?php _e("The page IDs can be found at the <a href=\"edit-pages.php\">Page Management</a>", "grain"); ?></a><br /> */ ?>
						
						<select 
							name="info_page_id" 
							id="info_page_id"
							style="width: 150px;">
						<?php
						
							$pages = get_pages();
							foreach($pages as $page) {
							
								echo '<option '.(grain_infopage_id() == $page->ID ? 'selected="selected"' : '').' value="'.$page->ID.'">'.$page->post_title.'</option>';
							}
						
						?>
						</select>
						
						<div class="input_pad"><?php
						
							/*
							Derzeit gew&auml;hlte Seite: <strong>ID #<?php echo htmlentities(grain_infopage_id(1)); ?></strong>: <?php*/
							$post = get_post(grain_infopage_id());
							$message = '<strong><span style="color: red;">'.__("Page does not exist.", "grain").'</span></strong> '.__("The link will be removed from the navigation bar.", "grain");
							if( $post != '' )
								$message = '<strong>'.$post->post_title.'</strong>';

						echo str_replace( "%SELECTION", '#' . htmlentities(grain_infopage_id(1)) . ': ' . $message, __("The currently selected page is: %SELECTION", "grain"));


						?><br />
						<?php 
							// let wordpress translate this
							$title = __("Write Page"); 
							// grain text again
							$message = __("You can create a new page in the \"%LINK\" menu.", "grain"); 							
							$url = get_bloginfo('url').'/wp-admin/page-new.php';
							$link = '<a target="_blank" href="'.$url.'">'.$title.'</a>';
							echo str_replace('%LINK', $link, $message);
						?>
						</div>
					</p>
				
				</fieldset>
				<fieldset>
					<legend><?php _e("Mosaic Page Link", "grain"); ?></legend>
				
					<p><label for="mosaic_visible"><?php _e("Mosaic:"); ?></label> <input style="margin-top: 8px;" type="checkbox" name="mosaic_visible" id="mosaic_visible" <?php if( grain_mosaic_enabled() ) echo ' checked="checked" ';?> value="1" /> <?php _e("Show \"Mosaic\" link in the navigation bar"); ?><br />
					</p>
					<p><label for="mosaic_page_id"><?php _e("ID of the Mosaic page:", "grain"); ?></label>
						<?php /* <input style="width: 150px" type="text" name="mosaic_page_id" id="mosaic_page_id" value="<?php echo htmlentities(grain_mosaicpage_id(FALSE, 1)); ?>" /> <?php _e("The page IDs can be found at the <a href=\"edit-pages.php\">Page Management</a>", "grain"); ?></a><br /> */?>
						
						<select 
							name="mosaic_page_id" 
							id="mosaic_page_id"
							style="width: 150px;">
						<?php
						
							$pages = get_pages();
							foreach($pages as $page) {
							
								echo '<option '.(grain_mosaicpage_id() == $page->ID ? 'selected="selected"' : '').' value="'.$page->ID.'">'.$page->post_title.'</option>';
							}
						
						?>
						</select>
						
						<div class="input_pad"><?php
						
							/*
							Derzeit gew&auml;hlte Seite: <strong>ID #<?php echo htmlentities(grain_infopage_id(1)); ?></strong>: <?php*/
							$post = get_post(grain_mosaicpage_id());
							$message = '<strong><span style="color: red;">'.__("Page does not exist.", "grain").'</span></strong> '.__("The link will be removed from the navigation bar.", "grain");
							if( $post != '' )
								$message = '<strong>'.$post->post_title.'</strong>';

						echo str_replace( "%SELECTION", '#' . htmlentities(grain_mosaicpage_id(1)) . ': ' . $message, __("The currently selected page is: %SELECTION", "grain"));


						?><br />
						<?php 
							// let wordpress translate this
							$title = __("Write Page"); 
							// grain text again
							$message = __("You can create a new page in the \"%LINK\" menu.", "grain"); 							
							$url = get_bloginfo('url').'/wp-admin/page-new.php';
							$link = '<a target="_blank" href="'.$url.'">'.$title.'</a>';
							echo str_replace('%LINK', $link, $message);
						?>
						</div>
					<p><label for="mosaic_title"><?php _e("Link Title:", "grain"); ?></label>
						<input style="width: 150px" type="text" name="mosaic_title" id="mosaic_title" value="<?php echo htmlentities(grain_mosaicpage_title(FALSE)); ?>" /> <?php _e("HTML allowed here", "grain"); ?><br />
						<div class="input_pad"><?php _e("This title will be shown in the navigation bar", "grain"); ?></div>
					</p>
					<p><label for="mosaic_count"><?php _e("Photos per page:", "grain"); ?></label>
						<input style="width: 150px" type="text" name="mosaic_count" id="mosaic_count" value="<?php echo htmlentities(grain_mosaic_count(FALSE)); ?>" /><br />
					</p>
				
				</fieldset>
				<fieldset>
					<legend><?php _e("Other options", "grain"); ?></legend>
				
					<p><label for="permalink_visible"><?php _e("Permalink:", "grain"); ?></label> <input style="margin-top: 8px;" type="checkbox" name="permalink_visible" id="permalink_visible" <?php if( grain_permalink_enabled() ) echo ' checked="checked" ';?> value="1" /> <?php _e("Show the Permalink in the Navigation Bar", "grain"); ?><br />
						<div class="input_pad"><?php _e("Unchecking this removes the permalink from the navigation. The permalink will still be visible at the Comments/Details popup (if it is not explicitely disabled otherwise).", "grain"); ?></div>
					</p>
									<p><label for="newest_visible"><?php _e("Newest Photo:", "grain"); ?></label> <input style="margin-top: 8px;" type="checkbox" name="newest_visible" id="newest_visible" <?php if( grain_newest_enabled() ) echo ' checked="checked" ';?> value="1" /> <?php _e("Show a link to the newest photo", "grain"); ?><br />
					</p>
					<p><label for="random_visible"><?php _e("Random Photo:", "grain"); ?></label> <input style="margin-top: 8px;" type="checkbox" name="random_visible" id="random_visible" <?php if( grain_random_enabled() ) echo ' checked="checked" ';?> value="1" /> <?php _e("Show a link to a random photo link", "grain"); ?>
					</p>
					
				</fieldset>

					<!-- <input type="submit" name="defaults" value="<?php echo _e("Factory Defaults", "grain"); ?>" /> -->
					<input type="submit" class="defbutton" name="submitform" value="<?php _e("Save Settings", "grain"); ?>" />
					<input type="hidden" name="action" value="save" />
					<input type="hidden" name="navigation_form" value="true" />
			</form>
		</div>
		</div>
	</div>
<?php 	
	}

	function grain_adminpage_general() {
		if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.__("Changes saved", "grain").'</strong></p></div>';
	?>
	<div class='wrap'>
		<div id="grain-header">
		<div id="nonJsForm">
			<form method="post" action="">
				<h2 id="first"><?php _e("Info and commenting mode", "grain"); ?></h2>
					<div class="zerosize"><input type="submit" name="defaultsubmit" value="Save" /></div>

				<fieldset>
					<legend><?php _e("Basic behavior", "grain"); ?></legend>

					<p><label for="enable_comments"><?php _e("Enable comments:", "grain"); ?></label> <input style="margin-top: 8px;" type="checkbox" name="enable_comments" id="enable_comments" <?php if( grain_comments_enabled() ) echo ' checked="checked" ';?> value="1" /> <?php _e("Allow the visitors to comment or view the comments", "grain"); ?><br />
						<div class="input_pad"><?php 
							$url = get_bloginfo('url') . '/wp-admin/options-discussion.php';
							$link = '<a href="'.$url.'" target="_blank">'.__("Discussion").'</a>';
							$message = __("If you disable that, visitors will neither be able to comment, nor to view any existing comments. You may want to disable wordpress' commenting options on the %LINK screen, too.", "grain"); 
							$message = str_replace('%LINK', $link, $message);
							echo $message;
						?></div>
					</p>

					<p><label for="extended_comments"><?php _e("Use Comments/Info popup:", "grain"); ?></label> <input style="margin-top: 8px;" type="checkbox" name="extended_comments" id="extended_comments" <?php if( !grain_extended_comments() ) echo ' checked="checked" ';?> value="1" /> <?php _e("Use a popup that displays the image's info as well as a comments form", "grain"); ?><br />
						<div class="input_pad"><?php _e("If you disable this, the \"Extended Info page\" will be used. This is a replacement for the comments popup, showing the info and comments right under the photo. One will still have to click the comments link to display it, though.", "grain"); ?></div>
					</p>
					
					<p><label for="enforce_extended_info"><?php _e("Always show info:", "grain"); ?></label> <input style="margin-top: 8px;" type="checkbox" name="enforce_extended_info" id="enforce_extended_info" <?php if( grain_enforce_info() ) echo ' checked="checked" ';?> value="1" /> <?php _e("Always show photo info regardless of the popup/extended info settings above", "grain"); ?><br />
						<div class="input_pad"><?php _e("If you always want to display the photo's title and it's text on the main page, enable this option. If you uncheck this option, be aware that the user won't see any information about your photo if you also disable the comments above.", "grain"); ?></div>
					</p>
					
					<p><label for="popup_jtc"><?php _e("Directly jump to comments:", "grain"); ?></label> <input style="margin-top: 8px;" type="checkbox" name="popup_jtc" id="popup_jtc" <?php if( grain_popup_jumptocomments() ) echo ' checked="checked" ';?> value="1" /> <?php _e("If the user clicks the link for the comments popup, jump directly to the comments", "grain"); ?><br />
						<div class="input_pad"><?php _e("This option only applies when the info is already shown on the main page, so that the visitor won't have to scroll to the comments on the popup.", "grain"); ?></div>
					</p>
										
				</fieldset>
				<fieldset>
					<legend><?php _e("Info display", "grain"); ?></legend>
					
					<p><label for="display_exif"><?php _e("Show EXIF info:", "grain"); ?></label> <input style="margin-top: 8px;" type="checkbox" name="display_exif" id="display_exif" <?php if( grain_exif_visible() ) echo ' checked="checked" ';?> value="1" /> <?php _e("If enabled, the Photo's EXIF information will be shown next to the text", "grain"); ?><br />
					<?php 
						$link_uri = get_bloginfo('url').'/wp-admin/theme-editor.php?file='.GRAIN_RELATIVE_PATH.'/exif-block.php&theme=Grain';
						$link = '<a target="_blank" href="'.$link_uri.'">exif-block.php</a>';
						$message = __("You can affect how the EXIF information is displayed by editing the file %FILE", "grain"); 
						$message = str_replace('%FILE', $link, $message);
						echo $message;
					?>
					</p>										
					
					<p><label for="show_permalink"><?php _e("Permalink:", "grain"); ?></label> <input style="margin-top: 8px;" type="checkbox" name="show_permalink" id="show_permalink" <?php if( grain_show_content_permalink() ) echo ' checked="checked" ';?> value="1" /> <?php _e("Show the permanlink in the image info", "grain"); ?><br />
					</p>
					
					<p><label for="show_comments_hint"><?php _e("Comment hint:", "grain"); ?></label> <input style="margin-top: 8px;" type="checkbox" name="show_comments_hint" id="show_comments_hint" <?php if( grain_show_comments_hint() ) echo ' checked="checked" ';?> value="1" /> <?php _e("Shows a link to the comments in the short info display", "grain"); ?><br />
					</p>
					<p><label for="show_dates"><?php _e("Show Date:", "grain"); ?></label> <input style="margin-top: 8px;" type="checkbox" name="show_dates" id="show_dates" <?php if( grain_show_content_dates() ) echo ' checked="checked" ';?> value="1" /> <?php _e("Display the date the photo was published", "grain"); ?><br />
					</p>
					
					<p><label for="show_categories"><?php _e("Category List:", "grain"); ?></label> <input style="margin-top: 8px;" type="checkbox" name="show_categories" id="show_categories" <?php if( grain_show_content_categories() ) echo ' checked="checked" ';?> value="1" /> <?php _e("Shows the categories in which the photo is filed"); ?><br />
					</p>
					<p><label for="before_categories"><?php _e("Begin category list:", "grain"); ?></label>
						<input style="width: 150px" type="text" name="before_categories" id="before_categories" value="<?php echo htmlentities(grain_begin_catlist(FALSE, __("Posted in: ", "grain"))); ?>" /> <?php _e("HTML allowed here", "grain"); ?><br />
						<div class="input_pad"><?php _e("This text will be enbedded prior to the category list"); ?></div>
					</p>
					<p><label for="after_categories"><?php _e("End category list:", "grain"); ?></label>
						<input style="width: 150px" type="text" name="after_categories" id="after_categories" value="<?php echo htmlentities(grain_end_catlist(FALSE)); ?>" /> <?php _e("HTML allowed here", "grain"); ?><br />
						<div class="input_pad"><?php _e("This text will be embedded after the category list", "grain"); ?></div>
					</p>
					
				</fieldset>

				<h2><?php _e("RSS and Atom Feed", "grain"); ?></h2>

				<fieldset>
					<legend><?php _e("Atom Feed Settings", "grain"); ?></legend>

					<p><label for="atom_feed"><img style="vertical-align: top; margin-top: -15px; margin-right: 10px;" alt="Atom Feed Logo" src="<?php echo bloginfo('template_directory'); ?>/images/atom_gray.gif" /> <?php _e("Atom Feed:", "grain"); ?></label> <input style="margin-top: 8px;" type="checkbox" name="atom_feed" id="atom_feed" <?php if( grain_atomfeed_enabled() ) echo ' checked="checked" ';?> value="1" /> <?php _e("Show the Atom Feed icon next to the RSS feed icon", "grain"); ?><br />
					</p>
					
				</fieldset>

				<h2><?php _e("Syndication Options", "grain"); ?></h2>

				<p><?php _e("Syndication data can (and has to be) changed in the <a href=\"theme-editor.php?file=wp-content/themes/grain/functions.syndication.php&theme=Grain\">functions.syndication.php</a> file.", "grain"); ?></p>

				<fieldset>
					<legend><?php _e("Flat Syndication", "grain"); ?></legend>

					<p><label for="flat_syndication"><?php _e("Flat Syndication:", "grain"); ?></label> <input style="margin-top: 8px;" type="checkbox" name="flat_syndication" id="flat_syndication" <?php if( grain_flat_syndication_enabled() ) echo ' checked="checked" ';?> value="1" /> <?php _e("Show Syndication in the footer", "grain"); ?><br />
					</p>
					<p><label for="flat_syndication_delimiter"><?php _e("Delimiter:", "grain"); ?></label>
						<input style="width: 150px" type="text" name="flat_syndication_delimiter" id="flat_syndication_delimiter" value="<?php echo htmlentities(grain_flat_delimiter(FALSE)); ?>" /> <?php _e("HTML allowed here", "grain"); ?><br />
						<div class="input_pad"><?php _e("This will separate the particular links", "grain"); ?></div>
					</p>

					<p><label for="sidebar_syndication"><?php _e("Sidebar Syndication:", "grain"); ?></label> <input style="margin-top: 8px;" type="checkbox" name="sidebar_syndication" id="sidebar_syndication" <?php if( grain_sidebar_syndication_enabled() ) echo ' checked="checked" ';?> value="1" /> <?php _e("Show Syndication in the Sidebar", "grain"); ?><br />
					</p>
				
				</fieldset>
	
				<h2><?php _e("Secondary Language Options", "grain"); ?></h2>

				<fieldset>
					<p><?php _e("Photo titles can be additionally displayed in a second language.", "grain"); ?></p>
				
					<legend><?php _e("General options", "grain"); ?></legend>

					  <p><label for="second_language"><?php _e("2nd Language:", "grain"); ?></label> <input style="margin-top: 8px;" type="checkbox" name="second_language" id="second_language" <?php if( grain_2ndlang_enabled() ) echo ' checked="checked" ';?> value="1" /> <?php _e("Enable 2nd language support", "grain"); ?><br />
					</p>
					<p><label for="second_language_tag"><?php _e("Custom field:"); ?></label>
						<input style="width: 150px" type="text" name="second_language_tag" id="second_language_tag" value="<?php echo htmlentities(grain_2ndlang_tag(FALSE)); ?>" /><br />
						<div class="input_pad"><?php _e("The name/tag of the custom field that contains the secondary language title", "grain"); ?></div>
					</p>
					
				</fieldset>

				<h2><?php _e("Sidebar"); ?></h2>

				<fieldset>
					<legend><?php _e("Most commented", "grain"); ?></legend>

					<p><label for="most_commented"><?php _e("Most commented:", "grain"); ?></label> <input style="margin-top: 8px;" type="checkbox" name="most_commented" id="most_commented" <?php if( grain_sidebar_mc_enabled() ) echo ' checked="checked" ';?> value="1" /> <?php _e("List most commented photos", "grain"); ?>
					</p>
					<p><label for="most_commented_count"><?php _e("Maximum count:", "grain"); ?></label>
						<input style="width: 150px" type="text" name="most_commented_count" id="most_commented_count" value="<?php echo htmlentities(grain_sidebar_mc_count(FALSE)); ?>" /><br />
						<div class="input_pad"><?php _e("Sets the maximum count of displayed entries", "grain"); ?></div>
					</p>
				
				</fieldset>
				<fieldset>
					<legend><?php _e("Other options", "grain"); ?></legend>
				
					<p><label for="calendar_enabled"><?php _e("Calendar:", "grain"); ?></label> <input style="margin-top: 8px;" type="checkbox" name="calendar_enabled" id="calendar_enabled" <?php if( grain_sidebar_calendar_enabled() ) echo ' checked="checked" ';?> value="1" /> <?php _e("Show calendar", "grain"); ?><br />
					</p>
					<p><label for="blogroll_enabled"><?php _e("Blogroll:", "grain"); ?></label> <input style="margin-top: 8px;" type="checkbox" name="blogroll_enabled" id="blogroll_enabled" <?php if( grain_sidebar_blogroll_enabled() ) echo ' checked="checked" ';?> value="1" /> <?php _e("Show Blogroll", "grain"); ?><br />
					</p>
					<p><label for="meta_enabled"><?php _e("Meta:", "grain"); ?></label> <input style="margin-top: 8px;" type="checkbox" name="meta_enabled" id="meta_enabled" <?php if( grain_sidebar_meta_enabled() ) echo ' checked="checked" ';?> value="1" /> <?php _e("Show Meta", "grain"); ?><br />
					</p>

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