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
		grain_admin_inject_yapb_msg();
		
	?>
	<div class='wrap'>
		<div id="grain-header">
		<h2>Temporary Landing Zone</h2>
		</div>
	</div>
	<?php 
	} // function grain_adminpage_landingzone()

?>