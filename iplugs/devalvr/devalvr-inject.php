<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/

	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));
	
/* devalvr */

	function grain_devalvr_inject_param(&$arguments, $viewer) {
		$pairs = array();
		foreach($arguments as $param => $value) {
			$pairs[] = '"'.$param.'","'.$value.'"';
		}
		$list = implode($pairs, ',');
		
		?>
			viewerparameters("<?php echo $viewer; ?>",<?php echo $list; ?>);
		<?php
	}

	function grain_embed_devalvr($arguments) {
		$url = grain_plugin_url('devalvr');
		// sanity check
		if( empty($arguments['file']) ) return;
		
		// parameters
		$file = $arguments['file'];
		unset($arguments['file']);
		
		$width = empty($arguments['width']) ? GRAIN_MAX_IMAGE_WIDTH : $arguments['width'];
		unset($arguments['width']);
		
		$height = empty($arguments['height']) ? GRAIN_MAX_IMAGE_HEIGHT : $arguments['height'];
		unset($arguments['height']);
		
		?>
		<div id="panorama" <?php echo grain_use_reflection() ? 'class="photo-noborder"' : ''; ?> 
			style="height:<?php echo $height; ?>px; width:<?php echo $width; ?>px;">
		
		<script type="text/javascript" src="<?php echo $url; ?>/detectvr.js.php"></script>
		<script type="text/javascript"> 
			<?php
			if(!empty($arguments['ptviewer'])) {
				grain_ptviewer_defaults($arguments['ptviewer']);
				grain_devalvr_inject_param($arguments['ptviewer'], 'java');
			}
			elseif(!empty($arguments['flash'])) {
				grain_devalvr_inject_param($arguments['flash'], 'flash');
			}
			elseif(!empty($arguments['devalvr'])) {
				grain_devalvr_inject_param($arguments['devalvr'], 'devalvr');
			}
			elseif(!empty($arguments['spiv'])) {
				grain_devalvr_inject_param($arguments['spiv'], 'spiv');
			}
			elseif(!empty($arguments['qt'])) {
				grain_devalvr_inject_param($arguments['qt'], 'qt');
			}
			elseif(!empty($arguments['pangeavr'])) {
				grain_devalvr_inject_param($arguments['pangeavr'], 'pangeavr');
			}
			
			// files for different players
			$qt = '';
			$devalvr = '';
			$java = '';
			$flash = '';
			$spiv = '';
			
			// switch file type
			if( 
				(substr($file, -5) == '.jpeg') ||
				(substr($file, -4) == '.jpg') 
				) {
					$java = $file;
				}
			elseif( 
				(substr($file, -4) == '.mov') ||
				(substr($file, -3) == '.qt')
				) {
					$devalvr = $file;
					$qt = $file;
					$java = $file;
				}
			elseif( 
				(substr($file, -4) == '.dvr') 
				) {
					$devalvr = $file;
				}
				
			$devalvr = $file;
			
			
			?>
			viewerparameters("devalvr","filetype","jpg");
			writecode("<?php echo $qt; ?>","<?php echo $devalvr; ?>","<?php echo $java; ?>","<?php echo $flash; ?>","<?php echo $spiv; ?>","<?php echo $width; ?>","<?php echo $height; ?>");
		</script>
		
		</div>
		
		<?php
	}
	
?>