<?php 
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/

	
	if(!defined('GRAIN_ADMINPAGE_LOADED') ) die(basename(__FILE__));
	

/* functions */


	function grain_adminpage_styling() 
	{
		grain_admin_inject_yapb_msg();
		
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

?>