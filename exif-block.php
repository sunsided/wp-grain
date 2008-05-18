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

	/* display photo EXIF data */
	
	if (function_exists('yapb_get_exif')): 
		$exif = yapb_get_exif();	
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
	
	<?php else: ?>
		<p><?php _e("This photo has no EXIF data.", "grain"); ?></p>
	<?php endif

?>