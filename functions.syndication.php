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

	function grain_initialize_syndication() {

		grain_add_to_syndication( 
			'Photoblogs.org',
			'Photoblogs.org - The Photoblogging Resource', 
			'http://www.photoblogs.org/', 
			false, 
			'http://buttons.photoblogs.org/photoblogs02.gif' );
			
		grain_add_to_syndication( 
			'CoolPhotoblogs', 
			'CoolPhotoblogs', 
			'http://www.coolphotoblogs.com/?do=profile&id=1324', 
			false, 
			'http://www.defx.de/iconic/cool_6.gif' );
			
		grain_add_to_syndication( 
			'I am VFXY', 
			'VFXY Photos', 
			'http://photos.vfxy.com/', 
			true, 
			'http://photos.vfxy.com/img/iam_vfxy.jpg' );
	}

?>