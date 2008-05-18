<?php
/*     
	This file is part of Grain Theme for WordPress.

    Grain Theme for WordPress is free software: you can redistribute it 
	and/or modify it under the terms of the GNU General Public License 
	as published by the Free Software Foundation, either version 3 of 
	the License, or (at your option) any later version.

    Grain Theme for WordPress is distributed in the hope that it will 
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
	of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Grain Theme for WordPress.  
	If not, see <http://www.gnu.org/licenses/>.
	
	------------------------------------------------------------------
	File version: $Id$
*/

	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));
	
/* ptviewer */

	define('GRAIN_PTVIEWER_FLAVOR', '-senore-2.8');
	//define('GRAIN_PTVIEWER_FLAVOR', '-dersch-3.1.2');
	//define('GRAIN_PTVIEWER_FLAVOR', '-sf-2.5.04'); // ptviewer.sourceforge.net

	function grain_ptviewer_inject_param(&$arguments, $tag, $remove=TRUE, $default=null) {
		$value = empty($arguments[$tag]) ? $default : $arguments[$tag];
		if( empty($value) ) return; 
		?>
			<param name="<?php echo $tag; ?>" value="<?php echo $value; ?>" />
		<?php
		// remove tag
		unset($arguments[$tag]);
	}

	function grain_ptviewer_defaults(&$arguments) {
		$wait_url_default = get_bloginfo('template_directory').'/images/loading.gif';
		if( empty($arguments['wait'])) $arguments['wait'] = $wait_url_default;
		
		if( empty($arguments['width'])) $arguments['width'] = GRAIN_MAX_IMAGE_WIDTH;
		if( empty($arguments['height'])) $arguments['height'] = GRAIN_MAX_IMAGE_HEIGHT;
		
		if( empty($arguments['view_width'])) $arguments['view_width'] = GRAIN_MAX_IMAGE_WIDTH;
		if( empty($arguments['view_height'])) $arguments['view_height'] = GRAIN_MAX_IMAGE_HEIGHT;
		if( empty($arguments['view_x'])) $arguments['view_x'] = 0;
		if( empty($arguments['view_y'])) $arguments['view_y'] = 0;
		
		if( empty($arguments['mass'])) $arguments['mass'] = 10;
	}

	function grain_embed_ptviewer($arguments) {
		$url = grain_plugin_url('ptviewer');
		// sanity check
		if( empty($arguments['file']) ) return;
	
		grain_ptviewer_defaults($arguments);
	
		// parameters
		$file = $arguments['file'];
		unset($arguments['file']);
		
		$width = $arguments['width'];
		unset($arguments['width']);
		
		$height = $arguments['height'];
		unset($arguments['height']);
	
		// embed code
		?>
		
		<div id="panorama" <?php echo grain_use_reflection() ? 'class="photo-noborder"' : ''; ?> 
			style="height:<?php echo $height; ?>px; width:<?php echo $width; ?>px;">
		<applet id="panorama-applet" 
			archive="<?php echo $url; ?>/ptviewer<?php echo GRAIN_PTVIEWER_FLAVOR; ?>.jar" 
			code="ptviewer.class" 
			width="<?php echo $width; ?>" 
			height="<?php echo $height; ?>">
			<param name="file" value="<?php echo $file; ?>">
		<?php
			// see documentations for params: http://webuser.fh-furtwangen.de/~dersch/PTVJ/doc.html

			// insert arguments that are not yet removed
			foreach($arguments as $param => $value) {
				grain_ptviewer_inject_param($arguments, $param, $param, FALSE);	
			}
			
			// action
			do_action(GRAIN_PANO_APPLET_PARAMS);
		?>

		</applet>
		</div>
		
		<?php
	}
	
?>