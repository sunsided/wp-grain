<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$

*//**

	Creative Commons helper functions
	
	@package Grain Theme for WordPress
	@subpackage Creative Commons
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

/* Option keys */

	@require_once(TEMPLATEPATH . '/func/options.php');

	/**
	 * grain_embed_cc_div() - Injects the Creative Commons license markup
	 *
	 * @since 0.3
	 * @global $GrainOpt			Grain options
	 */
	function grain_embed_cc_div() 
	{
		global $GrainOpt;
		echo '<div id="license-text"><!--Creative Commons License-->';
		
		// Get the code and remove linebreaks so that the HTML doesn't look too jagged
		$code = $GrainOpt->get("GRAIN_COPYRIGHT_CC_CODE");
		$code = grain_rmlinebreaks($code);
		echo $code;
		
		echo '<!--/Creative Commons License--></div>';
	}
	
	/**
	 * grain_embed_cc_rdf() - Injects the Creative Commons license RDF
	 *
	 * @since 0.3
	 * @global $GrainOpt			Grain options
	 */
	function grain_embed_cc_rdf() 
	{
		global $GrainOpt;
		
		if( !$GrainOpt->get(GRAIN_COPYRIGHT_CC_ENABLED) ) return;

		// test rdf
		$rdf = $GrainOpt->get(GRAIN_COPYRIGHT_CC_RDF);
		if(!empty($rdf)) echo '<!--'.$rdf.'-->';
	}

	/**
	 * grain_get_cc_license_url() - Gets the Creative Commons license URL from the markup
	 *
	 * @since 0.3
	 * @global $GrainOpt			Grain options
	 */
	function grain_get_cc_license_url() 
	{
		global $GrainOpt;
		if( !$GrainOpt->get(GRAIN_COPYRIGHT_CC_ENABLED) ) return;
		
		// Get the code and remove linebreaks so that the HTML doesn't look too jagged
		$code = $GrainOpt->get("GRAIN_COPYRIGHT_CC_CODE");
		// <a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/2.0/en/">
		$pattern = '#href="?(http://creativecommons.org/licenses/[^"]+)"?#i';
		preg_match($pattern, $code, $matches);
		return $matches[1];
	}

?>