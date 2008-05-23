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
			$permalink = get_permalink($post->ID) . ($contains_amp !== FALSE ? '&'.GRAIN_OTI_KEY.'=on' : '?'.GRAIN_OTI_KEY.'=on');
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
	
		// flag that this is a popup
		grain_set_ispopup(TRUE);
		get_header();
	

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
				<?php 
				
					if($GrainOpt->is(GRAIN_EXCERPTONLY))
						the_excerpt();
					else 
					{
						$_SESSION["GRAIN_FROM_COMPP"] = true;
						$the_content = apply_filters("the_content", get_the_content());
						$the_content = str_replace("?p=".$post->ID, "?p=".$post->ID."&".GRAIN_OTI_KEY."=on", $the_content);
						echo $the_content;
						
					}
					
				?>
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
	
			<span class="permalink-info"><?php _e("The permalink address <acronym title=\"Uniform Resource Identifier\">(URI)</acronym> of this photo is:"); ?><span class="the-permalink"><?php echo get_permalink(); ?></span></span>
							
			<?php					
			// inject  the comments loop
			include( TEMPLATEPATH.'/comments.php'); 
			?>
		</div>
		
		<?php /*
		
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

*/

get_footer();

?>
		
		<?php
	
	endif; // if( grain_can_comment() )
	break;

endwhile; // <-- if you delete this the sky will fall on your head! seriously!

?>