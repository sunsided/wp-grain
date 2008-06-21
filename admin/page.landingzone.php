<?php 
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$

*//**

	Administration menu options for copyright settings
	
	@package Grain Theme for WordPress
	@subpackage Administration Menu
*/

	
	if(!defined('GRAIN_ADMINPAGE_LOADED') ) die(basename(__FILE__));
	

/* functions */


	/**
	 * grain_adminpage_landingzone() - Builds the landing zone page
	 *
	 * @since 0.3
	 */
	function grain_adminpage_landingzone() 
	{			
		global $GrainOpt, $knownPagesList;
	?>
	<div class='wrap'>
		
		<div id="grain-header">
			<h2><?php echo str_replace("%VERSION", GRAIN_THEME_VERSION_BASE, __("Welcome to Grain %VERSION", "grain")); ?></h2>
			
			<?php
			if( !grain_is_yapb_installed() ):
			?>
			<div class="error">
			<h3><?php _e("Grain is missing the Yet Another Photoblog plugin", "grain") ?></h3>
			<p><?php _e("Unfortunately the YAPB plugin could not be found. Reasons may be that it isn't installed yet or that it's just not activated.", "grain") ?><br />
			<?php 
				$msg = __("If you haven't installed it yet, you may want to download it <a href=\"%YAPB_URL\">here</a>. If you did not activate it, you can do so on the <a href=\"%PLUGINS_URL\">plugins</a> page.", "grain");
				echo str_replace( array( "%YAPB_URL", "%PLUGINS_URL" ),
								array( GRAIN_YAPB_URL, "./plugins.php" ),
								$msg);
			?></p>
			<p><?php _e("You can continue editing the options for Grain now, but your photoblog won't work until YAPB is running. Grain needs YAPB. Really.", "grain") ?></p>
			</div>
			<?php
			endif;
			?>
			
			<?php
			if( defined("GRAIN_THEME_VERSION_DEVBUILD") && GRAIN_THEME_VERSION_DEVBUILD ):
			?>
			<div class="information">
			<h3><?php _e("This is a development version", "grain") ?></h3>
			<p class="space_me"><?php _e("While this doesn't affect the functionality of your blog it may increase loading times and send information about your server and configuration to the visitor. Although no sensitive data (e.g., passwords) are being sent it could happen that the additional information can be abused to exploit your blog. Because of this it is advised to enable the development feature only for testing and debugging purposes.", "grain") ?></p>
			<p id="logging-state"><?php 
				$logging_enabled = $GrainOpt->is(GRAIN_DEBUG_LOGGING) && !defined("GRAIN_LOGGING_DISABLED_THE_HARD_WAY");
			
				$logging_msg = __("Logging is currently %CURRENT_STATE.", "grain");
				$state = '<span class="disabled">'.__("disabled", "grain").'</span>';
				if($logging_enabled) $state = '<span class="enabled">'.__("enabled", "grain").'</span>';
				echo str_replace("%CURRENT_STATE", $state, $logging_msg). " ";
				if(!$logging_enabled) 
					_e("No trace or debug messages are sent to the browser.", "grain");
				else
					_e("Trace or debug messages may be sent to the browser.", "grain");
			?></p>
			<p><?php _e("To get rid of this warning you need to disable development mode altogether. To do so, please delete the following file:", "grain"); ?><br />
				<code id="dev-filename">/<?php echo GRAIN_RELATIVE_PATH; echo GRAIN_DEV_TRIGGER; ?></code>
			</p>
			</div>
			<?php
			endif;
			?>
			<div id="grain-version-info">
			<p><?php 
				global $yapb;
				$msg = __("You are using %YAPB_VERSION with %GRAIN_VERSION.", "grain"); 
				echo str_replace( array( "%GRAIN_VERSION", "%YAPB_VERSION" ),
								array( "<span id=\"grain-version\">Grain ".GRAIN_THEME_VERSION."</span>", "<span id=\"yapb-version\">Yet Another Photoblog ".$yapb->pluginVersion."</span>" ),
								$msg);
			?>
			<?php 	
				$msg = __("Please check out the <a href=\"%PROJECT_URL\">Grain Wiki</a> for updates and support.", "grain"); 
				echo str_replace( "%PROJECT_URL",
								GRAIN_THEME_URL,
								$msg);
			?></p>
			</div>	
		
			<div id="grain-options-info">
			
				<h4><?php _e("Grain configuration options", "grain") ?></h4>
			
				<p><?php _e("Here you can control and fine-tune how Grain displays your photoblog (or parts of it).", "grain") ?> <?php _e("Unfortunately the options are a bit cluttered right now. This will hopefully change in a future version. :)", "grain") ?></p>

				<div id="grain-options-info-inner">

					<div id="general-options-block" class="grain-options-block">
						<div class="option-name"><a href="admin.php?page=<?php echo $knownPagesList["general"]; ?>"><?php _e("General Settings", "grain"); ?></a></div>
						<div class="option-description"><?php _e("Here you will find general options (e.g. if you want to use a popup for the comments or not) and all those options that didn't fit elsewhere.", "grain"); ?></div>
					</div>
				
					<div id="navigation-options-block" class="grain-options-block">
						<div class="option-name"><a href="admin.php?page=<?php echo $knownPagesList["navigation"]; ?>"><?php _e("Navigation", "grain"); ?></a></div>
						<div class="option-description"><?php _e("This is where you control how your visitors will navigate your photo stream. You can also control which items will appear in the menu bar here.", "grain"); ?></div>
					</div>
					
					<div id="styling-options-block" class="grain-options-block">
						<div class="option-name"><a href="admin.php?page=<?php echo $knownPagesList["styling"]; ?>"><?php _e("Styling and Layout", "grain"); ?></a></div>
						<div class="option-description"><?php _e("This holds options such as maximum image sizes, CSS stylesheets, Thumbnails, options for your Mosaic, visual effects and more.", "grain"); ?></div>
					</div>
					
					<div id="copyright-options-block" class="grain-options-block">
						<div class="option-name"><a href="admin.php?page=<?php echo $knownPagesList["copyright"]; ?>"><?php _e("Copyright Settings", "grain"); ?></a></div>
						<div class="option-description"><?php _e("This is where you set your name, imprint and special licenses such as a Creative Commons license.", "grain"); ?></div>
					</div>
					
					<div id="datetime-options-block" class="grain-options-block">
						<div class="option-name"><a href="admin.php?page=<?php echo $knownPagesList["datetime"]; ?>"><?php _e("Date and Time", "grain"); ?></a></div>
						<div class="option-description"><?php _e("This section is most probably only interesting to you if you don't use a localized version of Grain. Here you can set how dates and times are displayed to your visitors.", "grain"); ?></div>
					</div>
				
				</div>
			
				<div id="happy-photoblogging">
					<h4><?php _e("Final words", "grain") ?></h4>
					<p><?php _e("Happy photoblogging! :)", "grain") ?></p>
				</div>
			
			</div>
		
		</div>
	</div>
	<?php 
	} // function grain_adminpage_landingzone()

?>