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

	@require_once('../../../../../wp-blog-header.php');
	
	function grain_aerial_get_value($tag, $default) {
		return !(empty($_REQUEST[$tag])) ? urldecode($_REQUEST[$tag]) : $default;
	}
	
	// get parameters
	$file = urldecode($_REQUEST['file']);
	$id = grain_aerial_get_value($_REQUEST['id'], 'pano');
	$type = grain_aerial_get_value($_REQUEST['type'], 'MOV');
	$rot = grain_aerial_get_value($_REQUEST['rot'], 0);
	$zoom = grain_aerial_get_value($_REQUEST['zoom'], 1.0);
	$limzoom = grain_aerial_get_value($_REQUEST['limzoom'], '1.0, 1.0');
	$mousemode = grain_aerial_get_value($_REQUEST['mousemode'], 2);
	
?>(<?php echo $id; ?>)
	{
	   TYPE=<?php echo $type ?>;
	   FILE=<?php echo $file; ?>;
	   ROT=<?php echo $rot; ?>;
	   ZOOM=<?php echo $zoom; ?>;
	   LIMZOOM=<?php echo $limzoom; ?>;
	   MOUSEMODE=<?php echo $mousemode; ?>;
	}<?php
	
?>