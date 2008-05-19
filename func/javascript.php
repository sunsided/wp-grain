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
		echo '<script type="text/javascript" src="'.GRAIN_TEMPLATE_DIR.'/js/boxover.js"></script>';
		
		if(grain_eyecandy_use_moofx()) 
		{
			echo '<script type="text/javascript" src="'.GRAIN_TEMPLATE_DIR.'/js/mootools.v1.11.js"></script>';
			if(grain_eyecandy_use_reflection()) 
			{
				echo '<script type="text/javascript" src="'.GRAIN_TEMPLATE_DIR.'/js/reflection.js"></script>';
			}
		}
		
		// info for the comments popup
		if(!grain_extended_comments()) comments_popup_script(600, 600);
	}

?>