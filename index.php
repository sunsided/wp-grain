<?php 
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/



/*******************************************************************************************************************/				

	define("GRAIN_IS_POPUP", false);
	get_header(); 
	
	?>

	<div id="content-post" class="widecolumn">

<?php 

	$grain_page_displayed = false;
	$hadPosts = have_posts();

	if ($hadPosts):
	
		while( have_posts() ) : the_post();
		
			// skip private pages
			if( is_private() ) continue;
		
			// set the currently visited page
			grain_announce_page( $post->ID );
		
			// set a flag that we have displayed a page
			$grain_page_displayed = true;
		
			?>
			<div class="post" id="post-<?php the_ID(); ?>">

			<?php 

			// inject widget sidebar
			grain_inject_sidebar_above();

			// inject navigation bar
			grain_inject_navigation_menu(GRAIN_IS_BODY_BEFORE); 

			// prepare post
			if( $GrainOpt->getYesNo(GRAIN_2NDLANG_ENABLED) ) 
			{
				$en_title = get_post_meta($post->ID, $GrainOpt->get(GRAIN_2NDLANG_TAG), true);
				$addon = "";
				if( $en_title != null && $en_title != $post->post_title ) $addon = "<br /><span class='thin'>" . $en_title .'</span>';
			}

			// get surrounding posts
			$previous = get_previous_post();
			$next = get_next_post();

			// compose messages
			$state = 0;
			if( $previous != null ) :
				$message_left = __("&lArr; click for previous photo", "grain");
			else:
				$message_left = __("(this is the first photo)", "grain");
				$state = -1;
			endif;

			if( $next != null ) :
				$message_right = __("click for next photo &rArr;", "grain");
			else:
				$message_right = __("(this is the newest photo)", "grain");
				$state = 1;
			endif;


			// check post type and for attached image
			if( grain_posttype($post->ID) == GRAIN_POSTTYPE_SPLITPOST ):
			
				echo '<div id="special-frame">';
				
				// action
				do_action(GRAIN_BEFORE_PANORAMA);
				
				if( !grain_use_reflection() ) 
					echo '<div class="photo-withborder">';
				else
					echo '<div class="photo-noborder">';
					
			
				$path = $post->image->systemFilePath();
				$image_url = get_bloginfo('url').$post->image->uri;
				// get image size
				$image_dimensions = getimagesize($path);					
				
				/*
				grain_embed_ptviewer( 
					array( 
						'file' => $image_url,
						'pwidth' => $image_dimensions[0], 
						'pheight' => $image_dimensions[1]
					));
				*/
				
				//$image_url = get_bloginfo('template_directory').'/iplugs/devalvr/testfiles/testfileForQT.mov';
				
				grain_embed_devalvr( array( 
					'file' => $image_url,
					'ptviewer' => 
						array( 
							'pwidth' => $image_dimensions[0], 
							'pheight' => $image_dimensions[1],
							'wait' => GRAIN_TEMPLATE_DIR.'/images/loading.gif',
							'panmin' => '-90',
							'panmax' => '90'
						)
					));
			
				// output the 'special' content here
				echo grain_get_the_special_content();
				
				echo '</div>'; // photo-(with)?border
				
				// action
				do_action(GRAIN_AFTER_PANORAMA);
				
				echo '</div>'; // special-frame
			
				// inject reflection script
				// grain_inject_reflec_script('panorama-applet');
			
			elseif( grain_posttype($post->ID) == GRAIN_POSTTYPE_PHOTO ):

		
				$whoops_url = $GrainOpt->get(GRAIN_WHOOPS_URL);
				
				echo '<div id="photo-frame">';
					
				// check if the post has no image
				// test if there is no replacement image as well
				if (!grain_post_has_image() && empty($whoops_url) )
				{
					grain_inject_photopage_error(__("This photo page is not ready yet. Please check back later.", "grain"));
				} 
				else
				{
					
					// get image path and URL
					if ( !grain_post_has_image() )
					{
						//$path = $whoops_path;
						$image_url = $whoops_url;
						// get image size
						$image_dimensions = array ( $GrainOpt->get(GRAIN_WHOOPS_WIDTH), $GrainOpt->get(GRAIN_WHOOPS_HEIGHT) );
					}
					else
					{
						$path = $post->image->systemFilePath();
						$image_url = $post->image->uri;					
						// get image size
						$image_dimensions = getimagesize($path);	
					}
	
						
					// scale image
					$dimensions = grain_scale_image_size($image_dimensions, $GrainOpt->get(GRAIN_MAX_IMAGE_WIDTH), $GrainOpt->get(GRAIN_MAX_IMAGE_HEIGHT)); // max width, max height
					$width = $dimensions['width'];
					$height = $dimensions['height'];
					$width2 = $dimensions['halfWidth'];
					$rigth_width = $dimensions['halfWidth2'];

					// get reduced size image if neccessary
					$flag = $image_dimensions[0] > GRAIN_MAX_IMAGE_WIDTH;
					$quality=100;
					if ( grain_post_has_image() ):
						if( $flag ):
							$image_url = $post->image->getThumbnailHref(array('w='.$width,'h='.$height,'q='.$quality));
						else:
							$image_url = $post->image->uri;
						endif;
					endif;

					// compose tooltips
					if(!$GrainOpt->getYesNo(GRAIN_EYECANDY_USE_MOOTIPS)) {
						// $title_prev = 'cssbody=[tooltip-text-prev] cssheader=[tooltip-title-prev] header=['.$post->post_title. $addon .'] body=['.$message_left.']';
						$title_prev = grain_thumbnail_title($post->post_title. $addon, $message_left);
						//$title_next = 'cssbody=[tooltip-text-next] cssheader=[tooltip-title-next] header=['.$post->post_title. $addon .'] body=['.$message_right.']';
						$title_next = grain_thumbnail_title($post->post_title. $addon, $message_right);
					}
					else
					{
						//$title_prev = $post->post_title. $addon .' :: '.$message_left;
						$title_prev = $post->post_title. ' :: '.$message_left;
						$title_next = $post->post_title. ' :: '.$message_right;
					}
					
					$link_prev = ($previous != null ) ? get_permalink($previous->ID) : '#';
					$link_next = ($next != null ) ? get_permalink($next->ID) : '#';

					$title_attr = "";
					if( $state != 0 )
					{
						$title_attr = 'title="';
						$title_attr .= ($state > 0) ? $title_next : $title_prev;
						$title_attr .= '"';
					}
					
					/*
					$loading_width = 100; $loading_height = 100;
					$loading_position_left = intval(($width2 - $loading_width / 2));
					$loading_position_top = intval(($height / 2 - $loading_height / 2));
					*/
						
					// build image link and link map
					$string =  '';
					if( function_exists("grain_bidir_nav_enabled") && grain_bidir_nav_enabled() )
					{				
						if( !grain_use_reflection() ) $string .= '<div class="photo-withborder">';
						
						$string .= '<img '.$title_attr.' id="photo" alt="'. $post->post_title . '" class="photo'.(grain_eyecandy_use_reflection()? '-noborder' : '-withborder' ).'" style="width: '.$width.'px; height: '.$height.'px;" src="'. $image_url .'" usemap="#bloglinks" />';
						
						if( !grain_use_reflection() ) $string .= '</div>';
						
						$string .= ' <map name="bloglinks">'."\n";
						
						if( $previous != null )
							$string .= '<area shape="rect" class="tooltipped" coords="0,0,'.$width2.','.$height.'" title="'.$title_prev.'" rel="prev" href="'. get_permalink($previous->ID) .'">'."\n";
						if( $next != null )
							$string .= '<area shape="rect" class="tooltipped" coords="'.$width2.',0,'.($rigth_width+$width2).','.$height.'" title="'.$title_next.'" rel="next" href="'. get_permalink($next->ID) .'">'."\n";

						$string .= '</map>';
							
					}
					else
					{
						if( $previous != null )
							$string = '<a class="tooltipped" title="'.$title_prev.'" rel="prev" href="'. get_permalink($previous->ID) .'"><img title="'.$title_prev.'" id="photo" alt="'. $post->post_title . '" class="photo'.(grain_eyecandy_use_reflection()? '-noborder' : '-withborder' ).'" style="width: '.$width.'px; height: '.$height.'px;" src="'. $image_url .'"/></a>';
						else
							$string = '<img '.$title_attr.' id="photo" alt="'. $post->post_title . '" class="photo'.($GrainOpt->get(GRAIN_EYECANDY_REFLECTION_ENABLED)? '-noborder' : '-withborder' ).'" style="width: '.$width.'px; height: '.$height.'px;" src="'. $image_url .'"/>';
						
					}

					// action
					do_action(GRAIN_BEFORE_IMAGE);				
					
					// display image
					if( grain_fx_can_fade() ) echo '<div id="photo-fade">';
					echo $string;				
					if( grain_fx_can_fade() ) echo '</div>';
					
					// action
					do_action(GRAIN_AFTER_IMAGE);
					
					// inject reflection script
					grain_inject_reflec_script('photo');

				} // (!grain_post_has_image() && empty($whoops_url) ) 

				echo '</div>		<!-- <div id="photo-frame"> -->';
				//echo '</div>		<!-- <div id="loading-frame"> -->';
		
			endif; // if( grain_posttype() == GRAIN_POSTTYPE_PHOTO ):
		
				// inject navigation bar
				grain_inject_navigation_menu(GRAIN_IS_BODY_AFTER);
	
				// inject widget sidebar
				grain_inject_sidebar_below();
	
/*******************************************************************************************************************/				

				// check for extended info mode
				//$extended_mode = (isset($_SESSION['grain:info']) && ($_SESSION['grain:info'] == 'on') && grain_extended_comments()) || (isset($_SESSION['grain:oti']) && ($_SESSION['grain:oti'] == 'on'));
				$extended_mode = (GRAIN_REQUESTED_EXINFO && $GrainOpt->getYesNo(GRAIN_EXTENDEDINFO_ENABLED)) || 
								GRAIN_REQUESTED_OTEXINFO || 
								$GrainOpt->getYesNo(GRAIN_CONTENT_ENFORCE_INFO);

				if ( $extended_mode ):
				
					$en_title = get_post_meta($post->ID, $GrainOpt->get(GRAIN_2NDLANG_TAG), true);
					$addon = FALSE;
					if( $GrainOpt->getYesNo(GRAIN_2NDLANG_ENABLED) && ( $en_title != null && $en_title != $post->post_title) ) $addon = TRUE;

					$exif_enabled = $post->image && $GrainOpt->getYesNo(GRAIN_EXIF_VISIBLE);
					$exif_class = $exif_enabled ? 'exif' : 'no-exif';
					$subtitle_class = $addon ? 'has-subtitle' : 'no-subtitle';
					
					$photo_style = '';
					if( $GrainOpt->getYesNo(GRAIN_EYECANDY_REFLECTION_ENABLED) ) {
						$photo_style = 'position: relative; top: -'.$top_offset.'px;';
					}
					?>

					<div id="info-frame" style="<?php echo $photo_style; ?>"><a name="info"></a>

					
					<?php if(grain_can_inject_moofx_slide()) : ?>
					<a href="#" id="content-toggle" name="content-toggle">
					<?php endif; ?>
										 <h2 id="title" class="<?php echo $subtitle_class; ?>"><?php the_title(); ?></h2>

					<?php if(grain_can_inject_moofx_slide()) : ?>
					</a>			 
					<?php endif; ?>
					
					
					<?php if($addon) echo '<h3 id="subtitle">'.$en_title.'</h3>'; ?>
					 
					 <div id="content">
					 
					 <?php
						// display EXIF data
						if ($exif_enabled): ?>
							<div id="exif-frame">
							<?php include( TEMPLATEPATH .'/exif-block.php' ); ?>
							</div>
					<?php 	endif ?>
					
					<div id="infotext-frame" class="<?php echo $exif_class; ?>"><?php 

						if( grain_posttype($post->ID) == GRAIN_POSTTYPE_SPLITPOST ):
							// output the basic content
							echo grain_get_the_content();
						else:
							// output the full content here
							//echo get_the_content();
							the_content();
						endif;
					
					?></div>

					<div id="meta" <?php if($GrainOpt->getYesNo(GRAIN_CONTENT_ENFORCE_INFO)) echo 'class="enforced"'; ?>>

					<?php edit_post_link(__("edit post", "grain"), '', ''); ?>
					
					<?php if($GrainOpt->getYesNo(GRAIN_CONTENT_DATES)): ?>
						<span id="content-date">
							<?php 
								the_time(grain_filter_dt(grain_dtfmt_published()));
							?>
						</span>
					
					<?php endif; ?>
					
					<?php if(/*!$extended_mode &&*/ $GrainOpt->getYesNo(GRAIN_COMMENTS_ENABLED) && $GrainOpt->getYesNo(GRAIN_CONTENT_COMMENTS_HINT)): ?>
						<span id="comment-hint">
							<?php 
								echo grain_generate_comments_link(); 
							?>
						</span>
					<?php endif; ?>

					<?php if($GrainOpt->getYesNo(GRAIN_CONTENT_CATEGORIES)) : ?>
						<span id="post-categories">
						<?php 
							echo grain_begin_catlist(TRUE, __("Posted in: ", "grain"));
							the_category(grain_catlist_separator());
							echo grain_end_catlist();
						?>
						</span>
					<?php endif; ?>
					
					<?php if($GrainOpt->getYesNo(GRAIN_CONTENT_TAGS)) : ?>
						<?php 
							if( the_tags( 
								grain_begin_taglist(TRUE, __("Tagged with: ", "grain")), 
								grain_taglist_separator(), 
								grain_end_taglist())):
								
								echo '<span id="post-tags">';
								the_tags(); 
								echo '</span>';
							
							endif;
						?>
					<?php endif; ?>
					
					<?php if( $GrainOpt->getYesNo(GRAIN_CONTENT_PERMALINK_VISIBLE) ):  ?>
						<span id="permalink-container"><?php _e("The permalink address <acronym title=\"Uniform Resource Identifier\">(URI)</acronym> of this photo is:", "grain"); ?> <span id="permalink"><?php echo get_permalink(); ?></span></span>
					<?php endif; ?>
					</div>
					
					</div> <!-- id content -->
					
<?php					

/*******************************************************************************************************************/

	// recheck for extended info mode, since info enforcement could be enabled
	// $GrainOpt->getYesNo(GRAIN_COMMENTS_ON_EMPTY_ENABLED)
	//if( $extended_mode && $GrainOpt->getYesNo(GRAIN_COMMENTS_ENABLED) ) {
	if( $extended_mode && grain_can_comment() ) {

		include( TEMPLATEPATH.'/comments.php'); 
	
	}
	
/*******************************************************************************************************************/	
	
					// inject widget sidebar
					grain_inject_sidebar_footer();
	
	?>
				</div>
			<?php
				endif;  // grain:info
			?>				
			</div>

		<?php 

		// ensure that only ONE item is displayed
		break; // // while (have_posts()) : the_post();
		endwhile; // while (have_posts()) : the_post();

	endif;
;

	// if there was no post or if we could not find one to display, show an error message
	if( !$hadPosts || $grain_page_displayed === false ) 
	{
		// set the currently visited page
		grain_announce_page( 0 );
		
		// display an error
		if( grain_getpostcount() == 0 ) 
		{
			grain_inject_photopage_error(__("There is currently no page to display. Please check back later.", "grain"));	
		}
		else 
		{
			grain_inject_photopage_error(__("There will be a search page soon.", "grain"));	
		}
	}
	
	?>
	</div>

<?php 

	get_footer(); 
	grain_endSession();

?>