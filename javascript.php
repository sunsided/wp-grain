<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));


	/* functions */

	function grain_embed_javascripts() 
	{
		global $GrainOpt;
		echo '<script type="text/javascript" src="'.GRAIN_TEMPLATE_DIR.'/js/boxover.js"></script>';
		
		if($GrainOpt->getYesNo(GRAIN_EYECANDY_MOOFX)) 
		{
			echo '<script type="text/javascript" src="'.GRAIN_TEMPLATE_DIR.'/js/mootools.v1.11.js"></script>';
			if($GrainOpt->getYesNo(GRAIN_EYECANDY_REFLECTION_ENABLED)) 
			{
				echo '<script type="text/javascript" src="'.GRAIN_TEMPLATE_DIR.'/js/reflection.js"></script>';
			}
		}
		
		// info for the comments popup
		if(!$GrainOpt->getYesNo(GRAIN_EXTENDEDINFO_ENABLED)) comments_popup_script(600, 600);
	}

?>