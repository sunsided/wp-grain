<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$	
*/
	
	session_start();

/* Don't remove these lines! */
add_filter('comment_text', 'popuplinks');
while ( have_posts()) : the_post();

	// if this is a direct hit, redirect to a temporary extended info page ("one time info")
	session_start();
	if( empty($_SESSION["GRAIN_PAGE_VISITED"]) ) {

		if( grain_can_comment() ) {
			// append info to permalink, based on whether it contains an ampersand or not
			$contains_amp = strstr(get_permalink($post->ID), '?');
			$permalink = get_permalink($post->ID) . ($contains_amp !== FALSE ? '&oti=on' : '?oti=on');
		} else {
			$permalink = get_permalink($post->ID);
		}

		// flag that we come from the popup, then leave		
		$_SESSION["GRAIN_FROM_COMPP"] = true;
		header('Location: '.$permalink);
		die();
	}

	// check if comments are allowed
	if( !grain_can_comment() ) :
	
		die(__("Comments are closed - You are not supposed to be here.", "grain"));
	
	else:
	
		define("GRAIN_IS_POPUP", true);
	
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	 <title>
	 		<?php echo str_replace( "%TITLE", $post->post_title, __("Comments on &quot;%TITLE&quot;", "grain")); ?> &laquo; <?php echo get_settings('blogname'); ?>
	 </title>
	
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_settings('blog_charset'); ?>" />
	
	<!-- meta -->
	<?php 
	grain_embed_general_meta();
	grain_embed_generator_meta();
	?>
	
	<!-- stylesheets -->
	<?php grain_embed_css(); ?>
	
	<!-- theme js -->
	<?php grain_embed_javascripts(); ?>	
</head>
	
<body id="body">
	
	<div id="commentspopup">
			
		<div id="header-complete">
			<div id="header">
				<div id="header-top"><div id="header-top-inner"></div></div>
				
				<div id="blogtitle-complete">
					<div id="headerimg">
						<h1 id="header-title"><?php echo get_settings('blogname'); ?></h1>
					</div>
					<div id="header-description">
						<a href="javascript:close();"><?php bloginfo('description'); ?></a>
					</div>
				</div>
			</div>
			<div id="header-bottom"><div id="header-bottom-inner"></div></div>
		</div>
	
		<div id="comment-page">
	
		<?php
			// show the popup
			grain_inject_popup_thumb();
	
			// prepare the second title line
			$en_title = grain_get_subtitle();
	
			// exif information
			$exif_enabled = grain_exif_visible();
			$exif_class = $exif_enabled ? 'exif' : 'no-exif';
			$subtitle_class = $en_title ? 'has-subtitle' : 'no-subtitle';
	
							?>
	
		<div id="info-frame">
			<a name="info"></a>
			<h2 id="title" class="<?php echo $subtitle_class; ?>"><?php the_title(); ?></h2>
			<?php 
				if($en_title) echo '<h3 id="subtitle">'.$en_title.'</h3>'.PHP_EOL;
			?>
			<div id="content" class="<?php echo $exif_class; ?>">
				<?php the_content() ?>
			</div>
	
			<?php
			// display EXIF data
			if ($exif_enabled) 
			{
				echo '<div id="exif-frame">'.PHP_EOL;;
				include( TEMPLATEPATH .'/exif-block.php' );
				echo '</div>'.PHP_EOL;
			}
			?>
	
			<p style="clear: both;"><?php _e("The permalink address <acronym title=\"Uniform Resource Identifier\">(URI)</acronym> of this photo is:"); ?><br /><em><?php echo get_permalink(); ?></em></p>
							
			<?php					
			// inject  the comments loop
			include( TEMPLATEPATH.'/comments.php'); 
			?>
		</div>
		
		
		<p class="credit"><?php timer_stop(1); ?> <cite>Powered by <a href="http://wordpress.org" title="Powered by WordPress, state-of-the-art semantic personal publishing platform"><strong>Wordpress</strong></a></cite></p>

		<script type="text/javascript">
		<!--
		document.onkeypress = function esc(e) {
			if(typeof(e) == "undefined") { e=event; }
			if (e.keyCode == 27) { self.close(); }
		}
		// -->
		</script>
	</div>
</body>
</html>		
		
		<?php
	
	endif; // if( grain_can_comment() )
	break;

endwhile; // <-- if you delete this the sky will fall on your head! seriously!

?>