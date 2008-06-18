<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$

*//**

	Stylesheet functions
	
	@package Grain Theme for WordPress
	@subpackage Stylesheets
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));


	/* functions */

	/**
	 * grain_embed_css() - Injects the HTML links to the CSS files used by Grain
	 *
	 * @global $GrainOpt Grain options
	 * @see grain_get_css_overrides()
	 */
	function grain_embed_css() 
	{
		global $GrainOpt;
		
		?>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<?php
		
		$style_override = $GrainOpt->get(GRAIN_STYLE_OVERRIDE);
		$style_overrides = grain_get_css_overrides();
		if( array_search($style_override, $style_overrides) !== FALSE ) 
		{
		?>
<link rel="stylesheet" href="<?php echo GRAIN_TEMPLATE_DIR; ?>/<?php echo $style_override; ?>" type="text/css" media="screen" />
<?php
		}	
	}
	
/* Helper: Override Styles */

	/**
	 * grain_get_css_overrides() - Gets an array of override stylesheet found in Grain's theme directory
	 *
	 * @return array An array of filenames
	 */
	function grain_get_css_overrides() {
		$regexp = '#style\.override([-\.].+|\d+)?(\.css(\.php)?|\.pcss)#i';
		$result = array();
		
		if ($dh = opendir(TEMPLATEPATH)) {
			while (($file = readdir($dh)) !== false) {
				$path = TEMPLATEPATH.'/'.$file;
				
				if(is_file($path)) {
					if( preg_match($regexp, $file) ) {
						$result[] = $file;
					}
				}
			}
			closedir($dh);
		}
		return $result;
    }

?>