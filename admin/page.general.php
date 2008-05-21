<?php 
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/

	
	if(!defined('GRAIN_ADMINPAGE_LOADED') ) die(basename(__FILE__));
	

/* functions */


	function grain_adminpage_general() 
	{
		grain_admin_inject_yapb_msg();
		
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

					<p><label for="atom_feed"><img style="vertical-align: top; margin-top: -15px; margin-right: 10px;" alt="Atom Feed Logo" src="<?php echo GRAIN_TEMPLATE_DIR; ?>/images/atom_gray.gif" /> <?php _e("Atom Feed:", "grain"); ?></label> <input style="margin-top: 8px;" type="checkbox" name="atom_feed" id="atom_feed" <?php if( grain_atomfeed_enabled() ) echo ' checked="checked" ';?> value="1" /> <?php _e("Show the Atom Feed icon next to the RSS feed icon", "grain"); ?><br />
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