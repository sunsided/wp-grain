<?php

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