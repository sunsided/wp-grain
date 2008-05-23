<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$	
*/
	
	session_start();

	/* Don't remove these lines. */
	add_filter('comment_text', 'popuplinks');
	while ( have_posts()) : the_post();

	// if this is a direct hit, redirect to a temporary extended info page ("one time info")
	if(!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) === FAlSE ) {

		if( grain_comments_enabled() ) {
			// append info to permalink, based on whether it contains an ampersand or not
			$contains_amp = strstr(get_permalink($post->ID), '?');
			$permalink = get_permalink($post->ID) . ($contains_amp !== FALSE ? '&oti=on' : '?oti=on');
		} else {
			$permalink = get_permalink($post->ID);
		}
		
		$_SESSION["GRAIN_FROM_COMPP"] = true;
		
		header('Location: '.$permalink);
		die();
	}

// check if comments are allowed
if( grain_comments_enabled() ) :

	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		 <title><?php echo get_settings('blogname'); ?> - <?php echo str_replace( "%TITLE", $post->post_title, __("Comments on %TITLE", "grain")); ?></title>

		<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_settings('blog_charset'); ?>" />
		<style type="text/css" media="screen">
			@import url( <?php bloginfo('stylesheet_url'); ?> );
		</style>

	</head>
	<body id="commentspopup">
	<div id="header">
		<div id="header-top"><div id="header-top-inner"></div></div>
		
		<div id="headerimg">
		<h1><a href="" title="<?php echo get_settings('blogname'); ?>"><?php echo get_settings('blogname'); ?></a></h1>
		</div>
		<div id="header-description"><?php bloginfo('description'); ?></div>

		<div id="header-bottom"><div id="header-bottom-inner"></div></div>
	</div>

	<div id="comment-page">

	<?php
		if(grain_show_popup_thumb()):

		// display thumbnail
		if ($post->image):

			// get image size
			$dimensions = getimagesize($post->image->systemFilePath());			 
			
			// scale image
			$width = grain_popupthumb_width();
			$height = grain_popupthumb_width();
			if( grain_popupthumb_stf() ) {
				$dimensions = grain_scale_image_size($dimensions, $width, $height); // max size
				$width = $dimensions['width'];
				$height = $dimensions['height'];
			}
			
		?>
		<img id="comment-thumb" src="<?php echo $post->image->getThumbnailHref(array('w='.$width,'h='.$height, 'zc=1')) ?>" alt="<?php the_title() ?>" title="<?php the_title() ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" />
	<?php endif; // if ($post->image):

		endif; // if(grain_show_popup_thumb()):

		$en_title = get_post_meta($post->ID, grain_2ndlang_tag(), true);
		$addon = FALSE;
		if( grain_2ndlang_enabled() && ( $en_title != null && $en_title != $post->post_title) ) $addon = TRUE;

		$exif_enabled = $post->image && grain_exif_visible();
		$exif_class = $exif_enabled ? 'exif' : 'no-exif';
		$subtitle_class = $addon ? 'has-subtitle' : 'no-subtitle';

						?>

						<div id="info-frame"><a name="info"></a>
						 <h2 id="title" class="<?php echo $subtitle_class; ?>"><?php the_title(); ?></h2>
						 <?php if($addon) echo '<h3 id="subtitle">'.$en_title.'</h3>'; ?>
						 <div id="content" class="<?php echo $exif_class; ?>"><?php the_content() ?></div>
						 <?php
							// display EXIF data
							if ($exif_enabled): ?>
								<div id="exif-frame">
								<?php include( TEMPLATEPATH .'/exif-block.php' ); ?>
								</div>
						<?php 	endif ?>


						<p style="clear: both;"><?php _e("The permalink address <acronym title=\"Uniform Resource Identifier\">(URI)</acronym> of this photo is:"); ?><br /><em><?php echo get_permalink(); ?></em></p>
						
	<?php					@include( TEMPLATEPATH.'/comments.php'); ?>
					</div>
	<?php

endif; // if( grain_comments_enabled() )

 // if you delete this the sky will fall on your head
endwhile;


// check if comments are allowed
if( grain_comments_enabled() ) :

	?>
	<p class="credit"><?php timer_stop(1); ?> <cite>Powered by <a href="http://wordpress.org" title="Powered by WordPress, state-of-the-art semantic personal publishing platform"><strong>Wordpress</strong></a></cite></p>

	<script type="text/javascript">
	<!--
	document.onkeypress = function esc(e) {
		if(typeof(e) == "undefined") { e=event; }
		if (e.keyCode == 27) { self.close(); }
	}
	// -->
	</script>
	</body>
	</html>

<?php

else:

	die(__("Comments are closed - You are not supposed to be here.", "grain"));

endif; // if( !grain_comments_enabled() ) :
?>