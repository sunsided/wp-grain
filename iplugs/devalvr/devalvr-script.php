<?php
/*     
	This file is part of Grain Theme for WordPress.
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