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
		</div>
	</div>
	<?php 
	} // function grain_adminpage_landingzone()

?>