<?php 
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$	
*/


	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

	/* display photo EXIF data */
	
	global $GrainOpt;
	
	if ( null != ($exif = grain_get_exif()) ): 
			
		?>
			<table id="exif">
			<caption><?php _e("EXIF information", "grain"); ?></caption>
			<tbody>
			<?php 
			apply_filters('grain_exif', $exif);
			foreach ($exif as $key => $value): ?>
			<tr>
				<td class="exif-key"><?php echo $key ?></td>
				<td class="exif-value"><?php echo $value ?></td>
			</tr>
			<?php endforeach ?>
		
			</tbody>
			</table>
		<?php
	endif;

?>