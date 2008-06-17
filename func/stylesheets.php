<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));


	/* functions */

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