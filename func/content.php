<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$

*//**

	Content helper functions
	
	@package Grain Theme for WordPress
	@subpackage Content
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));


/* functions */

	/** A "default" post type, with a photo and some text */
	define('GRAIN_POSTTYPE_PHOTO', 'photo');
	
	/** An "user defined" post type, where the "photo" content is build from the text */
	define('GRAIN_POSTTYPE_SPLITPOST', 'split-post');

	/**
	 * grain_posttype() - Gets the Grain post type of a given post
	 *
	 * Since post types are not implemented yet, this function always
	 * returns GRAIN_POSTTYPE_PHOTO;
	 *
	 * @since 0.2
	 * @param int $post_id 				The post's id
	 * @param string $default 			Optional. The default type.
	 * @return string 					The type of the current post.
	 */
	function grain_posttype($post_id, $default=GRAIN_POSTTYPE_PHOTO) 
	{
		return GRAIN_POSTTYPE_PHOTO;
	}

	/**
	 * grain_has_content() - Checks wheter the current post has any (text) content
	 *
	 * @since 0.3
	 * @uses $post						The current post object
	 * @return bool TRUE if the current post has any (textual) content
	 */
	function grain_has_content() {
		global $post;
		return strlen($post->post_content) != 0;
	}
	
	/**
	 * grain_get_the_content() - Gets the part of the content that is before the "more" tag
	 *
	 * In addition to the "get_the_content" and "the_content" filters, a filter on 
	 * "GRAIN_GET_THE_CONTENT" is also applied.
	 *
	 * @since 0.3
	 * @uses $post						The current post object
	 * @return string The content before the "more" tag
	 */	
	function grain_get_the_content() {
		global $post;
	
		$array = explode('<!--more-->', $post->post_content);
		//$content = get_the_content('', 0, '');
		$content = $array[0];
		$content = apply_filters('get_the_content', $content);
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]&gt;', $content);
		$content = apply_filters(GRAIN_GET_THE_CONTENT, $content);
		return $content;
	}
	
	/**
	 * grain_get_the_special_content() - Gets the part of the content that is after the "more" tag
	 *
	 * In addition to the "get_the_content" and "the_content" filters, a filter on 
	 * "GRAIN_THE_SPECIAL_CONTENT" is also applied.
	 *
	 * @since 0.3
	 * @uses $post						The current post object
	 * @return string The content after the "more" tag or NULL, if no "more" tag was set
	 */	
	function grain_get_the_special_content() {
		global $post;
	
		$array = explode('<!--more-->', $post->post_content);
		//$content = get_the_content('', 0, '');
		if( count($array) <= 1 ) return NULL;
		$content = $array[1];
		$content = apply_filters('get_the_content', $content);
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]&gt;', $content);
		$content = apply_filters(GRAIN_THE_SPECIAL_CONTENT, $content);
		return $content;
	}

/* Common post tasks */

	/**
	 * grain_prepare_post_logic() - Prepares for the _postlogic functions
	 *
	 * @since 0.3
	 * @access private
	 * @global $GrainOpt					Grain options
	 * @return array An associative array of certain post information
	 */	
	function grain_prepare_post_logic() {
	
		global $GrainOpt, $post; //, $previous, $next, $pagePosition, $message_left, $message_right;
	
		// this array will hold the options
		$returnValue = array();
	
		// prepare post
		if( $GrainOpt->getYesNo(GRAIN_2NDLANG_ENABLED) ) 
		{
			$en_title = get_post_meta($post->ID, $GrainOpt->get(GRAIN_2NDLANG_TAG), true);
			$returnValue["addon"] = "";
			if( $en_title != null && $en_title != $post->post_title ) $returnValue["addon"] = "<br /><span class='thin'>" . $en_title .'</span>';
		}

		// get surrounding posts
		$returnValue["previous"] = get_previous_post();
		$returnValue["next"] = get_next_post();

		// compose messages
		$returnValue["pagePosition"] = GRAIN_SURROUNDED_POST;
		if( $returnValue["previous"] != null ) {
			$returnValue["message_left"] = __("&lArr; click for previous photo", "grain");
		} else {
			$returnValue["message_left"] = __("(this is the first photo)", "grain");
			$returnValue["pagePosition"] = GRAIN_FIRST_POST;
		}

		if( $returnValue["next"] != null ) {
			$returnValue["message_right"] = __("click for next photo &rArr;", "grain");
		} else {
			$returnValue["message_right"] = __("(this is the newest photo)", "grain");
			$returnValue["pagePosition"] = GRAIN_NEWEST_POST;
		}
	
		// return the information
		return $returnValue;
	}

/* Regular photo page */

	/**
	 * grain_do_regularpost_logic() - Does the logic for a regular post (GRAIN_POSTTYPE_PHOTO)
	 *
	 * This renders the a default photo block.
	 *
	 * @since 0.3
	 * @global $GrainOpt					Grain options
	 */	
	function grain_do_regularpost_logic() {
		global $GrainOpt, $post;

		// prepare post
		$PostOpt = grain_prepare_post_logic();
		$next = $PostOpt["next"]; $previous = $PostOpt["previous"];
	
		// The URL of the image that will be displayed if no photo could be found for a post
		$whoops_url = $GrainOpt->get(GRAIN_WHOOPS_URL);
		
		// Test for reflection
		$useReflection = grain_use_reflection();
		
		// open the region
		echo '<div id="photo-frame" class="'.($useReflection?"with-reflection":"no-reflection").'">';
			
		// If the post has no image and no replacement ("whoops") image, display an error text
		$hasImage = grain_post_has_image();
		if (!$hasImage && empty($whoops_url) )
		{
			grain_inject_photopage_error(__("This photo page is not ready yet. Please check back later.", "grain"));
		} 
		else
		{
			// get image path and URL
			if ( $hasImage )
			{
				$path = $post->image->systemFilePath();
				$image_url = $post->image->uri;					
				$image_dimensions = getimagesize($path);	
			}
			else
			{
				$image_url = $whoops_url;
				$image_dimensions = array ( $GrainOpt->get(GRAIN_WHOOPS_WIDTH), $GrainOpt->get(GRAIN_WHOOPS_HEIGHT) );				
			}

				
			// scale image
			$dimensions = grain_scale_image_size($image_dimensions, $GrainOpt->get(GRAIN_MAX_IMAGE_WIDTH), $GrainOpt->get(GRAIN_MAX_IMAGE_HEIGHT)); // max width, max height
			$width = $dimensions['width']; $height = $dimensions['height']; $width2 = $dimensions['halfWidth']; $rigth_width = $dimensions['halfWidth2'];

			// the size of the actual photo
			$sizeStyle = 'width: '.$width.'px; height: '.$height.'px;';

			// get reduced size image if necessary
			$imageIsBiggerThanMax = $image_dimensions[0] > GRAIN_MAX_IMAGE_WIDTH;
			$quality = 100; // <- this value can be altered to set the quality (percent) of the rescaled image
			if ( $hasImage && $imageIsBiggerThanMax ) {
				$image_url = $post->image->getThumbnailHref(array('w='.$width,'h='.$height,'q='.$quality));
			}

			// compose tooltips
			$tooltips = grain_compose_post_tooltips($PostOpt["message_left"], $PostOpt["message_right"], $PostOpt["addon"] );
			$title_prev = $tooltips["prev"]; $title_next = $tooltips["next"];

			// Show tooltips
			$tips = $GrainOpt->is(GRAIN_EYECANDY_IMAGE_TOOLTIPS);
			if( !$tips ) { $title_prev = NULL; $title_next = NULL; }
						
			// get links
			$link_prev = ($previous != null ) ? get_permalink($previous->ID) : '#';
			$link_next = ($next != null ) ? get_permalink($next->ID) : '#';

			// tweak title
			$title_attr = "";
			if( $tips && ($PostOpt["pagePosition"] != GRAIN_SURROUNDED_POST) )
			{
				$title_attr = 'title="';
				$title_attr .= ($PostOpt["pagePosition"] == GRAIN_NEWEST_POST) ? $title_next : $title_prev;
				$title_attr .= '"';
			}
				
			// Image map
			$useImageMap = FALSE;
		
			// build image link and link map
			$string =  '';
			if( $GrainOpt->is(GRAIN_NAV_BIDIR_ENABLED) )
			{							
				// prepare non-reflection
				if( !$useReflection ) $string .= '<div class="photo" style="'.$sizeStyle.'">';
				
				// add photo
				$string .= '<img '.$title_attr.' id="photo" alt="'. $post->post_title . '" class="photo'.($useReflection? '-with-reflection' : '' ).'" style="'.$sizeStyle.'" src="'. $image_url .'"'.($useImageMap?' usemap="#bloglinks"':'').' />';
				
				// add links if the linkmap is not used
				if( !$useImageMap && ($previous || $next) ) {
					$string .= '<div id="linkmap" '.$title_attr.'>';
					if( $previous != null )	$string .= '<a id="area_prev" class="tooltipped bidir" title="'.$title_prev.'" rel="prev" style="width: '.$width2.'px; height: '.$height.'px;" href="'. get_permalink($previous->ID) .'"></a>';
					if( $next != null ) 	$string .= '<a id="area_next" class="tooltipped bidir" title="'.$title_next.'" rel="next" style="width: '.$width2.'px; height: '.$height.'px;" href="'. get_permalink($next->ID    ) .'"></a>';
					$string .= '</div>';
				}
				
				// prepare non-reflection
				if( !$useReflection ) $string .= '</div>';
				
				// create linkmap
				if( $useImageMap && (previous || $next) ) {
					$string .= ' <map name="bloglinks">'."\n";					
					if( $previous != null )	$string .= '<area id="area_prev" shape="rect" class="tooltipped bidir" coords="0,0,'.$width2.','.$height.'" title="'.$title_prev.'" rel="prev" href="'. get_permalink($previous->ID) .'">'."\n";
					if( $next!= null ) 		$string .= '<area id="area_next" shape="rect" class="tooltipped bidir" coords="'.$width2.',0,'.($rigth_width+$width2).','.$height.'" title="'.$title_next.'" rel="next" href="'. get_permalink($next->ID) .'">'."\n";
					$string .= '</map>';
				}

			}
			else
			{
				// prepare non-reflection
				if( !$useReflection ) $string .= '<div class="photo" style="'.$sizeStyle.'">';
			
				if( $previous != null ) {				
				
					$string .= '<img title="'.$title_prev.'" id="photo" alt="'. $post->post_title . '" class="photo'.($useReflection? '-with-reflection' : '' ).'" style="'.$sizeStyle.'" src="'. $image_url .'" />';
					
					$string .= '<div id="linkmap">';
					$string .= '<a id="area_prev'.($useImageMap?'_map':'').'" class="tooltipped unidir" style="'.$sizeStyle.'" title="'.$title_prev.'" rel="prev" href="'. get_permalink($previous->ID) .'"></a>';
					$string .= '</div>';
				} else {
					$string .= '<img '.$title_attr.' id="photo" alt="'. $post->post_title . '" class="photo'.($useReflection ? '-with-reflection' : '' ).'" style="width: '.$width.'px; height: '.$height.'px;" src="'. $image_url .'"/>';
				}
				
				// prepare non-reflection
				if( !$useReflection ) $string .= '</div>';
			}

			// action
			do_action(GRAIN_BEFORE_IMAGE);				
			
			// display image
			echo '<div id="photo-'.(grain_fx_can_fade()?'fade':'nofade').'" style="'.$sizeStyle.'">';
			echo $string;				
			echo '</div>';
			
			// action
			do_action(GRAIN_AFTER_IMAGE);
			
			// inject reflection script
			grain_inject_reflec_script('photo');

		} // (!grain_post_has_image() && empty($whoops_url) ) 

		echo '</div>		<!-- <div id="photo-frame"> -->';
		//echo '</div>		<!-- <div id="loading-frame"> -->';	
	}

/* Splitpost */
	
	/**
	 * grain_do_regularpost_logic() - Does the logic for an user-defined post (GRAIN_POSTTYPE_SPLITPOST)
	 *
	 * This function is marked deprecated since it is not fully implemented yet
	 *
	 * @deprecated
	 * @since 0.3
	 * @global $GrainOpt					Grain options
	 */	
	function grain_do_splitpost_logic() {
		global $post;
		
		// prepare post
		$PostOpt = grain_prepare_post_logic();
		
		echo '<div id="special-frame">';
				
		// action
		do_action(GRAIN_BEFORE_PANORAMA);
		
		if( !grain_use_reflection() ) 
			echo '<div class="photo">';
		else
			echo '<div class="photo-with-reflection">';
			
	
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
	}

/* Extended Info */

	/**
	 * grain_is_regular_extendedmode() - Tests whether Grain is in regular extended mode.
	 *
	 * Grain enters "regular" extended mode if extended mode is enabled in the options and the user clicked
	 * the comments/info link or when the user tried to visit a comments page directly.
	 *
	 * @since 0.3
	 * @global $GrainOpt					Grain options
	 * @return bool TRUE if Grain is in regular extended mode
	 */	
	function grain_is_regular_extendedmode() 
	{
		global $GrainOpt;
		return (GRAIN_REQUESTED_EXINFO && $GrainOpt->getYesNo(GRAIN_EXTENDEDINFO_ENABLED)) || GRAIN_REQUESTED_OTEXINFO;
	}

	/**
	 * grain_is_enforced_extendedmode() - Tests whether Grain is in enforced extended mode.
	 *
	 * Enforced extended mode means that the photo's information will be shown without regard to the
	 * extended mode setting in the options.
	 * This may happen if Grain performs a redirect from a direct hit on the comments popup.
	 *
	 * @since 0.3
	 * @global $GrainOpt					Grain options
	 * @return bool TRUE if Grain is in enforced extended mode
	 */	
	function grain_is_enforced_extendedmode() 
	{
		global $GrainOpt;
		return $GrainOpt->getYesNo(GRAIN_CONTENT_ENFORCE_INFO);
	}

	/**
	 * grain_is_extendedmode() - Tests whether Grain is in extended mode.
	 *
	 * Extended mode is enabled if the conditions for regular extended mode
	 * or enforced extended mode are met.
	 *
	 * @since 0.3
	 * @uses grain_is_regular_extendedmode()	Determines if we are in regular extended mode
	 * @uses grain_is_enforced_extendedmode()	Determines if we are in enforced extended mode
	 * @return bool TRUE if Grain is in regular extended mode
	 */	
	function grain_is_extendedmode() 
	{
		if(grain_is_regular_extendedmode()) return true;
		if(grain_is_enforced_extendedmode()) return true;
		return false;
	}

	/**
	 * grain_do_extendedinfo_logic() - Performs the "extended info" logic.
	 *
	 * This renders the extended information block under the photo
	 *
	 * @since 0.3
	 * @global $GrainOpt					Grain options
	 */	
	function grain_do_extendedinfo_logic() 
	{
		global $GrainOpt, $post;
	
		// check for extended info mode
		$extended_mode = grain_is_extendedmode();
		if( !$extended_mode ) return;
		
		
		// some shortcuts
		$is_folded = !grain_is_enforced_extendedmode() || !grain_is_regular_extendedmode();
		$is_unfolded = !$is_folded;

		// test for a subtitle
		$addon = FALSE;	
		if( $GrainOpt->getYesNo(GRAIN_2NDLANG_ENABLED) ) {
			$en_title = get_post_meta($post->ID, $GrainOpt->get(GRAIN_2NDLANG_TAG), true);
			if( $en_title != null && $en_title != $post->post_title ) $addon = TRUE;
		}

		$exif_enabled = grain_has_exif();
		$exif_class = $exif_enabled ? 'exif' : 'no-exif';
		$subtitle_class = $addon ? 'has-subtitle' : 'no-subtitle';

		$has_content = grain_has_content();
		$contentless = !$has_content && !$exif_enabled;
		
		// define CSS classes
		$classes = array();
		if( $contentless ) $classes[] = "contentless";
		if( $exif_enabled ) $classes[] = "has-exif"; else $classes[] = "no-exif";
		if( $has_content ) $classes[] = "has-content"; else $classes[] = "no-content";
		if( $GrainOpt->is(GRAIN_EXIF_RENDER_INLINE) ) $classes[] = "exif-inline"; else $classes[] = "exif-table";
		$class = implode(" ", $classes);
		
		?>

		<div id="info-frame" class="<?php echo $class; ?>"><a name="info"></a>
	
			<h2 id="title" class="<?php echo $subtitle_class; ?>"><?php the_title(); ?></h2>
			<?php if($addon) echo '<h3 id="subtitle">'.$en_title.'</h3>'; ?>
					 
			<div id="content">
					
				<div id="infotext-frame" class="<?php echo $exif_class; ?>">
				<?php 
					if( grain_posttype($post->ID) == GRAIN_POSTTYPE_SPLITPOST )
					{
						// output the basic content
						echo grain_get_the_content();					
					} 
					else 
					{
						// in default mode, so display the content (or it's excerpt)
						if($GrainOpt->is(GRAIN_EXCERPTONLY) || $is_folded) 
						{
							// the_excerpt();
							echo grain_get_the_content();
						} 
						else 
						{
							the_content();
						}
					}
				?>
				</div>

			<?php
				// display EXIF data
				if ($exif_enabled): 
				$class = $GrainOpt->is(GRAIN_EXIF_RENDER_INLINE) ? "inline" : "table";
			?>
				<div id="exif-frame" class="<?php echo $class ; ?>">
				<?php include( TEMPLATEPATH .'/exif-block.php' ); ?>
				</div>
			<?php 	
				endif 
			?>

				<div id="meta" class="<?php echo grain_is_enforced_extendedmode() ? 'enforced' : 'regular'; ?>">

					<span id="edit-post-link"><?php edit_post_link(__("edit post", "grain"), '', ''); ?></span>
					
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

					// now we recheck for extended info mode, since info enforcement could be enabled
					// in which case we don't want to display the comments
					if( grain_is_regular_extendedmode() && grain_can_comment() ) 
					{
						include( TEMPLATEPATH.'/comments.php'); 
					}
	
					// now inject the widget sidebar
					grain_inject_sidebar_footer();
	
					?>
				</div>
			<?php
	}

?>