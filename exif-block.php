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
			
		do_action(GRAIN_BEFORE_EXIFBLOCK);
		
		if(!$GrainOpt->is(GRAIN_EXIF_RENDER_INLINE)):
		?>
			<table id="exif">
			<caption><span><?php _e("EXIF information", "grain"); ?></span></caption>
			<tbody>
			<?php 
			$exif = apply_filters(GRAIN_EXIF, $exif);
			foreach ($exif as $key => $value): 
				// filter values
				$key = apply_filters(GRAIN_EXIF_KEY, $key);	
				$value = apply_filters(GRAIN_EXIF_VALUE, $value, $key);
				// skip empty values
				if( empty($key) || empty($value) ) continue;
			?>
			<tr id="exif-<?php echo $key; ?>">
				<td class="exif-key"><span><?php echo $key ?></span></td>
				<td class="exif-value"><span><?php echo $value ?></span></td>
			</tr>
			<?php endforeach ?>
		
			</tbody>
			</table>
		<?php
		else: // GRAIN_EXIF_RENDER_INLINE
		?>
			<div id="exif">
				<div id="exif-caption"><span><?php _e("EXIF information", "grain"); ?></span></div>
				<div id="exif-body">
				<?php 
				$exif = apply_filters(GRAIN_EXIF, $exif);
				foreach ($exif as $key => $value): 
					// filter values
					$key = apply_filters(GRAIN_EXIF_KEY, $key);	
					$value = apply_filters(GRAIN_EXIF_VALUE, $value, $key);
					// skip empty values
					if( empty($key) || empty($value) ) continue;
				?>
					<div id="exif-<?php echo $key; ?>">
						<span class="exif-key"><span><?php echo $key ?></span></span>
						<span class="exif-value"><span><?php echo $value ?></span></span>
					</div>
				<?php endforeach ?>
			
				</div>
			</div>			
		<?php
		endif; // !GRAIN_EXIF_RENDER_INLINE
		
		
		do_action(GRAIN_AFTER_EXIFBLOCK);
		
	endif;

?>