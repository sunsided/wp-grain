<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

/* little helpers */

	function grain_rmlinebreaks($string) 
	{
		$breaks = array('<br />', '<br/>', '<br>');
		return str_replace($breaks, '', $string);
	}

?>