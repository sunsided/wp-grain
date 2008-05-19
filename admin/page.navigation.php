<?php 
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/

	
	if(!defined('GRAIN_ADMINPAGE_LOADED') ) die(basename(__FILE__));
	

/* functions */


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

?>