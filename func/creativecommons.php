<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

/* Option keys */

	@require_once(TEMPLATEPATH . '/func/options.php');

/* Shortcut functions helper */

	function grain_embed_cc_div() 
	{
		echo '<div id="license-text"><!--Creative Commons License-->';
		
		// Get the code and remove linebreaks so that the HTML doesn't look too jagged
		$code = grain_cc_code();
		$code = grain_rmlinebreaks($code);
		echo $code;
		
		echo '<!--/Creative Commons License--></div>';
	}

?>