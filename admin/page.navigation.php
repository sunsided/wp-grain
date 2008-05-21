<?php 
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/

	
	if(!defined('GRAIN_ADMINPAGE_LOADED') ) die(basename(__FILE__));
	

/* functions */


	function grain_adminpage_navigation() 
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
			
				<h2 id="first"><?php _e("Navigation"); ?></h2>
					<div class="zerosize"><input type="submit" name="defaultsubmit" value="Save" /></div>
				
				<fieldset>
					<legend><?php _e("On-Image navigation", "grain"); ?></legend>
					<?php
					grain_admin_infoline(NULL, __("This setting controls what happens when a visitor clicks on the photo.", "grain"));
					grain_admin_checkbox(GRAIN_NAV_BIDIR_ENABLED, "bidir_nav", NULL, __("Bidirectional Navigation:", "grain"), __("Enable the bidirectional on-image navigation", "grain"), __("If enabled, the photo will contain links to the previous (older) and next (newer) photos. If disabled, only the previous link will be used.", "grain"));
					?>
				</fieldset>
				
				<fieldset>
					<legend><?php _e("Newest & Random", "grain"); ?></legend>
					<?php
					grain_admin_checkbox(GRAIN_MNU_NEWEST_VISIBLE, "newest_visible", NULL, __("Newest Photo:", "grain"), __("Show a link to the newest photo", "grain"), NULL);
					grain_admin_checkbox(GRAIN_MNU_RANDOM_VISIBLE, "random_visible", NULL, __("Random Photo:", "grain"), __("Show a link to a random photo", "grain"), NULL);
					?>
				</fieldset>
				
				<fieldset>
					<legend><?php _e("About & Information Link", "grain"); ?></legend>
					<div class="zerosize"><input type="submit" name="defaultsubmit" value="Save" /></div>
					<?php 
					
					grain_admin_checkbox(GRAIN_MNU_INFO_VISIBLE, "info_visible", NULL, __("Enable \"About\":", "grain"), __("Show the About link", "grain"), NULL);
				
					// select the ID of the about page
				
					$message = __("Select the page you want to use as your info page", "grain");
					if( 0 == grain_getpagecount() ) $message = __("You currently have no pages.", "grain");
				
					$url = get_bloginfo('url').'/wp-admin/page-new.php';
					$link = '<a target="_blank" href="'.$url.'">'.__("Write Page").'</a>';
					$description = str_replace('%LINK', $link, __("You can create a new page in the \"%LINK\" menu.", "grain"));
					
					grain_admin_pageselector(GRAIN_INFOPAGE_ID, "info_page_id", NULL, __("About page:", "grain"), $message, $description);
					?>
				</fieldset>	
				
				</fieldset>
				<fieldset>
					<legend><?php _e("Mosaic Page Link", "grain"); ?></legend>
					<?php
				
					grain_admin_checkbox(GRAIN_MOSAIC_ENABLED, "mosaic_visible", NULL, __("Enable \"Mosaic\":", "grain"), __("Show \"Mosaic\" link in the navigation bar", "grain"), NULL);
				
					// select the ID of the about page
				
					$message = __("Select the page you want to use as your mosaic page", "grain");
					if( 0 == grain_getpagecount() ) $message = __("You currently have no pages.", "grain");
				
					$url = get_bloginfo('url').'/wp-admin/page-new.php';
					$link = '<a target="_blank" href="'.$url.'">'.__("Write Page").'</a>';
					$description = str_replace('%LINK', $link, __("You can create a new page in the \"%LINK\" menu.", "grain"));
					
					grain_admin_pageselector(GRAIN_MOSAIC_PAGEID, "mosaic_page_id", NULL, __("Mosaic page:", "grain"), $message, $description);
				
					// get other properties
				
					grain_admin_shortline(GRAIN_MOSAIC_LINKTITLE, "mosaic_title", NULL, __("Link Title:", "grain"), $no_HTML, __("This title will be shown in the navigation bar.", "grain"));
					?>		
				</fieldset>
				<fieldset>
					<legend><?php _e("Permalink Navigation item", "grain"); ?></legend>
					<?php
					grain_admin_checkbox(GRAIN_MNU_PERMALINK_VISIBLE, "permalink_visible", NULL, __("Permalink:", "grain"), __("Show the Permalink in the Navigation Bar", "grain"), __("Unchecking this removes the permalink from the navigation. The permalink will still be visible at the Comments/Details popup (if it is not explicitely disabled otherwise).", "grain"));
					grain_admin_shortline(GRAIN_MNU_PERMALINK_TEXT, "permalink_title", NULL, __("Permalink title:", "grain"), $HTML_allowed, __("This title will be shown in the navigation bar.", "grain"));
					?>
				</fieldset>

				<fieldset>
					<legend><?php _e("Navigation Bar Location", "grain"); ?></legend>
					<?php
					
					$default = $GrainOpt->getDefault(GRAIN_NAVBAR_LOCATION);
					$options = array( 
						GRAIN_IS_HEADER => __("Page header", "grain"),
						GRAIN_IS_BODY_BEFORE => __("In front/top of the photo", "grain"),
						GRAIN_IS_BODY_AFTER => __("Behind/below the photo", "grain")
						);
					$message = __("This sets the position of the navigation bar. Normally you want this to be '<code>{DEFAULT}</code>', but if you have special plans you may choose another position that better fits your needs.", "grain");
					$message = str_replace("{DEFAULT}", $options[$default], $message);
					grain_admin_combobox(GRAIN_NAVBAR_LOCATION, "navbar_location", NULL, $options, __("Location:", "grain"), NULL, $message );
					
					?>
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