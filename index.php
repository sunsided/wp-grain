<?php 
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/

	grain_set_ispopup(FALSE);
	get_header(); 
	
	?>

	<div id="content-post" class="widecolumn">

<?php 

	// Prepare
	$grain_page_displayed = false;
	$hadPosts = have_posts();

	// Check if there are pages
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

			// check post type and for attached image
			if( grain_posttype($post->ID) == GRAIN_POSTTYPE_SPLITPOST ):
			
				grain_do_splitpost_logic();
			
			elseif( grain_posttype($post->ID) == GRAIN_POSTTYPE_PHOTO ):

				grain_do_regularpost_logic();
		
			endif; // if( grain_posttype() == GRAIN_POSTTYPE_PHOTO ):
		
			// inject navigation bar
			grain_inject_navigation_menu(GRAIN_IS_BODY_AFTER);

			// inject widget sidebar
			grain_inject_sidebar_below();
	
/*******************************************************************************************************************/				

				// check for extended info mode
				$regular_extended_mode = (GRAIN_REQUESTED_EXINFO && $GrainOpt->getYesNo(GRAIN_EXTENDEDINFO_ENABLED)) || GRAIN_REQUESTED_OTEXINFO;
				$enforced_extended_mode = $GrainOpt->getYesNo(GRAIN_CONTENT_ENFORCE_INFO);
				$extended_mode = $regular_extended_mode || $enforced_extended_mode;
				$is_folded = !$enforced_extended_mode || !$regular_extended_mode;
				$is_unfolded = !$is_folded;

				// dispatch
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
							
							if($GrainOpt->is(GRAIN_EXCERPTONLY) || $is_folded)
								the_excerpt();
							else 
							{
								the_content();
							}
							
						endif;
					
					?></div>

					<div id="meta" <?php if($GrainOpt->getYesNo(GRAIN_CONTENT_ENFORCE_INFO)) echo 'class="enforced"'; ?>>

					<?php edit_post_link(__("edit post", "grain"), '', ''); ?>
					
					<?php if($GrainOpt->getYesNo(GRAIN_CONTENT_DATES)): ?>
						<span id="content-date">
							<?php 
								the_time(grain_filter_dt($GrainOpt->get(GRAIN_DTFMT_PUBLISHED)));
							?>
						</span>
					
					<?php endif; ?>
					
					<?php if($GrainOpt->getYesNo(GRAIN_COMMENTS_ENABLED) && $GrainOpt->getYesNo(GRAIN_CONTENT_COMMENTS_HINT)): ?>
						<span id="comment-hint">
							<?php 
								echo grain_generate_comments_link(); 
							?>
						</span>
					<?php endif; ?>

					<?php if($GrainOpt->getYesNo(GRAIN_CONTENT_CATEGORIES)) : ?>
						<span id="post-categories">
						<?php 
							echo $GrainOpt->get(GRAIN_OPENTAGS_CATLIST);
							the_category($GrainOpt->get(GRAIN_CATLIST_SEPARATOR));
							echo $GrainOpt->get(GRAIN_CLOSETAGS_CATLIST);
						?>
						</span>
					<?php endif; ?>
					
					<?php if($GrainOpt->getYesNo(GRAIN_CONTENT_TAGS)) : ?>
						<span id="post-tags">
						<?php 
							if( the_tags( 
								$GrainOpt->get(GRAIN_OPENTAGS_TAGLIST), 
								$GrainOpt->get(GRAIN_TAGLIST_SEPARATOR), 
								$GrainOpt->get(GRAIN_CLOSETAGS_TAGLIST))):
								
								echo '<span id="post-tags">';
								the_tags(); 
								echo '</span>';
							
							endif;
						?>
					</span>
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
	if( $regular_extended_mode && grain_can_comment() ) {

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