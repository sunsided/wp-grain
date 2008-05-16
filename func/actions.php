<?php
/*     
	This file is part of Grain Theme for WordPress.

    Grain Theme for WordPress is free software: you can redistribute it 
	and/or modify it under the terms of the GNU General Public License 
	as published by the Free Software Foundation, either version 3 of 
	the License, or (at your option) any later version.

    Foobar is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Grain Theme for WordPress.  
	If not, see <http://www.gnu.org/licenses/>.
	
	------------------------------------------------------------------
	File version: $Id$
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

/* define filters */
	
	define('GRAIN_BEFORE_IMAGE', 'grain_before_image');
	define('GRAIN_AFTER_IMAGE', 'grain_after_image');
	define('GRAIN_BEFORE_PANORAMA', 'grain_before_pano');
	define('GRAIN_AFTER_PANORAMA', 'grain_after_pano');
	define('GRAIN_PANO_APPLET_PARAMS', 'grain_panoAppParams');
	
	define('GRAIN_PHOTO_PAGE_ERROR', 'grain_photo_page_error');
	
	define('GRAIN_ARCHIVE_BEFORE_THUMB', 'grain_archive_before_thumb');
	define('GRAIN_ARCHIVE_AFTER_THUMB', 'grain_archive_after_thumb');
	
?>